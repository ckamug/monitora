<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dados["acolhido_id"] = intval(base64_decode($_POST["acolhido"]));
$dados["executora_id"] = intval($_POST["executora"]);
$dados["usuario_id"] = intval(base64_decode($_SESSION["usr"]));
$dados["municipio_id"] = intval($_SESSION["pfv"]);
$dados["data_cadastro"] = date("Y-m-d H:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_solicitacoes_vagas',$dados);