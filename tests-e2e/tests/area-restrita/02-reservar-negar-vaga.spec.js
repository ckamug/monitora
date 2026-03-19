const { test, expect } = require('@playwright/test');
const ar = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'area-restrita_02-reservar-negar-vaga' });

async function esperarTabelaSolicitacoesExecutora(page) {
  await expect(page.locator('#boxTabelaSolicitacoesVagas')).toBeVisible();
  await expect
    .poll(async () => page.locator('#boxSolicitacoesVagas table').count(), { timeout: 20_000 })
    .toBeGreaterThan(0);
}

async function estadoListaSolicitacoesExecutora(page) {
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

async function esperarListaExecutoraAtualizarPosAcao(page) {
  await expect
    .poll(async () => (await estadoListaSolicitacoesExecutora(page)).estado, { timeout: 20_000 })
    .toMatch(/oculta|visivel_com_tabela/);
}

async function contarBotoesDaSolicitacaoNaTabela(page, solicitacaoId) {
  return page.locator('#boxSolicitacoesVagas button[onclick]').evaluateAll((botoes, id) => {
    const padrao = new RegExp(`pergunta\\(\\s*1\\s*,\\s*[01]\\s*,\\s*${String(id)}\\s*\\)`);
    return botoes.filter((btn) => padrao.test(btn.getAttribute('onclick') || '')).length;
  }, solicitacaoId);
}

async function assertSolicitacaoSaiuDaListaOuTabelaFoiOcultada(page, solicitacaoId) {
  await esperarListaExecutoraAtualizarPosAcao(page);

  const estado = await estadoListaSolicitacoesExecutora(page);
  if (estado.estado === 'oculta') return;

  await expect
    .poll(async () => contarBotoesDaSolicitacaoNaTabela(page, solicitacaoId), { timeout: 10_000 })
    .toBe(0);
}

function extrairSolicitacaoIdDoOnclick(onclick) {
  const match = String(onclick || '').match(/pergunta\(\s*1\s*,\s*[01]\s*,\s*(\d+)\s*\)/);
  return match ? match[1] : '';
}

async function selecionarSolicitacaoPorBotao(page, textoBotao, options = {}) {
  await esperarTabelaSolicitacoesExecutora(page);

  const filtroLinha = options.textoLinhaDeveConter
    ? ar.normalizarTexto(options.textoLinhaDeveConter)
    : '';

  const botoes = page.getByRole('button', { name: new RegExp(textoBotao, 'i') });
  const totalBotoes = await botoes.count();
  if (totalBotoes === 0) {
    throw new Error(
      `Nenhuma solicitacao disponivel para a acao "${textoBotao}" no perfil 4 (Executora).`
    );
  }

  for (let i = 0; i < totalBotoes; i += 1) {
    const botao = botoes.nth(i);
    const linha = botao.locator('xpath=ancestor::tr');
    const textoLinha = ((await linha.count()) > 0 ? await linha.first().innerText() : '').trim();

    if (filtroLinha && !ar.normalizarTexto(textoLinha).includes(filtroLinha)) {
      continue;
    }

    const onclick = (await botao.getAttribute('onclick')) || '';
    const solicitacaoId = extrairSolicitacaoIdDoOnclick(onclick);
    if (!solicitacaoId) {
      throw new Error(
        `Nao foi possivel extrair solicitacao_id do onclick do botao "${textoBotao}": ${onclick}`
      );
    }

    ar.h.logTabela('[E2E][AREA-RESTRITA][EXECUTORA] solicitacao selecionada', {
      acao: textoBotao,
      solicitacao_id: solicitacaoId,
      linha: textoLinha.slice(0, 220),
      totalBotoes: String(totalBotoes),
    });

    return { botao, solicitacaoId, textoLinha, totalBotoes };
  }

  throw new Error(
    `Nenhuma linha da executora com botao "${textoBotao}" correspondeu ao filtro "${options.textoLinhaDeveConter}".`
  );
}

test.describe('Area Restrita - Executora (perfil 4) - Responder Solicitacoes', () => {
  test('reservar vaga', async ({ page, browser }) => {
    ar.h.logInicioTeste('[AREA-RESTRITA][EXECUTORA] reservar vaga');
    const getAlert = await ar.h.capturarAlert(page);

    const criado = await ar.prepararSolicitacaoEncaminhadaParaOsc(browser, {
      executoraId: 86,
      nomeOsc: 'OSC Teste DNI',
      prefixo: 'E2E AREA EXECUTORA RESERVA',
    });

    await ar.loginComoExecutoraPerfil4(page, 'OSC Teste DNI');
    const alvo = await selecionarSolicitacaoPorBotao(page, 'Reservar vaga', {
      textoLinhaDeveConter: criado.cpf || criado.nome,
    });

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

    await alvo.botao.click();
    await expect(page.locator('#confirmacaoModal')).toHaveClass(/show/);
    await expect(page.locator('#corpoModal')).toContainText('Deseja reservar a vaga');

    await page.locator('#boxBotoesModal').getByRole('button', { name: /Reservar/i }).click();
    await waitAltera;
    await expect.poll(getAlert).toContain('Vaga reservada');
    await waitRecarregaLista;

    await expect(page.locator('#confirmacaoModal')).not.toHaveClass(/show/);
    await assertSolicitacaoSaiuDaListaOuTabelaFoiOcultada(page, alvo.solicitacaoId);
  });

  test('negar vaga (com justificativa)', async ({ page, browser }) => {
    ar.h.logInicioTeste('[AREA-RESTRITA][EXECUTORA] negar vaga');
    const getAlert = await ar.h.capturarAlert(page);

    const criado = await ar.prepararSolicitacaoEncaminhadaParaOsc(browser, {
      executoraId: 86,
      nomeOsc: 'OSC Teste DNI',
      prefixo: 'E2E AREA EXECUTORA NEGA',
    });

    await ar.loginComoExecutoraPerfil4(page, 'OSC Teste DNI');
    const alvo = await selecionarSolicitacaoPorBotao(page, 'Negar', {
      textoLinhaDeveConter: criado.cpf || criado.nome,
    });

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

    await alvo.botao.click();
    await expect(page.locator('#justificativaModal')).toHaveClass(/show/);
    await expect(page.locator('#txtJustificativa')).toBeVisible();

    const justificativa = `Justificativa E2E ${ar.h.gerarSufixoUnico()}`;
    await page.fill('#txtJustificativa', justificativa);
    await page
      .locator('#boxBotoesModalJustificativa')
      .getByRole('button', { name: /Negar vaga/i })
      .click();

    await waitAltera;
    await expect.poll(getAlert).toContain('Vaga negada');
    await waitRecarregaLista;

    await expect(page.locator('#justificativaModal')).not.toHaveClass(/show/);
    await assertSolicitacaoSaiuDaListaOuTabelaFoiOcultada(page, alvo.solicitacaoId);
  });
});
