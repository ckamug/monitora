<?php
session_start();
include __DIR__ . "/avaliacaoPsicossocialHelper.php";

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = isset($_POST["entrada_id"]) ? intval($_POST["entrada_id"]) : 0;
$entradaId = apResolveAcolhidoEntradaId($entradaId);

if ($entradaId <= 0 && $id > 0) {
    $entradaId = apBuscaEntradaPorAvaliacaoId($id);
}

if ($entradaId <= 0) {
    echo "Registro de Avaliação Psicossocial não localizado para o acolhimento informado";
    exit;
}

$novoId = apSalvaAvaliacaoPorEntrada($entradaId);
echo $novoId;
