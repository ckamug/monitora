<?php
include "../../../../classes/sistema.php";
session_start();

    $apontamento = str_replace("* ","</strong>",str_replace(" *","<strong>",str_replace("'" , "" , $_POST["apontamento"])));

    $dados["nota_fiscal_id"] = $_POST["id"];
    $dados["nota_apontamento_descricao"] = $apontamento;
    
    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");

    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->insert('rec_notas_apontamentos',$dados);
    
    $dadosUpdate["nota_status"] = intval(2);
    $sistema->update('rec_notas_fiscais',$dadosUpdate,'nota_fiscal_id = ' . $_POST["id"]);