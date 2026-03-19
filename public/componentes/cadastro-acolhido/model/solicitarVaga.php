<?php
include_once "../../../../configuracoes.php";
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dados["acolhido_id"] = intval(base64_decode($_POST["acolhido"]));
$dados["executora_id"] = 0;
$dados["usuario_id"] = intval(base64_decode($_SESSION["usr"]));
$dados["municipio_id"] = intval($_SESSION["pfv"]);
$dados["servico_id"] = intval($_POST["servico_id"]);
$dados["genero_solicitado"] = $_POST["genero_solicitado"];
$dados["status_vaga_id"] = 5;
$dados["data_cadastro"] = date("Y-m-d H:i:s");

if ($dados["servico_id"] <= 0 || $dados["genero_solicitado"] == "") {
    echo "Dados invalidos para solicitacao de vaga.";
    exit;
}

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_solicitacoes_vagas',$dados);
