const { test, expect } = require('@playwright/test');
const ar = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'area-restrita_01-encaminhar-para-osc' });

test.describe('Area Restrita - Conector - Encaminhamento para OSC', () => {
  test('modal de encaminhamento: bloqueia envio sem escolher OSC', async ({ page, browser }) => {
    ar.h.logInicioTeste('[AREA-RESTRITA][CONECTOR] validacao modal sem OSC');
    const getAlert = await ar.h.capturarAlert(page);

    const criado = await ar.criarSolicitacaoVagaPerfil3EmContexto(browser, 'E2E AREA CONECTOR MODAL');

    await ar.loginComoConector(page);
    await ar.abrirEncaminhamentoAteEncontrarOsc(page, 86, 'OSC Teste DNI', {
      textoLinhaDeveConter: criado.cpf || criado.nome,
    });

    await expect(page.locator('#encaminhamentoModal')).toHaveClass(/show/);
    await expect(page.locator('#slcOscsEncaminhamento')).toBeVisible();
    await page.selectOption('#slcOscsEncaminhamento', '0');
    await expect(page.locator('#slcOscsEncaminhamento')).toHaveValue('0');

    await page.click('#btnConfirmaEncaminhamento');

    await expect.poll(getAlert).toContain('Selecione uma OSC para encaminhar');
    await expect(page.locator('#encaminhamentoModal')).toHaveClass(/show/);
    await expect(page.locator('#hidSolicitacaoEncaminhamento')).not.toHaveValue('');
  });

  test('encaminha solicitacao para OSC Teste DNI (executora_id=86)', async ({ page, browser }) => {
    ar.h.logInicioTeste('[AREA-RESTRITA][CONECTOR] encaminhar para OSC Teste DNI');

    const criado = await ar.criarSolicitacaoVagaPerfil3EmContexto(browser, 'E2E AREA CONECTOR ENCAMINHA');

    await ar.loginComoConector(page);
    await ar.encaminharSolicitacaoParaOsc(page, {
      executoraId: 86,
      nomeOsc: 'OSC Teste DNI',
      textoLinhaDeveConter: criado.cpf || criado.nome,
    });
  });
});
