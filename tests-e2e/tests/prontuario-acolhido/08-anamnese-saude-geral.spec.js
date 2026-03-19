const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const an = require('./_anamnese_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_08-anamnese-saude-geral' });

test.describe('Prontuario Acolhido - Anamnese - Saude Geral', () => {
  test('registra e recarrega dados da aba saude geral', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][ANAMNESE][SAUDE GERAL] cadastro');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    await an.prepararAcolhidoEmAcolhimentoNoAnamnese(page, browser, token, getAlert, {
      prefixo: `E2E ANAMNESE SG ${token}`,
    });

    await page.click('#saudeGeral-tab');
    await expect(page.locator('#tabSaudeGeral')).toHaveClass(/active|show/);

    await expect
      .poll(async () => page.locator("#tabSaudeGeral input[name='chkPossuiDoenca[]']").count(), { timeout: 20_000 })
      .toBeGreaterThan(0);

    const doencaValor = await an.selecionarPrimeiroCheckboxComFallback(
      page,
      "#tabSaudeGeral input[name='chkPossuiDoenca[]'][data-outra='1']",
      "#tabSaudeGeral input[name='chkPossuiDoenca[]']"
    );

    const selecionouOutra = await page.evaluate((valorSelecionado) => {
      const item = Array.from(document.querySelectorAll("#tabSaudeGeral input[name='chkPossuiDoenca[]']")).find(
        (el) => String(el.value || '') === String(valorSelecionado || '')
      );
      return !!item && String(item.getAttribute('data-outra')) === '1';
    }, doencaValor);

    const dados = {
      outraDoenca: `OUTRA DOENCA ${token}`,
      ondeTratamento: `UBS ${token}`,
    };

    if (selecionouOutra) {
      await expect(page.locator('#tabSaudeGeral #boxOutraDoenca')).toBeVisible();
      await page.fill('#tabSaudeGeral #txtOutraDoencaSaudeGeral', dados.outraDoenca);
    }

    await page.check('#tabSaudeGeral #radTratamentoMedicoAmbulatorial1');
    await expect(page.locator('#tabSaudeGeral #boxOndeTratamentoMedicoAmbulatorial')).toBeVisible();
    await page.fill('#tabSaudeGeral #txtOndeTratamentoMedicoAmbulatorial', dados.ondeTratamento);

    const waitSalvar = page.waitForResponse(
      (resp) =>
        (resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraSaudeGeral.php') ||
          resp.url().includes('/public/componentes/prontuario_acolhido/model/editaSaudeGeral.php')) &&
        resp.request().method() === 'POST'
    );
    const waitCarrega = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/prontuario_acolhido/model/carregaSaudeGeral.php') &&
        resp.request().method() === 'POST'
    );

    await page.click('#tabSaudeGeral #btnCadIdentificacao');
    await waitSalvar;
    await waitCarrega;

    await expect(page.locator('#tabSaudeGeral #radTratamentoMedicoAmbulatorial1')).toBeChecked();
    await expect(page.locator('#tabSaudeGeral #txtOndeTratamentoMedicoAmbulatorial')).toHaveValue(dados.ondeTratamento);
    await expect(page.locator(`#tabSaudeGeral input[name='chkPossuiDoenca[]'][value='${doencaValor}']`)).toBeChecked();

    if (selecionouOutra) {
      await expect(page.locator('#tabSaudeGeral #txtOutraDoencaSaudeGeral')).toHaveValue(dados.outraDoenca);
    }
  });
});

