<?php
include "../../../../classes/sistema.php";
session_start();

function resolveAcolhidoEntradaId($idRecebido)
{
    $idRecebido = intval($idRecebido);
    if ($idRecebido <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_acolhidos_entradas",
        "acolhido_entrada_id",
        "acolhido_entrada_id = " . $idRecebido,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["acolhido_entrada_id"])) {
        return intval($result[0]["acolhido_entrada_id"]);
    }

    return 0;
}

function postValue($key)
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}

function normalizaDataMedicacao($dataInformada)
{
    $dataInformada = trim((string)$dataInformada);
    if ($dataInformada == "") {
        return date("Y-m-d");
    }

    $formatos = array("Y-m-d", "d/m/Y");

    foreach ($formatos as $formato) {
        $data = DateTime::createFromFormat($formato, $dataInformada);

        if ($data instanceof DateTime) {
            $erros = DateTime::getLastErrors();
            if ($erros === false || ($erros["warning_count"] == 0 && $erros["error_count"] == 0)) {
                return $data->format("Y-m-d");
            }
        }
    }

    return date("Y-m-d");
}

$entradaId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = resolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0) {
    echo 0;
    exit;
}

$dados = array();
$dados["acolhido_entrada_id"] = $entradaId;
$dados["data_medicacao"] = normalizaDataMedicacao(postValue("txtDataMedicacaoRegistro"));
$dados["nome_medicacao"] = postValue("txtNomeMedicacao");
$dados["dosagem"] = postValue("txtDosagemMedicacao");
$dados["prescricao"] = postValue("txtPrescricaoMedicacao");
$dados["tempo_uso"] = postValue("txtTempoUsoMedicacao");
$dados["unidade_saude_prescreveu"] = postValue("txtUnidadeSaudeMedicacao");
$dados["observacoes"] = postValue("txtObservacoesMedicacao");
$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["data_cadastro"] = date("Y-m-d H:i:s");

$sistema = new Sistema();
$sistema->insert("rec_prontuario_medicacoes", $dados);

$id = isset($_SESSION["sessionForIdInserted"]) ? intval($_SESSION["sessionForIdInserted"]) : 0;
echo $id;
