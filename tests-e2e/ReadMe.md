# Testes E2E (Playwright) - COED

Este pacote contem testes automatizados de caixa preta (E2E) do sistema COED.
Atualmente cobre:

- login
- cadastro e edicao de acolhidos (por secao)
- solicitacao de vaga (a partir do cadastro do acolhido)
- area restrita (Conector / Executora) encaminhar para a osc / reservar ou negar vaga

## Estrutura

Todos os arquivos do Playwright ficam nesta pasta para facilitar a copia entre projetos:

- `tests/login.spec.js`
- `tests/cadastro-acolhido/` (cadastro, edicao, solicitacao de vaga)
- `tests/area-restrita/` (encaminhamento para OSC, reservar/negar vaga)
- `playwright.config.js`
- `package.json` / `package-lock.json`
- `node_modules/`

## Pre-requisitos

- Windows + PowerShell
- Node.js instalado (`npm` / `npx`)
- WAMP rodando
- Sistema acessivel em `http://localhost/coed/login`
- Google Chrome instalado (config atual usa `channel: 'chrome'`)

## Instalacao (primeira vez)

```powershell
cd C:\wamp64\www\coed\tests-e2e
npm install
```

Observacoes:
- O projeto usa Chrome local (`channel: 'chrome'`).
- Em geral nao precisa rodar `npx playwright install`.

## Configuracao com `.env` (recomendado)

O pacote carrega automaticamente `tests-e2e/.env`.

1. Criar o arquivo:

```powershell
cd C:\wamp64\www\coed\tests-e2e
Copy-Item .env.example .env
```

2. Preencher credenciais e dados:

```env
E2E_CPF=321.291.520-07
E2E_SENHA=123456
BASE_URL=http://localhost/coed/
BROWSER_CHANNEL=chrome

# Documentos existentes (duplicidade de cadastro)
E2E_ACO_NIS_EXISTENTE=123456
E2E_ACO_CPF_EXISTENTE=400.289.222-22
```

Observacao:
- Variaveis definidas no PowerShell sobrescrevem o `.env`.

## Pre-condicoes de dados (negocio)

### Credencial E2E

O usuario de `E2E_CPF` / `E2E_SENHA` precisa conseguir acessar os vinculos:

- `perfil_id = 3` (Porta de Entrada) - cadastro / solicitacao de vaga
- `perfil_id = 7` (Conector) - encaminhar para OSC
- `perfil_id = 4` (Executora / OSC) - reservar / negar vaga

### Cadastro de acolhido

- `E2E_ACO_NIS_EXISTENTE` deve ser de um acolhido real
- `E2E_ACO_CPF_EXISTENTE` deve ser de um acolhido real

Observacoes:
- Os testes `[NOVO]` criam registros reais na base.
- CPF/NIS novos sao gerados aleatoriamente e pre-validados para evitar colisao.

### Area restrita (fila de solicitacoes)

Para os testes de `area-restrita` funcionarem, deve haver solicitacoes nas filas esperadas:

- `Conector (perfil 7)`: precisa existir solicitacao pendente para encaminhamento (status 5)
- `Executora (perfil 4 / OSC Teste DNI)`: precisa existir solicitacao encaminhada para a OSC (status 1)

Fluxo recomendado para montar a fila automaticamente:
1. Rodar `17-solicitacao-vaga.spec.js`
2. Rodar `area-restrita/01-encaminhar-para-osc.spec.js`
3. Rodar `area-restrita/02-reservar-negar-vaga.spec.js`

## Como rodar

npx playwright test tests/login.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/01-secao-inicial.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/02-primeiro-acolhimento.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/03-dados-contato.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/04-contatos-referencia.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/05-endereco.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/06-saude.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/07-historico.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/08-edicao.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/09-edicao-secao-inicial.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/10-edicao-primeiro-acolhimento.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/11-edicao-dados-contato.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/12-edicao-contatos-referencia.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/13-edicao-endereco.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/14-edicao-saude.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/15-edicao-historico.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/16-edicao-duplicidade.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/17-solicitacao-vaga.spec.js --headed --workers=1
npx playwright test tests/cadastro-acolhido/cadastro-acolhido-minimo.spec.js --headed --workers=1
npx playwright test tests/area-restrita/01-encaminhar-para-osc.spec.js --headed --workers=1
npx playwright test tests/area-restrita/02-reservar-negar-vaga.spec.js --headed --workers=1


