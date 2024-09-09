<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dados["prestacao_ciencia"] = intval(1);
$dados["usuario_id_ciencia"] = intval(base64_decode($_SESSION["usr"]));

$sistema->update("rec_prestacoes",$dados,"prestacao_id = " . base64_decode($_POST["id"]));