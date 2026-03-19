<?php
include_once "../../../../configuracoes.php";
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dados["prontuario_entrada_id"] = $_POST["id"];
$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["tipo_atendimento_id"] = $_POST["slcTiposAtendimentos"];
$dados["subtipo_atendimento_id"] = (isset($_POST["slcSubTiposAtendimentos"])) ? $_POST["slcSubTiposAtendimentos"] : 0;
$dados["descricao_outro_tipo_atendimento"] = $_POST["txtOutraAtividade"];
$dados["descricao_acao"] = $_POST["txtDescricaoAcaoPsicologia"];
$dados["sigiloso"] = (isset($_POST["chkSigilosoPsi"]) AND $_POST["chkSigilosoPsi"]==1) ? 1 : 0;
$dados["data_cadastro"] = normalizaDataAtendimento($_POST["txtDataAnotacaoPsicologia"]);

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

function normalizaDataAtendimento($dataInformada){
    $dataInformada = trim((string)$dataInformada);
    $horaAtual = date("H:i:s");

    if($dataInformada!=""){
        $formatos = array("Y-m-d", "d/m/Y");

        foreach($formatos as $formato){
            $data = DateTime::createFromFormat($formato, $dataInformada);

            if($data instanceof DateTime){
                $erros = DateTime::getLastErrors();
                if($erros === false || ($erros["warning_count"] == 0 && $erros["error_count"] == 0)){
                    return $data->format("Y-m-d") . " " . $horaAtual;
                }
            }
        }
    }

    return date("Y-m-d H:i:s");
}
