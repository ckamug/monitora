<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_usuarios","perfil_id , perfil_vinculo_id","usuario_id = " . base64_decode($_SESSION["usr"]) , "" , "");
$result = $sistema->getResult();

$dir = "anexos/" . $result[0]["perfil_id"] . "_" . $result[0]["perfil_vinculo_id"];


if(file_exists( $dir . "/" . $_POST["txtNumeroNotaFiscal"] . ".pdf" )){

    $dados["data_nota_fiscal"] = $_POST["txtDataNota"];
    $dados["numero_nota_fiscal"] = $_POST["txtNumeroNotaFiscal"];
    $dados["categoria_id"] = $_POST["slcCategorias"];
    $dados["subcategoria_id"] = $_POST["slcSubcategorias"];
    $dados["valor_nota"] = str_replace("R$ " , "" , $_POST["txtValorNotaFiscal"]);

    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->update('rec_notas_fiscais',$dados,'nota_fiscal_id = ' . $_POST["id"]);

    echo 3;

}
else{

    if(!is_dir($dir)){
        mkdir($dir, 0755, true);
    }

    $ext = extensao($_FILES["arquivo"]['name']);
    $filename = $_POST["txtNumeroNotaFiscal"].'.'.$ext;
    $src = $_FILES["arquivo"]['tmp_name'];

    if( $ext=="pdf"){
        
        $output_dir = $dir . "/" . $filename;
        
        if(move_uploaded_file($src, $output_dir )){

            $dados["data_nota_fiscal"] = $_POST["txtDataNota"];
            $dados["numero_nota_fiscal"] = $_POST["txtNumeroNotaFiscal"];
            $dados["categoria_id"] = $_POST["slcCategorias"];
            $dados["subcategoria_id"] = $_POST["slcSubcategorias"];
            $dados["valor_nota"] = str_replace("R$ " , "" , $_POST["txtValorNotaFiscal"]);

            $sistema = new Sistema();
            //$sistema->debug=true;
            $sistema->update('rec_notas_fiscais',$dados,'nota_fiscal_id = ' . $_POST["id"]);

            echo 2;
        
        }else{
            echo 1; //Erro no envio do arquivo
        }

    }
    else{
        echo 0; //Formato de arquivo inválido
    }

}

function extensao($arquivo){
    $arquivo = strtolower($arquivo);
    $explode = explode(".", $arquivo);
    $arquivo = end($explode);
    
    return ($arquivo);
}