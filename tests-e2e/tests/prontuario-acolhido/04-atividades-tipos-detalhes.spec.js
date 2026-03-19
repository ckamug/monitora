const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_04-atividades-tipos-detalhes' });

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
  await expect(page.locator('#atividades-tab')).toBeVisible();
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

async function abrirAbaAtividades(page) {
  await page.click('#atividades-tab');
  await expect(page.locator('#tabAtividades')).toHaveClass(/active|show/);

  await expect
    .poll(async () => page.locator('#tabAtividades #slcTiposAtendimentos option').count(), { timeout: 20_000 })
    .toBeGreaterThan(1);
}

async function listarTiposAtendimentoAtividades(page) {
  const opcoes = await page.locator('#tabAtividades #slcTiposAtendimentos option').evaluateAll((opts) =>
    opts
      .map((o) => ({ value: String(o.value || '').trim(), text: String(o.textContent || '').trim() }))
      .filter((o) => o.value !== '' && o.value !== '0' && o.text !== '')
  );

  return opcoes;
}

async function selecionarDetalhePorTipoAtividade(page, tipo, token, indice) {
  await abrirAbaAtividades(page);
  await garantirColapsoAberto(page, {
    colapsoSelector: '#colAnotacoesAtividades',
    botaoSelector: '#boxBotaoNovaAnotacaoAtividades button',
  });

  await page.selectOption('#tabAtividades #slcTiposAtendimentos', String(tipo.value));

  if (String(tipo.value) === '3') {
    await expect(page.locator('#boxSubTiposAtendimentosAtividades #txtOutraAtividade')).toBeVisible({
      timeout: 20_000,
    });
    const detalheOutro = `E2E ATV OUTRO ${indice}_${token}`;
    await page.fill('#boxSubTiposAtendimentosAtividades #txtOutraAtividade', detalheOutro);
    return detalheOutro;
  }

  await expect
    .poll(
      async () => page.locator('#boxSubTiposAtendimentosAtividades #slcSubTiposAtendimentos option').count(),
      { timeout: 20_000 }
    )
    .toBeGreaterThan(1);

  const subtipo = await page
    .locator('#boxSubTiposAtendimentosAtividades #slcSubTiposAtendimentos option')
    .evaluateAll((opts) => {
      const validas = opts
        .map((o) => ({ value: String(o.value || '').trim(), text: String(o.textContent || '').trim() }))
        .filter((o) => o.value !== '' && o.value !== '0' && o.text !== '');
      return validas.length ? validas[0] : null;
    });

  if (!subtipo || !subtipo.value) {
    throw new Error(`Nenhum subtipo disponivel para tipo_atendimento_id=${tipo.value}`);
  }

  await page.selectOption('#boxSubTiposAtendimentosAtividades #slcSubTiposAtendimentos', subtipo.value);
  return subtipo.text;
}

async function registrarAcaoAtividades(page, { dataYmd, descricao }) {
  await garantirColapsoAberto(page, {
    colapsoSelector: '#colAnotacoesAtividades',
    botaoSelector: '#boxBotaoNovaAnotacaoAtividades button',
  });

  await page.fill('#txtDataAnotacaoAtividades', dataYmd);
  await page.fill('#txtDescricaoAcaoAtividades', descricao);

  const waitCadastra = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraAcaoAtv.php') &&
      resp.request().method() === 'POST'
  );
  const waitListaAcoes = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/listaAcoes.php') &&
      resp.request().method() === 'POST'
  );

  await page.locator('#colAnotacoesAtividades button[onclick*="cadastraAcaoAtv"]').click();

  await waitCadastra;
  await waitListaAcoes;
  await expect(page.locator('#tabAtividades #boxListaAcoesAtv')).toContainText(descricao);
}

test.describe('Prontuario Acolhido - Atividades', () => {
  test('registra atividades com detalhes conforme cada tipo de atendimento', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][ATIVIDADES] tipos e detalhes por tipo');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    const criado = await ar.prepararSolicitacaoEncaminhadaParaOsc(browser, {
      executoraId: 86,
      nomeOsc: 'OSC Teste DNI',
      prefixo: `E2E PRONTUARIO ATV ${token}`,
    });

    const idAcolhido = extrairIdDoCadastro(criado.urlAcolhido);
    expect(idAcolhido).not.toBe('');

    await ar.loginComoExecutoraPerfil4(page, 'OSC Teste DNI');

    await reservarVagaExecutora(page, getAlert, {
      textoLinhaDeveConter: criado.cpf || criado.nome,
    });

    await confirmarAcolhimento(page, getAlert, idAcolhido);
    await abrirProntuarioAcolhimento(page, idAcolhido);
    await abrirAbaAtividades(page);

    const tipos = await listarTiposAtendimentoAtividades(page);
    expect(tipos.length).toBeGreaterThan(1);

    ar.h.logTabela('[E2E][PRONTUARIO][ATIVIDADES] tipos encontrados', {
      total: tipos.length,
      tipos: tipos.map((t) => `${t.value}:${t.text}`).join(' | '),
    });

    const dataBase = ymdComDeslocamento(0);
    const registros = [];

    for (let i = 0; i < tipos.length; i += 1) {
      const tipo = tipos[i];
      const detalhe = await selecionarDetalhePorTipoAtividade(page, tipo, token, i + 1);
      const descricao = `E2E ATV TIPO_${tipo.value}_${i + 1}_${token}`;

      await registrarAcaoAtividades(page, {
        dataYmd: dataBase,
        descricao,
      });

      registros.push({
        tipoValue: tipo.value,
        tipoText: tipo.text,
        detalhe,
        descricao,
      });
    }

    const cards = page.locator('#tabAtividades #boxListaAcoesAtv .alert');
    await expect
      .poll(async () => {
        const textos = await cards.allTextContents();
        const listaNormalizada = textos.map((t) => normalizarTexto(t));
        return registros.every((r) =>
          listaNormalizada.some((t) => t.includes(normalizarTexto(r.descricao)))
        );
      }, { timeout: 20_000 })
      .toBe(true);

    const textosCards = await cards.allTextContents();
    for (const r of registros) {
      const cardTexto = textosCards.find((t) => normalizarTexto(t).includes(normalizarTexto(r.descricao)));
      expect(cardTexto).toBeTruthy();
      expect(normalizarTexto(cardTexto)).toContain(normalizarTexto(r.tipoText));
      expect(normalizarTexto(cardTexto)).toContain(normalizarTexto(r.detalhe));
    }

    ar.h.logTabela('[E2E][PRONTUARIO][ATIVIDADES] registros criados', {
      total: registros.length,
      detalhes: registros.map((r) => `${r.tipoValue}:${r.tipoText} -> ${r.detalhe}`).join(' | '),
      urlFinal: page.url(),
    });
  });
});
