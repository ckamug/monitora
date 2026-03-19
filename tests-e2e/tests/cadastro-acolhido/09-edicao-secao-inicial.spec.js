const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_09-edicao-secao-inicial' });


test.describe('Cadastro Acolhido - Edicao - Secao Inicial', () => {
  test('obrigatorios da secao inicial em modo edicao (nome e data de nascimento)', async ({
    page,
  }) => {
    h.logInicioTeste('[EDICAO][SEC INICIAL] obrigatorios');
    const { getAlert, urlEdicao } = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO SEC INICIAL OBR');

    await h.abrirEdicaoPorUrl(page, urlEdicao);
    await page.fill('#txtNomeCompleto', '');
    await page.fill('#txtDataNascimento', '');

    await page.click('#btnEditar');

    await expect.poll(getAlert).toContain('Preencha todos os campos obrigat');
    await expect(page).toHaveURL(urlEdicao);
    await expect.poll(() => h.campoInvalido(page, '#txtNomeCompleto')).toBe(true);
    await expect.poll(() => h.campoInvalido(page, '#txtDataNascimento')).toBe(true);
  });

  test('edita e persiste campos da secao inicial', async ({ page }) => {
    h.logInicioTeste('[EDICAO][SEC INICIAL] persistencia');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO SEC INICIAL PERSIST');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);
    const docsNovos = await h.gerarDocumentosNovosUnicos(page);

    const dadosEditados = {
      ...base.dados,
      nome: `${base.dados.nome} EDITADO`,
      nomeSocial: `${base.dados.nome} SOCIAL EDITADO`,
      sexo: 'Feminino',
      identidadeGenero: 'Feminino',
      orientacaoSexual: 'Bissexual',
      filiacao1: 'FILIACAO 1 EDICAO',
      filiacao2: 'FILIACAO 2 EDICAO',
      filiacao3: 'FILIACAO 3 EDICAO',
      estadoCivil: 'Casado',
      nis: docsNovos.nis,
      cpf: docsNovos.cpf,
      rg: '12345678',
    };

    expect(dadosEditados.nis).toHaveLength(11);

    await h.preencherDadosBasicos(page, dadosEditados);
    await h.salvarEdicaoComSucesso(page, base.getAlert);

    h.logTabela('[E2E][EDICAO][SEC INICIAL] retorno apos salvar', {
      url: page.url(),
      nome: await page.locator('#txtNomeCompleto').inputValue(),
      nascimento: await page.locator('#txtDataNascimento').inputValue(),
      nis: await page.locator('#txtNis').inputValue(),
      cpf: await page.locator('#txtCpf').inputValue(),
      rg: await page.locator('#txtRg').inputValue(),
    });

    await expect(page.locator('#txtNomeCompleto')).toHaveValue(dadosEditados.nome);
    await expect(page.locator('#txtDataNascimento')).toHaveValue(dadosEditados.dataNascimento);
    await expect(page.locator('#slcSexo')).toHaveValue(dadosEditados.sexo);
    await expect(page.locator('#txtNomeSocial')).toHaveValue(dadosEditados.nomeSocial);
    await expect(page.locator('#slcIdentidadeGenero')).toHaveValue(dadosEditados.identidadeGenero);
    await expect(page.locator('#slcOrientacaoSexual')).toHaveValue(dadosEditados.orientacaoSexual);
    await expect(page.locator('#txtFiliacao1')).toHaveValue(dadosEditados.filiacao1);
    await expect(page.locator('#txtFiliacao2')).toHaveValue(dadosEditados.filiacao2);
    await expect(page.locator('#txtFiliacao3')).toHaveValue(dadosEditados.filiacao3);
    await expect(page.locator('#slcEstadoCivil')).toHaveValue(dadosEditados.estadoCivil);
    await expect(page.locator('#txtNis')).toHaveValue(dadosEditados.nis);
    await expect(page.locator('#txtCpf')).toHaveValue(dadosEditados.cpf);
    await expect(page.locator('#txtRg')).toHaveValue(dadosEditados.rg);
  });
});
