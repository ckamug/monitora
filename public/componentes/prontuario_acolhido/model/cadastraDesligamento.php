<?php
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

foreach($_POST["chkImpactos"] as $impacto)
{
    $impactos .= $impacto . ", ";
}

$dados["desligamento_impactos"] = $impactos;

$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["data_desligamento"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_acolhidos_desligamentos',$dados);

$dadosUpdate['status'] = 2;
$sistema->update("rec_acolhidos_entradas",$dadosUpdate,"acolhido_entrada_id = " . $_POST['id'] . "");