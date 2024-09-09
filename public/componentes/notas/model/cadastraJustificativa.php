<?php
include "../../../../classes/sistema.php";
session_start();
    
    $dados["nota_apontamento_id"] = $_POST["nota_apontamento_id"];
    $dados["nota_justificativa_descricao"] = $_POST["justificativa"];
            
    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");
        
    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->insert('rec_notas_justificativas',$dados);

    $dadosUpdate["nota_status"] = intval(1);
    
    $sistema->update('rec_notas_fiscais',$dadosUpdate,'nota_fiscal_id = ' . $_POST["id"]);