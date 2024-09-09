<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dados["analise_coed"] = intval($_POST["acao"]);
if($_POST["acao"]==1){
    $dados["nota_status"] = intval(6);
}
else{
    $dados["nota_status"] = intval(1);
}

$sistema->update('rec_notas_fiscais',$dados,'nota_fiscal_id = ' . $_POST["id"]);