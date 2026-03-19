<?php
include "../../../../classes/sistema.php";

function hsPostValue($key)
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}

function hsResolveAcolhidoEntradaId($idRecebido)
{
    $idRecebido = intval($idRecebido);
    if ($idRecebido <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_acolhidos_entradas",
        "acolhido_entrada_id",
        "acolhido_entrada_id = " . $idRecebido,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["acolhido_entrada_id"])) {
        return intval($result[0]["acolhido_entrada_id"]);
    }

    return 0;
}

function hsBuscaHistoricoIdPorEntrada($entradaId)
{
    $entradaId = intval($entradaId);
    if ($entradaId <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_historico_social",
        "prontuario_historico_social_id",
        "acolhido_entrada_id = " . $entradaId,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["prontuario_historico_social_id"])) {
        return intval($result[0]["prontuario_historico_social_id"]);
    }

    return 0;
}

function hsBuscaEntradaPorHistoricoId($historicoId)
{
    $historicoId = intval($historicoId);
    if ($historicoId <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_historico_social",
        "acolhido_entrada_id",
        "prontuario_historico_social_id = " . $historicoId,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["acolhido_entrada_id"])) {
        return intval($result[0]["acolhido_entrada_id"]);
    }

    return 0;
}

function hsNormalizaSimNao($valor)
{
    $valor = trim($valor);
    if ($valor === "") {
        return "";
    }

    $valorLower = strtolower($valor);
    if ($valorLower === "sim" || $valor === "1") {
        return 1;
    }
    if ($valorLower === "não" || $valorLower === "nao" || $valor === "0") {
        return 0;
    }

    return "";
}

function hsNormalizaIdReferencia($valor, $tabela, $campoId, $campoDescricao)
{
    $valor = trim($valor);

    if ($valor === "" || $valor === "0") {
        return "";
    }

    if (ctype_digit($valor)) {
        $valorInt = intval($valor);
        return $valorInt > 0 ? $valorInt : "";
    }

    $sistema = new Sistema();
    $where = $campoDescricao . ' = "' . addslashes($valor) . '"';
    $sistema->select($tabela, $campoId, $where, "", "");
    $result = $sistema->getResult();

    if ($result && isset($result[0][$campoId])) {
        return intval($result[0][$campoId]);
    }

    return "";
}

function hsNormalizaValorMonetario($valor)
{
    $valor = trim($valor);
    if ($valor === "") {
        return "";
    }

    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", ".", $valor);
    $valor = preg_replace('/[^0-9\.\-]/', '', $valor);

    if ($valor === "" || !is_numeric($valor)) {
        return "";
    }

    return number_format((float)$valor, 2, ".", "");
}

function hsNormalizaListaIdsReferencia($campoPost, $tabela, $campoId, $campoDescricao)
{
    $lista = array();

    if (!isset($_POST[$campoPost]) || !is_array($_POST[$campoPost])) {
        return $lista;
    }

    foreach ($_POST[$campoPost] as $valor) {
        $id = hsNormalizaIdReferencia($valor, $tabela, $campoId, $campoDescricao);
        if ($id !== "") {
            $id = intval($id);
            if (!in_array($id, $lista)) {
                $lista[] = $id;
            }
        }
    }

    return $lista;
}

