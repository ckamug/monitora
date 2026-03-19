<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dadosUpdate["nota_status"] = intval($_POST["status"]);
$dadosUpdate["data_alteracao"] = date("Y-m-d H:i:s");

if($_SESSION['pf']==2){
    $dadosUpdate["usuario_id_celebrante_status"] = base64_decode($_SESSION["usr"]);
}
else{
    $dadosUpdate["usuario_id_status"] = base64_decode($_SESSION["usr"]);
}

if($_POST["status"]!=7){
    $sistema->update('rec_notas_fiscais',$dadosUpdate,'nota_fiscal_id = ' . $_POST["id"]);
}

if($_POST["status"]==4 OR $_POST["status"]==7){
    if($_POST["status"]==7){
        $dados["valor_glosa_parcial"] = str_replace("R$ " , "" , $_POST["valorGlosa"]);    
    }
    $dados["nota_fiscal_id"] = intval($_POST["id"]);
    $dados["motivo_glosa_descricao"] = $_POST["motivo"];
            
    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");
        
    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->insert('rec_notas_motivos_glosa',$dados);
}

if($_POST["status"]==8){
    $dados["nota_fiscal_id"] = intval($_POST["id"]);
    $dados["ressalva_descricao"] = $_POST["ressalva"];
            
    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");
        
    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->insert('rec_notas_ressalvas',$dados);
}