const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_05-dados-sensiveis' });

function normalizarTexto(texto) {
  return String(texto || '')
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase();
}

function extrairIdDoCadastro(urlAcolhido) {
  const semQuery = String(urlAcolhido || '').split('?')[0].replace(/\/+$/, '');
  return semQuery.split('/').pop();
}

async function reservarVagaExecutora(page, getAlert, { textoLinhaDeveConter }) {
  await expect(page.locator('#boxTabelaSolicitacoesVagas')).toBeVisible();
  await expect
    .poll(async () => page.locator('#boxSolicitacoesVagas table').count(), { timeout: 20_000 })
    .toBeGreaterThan(0);

  const linha = page.locator('#boxSolicitacoesVagas tr').filter({ hasText: textoLinhaDeveConter }).first();
  await expect(linha).toBeVisible({ timeout: 20_000 });

  const botaoReservar = linha.getByRole('button', { name: /Reservar vaga/i }).first();
  await expect(botaoReservar).toBeVisible();

  const waitAltera = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/area-restrita/model/alteraSolicitacaoVaga.php') &&
      resp.request().method() === 'POST'
  );
  const waitRecarregaLista = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/area-restrita/model/solicitacoesVagas.php') &&
      resp.request().method() === 'POST'
  );

  await botaoReservar.click();
  await expect(page.locator('#confirmacaoModal')).toHaveClass(/show/);
  await expect(page.locator('#corpoModal')).toContainText('Deseja reservar a vaga');

  await page.locator('#boxBotoesModal').getByRole('button', { name: /Reservar/i }).click();

  await waitAltera;
  await expect.poll(() => normalizarTexto(getAlert())).toContain('vaga reservada');
  await waitRecarregaLista;
  await expect(page.locator('#confirmacaoModal')).not.toHaveClass(/show/);
}

async function confirmarAcolhimento(page, getAlert, idAcolhido) {
  await page.goto(`cadastro-acolhido/${idAcolhido}`, { waitUntil: 'domcontentloaded' });
  await expect(page.locator('#formAcolhido')).toBeVisible();
  await expect(page.locator('#tabAcolhimento')).not.toHaveClass(/d-none/);

  await page.click('#btnAcolhimento');
  await expect(page.locator('#tabAcolhimento')).toHaveClass(/active|show/);

  await page.check('#chkDocPossuo1');

  const docNecessariaHabilitada = page
    .locator("input[name='chkDocNecessaria']:not([disabled]), input[name='chkDocNecessaria[]']:not([disabled])")
    .first();
  if ((await docNecessariaHabilitada.count()) > 0) {
    await docNecessariaHabilitada.check();
  }

  await page.check('#chkBeneficio2');

  const waitCadastraAcolhimento = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/cadastro-acolhido/model/cadastraAcolhimento.php') &&
      resp.request().method() === 'POST'
  );
  const waitCarregaAcolhimento = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/cadastro-acolhido/model/carregaAcolhimento.php') &&
      resp.request().method() === 'POST'
  );

  await page.locator('#boxBotaoAcolhimento button').first().click();

  await waitCadastraAcolhimento;
  await expect.poll(() => normalizarTexto(getAlert())).toContain('acolhimento confirmado');
  await waitCarregaAcolhimento;
}

async function abrirProntuarioAcolhimento(page, idAcolhido) {
  await page.goto(`prontuario/${idAcolhido}`, { waitUntil: 'domcontentloaded' });
  await expect
    .poll(async () => page.locator('#boxListaEntradas .card').count(), { timeout: 20_000 })
    .toBeGreaterThan(0);

  const cardEmAcolhimento = page
    .locator('#boxListaEntradas .card')
    .filter({ hasText: /Em acolhimento/i })
    .first();

  if ((await cardEmAcolhimento.count()) > 0) {
    await cardEmAcolhimento.click();
  } else {
    await page.locator('#boxListaEntradas .card').first().click();
  }

  await page.waitForURL(/\/coed\/prontuario_acolhido\/\d+\/?$/);
  await expect(page.locator('#dadosSensiveis-tab')).toBeVisible();
}

