const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_06-saude' });


test.describe('Cadastro Acolhido - Saude', () => {
  test('comorbidade Outra + substancia Outra + unidade hospitalar Outra', async ({ page }) => {
    h.logInicioTeste('[SAUDE] outras + hospital outra');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E SAUDE OUTRA');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page, {
      comorbidade: { outra: true, outraTexto: 'Comorbidade Outra E2E' },
      deficiencia: { tem: false },
      substancia: { outra: true, outraTexto: 'Substancia Outra E2E', baseId: '#chkSubstanciaPreferencia2' },
      unidadeHospitalar: { status: 'SIM', tipo: 'OUTRA', outraTexto: 'Hospital Outra E2E' },
    });
    await h.salvarCadastroComSucesso(page, getAlert);

    await h.assertCheckbox(page, '#chkComorbidade11', true);
    await expect(page.locator('#txtOutraComorbidade')).toHaveValue('Comorbidade Outra E2E');
    await expect(page.locator('#radDeficiencia2')).toBeChecked();
    await h.assertCheckbox(page, '#chkSubstanciaPreferencia12', true);
    await expect(page.locator('#txtOutraSubstanciaPreferencia')).toHaveValue('Substancia Outra E2E');
    await expect(page.locator('#radUnidadeHospitalar1')).toBeChecked();
    await expect(page.locator('#slcUnidadeHospitalar')).toHaveValue('Outra');
    await expect(page.locator('#txtOutraUnidadeHospitalar')).toHaveValue('Hospital Outra E2E');
  });

  test('caminhos NAO (comorbidade, deficiencia, unidade hospitalar)', async ({ page }) => {
    h.logInicioTeste('[SAUDE] caminhos NAO');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E SAUDE NAO');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page, {
      comorbidade: { outra: false },
      deficiencia: { tem: false },
      substancia: { outra: false, baseId: '#chkSubstanciaPreferencia4' },
      unidadeHospitalar: { status: 'NAO' },
    });
    await h.salvarCadastroComSucesso(page, getAlert);

    await h.assertCheckbox(page, '#chkComorbidade12', true);
    await expect(page.locator('#radDeficiencia2')).toBeChecked();
    await h.assertCheckbox(page, '#chkSubstanciaPreferencia4', true);
    await expect(page.locator('#radUnidadeHospitalar2')).toBeChecked();
  });

  test('[UI] deficiencia: alterna exibicao do bloco (Sim -> Nao -> Sim)', async ({ page }) => {
    h.logInicioTeste('[SAUDE][UI] deficiencia toggle');

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E UI DEFICIENCIA TOGGLE');
    await h.preencherDadosBasicos(page, dados);

    await h.preencherDeficiencia(page, {
      tem: true,
      tipoIds: ['#chkDeficiencia5'],
      cuidadoIds: ['#chkCuidadosTerceiros3'],
    });
    await expect(page.locator('#boxDeficiencia')).toBeVisible();

    await page.check('#radDeficiencia2');
    await expect(page.locator('#boxDeficiencia')).toBeHidden();

    await page.check('#radDeficiencia1');
    await expect(page.locator('#boxDeficiencia')).toBeVisible();
  });

  test('unidade hospitalar SIM (lista) persiste opcao selecionada', async ({ page }) => {
    h.logInicioTeste('[SAUDE] hospital SIM lista');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E SAUDE HOSPITAL LISTA');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page, {
      deficiencia: { tem: false },
      unidadeHospitalar: { status: 'SIM', tipo: 'PADRAO', valor: 'Hospital Lacan' },
    });
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page.locator('#radUnidadeHospitalar1')).toBeChecked();
    await expect(page.locator('#slcUnidadeHospitalar')).toHaveValue('Hospital Lacan');
  });

  test('[UI] comorbidade: trocar de Outra para Nao oculta box Outra', async ({ page }) => {
    h.logInicioTeste('[SAUDE][UI] comorbidade Outra -> Nao');

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E UI COMORBIDADE TOGGLE');
    await h.preencherDadosBasicos(page, dados);

    await h.preencherComorbidades(page, { outra: true, outraTexto: 'Comorbidade Toggle UI' });
    await expect(page.locator('#boxTipoAcompanhamento')).toBeVisible();
    await expect(page.locator('#txtOutraComorbidade')).toHaveValue('Comorbidade Toggle UI');

    await page.check('#chkComorbidade12');
    await page.uncheck('#chkComorbidade11');
    await expect(page.locator('#boxTipoAcompanhamento')).toBeHidden();
  });

  test('[UI] substancia: desmarcar Outra oculta box Outra', async ({ page }) => {
    h.logInicioTeste('[SAUDE][UI] substancia desmarca Outra');

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E UI SUBSTANCIA TOGGLE');
    await h.preencherDadosBasicos(page, dados);

    await h.preencherSubstanciaPreferencia(page, {
      outra: true,
      baseId: '#chkSubstanciaPreferencia2',
      outraTexto: 'Substancia Toggle UI',
    });
    await expect(page.locator('#boxOutraSubstanciaPreferencia')).toBeVisible();
    await expect(page.locator('#txtOutraSubstanciaPreferencia')).toHaveValue('Substancia Toggle UI');

    await page.uncheck('#chkSubstanciaPreferencia12');
    await expect(page.locator('#boxOutraSubstanciaPreferencia')).toBeHidden();
  });

  test('[BUG] unidade hospitalar: trocar de Outra para Nao nao oculta box Outra', async ({
    page,
  }) => {
    h.logInicioTeste('[BUG][SAUDE] unidade hospitalar Outra -> Nao');

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E BUG SAUDE HOSPITAL TOGGLE');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page, {
      unidadeHospitalar: { status: 'SIM', tipo: 'OUTRA', outraTexto: 'Hospital Outra BUG UI' },
    });

    await expect(page.locator('#radUnidadeHospitalar1')).toBeChecked();
    await expect(page.locator('#boxUnidadeHospitalar')).toBeVisible();
    await expect(page.locator('#boxOutraUnidadeHospitalar')).toBeVisible();

    await page.check('#radUnidadeHospitalar2');
    await expect(page.locator('#boxUnidadeHospitalar')).toBeHidden();
    await expect(page.locator('#boxOutraUnidadeHospitalar')).toBeHidden();
  });

  test('[STRESS] marca todos os checkbox da saude (exceto opcoes "Nao") e persiste', async ({
    page,
  }) => {
    h.logInicioTeste('[SAUDE][STRESS] todos checkboxes (exceto Nao)');
    const getAlert = await h.capturarAlert(page);

    const comorbidadeIds = Array.from({ length: 11 }, (_, i) => `#chkComorbidade${i + 1}`); // 1..11 (exclui 12 = Nao)
    const deficienciaIds = Array.from({ length: 8 }, (_, i) => `#chkDeficiencia${i + 1}`); // 1..8
    const cuidadosIds = Array.from({ length: 5 }, (_, i) => `#chkCuidadosTerceiros${i + 2}`); // 2..6 (exclui 1 = Nao)
    const substanciaIds = Array.from({ length: 12 }, (_, i) => `#chkSubstanciaPreferencia${i + 1}`); // 1..12

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E SAUDE STRESS ALL');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page, {
      comorbidade: { outra: true, outraTexto: 'Comorbidade STRESS E2E' },
      deficiencia: { tem: true, tipoIds: deficienciaIds, cuidadoIds: cuidadosIds },
      substancia: {
        outra: true,
        baseId: '#chkSubstanciaPreferencia1',
        outraTexto: 'Substancia STRESS E2E',
        tempoIndex: 3,
      },
      unidadeHospitalar: { status: 'SIM', tipo: 'OUTRA', outraTexto: 'Hospital STRESS E2E' },
    });

    for (const id of comorbidadeIds) {
      await page.check(id);
    }
    for (const id of substanciaIds) {
      await page.check(id);
    }

    // "Nao" conflita com os demais checkboxes destes grupos; validamos em teste separado.
    await h.assertCheckbox(page, '#chkComorbidade12', false);
    await h.assertCheckbox(page, '#chkCuidadosTerceiros1', false);

    await h.salvarCadastroComSucesso(page, getAlert);

    for (const id of comorbidadeIds) {
      await h.assertCheckbox(page, id, true);
    }
    await h.assertCheckbox(page, '#chkComorbidade12', false);
    await expect(page.locator('#txtOutraComorbidade')).toHaveValue('Comorbidade STRESS E2E');

    await expect(page.locator('#radDeficiencia1')).toBeChecked();
    for (const id of deficienciaIds) {
      await h.assertCheckbox(page, id, true);
    }
    await h.assertCheckbox(page, '#chkCuidadosTerceiros1', false);
    for (const id of cuidadosIds) {
      await h.assertCheckbox(page, id, true);
    }

    for (const id of substanciaIds) {
      await h.assertCheckbox(page, id, true);
    }
    await expect(page.locator('#txtOutraSubstanciaPreferencia')).toHaveValue('Substancia STRESS E2E');

    await expect(page.locator('#radUnidadeHospitalar1')).toBeChecked();
    await expect(page.locator('#slcUnidadeHospitalar')).toHaveValue('Outra');
    await expect(page.locator('#txtOutraUnidadeHospitalar')).toHaveValue('Hospital STRESS E2E');
  });

  test('Deficiencia Fisica volta marcada apos salvar', async ({ page }) => {
    h.logInicioTeste('[SAUDE] Deficiencia Fisica persiste');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E BUG DEFICIENCIA');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page, {
      deficiencia: { tem: true, tipoIds: ['#chkDeficiencia5'], cuidadoIds: ['#chkCuidadosTerceiros3'] },
    });
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page.locator('#radDeficiencia1')).toBeChecked();
    await h.assertCheckbox(page, '#chkCuidadosTerceiros3', true);
    await h.assertCheckbox(page, '#chkDeficiencia5', true);
  });
});
