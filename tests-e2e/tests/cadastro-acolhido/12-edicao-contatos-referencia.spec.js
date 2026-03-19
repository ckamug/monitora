const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_12-edicao-contatos-referencia' });


test.describe('Cadastro Acolhido - Edicao - Contatos de Referencia', () => {
  test('multiplos contatos via + funcionam em modo edicao', async ({ page }) => {
    h.logInicioTeste('[EDICAO][CONTATO REF] + apos salvar');
    const base = await h.criarAcolhidoBaseParaEdicao(page, 'E2E EDICAO CONTATO REF');

    await h.abrirEdicaoPorUrl(page, base.urlEdicao);

    await h.adicionarContatoReferencia(
      page,
      { nome: 'CONTATO EDICAO 1', telefone: '11970001111', parentesco: 'Pai' },
      { timeoutMs: 8000 }
    );
    await h.adicionarContatoReferencia(
      page,
      { nome: 'CONTATO EDICAO 2', telefone: '11980002222', parentesco: 'Vizinhos' },
      { timeoutMs: 8000 }
    );

    await expect(page.locator('#boxContatosReferencia')).toContainText('CONTATO EDICAO 1');
    await expect(page.locator('#boxContatosReferencia')).toContainText('CONTATO EDICAO 2');
  });
});

