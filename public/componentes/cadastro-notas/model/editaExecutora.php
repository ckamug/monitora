<?php
include "../../../../classes/sistema.php";
session_start();

$dados["executora_nome_fantasia"] = $_POST["txtNomeFantasia"];
$dados["executora_razao_social"] = $_POST["txtRazaoSocial"];
$dados["executora_cnpj"] = $_POST["txtCnpj"];
$dados["executora_cnae"] = $_POST["txtCnae"];
$dados["executora_cep"] = $_POST["txtCep"];
$dados["executora_endereco"] = $_POST["txtEndereco"];
$dados["executora_numero"] = $_POST["txtNumero"];
$dados["executora_complemento"] = $_POST["txtComplemento"];
$dados["executora_bairro"] = $_POST["txtBairro"];
$dados["cidade_id"] = $_POST["slcMunicipios"];
$dados["executora_email"] = $_POST["txtEmail"];
$dados["executora_telefone"] = $_POST["txtTelefone"];
$dados["executora_vagas"] = $_POST["txtVagas"];

foreach($_POST["chkGenero"] as $genero)
{
    $generos .= $genero . ", ";
}

foreach($_POST["chkServico"] as $servico)
{
    $servicos .= $servico . ", ";
}

$dados["executora_generos"] = $generos;
$dados["executora_servicos_id"] = $servicos;

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->update('rec_executoras',$dados,'executora_id = ' . $_POST["id"]);