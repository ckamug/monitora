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

$entradaId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = resolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0) {
    echo json_encode(new stdClass(), JSON_UNESCAPED_UNICODE);
    exit;
}

$sistema = new Sistema();
$sistema->select(
    "rec_prontuario_saude_geral",
    "*",
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
$saudeGeralId = isset($registro["prontuario_saude_geral_id"]) ? intval($registro["prontuario_saude_geral_id"]) : 0;

$doencasIds = array();
if ($saudeGeralId > 0) {
    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_sg_doencas",
        "doenca_id",
        "prontuario_saude_geral_id = " . $saudeGeralId,
        "",
        "doenca_id"
    );
    $resDoencas = $sistema->getResult();
    if ($resDoencas) {
        foreach ($resDoencas as $item) {
            if (isset($item["doenca_id"])) {
                $doencasIds[] = intval($item["doenca_id"]);
            }
        }
    }
}

foreach ($registro as $key => $val) {
    if (is_string($val)) {
        $registro[$key] = utf8_encode($val);
    }
}

$registro["doencas_ids"] = $doencasIds;

echo json_encode($registro, JSON_UNESCAPED_UNICODE);

