<?php
include "../../../../classes/sistema.php";
session_start();

header('Content-Type: application/json; charset=utf-8');

$entradaId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

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

$entradaId = resolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0) {
    echo json_encode(new stdClass(), JSON_UNESCAPED_UNICODE);
    exit;
}

$sistema = new Sistema();
$sistema->select(
    "rec_prontuario_identificacao",
    "*",
    "prontuario_entrada_id = " . $entradaId,
    "",
    ""
);

$result = $sistema->getResult();

if (!$result || !isset($result[0])) {
    echo json_encode(new stdClass(), JSON_UNESCAPED_UNICODE);
    exit;
}

foreach ($result[0] as $key => $val) {
    if (is_string($val)) {
        $result[0][$key] = utf8_encode($val);
    }
}

echo json_encode($result[0], JSON_UNESCAPED_UNICODE);
