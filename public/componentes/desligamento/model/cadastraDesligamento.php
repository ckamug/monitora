<?php
include_once "../../../../configuracoes.php";
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dados["acolhido_entrada_id"] = $_POST["id"];
$dados["tipo_desligamento_id"] = $_POST["slcTiposDesligamentos"];

switch($_POST["slcTiposDesligamentos"]){
    case 1:
        $motivos = $_POST["radMotivoDesligamentoAdm"];
    break;
    case 2:
        foreach($_POST["chkMotivosDesligamentoQualificado"] as $motivo)
        {
            $motivos .= $motivo . ", ";
        }
    break;
    case 3:
        $motivos = $_POST["radDesligamentoSolicitado"];
    break;
    case 4:
        $motivos = $_POST["radDesistencia"];
    break;
    case 5:
        $motivos = "";
    break;
    case 6:
        $motivos = $_POST["radTransferencia"];
    break;
}

$dados["desligamento_motivo"] = $motivos;
$dados["desligamento_sintese"] = $_POST["txtSintese"];
if (isset($_POST["tipo_encaminhamento_id"])) {
    $dados["tipo_encaminhamento_id"] = $_POST["tipo_encaminhamento_id"];
}
if (isset($_POST["tipo_encaminhamento_realizado_id"])) {
    if (is_array($_POST["tipo_encaminhamento_realizado_id"])) {
        $encaminhamentosRealizados = array_filter(array_map('trim', $_POST["tipo_encaminhamento_realizado_id"]), function ($id) {
            return $id !== '';
        });
        $dados["tipo_encaminhamento_realizado_id"] = !empty($encaminhamentosRealizados) ? implode(", ", $encaminhamentosRealizados) : null;
    } else {
        $dados["tipo_encaminhamento_realizado_id"] = $_POST["tipo_encaminhamento_realizado_id"];
    }
} else {
    $dados["tipo_encaminhamento_realizado_id"] = null;
}
if (isset($_POST["tipo_encaminhamento_realizado_outros_equipamentos"])) {
    $dados["tipo_encaminhamento_realizado_outros_equipamentos"] = $_POST["tipo_encaminhamento_realizado_outros_equipamentos"];
}
if (isset($_POST["tipo_encaminhamento_realizado_outro"])) {
    $dados["tipo_encaminhamento_realizado_outro"] = $_POST["tipo_encaminhamento_realizado_outro"];
}

foreach($_POST["chkImpactos"] as $impacto)
{
    $impactos .= $impacto . ", ";
}

$dados["desligamento_impactos"] = $impactos;

$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["data_desligamento"] = date("Y-m-d H:i:s");

$sistema = new Sistema();
//$sistema->debug=true;

/* novas perguntas */
$dados["acolhido_encaminhado_hub"] = isset($_POST["acolhido_encaminhado_hub"]) ? $_POST["acolhido_encaminhado_hub"] : null;
if ($dados["acolhido_encaminhado_hub"] === "1") {
    $dados["tipo_encaminhamento_hub_id"] = isset($_POST["tipo_encaminhamento_hub_id"]) ? $_POST["tipo_encaminhamento_hub_id"] : null;
}

/* fim novas perguntas */

$sistema->insert('rec_acolhidos_desligamentos',$dados);

$dadosUpdate['status'] = 2;
$sistema->update("rec_acolhidos_entradas",$dadosUpdate,"acolhido_entrada_id = " . $_POST['id'] . "");

header('Content-Type: application/json; charset=utf-8');
echo json_encode(
    array(
        "post" => $_POST,
        "dados" => $dados,
        "new_id" => $sistema->newID
    ),
    JSON_UNESCAPED_UNICODE
);
