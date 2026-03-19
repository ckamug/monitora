const { expect } = require('@playwright/test');
const h = require('../cadastro-acolhido/_helpers');

function normalizarTexto(texto) {
  return String(texto || '')
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase();
}

async function concluirLoginSelecionandoVinculo(page, { perfilEsperado, regexCard, descricao }) {
  await page.goto('login', { waitUntil: 'domcontentloaded' });
  await page.fill('#txtLogin', process.env.E2E_CPF);
  await page.click('#btnStep');
  await page.fill('#txtSenha', process.env.E2E_SENHA);
  await page.click('#btnAcessar');

  await expect
    .poll(async () => {
      const urlAtual = page.url();
      const qtdVinculos = await page.locator('#boxLocais .card').count();

      if (!urlAtual.includes('/coed/login')) return 'redirect';
      if (qtdVinculos > 0) return 'vinculos';
      return 'aguardando';
    })
    .not.toBe('aguardando');

  if (page.url().includes('/coed/login')) {
    const cards = page.locator('#boxLocais .card');
    await expect(cards.first()).toBeVisible();

    const card = cards.filter({ hasText: regexCard }).first();
    if ((await card.count()) === 0) {
      const textos = await cards.allTextContents();
      throw new Error(
        `Vinculo "${descricao}" nao encontrado. Vinculos exibidos: ${JSON.stringify(textos)}`
      );
    }

    await Promise.all([
      page.waitForResponse(
        (resp) =>
          resp.url().includes('/public/componentes/login/model/direcionaUsuario.php') &&
          resp.request().method() === 'POST'
      ),
      card.click(),
    ]);
  }

  await page.waitForURL(/\/coed\/area-restrita(\/)?$/);
  await expect(page.locator('#hidPerfilLogado')).toHaveValue(String(perfilEsperado));
}

async function loginComoConector(page) {
  await concluirLoginSelecionandoVinculo(page, {
    perfilEsperado: 7,
    regexCard: /Conector/i,
    descricao: 'Conector',
  });
}

async function loginComoExecutoraPerfil4(page, nomeOsc = 'OSC Teste DNI') {
  await concluirLoginSelecionandoVinculo(page, {
    perfilEsperado: 4,
    regexCard: new RegExp(nomeOsc, 'i'),
    descricao: nomeOsc,
  });
}

async function esperarTabelaSolicitacoesConector(page) {
  await expect(page.locator('#boxTabelaSolicitacoesVagas')).toBeVisible();
  await expect
    .poll(async () => page.locator('#boxSolicitacoesVagas table').count(), { timeout: 20_000 })
    .toBeGreaterThan(0);
}

async function estadoListaSolicitacoesConector(page) {
  const box = page.locator('#boxTabelaSolicitacoesVagas');
  const existeBox = (await box.count()) > 0;
  if (!existeBox) return { estado: 'ausente', tabelas: 0 };

  const classes = await box.evaluate((el) => el.className || '');
  const oculto = classes.includes('d-none');
  const tabelas = await page.locator('#boxSolicitacoesVagas table').count();

  if (oculto) return { estado: 'oculta', tabelas };
  if (tabelas > 0) return { estado: 'visivel_com_tabela', tabelas };
  return { estado: 'visivel_sem_tabela', tabelas };
}

