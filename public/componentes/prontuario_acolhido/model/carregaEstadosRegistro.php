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

$sistema = new Sistema();
$sistema->select("tbl_estados", "estado_id, estado_descricao", "", "", "estado_descricao");
$result = $sistema->getResult();

retornaJson($result);
