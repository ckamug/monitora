<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dados["acolhido_nome"] = $_POST["txtNomeCompleto"];
$dados["data_nascimento"] = $_POST["txtDataNascimento"];
$dados["data_entrada"] = $_POST["txtDataEntrada"];
$dados["data_saida"] = $_POST["txtDataSaida"] . " ";
$dados["tipo_desligamento"] = $_POST["slcTipoDesligamento"];
$dados["local_antes_acolhimento"] = $_POST["slcAntesHub"] . " ";

if($_POST["slcAntesHub"]=="Situação de Rua"){
    $dados["local_situacao_rua"] = $_POST["slcLocalSituacaoRua"] . " ";
}

$dados["local_apos_desligamento"] = $_POST["slcAposDesligamento"] . " ";

foreach($_POST["chkSubstanciaConsumia"] as $consumia)
{
    $susbtanciaConsumia .= $consumia . ", ";
}

$dados["tipo_droga"] = $susbtanciaConsumia . " ";
$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["executora_id"] = intval($_SESSION["pfv"]);
$dados["data_alteracao"] = date("Y-m-d h:i:s");
$dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_acolhidos_hub',$dados);

echo base64_encode($_SESSION["sessionForIdInserted"]);