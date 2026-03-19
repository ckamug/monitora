<?php
session_start();
include __DIR__ . "/historicoSocialHelper.php";

$entradaId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = hsResolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0) {
    echo "ID de acolhimento inválido para o Histórico Social";
    exit;
}

$novoId = hsSalvaHistoricoSocialPorEntrada($entradaId);
echo $novoId;

