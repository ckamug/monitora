<?php
session_start();
include __DIR__ . "/saudeGeralHelper.php";

$entradaId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = sgResolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0) {
    echo "ID de acolhimento inválido para a Saúde Geral";
    exit;
}

$novoId = sgSalvaSaudeGeralPorEntrada($entradaId);
echo $novoId;

