<?php
include "../../../../classes/sistema.php";
session_start();

header('Content-Type: application/json; charset=utf-8');

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

if ($id <= 0) {
    echo json_encode(new stdClass(), JSON_UNESCAPED_UNICODE);
    exit;
}

$sistema = new Sistema();
$campos = "a.* , b.*";
$from = "rec_acolhidos_dados_sensiveis a";
$innerJoin = array();
$innerJoin[] = "left join rec_usuarios b on a.usuario_id = b.usuario_id";
$where = "a.acolhido_entrada_id = " . $id;

$sistema->innerJoin($campos, $from, $innerJoin, $where, "", "");
$result = $sistema->getResult();

if (!$result || !isset($result[0])) {
    echo json_encode(new stdClass(), JSON_UNESCAPED_UNICODE);
    exit;
}

$registro = $result[0];
foreach ($registro as $key => $val) {
    if (is_string($val)) {
        $registro[$key] = utf8_encode($val);
    }
    else if ($val === null) {
        $registro[$key] = "";
    }
}

echo json_encode($registro, JSON_UNESCAPED_UNICODE);
