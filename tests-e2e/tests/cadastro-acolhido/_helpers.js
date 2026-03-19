const { expect } = require('@playwright/test');
const alertStates = new WeakMap();

function somenteDigitos(valor) {
  return String(valor || '').replace(/\D/g, '');
}

function formatarCpf(cpf11) {
  const d = somenteDigitos(cpf11).padStart(11, '0').slice(0, 11);
  return `${d.slice(0, 3)}.${d.slice(3, 6)}.${d.slice(6, 9)}-${d.slice(9, 11)}`;
}

function calcularDigitoCpf(noveOuDezDigitos) {
  const numeros = somenteDigitos(noveOuDezDigitos);
  const pesoInicial = numeros.length + 1;
  let soma = 0;

  for (let i = 0; i < numeros.length; i += 1) {
    soma += Number(numeros[i]) * (pesoInicial - i);
  }

  const resto = soma % 11;
  return resto < 2 ? '0' : String(11 - resto);
}

function gerarCpfValidoAleatorio() {
  let base = '';
  do {
    base = String(Math.floor(Math.random() * 1_000_000_000)).padStart(9, '0');
  } while (/^(\d)\1{8}$/.test(base));

  const d1 = calcularDigitoCpf(base);
  const d2 = calcularDigitoCpf(base + d1);
  return formatarCpf(base + d1 + d2);
}

function gerarNisAleatorio() {
  return String(Math.floor(Math.random() * 100_000_000_000)).padStart(11, '0');
}

function logInicioTeste(nome) {
  console.log(`[E2E][START] ${nome}`);
}

function logTabela(titulo, objeto) {
  console.log(titulo);
  console.table(objeto);
}

function normalizarTexto(texto) {
  return String(texto || '')
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase();
}

function gerarSufixoUnico() {
  return `${Date.now()}${String(Math.floor(Math.random() * 1000)).padStart(3, '0')}`;
}

async function capturarAlert(page) {
  let state = alertStates.get(page);

  if (!state) {
    state = { texto: '' };
    state.handler = async (dialog) => {
      state.texto = dialog.message();
      try {
        await dialog.accept();
      } catch (error) {
        // Pode acontecer se outro handler legado/duplicado ja aceitou o dialogo.
        if (!String(error && error.message ? error.message : error).includes('already handled')) {
          throw error;
        }
      }
    };
    page.on('dialog', state.handler);
    alertStates.set(page, state);
  } else {
    state.texto = '';
  }

  return () => state.texto;
}

async function consultarDuplicidadeAcolhido(page, { cpf = '', nis = '' }) {
  const baseUrl = process.env.BASE_URL || 'http://localhost/coed/';
  const url = new URL('public/componentes/cadastro-acolhido/model/consultaCpf.php', baseUrl).toString();

  const response = await page.request.post(url, {
    form: { cpf, nis, id: '', id_atual: '' },
  });

  if (!response.ok()) {
    throw new Error(`Falha ao consultar duplicidade (${response.status()}) em ${url}`);
  }

  return response.json();
}

async function gerarDocumentosNovosUnicos(page) {
  for (let tentativa = 1; tentativa <= 20; tentativa += 1) {
    const cpf = gerarCpfValidoAleatorio();
    const nis = gerarNisAleatorio();
    const retorno = await consultarDuplicidadeAcolhido(page, { cpf, nis });
    if (!retorno || retorno.usuario_existe !== true) return { cpf, nis };
  }

  throw new Error('Nao foi possivel gerar CPF/NIS unicos apos 20 tentativas.');
}

async function mockViaCep01009000(page) {
  await page.route(/https:\/\/viacep\.com\.br\/ws\/.+/i, async (route) => {
    const url = new URL(route.request().url());
    const callback = url.searchParams.get('callback') || 'callback';
    const payload = {
      cep: '01009-000',
      logradouro: 'Praca da Se',
      complemento: 'lado impar',
      bairro: 'Se',
      localidade: 'São Paulo',
      uf: 'SP',
      ibge: '3550308',
      gia: '1004',
      ddd: '11',
      siafi: '7107',
    };

    await route.fulfill({
      status: 200,
      contentType: 'application/javascript; charset=utf-8',
      body: `${callback}(${JSON.stringify(payload)});`,
    });
  });
}

