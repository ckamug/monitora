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
    "rec_prontuario_historico_social",
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
$historicoId = isset($registro["prontuario_historico_social_id"]) ? intval($registro["prontuario_historico_social_id"]) : 0;

$motivosRuaIds = array();
$referenciadaIds = array();

if ($historicoId > 0) {
    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_hs_motivos_rua",
        "motivo_rua_id",
        "prontuario_historico_social_id = " . $historicoId,
        "",
        "motivo_rua_id"
    );
    $resMotivos = $sistema->getResult();
    if ($resMotivos) {
        foreach ($resMotivos as $item) {
            if (isset($item["motivo_rua_id"])) {
                $motivosRuaIds[] = intval($item["motivo_rua_id"]);
            }
        }
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_hs_referenciada",
        "referenciada_id",
        "prontuario_historico_social_id = " . $historicoId,
        "",
        "referenciada_id"
    );
    $resReferenciada = $sistema->getResult();
    if ($resReferenciada) {
        foreach ($resReferenciada as $item) {
            if (isset($item["referenciada_id"])) {
                $referenciadaIds[] = intval($item["referenciada_id"]);
            }
        }
    }
}

foreach ($registro as $key => $val) {
    if (is_string($val)) {
        $registro[$key] = utf8_encode($val);
    }
}

$registro["motivos_rua_ids"] = $motivosRuaIds;
$registro["referenciada_ids"] = $referenciadaIds;

echo json_encode($registro, JSON_UNESCAPED_UNICODE);