function hsMontaPayloadHistoricoSocial($entradaId)
{
    $payload = array(
        "dados" => array(),
        "motivos" => array(),
        "referenciada" => array()
    );

    $dados = array();
    $dados["acolhido_entrada_id"] = intval($entradaId);

    $sabeLer = hsNormalizaSimNao(hsPostValue("radSabeLer"));
    if ($sabeLer !== "") {
        $dados["sabe_ler_escrever"] = $sabeLer;
    }

    $frequentouEscola = hsNormalizaSimNao(hsPostValue("radFrequentouEscola"));
    if ($frequentouEscola !== "") {
        $dados["frequentou_escola"] = $frequentouEscola;
    }

    if ($frequentouEscola === 1) {
        $grauEscolaridadeId = hsNormalizaIdReferencia(
            hsPostValue("slcGrauEscolaridade"),
            "rec_grau_escolaridade",
            "grau_escolaridade_id",
            "grau_escolaridade_descricao"
        );
        if ($grauEscolaridadeId !== "") {
            $dados["grau_escolaridade_id"] = $grauEscolaridadeId;
        }

        $anoSerieId = hsNormalizaIdReferencia(
            hsPostValue("slcAnoSerie"),
            "rec_anos_series",
            "ano_serie_id",
            "ano_serie_descricao"
        );
        if ($anoSerieId !== "") {
            $dados["ano_serie_id"] = $anoSerieId;
        }

        $nomeEscola = hsPostValue("txtNomeEscola");
        if ($nomeEscola !== "") {
            $dados["nome_escola"] = $nomeEscola;
        }

        $ufEscolaId = hsNormalizaIdReferencia(
            hsPostValue("slcUfEscola"),
            "tbl_estados",
            "estado_id",
            "estado_descricao"
        );
        if ($ufEscolaId !== "") {
            $dados["uf_escola_id"] = $ufEscolaId;
        }

        $municipioEscolaId = hsNormalizaIdReferencia(
            hsPostValue("slcMunicipioEscola"),
            "tbl_cidades",
            "cidade_id",
            "cidade_descricao"
        );
        if ($municipioEscolaId !== "") {
            $dados["municipio_escola_id"] = $municipioEscolaId;
        }
    }

    $ondeCostumaDormirId = hsNormalizaIdReferencia(
        hsPostValue("radCostumaDormir"),
        "rec_onde_costuma_dormir",
        "onde_costuma_dormir_id",
        "onde_costuma_dormir_descricao"
    );
    if ($ondeCostumaDormirId !== "") {
        $dados["onde_costuma_dormir_id"] = $ondeCostumaDormirId;
    }

    $exibeSituacaoRua = false;
    $exibeTempoMoradia = false;
    if ($ondeCostumaDormirId !== "") {
        $ondeCostumaDormirId = intval($ondeCostumaDormirId);
        $exibeSituacaoRua = in_array($ondeCostumaDormirId, array(1, 4, 5));
        $exibeTempoMoradia = ($ondeCostumaDormirId === 5);
    }

    if ($exibeTempoMoradia) {
        $tempoMoradiaId = hsNormalizaIdReferencia(
            hsPostValue("slcTempoMoradia"),
            "rec_faixas_tempo",
            "faixa_tempo_id",
            "faixa_tempo_descricao"
        );
        if ($tempoMoradiaId !== "") {
            $dados["tempo_moradia_id"] = $tempoMoradiaId;
        }

        $rotinaDiurnaId = hsNormalizaIdReferencia(
            hsPostValue("radRotina"),
            "rec_rotina_diurna",
            "rotina_diurna_id",
            "rotina_diurna_descricao"
        );
        if ($rotinaDiurnaId !== "") {
            $dados["rotina_diurna_id"] = $rotinaDiurnaId;
        }
    }

    if ($exibeSituacaoRua) {
        $tempoSituacaoRuaId = hsNormalizaIdReferencia(
            hsPostValue("slcTempoSituacaoRua"),
            "rec_faixas_tempo",
            "faixa_tempo_id",
            "faixa_tempo_descricao"
        );
        if ($tempoSituacaoRuaId !== "") {
            $dados["tempo_situacao_rua_id"] = $tempoSituacaoRuaId;
        }

        $situacaoRuaOrigem = hsPostValue("txtEstavaSituacaoRua");
        if ($situacaoRuaOrigem !== "") {
            $dados["situacao_rua_origem"] = $situacaoRuaOrigem;
        }

        $payload["motivos"] = hsNormalizaListaIdsReferencia(
            "chkMotivosRua",
            "rec_motivos_rua",
            "motivo_rua_id",
            "motivo_rua_descricao"
        );
    }

    $atividadeRemunerada = hsNormalizaSimNao(hsPostValue("radAtividadeRemunerada"));
    if ($atividadeRemunerada !== "") {
        $dados["atividade_remunerada"] = $atividadeRemunerada;
    }

    if ($atividadeRemunerada === 1) {
        $trabalhoPrincipalId = hsNormalizaIdReferencia(
            hsPostValue("slcTrabalhoPrincipal"),
            "rec_trabalho_principal",
            "trabalho_principal_id",
            "trabalho_principal_descricao"
        );
        if ($trabalhoPrincipalId !== "") {
            $dados["trabalho_principal_id"] = $trabalhoPrincipalId;
        }

        $outroTrabalhoPrincipal = hsPostValue("txtOutroTrabalhoPrincipal");
        if ($outroTrabalhoPrincipal !== "") {
            $dados["outro_trabalho_principal"] = $outroTrabalhoPrincipal;
        }
    }

    $valorAjudaDoacao = hsNormalizaValorMonetario(hsPostValue("txtAjudaDoacao"));
    if ($valorAjudaDoacao !== "") {
        $dados["valor_ajuda_doacao"] = $valorAjudaDoacao;
    }

    $valorAposentadoria = hsNormalizaValorMonetario(hsPostValue("txtAposentadoria"));
    if ($valorAposentadoria !== "") {
        $dados["valor_aposentadoria"] = $valorAposentadoria;
    }

    $valorSeguroDesemprego = hsNormalizaValorMonetario(hsPostValue("txtSeguroDesemprego"));
    if ($valorSeguroDesemprego !== "") {
        $dados["valor_seguro_desemprego"] = $valorSeguroDesemprego;
    }

    $valorPensao = hsNormalizaValorMonetario(hsPostValue("txtPensao"));
    if ($valorPensao !== "") {
        $dados["valor_pensao_alimenticia"] = $valorPensao;
    }

    $valorOutrasFontes = hsNormalizaValorMonetario(hsPostValue("txtOutrasdFontes"));
    if ($valorOutrasFontes !== "") {
        $dados["valor_outras_fontes"] = $valorOutrasFontes;
    }

    $precisaQualificacao = hsNormalizaSimNao(hsPostValue("radPrecisaQualificacao"));
    if ($precisaQualificacao !== "") {
        $dados["precisa_qualificacao"] = $precisaQualificacao;
    }

    if ($precisaQualificacao === 1) {
        $qualificacaoDescricao = hsPostValue("txtQualQualificacao");
        if ($qualificacaoDescricao !== "") {
            $dados["qualificacao_descricao"] = $qualificacaoDescricao;
        }
    }

    $payload["referenciada"] = hsNormalizaListaIdsReferencia(
        "chkReferenciada",
        "rec_referenciada",
        "referenciada_id",
        "referenciada_descricao"
    );

    $payload["dados"] = $dados;
    return $payload;
}

