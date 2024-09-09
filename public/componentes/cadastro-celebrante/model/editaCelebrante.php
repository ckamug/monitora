<?php
include "../../../../classes/sistema.php";
session_start();

$dados["celebrante_nome_fantasia"] = $_POST["txtNomeFantasia"];
$dados["celebrante_razao_social"] = $_POST["txtRazaoSocial"];
$dados["celebrante_cnpj"] = $_POST["txtCnpj"];
$dados["celebrante_cnae"] = $_POST["txtCnae"];
$dados["celebrante_cep"] = $_POST["txtCep"];
$dados["celebrante_endereco"] = $_POST["txtEndereco"];
$dados["celebrante_numero"] = $_POST["txtNumero"];
$dados["celebrante_complemento"] = $_POST["txtComplemento"];
$dados["celebrante_bairro"] = $_POST["txtBairro"];
$dados["cidade_id"] = $_POST["slcMunicipios"];
$dados["celebrante_email"] = $_POST["txtEmail"];
$dados["celebrante_telefone"] = $_POST["txtTelefone"];


if(base64_decode($_SESSION["usr"])==1 OR base64_decode($_SESSION["usr"])==18 OR base64_decode($_SESSION["usr"])==22){
    $dados["celebrante_valor_previsto_rh"] = str_replace("R$ ","",$_POST["txtRh"]);
    $dados["celebrante_valor_previsto_custeio"] = str_replace("R$ ","",$_POST["txtCusteio"]);
    $dados["celebrante_valor_previsto_terceiros"] = str_replace("R$ ","",$_POST["txtServicosTerceiros"]);
}

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->update('rec_celebrantes',$dados,'celebrante_id = ' . $_POST["id"]);