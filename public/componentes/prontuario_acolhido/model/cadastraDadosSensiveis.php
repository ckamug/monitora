<?php
include "../../../../classes/sistema.php";
session_start();

function postValue($key)
{
    return isset($_POST[$key]) ? $_POST[$key] : "";
}

function normalizaInteiro($valor)
{
    $valor = trim((string)$valor);
    if ($valor === "") {
        return "";
    }

    $somenteDigitos = preg_replace('/\D/', '', $valor);
    if ($somenteDigitos === "") {
        return "";
    }

    return intval($somenteDigitos);
}

$dados = array();
$dados["acolhido_entrada_id"] = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

$dados["negligencia"] = postValue("radNegligencia");
// BLOCO ANTIGO (mantido como referencia):
// $idadeNegligencia = trim(postValue("txtIdadeNegligencia"));
// $dados["negligencia_idade"] = (strpos($dados["negligencia"], "Sim,") === 0) ? $idadeNegligencia : "";
$idadeNegligencia = normalizaInteiro(postValue("txtIdadeNegligencia"));
if (strpos($dados["negligencia"], "Sim,") === 0 && $idadeNegligencia !== "") {
    $dados["negligencia_idade"] = $idadeNegligencia;
}

$dados["violencia_fisica"] = postValue("radViolenciaFisica");
// BLOCO ANTIGO (mantido como referencia):
// $idadeViolenciaFisica = trim(postValue("txtIdadeViolenciaFisica"));
// $dados["violencia_fisica_idade"] = (strpos($dados["violencia_fisica"], "Sim,") === 0) ? $idadeViolenciaFisica : "";
$idadeViolenciaFisica = normalizaInteiro(postValue("txtIdadeViolenciaFisica"));
if (strpos($dados["violencia_fisica"], "Sim,") === 0 && $idadeViolenciaFisica !== "") {
    $dados["violencia_fisica_idade"] = $idadeViolenciaFisica;
}

$dados["violencia_sexual"] = postValue("radViolenciaSexual");
// BLOCO ANTIGO (mantido como referencia):
// $dados["violencia_sexual_idade"] = postValue("txtQualIdade");
$idadeViolenciaSexual = normalizaInteiro(postValue("txtQualIdade"));
// Como a coluna em producao esta VARCHAR e sem default, o campo precisa sempre existir no INSERT.
$dados["violencia_sexual_idade"] = ($dados["violencia_sexual"] == "Sim" && $idadeViolenciaSexual !== "")
    ? (string)$idadeViolenciaSexual
    : "";
$dados["observacoes_violencia_sexual"] = postValue("txtObservacoesViolenciaSexual");

$agressores = "";
if (isset($_POST["chkAgressor"]) && is_array($_POST["chkAgressor"])) {
    foreach ($_POST["chkAgressor"] as $agressor) {
        $agressores .= $agressor . ", ";
    }
}
$dados["agressor"] = $agressores;

$dados["violencia_parceiros"] = postValue("radViolenciaParceiros");
// BLOCO ANTIGO (mantido como referencia):
// $idadeViolenciaParceiros = trim(postValue("txtIdadeViolenciaParceiros"));
// $dados["violencia_parceiros_idade"] = ($dados["violencia_parceiros"] == "Sim") ? $idadeViolenciaParceiros : "";
$idadeViolenciaParceiros = normalizaInteiro(postValue("txtIdadeViolenciaParceiros"));
if ($dados["violencia_parceiros"] == "Sim" && $idadeViolenciaParceiros !== "") {
    $dados["violencia_parceiros_idade"] = $idadeViolenciaParceiros;
}

$tiposViolencia = "";
if (isset($_POST["chkTipoViolenciaParceiro"]) && is_array($_POST["chkTipoViolenciaParceiro"])) {
    foreach ($_POST["chkTipoViolenciaParceiro"] as $tipoViolencia) {
        $tiposViolencia .= $tipoViolencia . ", ";
    }
}
$dados["tipos_violencia_parceiros"] = $tiposViolencia;

$dados["suporte_violencia_parceiros"] = postValue("radSuporte");
$dados["tipo_suporte"] = postValue("txtQualSuporte");
$dados["autor_violencia"] = postValue("radAutorViolencia");

$tiposAutorViolencia = "";
if (isset($_POST["chkTipoViolencia"]) && is_array($_POST["chkTipoViolencia"])) {
    foreach ($_POST["chkTipoViolencia"] as $tipoAutorViolencia) {
        $tiposAutorViolencia .= $tipoAutorViolencia . ", ";
    }
}
$dados["tipo_autor_violencia"] = $tiposAutorViolencia;

$dados["responsabilizado"] = postValue("radResponsabilizado");
$dados["pena_aplicada"] = postValue("radPenaAplicada");
$dados["tempo_pena_aplicada"] = postValue("txtTempoPenaAplicada");
$dados["egresso_sistema_prisional"] = postValue("radEgresso");
$dados["pena_egresso"] = postValue("radEgressoPena");
$dados["tempo_pena_egresso"] = postValue("txtTempoPenaEgresso");
$dados["cumpriu_pena"] = postValue("radCumpriuPena");
$dados["foragido"] = postValue("radForagido");
$dados["liberdade_provisoria"] = postValue("radLiberdade");
$dados["pendencia_judicial"] = postValue("radPendenciaJudicial");
$dados["motivo_pendencia_judicial"] = ($dados["pendencia_judicial"] == "Sim") ? postValue("txtMotivoPendencia") : "";

$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["data_cadastro"] = date("Y-m-d H:i:s");

if ($dados["acolhido_entrada_id"] <= 0) {
    echo 0;
    exit;
}

$sistema = new Sistema();
// BLOCO ANTIGO (mantido como referencia):
// $sistema->insert("rec_acolhidos_dados_sensiveis", $dados);

// Evita duplicidade por acolhido_entrada_id e mantém comportamento de "salvar novamente".
$sistema->delete("rec_acolhidos_dados_sensiveis", "acolhido_entrada_id = " . intval($dados["acolhido_entrada_id"]));
$sistema->insert("rec_acolhidos_dados_sensiveis", $dados);

echo isset($_SESSION["sessionForIdInserted"]) ? intval($_SESSION["sessionForIdInserted"]) : 0;
