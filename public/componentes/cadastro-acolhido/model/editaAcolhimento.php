<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$sistema->select("rec_solicitacoes_vagas","solicitacao_vaga_id , municipio_id","acolhido_id = " . base64_decode($_POST['acolhido']) . " AND status_registro = 1 AND status_vaga_id IN (1,2,3)");
$res = $sistema->getResult();

foreach($_POST["docPossui"] as $docPossui)
{
    $todosDocPossui .= $docPossui . ",";
}

foreach($_POST["docNecessaria"] as $docNecessaria)
{
    $todosDocNecessaria .= $docNecessaria . ",";
}

$dados["acolhido_id"] = base64_decode($_POST['acolhido']);
$dados["executora_id"] = intval($_SESSION["pfv"]);
$dados["solicitacao_vaga_id"] = $res[0]['solicitacao_vaga_id'];
$dados["doc_possui"] = substr($todosDocPossui,0,-1);
$dados["outros_doc_possui"] = $_POST["outroDocPossui"];
$dados["doc_necessaria"] = substr($todosDocNecessaria,0,-1);
$dados["outros_doc_necessaria"] = $_POST["outroDocNecessaria"];
$dados["acolhido_escolaridade"] = $_POST["escolaridade"];
$dados["acolhido_beneficio"] = $_POST["beneficios"];

if($_POST["beneficios"]=="SIM"){
    foreach($_POST["tipoBeneficios"] as $tipoBeneficio)
    {
        $todosTiposBeneficios .= $tipoBeneficio . ",";

        if($tipoBeneficio=='Outros'){
            $dados["outro_tipo_beneficio"] = $_POST["outroTipoBeneficio"];
        }

    }
    
    $dados["acolhido_tipo_beneficio"] = substr($todosTiposBeneficios,0,-1);
}

$dados["acolhido_valor_recebido"] = str_replace("R$ " , "" , $_POST["valorRecebido"]);

// $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
// $dados["executora_id"] = intval($_SESSION["pfv"]);
// $dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->update('rec_acolhidos_entradas',$dados,"acolhido_entrada_id=" . $_POST['id']);

echo $_POST['acolhido'];