function hsRemoveHistoricoExistente($historicoId)
{
    $historicoId = intval($historicoId);
    if ($historicoId <= 0) {
        return;
    }

    $sistema = new Sistema();
    $sistema->delete("rec_prontuario_hs_motivos_rua", "prontuario_historico_social_id = " . $historicoId);
    $sistema->delete("rec_prontuario_hs_referenciada", "prontuario_historico_social_id = " . $historicoId);
    $sistema->delete("rec_prontuario_historico_social", "prontuario_historico_social_id = " . $historicoId);
}

function hsSalvaHistoricoSocialPorEntrada($entradaId)
{
    $entradaId = intval($entradaId);
    if ($entradaId <= 0) {
        return 0;
    }

    $historicoIdExistente = hsBuscaHistoricoIdPorEntrada($entradaId);
    if ($historicoIdExistente > 0) {
        hsRemoveHistoricoExistente($historicoIdExistente);
    }

    $payload = hsMontaPayloadHistoricoSocial($entradaId);
    $dados = $payload["dados"];

    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");

    $sistema = new Sistema();
    $sistema->insert("rec_prontuario_historico_social", $dados);

    $novoId = isset($_SESSION["sessionForIdInserted"]) ? intval($_SESSION["sessionForIdInserted"]) : 0;
    if ($novoId <= 0) {
        return 0;
    }

    foreach ($payload["motivos"] as $motivoRuaId) {
        $sistema = new Sistema();
        $sistema->insert("rec_prontuario_hs_motivos_rua", array(
            "prontuario_historico_social_id" => $novoId,
            "motivo_rua_id" => intval($motivoRuaId)
        ));
    }

    foreach ($payload["referenciada"] as $referenciadaId) {
        $sistema = new Sistema();
        $sistema->insert("rec_prontuario_hs_referenciada", array(
            "prontuario_historico_social_id" => $novoId,
            "referenciada_id" => intval($referenciadaId)
        ));
    }

    return $novoId;
}

