const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const an = require('./_anamnese_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_11-anamnese-medicacao' });

test.describe('Prontuario Acolhido - Anamnese - Medicacao', () => {
  test('registra medicacao na aba medicacao', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][ANAMNESE][MEDICACAO] cadastro');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    await an.prepararAcolhidoEmAcolhimentoNoAnamnese(page, browser, token, getAlert, {
      prefixo: `E2E ANAMNESE MED ${token}`,
    });

    await page.click('#medicacao-tab');
    await expect(page.locator('#tabMedicacao')).toHaveClass(/active|show/);

    const dados = {
      dataRegistro: an.ymdHoje(),
      nomeMedicacao: `MEDICACAO ${token}`,
      dosagem: `DOSAGEM ${token}`,
      prescricao: `PRESCRICAO ${token}`,
      tempoUso: `TEMPO USO ${token}`,
      unidadeSaude: `UNIDADE ${token}`,
      observacoes: `OBSERVACAO ${token}`,
    };

    await page.locator('#tabMedicacao #boxBotaoNovaMedicacao button').first().click();
    await expect(page.locator('#tabMedicacao #colMedicacao')).toHaveClass(/show/);

    await page.fill('#tabMedicacao #txtDataMedicacaoRegistro', dados.dataRegistro);
    await page.fill('#tabMedicacao #txtNomeMedicacao', dados.nomeMedicacao);
    await page.fill('#tabMedicacao #txtDosagemMedicacao', dados.dosagem);
    await page.fill('#tabMedicacao #txtPrescricaoMedicacao', dados.prescricao);
    await page.fill('#tabMedicacao #txtTempoUsoMedicacao', dados.tempoUso);
    await page.fill('#tabMedicacao #txtUnidadeSaudeMedicacao', dados.unidadeSaude);
    await page.fill('#tabMedicacao #txtObservacoesMedicacao', dados.observacoes);

    const waitSalvar = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraMedicacao.php') &&
        resp.request().method() === 'POST'
    );
    const waitLista = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/prontuario_acolhido/model/listaMedicacoes.php') &&
        resp.request().method() === 'POST'
    );

    await page.click('#tabMedicacao #btnCadMedicacao');
    await waitSalvar;
    await waitLista;

    await expect(page.locator('#tabMedicacao #boxListaMedicacoes')).toContainText(dados.nomeMedicacao);
    await expect(page.locator('#tabMedicacao #boxListaMedicacoes')).toContainText(dados.dosagem);
    await expect(page.locator('#tabMedicacao #boxListaMedicacoes')).toContainText(dados.unidadeSaude);
  });
});