async function abrirAbaDadosSensiveis(page) {
  await page.click('#dadosSensiveis-tab');
  await expect(page.locator('#tabDadosSensiveis')).toHaveClass(/active|show/);
}

async function prepararAcolhidoEmAcolhimentoNoProntuario(page, browser, token, getAlert) {
  const criado = await ar.prepararSolicitacaoEncaminhadaParaOsc(browser, {
    executoraId: 86,
    nomeOsc: 'OSC Teste DNI',
    prefixo: `E2E PRONTUARIO DS ${token}`,
  });

  const idAcolhido = extrairIdDoCadastro(criado.urlAcolhido);
  expect(idAcolhido).not.toBe('');

  await ar.loginComoExecutoraPerfil4(page, 'OSC Teste DNI');
  await reservarVagaExecutora(page, getAlert, {
    textoLinhaDeveConter: criado.cpf || criado.nome,
  });
  await confirmarAcolhimento(page, getAlert, idAcolhido);
  await abrirProntuarioAcolhimento(page, idAcolhido);
  await abrirAbaDadosSensiveis(page);

  return { criado, idAcolhido };
}

async function preencherDadosSensiveisTudoNao(page) {
  await page.check('#radNegligencia4');
  await page.check('#radViolenciaFisica4');
  await page.check('#radViolenciaSexual2');
  await page.check('#radViolenciaParceiros1');
  await page.check('#radAutorViolencia1');
  await page.check('#radEgresso2');
  await page.check('#radPendenciaJudicial2');
}

async function preencherDadosSensiveisTudoSim(page, token) {
  const dados = {
    idadeNegligencia: '12',
    idadeViolenciaFisica: '14',
    idadeViolenciaSexual: '13',
    idadeViolenciaParceiros: '26',
    observacoes: `OBS VIOLENCIA SEXUAL ${token}`,
    qualSuporte: `SUPORTE CREAS ${token}`,
    tempoPenaAplicada: '2 anos',
    tempoPenaEgresso: '4 anos',
    motivoPendencia: `PROCESSO ${token}`,
  };

  await page.check('#radNegligencia1');
  await expect(page.locator('#boxIdadeNegligencia')).not.toHaveClass(/d-none/);
  await page.fill('#txtIdadeNegligencia', dados.idadeNegligencia);

  await page.check('#radViolenciaFisica1');
  await expect(page.locator('#boxIdadeViolenciaFisica')).not.toHaveClass(/d-none/);
  await page.fill('#txtIdadeViolenciaFisica', dados.idadeViolenciaFisica);

  await page.check('#radViolenciaSexual1');
  await expect(page.locator('#boxIdade')).not.toHaveClass(/d-none/);
  await page.fill('#txtQualIdade', dados.idadeViolenciaSexual);
  await page.fill('#txtObservacoesViolenciaSexual', dados.observacoes);
  await page.check('#chkAgressor1');
  await page.check('#chkAgressor8');

  await page.check('#radViolenciaParceiros4');
  await expect(page.locator('#boxIdadeViolenciaParceiros')).not.toHaveClass(/d-none/);
  await expect(page.locator('#boxViolenciaParceiros')).not.toHaveClass(/d-none/);
  await page.fill('#txtIdadeViolenciaParceiros', dados.idadeViolenciaParceiros);
  await page.check('#chkTipoViolenciaParceiro2');
  await page.check('#chkTipoViolenciaParceiro3');
  await page.check('#radSuporte1');
  await expect(page.locator('#boxSuporte')).not.toHaveClass(/d-none/);
  await page.fill('#txtQualSuporte', dados.qualSuporte);

  await page.check('#radAutorViolencia4');
  await expect(page.locator('#boxAutorViolencia')).not.toHaveClass(/d-none/);
  await page.check('#chkTipoViolencia1');
  await page.check('#chkTipoViolencia4');
  await page.check('#radResponsabilizado2');
  await expect(page.locator('#boxPenaAplicada')).not.toHaveClass(/d-none/);
  await page.check('#radPenaAplicada4');
  await expect(page.locator('#boxTempoPenaAplicada')).not.toHaveClass(/d-none/);
  await page.fill('#txtTempoPenaAplicada', dados.tempoPenaAplicada);

  await page.check('#radEgresso1');
  await expect(page.locator('#boxEgresso')).not.toHaveClass(/d-none/);
  await page.check('#radEgressoPena4');
  await expect(page.locator('#boxSentenca')).not.toHaveClass(/d-none/);
  await page.fill('#txtTempoPenaEgresso', dados.tempoPenaEgresso);
  await page.check('#radCumpriuPena1');
  await page.check('#radForagido2');
  await page.check('#radLiberdade1');

  await page.check('#radPendenciaJudicial1');
  await expect(page.locator('#boxPendencia')).not.toHaveClass(/d-none/);
  await page.fill('#txtMotivoPendencia', dados.motivoPendencia);

  return dados;
}

