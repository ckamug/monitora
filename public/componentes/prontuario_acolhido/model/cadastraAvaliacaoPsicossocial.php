<?php
session_start();
include __DIR__ . "/avaliacaoPsicossocialHelper.php";

$entradaId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = apResolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0) {
    echo "ID de acolhimento inválido para a Avaliação Psicossocial";
    exit;
}

$novoId = apSalvaAvaliacaoPorEntrada($entradaId);
echo $novoId;