async function loginComoPortaEntrada(page) {
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

  if (!page.url().includes('/coed/login')) return;

  const cardsVinculo = page.locator('#boxLocais .card');
  await expect(cardsVinculo.first()).toBeVisible();

  let vinculo = cardsVinculo.filter({ hasText: /Perfil:\s*Porta de Entrada/i }).first();
  if ((await vinculo.count()) === 0) {
    vinculo = cardsVinculo.filter({ hasText: /Porta de Entrada/i }).first();
  }
  if ((await vinculo.count()) === 0) {
    const textos = await cardsVinculo.allTextContents();
    throw new Error(
      `Vinculo 'Porta de Entrada' nao encontrado. Vinculos exibidos: ${JSON.stringify(textos)}`
    );
  }

  await Promise.all([
    page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/login/model/direcionaUsuario.php') &&
        resp.request().method() === 'POST'
    ),
    vinculo.click(),
  ]);

  await page.waitForURL(/\/coed\/(area-restrita|prestacoes)(\/)?$/);
}

async function abrirCadastroAcolhidoPeloMenu(page) {
  const perfilLogado = page.locator('#hidPerfilLogado');
  if (await perfilLogado.count()) {
    await expect(perfilLogado).toHaveValue('3');
  }

  const menuCadastros = page.locator('#titCadastros > a');
  const submenuCadastros = page.locator('#components-nav');
  const itemAcolhidos = page.locator('#mnuCadAcolhidos');
  const linkAcolhidos = page.locator('#mnuCadAcolhidos a');

  await expect(menuCadastros).toBeVisible();
  await expect(itemAcolhidos).not.toHaveClass(/d-none/);

  const submenuAberto = await submenuCadastros.evaluate((el) => el.classList.contains('show'));
  if (!submenuAberto) await menuCadastros.click();

  await expect(submenuCadastros).toHaveClass(/show/);
  await expect(linkAcolhidos).toBeVisible();
  await linkAcolhidos.click();

  await page.waitForURL(/\/coed\/acolhidos/);
  await page.getByRole('button', { name: /Cadastrar Acolhido/i }).click();
  await page.waitForURL(/\/coed\/cadastro-acolhido(\/.*)?$/);
  await expect(page.locator('#formAcolhido')).toBeVisible();
  await expect(page.locator('#btnRegistrar')).toBeVisible();
}

async function abrirCadastroNovo(page, options = {}) {
  if (options.mockCep) {
    await mockViaCep01009000(page);
  }
  await loginComoPortaEntrada(page);
  await abrirCadastroAcolhidoPeloMenu(page);
}

async function criarDadosBasicosUnicos(page, prefixo = 'E2E CAD ACOLHIDO') {
  const docs = await gerarDocumentosNovosUnicos(page);
  const sufixo = gerarSufixoUnico();
  const nome = `${prefixo} ${sufixo}`;

  return {
    nome,
    dataNascimento: '01/01/2000',
    sexo: 'Masculino',
    nomeSocial: `${nome} SOCIAL`,
    identidadeGenero: 'Masculino',
    orientacaoSexual: 'Heterossexual',
    filiacao1: 'FILIACAO 1 E2E',
    filiacao2: 'FILIACAO 2 E2E',
    filiacao3: 'FILIACAO 3 E2E',
    estadoCivil: 'Solteiro',
    nis: docs.nis,
    cpf: docs.cpf,
    rg: String(Math.floor(Math.random() * 100_000_000)).padStart(8, '0'),
  };
}

