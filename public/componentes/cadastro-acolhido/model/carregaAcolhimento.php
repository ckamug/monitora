<?php
include "../../../../classes/sistema.php";
session_start();

$id = base64_decode($_POST["id"]);

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_acolhidos_entradas","*","acolhido_id = " . $id . " AND status = 1");
$result = $sistema->resultToJSON();

$dados = json_decode($result);

//$dados->{'data_entrada'} = $sistema->convertData($dados->{'data_entrada'});

$result = json_encode($dados);

echo $result;