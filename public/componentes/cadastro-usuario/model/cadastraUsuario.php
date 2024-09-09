<?php
include "../../../../classes/sistema.php";
session_start();

$senha = base64_encode(str_replace(".","",str_replace("-","",$_POST['txtCpf'])));
$options = ['cost' => 8];
$hash = password_hash($senha,  PASSWORD_DEFAULT, $options);

//$dados["perfil_id"] = $_POST["slcPerfis"];
$dados["perfil_id"] = 0;

if($_POST["slcPerfilVinculo"]){
    $dados["perfil_vinculo_id"] = $_POST["slcPerfilVinculo"];
}
else{
    $dados["perfil_vinculo_id"]=0;
}
$dados["usuario_nome"] = $_POST["txtNome"];
$dados["usuario_cpf"] = $_POST["txtCpf"];
$dados["usuario_email"] = $_POST["txtEmail"];
$dados['usuario_senha'] = $senha;
$dados['usuario_hash'] = $hash;
$dados["usuario_cadastro_id"] = base64_decode($_SESSION["usr"]);
$dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_usuarios',$dados);

echo base64_encode($_SESSION['sessionForIdInserted']);