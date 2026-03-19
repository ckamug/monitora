const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_14-edicao-saude' });


async function uncheckSeMarcado(page, selector) {
  const loc = page.locator(selector);
  if (await loc.isChecked()) {
    await loc.uncheck();
  }
}

test.describe('Cadastro Acolhido - Edicao - Saude', () => {
  test('comorbidade Outra + substancia Outra + unidade hospitalar Outra', async ({ page }) => {
    h.logInicioTeste('[EDICAO][SAUDE] outras + hospital outra');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO SAUDE OUTRA');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await uncheckSeMarcado(page, '#chkComorbidade12');
    await h.preencherComorbidades(page, { outra: true, outraTexto: 'Comorbidade Outra EDICAO' });
    await h.preencherDeficiencia(page, { tem: false });
    await h.preencherSubstanciaPreferencia(page, {
      outra: true,
      outraTexto: 'Substancia Outra EDICAO',
      baseId: '#chkSubstanciaPreferencia2',
    });
    await h.preencherUnidadeHospitalar(page, {
      status: 'SIM',
      tipo: 'OUTRA',
      outraTexto: 'Hospital Outra EDICAO',
    });
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    await h.assertCheckbox(page, '#chkComorbidade11', true);
    await expect(page.locator('#txtOutraComorbidade')).toHaveValue('Comorbidade Outra EDICAO');
    await expect(page.locator('#radDeficiencia2')).toBeChecked();
    await h.assertCheckbox(page, '#chkSubstanciaPreferencia12', true);
    await expect(page.locator('#txtOutraSubstanciaPreferencia')).toHaveValue('Substancia Outra EDICAO');
    await expect(page.locator('#radUnidadeHospitalar1')).toBeChecked();
    await expect(page.locator('#slcUnidadeHospitalar')).toHaveValue('Outra');
    await expect(page.locator('#txtOutraUnidadeHospitalar')).toHaveValue('Hospital Outra EDICAO');
  });

  test('caminhos NAO (comorbidade, deficiencia, unidade hospitalar)', async ({ page }) => {
    h.logInicioTeste('[EDICAO][SAUDE] caminhos NAO');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO SAUDE NAO', {
      questionario: {
        comorbidade: { outra: true, outraTexto: 'BASE' },
        deficiencia: { tem: true, tipoIds: ['#chkDeficiencia5'], cuidadoIds: ['#chkCuidadosTerceiros3'] },
        substancia: { outra: true, baseId: '#chkSubstanciaPreferencia2', outraTexto: 'BASE' },
        unidadeHospitalar: { status: 'SIM', tipo: 'OUTRA', outraTexto: 'BASE' },
      },
    });

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    for (let i = 1; i <= 11; i += 1) {
      await uncheckSeMarcado(page, `#chkComorbidade${i}`);
    }
    if (await page.locator('#txtOutraComorbidade').isVisible()) {
      await page.fill('#txtOutraComorbidade', '');
    }
    await h.preencherComorbidades(page, { outra: false });
    await h.preencherDeficiencia(page, { tem: false });
    for (let i = 1; i <= 12; i += 1) {
      await uncheckSeMarcado(page, `#chkSubstanciaPreferencia${i}`);
    }
    if (await page.locator('#txtOutraSubstanciaPreferencia').isVisible()) {
      await page.fill('#txtOutraSubstanciaPreferencia', '');
    }
    await h.preencherSubstanciaPreferencia(page, { outra: false, baseId: '#chkSubstanciaPreferencia4' });
    await h.preencherUnidadeHospitalar(page, { status: 'NAO' });
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    await h.assertCheckbox(page, '#chkComorbidade12', true);
    await expect(page.locator('#radDeficiencia2')).toBeChecked();
    await h.assertCheckbox(page, '#chkSubstanciaPreferencia4', true);
    await expect(page.locator('#radUnidadeHospitalar2')).toBeChecked();
  });

  test('[UI] deficiencia: alterna exibicao do bloco em edicao (Sim -> Nao -> Sim)', async ({
    page,
  }) => {
    h.logInicioTeste('[EDICAO][SAUDE][UI] deficiencia toggle');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO UI DEFICIENCIA TOGGLE', {
      questionario: {
        deficiencia: { tem: true, tipoIds: ['#chkDeficiencia5'], cuidadoIds: ['#chkCuidadosTerceiros3'] },
      },
    });

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await expect(page.locator('#boxDeficiencia')).toBeVisible();

    await page.check('#radDeficiencia2');
    await expect(page.locator('#boxDeficiencia')).toBeHidden();

    await page.check('#radDeficiencia1');
    await expect(page.locator('#boxDeficiencia')).toBeVisible();
  });

  test('unidade hospitalar SIM (lista) persiste opcao selecionada', async ({ page }) => {
    h.logInicioTeste('[EDICAO][SAUDE] hospital SIM lista');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO SAUDE HOSPITAL LISTA');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await h.preencherDeficiencia(page, { tem: false });
    await h.preencherUnidadeHospitalar(page, { status: 'SIM', tipo: 'PADRAO', valor: 'Hospital Lacan' });
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    await expect(page.locator('#radUnidadeHospitalar1')).toBeChecked();
    await expect(page.locator('#slcUnidadeHospitalar')).toHaveValue('Hospital Lacan');
  });

  test('[UI] comorbidade: trocar de Outra para Nao oculta box Outra em edicao', async ({ page }) => {
    h.logInicioTeste('[EDICAO][SAUDE][UI] comorbidade Outra -> Nao');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO UI COMORBIDADE TOGGLE', {
      questionario: {
        comorbidade: { outra: true, outraTexto: 'BASE COMORBIDADE OUTRA' },
      },
    });

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await expect(page.locator('#boxTipoAcompanhamento')).toBeVisible();
    await expect(page.locator('#txtOutraComorbidade')).toHaveValue('BASE COMORBIDADE OUTRA');

    await page.check('#chkComorbidade12');
    await page.uncheck('#chkComorbidade11');
    await expect(page.locator('#boxTipoAcompanhamento')).toBeHidden();
  });

  test('[UI] substancia: desmarcar Outra oculta box Outra em edicao', async ({ page }) => {
    h.logInicioTeste('[EDICAO][SAUDE][UI] substancia desmarca Outra');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO UI SUBSTANCIA TOGGLE', {
      questionario: {
        substancia: {
          outra: true,
          baseId: '#chkSubstanciaPreferencia2',
          outraTexto: 'BASE SUBSTANCIA OUTRA',
        },
      },
    });

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await expect(page.locator('#boxOutraSubstanciaPreferencia')).toBeVisible();
    await expect(page.locator('#txtOutraSubstanciaPreferencia')).toHaveValue('BASE SUBSTANCIA OUTRA');

    await page.uncheck('#chkSubstanciaPreferencia12');
    await expect(page.locator('#boxOutraSubstanciaPreferencia')).toBeHidden();
  });

  test('[STRESS] marca todos os checkbox da saude (exceto opcoes "Nao") e persiste', async ({
    page,
  }) => {
    h.logInicioTeste('[EDICAO][SAUDE][STRESS] todos checkboxes (exceto Nao)');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO SAUDE STRESS');

    const comorbidadeIds = Array.from({ length: 11 }, (_, i) => `#chkComorbidade${i + 1}`);
    const deficienciaIds = Array.from({ length: 8 }, (_, i) => `#chkDeficiencia${i + 1}`);
    const cuidadosIds = Array.from({ length: 5 }, (_, i) => `#chkCuidadosTerceiros${i + 2}`);
    const substanciaIds = Array.from({ length: 12 }, (_, i) => `#chkSubstanciaPreferencia${i + 1}`);

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await h.preencherDeficiencia(page, { tem: true, tipoIds: deficienciaIds, cuidadoIds: cuidadosIds });
    await h.preencherComorbidades(page, { outra: true, outraTexto: 'Comorbidade STRESS EDICAO' });
    await h.preencherSubstanciaPreferencia(page, {
      outra: true,
      baseId: '#chkSubstanciaPreferencia1',
      outraTexto: 'Substancia STRESS EDICAO',
      tempoIndex: 3,
    });
    await h.preencherUnidadeHospitalar(page, { status: 'SIM', tipo: 'OUTRA', outraTexto: 'Hospital STRESS EDICAO' });

    for (const id of comorbidadeIds) await page.check(id);
    for (const id of substanciaIds) await page.check(id);

    await uncheckSeMarcado(page, '#chkComorbidade12');
    await uncheckSeMarcado(page, '#chkCuidadosTerceiros1');

    await h.salvarEdicaoComSucesso(page, base.getAlert);

    for (const id of comorbidadeIds) await h.assertCheckbox(page, id, true);
    await h.assertCheckbox(page, '#chkComorbidade12', false);
    await expect(page.locator('#txtOutraComorbidade')).toHaveValue('Comorbidade STRESS EDICAO');

    await expect(page.locator('#radDeficiencia1')).toBeChecked();
    for (const id of deficienciaIds) await h.assertCheckbox(page, id, true);
    await h.assertCheckbox(page, '#chkCuidadosTerceiros1', false);
    for (const id of cuidadosIds) await h.assertCheckbox(page, id, true);

    for (const id of substanciaIds) await h.assertCheckbox(page, id, true);
    await expect(page.locator('#txtOutraSubstanciaPreferencia')).toHaveValue('Substancia STRESS EDICAO');

    await expect(page.locator('#radUnidadeHospitalar1')).toBeChecked();
    await expect(page.locator('#slcUnidadeHospitalar')).toHaveValue('Outra');
    await expect(page.locator('#txtOutraUnidadeHospitalar')).toHaveValue('Hospital STRESS EDICAO');
  });

  test('Deficiencia Fisica volta marcada apos salvar', async ({ page }) => {
    h.logInicioTeste('[EDICAO][SAUDE] Deficiencia Fisica persiste');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO DEFICIENCIA');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    await h.preencherDeficiencia(page, {
      tem: true,
      tipoIds: ['#chkDeficiencia5'],
      cuidadoIds: ['#chkCuidadosTerceiros3'],
    });
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    await expect(page.locator('#radDeficiencia1')).toBeChecked();
    await h.assertCheckbox(page, '#chkCuidadosTerceiros3', true);
    await h.assertCheckbox(page, '#chkDeficiencia5', true);
  });
});
