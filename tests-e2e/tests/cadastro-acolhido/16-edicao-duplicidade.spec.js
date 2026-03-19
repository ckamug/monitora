const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_16-edicao-duplicidade' });


async function criarDoisCadastrosParaDuplicidade(page) {
  const a = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO DUP A');
  const b = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO DUP B');
  return { a, b };
}

test.describe('Cadastro Acolhido - Edicao - Duplicidade (CPF/NIS)', () => {
  test('preencher CPF de outra pessoa em edicao redireciona para o cadastro da outra pessoa', async ({
    page,
  }) => {
    h.logInicioTeste('[EDICAO][DUPLICIDADE] CPF de outra pessoa');
    const { a, b } = await criarDoisCadastrosParaDuplicidade(page);
    const getAlert = await h.capturarAlert(page);

    await h.abrirEdicaoPorUrl(page, a.urlEdicao);
    await page.fill('#txtCpf', b.resumo.cpf);
    await page.click('#txtNomeCompleto');

    await expect.poll(getAlert).toContain('Pessoa ja cadastrada');
    await page.waitForURL(b.urlEdicao);
    await expect(page.locator('#txtCpf')).toHaveValue(b.resumo.cpf);

    h.logTabela('[E2E][EDICAO][DUPLICIDADE][CPF] redirecionamento', {
      origem: a.urlEdicao,
      destino: page.url(),
      cpfDigitado: b.resumo.cpf,
    });
  });

  test('preencher NIS de outra pessoa em edicao redireciona para o cadastro da outra pessoa', async ({
    page,
  }) => {
    h.logInicioTeste('[EDICAO][DUPLICIDADE] NIS de outra pessoa');
    const { a, b } = await criarDoisCadastrosParaDuplicidade(page);
    const getAlert = await h.capturarAlert(page);

    await h.abrirEdicaoPorUrl(page, a.urlEdicao);
    await page.fill('#txtNis', b.resumo.nis);
    await page.click('#txtNomeCompleto');

    await expect.poll(getAlert).toContain('Pessoa ja cadastrada');
    await page.waitForURL(b.urlEdicao);
    await expect(page.locator('#txtNis')).toHaveValue(b.resumo.nis);

    h.logTabela('[E2E][EDICAO][DUPLICIDADE][NIS] redirecionamento', {
      origem: a.urlEdicao,
      destino: page.url(),
      nisDigitado: b.resumo.nis,
    });
  });

  test('preencher o proprio CPF/NIS em edicao nao redireciona', async ({ page }) => {
    h.logInicioTeste('[EDICAO][DUPLICIDADE] proprio CPF/NIS nao redireciona');
    const a = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO DUP SELF');
    const getAlert = await h.capturarAlert(page);

    await h.abrirEdicaoPorUrl(page, a.urlEdicao);
    await page.fill('#txtCpf', a.resumo.cpf);
    await page.click('#txtNomeCompleto');
    await expect.poll(() => page.url(), { timeout: 1500 }).toBe(a.urlEdicao);

    await page.fill('#txtNis', a.resumo.nis);
    await page.click('#txtNomeCompleto');
    await expect.poll(() => page.url(), { timeout: 1500 }).toBe(a.urlEdicao);

    // Se houve alerta anterior de criacao, garante que nao virou alerta de duplicidade neste passo.
    await expect.poll(() => getAlert()).not.toContain('Pessoa ja cadastrada');
  });
});
