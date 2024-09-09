<?php
include "../../../../classes/sistema.php";
session_start();

$dados["executora_id"] = intval(base64_decode($_POST["id"]));

if($_POST["id"]==""){
    $where = "executora_id = 0";
}
else{
    $where = "executora_id = " . intval(base64_decode($_POST["id"]));
}

$sistema = new Sistema();
$sistema->select("rec_executoras_casas","*",$where,"","");
$res = $sistema->getResult();

if(count($res)==0){
    $dados["executora_casa_descricao"] = "CASA 1";
}
else{
    $dados["executora_casa_descricao"] = "CASA " . (count($res)+1);
}


$dados["usuario_id"] = intval(base64_decode($_SESSION["usr"]));
$dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_executoras_casas',$dados);