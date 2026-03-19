const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_10-edicao-primeiro-acolhimento' });


test.describe('Cadastro Acolhido - Edicao - Primeiro Acolhimento', () => {
  test('[UI] alterna exibicao de "Quantas vezes" em edicao (Nao -> Sim)', async ({ page }) => {
    h.logInicioTeste('[EDICAO][PRIMEIRO ACOLHIMENTO][UI] toggle quantas vezes');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO UI ACOLHIMENTO TOGGLE', {
      questionario: { acolhimento: { primeiraVez: false, reincidencia: 4 } },
    });

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await expect(page.locator('#radAcolhimento2')).toBeChecked();
    await expect(page.locator('#boxQuantasVezes')).toBeVisible();

    await h.preencherPrimeiroAcolhimento(page, { primeiraVez: true });
    await expect(page.locator('#radAcolhimento1')).toBeChecked();
    await expect(page.locator('#boxQuantasVezes')).toBeHidden();
  });

  test('edita para caminho SIM (oculta quantas vezes)', async ({ page }) => {
    h.logInicioTeste('[EDICAO][PRIMEIRO ACOLHIMENTO] SIM');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO ACOLHIMENTO SIM', {
      questionario: { acolhimento: { primeiraVez: false, reincidencia: 4 } },
    });

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await h.preencherPrimeiroAcolhimento(page, { primeiraVez: true });
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    await expect(page.locator('#radAcolhimento1')).toBeChecked();
    await expect(page.locator('#boxQuantasVezes')).toBeHidden();
    await expect(page.locator('#txtReincidencia')).toHaveValue('');
  });

  test('edita para caminho NAO (libera e persiste quantas vezes)', async ({ page }) => {
    h.logInicioTeste('[EDICAO][PRIMEIRO ACOLHIMENTO] NAO');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO ACOLHIMENTO NAO');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await h.preencherPrimeiroAcolhimento(page, { primeiraVez: false, reincidencia: 3 });
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    await expect(page.locator('#radAcolhimento2')).toBeChecked();
    await expect(page.locator('#boxQuantasVezes')).toBeVisible();
    await expect(page.locator('#txtReincidencia')).toHaveValue('3');
  });
});
