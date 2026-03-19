<?php
include "../../../../classes/sistema.php";
include_once __DIR__ . "/contatoReferencia.php";
session_start();

$sistema = new Sistema();
$id = resolverIdAcolhidoOuTemporario(isset($_POST['id']) ? $_POST['id'] : "");

if ($id === "") {
    http_response_code(400);
    exit;
}

$dados["acolhido_id"] = $id;
$dados["referencia_nome"] = $_POST["nomeContato"];
$dados["referencia_contato"] = $_POST["telefoneReferencia"];
$dados["referencia_parentesco"] = $_POST["parentesco"];
$dados["usuario_id"] = base64_decode($_SESSION['usr']);
$dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_acolhidos_referencias',$dados);

echo $id;
