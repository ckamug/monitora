const fs = require('fs');
const path = require('path');

const RUN_ID = new Date().toISOString().replace(/[:.]/g, '-');
const ANSI_REGEX = /\u001b\[[0-9;]*m/g;

function safeName(texto) {
  return String(texto || '')
    .replace(/[<>:"/\\|?*\x00-\x1F]/g, '_')
    .replace(/\s+/g, ' ')
    .trim()
    .slice(0, 180);
}

function pastaRaizTests() {
  return __dirname;
}

function limparTexto(texto) {
  return String(texto ?? '')
    .replace(ANSI_REGEX, '')
    .replace(/\r/g, '')
    .trim();
}

function serializarValor(valor) {
  if (valor === undefined || valor === null) return '';
  if (typeof valor === 'string') return limparTexto(valor);
  try {
    return limparTexto(JSON.stringify(valor));
  } catch {
    return limparTexto(String(valor));
  }
}

function extrairLocatorDoErro(testInfo) {
  const texto = limparTexto(testInfo?.error?.message || testInfo?.error || '');
  if (!texto) return '';

  const padroes = [
    /Locator:\s*locator\((['"])(.*?)\1\)/i,
    /waiting for locator\((['"])(.*?)\1\)/i,
    /locator\((['"])(.*?)\1\)/i,
  ];

  for (const padrao of padroes) {
    const match = texto.match(padrao);
    if (match?.[2]) return match[2];
  }
  return '';
}

function extrairPrimeiraOcorrencia(linhas, regexes) {
  for (const linha of linhas) {
    for (const regex of regexes) {
      const match = linha.match(regex);
      if (match?.[1]) return match[1].trim();
    }
  }
  return '';
}

function extrairEsperadoRecebido(testInfo) {
  const erroObj = testInfo?.error || {};
  const mensagem = limparTexto(erroObj?.message || erroObj || '');
  const linhas = mensagem.split(/\r?\n/);

  let esperado = serializarValor(
    erroObj?.expected ?? erroObj?.matcherResult?.expected ?? erroObj?.value?.expected
  );
  let recebido = serializarValor(
    erroObj?.actual ?? erroObj?.received ?? erroObj?.matcherResult?.actual ?? erroObj?.value?.actual
  );

  if (!esperado) {
    esperado = extrairPrimeiraOcorrencia(linhas, [
      /^\s*Expected(?:\s+substring|\s+pattern|\s+string|\s+value|\s+status)?\s*:\s*(.*)\s*$/i,
      /^\s*Expected\s+length\s*:\s*(.*)\s*$/i,
    ]);
  }

  if (!recebido) {
    recebido = extrairPrimeiraOcorrencia(linhas, [
      /^\s*Received(?:\s+substring|\s+pattern|\s+string|\s+value|\s+status)?\s*:\s*(.*)\s*$/i,
      /^\s*Received\s+length\s*:\s*(.*)\s*$/i,
    ]);
  }

  return { esperado: limparTexto(esperado), recebido: limparTexto(recebido) };
}

function montarDescricaoTeste(testInfo) {
  const partes = Array.isArray(testInfo?.titlePath)
    ? testInfo.titlePath.filter((p) => String(p || '').trim() !== '')
    : [];
  if (partes.length === 0) return testInfo?.title || '';
  return partes.join(' > ');
}

function recortarTexto(texto, limite = 3000) {
  const bruto = limparTexto(texto);
  if (bruto.length <= limite) return bruto;
  return `${bruto.slice(0, limite)}...[truncado]`;
}

function extrairMatcher(erroTexto) {
  const texto = limparTexto(erroTexto);
  const match = texto.match(/expect\([^)]+\)\.(\w+)\(/i);
  return match?.[1] || '';
}

function extrairCallLog(erroTexto) {
  const texto = limparTexto(erroTexto);
  const idx = texto.indexOf('Call log:');
  if (idx < 0) return '';
  return recortarTexto(texto.slice(idx + 'Call log:'.length).trim(), 3000);
}

function extrairErroCurto(erroTexto) {
  const texto = limparTexto(erroTexto);
  if (!texto) return '';
  const semCallLog = texto.split('Call log:')[0];
  const linhas = semCallLog
    .split(/\r?\n/)
    .map((l) => l.trim())
    .filter(Boolean);
  return recortarTexto(linhas.slice(0, 3).join(' | '), 500);
}

function parseFrameLinha(stackLine) {
  const linha = String(stackLine || '').trim();
  if (!linha.startsWith('at ')) return null;

  let m = linha.match(/^at\s+(.*?)\s+\((.*):(\d+):(\d+)\)$/);
  if (m) {
    return {
      funcao: m[1],
      arquivo: m[2],
      linha: Number(m[3]),
      coluna: Number(m[4]),
    };
  }

  m = linha.match(/^at\s+(.*):(\d+):(\d+)$/);
  if (m) {
    return {
      funcao: '',
      arquivo: m[1],
      linha: Number(m[2]),
      coluna: Number(m[3]),
    };
  }

  return null;
}

function extrairLocalFalha(stack, raizProjeto) {
  const linhas = limparTexto(stack).split(/\r?\n/);
  let fallback = null;

  for (const l of linhas) {
    const frame = parseFrameLinha(l);
    if (!frame) continue;
    if (String(frame.arquivo || '').startsWith('node:')) continue;

    if (!fallback) fallback = frame;

    const arq = String(frame.arquivo || '').replace(/\\/g, '/');
    if (arq.includes('/tests/')) {
      fallback = frame;
      break;
    }
  }

  if (!fallback) {
    return { local: '', arquivo: '', linha: 0, coluna: 0, funcao: '' };
  }

  const arquivoAbs = fallback.arquivo;
  let arquivoRel = limparTexto(arquivoAbs);
  if (path.isAbsolute(arquivoAbs)) {
    const relativo = path.relative(raizProjeto, arquivoAbs);
    arquivoRel = relativo && !relativo.startsWith('..') ? relativo : arquivoAbs;
  }
  arquivoRel = arquivoRel.replace(/\\/g, '/');

  return {
    local: `${arquivoRel}:${fallback.linha}:${fallback.coluna}`,
    arquivo: arquivoRel,
    linha: fallback.linha,
    coluna: fallback.coluna,
    funcao: limparTexto(fallback.funcao),
  };
}

function classificarFalha({ status, erroTexto, esperado, recebido, locator }) {
  const msg = limparTexto(erroTexto).toLowerCase();

  if (status === 'interrupted') {
    return {
      tipo_falha: 'execucao_interrompida',
      causa_provavel: 'Execucao interrompida antes do fim (cancelamento manual, falha anterior em cascata ou worker finalizado).',
      acao_sugerida: 'Reexecutar o spec isolado com --workers=1 e sem interrupcao manual.',
    };
  }

  if (status === 'timedOut' || /test timeout .* exceeded/.test(msg)) {
    return {
      tipo_falha: 'timeout',
      causa_provavel: 'O fluxo nao concluiu no tempo limite por pre-condicao nao atendida, espera bloqueada ou elemento indisponivel.',
      acao_sugerida: 'Validar pre-condicoes de dados e adicionar esperas condicionais para estado visivel/habilitado antes da acao.',
    };
  }

  if (/strict mode violation/.test(msg)) {
    return {
      tipo_falha: 'locator_ambiguo',
      causa_provavel: 'O seletor retornou mais de um elemento (ID duplicado ou seletor amplo).',
      acao_sugerida: 'Escopar o locator pelo container/aba correta, evitando seletores globais.',
    };
  }

  if (/element is not visible|received:\s*hidden/.test(msg)) {
    return {
      tipo_falha: 'elemento_oculto',
      causa_provavel: 'Elemento existe no DOM, mas esta oculto (aba/colapso nao ativo).',
      acao_sugerida: 'Abrir a aba/colapso correspondente e validar visibilidade antes de interagir.',
    };
  }

  if (/element is not enabled|disabled/.test(msg)) {
    return {
      tipo_falha: 'elemento_desabilitado',
      causa_provavel: 'Elemento estava desabilitado no estado atual do formulario.',
      acao_sugerida: 'Executar a acao habilitadora anterior (radio/select/checkbox) antes de clicar/check.',
    };
  }

  if (/tohaveclass/.test(msg) && /show|collapsing|collapse/.test(msg)) {
    return {
      tipo_falha: 'colapso_ou_aba_nao_abriu',
      causa_provavel: 'Colapso/aba nao entrou no estado "show" antes da validacao.',
      acao_sugerida: 'Adicionar rotina de abertura robusta (retry + aguardo do estado final).',
    };
  }

  if (/tohavelength/.test(msg) && /expected length/.test(msg)) {
    return {
      tipo_falha: 'validacao_dado',
      causa_provavel: 'Valor informado nao atende regra de tamanho esperada.',
      acao_sugerida: 'Revisar massa de teste e garantir formato valido antes da assertiva.',
    };
  }

  if (/tocontain/.test(msg) && /pessoa ja cadastrada/.test(msg)) {
    return {
      tipo_falha: 'mensagem_esperada_nao_exibida',
      causa_provavel: 'A mensagem esperada nao apareceu no fluxo atual.',
      acao_sugerida: 'Confirmar evento que dispara alerta (blur, submit ou request) e aguardar resposta correspondente.',
    };
  }

  const detalhe = [esperado ? `esperado=${esperado}` : '', recebido ? `recebido=${recebido}` : '', locator ? `locator=${locator}` : '']
    .filter(Boolean)
    .join(' | ');

  return {
    tipo_falha: 'falha_assertiva',
    causa_provavel: detalhe || 'Falha de assertiva sem classificacao automatica especifica.',
    acao_sugerida: 'Inspecionar mensagem limpa, call log e local da falha para ajustar pre-condicao, seletor ou regra.',
  };
}

function montarResumoHumano({ status, teste, localFalha, erroCurto, esperado, recebido, locator, causa }) {
  const partes = [
    `[${status}] ${teste || 'Teste sem titulo'}`,
    localFalha ? `onde: ${localFalha}` : '',
    erroCurto ? `o que: ${erroCurto}` : '',
    esperado ? `esperado: ${esperado}` : '',
    recebido ? `recebido: ${recebido}` : '',
    locator ? `locator: ${locator}` : '',
    causa ? `por que (provavel): ${causa}` : '',
  ].filter(Boolean);
  return recortarTexto(partes.join(' | '), 1200);
}

function formatarBlocoHumano(linha) {
  const callLogCurto = recortarTexto(linha.call_log || '', 1200);
  const itens = [
    `[${linha.data_hora}] ${String(linha.status || '').toUpperCase()} - ${linha.funcionalidade}`,
    `Teste: ${linha.teste || '-'}`,
    `Arquivo: ${linha.arquivo || '-'}`,
    `Onde: ${linha.local_falha || '-'}`,
    `Tipo: ${linha.tipo_falha || '-'}`,
    `Erro: ${linha.erro_curto || '-'}`,
    `Esperado: ${linha.esperado || '-'}`,
    `Recebido: ${linha.recebido || '-'}`,
    `Locator: ${linha.erro_locator || '-'}`,
    `Causa provavel: ${linha.causa_provavel || '-'}`,
    `Acao sugerida: ${linha.acao_sugerida || '-'}`,
    `Call log: ${callLogCurto || '-'}`,
    `Run: ${linha.run_id} | worker=${linha.worker_index} | retry=${linha.retry} | duracao_ms=${linha.duracao_ms}`,
    '---',
    '',
  ];
  return itens.join('\n');
}

function statusEhErro(status) {
  return status === 'failed' || status === 'timedOut' || status === 'interrupted';
}

function habilitarAuditoria(test, { funcionalidade }) {
  if (!test || typeof test.afterEach !== 'function') {
    throw new Error('habilitarAuditoria requer o objeto "test" do @playwright/test.');
  }

  const funcionalidadeFinal = funcionalidade || 'sem-funcionalidade';

  test.afterEach(async ({}, testInfo) => {
    if (!statusEhErro(testInfo.status)) return;

    const agora = new Date();
    const dataArquivo = agora.toISOString().slice(0, 10);
    const raizProjeto = path.join(pastaRaizTests(), '..');
    const pastaAudit = path.join(pastaRaizTests(), '..', 'audit');
    fs.mkdirSync(pastaAudit, { recursive: true });

    const arquivoRelativo = testInfo?.file
      ? path.relative(raizProjeto, testInfo.file).replace(/\\/g, '/')
      : '';
    const erroTexto = limparTexto(testInfo?.error?.message || testInfo?.error || '');
    const { esperado, recebido } = extrairEsperadoRecebido(testInfo);
    const stack = limparTexto(testInfo?.error?.stack || '');
    const callLog = extrairCallLog(erroTexto);
    const erroCurto = extrairErroCurto(erroTexto);
    const matcher = extrairMatcher(erroTexto);
    const localFalha = extrairLocalFalha(stack, raizProjeto);
    const classificacao = classificarFalha({
      status: testInfo.status,
      erroTexto,
      esperado,
      recebido,
      locator: extrairLocatorDoErro(testInfo),
    });

    const pathErrorContext = testInfo.outputDir ? path.join(testInfo.outputDir, 'error-context.md') : '';
    const errorContextRel = pathErrorContext && fs.existsSync(pathErrorContext)
      ? path.relative(raizProjeto, pathErrorContext).replace(/\\/g, '/')
      : '';
    const locator = extrairLocatorDoErro(testInfo);
    const resumoHumano = montarResumoHumano({
      status: testInfo.status,
      teste: testInfo.title || '',
      localFalha: localFalha.local,
      erroCurto,
      esperado,
      recebido,
      locator,
      causa: classificacao.causa_provavel,
    });

    const linha = {
      schema_version: 2,
      run_id: RUN_ID,
      data_hora: agora.toISOString(),
      funcionalidade: funcionalidadeFinal,
      arquivo: arquivoRelativo,
      teste: testInfo.title || '',
      descricao: montarDescricaoTeste(testInfo),
      status: testInfo.status,
      expected_status: testInfo.expectedStatus || '',
      esperado,
      recebido,
      erro_curto: erroCurto,
      erro: recortarTexto(erroTexto, 5000),
      matcher,
      call_log: callLog,
      erro_locator: locator,
      local_falha: localFalha.local,
      local_arquivo: localFalha.arquivo,
      local_linha: localFalha.linha,
      local_coluna: localFalha.coluna,
      local_funcao: localFalha.funcao,
      tipo_falha: classificacao.tipo_falha,
      causa_provavel: classificacao.causa_provavel,
      acao_sugerida: classificacao.acao_sugerida,
      resumo_humano: resumoHumano,
      stack_resumo: recortarTexto(stack, 5000),
      duracao_ms: testInfo.duration,
      retry: testInfo.retry ?? 0,
      worker_index: testInfo.workerIndex ?? 0,
      projeto: testInfo?.project?.name || '',
      resultado_dir: testInfo.outputDir || '',
      error_context_md: errorContextRel,
      tags: Array.isArray(testInfo.tags) ? testInfo.tags.join(',') : '',
      nome_logico: safeName(testInfo.title || ''),
    };

    const jsonlPath = path.join(pastaAudit, `falhas_${dataArquivo}.jsonl`);
    fs.appendFileSync(jsonlPath, `${JSON.stringify(linha)}\n`, 'utf8');

    const txtPath = path.join(pastaAudit, `falhas_${dataArquivo}.log`);
    fs.appendFileSync(txtPath, formatarBlocoHumano(linha), 'utf8');
  });
}

module.exports = {
  habilitarAuditoria,
};
