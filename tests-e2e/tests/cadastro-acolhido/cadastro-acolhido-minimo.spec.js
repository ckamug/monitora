// tests/acolhidos-cadastro.spec.js
//
// Fluxo coberto (caixa preta):
// 1) login
// 2) selecao do vinculo "Porta de Entrada" (perfil_id = 3)
// 3) menu Cadastros -> Acolhidos -> Cadastrar Acolhido
// 4) validacoes de obrigatorios, duplicidade (NIS/CPF) e cadastros novos
//
// Pre-requisitos:
// - E2E_CPF / E2E_SENHA no .env (usuario com acesso ao vinculo de perfil 3)
//
// Observacoes:
// - Os cenarios [NOVO] alteram o banco (criam registros).
// - CPF/NIS "novos" sao gerados aleatoriamente e pre-validados via endpoint
//   consultaCpf.php (sem acesso direto ao banco).
// - Para depuracao com logs em ordem, prefira rodar com --workers=1.

/*
COMO RODAR ESTE TESTE:
npx playwright test tests/cadastro-acolhido/cadastro-acolhido-minimo.spec.js --headed
*/

const { test, expect } = require('@playwright/test');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_cadastro-acolhido-minimo' });

function somenteDigitos(valor) {
  return String(valor || '').replace(/\D/g, '');
}

