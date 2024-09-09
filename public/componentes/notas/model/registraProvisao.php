<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_prestacoes","executora_id , celebrante_id , prestacao_mes_referencia","prestacao_id = " . base64_decode($_POST["id"]));
$resultPrestacao = $sistema->getResult();

//$sistema->debug=true;

if($resultPrestacao[0]["celebrante_id"]>0){
    $where = "celebrante_id = " . $resultPrestacao[0]["celebrante_id"] . " AND cabecalho_mes_referencia = '" . $resultPrestacao[0]["prestacao_mes_referencia"] . "'";
}
else{
    $where = "executora_id = " . $resultPrestacao[0]["executora_id"] . " AND cabecalho_mes_referencia = '" . $resultPrestacao[0]["prestacao_mes_referencia"] . "'";
}

$sistema->select("rec_cabecalhos","cabecalho_id",$where);
$resultCabecalho = $sistema->getResult();


$dadosValores["valor_provisao"] =  str_replace("R$ " , "" , $_POST["provisao"]);
//$sistema->debug=true;
$sistema->update('rec_cabecalhos',$dadosValores,'cabecalho_id = ' . $resultCabecalho[0]["cabecalho_id"]);