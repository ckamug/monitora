<?php
include "../../../../classes/sistema.php";
session_start();

$dados["municipio_orgao_publico"] = $_POST["txtOrgaoPublico"];
$dados["municipio_cnpj"] = $_POST["txtCnpj"];
$dados["municipio_cep"] = $_POST["txtCep"];
$dados["municipio_endereco"] = $_POST["txtEndereco"];
$dados["municipio_numero"] = $_POST["txtNumero"];
$dados["municipio_complemento"] = $_POST["txtComplemento"];
$dados["municipio_bairro"] = $_POST["txtBairro"];
$dados["cidade_id"] = $_POST["slcMunicipios"];
$dados["municipio_tecnico_referencia"] = $_POST["txtTecnicoReferencia"];
$dados["municipio_email_institucional"] = $_POST["txtEmail"];
$dados["municipio_telefone"] = $_POST["txtTelefone"];
$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_municipios',$dados);