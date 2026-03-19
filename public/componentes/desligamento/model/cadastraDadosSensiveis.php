<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dados["acolhido_entrada_id"] = $_POST["id"];

$dados["negligencia"] = $_POST["radNegligencia"];
$dados["violencia_fisica"] = $_POST["radViolenciaFisica"];
$dados["violencia_sexual"] = $_POST["radViolenciaSexual"];
$dados["violencia_sexual_idade"] = $_POST["txtQualIdade"];
$dados["observacoes_violencia_sexual"] = $_POST["txtObservacoesViolenciaSexual"];

foreach($_POST["chkAgressor"] as $agressor)
{
    $agressores .= $agressor . ", ";
}
$dados["agressor"] = $agressores;

$dados["violencia_parceiros"] = $_POST["radViolenciaParceiros"];

foreach($_POST["chkTipoViolenciaParceiro"] as $tipoViolencia)
{
    $tiposViolencia .= $tipoViolencia . ", ";
}
$dados["tipos_violencia_parceiros"] = $tiposViolencia;

$dados["suporte_violencia_parceiros"] = $_POST["radSuporte"];
$dados["tipo_suporte"] = $_POST["txtQualSuporte"];
$dados["autor_violencia"] = $_POST["radAutorViolencia"];

foreach($_POST["chkTipoViolencia"] as $tipoAutorViolencia)
{
    $tiposAutorViolencia .= $tipoAutorViolencia . ", ";
}
$dados["tipo_autor_violencia"] = $tiposAutorViolencia;

$dados["responsabilizado"] = $_POST["radResponsabilizado"];
$dados["pena_aplicada"] = $_POST["radPenaAplicada"];
$dados["tempo_pena_aplicada"] = $_POST["txtTempoPenaAplicada"];
$dados["egresso_sistema_prisional"] = $_POST["radEgresso"];
$dados["pena_egresso"] = $_POST["radEgressoPena"];
$dados["tempo_pena_egresso"] = $_POST["txtTempoPenaEgresso"];
$dados["cumpriu_pena"] = $_POST["radCumpriuPena"];
$dados["foragido"] = $_POST["radForagido"];
$dados["liberdade_provisoria"] = $_POST["radLiberdade"];
$dados["pendencia_judicial"] = $_POST["radPendenciaJudicial"];
$dados["motivo_pendencia_judicial"] = $_POST["txtMotivoPendencia"];

$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["data_cadastro"] = date("Y-m-d H:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_acolhidos_dados_sensiveis',$dados);

echo $_SESSION["sessionForIdInserted"];