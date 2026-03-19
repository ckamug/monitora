const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const an = require('./_anamnese_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_06-anamnese-identificacao' });

test.describe('Prontuario Acolhido - Anamnese - Identificacao', () => {
  test('registra e recarrega dados da aba identificacao', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][ANAMNESE][IDENTIFICACAO] cadastro');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    await an.prepararAcolhidoEmAcolhimentoNoAnamnese(page, browser, token, getAlert, {
      prefixo: `E2E ANAMNESE ID ${token}`,
    });

    await page.click('#identificacao-tab');
    await expect(page.locator('#tabIdentificacao')).toHaveClass(/active|show/);

    await an.aguardarOpcoesSelect(page, '#tabIdentificacao #slcEtnia');
    await an.aguardarOpcoesSelect(page, '#tabIdentificacao #slcTipoCertidao');
    await an.aguardarOpcoesSelect(page, '#tabIdentificacao #slcUfRegistro');
    await expect
      .poll(async () => page.locator("#tabIdentificacao input[name='radRegistroCartorio']").count(), { timeout: 20_000 })
      .toBeGreaterThan(0);

    const dados = {
      nacionalidade: `NACIONALIDADE ${token}`,
      naturalidade: `NATURALIDADE ${token}`,
      nomeCartorio: `CARTORIO ${token}`,
      dataRegistro: an.ymdHoje(),
      numeroLivro: `LIVRO-${token}`,
      numeroFolha: `FOLHA-${token}`,
      numeroTermo: `TERMO-${token}`,
      matricula: `MAT-${token}`,
    };

    await page.fill('#tabIdentificacao #txtNacionalidade', dados.nacionalidade);
    await page.fill('#tabIdentificacao #txtNaturalidade', dados.naturalidade);
    await page.fill('#tabIdentificacao #txtNomeCartorio', dados.nomeCartorio);
    await page.fill('#tabIdentificacao #txtDataRegistro', dados.dataRegistro);
    await page.fill('#tabIdentificacao #txtNLivro', dados.numeroLivro);
    await page.fill('#tabIdentificacao #txtNFolha', dados.numeroFolha);
    await page.fill('#tabIdentificacao #txtNTermo', dados.numeroTermo);
    await page.fill('#tabIdentificacao #txtMatricula', dados.matricula);

    const etnia = await an.selecionarPrimeiraOpcaoValida(page, '#tabIdentificacao #slcEtnia');
    const tipoCertidao = await an.selecionarPrimeiraOpcaoValida(page, '#tabIdentificacao #slcTipoCertidao');
    const registroCartorioValor = await an.selecionarPrimeiroRadio(
      page,
      "#tabIdentificacao input[name='radRegistroCartorio']"
    );
    const uf = await an.selecionarPrimeiraOpcaoValida(page, '#tabIdentificacao #slcUfRegistro');

    let municipio = null;
    try {
      municipio = await an.selecionarPrimeiraOpcaoValida(page, '#tabIdentificacao #slcMunicipioCertidao', {
        timeout: 15_000,
      });
    } catch (_) {
      municipio = null;
    }

    expect(etnia).not.toBeNull();
    expect(tipoCertidao).not.toBeNull();
    expect(uf).not.toBeNull();
    expect(registroCartorioValor).not.toBe('');

    const waitSalvar = page.waitForResponse(
      (resp) =>
        (resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraIdentificacao.php') ||
          resp.url().includes('/public/componentes/prontuario_acolhido/model/editaIdentificacao.php')) &&
        resp.request().method() === 'POST'
    );
    const waitCarrega = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/prontuario_acolhido/model/carregaIdentificacao.php') &&
        resp.request().method() === 'POST'
    );

    await page.click('#tabIdentificacao #btnCadIdentificacao');
    await waitSalvar;
    await waitCarrega;

    await expect(page.locator('#tabIdentificacao #txtNacionalidade')).toHaveValue(dados.nacionalidade);
    await expect(page.locator('#tabIdentificacao #txtNaturalidade')).toHaveValue(dados.naturalidade);
    await expect(page.locator('#tabIdentificacao #txtNomeCartorio')).toHaveValue(dados.nomeCartorio);
    await expect(page.locator('#tabIdentificacao #txtDataRegistro')).toHaveValue(dados.dataRegistro);
    await expect(page.locator('#tabIdentificacao #txtNLivro')).toHaveValue(dados.numeroLivro);
    await expect(page.locator('#tabIdentificacao #txtNFolha')).toHaveValue(dados.numeroFolha);
    await expect(page.locator('#tabIdentificacao #txtNTermo')).toHaveValue(dados.numeroTermo);
    await expect(page.locator('#tabIdentificacao #txtMatricula')).toHaveValue(dados.matricula);

    await expect(page.locator('#tabIdentificacao #slcEtnia')).toHaveValue(etnia.value);
    await expect(page.locator('#tabIdentificacao #slcTipoCertidao')).toHaveValue(tipoCertidao.value);
    await expect(page.locator('#tabIdentificacao #slcUfRegistro')).toHaveValue(uf.value);

    if (municipio && municipio.value) {
      await expect(page.locator('#tabIdentificacao #slcMunicipioCertidao')).toHaveValue(municipio.value);
    }

    await expect
      .poll(() => an.valorRadioSelecionado(page, "#tabIdentificacao input[name='radRegistroCartorio']"))
      .toBe(registroCartorioValor);
  });
});