async function criarSolicitacaoVagaPerfil3(page, prefixo = 'E2E AREA RESTRITA SOLIC') {
  const getAlert = await h.capturarAlert(page);

  await h.abrirCadastroNovo(page);
  const base = await h.criarDadosBasicosUnicos(page, prefixo);
  const dadosMinimos = {
    nome: base.nome,
    dataNascimento: base.dataNascimento,
    sexo: 'Masculino',
    nis: base.nis,
    cpf: base.cpf,
  };

  h.logTabela('[E2E][AREA-RESTRITA][SETUP] cadastro para solicitacao', {
    nome: dadosMinimos.nome,
    nascimento: dadosMinimos.dataNascimento,
    sexo: dadosMinimos.sexo,
    nis: dadosMinimos.nis,
    cpf: dadosMinimos.cpf,
  });

  await h.preencherDadosBasicos(page, dadosMinimos);
  await expect(page.locator('#slcSexo')).toHaveValue('Masculino');
  await h.preencherQuestionarioMinimoSeguro(page);
  await h.salvarCadastroComSucesso(page, getAlert);

  await expect
    .poll(async () => page.locator('#btnSolicitarVaga').count(), { timeout: 20_000 })
    .toBeGreaterThan(0);
  await expect(page.locator('#btnSolicitarVaga')).toBeVisible({ timeout: 20_000 });

  const waitCarregaServicos = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/cadastro-acolhido/model/carregaServicos.php') &&
      resp.request().method() === 'POST'
  );
  await page.click('#btnSolicitarVaga');
  await waitCarregaServicos;

  await expect(page.locator('#mdlSolicitarVaga')).toHaveClass(/show/);
  await expect(page.locator('#slcServicos')).toBeVisible();
  await expect(page.locator('#slcServicos option[value="1"]')).toHaveCount(1);

  await page.selectOption('#slcServicos', '1');
  await expect(page.locator('#boxGenero')).toBeVisible();
  await page.selectOption('#slcGenero', 'Masculino');

  const waitSolicitarVaga = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/cadastro-acolhido/model/solicitarVaga.php') &&
      resp.request().method() === 'POST'
  );
  const waitRecarregaAcolhido = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/cadastro-acolhido/model/carregaAcolhido.php') &&
      resp.request().method() === 'POST'
  );
  const waitListaStatus = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/cadastro-acolhido/model/carregaSolicitacoesVagas.php') &&
      resp.request().method() === 'POST'
  );

  await page.click('#btnConfirmaSolicitacaoVaga');
  await waitSolicitarVaga;
  await expect.poll(getAlert).toContain('Vaga Solicitada');
  await Promise.all([waitRecarregaAcolhido, waitListaStatus]);

  await expect(page.locator('#mdlSolicitarVaga')).not.toHaveClass(/show/);
  await expect(page.locator('#boxSolicitarVaga #btnSolicitarVaga')).toHaveCount(0);
  await expect(page.locator('#abaStatus')).not.toHaveClass(/d-none/);

  return {
    nome: dadosMinimos.nome,
    cpf: dadosMinimos.cpf,
    nis: dadosMinimos.nis,
    urlAcolhido: page.url(),
  };
}

async function criarSolicitacaoVagaPerfil3EmContexto(browser, prefixo) {
  const ctx = await browser.newContext();
  const page = await ctx.newPage();

  try {
    const dados = await criarSolicitacaoVagaPerfil3(page, prefixo);
    return dados;
  } finally {
    await ctx.close();
  }
}

async function abrirEncaminhamentoAteEncontrarOsc(page, executoraId, nomeOsc, options = {}) {
  await esperarTabelaSolicitacoesConector(page);

  const filtroLinha = options.textoLinhaDeveConter
    ? normalizarTexto(options.textoLinhaDeveConter)
    : '';

  const botoes = page.getByRole('button', { name: /Encaminhar para OSC/i });
  const totalBotoes = await botoes.count();

  if (totalBotoes === 0) {
    throw new Error(
      'Nenhuma solicitacao pendente para encaminhamento encontrada na tela do Conector (perfil 7).'
    );
  }

  for (let i = 0; i < totalBotoes; i += 1) {
    const botao = botoes.nth(i);
    const linha = botao.locator('xpath=ancestor::tr');
    const textoLinha = ((await linha.count()) > 0 ? await linha.first().innerText() : '').trim();
    const onclick = (await botao.getAttribute('onclick')) || '';

    if (filtroLinha && !normalizarTexto(textoLinha).includes(filtroLinha)) {
      continue;
    }

    const waitCarregaOscs = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/area-restrita/model/carregaOscsEncaminhamento.php') &&
        resp.request().method() === 'POST'
    );

    await botao.click();
    await waitCarregaOscs;

    await expect(page.locator('#encaminhamentoModal')).toHaveClass(/show/);
    await expect
      .poll(async () => page.locator('#slcOscsEncaminhamento option').count(), { timeout: 15_000 })
      .toBeGreaterThan(0);

    const existeOsc =
      (await page.locator(`#slcOscsEncaminhamento option[value="${executoraId}"]`).count()) > 0;
    if (!existeOsc) {
      h.logTabela('[E2E][AREA-RESTRITA] solicitacao sem OSC alvo', {
        indice: i + 1,
        onclick,
        linha: textoLinha.slice(0, 220),
        executora_id_alvo: String(executoraId),
        osc_alvo: nomeOsc,
      });

      await page.locator('#encaminhamentoModal').getByRole('button', { name: /Cancelar/i }).click();
      await expect(page.locator('#encaminhamentoModal')).not.toHaveClass(/show/);
      continue;
    }

    const optionTexto = await page
      .locator(`#slcOscsEncaminhamento option[value="${executoraId}"]`)
      .innerText();
    h.logTabela('[E2E][AREA-RESTRITA] solicitacao selecionada para encaminhamento', {
      indice: i + 1,
      onclick,
      linha: textoLinha.slice(0, 220),
      executora_id: String(executoraId),
      osc_opcao: optionTexto,
    });

    expect(normalizarTexto(optionTexto)).toContain(normalizarTexto(nomeOsc));
    return { indice: i, textoLinha, onclick };
  }

  throw new Error(
    `Nenhuma das ${totalBotoes} solicitacoes exibidas retornou a OSC alvo "${nomeOsc}" (executora_id=${executoraId}) no combo de encaminhamento.`
  );
}

