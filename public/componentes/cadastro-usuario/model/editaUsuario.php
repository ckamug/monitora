<?php
include "../../../../classes/sistema.php";
session_start();

$dados["usuario_nome"] = $_POST["txtNome"];
$dados["usuario_cpf"] = $_POST["txtCpf"];
$dados["usuario_email"] = $_POST["txtEmail"];
$dados["tipo_registro_id"] = (isset($_POST["slcTipoRegistro"])) ? $_POST["slcTipoRegistro"] : 0;
$dados["numero_registro"] = $_POST["txtNumeroRegistro"];

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->update('rec_usuarios',$dados,'usuario_id = ' . $_POST["id"]);