<?php
include "../../../../classes/sistema.php";
session_start();

    $dir = "anexos/prestacoes";

    $ext = extensao($_FILES['arquivo']['name']);
    $filename = "DocCompl" . $_POST["hidIdPrestacaoDoc"].'.'.$ext;
    $src = $_FILES['arquivo']['tmp_name'];

    if( in_array( $ext, array("pdf","xls","xlsx") ) ){
        
        $output_dir = $dir . "/" . $filename;
        
        if(move_uploaded_file($src, $output_dir )){
            echo $filename;
        }else{
            echo 1; //Erro no envio do arquivo
        };
    
    }
    else{
        echo 0; //Formato de arquivo inválido
    }

    function extensao($arquivo){
        $arquivo = strtolower($arquivo);
        $explode = explode(".", $arquivo);
        $arquivo = end($explode);
     
        return ($arquivo);
    }