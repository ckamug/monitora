<?php
include "../../../../classes/sistema.php";
session_start();

$dados["executora_id"] = intval(base64_decode($_POST["id"]));
$dados["executora_responsavel_nome"] = $_POST["nome"];
$dados["executora_responsavel_cpf"] = $_POST["cpf"];
$dados["cargo_id"] = intval($_POST["cargo"]);
$dados["usuario_id"] = intval(base64_decode($_SESSION["usr"]));
$dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_executoras_responsaveis',$dados);