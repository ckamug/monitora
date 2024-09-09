<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

if($_POST['id']!=""){
    $id = base64_decode($_POST['id']);
}
else{
    $id = $_SESSION["hs"];
}

$dados["acolhido_id"] = $id;
$dados["referencia_nome"] = $_POST["nomeContato"];
$dados["referencia_contato"] = $_POST["telefoneReferencia"];
$dados["referencia_parentesco"] = $_POST["parentesco"];
$dados["usuario_id"] = base64_decode($_SESSION['usr']);
$dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_acolhidos_referencias',$dados);

echo $id;