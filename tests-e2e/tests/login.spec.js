const { test, expect } = require('@playwright/test');
const { habilitarAuditoria } = require('./_audit');

habilitarAuditoria(test, { funcionalidade: 'login' });


async function abrirLogin(page) {
  await page.goto('login', { waitUntil: 'domcontentloaded' });
  await expect(page.locator('#txtLogin')).toBeVisible();
}

async function capturarAlert(page) {
  let alertText = '';
  page.on('dialog', async (dialog) => {
    alertText = dialog.message();
    await dialog.accept();
  });
  return () => alertText;
}

async function irParaEtapaSenha(page, cpf) {
  await abrirLogin(page);
  await page.fill('#txtLogin', cpf);
  await page.click('#btnStep');
  await expect(page.locator('#txtSenha')).toBeVisible();
  await expect(page.locator('#btnAcessar')).toBeVisible();
}

test.describe('Login COED (caixa preta)', () => {
  test('CPF invalido mostra alerta e nao avanca', async ({ page }) => {
    const getAlert = await capturarAlert(page);

    await abrirLogin(page);
    await page.fill('#txtLogin', '111.111.111-11');
    await page.click('#btnStep');

    await expect.poll(getAlert).toContain('CPF Inv');
    await expect(page.locator('#txtSenha')).toBeHidden();
  });

  test('CPF valido + senha invalida', async ({ page }) => {
    const cpf = process.env.E2E_CPF;
    test.skip(!cpf, 'Defina E2E_CPF');

    const getAlert = await capturarAlert(page);

    await irParaEtapaSenha(page, cpf);
    await page.fill('#txtSenha', 'senha-errada');
    await page.click('#btnAcessar');

    await expect.poll(getAlert).toContain('CPF ou Senha');
  });

  test('CPF valido + senha valida', async ({ page }) => {
    const cpf = process.env.E2E_CPF;
    const senha = process.env.E2E_SENHA;
    test.skip(!cpf || !senha, 'Defina E2E_CPF e E2E_SENHA');

    await irParaEtapaSenha(page, cpf);
    await page.fill('#txtSenha', senha);
    await page.click('#btnAcessar');

    await expect.poll(async () => {
      const urlMudou = !page.url().includes('/coed/login');
      const loginOculto = await page
        .locator('#boxCamposLogin')
        .evaluate((el) => el.classList.contains('d-none'));
      return urlMudou || loginOculto;
    }).toBe(true);
  });

  test('CPF de primeiro acesso abre fluxo Criar Senha', async ({ page }) => {
    const cpfPrimeiroAcesso = process.env.E2E_CPF_PRIMEIRO_ACESSO;
    test.skip(!cpfPrimeiroAcesso, 'Defina E2E_CPF_PRIMEIRO_ACESSO');

    await abrirLogin(page);
    await page.fill('#txtLogin', cpfPrimeiroAcesso);
    await page.click('#btnStep');

    await expect(page.locator('#boxInicial')).toBeVisible();
    await expect(page.locator('#txtCriaSenha')).toBeVisible();
    await expect(page.locator('#txtRepeteSenha')).toBeVisible();
    await expect(page.locator('#btnAcessar')).toHaveText(/Criar Senha/i);
  });
});
