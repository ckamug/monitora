const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const an = require('./_anamnese_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_07-anamnese-historico-social' });

test.describe('Prontuario Acolhido - Anamnese - Historico Social', () => {
  test('registra e recarrega dados da aba historico social', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][ANAMNESE][HISTORICO SOCIAL] cadastro');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    await an.prepararAcolhidoEmAcolhimentoNoAnamnese(page, browser, token, getAlert, {
      prefixo: `E2E ANAMNESE HS ${token}`,
    });

    await page.click('#historicoSocial-tab');
    await expect(page.locator('#tabHistoricoSocial')).toHaveClass(/active|show/);

    await an.aguardarOpcoesSelect(page, '#tabHistoricoSocial #slcGrauEscolaridade');
    await an.aguardarOpcoesSelect(page, '#tabHistoricoSocial #slcUfEscola');
    await an.aguardarOpcoesSelect(page, '#tabHistoricoSocial #slcTrabalhoPrincipal');
    await expect
      .poll(async () => page.locator("#tabHistoricoSocial input[name='radCostumaDormir']").count(), { timeout: 20_000 })
      .toBeGreaterThan(0);
    await expect
      .poll(async () => page.locator("#tabHistoricoSocial input[name='chkReferenciada[]']").count(), { timeout: 20_000 })
      .toBeGreaterThan(0);

    const dados = {
      nomeEscola: `ESCOLA ${token}`,
      situacaoRua: `SITUACAO RUA ${token}`,
      outraAtividade: `OUTRA ATIVIDADE ${token}`,
      qualificacao: `QUALIFICACAO ${token}`,
      ajudaDoacao: '123,45',
      aposentadoria: '234,56',
      seguroDesemprego: '345,67',
      pensao: '456,78',
      outrasFontes: '567,89',
    };

    await page.check('#tabHistoricoSocial #radSabeLer1');
    await page.check('#tabHistoricoSocial #radFrequentouEscola1');

    const grauEscolaridade = await an.selecionarPrimeiraOpcaoValida(page, '#tabHistoricoSocial #slcGrauEscolaridade');
    let anoSerie = null;
    try {
      await an.aguardarOpcoesSelect(page, '#tabHistoricoSocial #slcAnoSerie', { timeout: 15_000 });
      anoSerie = await an.selecionarPrimeiraOpcaoValida(page, '#tabHistoricoSocial #slcAnoSerie', {
        timeout: 15_000,
      });
    } catch (_) {
      anoSerie = null;
    }
    const ufEscola = await an.selecionarPrimeiraOpcaoValida(page, '#tabHistoricoSocial #slcUfEscola');
    let municipioEscola = null;
    try {
      municipioEscola = await an.selecionarPrimeiraOpcaoValida(page, '#tabHistoricoSocial #slcMunicipioEscola', {
        timeout: 15_000,
      });
    } catch (_) {
      municipioEscola = null;
    }

    await page.fill('#tabHistoricoSocial #txtNomeEscola', dados.nomeEscola);

    const costumaDormirValor = await an.selecionarPrimeiroRadioComFallback(
      page,
      "#tabHistoricoSocial input[name='radCostumaDormir'][data-exibe-situacao-rua='1']",
      "#tabHistoricoSocial input[name='radCostumaDormir']"
    );

    let tempoMoradia = null;
    let rotinaValor = '';
    if (await page.locator('#tabHistoricoSocial #boxTempoMoradia').isVisible()) {
      tempoMoradia = await an.selecionarPrimeiraOpcaoValida(page, '#tabHistoricoSocial #slcTempoMoradia');
      rotinaValor = await an.selecionarPrimeiroRadio(page, "#tabHistoricoSocial input[name='radRotina']");
    }

    let tempoSituacaoRua = null;
    let motivoRuaValor = '';
    if (await page.locator('#tabHistoricoSocial #boxSituacaoRua').isVisible()) {
      tempoSituacaoRua = await an.selecionarPrimeiraOpcaoValida(page, '#tabHistoricoSocial #slcTempoSituacaoRua');
      await page.fill('#tabHistoricoSocial #txtEstavaSituacaoRua', dados.situacaoRua);
      motivoRuaValor = await an.selecionarPrimeiroCheckbox(page, "#tabHistoricoSocial input[name='chkMotivosRua[]']");
    }

    await page.check('#tabHistoricoSocial #radAtividadeRemunerada1');
    const trabalhoPrincipal = await an.selecionarPrimeiraOpcaoValida(page, '#tabHistoricoSocial #slcTrabalhoPrincipal');

    if (await page.locator('#tabHistoricoSocial #boxOutraAtividade').isVisible()) {
      await page.fill('#tabHistoricoSocial #txtOutroTrabalhoPrincipal', dados.outraAtividade);
    }

    await page.fill('#tabHistoricoSocial #txtAjudaDoacao', dados.ajudaDoacao);
    await page.fill('#tabHistoricoSocial #txtAposentadoria', dados.aposentadoria);
    await page.fill('#tabHistoricoSocial #txtSeguroDesemprego', dados.seguroDesemprego);
    await page.fill('#tabHistoricoSocial #txtPensao', dados.pensao);
    await page.fill('#tabHistoricoSocial #txtOutrasdFontes', dados.outrasFontes);

    await page.check('#tabHistoricoSocial #radPrecisaQualificacao1');
    await page.fill('#tabHistoricoSocial #txtQualQualificacao', dados.qualificacao);

    const referenciadaValor = await an.selecionarPrimeiroCheckbox(page, "#tabHistoricoSocial input[name='chkReferenciada[]']");

    const waitSalvar = page.waitForResponse(
      (resp) =>
        (resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraHistoricoSocial.php') ||
          resp.url().includes('/public/componentes/prontuario_acolhido/model/editaHistoricoSocial.php')) &&
        resp.request().method() === 'POST'
    );
    const waitCarrega = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/prontuario_acolhido/model/carregaHistoricoSocial.php') &&
        resp.request().method() === 'POST'
    );

    await page.click('#tabHistoricoSocial #btnCadIdentificacao');
    await waitSalvar;
    await waitCarrega;

    await expect(page.locator('#tabHistoricoSocial #radSabeLer1')).toBeChecked();
    await expect(page.locator('#tabHistoricoSocial #radFrequentouEscola1')).toBeChecked();
    await expect(page.locator('#tabHistoricoSocial #radAtividadeRemunerada1')).toBeChecked();
    await expect(page.locator('#tabHistoricoSocial #radPrecisaQualificacao1')).toBeChecked();

    await expect(page.locator('#tabHistoricoSocial #txtNomeEscola')).toHaveValue(dados.nomeEscola);
    await expect(page.locator('#tabHistoricoSocial #txtQualQualificacao')).toHaveValue(dados.qualificacao);

    await expect(page.locator('#tabHistoricoSocial #slcGrauEscolaridade')).toHaveValue(grauEscolaridade.value);
    await expect(page.locator('#tabHistoricoSocial #slcUfEscola')).toHaveValue(ufEscola.value);
    await expect(page.locator('#tabHistoricoSocial #slcTrabalhoPrincipal')).toHaveValue(trabalhoPrincipal.value);
    if (anoSerie && anoSerie.value) {
      await expect(page.locator('#tabHistoricoSocial #slcAnoSerie')).toHaveValue(anoSerie.value);
    }

    if (municipioEscola && municipioEscola.value) {
      await expect(page.locator('#tabHistoricoSocial #slcMunicipioEscola')).toHaveValue(municipioEscola.value);
    }
    if (tempoMoradia && tempoMoradia.value) {
      await expect(page.locator('#tabHistoricoSocial #slcTempoMoradia')).toHaveValue(tempoMoradia.value);
    }
    if (tempoSituacaoRua && tempoSituacaoRua.value) {
      await expect(page.locator('#tabHistoricoSocial #slcTempoSituacaoRua')).toHaveValue(tempoSituacaoRua.value);
      await expect(page.locator('#tabHistoricoSocial #txtEstavaSituacaoRua')).toHaveValue(dados.situacaoRua);
    }
    if (motivoRuaValor) {
      await expect(page.locator(`#tabHistoricoSocial input[name='chkMotivosRua[]'][value='${motivoRuaValor}']`)).toBeChecked();
    }
    if (rotinaValor) {
      await expect
        .poll(() => an.valorRadioSelecionado(page, "#tabHistoricoSocial input[name='radRotina']"))
        .toBe(rotinaValor);
    }

    await expect
      .poll(() => an.valorRadioSelecionado(page, "#tabHistoricoSocial input[name='radCostumaDormir']"))
      .toBe(costumaDormirValor);
    await expect(
      page.locator(`#tabHistoricoSocial input[name='chkReferenciada[]'][value='${referenciadaValor}']`)
    ).toBeChecked();
  });
});
