<?php
include "../../../../classes/sistema.php";

function apPostValue($key)
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}

function apResolveAcolhidoEntradaId($idRecebido)
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

function apBuscaAvaliacaoIdPorEntrada($entradaId)
{
    $entradaId = intval($entradaId);
    if ($entradaId <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_avaliacao_psicossocial",
        "prontuario_avaliacao_psicossocial_id",
        "acolhido_entrada_id = " . $entradaId,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["prontuario_avaliacao_psicossocial_id"])) {
        return intval($result[0]["prontuario_avaliacao_psicossocial_id"]);
    }

    return 0;
}

function apBuscaEntradaPorAvaliacaoId($avaliacaoId)
{
    $avaliacaoId = intval($avaliacaoId);
    if ($avaliacaoId <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_avaliacao_psicossocial",
        "acolhido_entrada_id",
        "prontuario_avaliacao_psicossocial_id = " . $avaliacaoId,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["acolhido_entrada_id"])) {
        return intval($result[0]["acolhido_entrada_id"]);
    }

    return 0;
}

function apNormalizaIdReferencia($valor, $tabela, $campoId, $campoDescricao)
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

function apNormalizaListaIdsReferencia($campoPost, $tabela, $campoId, $campoDescricao)
{
    $lista = array();

    if (!isset($_POST[$campoPost]) || !is_array($_POST[$campoPost])) {
        return $lista;
    }

    foreach ($_POST[$campoPost] as $valor) {
        $id = apNormalizaIdReferencia($valor, $tabela, $campoId, $campoDescricao);
        if ($id !== "") {
            $id = intval($id);
            if (!in_array($id, $lista)) {
                $lista[] = $id;
            }
        }
    }

    return $lista;
}

function apBuscaEspecificidadeIdPorDescricao($descricao)
{
    $descricao = trim($descricao);
    if ($descricao === "") {
        return 0;
    }

    $sistema = new Sistema();
    $where = 'especificidade_descricao = "' . addslashes($descricao) . '"';
    $sistema->select("rec_especificidades", "especificidade_id", $where, "", "");
    $result = $sistema->getResult();

    if ($result && isset($result[0]["especificidade_id"])) {
        return intval($result[0]["especificidade_id"]);
    }

    return 0;
}

function apMontaPayloadAvaliacaoPsicossocial($entradaId)
{
    $payload = array(
        "dados" => array(),
        "especificidades" => array()
    );

    $dados = array();
    $dados["acolhido_entrada_id"] = intval($entradaId);

    $tipoAcompanhamentoId = apNormalizaIdReferencia(
        apPostValue("radAcompanhamento"),
        "rec_tipo_acompanhamento",
        "tipo_acompanhamento_id",
        "tipo_acompanhamento_descricao"
    );
    if ($tipoAcompanhamentoId !== "") {
        $dados["tipo_acompanhamento_id"] = $tipoAcompanhamentoId;
    }

    $tipoAcompanhamentoInt = intval($tipoAcompanhamentoId);
    if ($tipoAcompanhamentoInt === 2 || $tipoAcompanhamentoInt === 3) {
        $ondeAcompanhamento = apPostValue("txtOndeAcompanhamento");
        if ($ondeAcompanhamento !== "") {
            $dados["onde_acompanhamento"] = $ondeAcompanhamento;
        }
    }

    $especificidades = apNormalizaListaIdsReferencia(
        "chkEspecificidades",
        "rec_especificidades",
        "especificidade_id",
        "especificidade_descricao"
    );

    $idNaoSeAplica = apBuscaEspecificidadeIdPorDescricao("Não se aplica");
    if ($idNaoSeAplica <= 0) {
        $idNaoSeAplica = 14;
    }
    if (in_array($idNaoSeAplica, $especificidades)) {
        $especificidades = array($idNaoSeAplica);
    }

    $idOutra = apBuscaEspecificidadeIdPorDescricao("Outra");
    if ($idOutra <= 0) {
        $idOutra = 13;
    }
    if (in_array($idOutra, $especificidades)) {
        $outroTranstorno = apPostValue("txtOutroTranstornoPsicossocial");
        if ($outroTranstorno !== "") {
            $dados["outro_transtorno"] = $outroTranstorno;
        }
    }

    $payload["dados"] = $dados;
    $payload["especificidades"] = $especificidades;
    return $payload;
}

function apRemoveAvaliacaoExistente($avaliacaoId)
{
    $avaliacaoId = intval($avaliacaoId);
    if ($avaliacaoId <= 0) {
        return;
    }

    $sistema = new Sistema();
    $sistema->delete(
        "rec_prontuario_ap_especificidades",
        "prontuario_avaliacao_psicossocial_id = " . $avaliacaoId
    );
    $sistema->delete(
        "rec_prontuario_avaliacao_psicossocial",
        "prontuario_avaliacao_psicossocial_id = " . $avaliacaoId
    );
}

function apSalvaAvaliacaoPorEntrada($entradaId)
{
    $entradaId = intval($entradaId);
    if ($entradaId <= 0) {
        return 0;
    }

    $avaliacaoIdExistente = apBuscaAvaliacaoIdPorEntrada($entradaId);
    if ($avaliacaoIdExistente > 0) {
        apRemoveAvaliacaoExistente($avaliacaoIdExistente);
    }

    $payload = apMontaPayloadAvaliacaoPsicossocial($entradaId);
    $dados = $payload["dados"];

    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");

    $sistema = new Sistema();
    $sistema->insert("rec_prontuario_avaliacao_psicossocial", $dados);

    $novoId = isset($_SESSION["sessionForIdInserted"]) ? intval($_SESSION["sessionForIdInserted"]) : 0;
    if ($novoId <= 0) {
        return 0;
    }

    foreach ($payload["especificidades"] as $especificidadeId) {
        $sistema = new Sistema();
        $sistema->insert("rec_prontuario_ap_especificidades", array(
            "prontuario_avaliacao_psicossocial_id" => $novoId,
            "especificidade_id" => intval($especificidadeId)
        ));
    }

    return $novoId;
}
