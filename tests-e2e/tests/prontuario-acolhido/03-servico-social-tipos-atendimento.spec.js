const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_03-servico-social-tipos-atendimento' });

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
  await expect(page.locator('#servicoSocial-tab')).toBeVisible();
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

async function abrirAbaServicoSocial(page) {
  await page.click('#servicoSocial-tab');
  await expect(page.locator('#tabServicoSocial')).toHaveClass(/active|show/);

  await expect
    .poll(async () => page.locator('#tabServicoSocial #slcTiposAtendimentos option').count(), {
      timeout: 20_000,
    })
    .toBeGreaterThan(1);
}

async function listarTiposAtendimentoServicoSocial(page) {
  const opcoes = await page.locator('#tabServicoSocial #slcTiposAtendimentos option').evaluateAll((opts) =>
    opts
      .map((o) => ({ value: String(o.value || '').trim(), text: String(o.textContent || '').trim() }))
      .filter((o) => o.value !== '' && o.value !== '0' && o.text !== '')
  );

  return opcoes;
}

async function registrarAcaoServicoSocial(page, { dataYmd, tipoAtendimentoId, descricao, sigiloso }) {
  await garantirColapsoAberto(page, {
    colapsoSelector: '#colAnotacoesServicoSocial',
    botaoSelector: '#tabServicoSocial button[data-bs-target="#colAnotacoesServicoSocial"]',
  });

  await page.fill('#txtDataAnotacaoServicoSocial', dataYmd);
  await page.selectOption('#tabServicoSocial #slcTiposAtendimentos', String(tipoAtendimentoId));
  await page.fill('#txtDescricaoAcaoServicoSocial', descricao);

  const chkSigiloso = page.locator('#chkSigilosoSs');
  if (sigiloso) {
    await chkSigiloso.check();
  } else {
    await chkSigiloso.uncheck();
  }

  const waitCadastra = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraAcaoSs.php') &&
      resp.request().method() === 'POST'
  );
  const waitListaAcoes = page.waitForResponse(
    (resp) =>
      resp.url().includes('/public/componentes/prontuario_acolhido/model/listaAcoes.php') &&
      resp.request().method() === 'POST'
  );

  await page.locator('#colAnotacoesServicoSocial button[onclick*="cadastraAcaoSs"]').click();

  await waitCadastra;
  await waitListaAcoes;
  await expect(page.locator('#boxListaAcoesSs')).toContainText(descricao);
}

test.describe('Prontuario Acolhido - Servico Social', () => {
  test('registra servico social cobrindo diferentes tipos de atendimento', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][SERVICO SOCIAL] tipos de atendimento');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    const criado = await ar.prepararSolicitacaoEncaminhadaParaOsc(browser, {
      executoraId: 86,
      nomeOsc: 'OSC Teste DNI',
      prefixo: `E2E PRONTUARIO SS ${token}`,
    });

    const idAcolhido = extrairIdDoCadastro(criado.urlAcolhido);
    expect(idAcolhido).not.toBe('');

    await ar.loginComoExecutoraPerfil4(page, 'OSC Teste DNI');

    await reservarVagaExecutora(page, getAlert, {
      textoLinhaDeveConter: criado.cpf || criado.nome,
    });

    await confirmarAcolhimento(page, getAlert, idAcolhido);
    await abrirProntuarioAcolhimento(page, idAcolhido);
    await abrirAbaServicoSocial(page);

    const tipos = await listarTiposAtendimentoServicoSocial(page);
    expect(tipos.length).toBeGreaterThan(1);

    ar.h.logTabela('[E2E][PRONTUARIO][SERVICO SOCIAL] tipos encontrados', {
      total: tipos.length,
      tipos: tipos.map((t) => `${t.value}:${t.text}`).join(' | '),
    });

    const dataBase = ymdComDeslocamento(0);
    const registros = [];

    for (let i = 0; i < tipos.length; i += 1) {
      const tipo = tipos[i];
      const descricao = `E2E SS TIPO_${tipo.value}_${i + 1}_${token}`;
      const sigiloso = i === 0;

      await registrarAcaoServicoSocial(page, {
        dataYmd: dataBase,
        tipoAtendimentoId: tipo.value,
        descricao,
        sigiloso,
      });

      registros.push({
        ...tipo,
        descricao,
        sigiloso,
      });
    }

    const cards = page.locator('#boxListaAcoesSs .alert');
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
      expect(normalizarTexto(cardTexto)).toContain(normalizarTexto(r.text));
    }

    ar.h.logTabela('[E2E][PRONTUARIO][SERVICO SOCIAL] registros criados', {
      total: registros.length,
      tipos: registros.map((r) => `${r.value}:${r.text}`).join(' | '),
      urlFinal: page.url(),
    });
  });
});
