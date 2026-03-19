<?php
session_start();
include __DIR__ . "/saudeGeralHelper.php";

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = isset($_POST["entrada_id"]) ? intval($_POST["entrada_id"]) : 0;
$entradaId = sgResolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0 && $id > 0) {
    $entradaId = sgBuscaEntradaPorSaudeGeralId($id);
}

if ($entradaId <= 0) {
    echo "Registro de Saúde Geral não localizado para o acolhimento informado";
    exit;
}

$novoId = sgSalvaSaudeGeralPorEntrada($entradaId);
echo $novoId;

