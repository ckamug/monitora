<?php
include "../../../../classes/sistema.php";
session_start();

if(date("m")==01){
    $mesReferencia = (date("Y")-1) . '-12';
}
else{
    $mesReferencia = date("Y") . '-' . date("m",strtotime('-1 month'));
}

$sistema = new Sistema();
$sistema->select("rec_prestacoes","COUNT(prestacao_id) as total",'prestacao_status = 1 AND prestacao_mes_referencia = "' . $mesReferencia . '"','');

$result = $sistema->getResult();

echo $result[0]["total"];