const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_15-edicao-historico' });


test.describe('Cadastro Acolhido - Edicao - Historico', () => {
  test('edita e persiste texto do historico', async ({ page }) => {
    h.logInicioTeste('[EDICAO][HISTORICO] persistencia');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO HISTORICO');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    const textoHistorico = `Historico em edicao E2E ${h.gerarSufixoUnico()}`;
    await h.preencherHistorico(page, textoHistorico);
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    await expect(page.locator('#txtHistorico')).toHaveValue(textoHistorico);
  });
});

