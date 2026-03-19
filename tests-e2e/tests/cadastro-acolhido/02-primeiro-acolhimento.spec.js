const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_02-primeiro-acolhimento' });


test.describe('Cadastro Acolhido - Primeiro Acolhimento', () => {
  test('[UI] alterna exibicao de "Quantas vezes" (Nao -> Sim)', async ({ page }) => {
    h.logInicioTeste('[PRIMEIRO ACOLHIMENTO][UI] toggle quantas vezes');

    await h.abrirCadastroNovo(page);
    await h.preencherPrimeiroAcolhimento(page, { primeiraVez: false, reincidencia: 2 });

    await expect(page.locator('#radAcolhimento2')).toBeChecked();
    await expect(page.locator('#boxQuantasVezes')).toBeVisible();

    await h.preencherPrimeiroAcolhimento(page, { primeiraVez: true });

    await expect(page.locator('#radAcolhimento1')).toBeChecked();
    await expect(page.locator('#boxQuantasVezes')).toBeHidden();
  });

  test('caminho SIM (oculta quantas vezes)', async ({ page }) => {
    h.logInicioTeste('[PRIMEIRO ACOLHIMENTO] SIM');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E ACOLHIMENTO SIM');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherPrimeiroAcolhimento(page, { primeiraVez: true });
    await h.preencherQuestionarioMinimoSeguro(page, {
      acolhimento: { primeiraVez: true },
    });
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page.locator('#radAcolhimento1')).toBeChecked();
    await expect(page.locator('#boxQuantasVezes')).toBeHidden();
    await expect(page.locator('#txtReincidencia')).toHaveValue('');
  });

  test('caminho NAO (libera e persiste quantas vezes)', async ({ page }) => {
    h.logInicioTeste('[PRIMEIRO ACOLHIMENTO] NAO');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E ACOLHIMENTO NAO');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherPrimeiroAcolhimento(page, { primeiraVez: false, reincidencia: 3 });
    await h.preencherQuestionarioMinimoSeguro(page, {
      acolhimento: { primeiraVez: false, reincidencia: 3 },
    });
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page.locator('#radAcolhimento2')).toBeChecked();
    await expect(page.locator('#boxQuantasVezes')).toBeVisible();
    await expect(page.locator('#txtReincidencia')).toHaveValue('3');
  });
});