async function salvarCadastroDadosSensiveis(page, getAlert) {
  const waitCadastra = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraDadosSensiveis.php') &&
      resp.request().method() === 'POST'
  );
  const waitCarrega = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/carregaDadosSensiveis.php') &&
      resp.request().method() === 'POST'
  );

  await page.locator('#boxBotaoDadosSensiveis button').first().click();
  await waitCadastra;
  await expect.poll(() => normalizarTexto(getAlert())).toContain('registros efetuados');
  await waitCarrega;
  await abrirAbaDadosSensiveis(page);
  await expect(page.locator('#tabDadosSensiveis #btnEditar')).toBeVisible();
}

async function salvarEdicaoDadosSensiveis(page, getAlert) {
  const waitEdita = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/editaDadosSensiveis.php') &&
      resp.request().method() === 'POST'
  );
  const waitCarrega = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/carregaDadosSensiveis.php') &&
      resp.request().method() === 'POST'
  );

  await abrirAbaDadosSensiveis(page);
  await page.click('#tabDadosSensiveis #btnEditar');
  await waitEdita;
  await expect.poll(() => normalizarTexto(getAlert())).toContain('alteracoes efetuadas');
  await waitCarrega;
}

async function assertDadosSensiveisTudoNaoPersistido(page) {
  await abrirAbaDadosSensiveis(page);

  await expect(page.locator('#radNegligencia4')).toBeChecked();
  await expect(page.locator('#radViolenciaFisica4')).toBeChecked();
  await expect(page.locator('#radViolenciaSexual2')).toBeChecked();
  await expect(page.locator('#radViolenciaParceiros1')).toBeChecked();
  await expect(page.locator('#radAutorViolencia1')).toBeChecked();
  await expect(page.locator('#radEgresso2')).toBeChecked();
  await expect(page.locator('#radPendenciaJudicial2')).toBeChecked();

  await expect(page.locator('#boxIdadeNegligencia')).toHaveClass(/d-none/);
  await expect(page.locator('#boxIdadeViolenciaFisica')).toHaveClass(/d-none/);
  await expect(page.locator('#boxIdade')).toHaveClass(/d-none/);
  await expect(page.locator('#boxIdadeViolenciaParceiros')).toHaveClass(/d-none/);
  await expect(page.locator('#boxViolenciaParceiros')).toHaveClass(/d-none/);
  await expect(page.locator('#boxAutorViolencia')).toHaveClass(/d-none/);
  await expect(page.locator('#boxEgresso')).toHaveClass(/d-none/);
  await expect(page.locator('#boxPendencia')).toHaveClass(/d-none/);

  await expect(page.locator('#txtIdadeNegligencia')).toHaveValue('');
  await expect(page.locator('#txtIdadeViolenciaFisica')).toHaveValue('');
  await expect(page.locator('#txtIdadeViolenciaParceiros')).toHaveValue('');
  await expect(page.locator('#txtMotivoPendencia')).toHaveValue('');
}

