const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_01-secao-inicial' });


test.describe('Cadastro Acolhido - Secao Inicial', () => {
  test('obrigatorios da secao inicial (nome e data de nascimento)', async ({ page }) => {
    h.logInicioTeste('[SEC INICIAL] obrigatorios');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const urlAntes = page.url();
    await page.click('#btnRegistrar');

    await expect.poll(getAlert).toContain('Preencha todos os campos obrigat');
    await expect(page).toHaveURL(urlAntes);
    await expect.poll(() => h.campoInvalido(page, '#txtNomeCompleto')).toBe(true);
    await expect.poll(() => h.campoInvalido(page, '#txtDataNascimento')).toBe(true);
  });

  test('preenche e persiste campos da secao inicial', async ({ page }) => {
    h.logInicioTeste('[SEC INICIAL] persistencia');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const dados = await h.criarDadosBasicosUnicos(page, 'E2E SEC INICIAL');

    await h.preencherDadosBasicos(page, dados);
    await h.preencherQuestionarioMinimoSeguro(page);
    await h.salvarCadastroComSucesso(page, getAlert);

    expect(dados.nis).toHaveLength(11);

    await expect(page.locator('#txtNomeCompleto')).toHaveValue(dados.nome);
    await expect(page.locator('#txtDataNascimento')).toHaveValue(dados.dataNascimento);
    await expect(page.locator('#slcSexo')).toHaveValue(dados.sexo);
    await expect(page.locator('#txtNomeSocial')).toHaveValue(dados.nomeSocial);
    await expect(page.locator('#slcIdentidadeGenero')).toHaveValue(dados.identidadeGenero);
    await expect(page.locator('#slcOrientacaoSexual')).toHaveValue(dados.orientacaoSexual);
    await expect(page.locator('#txtFiliacao1')).toHaveValue(dados.filiacao1);
    await expect(page.locator('#txtFiliacao2')).toHaveValue(dados.filiacao2);
    await expect(page.locator('#txtFiliacao3')).toHaveValue(dados.filiacao3);
    await expect(page.locator('#slcEstadoCivil')).toHaveValue(dados.estadoCivil);
    await expect(page.locator('#txtNis')).toHaveValue(dados.nis);
    await expect(page.locator('#txtCpf')).toHaveValue(dados.cpf);
    await expect(page.locator('#txtRg')).toHaveValue(dados.rg);
  });

  test('NIS com menos de 11 digitos invalida o campo e bloqueia o cadastro', async ({ page }) => {
    h.logInicioTeste('[SEC INICIAL] nis invalido');

    await h.abrirCadastroNovo(page);
    const urlAntes = page.url();

    await page.fill('#txtNomeCompleto', 'E2E NIS INVALIDO');
    await page.fill('#txtDataNascimento', '01/01/2000');
    await page.fill('#txtNis', '1234567890');
    await page.click('#btnRegistrar');

    await expect(page).toHaveURL(urlAntes);
    await expect.poll(() => h.campoInvalido(page, '#txtNis')).toBe(true);
    await expect(page.locator('#txtNis')).toHaveValue('1234567890');
  });
});

