<?php
include "../../../../classes/sistema.php";
session_start();

if (intval($_SESSION["pf"]) != 7) {
    exit;
}

$executoraId = intval($_POST["id"]);
if ($executoraId <= 0) {
    exit;
}

$sistema = new Sistema();
$campos = 'a.* , b.*';
$from = 'rec_executoras a';
$innerJoin[] = 'inner join tbl_cidades b on a.cidade_id = b.cidade_id';
$where = 'a.executora_id = ' . $executoraId;

$sistema->innerJoin($campos, $from, $innerJoin, $where, '', '');
$result = $sistema->getResult();

if (!is_array($result) || count($result) == 0) {
    exit;
}

$camposSolicitacoes = 'count(a.solicitacao_vaga_id) as total';
$fromSolicitacoes = 'rec_solicitacoes_vagas a';
$innerJoinSolicitacoes = array();
$innerJoinSolicitacoes[] = 'left join (
    select
        x.acolhido_id,
        y.data_desligamento
    from rec_acolhidos_entradas x
    inner join rec_acolhidos_desligamentos y on y.acolhido_entrada_id = x.acolhido_entrada_id
    where y.acolhido_desligamento_id = (
        select y2.acolhido_desligamento_id
        from rec_acolhidos_entradas x2
        inner join rec_acolhidos_desligamentos y2 on y2.acolhido_entrada_id = x2.acolhido_entrada_id
        where x2.acolhido_id = x.acolhido_id
        order by y2.data_desligamento desc, y2.acolhido_desligamento_id desc
        limit 1
    )
) c on c.acolhido_id = a.acolhido_id';
$whereSolicitacoes = 'a.solicitacao_vaga_id = (
        select a2.solicitacao_vaga_id
        from rec_solicitacoes_vagas a2
        where a2.acolhido_id = a.acolhido_id
          and a2.status_registro = 1
        order by a2.data_cadastro desc, a2.solicitacao_vaga_id desc
        limit 1
    )
    and a.status_vaga_id <> 4
    and a.status_registro = 1
    and a.executora_id = ' . $result[0]["executora_id"] . '
    and (c.data_desligamento is null or a.data_cadastro > c.data_desligamento)';
$sistema->innerJoin($camposSolicitacoes, $fromSolicitacoes, $innerJoinSolicitacoes, $whereSolicitacoes, '', '');
$resSolicitacoes = $sistema->getResult();

$totalOcupadas = 0;
if (is_array($resSolicitacoes) && count($resSolicitacoes) > 0) {
    $totalOcupadas = intval($resSolicitacoes[0]["total"]);
}

$vagasDisponiveis = intval($result[0]["executora_vagas"]) - $totalOcupadas;

if ($vagasDisponiveis <= 0) {
    $qtdVagas = 0;
    $estilo = "text-danger";
    if ($vagasDisponiveis == -1) {
        $aviso = '<span>(' . ($vagasDisponiveis * -1) . ' acolhido em fila de espera)</span>';
    } else {
        $aviso = '<span>(' . ($vagasDisponiveis * -1) . ' acolhidos em fila de espera)</span>';
    }
} else {
    $qtdVagas = $vagasDisponiveis;
    $estilo = "text-success";
    $aviso = "";
}

echo '<div class="col-md-12 mt-2 ' . $estilo . '"><strong>Vagas disponiveis:</strong> ' . $qtdVagas . " " . $aviso;
echo '<div class="col-md-12 text-secondary mt-2"><strong>Genero:</strong>  ' . substr($result[0]["executora_generos"], 0, -2) . '</div>';
echo '<div class="col-md-12 text-secondary mt-2 mb-1"><strong>Endereco:</strong> ' . utf8_encode($result[0]["executora_endereco"]) . ', ' . $result[0]["executora_numero"] . ' - ' . utf8_encode($result[0]["executora_bairro"]) . ' - ' . utf8_encode($result[0]["cidade_descricao"]) . '</div>';