async function assertDadosSensiveisTudoSimPersistido(page, dados) {
  await abrirAbaDadosSensiveis(page);

  await expect(page.locator('#radNegligencia1')).toBeChecked();
  await expect(page.locator('#txtIdadeNegligencia')).toHaveValue(dados.idadeNegligencia);

  await expect(page.locator('#radViolenciaFisica1')).toBeChecked();
  await expect(page.locator('#txtIdadeViolenciaFisica')).toHaveValue(dados.idadeViolenciaFisica);

  await expect(page.locator('#radViolenciaSexual1')).toBeChecked();
  await expect(page.locator('#txtQualIdade')).toHaveValue(dados.idadeViolenciaSexual);
  await expect(page.locator('#txtObservacoesViolenciaSexual')).toHaveValue(dados.observacoes);
  await expect(page.locator('#chkAgressor1')).toBeChecked();
  await expect(page.locator('#chkAgressor8')).toBeChecked();

  await expect(page.locator('#radViolenciaParceiros4')).toBeChecked();
  await expect(page.locator('#txtIdadeViolenciaParceiros')).toHaveValue(dados.idadeViolenciaParceiros);
  await expect(page.locator('#chkTipoViolenciaParceiro2')).toBeChecked();
  await expect(page.locator('#chkTipoViolenciaParceiro3')).toBeChecked();
  await expect(page.locator('#radSuporte1')).toBeChecked();
  await expect(page.locator('#txtQualSuporte')).toHaveValue(dados.qualSuporte);

  await expect(page.locator('#radAutorViolencia4')).toBeChecked();
  await expect(page.locator('#chkTipoViolencia1')).toBeChecked();
  await expect(page.locator('#chkTipoViolencia4')).toBeChecked();
  await expect(page.locator('#radResponsabilizado2')).toBeChecked();
  await expect(page.locator('#radPenaAplicada4')).toBeChecked();
  await expect(page.locator('#txtTempoPenaAplicada')).toHaveValue(dados.tempoPenaAplicada);

  await expect(page.locator('#radEgresso1')).toBeChecked();
  await expect(page.locator('#radEgressoPena4')).toBeChecked();
  await expect(page.locator('#txtTempoPenaEgresso')).toHaveValue(dados.tempoPenaEgresso);
  await expect(page.locator('#radCumpriuPena1')).toBeChecked();
  await expect(page.locator('#radForagido2')).toBeChecked();
  await expect(page.locator('#radLiberdade1')).toBeChecked();

  await expect(page.locator('#radPendenciaJudicial1')).toBeChecked();
  await expect(page.locator('#txtMotivoPendencia')).toHaveValue(dados.motivoPendencia);
}

test.describe('Prontuario Acolhido - Dados Sensiveis', () => {
  test('cadastro com nao em tudo', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][DADOS SENSIVEIS] cadastro nao em tudo');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    await prepararAcolhidoEmAcolhimentoNoProntuario(page, browser, token, getAlert);
    await preencherDadosSensiveisTudoNao(page);
    await salvarCadastroDadosSensiveis(page, getAlert);
    await assertDadosSensiveisTudoNaoPersistido(page);
  });

  test('cadastro com sim e perguntas condicionais abertas', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][DADOS SENSIVEIS] cadastro sim com condicionais');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    await prepararAcolhidoEmAcolhimentoNoProntuario(page, browser, token, getAlert);
    const dados = await preencherDadosSensiveisTudoSim(page, token);
    await salvarCadastroDadosSensiveis(page, getAlert);
    await assertDadosSensiveisTudoSimPersistido(page, dados);
  });

  test('edicao: altera de sim para nao e limpa campos condicionais', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][DADOS SENSIVEIS] edicao sim para nao');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    await prepararAcolhidoEmAcolhimentoNoProntuario(page, browser, token, getAlert);
    const dados = await preencherDadosSensiveisTudoSim(page, token);
    await salvarCadastroDadosSensiveis(page, getAlert);
    await assertDadosSensiveisTudoSimPersistido(page, dados);

    await preencherDadosSensiveisTudoNao(page);
    await salvarEdicaoDadosSensiveis(page, getAlert);
    await assertDadosSensiveisTudoNaoPersistido(page);
  });
});
