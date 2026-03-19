const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_05-endereco' });


test.describe('Cadastro Acolhido - Endereco', () => {
  test('[UI] alterna exibicao entre endereco fixo e situacao de rua', async ({ page }) => {
    h.logInicioTeste('[ENDERECO][UI] toggle fixo <-> rua');

    await h.abrirCadastroNovo(page);

    await page.check('#radEndereco1');
    await expect(page.locator('#boxEndereco')).toBeVisible();
    await expect(page.locator('#boxSituacaoRua')).toBeHidden();

    await page.check('#radEndereco2');
    await expect(page.locator('#boxSituacaoRua')).toBeVisible();
    await expect(page.locator('#boxEndereco')).toBeHidden();

    await page.check('#radEndereco1');
    await expect(page.locator('#boxEndereco')).toBeVisible();
    await expect(page.locator('#boxSituacaoRua')).toBeHidden();
  });

  test('endereco fixo (SIM) com CEP preenche endereco e persiste', async ({ page }) => {
    h.logInicioTeste('[ENDERECO] SIM');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page, { mockCep: true });
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E ENDERECO SIM');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page, {
      endereco: {
        fixo: true,
        cep: '01009-000',
        numero: '101',
        complemento: 'CASA TESTE',
        municipioFallbackValor: '4664',
      },
    });
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page.locator('#radEndereco1')).toBeChecked();
    await expect(page.locator('#txtCep')).toHaveValue('01009-000');
    await expect(page.locator('#txtEndereco')).toHaveValue('Praca da Se');
    await expect(page.locator('#txtBairro')).toHaveValue('Se');
    await expect(page.locator('#txtNumero')).toHaveValue('101');
    await expect(page.locator('#txtComplemento')).toHaveValue('CASA TESTE');
    await expect.poll(async () => (await h.obterMunicipioSelecionado(page)).valid).toBe(true);
  });

  test('situacao de rua (NAO endereco fixo) persiste tempo', async ({ page }) => {
    h.logInicioTeste('[ENDERECO] NAO / situacao de rua');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E ENDERECO RUA');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page, {
      endereco: { fixo: false, tempoIndex: 2 },
    });
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page.locator('#radEndereco2')).toBeChecked();
    await expect(page.locator('#slcTempoSituacaoRua')).not.toHaveValue('0');
  });
});
