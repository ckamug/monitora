<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

if($_SESSION['pf']==1 or $_SESSION['pf']==2){
    $sistema->select("rec_executoras","SUM(executora_vagas) as vagas","executora_status = 1","","");
    $result = $sistema->getResult();

    //$sistema->debug=true;
    $sistema->select("rec_solicitacoes_vagas","count(acolhido_id) as ocupadas","status_vaga_id = 3 and status_registro = 1","acolhido_id","");
    $resultOcupadas = $sistema->getResult();
}
else if($_SESSION['pf']==4){
    $sistema->select("rec_executoras","executora_vagas as vagas","executora_status = 1 AND executora_id = " . $_SESSION['pfv'],"","");
    $result = $sistema->getResult();

    //$sistema->debug=true;
    $sistema->select("rec_solicitacoes_vagas","count(acolhido_id) as ocupadas","status_vaga_id = 3 and status_registro = 1 and executora_id = " . $_SESSION['pfv'],"acolhido_id","");
    $resultOcupadas = $sistema->getResult();
}
else{}

echo $result[0]["vagas"] .",". count($resultOcupadas);