async function assertSolicitacaoSaiuFilaConectorOuTabelaOculta(page, solicitacaoId) {
  await expect
    .poll(async () => (await estadoListaSolicitacoesConector(page)).estado, { timeout: 20_000 })
    .toMatch(/oculta|visivel_com_tabela/);

  const estado = await estadoListaSolicitacoesConector(page);
  if (estado.estado === 'oculta') return;

  await expect(
    page.locator(`#boxSolicitacoesVagas button[onclick*="abreEncaminhamento(${solicitacaoId},"]`)
  ).toHaveCount(0);
}

async function encaminharSolicitacaoParaOsc(page, { executoraId = 86, nomeOsc = 'OSC Teste DNI', textoLinhaDeveConter } = {}) {
  const getAlert = await h.capturarAlert(page);

  const botoesAntes = page.getByRole('button', { name: /Encaminhar para OSC/i });
  const totalAntes = await botoesAntes.count();

  const alvo = await abrirEncaminhamentoAteEncontrarOsc(page, executoraId, nomeOsc, {
    textoLinhaDeveConter,
  });

  const solicitacaoId = await page.locator('#hidSolicitacaoEncaminhamento').inputValue();
  expect(Number(solicitacaoId)).toBeGreaterThan(0);

  const waitDetalhesOsc = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/area-restrita/model/carregaDetalhesOscEncaminhamento.php') &&
      resp.request().method() === 'POST'
  );
  await page.selectOption('#slcOscsEncaminhamento', String(executoraId));
  await waitDetalhesOsc;
  await expect(page.locator('#slcOscsEncaminhamento')).toHaveValue(String(executoraId));

  const textoSelecionado = await page.locator('#slcOscsEncaminhamento option:checked').innerText();
  expect(normalizarTexto(textoSelecionado)).toContain(normalizarTexto(nomeOsc));

  const waitEncaminhar = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/area-restrita/model/encaminharSolicitacaoVaga.php') &&
      resp.request().method() === 'POST'
  );
  const waitRecarregaLista = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/area-restrita/model/solicitacoesVagas.php') &&
      resp.request().method() === 'POST'
  );

  await page.click('#btnConfirmaEncaminhamento');
  await waitEncaminhar;
  await expect.poll(getAlert).toContain('Solicitacao encaminhada para OSC executora');
  await waitRecarregaLista;

  await expect(page.locator('#encaminhamentoModal')).not.toHaveClass(/show/);
  await assertSolicitacaoSaiuFilaConectorOuTabelaOculta(page, solicitacaoId);

  const totalDepois = await page.getByRole('button', { name: /Encaminhar para OSC/i }).count();
  h.logTabela('[E2E][AREA-RESTRITA] encaminhamento concluido', {
    solicitacao_id: solicitacaoId,
    executora_id: String(executoraId),
    osc: textoSelecionado,
    totalAntes: String(totalAntes),
    totalDepois: String(totalDepois),
    linha: (alvo.textoLinha || '').slice(0, 220),
  });

  return { solicitacaoId, textoSelecionado, alvo };
}

async function prepararSolicitacaoEncaminhadaParaOsc(browser, { executoraId = 86, nomeOsc = 'OSC Teste DNI', prefixo } = {}) {
  const criado = await criarSolicitacaoVagaPerfil3EmContexto(
    browser,
    prefixo || 'E2E AREA RESTRITA EXECUTORA'
  );

  const ctxConector = await browser.newContext();
  const pageConector = await ctxConector.newPage();

  try {
    await loginComoConector(pageConector);
    await encaminharSolicitacaoParaOsc(pageConector, {
      executoraId,
      nomeOsc,
      textoLinhaDeveConter: criado.cpf || criado.nome,
    });
  } finally {
    await ctxConector.close();
  }

  return criado;
}

module.exports = {
  expect,
  h,
  normalizarTexto,
  loginComoConector,
  loginComoExecutoraPerfil4,
  esperarTabelaSolicitacoesConector,
  abrirEncaminhamentoAteEncontrarOsc,
  encaminharSolicitacaoParaOsc,
  criarSolicitacaoVagaPerfil3,
  criarSolicitacaoVagaPerfil3EmContexto,
  prepararSolicitacaoEncaminhadaParaOsc,
};
