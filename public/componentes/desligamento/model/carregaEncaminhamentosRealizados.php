<?php
include "../../../../classes/sistema.php";
session_start();

header('Content-Type: application/json; charset=utf-8');

$sistema = new Sistema();
$sistema->select("rec_tipos_encaminhamentos_realizados","*","status = 1","","tipo_encaminhamento_realizado_descricao");
$result = $sistema->getResult();

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
