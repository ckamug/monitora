const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_04-contatos-referencia' });


test.describe('Cadastro Acolhido - Contatos de Referencia', () => {
  test('um unico contato: campos podem ser preenchidos sem clicar em + (nao trava cadastro)', async ({
    page,
  }) => {
    h.logInicioTeste('[CONTATO REF] 1 contato sem +');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E CONTATO REF 1');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherCamposContatoReferenciaSemAdicionar(page, {
      nome: 'CONTATO UNICO SEM MAIS',
      telefone: '11911112222',
      parentesco: 'Pai',
    });
    await h.preencherQuestionarioMinimoSeguro(page);
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page).toHaveURL(/\/coed\/cadastro-acolhido\/.+/);
  });

  test('[BUG] multiplos contatos via + antes de salvar', async ({ page }) => {
    h.logInicioTeste('[BUG][CONTATO REF] + antes de salvar');

    await h.abrirCadastroNovo(page);
    await h.preencherContatosTelefonicos(page);

    await h.adicionarContatoReferencia(
      page,
      { nome: 'CONTATO BUG 1', telefone: '11977778888', parentesco: 'Pai' },
      { timeoutMs: 5000 }
    );
    await h.adicionarContatoReferencia(
      page,
      { nome: 'CONTATO BUG 2', telefone: '11988889999', parentesco: 'Vizinhos' },
      { timeoutMs: 5000 }
    );

    await expect(page.locator('#boxContatosReferencia')).toContainText('CONTATO BUG 1');
    await expect(page.locator('#boxContatosReferencia')).toContainText('CONTATO BUG 2');
  });
});
