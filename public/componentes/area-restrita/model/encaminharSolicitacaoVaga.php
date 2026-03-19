<?php
include_once "../../../../configuracoes.php";
include "../../../../classes/sistema.php";
session_start();

if (intval($_SESSION["pf"]) != 7) {
    echo "Perfil sem permissao para encaminhar solicitacoes.";
    exit;
}

$solicitacaoId = intval($_POST["solicitacao_id"]);
$executoraId = intval($_POST["executora_id"]);

if ($solicitacaoId <= 0 || $executoraId <= 0) {
    echo "Dados invalidos para encaminhamento.";
    exit;
}

$sistema = new Sistema();
$sistema->select(
    "rec_solicitacoes_vagas",
    "*",
    "solicitacao_vaga_id = " . $solicitacaoId . " AND status_registro = 1 AND status_vaga_id = 5"
);
$result = $sistema->getResult();

if (!is_array($result) || count($result) == 0) {
    echo "Solicitacao nao encontrada ou fora do status de encaminhamento.";
    exit;
}

$dadosUpdate['status_registro'] = 0;
$sistema->update("rec_solicitacoes_vagas", $dadosUpdate, "solicitacao_vaga_id = " . $solicitacaoId);

$dados["acolhido_id"] = $result[0]["acolhido_id"];
$dados["executora_id"] = $executoraId;
$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["municipio_id"] = $result[0]["municipio_id"];
$dados["servico_id"] = $result[0]["servico_id"];
$dados["genero_solicitado"] = $result[0]["genero_solicitado"];
$dados["status_vaga_id"] = 1;
$dados["data_cadastro"] = date("Y-m-d H:i:s");

$sistema->insert("rec_solicitacoes_vagas", $dados);

echo "Solicitacao encaminhada para OSC executora.";
