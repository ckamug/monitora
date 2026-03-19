<?php
include "../../../../classes/sistema.php";
session_start();

header('Content-Type: application/json; charset=utf-8');

function possuiColuna($tabela, $coluna)
{
    $sistema = new Sistema();
    $where = "TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . $tabela . "' AND COLUMN_NAME = '" . $coluna . "'";
    $sistema->select("information_schema.COLUMNS", "COUNT(*) AS total", $where, "", "");
    $result = $sistema->getResult();

    if (!$result || !isset($result[0]["total"])) {
        return false;
    }

    return intval($result[0]["total"]) > 0;
}

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
$where = "";

if (possuiColuna("rec_etnias", "status")) {
    $where = "status = 1";
}

$sistema->select("rec_etnias", "etnia_id, etnia_descricao", $where, "", "etnia_descricao");
$result = $sistema->getResult();

retornaJson($result);
