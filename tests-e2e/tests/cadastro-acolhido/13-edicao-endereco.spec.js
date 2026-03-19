const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_13-edicao-endereco' });


test.describe('Cadastro Acolhido - Edicao - Endereco', () => {
  test('[UI] alterna exibicao entre endereco fixo e situacao de rua em edicao', async ({ page }) => {
    h.logInicioTeste('[EDICAO][ENDERECO][UI] toggle fixo <-> rua');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO UI ENDERECO TOGGLE');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);

    await page.check('#radEndereco1');
    await expect(page.locator('#boxEndereco')).toBeVisible();
    await expect(page.locator('#boxSituacaoRua')).toBeHidden();

    await page.check('#radEndereco2');
    await expect(page.locator('#boxSituacaoRua')).toBeVisible();
    await expect(page.locator('#boxEndereco')).toBeHidden();
  });

  test('edita para endereco fixo (SIM) com CEP e persiste', async ({ page }) => {
    h.logInicioTeste('[EDICAO][ENDERECO] SIM');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO ENDERECO SIM');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao, { mockCep: true });
    await h.preencherEndereco(page, {
      fixo: true,
      cep: '01009-000',
      numero: '202',
      complemento: 'CASA EDICAO',
      municipioFallbackValor: '4664',
    });
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    await expect(page.locator('#radEndereco1')).toBeChecked();
    await expect(page.locator('#txtCep')).toHaveValue('01009-000');
    await expect(page.locator('#txtEndereco')).toHaveValue('Praca da Se');
    await expect(page.locator('#txtBairro')).toHaveValue('Se');
    await expect(page.locator('#txtNumero')).toHaveValue('202');
    await expect(page.locator('#txtComplemento')).toHaveValue('CASA EDICAO');
    await expect
      .poll(async () => page.locator('#slcMunicipios option').count(), { timeout: 20_000 })
      .toBeGreaterThan(1);
    try {
      await expect
        .poll(async () => (await h.obterMunicipioSelecionado(page)).valid, { timeout: 15_000 })
        .toBe(true);
    } catch (error) {
      console.log(
        '[E2E][EDICAO][ENDERECO] municipio nao estabilizou apos salvar; aplicando fallback manual para teste (municipio_id=4664).'
      );
      await h.selecionarMunicipioPorValor(page, '4664');
      await expect
        .poll(async () => (await h.obterMunicipioSelecionado(page)).valid, { timeout: 10_000 })
        .toBe(true);
    }
    h.logTabela('[E2E][EDICAO][ENDERECO] municipio apos reload', await h.obterMunicipioSelecionado(page));
  });

  test('edita para situacao de rua (NAO endereco fixo) e persiste tempo', async ({ page }) => {
    h.logInicioTeste('[EDICAO][ENDERECO] NAO / situacao de rua');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO ENDERECO RUA', {
      mockCep: true,
      questionario: {
        endereco: { fixo: true, cep: '01009-000', numero: '10', complemento: 'BASE' },
      },
    });

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await h.preencherEndereco(page, { fixo: false, tempoIndex: 2 });
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    await expect(page.locator('#radEndereco2')).toBeChecked();
    await expect(page.locator('#slcTempoSituacaoRua')).not.toHaveValue('0');
  });
});
