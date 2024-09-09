<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$sistema->select("rec_prestacoes","prestacao_ciencia","prestacao_id = " . base64_decode($_POST["id"]));
$result = $sistema->getResult();

echo $result[0]["prestacao_ciencia"];