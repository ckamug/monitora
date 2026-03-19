const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_01-equipe-tecnica-datas' });

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

function ymdComDeslocamento(dias = 0) {
  const data = new Date();
  data.setHours(12, 0, 0, 0);
  data.setDate(data.getDate() + dias);
  const y = String(data.getFullYear());
  const m = String(data.getMonth() + 1).padStart(2, '0');
  const d = String(data.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
}

function ymdParaBr(ymd) {
  const [y, m, d] = String(ymd || '').split('-');
  return `${d}/${m}/${y}`;
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
  await expect(page.locator('#equipeTecnica-tab')).toBeVisible();
}

async function garantirColapsoAberto(page, { colapsoSelector, botaoSelector }) {
  const colapso = page.locator(colapsoSelector);
  const botao = page.locator(botaoSelector).first();

  await expect(botao).toBeVisible();

  for (let tentativa = 0; tentativa < 6; tentativa += 1) {
    const classes = String((await colapso.getAttribute('class')) || '');
    if (/\bshow\b/.test(classes)) {
      return;
    }

    if (/\bcollapsing\b/.test(classes)) {
      await page.waitForTimeout(200);
      continue;
    }

    await botao.click();
    await page.waitForTimeout(250);
  }

  await expect(colapso).toHaveClass(/show/, { timeout: 10_000 });
}

async function registrarAcaoEquipeTecnica(page, { dataYmd, descricao }) {
  await garantirColapsoAberto(page, {
    colapsoSelector: '#colAnotacoesTecnicas',
    botaoSelector: '#boxBotaoNovaAnotacaoEquipeTecnica button',
  });

  await page.fill('#txtDataAnotacaoTecnica', dataYmd);
  await page.fill('#txtDescricaoAcaoEquipeTecnica', descricao);

  const waitCadastra = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraAcaoEt.php') &&
      resp.request().method() === 'POST'
  );
  const waitListaAcoes = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/listaAcoes.php') &&
      resp.request().method() === 'POST'
  );

  await page.locator('#colAnotacoesTecnicas button[onclick*="cadastraAcaoEt"]').click();

  await waitCadastra;
  await waitListaAcoes;
  await expect(page.locator('#boxListaAcoesEt')).toContainText(descricao);
}

test.describe('Prontuario Acolhido - Equipe Tecnica', () => {
  test('registra atendimento com data atual, futura e antiga', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][EQUIPE TECNICA] datas atual/futura/antiga');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    const criado = await ar.prepararSolicitacaoEncaminhadaParaOsc(browser, {
      executoraId: 86,
      nomeOsc: 'OSC Teste DNI',
      prefixo: `E2E PRONTUARIO ET ${token}`,
    });

    const idAcolhido = extrairIdDoCadastro(criado.urlAcolhido);
    expect(idAcolhido).not.toBe('');

    ar.h.logTabela('[E2E][PRONTUARIO][SETUP] acolhido criado', {
      nome: criado.nome,
      cpf: criado.cpf,
      nis: criado.nis,
      urlCadastro: criado.urlAcolhido,
      idAcolhido,
    });

    await ar.loginComoExecutoraPerfil4(page, 'OSC Teste DNI');

    await reservarVagaExecutora(page, getAlert, {
      textoLinhaDeveConter: criado.cpf || criado.nome,
    });

    await confirmarAcolhimento(page, getAlert, idAcolhido);
    await abrirProntuarioAcolhimento(page, idAcolhido);

    const dataAtual = ymdComDeslocamento(0);
    const dataFutura = ymdComDeslocamento(10);
    const dataAntiga = ymdComDeslocamento(-10);

    const descricaoAtual = `E2E ET DATA_ATUAL ${token}`;
    const descricaoFutura = `E2E ET DATA_FUTURA ${token}`;
    const descricaoAntiga = `E2E ET DATA_ANTIGA ${token}`;

    await registrarAcaoEquipeTecnica(page, { dataYmd: dataAtual, descricao: descricaoAtual });
    await registrarAcaoEquipeTecnica(page, { dataYmd: dataFutura, descricao: descricaoFutura });
    await registrarAcaoEquipeTecnica(page, { dataYmd: dataAntiga, descricao: descricaoAntiga });

    const cards = page.locator('#boxListaAcoesEt .alert');
    await expect
      .poll(async () => {
        const textos = await cards.allTextContents();
        const listaNormalizada = textos.map((t) => normalizarTexto(t));
        const alvoAtual = normalizarTexto(descricaoAtual);
        const alvoFutura = normalizarTexto(descricaoFutura);
        const alvoAntiga = normalizarTexto(descricaoAntiga);
        return (
          listaNormalizada.some((t) => t.includes(alvoAtual)) &&
          listaNormalizada.some((t) => t.includes(alvoFutura)) &&
          listaNormalizada.some((t) => t.includes(alvoAntiga))
        );
      }, { timeout: 20_000 })
      .toBe(true);

    const textosCards = await cards.allTextContents();
    const idxFutura = textosCards.findIndex((t) => normalizarTexto(t).includes(normalizarTexto(descricaoFutura)));
    const idxAtual = textosCards.findIndex((t) => normalizarTexto(t).includes(normalizarTexto(descricaoAtual)));
    const idxAntiga = textosCards.findIndex((t) => normalizarTexto(t).includes(normalizarTexto(descricaoAntiga)));

    expect(idxFutura).toBeGreaterThanOrEqual(0);
    expect(idxAtual).toBeGreaterThanOrEqual(0);
    expect(idxAntiga).toBeGreaterThanOrEqual(0);

    expect(idxFutura).toBeLessThan(idxAtual);
    expect(idxAtual).toBeLessThan(idxAntiga);

    expect(textosCards[idxFutura]).toContain(ymdParaBr(dataFutura));
    expect(textosCards[idxAtual]).toContain(ymdParaBr(dataAtual));
    expect(textosCards[idxAntiga]).toContain(ymdParaBr(dataAntiga));

    ar.h.logTabela('[E2E][PRONTUARIO][EQUIPE TECNICA] registros', {
      dataAtual,
      dataFutura,
      dataAntiga,
      idxFutura,
      idxAtual,
      idxAntiga,
      urlFinal: page.url(),
    });
  });
});
