<?php
include "../../../../classes/sistema.php";
session_start();

if(is_numeric($_POST["id"])){
    $id = $_POST["id"];
}
else{
    $id = base64_decode($_POST["id"]);
}

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_acolhidos_hub","*","acolhido_hub_id = " . $id);
$result = $sistema->resultToJSON();

$dados = json_decode($result);

$dados->{'data_nascimento'} = substr($dados->{'data_nascimento'},0,10);
$dados->{'data_entrada'} = substr($dados->{'data_entrada'},0,10);
$dados->{'data_saida'} = substr($dados->{'data_saida'},0,10);

$dados->{'perfil_logado'} = $_SESSION["pf"];
$dados->{'local_logado'} = $_SESSION["pfv"];

$result = json_encode($dados);

echo $result;