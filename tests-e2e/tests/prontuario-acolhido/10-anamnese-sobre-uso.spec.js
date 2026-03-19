const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const an = require('./_anamnese_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_10-anamnese-sobre-uso' });

test.describe('Prontuario Acolhido - Anamnese - Sobre o Uso', () => {
  test('registra e recarrega dados da aba sobre o uso', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][ANAMNESE][SOBRE USO] cadastro');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    await an.prepararAcolhidoEmAcolhimentoNoAnamnese(page, browser, token, getAlert, {
      prefixo: `E2E ANAMNESE SU ${token}`,
    });

    await page.click('#sobreUso-tab');
    await expect(page.locator('#tabSobreUso')).toHaveClass(/active|show/);

    const dados = {
      ultimoUso: `ULTIMO USO ${token}`,
      periodoEmergencia: '2019-2020',
      localEmergencia: `LOCAL EMERGENCIA ${token}`,
      motivoSaida: `MOTIVO SAIDA ${token}`,
      localInternacaoCompulsoria: `LOCAL INTERNACAO ${token}`,
      infoCompartilhada: `INFO COMPARTILHADA ${token}`,
    };

    await an.selecionarPrimeiraOpcaoValida(page, '#tabSobreUso #slcDrogas', { invalidos: [''] });
    await page.fill('#tabSobreUso #txtIdadeInicio', '17');
    await page.fill('#tabSobreUso #txtUltimoUso', dados.ultimoUso);
    await page.selectOption('#tabSobreUso #slcContinuaUso', 'Sim');
    await page.click('#tabSobreUso #btnAddSubstancias');
    await expect(page.locator('#tabSobreUso #listaSubstanciasProblema')).toContainText(dados.ultimoUso);

    const selectHistoricoFamilia = page.locator("#tabSobreUso [name='slcHistoricoFamiliar']").first();
    await selectHistoricoFamilia.selectOption('Sim');
    await expect(page.locator('#tabSobreUso #boxHistoricoFamilia')).toBeVisible();
    await page.check('#tabSobreUso #chkFamiliarDrogas1');
    await an.selecionarPrimeiraOpcaoValida(page, '#tabSobreUso #slcPresenciouFamiliar', { invalidos: [''] });

    await an.selecionarPrimeiraOpcaoValida(page, '#tabSobreUso #slcTrajetoria', { invalidos: [''] });
    await an.selecionarPrimeiraOpcaoValida(page, '#tabSobreUso #slcRankeamento', { invalidos: [''] });
    await page.locator('#tabSobreUso #btnAddRanking').first().click();
    await expect
      .poll(async () => page.locator('#tabSobreUso #bosListaRanking .btn-remove-ranking-sobre-uso').count(), {
        timeout: 20_000,
      })
      .toBeGreaterThan(0);

    await page.selectOption('#tabSobreUso #slcServicosEmergencia', 'Sim');
    await expect(page.locator('#tabSobreUso #boxRelacaoAjuda')).toBeVisible();
    await page.fill('#tabSobreUso #txtPeriodoEmergencia', dados.periodoEmergencia);
    await page.fill('#tabSobreUso #txtLocalEmergencia', dados.localEmergencia);
    await page.selectOption('#tabSobreUso #slcAbstinencia', 'Sim');
    await page.fill('#tabSobreUso #txtMotivoSaida', dados.motivoSaida);
    await page.locator('#tabSobreUso #boxRelacaoAjuda #btnAddRanking').first().click();
    await expect
      .poll(async () => page.locator('#tabSobreUso #boxListaAjuda .btn-remove-ajuda-sobre-uso').count(), {
        timeout: 20_000,
      })
      .toBeGreaterThan(0);

    await page.selectOption('#tabSobreUso #slcFezTratamento', 'Sim');
    await page.check('#tabSobreUso #chkTratamentoDependencia1');
    await page.fill('#tabSobreUso #txtVezesTratamento', '2');
    await page.fill('#tabSobreUso #txtTempoTratamento', '1 ano');

    await page.selectOption('#tabSobreUso #slcInternacaoCompulsoria', 'Sim');
    await page.fill('#tabSobreUso #txtVezesInternacaoCompulsoria', '1');
    await page.fill('#tabSobreUso #txtLocalInternacaoCompulsoria', dados.localInternacaoCompulsoria);

    await page.selectOption('#tabSobreUso #slcRecaidaUsoDrogas', 'Sim');
    await page.fill('#tabSobreUso #txtQtdRecaidaUsoDrogas', '3');

    await page.selectOption('#tabSobreUso #slcInternacaoDesintoxicacao', 'Sim');
    await page.fill('#tabSobreUso #txtQtdInternacaoDesintoxicacao', '1');

    await page.check('#tabSobreUso #chkConsequencias17');
    await expect(page.locator('#tabSobreUso #boxRompimentoVinculos')).toBeVisible();
    await page.check('#tabSobreUso #radRompimentoVinculos1');
    await page.fill('#tabSobreUso #txtInformacoesCompartilhadas', dados.infoCompartilhada);

    const waitSalvar = page.waitForResponse(
      (resp) =>
        (resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraSobreUso.php') ||
          resp.url().includes('/public/componentes/prontuario_acolhido/model/editaSobreUso.php')) &&
        resp.request().method() === 'POST'
    );
    const waitCarrega = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/prontuario_acolhido/model/carregaSobreUso.php') &&
        resp.request().method() === 'POST'
    );

    await page.click('#tabSobreUso #btnCadIdentificacao');
    await waitSalvar;
    await waitCarrega;

    await expect(page.locator('#tabSobreUso #listaSubstanciasProblema')).toContainText(dados.ultimoUso);
    await expect(page.locator('#tabSobreUso #boxListaAjuda')).toContainText(dados.localEmergencia);
    await expect(page.locator('#tabSobreUso #chkFamiliarDrogas1')).toBeChecked();
    await expect(page.locator('#tabSobreUso #chkTratamentoDependencia1')).toBeChecked();
    await expect(page.locator('#tabSobreUso #chkConsequencias17')).toBeChecked();
    await expect(page.locator('#tabSobreUso #radRompimentoVinculos1')).toBeChecked();
    await expect(page.locator('#tabSobreUso #txtInformacoesCompartilhadas')).toHaveValue(dados.infoCompartilhada);
    await expect(page.locator('#tabSobreUso #txtLocalInternacaoCompulsoria')).toHaveValue(
      dados.localInternacaoCompulsoria
    );
  });
});

