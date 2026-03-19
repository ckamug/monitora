<?php
include "../../../../classes/sistema.php";
session_start();

header('Content-Type: application/json; charset=utf-8');

$tipoDesligamentoId = isset($_POST["tipo_desligamento_id"]) ? $_POST["tipo_desligamento_id"] : null;

$sistema = new Sistema();
$where = "status = 1";
if (!empty($tipoDesligamentoId)) {
    $where = "status = 1 AND tipo_desligamento_id = " . $tipoDesligamentoId;
}
$sistema->select("rec_tipos_encaminhamentos","*",$where,"","tipo_encaminhamento_descricao");
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
