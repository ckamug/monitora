const { expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');

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

function ymdHoje() {
  const data = new Date();
  data.setHours(12, 0, 0, 0);
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
  if (typeof getAlert === 'function') {
    await expect.poll(() => normalizarTexto(getAlert())).toContain('vaga reservada');
  }
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
  if (typeof getAlert === 'function') {
    await expect.poll(() => normalizarTexto(getAlert())).toContain('acolhimento confirmado');
  }
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
  await expect(page.locator('#btnAnamnese')).toBeVisible();
}

async function abrirAnamnese(page) {
  await page.click('#btnAnamnese');
  await expect(page.locator('#cardAnamnese')).toBeVisible();
  await expect(page.locator('#btnAnamnese')).toHaveClass(/btn-success/);
  await expect(page.locator('#tabIdentificacao')).toBeVisible();
}

async function prepararAcolhidoEmAcolhimentoNoAnamnese(
  page,
  browser,
  token,
  getAlert,
  { prefixo = `E2E ANAMNESE ${token}`, executoraId = 86, nomeOsc = 'OSC Teste DNI' } = {}
) {
  const criado = await ar.prepararSolicitacaoEncaminhadaParaOsc(browser, {
    executoraId,
    nomeOsc,
    prefixo,
  });

  const idAcolhido = extrairIdDoCadastro(criado.urlAcolhido);
  expect(idAcolhido).not.toBe('');

  await ar.loginComoExecutoraPerfil4(page, nomeOsc);
  await reservarVagaExecutora(page, getAlert, {
    textoLinhaDeveConter: criado.cpf || criado.nome,
  });
  await confirmarAcolhimento(page, getAlert, idAcolhido);
  await abrirProntuarioAcolhimento(page, idAcolhido);
  await abrirAnamnese(page);

  return { criado, idAcolhido };
}

async function contarOpcoesValidas(page, selector, invalidos = ['', '0']) {
  return page.locator(`${selector} option`).evaluateAll(
    (opts, invalidValues) =>
      opts.filter((opt) => {
        if (opt.disabled) return false;
        const value = String(opt.value || '').trim();
        const texto = String(opt.textContent || '').trim();
        return texto !== '' && !invalidValues.includes(value);
      }).length,
    invalidos
  );
}

async function aguardarOpcoesSelect(page, selector, { timeout = 20_000, minOpcoes = 1, invalidos = ['', '0'] } = {}) {
  await expect.poll(() => contarOpcoesValidas(page, selector, invalidos), { timeout }).toBeGreaterThanOrEqual(minOpcoes);
}

async function selecionarPrimeiraOpcaoValida(
  page,
  selector,
  { invalidos = ['', '0'], timeout = 20_000 } = {}
) {
  await aguardarOpcoesSelect(page, selector, { timeout, minOpcoes: 1, invalidos });
  const opcao = await page.locator(`${selector} option`).evaluateAll(
    (opts, invalidValues) => {
      const primeira = opts.find((opt) => {
        if (opt.disabled) return false;
        const value = String(opt.value || '').trim();
        const texto = String(opt.textContent || '').trim();
        return texto !== '' && !invalidValues.includes(value);
      });
      if (!primeira) return null;
      return {
        value: String(primeira.value || ''),
        text: String(primeira.textContent || '').trim(),
      };
    },
    invalidos
  );

  if (!opcao || !opcao.value) {
    return null;
  }

  await page.selectOption(selector, opcao.value);
  return opcao;
}

async function selecionarPrimeiroRadio(page, selector, { timeout = 20_000 } = {}) {
  await expect.poll(async () => page.locator(selector).count(), { timeout }).toBeGreaterThan(0);
  const radio = page.locator(selector).first();
  await radio.check();
  return radio.inputValue();
}

async function selecionarPrimeiroRadioComFallback(
  page,
  preferencialSelector,
  fallbackSelector,
  { timeout = 20_000 } = {}
) {
  await expect
    .poll(async () => page.locator(fallbackSelector).count(), { timeout })
    .toBeGreaterThan(0);

  if ((await page.locator(preferencialSelector).count()) > 0) {
    const radio = page.locator(preferencialSelector).first();
    await radio.check();
    return radio.inputValue();
  }

  return selecionarPrimeiroRadio(page, fallbackSelector, { timeout });
}

async function selecionarPrimeiroCheckbox(page, selector, { timeout = 20_000 } = {}) {
  await expect.poll(async () => page.locator(selector).count(), { timeout }).toBeGreaterThan(0);
  const checkbox = page.locator(selector).first();
  await checkbox.check();
  return checkbox.inputValue();
}

async function selecionarPrimeiroCheckboxComFallback(
  page,
  preferencialSelector,
  fallbackSelector,
  { timeout = 20_000 } = {}
) {
  await expect
    .poll(async () => page.locator(fallbackSelector).count(), { timeout })
    .toBeGreaterThan(0);

  if ((await page.locator(preferencialSelector).count()) > 0) {
    const checkbox = page.locator(preferencialSelector).first();
    await checkbox.check();
    return checkbox.inputValue();
  }

  return selecionarPrimeiroCheckbox(page, fallbackSelector, { timeout });
}

async function valorRadioSelecionado(page, selector) {
  return page.evaluate((sel) => {
    const selecionado = document.querySelector(`${sel}:checked`);
    return selecionado ? String(selecionado.value || '') : '';
  }, selector);
}

module.exports = {
  normalizarTexto,
  extrairIdDoCadastro,
  ymdHoje,
  prepararAcolhidoEmAcolhimentoNoAnamnese,
  aguardarOpcoesSelect,
  selecionarPrimeiraOpcaoValida,
  selecionarPrimeiroRadio,
  selecionarPrimeiroRadioComFallback,
  selecionarPrimeiroCheckbox,
  selecionarPrimeiroCheckboxComFallback,
  valorRadioSelecionado,
};

