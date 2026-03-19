<?php
include "../../../../classes/sistema.php";
session_start();

header('Content-Type: application/json; charset=utf-8');

function retornaJson($result)
{
    if (!$result) {
        $result = array();
    }

    foreach ($result as &$row) {
        foreach ($row as $key => $val) {
            if (is_string($val)) {
                $row[$key] = utf8_encode($val);
            }
        }
    }
    unset($row);

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

$estadoId = isset($_POST["estado_id"]) ? intval($_POST["estado_id"]) : 0;

if ($estadoId <= 0) {
    echo json_encode(array(), JSON_UNESCAPED_UNICODE);
    exit;
}

$sistema = new Sistema();
$sistema->select("tbl_cidades", "cidade_id, cidade_descricao", "estado_id = " . $estadoId, "", "cidade_descricao");
$result = $sistema->getResult();

retornaJson($result);
