<?php
include "../../../../classes/sistema.php";

function suResolveAcolhidoEntradaId($idRecebido)
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

function suBuscaSobreUsoIdPorEntrada($entradaId)
{
    $entradaId = intval($entradaId);
    if ($entradaId <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_sobre_uso",
        "prontuario_sobre_uso_id",
        "acolhido_entrada_id = " . $entradaId,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["prontuario_sobre_uso_id"])) {
        return intval($result[0]["prontuario_sobre_uso_id"]);
    }

    return 0;
}

function suBuscaEntradaPorSobreUsoId($sobreUsoId)
{
    $sobreUsoId = intval($sobreUsoId);
    if ($sobreUsoId <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_sobre_uso",
        "acolhido_entrada_id",
        "prontuario_sobre_uso_id = " . $sobreUsoId,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["acolhido_entrada_id"])) {
        return intval($result[0]["acolhido_entrada_id"]);
    }

    return 0;
}

function suNormalizaDadosJson($dadosJson)
{
    if (!is_string($dadosJson)) {
        return "{}";
    }

    $dadosJson = trim($dadosJson);
    if ($dadosJson === "") {
        return "{}";
    }

    $dados = json_decode($dadosJson, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($dados)) {
        return "{}";
    }

    return json_encode($dados, JSON_UNESCAPED_UNICODE);
}

function suRemoveSobreUsoExistente($sobreUsoId)
{
    $sobreUsoId = intval($sobreUsoId);
    if ($sobreUsoId <= 0) {
        return;
    }

    $sistema = new Sistema();
    $sistema->delete("rec_prontuario_sobre_uso", "prontuario_sobre_uso_id = " . $sobreUsoId);
}

function suSalvaSobreUsoPorEntrada($entradaId, $dadosJson)
{
    $entradaId = intval($entradaId);
    if ($entradaId <= 0) {
        return 0;
    }

    $sobreUsoIdExistente = suBuscaSobreUsoIdPorEntrada($entradaId);
    if ($sobreUsoIdExistente > 0) {
        suRemoveSobreUsoExistente($sobreUsoIdExistente);
    }

    $dados = array();
    $dados["acolhido_entrada_id"] = $entradaId;
    $dados["dados_json"] = suNormalizaDadosJson($dadosJson);
    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");

    $sistema = new Sistema();
    $sistema->insert("rec_prontuario_sobre_uso", $dados);

    return isset($_SESSION["sessionForIdInserted"]) ? intval($_SESSION["sessionForIdInserted"]) : 0;
}
