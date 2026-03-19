<?php
session_start();
include __DIR__ . "/sobreUsoHelper.php";

$entradaId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = suResolveAcolhidoEntradaId($entradaId);
$dadosJson = isset($_POST["dados_json"]) ? $_POST["dados_json"] : "";

if ($entradaId <= 0) {
    echo "ID de acolhimento inválido para Dados Sobre o Uso";
    exit;
}

$novoId = suSalvaSobreUsoPorEntrada($entradaId, $dadosJson);
echo $novoId;