async function preencherDadosBasicos(page, dados) {
  await page.fill('#txtNomeCompleto', dados.nome);
  await page.fill('#txtDataNascimento', dados.dataNascimento);

  if (dados.sexo) await page.selectOption('#slcSexo', dados.sexo);
  if (dados.nomeSocial !== undefined) await page.fill('#txtNomeSocial', dados.nomeSocial || '');
  if (dados.identidadeGenero) await page.selectOption('#slcIdentidadeGenero', dados.identidadeGenero);
  if (dados.orientacaoSexual) await page.selectOption('#slcOrientacaoSexual', dados.orientacaoSexual);
  if (dados.filiacao1 !== undefined) await page.fill('#txtFiliacao1', dados.filiacao1 || '');
  if (dados.filiacao2 !== undefined) await page.fill('#txtFiliacao2', dados.filiacao2 || '');
  if (dados.filiacao3 !== undefined) await page.fill('#txtFiliacao3', dados.filiacao3 || '');
  if (dados.estadoCivil) await page.selectOption('#slcEstadoCivil', dados.estadoCivil);

  if (dados.nis !== undefined) await page.fill('#txtNis', dados.nis || '');
  if (dados.cpf !== undefined) await page.fill('#txtCpf', dados.cpf || '');
  if (dados.rg !== undefined) await page.fill('#txtRg', dados.rg || '');
}

async function preencherPrimeiroAcolhimento(page, cfg) {
  if (cfg.primeiraVez) {
    await page.check('#radAcolhimento1');
    await expect(page.locator('#boxQuantasVezes')).toBeHidden();
  } else {
    await page.check('#radAcolhimento2');
    await expect(page.locator('#boxQuantasVezes')).toBeVisible();
    await page.fill('#txtReincidencia', String(cfg.reincidencia || 2));
  }
}

async function preencherContatosTelefonicos(page, dados = {}) {
  await page.fill('#txtTelefonePessoal', dados.pessoal || '11987654321');
  await page.fill('#txtTelefoneResidencial', dados.residencial || '1134567890');
}

async function preencherCamposContatoReferenciaSemAdicionar(page, contato) {
  if (!contato) return;
  await page.fill('#txtNomeReferencia', contato.nome);
  await page.fill('#txtTelefoneReferencia', contato.telefone || '11999998888');
  await page.selectOption('#slcGrauParentesco', contato.parentesco || 'Pai');
  if (contato.tipoServico) {
    await page.selectOption('#slcTipoServico', contato.tipoServico);
  }
  console.log(
    `[E2E][CONTATO] campos preenchidos (sem +) -> nome="${contato.nome}", telefone="${contato.telefone || '11999998888'}"`
  );
}

async function adicionarContatoReferencia(page, contato, options = {}) {
  const timeoutMs = options.timeoutMs ?? 10_000;
  await preencherCamposContatoReferenciaSemAdicionar(page, contato);

  const btnAdicionarContato = page.locator('button[onclick*="cadastraContatoReferencia"]');
  await expect(btnAdicionarContato).toBeVisible();
  await btnAdicionarContato.scrollIntoViewIfNeeded();
  console.log(`[E2E][CONTATO] adicionando contato -> nome="${contato.nome}"`);

  const waitCadastroContato = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/cadastro-acolhido/model/cadastraContatoReferencia.php') &&
      resp.request().method() === 'POST',
    { timeout: timeoutMs }
  );
  const waitListaContatos = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/cadastro-acolhido/model/listaContatosReferencia.php') &&
      resp.request().method() === 'POST',
    { timeout: timeoutMs }
  );

  try {
    await btnAdicionarContato.click({ timeout: timeoutMs });
    await waitCadastroContato;
    await waitListaContatos;
  } catch (error) {
    throw new Error(
      `Falha ao adicionar contato de referencia via botao +. Possivel causa: inclusao so funciona apos salvar o acolhido. Detalhe: ${error.message}`
    );
  }

  await expect(page.locator('#boxContatosReferencia')).toContainText(contato.nome);
}

async function obterMunicipioSelecionado(page) {
  return page.locator('#slcMunicipios').evaluate((el) => {
    const select = /** @type {HTMLSelectElement} */ (el);
    const opt = select.options[select.selectedIndex];
    return {
      value: select.value,
      text: opt ? String(opt.textContent || '').trim() : '',
      valid: Boolean(select.value) && select.value !== '0',
    };
  });
}

