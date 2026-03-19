<?php
session_start();
include __DIR__ . "/sobreUsoHelper.php";

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = isset($_POST["entrada_id"]) ? intval($_POST["entrada_id"]) : 0;
$entradaId = suResolveAcolhidoEntradaId($entradaId);
$dadosJson = isset($_POST["dados_json"]) ? $_POST["dados_json"] : "";

if ($entradaId <= 0 && $id > 0) {
    $entradaId = suBuscaEntradaPorSobreUsoId($id);
}

if ($entradaId <= 0) {
    echo "Registro de Dados Sobre o Uso não localizado para o acolhimento informado";
    exit;
}

$novoId = suSalvaSobreUsoPorEntrada($entradaId, $dadosJson);
echo $novoId;