function extrairIdCadastroPelaUrl(url) {
  const match = String(url || '').match(/\/cadastro-acolhido\/([^/?#]+)/);
  return match ? String(match[1] || '') : '';
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

  // Evita todos os digitos iguais.
  do {
    base = String(Math.floor(Math.random() * 1_000_000_000)).padStart(9, '0');
  } while (/^(\d)\1{8}$/.test(base));

  const d1 = calcularDigitoCpf(base);
  const d2 = calcularDigitoCpf(base + d1);
  return formatarCpf(base + d1 + d2);
}

function gerarNisAleatorio() {
  // O sistema exige 11 digitos para NIS; geramos no formato aceito pelo front e back.
  return String(Math.floor(Math.random() * 100_000_000_000)).padStart(11, '0');
}

async function consultarDuplicidadeAcolhido(page, { cpf = '', nis = '' }) {
  const baseUrl = process.env.BASE_URL || 'http://localhost/coed/';
  const url = new URL('public/componentes/cadastro-acolhido/model/consultaCpf.php', baseUrl).toString();

  const response = await page.request.post(url, {
    form: {
      cpf,
      nis,
      id: '',
      id_atual: '',
    },
  });

  if (!response.ok()) {
    throw new Error(`Falha ao consultar duplicidade (${response.status()}) em ${url}`);
  }

  return response.json();
}

async function gerarDocumentosNovosUnicos(page) {
  // Gera CPF+NIS juntos e valida no endpoint do sistema para reduzir reuso de dados.
  for (let tentativa = 1; tentativa <= 20; tentativa += 1) {
    const cpf = gerarCpfValidoAleatorio();
    const nis = gerarNisAleatorio();
    const retorno = await consultarDuplicidadeAcolhido(page, { cpf, nis });

    if (!retorno || retorno.usuario_existe !== true) {
      return { cpf, nis };
    }
  }

  throw new Error(
    'Nao foi possivel gerar CPF/NIS unicos apos 20 tentativas (consultaCpf.php retornou duplicidade).'
  );
}

async function gerarDocumentosNovosParaCadastro(page, { usarNis, usarCpf }) {
  // Permite montar cenarios [NOVO] com combinacoes diferentes:
  // NIS+CPF, so NIS, so CPF, ou sem documentos.
  if (!usarNis && !usarCpf) {
    return { nis: '', cpf: '' };
  }

  if (usarNis && usarCpf) {
    return gerarDocumentosNovosUnicos(page);
  }

  for (let tentativa = 1; tentativa <= 20; tentativa += 1) {
    const nis = usarNis ? gerarNisAleatorio() : '';
    const cpf = usarCpf ? gerarCpfValidoAleatorio() : '';
    const retorno = await consultarDuplicidadeAcolhido(page, { cpf, nis });

    if (!retorno || retorno.usuario_existe !== true) {
      return { nis, cpf };
    }
  }

  throw new Error(
    `Nao foi possivel gerar documento novo (${usarNis ? 'NIS' : ''}${usarNis && usarCpf ? '+' : ''}${usarCpf ? 'CPF' : ''}) apos 20 tentativas.`
  );
}

async function capturarAlert(page) {
  let texto = '';
  page.on('dialog', async (dialog) => {
    texto = dialog.message();
    await dialog.accept();
  });
  return () => texto;
}

async function loginComoPortaEntrada(page) {
  await page.goto('login', { waitUntil: 'domcontentloaded' });
  await page.fill('#txtLogin', process.env.E2E_CPF);
  await page.click('#btnStep');
  await page.fill('#txtSenha', process.env.E2E_SENHA);
  await page.click('#btnAcessar');

  // O login pode redirecionar direto (1 vinculo) ou mostrar lista de vinculos.
  await expect
    .poll(async () => {
      const urlAtual = page.url();
      const qtdVinculos = await page.locator('#boxLocais .card').count();

      if (!urlAtual.includes('/coed/login')) return 'redirect';
      if (qtdVinculos > 0) return 'vinculos';
      return 'aguardando';
    })
    .not.toBe('aguardando');

  // Se redirecionou direto, nao ha selecao de vinculo.
  if (!page.url().includes('/coed/login')) {
    return;
  }

  const cardsVinculo = page.locator('#boxLocais .card');
  await expect(cardsVinculo.first()).toBeVisible();

  let vinculo = cardsVinculo
    .filter({ hasText: /Perfil:\s*Porta de Entrada/i })
    .first();

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
  // A tela de acolhidos deve ser acessada no perfil 3 (Porta de Entrada).
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

  const submenuAberto = await submenuCadastros.evaluate((el) =>
    el.classList.contains('show')
  );
  if (!submenuAberto) {
    await menuCadastros.click();
  }

  await expect(submenuCadastros).toHaveClass(/show/);
  await expect(linkAcolhidos).toBeVisible();
  await linkAcolhidos.click();

  await page.waitForURL(/\/coed\/acolhidos/);
  await page.getByRole('button', { name: /Cadastrar Acolhido/i }).click();

  await page.waitForURL(/\/coed\/cadastro-acolhido(\/.*)?$/);
  await expect(page.locator('#formAcolhido')).toBeVisible();
  await expect(page.locator('#btnRegistrar')).toBeVisible();
}

async function preencherMinimo(page, dados) {
  await page.fill('#txtNomeCompleto', dados.nome);
  await page.fill('#txtDataNascimento', dados.dataNascimento);
  await page.fill('#txtNis', dados.nis ?? '');
  await page.fill('#txtCpf', dados.cpf ?? '');
}

async function campoInvalido(page, selector) {
  return page.locator(selector).evaluate((el) => !el.checkValidity());
}

function logInicioTeste(nome) {
  console.log(`[E2E][START] ${nome}`);
}

function logTabela(titulo, objeto) {
  console.log(titulo);
  console.table(objeto);
}

async function lerResumoAcolhido(page) {
  const nome = await page.locator('#txtNomeCompleto').inputValue();
  const nascimento = await page.locator('#txtDataNascimento').inputValue();
  const nis = await page.locator('#txtNis').inputValue();
  const cpf = await page.locator('#txtCpf').inputValue();

  let acao = 'desconhecido';
  if (await page.locator('#btnEditar').count()) acao = 'editar';
  else if (await page.locator('#btnRegistrar').count()) acao = 'cadastrar';

  return {
    url: page.url(),
    nome,
    nascimento,
    nis,
    cpf,
    acao,
  };
}

async function cadastrarPessoaMinimaParaTesteDuplicidade(page, getAlert, { nomeBase }) {
  const docsNovos = await gerarDocumentosNovosParaCadastro(page, { usarNis: true, usarCpf: true });
  const dadosNovo = {
    nome: `${nomeBase} ${Date.now()}`,
    dataNascimento: '01/01/2000',
    nis: docsNovos.nis,
    cpf: docsNovos.cpf,
  };

  await preencherMinimo(page, dadosNovo);
  await page.click('#btnRegistrar');

  await expect.poll(getAlert).toContain('registradas com sucesso');
  await page.waitForURL(/\/coed\/cadastro-acolhido\/.+/);

  const idCadastro = extrairIdCadastroPelaUrl(page.url());
  expect(idCadastro).not.toBe('');

  return {
    ...dadosNovo,
    idCadastro,
  };
}

const casosObrigatorios = [
  {
    nome: 'bloqueia cadastro sem nome e sem data',
    preencher: async () => {},
    invalidos: ['#txtNomeCompleto', '#txtDataNascimento'],
  },
  {
    nome: 'bloqueia cadastro sem nome',
    preencher: async (page) => {
      await page.fill('#txtDataNascimento', '01/01/2000');
    },
    invalidos: ['#txtNomeCompleto'],
  },
  {
    nome: 'bloqueia cadastro sem data de nascimento',
    preencher: async (page) => {
      await page.fill('#txtNomeCompleto', 'TESTE E2E OBRIGATORIO');
    },
    invalidos: ['#txtDataNascimento'],
  },
];

for (const c of casosObrigatorios) {
  test(c.nome, async ({ page }) => {
    logInicioTeste(c.nome);
    const getAlert = await capturarAlert(page);

    await loginComoPortaEntrada(page);
    await abrirCadastroAcolhidoPeloMenu(page);

    await c.preencher(page);

    const urlAntes = page.url();
    await page.click('#btnRegistrar');

    // Comportamento atual (jQuery Validate)
    await expect.poll(getAlert).toContain('Preencha todos os campos obrigat');

    // Nao deve cadastrar/redirecionar
    await expect(page).toHaveURL(urlAntes);

    // Campos esperados continuam invalidos
    for (const selector of c.invalidos) {
      await expect.poll(() => campoInvalido(page, selector)).toBe(true);
    }

    await expect(page.locator('#btnRegistrar')).toBeVisible();
  });
}

test.describe('Cadastro de Acolhidos (caixa preta)', () => {
  const cenariosCadastroNovo = [
    {
      nomeTeste: '[NOVO] cadastro novo com NIS e CPF',
      logLabel: 'NOVO nis+cpf',
      usarNis: true,
      usarCpf: true,
      nomeBase: 'TESTE E2E NOVO NISCPF',
    },
    {
      nomeTeste: '[NOVO] cadastro novo so NIS',
      logLabel: 'NOVO so nis',
      usarNis: true,
      usarCpf: false,
      nomeBase: 'TESTE E2E NOVO NIS',
    },
    {
      nomeTeste: '[NOVO] cadastro novo so CPF',
      logLabel: 'NOVO so cpf',
      usarNis: false,
      usarCpf: true,
      nomeBase: 'TESTE E2E NOVO CPF',
    },
    {
      nomeTeste: '[NOVO] cadastro novo sem NIS e sem CPF',
      logLabel: 'NOVO sem nis/cpf',
      usarNis: false,
      usarCpf: false,
      nomeBase: 'TESTE E2E NOVO SEMDOC',
    },
  ];

  for (const c of cenariosCadastroNovo) {
    test(c.nomeTeste, async ({ page }) => {
      logInicioTeste(`Cadastro de Acolhidos > ${c.nomeTeste}`);
      const getAlert = await capturarAlert(page);
      const dadosNovo = {
        nome: `${c.nomeBase} ${Date.now()}`,
        dataNascimento: '01/01/2000',
      };

      await loginComoPortaEntrada(page);
      await abrirCadastroAcolhidoPeloMenu(page);

      const docsNovos = await gerarDocumentosNovosParaCadastro(page, {
        usarNis: c.usarNis,
        usarCpf: c.usarCpf,
      });

      logTabela(`[E2E][${c.logLabel}] dados gerados`, {
        nome: dadosNovo.nome,
        nascimento: dadosNovo.dataNascimento,
        nis: docsNovos.nis,
        cpf: docsNovos.cpf,
      });

      await preencherMinimo(page, {
        nome: dadosNovo.nome,
        dataNascimento: dadosNovo.dataNascimento,
        nis: docsNovos.nis,
        cpf: docsNovos.cpf,
      });

      await page.click('#btnRegistrar');

      await expect.poll(getAlert).toContain('registradas com sucesso');
      await page.waitForURL(/\/coed\/cadastro-acolhido\/.+/);

      await expect(page.locator('#txtNomeCompleto')).toHaveValue(dadosNovo.nome);
      await expect(page.locator('#txtNis')).toHaveValue(docsNovos.nis);
      await expect(page.locator('#txtCpf')).toHaveValue(docsNovos.cpf);

      const nomeFinal = await page.locator('#txtNomeCompleto').inputValue();
      const nascimentoFinal = await page.locator('#txtDataNascimento').inputValue();
      const nisFinal = await page.locator('#txtNis').inputValue();
      const cpfFinal = await page.locator('#txtCpf').inputValue();
      logTabela(`[E2E][${c.logLabel}] cadastrado`, {
        url: page.url(),
        nome: nomeFinal,
        nascimento: nascimentoFinal,
        nis: nisFinal,
        cpf: cpfFinal,
      });
    });
  }

  test('NIS existente carrega pessoa correta', async ({ page }) => {
    logInicioTeste('Cadastro de Acolhidos > NIS existente carrega pessoa correta');
    const getAlert = await capturarAlert(page);

    await loginComoPortaEntrada(page);
    await abrirCadastroAcolhidoPeloMenu(page);

    const criado = await cadastrarPessoaMinimaParaTesteDuplicidade(page, getAlert, {
      nomeBase: 'TESTE E2E DUP NIS',
    });

    await abrirCadastroAcolhidoPeloMenu(page);
    await page.fill('#txtNis', criado.nis);
    await page.click('#txtNomeCompleto'); // forÃ§a blur no NIS

    await expect
      .poll(() => extrairIdCadastroPelaUrl(page.url()), { timeout: 15_000 })
      .toBe(criado.idCadastro);

    await expect(page.locator('#txtNis')).toHaveValue(criado.nis);
    await expect(page.locator('#txtCpf')).toHaveValue(criado.cpf);
    await expect(page.locator('#txtNomeCompleto')).toHaveValue(criado.nome);

    const resumo = await lerResumoAcolhido(page);
    logTabela('[E2E][NIS existente] retorno', {
      url: resumo.url,
      acao: resumo.acao,
      nome: resumo.nome,
      nascimento: resumo.nascimento,
      nis: resumo.nis,
      cpf: resumo.cpf,
      idCadastroEsperado: criado.idCadastro,
    });
  });

  test('CPF existente carrega pessoa correta', async ({ page }) => {
    logInicioTeste('Cadastro de Acolhidos > CPF existente carrega pessoa correta');
    const getAlert = await capturarAlert(page);

    await loginComoPortaEntrada(page);
    await abrirCadastroAcolhidoPeloMenu(page);

    const criado = await cadastrarPessoaMinimaParaTesteDuplicidade(page, getAlert, {
      nomeBase: 'TESTE E2E DUP CPF',
    });

    await abrirCadastroAcolhidoPeloMenu(page);
    await page.fill('#txtCpf', criado.cpf);
    await page.click('#txtNomeCompleto'); // forÃ§a blur no CPF

    await expect
      .poll(() => extrairIdCadastroPelaUrl(page.url()), { timeout: 15_000 })
      .toBe(criado.idCadastro);

    await expect(page.locator('#txtCpf')).toHaveValue(criado.cpf);
    await expect(page.locator('#txtNis')).toHaveValue(criado.nis);
    await expect(page.locator('#txtNomeCompleto')).toHaveValue(criado.nome);

    const resumo = await lerResumoAcolhido(page);
    logTabela('[E2E][CPF existente] retorno', {
      url: resumo.url,
      acao: resumo.acao,
      nome: resumo.nome,
      nascimento: resumo.nascimento,
      nis: resumo.nis,
      cpf: resumo.cpf,
      idCadastroEsperado: criado.idCadastro,
    });
  });
});
