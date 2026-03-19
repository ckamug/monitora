<?php
include_once "../../../../configuracoes.php";
include "../../../../classes/sistema.php";
session_start();

if (intval($_SESSION["pf"]) != 4) {
    echo "Perfil sem permissao para responder solicitacoes.";
    exit;
}

$sistema = new Sistema();

$sistema->select(
    "rec_solicitacoes_vagas",
    "*",
    "solicitacao_vaga_id = " . intval($_POST["solicitacao_id"]) . " AND status_registro = 1 AND status_vaga_id = 1 AND executora_id = " . intval($_SESSION["pfv"])
);
$result = $sistema->getResult();

if (!is_array($result) || count($result) == 0) {
    echo "Solicitacao nao encontrada ou indisponivel para esta OSC.";
    exit;
}

$dados_update['status_registro']=0;
$sistema->update("rec_solicitacoes_vagas",$dados_update,"solicitacao_vaga_id = " . $_POST["solicitacao_id"]);

$dados['acolhido_id'] = $result[0]["acolhido_id"];
$dados['executora_id'] = $result[0]["executora_id"];
$dados['usuario_id'] = base64_decode($_SESSION["usr"]);
$dados['municipio_id'] = $result[0]["municipio_id"];

if($_POST["parametro"]==0){
    $dados['status_vaga_id'] = 4;
    $msg = "Vaga negada";
}
else{
    $dados['status_vaga_id'] = 2;
    $msg = "Vaga reservada";
}

$dados['data_cadastro'] = date("Y-m-d H:i:s");

$sistema->insert("rec_solicitacoes_vagas",$dados);


if($_POST["justificativa"]!=""){
 
    $dadosJustificativa['solicitacao_vaga_id'] = $_SESSION['sessionForIdInserted'];
    $dadosJustificativa['solicitacao_vaga_justificativa_descricao'] = $_POST["justificativa"];
    $dadosJustificativa['usuario_id'] = base64_decode($_SESSION["usr"]);
    $dadosJustificativa['data_cadastro'] = date("Y-m-d H:i:s");

    $sistema->insert("rec_solicitacoes_vagas_justificativas",$dadosJustificativa);

}

echo $msg;