async function selecionarMunicipioFallback(page) {
  const municipioFallback = await page.locator('#slcMunicipios option').evaluateAll((options) => {
    const normalizarTextoLocal = (texto) =>
      String(texto || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();

    const infos = options.map((o) => ({
      value: String(o.value || ''),
      text: String(o.textContent || '').trim(),
    }));

    const optCampinas = infos.find((o) => normalizarTextoLocal(o.text).includes('campinas'));
    const optQualquer = infos.find((o) => o.value && o.value !== '0');
    const opt = optCampinas || optQualquer || null;

    return {
      value: opt ? opt.value : '',
      text: opt ? opt.text : '',
      amostra: infos.slice(0, 10),
    };
  });

  if (municipioFallback.value && municipioFallback.value !== '0') {
    console.log(
      `[E2E][ENDERECO] municipio nao foi auto-selecionado pelo CEP; selecionando manualmente: "${municipioFallback.text}".`
    );
    await page.selectOption('#slcMunicipios', municipioFallback.value);
  } else {
    console.log(
      `[E2E][ENDERECO] fallback sem municipio valido. Amostra: ${JSON.stringify(municipioFallback.amostra)}`
    );
  }
}

async function selecionarMunicipioPorTexto(page, textoPreferido) {
  const alvoNormalizado = normalizarTexto(textoPreferido);
  const encontrado = await page.locator('#slcMunicipios option').evaluateAll((options, alvo) => {
    const normalizarTextoLocal = (texto) =>
      String(texto || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();

    const infos = options.map((o) => ({
      value: String(o.value || ''),
      text: String(o.textContent || '').trim(),
    }));

    return (
      infos.find((o) => o.value && o.value !== '0' && normalizarTextoLocal(o.text).includes(alvo)) ||
      null
    );
  }, alvoNormalizado);

  if (encontrado && encontrado.value && encontrado.value !== '0') {
    console.log(
      `[E2E][ENDERECO] forçando municipio manualmente por texto -> "${encontrado.text}" (preferido="${textoPreferido}").`
    );
    await page.selectOption('#slcMunicipios', encontrado.value);
    return true;
  }

  return false;
}

async function selecionarMunicipioPorValor(page, valorMunicipio) {
  const valor = String(valorMunicipio || '').trim();
  if (!valor || valor === '0') return false;

  const existe = await page.locator('#slcMunicipios option').evaluateAll(
    (options, alvo) => options.some((o) => String(o.value || '') === alvo),
    valor
  );

  if (!existe) {
    console.log(`[E2E][ENDERECO] municipio_id fallback nao encontrado na lista: ${valor}`);
    return false;
  }

  await page.selectOption('#slcMunicipios', valor);
  const municipio = await obterMunicipioSelecionado(page);
  console.log(
    `[E2E][ENDERECO] forçando municipio manualmente por valor -> value="${municipio.value}", texto="${municipio.text}"`
  );
  return municipio.valid;
}

async function preencherEndereco(page, cfg) {
  if (cfg.fixo) {
    await page.check('#radEndereco1');
    await expect(page.locator('#boxEndereco')).toBeVisible();
    await expect(page.locator('#boxSituacaoRua')).toBeHidden();
    await expect(page.locator('#slcMunicipios')).toBeVisible();
    await expect
      .poll(async () => page.locator('#slcMunicipios option').count())
      .toBeGreaterThan(1);

    console.log('[E2E][ENDERECO] consultando CEP via mock ViaCEP...');
    await page.fill('#txtCep', cfg.cep || '01009-000');
    await page.locator('#txtCep').press('Tab');

    await expect.poll(async () => page.locator('#txtEndereco').inputValue()).toBe('Praca da Se');
    await expect.poll(async () => page.locator('#txtBairro').inputValue()).toBe('Se');

    let municipio = await obterMunicipioSelecionado(page);
    if (!municipio.valid) {
      await selecionarMunicipioFallback(page);
      municipio = await obterMunicipioSelecionado(page);
    }
    if (!municipio.valid && cfg.municipioFallbackValor) {
      await selecionarMunicipioPorValor(page, cfg.municipioFallbackValor);
      municipio = await obterMunicipioSelecionado(page);
    }
    if (!municipio.valid && cfg.municipioFallbackTexto) {
      await selecionarMunicipioPorTexto(page, cfg.municipioFallbackTexto);
      municipio = await obterMunicipioSelecionado(page);
    }
    await expect.poll(async () => (await obterMunicipioSelecionado(page)).valid).toBe(true);
    municipio = await obterMunicipioSelecionado(page);
    console.log(
      `[E2E][ENDERECO] municipio selecionado -> value="${municipio.value}", texto="${municipio.text}"`
    );
    console.log('[E2E][ENDERECO] CEP preenchido e endereco retornado com sucesso.');

    await page.fill('#txtNumero', cfg.numero || '100');
    await page.fill('#txtComplemento', cfg.complemento || 'APTO 1');
  } else {
    await page.check('#radEndereco2');
    await expect(page.locator('#boxSituacaoRua')).toBeVisible();
    await expect(page.locator('#boxEndereco')).toBeHidden();
    if (cfg.tempoIndex) {
      await page.selectOption('#slcTempoSituacaoRua', { index: cfg.tempoIndex });
    } else if (cfg.tempoValor) {
      await page.selectOption('#slcTempoSituacaoRua', cfg.tempoValor);
    } else {
      await page.selectOption('#slcTempoSituacaoRua', { index: 1 });
    }
  }
}

async function preencherComorbidades(page, cfg) {
  if (cfg.outra) {
    if (cfg.comuns && cfg.comuns.includes('PressaoAlta')) await page.check('#chkComorbidade1');
    else await page.check('#chkComorbidade1');
    await page.check('#chkComorbidade11');
    await expect(page.locator('#boxTipoAcompanhamento')).toBeVisible();
    await page.fill('#txtOutraComorbidade', cfg.outraTexto || 'Comorbidade E2E');
  } else {
    await page.check('#chkComorbidade12');
    await expect(page.locator('#boxTipoAcompanhamento')).toBeHidden();
  }
}

async function preencherDeficiencia(page, cfg) {
  if (cfg.tem) {
    await page.check('#radDeficiencia1');
    await expect(page.locator('#boxDeficiencia')).toBeVisible();
    if (cfg.tipoIds && cfg.tipoIds.length) {
      for (const id of cfg.tipoIds) await page.check(id);
    } else {
      await page.check('#chkDeficiencia5');
    }
    if (cfg.cuidadoIds && cfg.cuidadoIds.length) {
      for (const id of cfg.cuidadoIds) await page.check(id);
    } else {
      await page.check('#chkCuidadosTerceiros3');
    }
  } else {
    await page.check('#radDeficiencia2');
    await expect(page.locator('#boxDeficiencia')).toBeHidden();
  }
}

async function preencherSubstanciaPreferencia(page, cfg) {
  if (cfg.outra) {
    if (cfg.baseId) await page.check(cfg.baseId);
    else await page.check('#chkSubstanciaPreferencia2');
    await page.check('#chkSubstanciaPreferencia12');
    await expect(page.locator('#boxOutraSubstanciaPreferencia')).toBeVisible();
    await page.fill('#txtOutraSubstanciaPreferencia', cfg.outraTexto || 'Substancia E2E');
  } else {
    await page.check(cfg.baseId || '#chkSubstanciaPreferencia4');
    await expect(page.locator('#boxOutraSubstanciaPreferencia')).toBeHidden();
  }
  await page.selectOption('#slcTempoUtilizaSubstancia', { index: cfg.tempoIndex || 2 });
}

async function preencherUnidadeHospitalar(page, cfg) {
  if (cfg.status === 'NAO') {
    await page.check('#radUnidadeHospitalar2');
    await expect(page.locator('#boxUnidadeHospitalar')).toBeHidden();
    await expect(page.locator('#boxOutraUnidadeHospitalar')).toBeHidden();
    return;
  }

  await page.check('#radUnidadeHospitalar1');
  await expect(page.locator('#boxUnidadeHospitalar')).toBeVisible();

  if (cfg.tipo === 'OUTRA') {
    await page.selectOption('#slcUnidadeHospitalar', 'Outra');
    await expect(page.locator('#boxOutraUnidadeHospitalar')).toBeVisible();
    await page.fill('#txtOutraUnidadeHospitalar', cfg.outraTexto || 'Hospital E2E');
  } else {
    await page.selectOption('#slcUnidadeHospitalar', cfg.valor || 'Hospital Lacan');
    await expect(page.locator('#boxOutraUnidadeHospitalar')).toBeHidden();
  }
}

async function preencherHistorico(page, texto) {
  await page.fill('#txtHistorico', texto);
}

async function preencherQuestionarioMinimoSeguro(page, options = {}) {
  await preencherPrimeiroAcolhimento(page, options.acolhimento || { primeiraVez: true });
  await preencherEndereco(page, options.endereco || { fixo: false, tempoIndex: 1 });
  await preencherComorbidades(page, options.comorbidade || { outra: false });
  await preencherDeficiencia(page, options.deficiencia || { tem: false });
  await preencherSubstanciaPreferencia(page, options.substancia || { outra: false });
  await preencherUnidadeHospitalar(page, options.unidadeHospitalar || { status: 'NAO' });
  if (options.historico) {
    await preencherHistorico(page, options.historico);
  }
}

function esperarRespostaCarregaAcolhido(page, timeout = 15_000) {
  return page
    .waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/cadastro-acolhido/model/carregaAcolhido.php') &&
        resp.request().method() === 'POST',
      { timeout }
    )
    .catch(() => null);
}

async function esperarModoEdicaoEstavel(page, options = {}) {
  const maxTentativas = options.maxTentativas || 3;
  const timeout = options.timeout || 20_000;
  const logPrefix = options.logPrefix || '[E2E][EDICAO]';

  await expect(page.locator('#formAcolhido')).toBeVisible({ timeout });

  let ultimoErro;
  for (let tentativa = 1; tentativa <= maxTentativas; tentativa += 1) {
    try {
      await expect
        .poll(async () => page.locator('#btnEditar').count(), { timeout })
        .toBeGreaterThan(0);
      await expect(page.locator('#btnEditar')).toBeVisible({ timeout });
      await expect(page.locator('#btnRegistrar')).toHaveCount(0, { timeout: 5_000 });
      return;
    } catch (error) {
      ultimoErro = error;

      const btnEditarCount = await page.locator('#btnEditar').count();
      const btnRegistrarCount = await page.locator('#btnRegistrar').count();
      console.log(
        `${logPrefix} tentativa ${tentativa}/${maxTentativas} sem btnEditar apos abrir URL. btnEditar=${btnEditarCount}, btnRegistrar=${btnRegistrarCount}, url="${page.url()}"`
      );

      if (tentativa === maxTentativas) {
        throw ultimoErro;
      }

      // Sintoma mais comum do flake: URL mascarada de edicao aberta, mas tela ainda montada
      // como cadastro novo (#btnRegistrar). Recarrega e espera a chamada que repopula a edicao.
      console.log(`${logPrefix} recarregando URL de edicao para estabilizar montagem do modo edicao...`);
      const waitCarregaReload = esperarRespostaCarregaAcolhido(page, 15_000);
      await page.reload({ waitUntil: 'domcontentloaded' });
      await expect(page.locator('#formAcolhido')).toBeVisible({ timeout });
      await waitCarregaReload;
      await page.waitForTimeout(400);
    }
  }

  throw ultimoErro || new Error('Modo edicao nao estabilizou.');
}

async function salvarCadastroComSucesso(page, getAlert) {
  await page.click('#btnRegistrar');
  await expect.poll(getAlert).toContain('registradas com sucesso');
  await page.waitForURL(/\/coed\/cadastro-acolhido\/.+/);
  await esperarModoEdicaoEstavel(page, { timeout: 20_000, maxTentativas: 3 });
}

async function criarAcolhidoBaseParaEdicao(page, prefixo = 'E2E EDICAO BASE', options = {}) {
  const getAlert = await capturarAlert(page);

  await abrirCadastroNovo(page, { mockCep: Boolean(options.mockCep) });
  const dadosBase = await criarDadosBasicosUnicos(page, prefixo);
  const dados = { ...dadosBase, ...(options.dados || {}) };

  await preencherDadosBasicos(page, dados);

  if (options.contatosTelefonicos !== false) {
    await preencherContatosTelefonicos(page, options.contatosTelefonicos || {});
  }

  await preencherQuestionarioMinimoSeguro(page, options.questionario || { historico: 'Historico base para edicao E2E' });
  await salvarCadastroComSucesso(page, getAlert);

  const urlEdicao = page.url();
  const resumo = await lerResumoAcolhido(page);

  if (options.log !== false) {
    logTabela('[E2E][EDICAO][BASE] cadastro criado para edicao', {
      url: urlEdicao,
      nome: resumo.nome,
      nascimento: resumo.nascimento,
      telefonePessoal: resumo.telefonePessoal,
      telefoneResidencial: resumo.telefoneResidencial,
      nis: resumo.nis,
      cpf: resumo.cpf,
    });
  }

  return { getAlert, urlEdicao, dados, resumo };
}

async function abrirEdicaoPorUrl(page, urlEdicao, options = {}) {
  if (options.mockCep) {
    await mockViaCep01009000(page);
  }

  const waitCarrega = esperarRespostaCarregaAcolhido(page, 15_000);
  await page.goto(urlEdicao, { waitUntil: 'domcontentloaded' });
  await expect(page.locator('#formAcolhido')).toBeVisible({ timeout: 20_000 });
  await waitCarrega;

  await esperarModoEdicaoEstavel(page, { timeout: 20_000, maxTentativas: 3 });
}

async function salvarEdicaoComSucesso(page, getAlert) {
  const waitEdita = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/cadastro-acolhido/model/editaAcolhido.php') &&
      resp.request().method() === 'POST'
  );

  await page.click('#btnEditar');
  await waitEdita;

  await expect.poll(getAlert).toContain('alteradas com sucesso');
  await esperarModoEdicaoEstavel(page, { timeout: 20_000, maxTentativas: 2 });
}

