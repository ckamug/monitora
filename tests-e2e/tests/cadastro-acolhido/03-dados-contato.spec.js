const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_03-dados-contato' });


test.describe('Cadastro Acolhido - Dados de Contato', () => {
  test('persiste telefone pessoal e residencial', async ({ page }) => {
    h.logInicioTeste('[DADOS CONTATO] persistencia');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E DADOS CONTATO');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherContatosTelefonicos(page, {
      pessoal: '11987651234',
      residencial: '1133445566',
    });
    await h.preencherQuestionarioMinimoSeguro(page);
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page.locator('#txtTelefonePessoal')).toHaveValue('(11)98765-1234');
    await expect(page.locator('#txtTelefoneResidencial')).toHaveValue('(11)3344-5566');
  });
});

