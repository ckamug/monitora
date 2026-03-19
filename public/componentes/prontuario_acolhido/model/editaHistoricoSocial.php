<?php
session_start();
include __DIR__ . "/historicoSocialHelper.php";

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = isset($_POST["entrada_id"]) ? intval($_POST["entrada_id"]) : 0;
$entradaId = hsResolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0 && $id > 0) {
    $entradaId = hsBuscaEntradaPorHistoricoId($id);
}

if ($entradaId <= 0) {
    echo "Registro de Histórico Social não localizado para o acolhimento informado";
    exit;
}

$novoId = hsSalvaHistoricoSocialPorEntrada($entradaId);
echo $novoId;

