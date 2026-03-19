<?php
include "../../../../classes/sistema.php";
session_start();

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

if ($id <= 0) {
    exit;
}

$sistema = new Sistema();
$from = "rec_prontuario_medicacoes a";
$innerJoin = array();
$innerJoin[] = "left join rec_usuarios b on a.usuario_id = b.usuario_id";
$innerJoin[] = "left join rec_tipos_registro c on b.tipo_registro_id = c.tipo_registro_id";
$where = "a.acolhido_entrada_id = " . $id;

$sistema->innerJoin(
    "a.*, b.usuario_nome, b.numero_registro, c.tipo_registro_descricao",
    $from,
    $innerJoin,
    $where,
    "",
    "a.data_medicacao DESC, a.prontuario_medicacao_id DESC"
);
$result = $sistema->getResult();

if (!$result || count($result) == 0) {
    echo '<div class="col-11 mt-3 ms-5 small text-muted">Nenhuma medicação registrada.</div>';
    exit;
}

for ($i = 0; $i < count($result); $i++) {
    $dataMedicacao = isset($result[$i]["data_medicacao"]) ? $result[$i]["data_medicacao"] : "";
    $horaCadastro = isset($result[$i]["data_cadastro"]) ? substr($result[$i]["data_cadastro"], 11, 5) : "";

    $nomeMedicacao = isset($result[$i]["nome_medicacao"]) ? utf8_encode($result[$i]["nome_medicacao"]) : "";
    $dosagem = isset($result[$i]["dosagem"]) ? utf8_encode($result[$i]["dosagem"]) : "";
    $prescricao = isset($result[$i]["prescricao"]) ? nl2br(utf8_encode($result[$i]["prescricao"])) : "";
    $tempoUso = isset($result[$i]["tempo_uso"]) ? utf8_encode($result[$i]["tempo_uso"]) : "";
    $unidadeSaude = isset($result[$i]["unidade_saude_prescreveu"]) ? utf8_encode($result[$i]["unidade_saude_prescreveu"]) : "";
    $observacoes = isset($result[$i]["observacoes"]) ? nl2br(utf8_encode($result[$i]["observacoes"])) : "";

    $usuarioNome = isset($result[$i]["usuario_nome"]) ? utf8_encode($result[$i]["usuario_nome"]) : "";
    $tipoRegistro = isset($result[$i]["tipo_registro_descricao"]) ? $result[$i]["tipo_registro_descricao"] : "";
    $numeroRegistro = isset($result[$i]["numero_registro"]) ? $result[$i]["numero_registro"] : "";

    echo '<div class="alert bg-warning bg-opacity-10 col-11 mt-3 border ms-5" role="alert">';
    echo '    <h5 class="alert-heading">' . $sistema->convertData($dataMedicacao) . (($horaCadastro != "") ? ' às ' . $horaCadastro . 'hs' : '') . '</h5>';
    echo '    <div><strong>Nome da medicação:</strong> ' . $nomeMedicacao . '</div>';
    echo '    <div><strong>Dosagem:</strong> ' . $dosagem . '</div>';
    echo '    <div><strong>Prescrição:</strong> ' . $prescricao . '</div>';
    echo '    <div><strong>Tempo de uso:</strong> ' . $tempoUso . '</div>';
    echo '    <div><strong>Unidade de saúde que prescreveu:</strong> ' . $unidadeSaude . '</div>';
    echo '    <div><strong>Observações:</strong> ' . $observacoes . '</div>';
    echo '    <hr>';
    echo '    <div class="row">';
    echo '        <div class="col-4">' . $usuarioNome . '</div>';
    echo '        <div class="col-4">' . $tipoRegistro . ' ' . $numeroRegistro . '</div>';
    echo '    </div>';
    echo '</div>';
}
