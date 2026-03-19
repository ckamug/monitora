const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_11-edicao-dados-contato' });


test.describe('Cadastro Acolhido - Edicao - Dados de Contato', () => {
  test('edita e persiste telefone pessoal e residencial', async ({ page }) => {
    h.logInicioTeste('[EDICAO][DADOS CONTATO] persistencia');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO DADOS CONTATO');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await h.preencherContatosTelefonicos(page, {
      pessoal: '11981234567',
      residencial: '1133221100',
    });
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    h.logTabela('[E2E][EDICAO][DADOS CONTATO] retorno apos salvar', {
      url: page.url(),
      telefonePessoal: await page.locator('#txtTelefonePessoal').inputValue(),
      telefoneResidencial: await page.locator('#txtTelefoneResidencial').inputValue(),
    });

    await expect(page.locator('#txtTelefonePessoal')).toHaveValue('(11)98123-4567');
    await expect(page.locator('#txtTelefoneResidencial')).toHaveValue('(11)3322-1100');
  });
});

