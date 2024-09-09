<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_usuarios","perfil_id , perfil_vinculo_id","usuario_id = " . base64_decode($_SESSION["usr"]) , "" , "");
$result = $sistema->getResult();

$dir = "anexos/" . base64_decode($_POST["hidIdPrestacao"]);

if(!is_dir($dir)){
    mkdir($dir, 0755, true);
}

if($_FILES["arquivo"]['name']!=""){
    $ext = extensao($_FILES["arquivo"]['name']);
    $filename = 'usr' . base64_decode($_SESSION["usr"]) . '.'.$ext;
    $src = $_FILES["arquivo"]['tmp_name'];

    if($ext=="pdf"){
        $output_dir = $dir . "/" . $filename;
        if(move_uploaded_file($src, $output_dir)){
            registraNota(base64_decode($_SESSION["usr"]));
        }
    }
    else{
        echo 0;
    }
}
else{
    registraNota(0);
}

function registraNota($tmp){

    $dados["prestacao_id"] = base64_decode($_POST["hidIdPrestacao"]);
    $dados["data_nota_fiscal"] = $_POST["txtDataNota"];
    $dados["numero_nota_fiscal"] = $_POST["txtNumeroNotaFiscal"];
    $dados["categoria_id"] = intval($_POST["slcCategorias"]);
    $dados["subcategoria_id"] = intval($_POST["slcSubcategorias"]);
    $dados["valor_nota"] = str_replace("R$ " , "" , $_POST["txtValorNotaFiscal"]);
    $dados["data_pagamento"] = $_POST["txtDataPagamento"];
    
    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");
    
    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->insert('rec_notas_fiscais',$dados);

    echo $_SESSION['sessionForIdInserted'];

    if($tmp!=0){
        rename ("anexos/" . base64_decode($_POST["hidIdPrestacao"]) . "/usr" . $tmp . ".pdf", "anexos/" . base64_decode($_POST["hidIdPrestacao"]) . "/" . $_SESSION['sessionForIdInserted'] . ".pdf");
    }

}

function extensao($arquivo){
    $arquivo = strtolower($arquivo);
    $explode = explode(".", $arquivo);
    $arquivo = end($explode);
    
    return ($arquivo);
}