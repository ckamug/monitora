const { test, expect } = require('@playwright/test');
const h = require('./_helpers');
const { habilitarAuditoria } = require('../_audit');

habilitarAuditoria(test, { funcionalidade: 'cadastro-acolhido_17-solicitacao-vaga' });

function normalizarTexto(texto) {
  return String(texto || '')
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase();
}

test.describe('Cadastro Acolhido - Solicitacao de Vaga', () => {
  test('cadastro minimo + sexo masculino -> solicitar vaga (servico_id=1)', async ({ page }) => {
    h.logInicioTeste('[SOLICITACAO VAGA] cadastro minimo + solicitar vaga');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const base = await h.criarDadosBasicosUnicos(page, 'E2E SOLIC VAGA');
    const dadosMinimos = {
      nome: base.nome,
      dataNascimento: base.dataNascimento,
      sexo: 'Masculino',
      nis: base.nis,
      cpf: base.cpf,
    };

    h.logTabela('[E2E][SOLICITACAO VAGA] dados do cadastro', {
      nome: dadosMinimos.nome,
      nascimento: dadosMinimos.dataNascimento,
      sexo: dadosMinimos.sexo,
      nis: dadosMinimos.nis,
      cpf: dadosMinimos.cpf,
    });

    await h.preencherDadosBasicos(page, dadosMinimos);
    await expect(page.locator('#slcSexo')).toHaveValue('Masculino');

    // O cadastro "minimo" da pessoa ainda precisa de respostas minimas do questionario para salvar.
    await h.preencherQuestionarioMinimoSeguro(page);
    await h.salvarCadastroComSucesso(page, getAlert);

    const resumoCriado = await h.lerResumoAcolhido(page);
    h.logTabela('[E2E][SOLICITACAO VAGA] cadastro salvo', {
      url: resumoCriado.url,
      nome: resumoCriado.nome,
      nascimento: resumoCriado.nascimento,
      sexo: await page.locator('#slcSexo').inputValue(),
      nis: resumoCriado.nis,
      cpf: resumoCriado.cpf,
    });

    await expect
      .poll(async () => page.locator('#btnSolicitarVaga').count(), { timeout: 20_000 })
      .toBeGreaterThan(0);
    await expect(page.locator('#btnSolicitarVaga')).toBeVisible();

    const waitCarregaServicos = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/cadastro-acolhido/model/carregaServicos.php') &&
        resp.request().method() === 'POST'
    );

    await page.click('#btnSolicitarVaga');
    await waitCarregaServicos;

    await expect(page.locator('#mdlSolicitarVaga')).toHaveClass(/show/);
    await expect(page.locator('#slcServicos')).toBeVisible();
    await expect
      .poll(async () => page.locator('#slcServicos option').count(), { timeout: 10_000 })
      .toBeGreaterThan(1);

    // Nao depender da ordem da lista; selecionar por servico_id.
    await expect(page.locator('#slcServicos option[value="1"]')).toHaveCount(1);
    const textoServicoId1 = await page.locator('#slcServicos option[value="1"]').innerText();
    h.logTabela('[E2E][SOLICITACAO VAGA] servico selecionado', {
      servico_id: '1',
      descricao: textoServicoId1,
      observacao: 'Selecionado por valor (nao por posicao da lista)',
    });

    await page.selectOption('#slcServicos', '1');
    await expect(page.locator('#boxGenero')).toBeVisible();
    await page.selectOption('#slcGenero', 'Masculino');
    await expect(page.locator('#slcGenero')).toHaveValue('Masculino');

    const waitSolicitarVaga = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/cadastro-acolhido/model/solicitarVaga.php') &&
        resp.request().method() === 'POST'
    );
    const waitRecarregaAcolhido = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/cadastro-acolhido/model/carregaAcolhido.php') &&
        resp.request().method() === 'POST'
    );
    const waitListaStatus = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/cadastro-acolhido/model/carregaSolicitacoesVagas.php') &&
        resp.request().method() === 'POST'
    );

    await page.click('#btnConfirmaSolicitacaoVaga');
    await waitSolicitarVaga;
    await expect.poll(getAlert).toContain('Vaga Solicitada');
    await Promise.all([waitRecarregaAcolhido, waitListaStatus]);

    await expect(page.locator('#mdlSolicitarVaga')).not.toHaveClass(/show/);
    await expect(page.locator('#abaStatus')).not.toHaveClass(/d-none/);
    await expect(page.locator('#btnStatus')).toBeVisible();
    await expect(page.locator('#boxSolicitarVaga #btnSolicitarVaga')).toHaveCount(0);

    await expect
      .poll(async () => page.locator('#boxStatusVaga .alert').count(), { timeout: 20_000 })
      .toBeGreaterThan(0);
    await expect(page.locator('#boxStatusVaga')).toContainText('Status:');

    const textoStatus = await page.locator('#boxStatusVaga').innerText();
    expect(normalizarTexto(textoStatus)).toContain('acolhimento');
    expect(normalizarTexto(textoStatus)).toContain('terapeutico');
  });

  test('modal de solicitacao: bloqueia envio sem servico e genero', async ({ page }) => {
    h.logInicioTeste('[SOLICITACAO VAGA] validacao modal sem servico/genero');
    const getAlert = await h.capturarAlert(page);

    await h.abrirCadastroNovo(page);
    const base = await h.criarDadosBasicosUnicos(page, 'E2E SOLIC VAGA VALIDACAO');
    const dadosMinimos = {
      nome: base.nome,
      dataNascimento: base.dataNascimento,
      sexo: 'Masculino',
      nis: base.nis,
      cpf: base.cpf,
    };

    await h.preencherDadosBasicos(page, dadosMinimos);
    await h.preencherQuestionarioMinimoSeguro(page);
    await h.salvarCadastroComSucesso(page, getAlert);

    await expect(page.locator('#btnSolicitarVaga')).toBeVisible();

    const waitCarregaServicos = page.waitForResponse(
      (resp) =>
        resp.url().includes('/public/componentes/cadastro-acolhido/model/carregaServicos.php') &&
        resp.request().method() === 'POST'
    );
    await page.click('#btnSolicitarVaga');
    await waitCarregaServicos;

    await expect(page.locator('#mdlSolicitarVaga')).toHaveClass(/show/);
    await expect(page.locator('#slcServicos')).toBeVisible();
    await expect(page.locator('#slcServicos')).toHaveValue('0');
    await expect(page.locator('#boxGenero')).toHaveClass(/d-none/);
    await expect(page.locator('#slcGenero')).toHaveValue('0');

    await page.click('#btnConfirmaSolicitacaoVaga');

    await expect.poll(getAlert).toContain('Selecione o referenciamento do servico e o genero');
    await expect(page.locator('#mdlSolicitarVaga')).toHaveClass(/show/);

    // Nao deve criar solicitacao nem esconder o botao principal.
    await expect(page.locator('#boxSolicitarVaga #btnSolicitarVaga')).toHaveCount(1);
    await expect(page.locator('#abaStatus')).toHaveClass(/d-none/);
    await expect(page.locator('#boxStatusVaga .alert')).toHaveCount(0);
  });
});
