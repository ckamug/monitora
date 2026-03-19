const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_08-edicao' });


test.describe('Cadastro Acolhido - Edicao', () => {
  test('smoke: abre modo edicao pela URL mascarada apos criar cadastro', async ({ page }) => {
    h.logInicioTeste('[EDICAO] smoke URL mascarada');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO SMOKE');

    await expect(page).toHaveURL(/\/coed\/cadastro-acolhido\/.+/);
    await h.abrirEdicaoPorUrl(page, base.urlEdicao);

    await expect(page.locator('#btnEditar')).toBeVisible();
    await expect(page.locator('#txtNomeCompleto')).toHaveValue(base.resumo.nome);
  });
});

