<?php
include "../../../../classes/sistema.php";
session_start();

$dir = "anexos/acolhidos/" . base64_decode($_POST["id"]);
$quadro = $_POST["quadro"];

if(!is_dir($dir)){
    mkdir($dir, 0755, true);
}

if($_FILES[$quadro]['name']!=""){
    $ext = extensao(strtolower($_FILES[$quadro]['name']));
    $filename = strtolower($_FILES[$quadro]['name']);
    $src = $_FILES[$quadro]['tmp_name'];

    if( in_array( $ext, array("pdf","jpg","gif","png","jpeg","bmp") ) ){
        $output_dir = $dir . "/" . $quadro . "_" . $filename;
        if(move_uploaded_file($src, $output_dir)){
            echo $_POST["id"];
        }else{
            echo 1; //Erro no envio do arquivo
        }
    }
    else{
        echo 0;
    }
}

function extensao($arquivo){
    $arquivo = strtolower($arquivo);
    $explode = explode(".", $arquivo);
    $arquivo = end($explode);
    
    return ($arquivo);
}