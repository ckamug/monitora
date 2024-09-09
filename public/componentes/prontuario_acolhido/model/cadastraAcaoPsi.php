<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dados["prontuario_entrada_id"] = $_POST["id"];
$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["tipo_atendimento_id"] = $_POST["slcTiposAtendimentos"];
$dados["descricao_acao"] = $_POST["txtDescricaoAcaoPsicologia"];
$dados["data_cadastro"] = date("Y-m-d H:i:s");

$hash = md5($dados["data_cadastro"]);

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_prontuario_psicologia',$dados);
$id =  $_SESSION["sessionForIdInserted"];

$dir = "anexos/" . $_POST["id"];

if(!is_dir($dir)){
    mkdir($dir, 0755, true);
}

if($_FILES["arquivo"]['name']!=""){
    $ext = extensao($_FILES["arquivo"]['name']);
    $filename = 'psi.'.$id.'.' . $hash . '.'.$ext;
    $src = $_FILES["arquivo"]['tmp_name'];

    if($ext=="pdf" OR $ext=="doc" OR $ext=="docx" OR $ext=="xls" OR $ext=="xlsx" OR $ext=="jpg" OR $ext=="jpeg" OR $ext=="gif" OR $ext=="bmp"){
        $output_dir = $dir . "/" . $filename;

        if(move_uploaded_file($src, $output_dir)){
            echo 1;
        }
    }
    else{
        echo 0;
    }
}
else{

}

function extensao($arquivo){
    $arquivo = strtolower($arquivo);
    $explode = explode(".", $arquivo);
    $arquivo = end($explode);
    
    return ($arquivo);
}