## Dicas para evitar `No tests found`

- Use o arquivo correto (`05-endereco`, `06-saude`, `17-solicitacao-vaga`, `area-restrita/...`).
- O `-g` filtra pelo **nome do teste** (regex), nao pelo arquivo.
- Sempre passe um texto depois de `-g`:
  - `-g "STRESS"`
  - `-g "endereco fixo"`
- Se quiser casar texto com parenteses, escape no regex:
  - `-g 'edita para endereco fixo \(SIM\)'`
- Prefira barras `/` no caminho:
  - `tests/cadastro-acolhido/05-endereco.spec.js`
- Para listar todos os testes visiveis:

```powershell
npx playwright test --list
```

## Leitura do relatorio (importante)

- Testes com prefixo `[BUG]` sao bugs conhecidos e **ficam vermelhos de proposito** para nao serem esquecidos.
- Se um `[BUG]` passar, significa que o defeito pode ter sido corrigido e o teste deve ser revisado.
- Logs em terminal usam `console.table(...)` para mostrar dados gerados e retornos (URL, CPF/NIS, etc.).

## Auditoria de execucao

Todos os arquivos `.spec.js` estao configurados com auditoria automatica por teste (helper `tests/_audit.js`).

Agora a auditoria grava **somente falhas** em uma pasta unica:

- `tests-e2e/audit/falhas_YYYY-MM-DD.jsonl`
- `tests-e2e/audit/falhas_YYYY-MM-DD.log` (versao legivel para analise humana)

### O que fica registrado

- `run_id` (id da execucao)
- `data_hora`
- `funcionalidade`
- `arquivo` / `teste` / `descricao`
- `status` / `expected_status`
- `erro_curto` (resumo tecnico em 1 linha)
- `resumo_humano` (texto direto: o que falhou, onde e causa provavel)
- `tipo_falha` (timeout, locator_ambiguo, elemento_oculto, etc.)
- `causa_provavel` (heuristica automatica)
- `acao_sugerida` (proximo passo objetivo para correcao)
- `esperado` / `recebido` (extraidos da mensagem de erro)
- `matcher` (ex: `toBeVisible`, `toContainText`)
- `erro_locator` (selector quando identificado)
- `local_falha` / `local_arquivo` / `local_linha` / `local_coluna` / `local_funcao`
- `call_log` (trecho limpo do Call log)
- `erro` / `stack_resumo` (sem ANSI)
- `error_context_md` (caminho relativo para contexto Playwright, quando existir)
- `duracao_ms` / `retry` / `worker_index` / `projeto` / `resultado_dir`

### Exemplo de pasta de auditoria

```text
tests-e2e/
  audit/
    falhas_2026-03-11.jsonl
    falhas_2026-03-11.log
    playwright-output/
```

## Cenarios cobertos (resumo)

### `tests/login.spec.js`

1. Login com senha invalida
2. Login com credenciais validas
3. CPF invalido
4. Primeiro acesso (quando configurado)

### `tests/cadastro-acolhido/`

1. Cadastro por secao (inicial, acolhimento, contato, endereco, saude, historico)
2. Edicao por secao (espelho do cadastro)
3. Regras de duplicidade por NIS/CPF (cadastro e edicao)
4. Testes de UI condicional (`[UI]`) para blocos que aparecem/escondem
5. Testes de bug/diagnostico (`[BUG]`) para falhas conhecidas
6. Solicitacao de vaga a partir do cadastro salvo

### `tests/area-restrita/`

1. Conector (`perfil 7`) encaminha solicitacao para OSC (`executora_id = 86`)
2. Modal de encaminhamento bloqueia envio sem escolher OSC
3. Executora (`perfil 4`) reserva vaga
4. Executora (`perfil 4`) nega vaga com justificativa

## Reutilizar em outro projeto

1. Copie a pasta `tests-e2e`
2. Ajuste `BASE_URL` / `playwright.config.js`
3. Ajuste seletores/fluxos em `tests/*.spec.js`

## Problemas comuns

### `npm` / `npx` nao reconhecido

Node.js nao instalado (ou fora do `PATH`).

### `SELF_SIGNED_CERT_IN_CHAIN`

Ambiente com proxy/certificado corporativo. Como o setup usa Chrome local, normalmente os testes podem rodar sem baixar navegadores do Playwright.