async function campoInvalido(page, selector) {
  return page.locator(selector).evaluate((el) => !el.checkValidity());
}

async function lerResumoAcolhido(page) {
  return {
    url: page.url(),
    nome: await page.locator('#txtNomeCompleto').inputValue(),
    nascimento: await page.locator('#txtDataNascimento').inputValue(),
    nis: await page.locator('#txtNis').inputValue(),
    cpf: await page.locator('#txtCpf').inputValue(),
    rg: await page.locator('#txtRg').inputValue(),
    telefonePessoal: await page.locator('#txtTelefonePessoal').inputValue(),
    telefoneResidencial: await page.locator('#txtTelefoneResidencial').inputValue(),
    cep: await page.locator('#txtCep').inputValue(),
    endereco: await page.locator('#txtEndereco').inputValue(),
    bairro: await page.locator('#txtBairro').inputValue(),
    historico: await page.locator('#txtHistorico').inputValue(),
  };
}

async function assertCheckbox(page, selector, checked = true) {
  if (checked) await expect(page.locator(selector)).toBeChecked();
  else await expect(page.locator(selector)).not.toBeChecked();
}

module.exports = {
  expect,
  logInicioTeste,
  logTabela,
  capturarAlert,
  consultarDuplicidadeAcolhido,
  gerarDocumentosNovosUnicos,
  mockViaCep01009000,
  loginComoPortaEntrada,
  abrirCadastroAcolhidoPeloMenu,
  abrirCadastroNovo,
  criarDadosBasicosUnicos,
  preencherDadosBasicos,
  preencherPrimeiroAcolhimento,
  preencherContatosTelefonicos,
  preencherCamposContatoReferenciaSemAdicionar,
  adicionarContatoReferencia,
  preencherEndereco,
  preencherComorbidades,
  preencherDeficiencia,
  preencherSubstanciaPreferencia,
  preencherUnidadeHospitalar,
  preencherHistorico,
  preencherQuestionarioMinimoSeguro,
  salvarCadastroComSucesso,
  criarAcolhidoBaseParaEdicao,
  abrirEdicaoPorUrl,
  salvarEdicaoComSucesso,
  campoInvalido,
  lerResumoAcolhido,
  assertCheckbox,
  obterMunicipioSelecionado,
  selecionarMunicipioPorTexto,
  selecionarMunicipioPorValor,
  gerarSufixoUnico,
};
