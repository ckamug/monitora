const { test, expect } = require('@playwright/test');
const ar = require('../area-restrita/_helpers');
const an = require('./_anamnese_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'prontuario-acolhido_09-anamnese-avaliacao-psicossocial' });

test.describe('Prontuario Acolhido - Anamnese - Avaliacao Psicossocial', () => {
  test('registra e recarrega dados da aba avaliacao psicossocial', async ({ page, browser }) => {
    ar.h.logInicioTeste('[PRONTUARIO][ANAMNESE][AVALIACAO PSICOSSOCIAL] cadastro');
    const getAlert = await ar.h.capturarAlert(page);
    const token = ar.h.gerarSufixoUnico();

    await an.prepararAcolhidoEmAcolhimentoNoAnamnese(page, browser, token, getAlert, {
      prefixo: `E2E ANAMNESE AP ${token}`,
    });

    await page.click('#avaliacaoPsicossocial-tab');
    await expect(page.locator('#tabAvaliacaoPsicossocial')).toHaveClass(/active|show/);

    await expect
      .poll(async () => page.locator("#tabAvaliacaoPsicossocial input[name='chkEspecificidades[]']").count(), {
        timeout: 20_000,
      })
      .toBeGreaterThan(0);
    await expect
      .poll(async () => page.locator("#tabAvaliacaoPsicossocial input[name='radAcompanhamento']").count(), {
        timeout: 20_000,
      })
      .toBeGreaterThan(0);

    const especificidadeValor = await an.selecionarPrimeiroCheckboxComFallback(
      page,
      "#tabAvaliacaoPsicossocial input[name='chkEspecificidades[]'][data-outra='1']",
      "#tabAvaliacaoPsicossocial input[name='chkEspecificidades[]']"
    );

    const selecionouOutraEspecificidade = await page.evaluate((valorSelecionado) => {
      const item = Array.from(
        document.querySelectorAll("#tabAvaliacaoPsicossocial input[name='chkEspecificidades[]']")
      ).find((el) => String(el.value || '') === String(valorSelecionado || ''));
      return !!item && String(item.getAttribute('data-outra')) === '1';
    }, especificidadeValor);

    const acompanhamentoValor = await an.selecionarPrimeiroRadioComFallback(
      page,
      "#tabAvaliacaoPsicossocial input[name='radAcompanhamento'][data-exige-onde='1']",
      "#tabAvaliacaoPsicossocial input[name='radAcompanhamento']"
    );

    const exigeOndeAcompanhamento = await page.evaluate((valorSelecionado) => {
      const item = Array.from(
        document.querySelectorAll("#tabAvaliacaoPsicossocial input[name='radAcompanhamento']")
      ).find((el) => String(el.value || '') === String(valorSelecionado || ''));
      return !!item && String(item.getAttribute('data-exige-onde')) === '1';
    }, acompanhamentoValor);

    const dados = {
      outroTranstorno: `OUTRO TRANSTORNO ${token}`,
      ondeAcompanhamento: `ACOMPANHAMENTO ${token}`,
    };

    if (selecionouOutraEspecificidade) {
      await expect(page.locator('#tabAvaliacaoPsicossocial #boxOutroTranstorno')).toBeVisible();
      await page.fill('#tabAvaliacaoPsicossocial #txtOutroTranstornoPsicossocial', dados.outroTranstorno);
    }

    if (exigeOndeAcompanhamento) {
      await expect(page.locator('#tabAvaliacaoPsicossocial #boxOndeAcompanhamento')).toBeVisible();
      await page.fill('#tabAvaliacaoPsicossocial #txtOndeAcompanhamento', dados.ondeAcompanhamento);
    }

    const waitSalvar = page.waitForResponse(
      (resp) =>
        (resp.url().includes('/public/componentes/prontuario_acolhido/model/cadastraAvaliacaoPsicossocial.php') ||
          resp.url().includes('/public/componentes/prontuario_acolhido/model/editaAvaliacaoPsicossocial.php')) &&
        resp.request().method() === 'POST'
    );
    const waitCarrega = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/prontuario_acolhido/model/carregaAvaliacaoPsicossocial.php') &&
        resp.request().method() === 'POST'
    );

    await page.click('#tabAvaliacaoPsicossocial #btnCadIdentificacao');
    await waitSalvar;
    await waitCarrega;

    await expect(
      page.locator(`#tabAvaliacaoPsicossocial input[name='chkEspecificidades[]'][value='${especificidadeValor}']`)
    ).toBeChecked();
    await expect
      .poll(() => an.valorRadioSelecionado(page, "#tabAvaliacaoPsicossocial input[name='radAcompanhamento']"))
      .toBe(acompanhamentoValor);

    if (selecionouOutraEspecificidade) {
      await expect(page.locator('#tabAvaliacaoPsicossocial #txtOutroTranstornoPsicossocial')).toHaveValue(
        dados.outroTranstorno
      );
    }

    if (exigeOndeAcompanhamento) {
      await expect(page.locator('#tabAvaliacaoPsicossocial #txtOndeAcompanhamento')).toHaveValue(
        dados.ondeAcompanhamento
      );
    }
  });
});
