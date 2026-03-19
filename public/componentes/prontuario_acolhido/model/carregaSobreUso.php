<?php
include "../../../../classes/sistema.php";
session_start();

header('Content-Type: application/json; charset=utf-8');

function resolveAcolhidoEntradaId($idRecebido)
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

function suEhUtf8Valido($valor)
{
    if (!is_string($valor)) {
        return true;
    }

    return preg_match('//u', $valor) === 1;
}

function suNormalizaUtf8Recursivo($valor)
{
    if (is_array($valor)) {
        foreach ($valor as $chave => $item) {
            $valor[$chave] = suNormalizaUtf8Recursivo($item);
        }
        return $valor;
    }

    if (is_string($valor) && !suEhUtf8Valido($valor)) {
        return utf8_encode($valor);
    }

    return $valor;
}

function suDecodificaDadosJson($jsonBruto)
{
    if (!is_string($jsonBruto) || trim($jsonBruto) === "") {
        return array();
    }

    $dados = json_decode($jsonBruto, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($dados)) {
        return $dados;
    }

    $jsonAlternativo = suEhUtf8Valido($jsonBruto) ? $jsonBruto : utf8_encode($jsonBruto);
    $dados = json_decode($jsonAlternativo, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($dados)) {
        return $dados;
    }

    return array();
}

$entradaId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = resolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0) {
    echo json_encode(new stdClass(), JSON_UNESCAPED_UNICODE);
    exit;
}

$sistema = new Sistema();
$sistema->select(
    "rec_prontuario_sobre_uso",
    "prontuario_sobre_uso_id, dados_json",
    "acolhido_entrada_id = " . $entradaId,
    "",
    ""
);
$result = $sistema->getResult();

if (!$result || !isset($result[0])) {
    echo json_encode(new stdClass(), JSON_UNESCAPED_UNICODE);
    exit;
}

$registro = $result[0];
$dados = isset($registro["dados_json"]) ? suDecodificaDadosJson($registro["dados_json"]) : array();
$dados = suNormalizaUtf8Recursivo($dados);

$retorno = array(
    "prontuario_sobre_uso_id" => intval($registro["prontuario_sobre_uso_id"]),
    "dados" => $dados
);

echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
