const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_07-historico' });


test.describe('Cadastro Acolhido - Historico', () => {
  test('persiste texto do historico', async ({ page }) => {
    h.logInicioTeste('[HISTORICO] persistencia');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E HISTORICO');
    const textoHistorico = `Historico E2E ${h.gerarSufixoUnico()}`;

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page, { historico: textoHistorico });
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page.locator('#txtHistorico')).toHaveValue(textoHistorico);
  });
});

