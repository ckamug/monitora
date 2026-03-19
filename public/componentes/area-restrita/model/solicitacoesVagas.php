<?php
include "../../../../classes/sistema.php";
session_start();

$perfil = intval($_SESSION['pf']);

if ($perfil != 4 && $perfil != 7) {
    echo "<script>$('#boxTabelaSolicitacoesVagas').addClass('d-none');</script>";
    exit;
}

$sistema = new Sistema();

$campos = 'a.acolhido_nome_completo , a.acolhido_nis , a.acolhido_cpf , a.acolhido_endereco_fixo , b.solicitacao_vaga_id , b.status_vaga_id , b.data_cadastro , b.municipio_id , b.servico_id , b.genero_solicitado , c.municipio_orgao_publico , d.servico_descricao';
$from = 'rec_acolhidos a';
$innerJoin[] = 'inner join rec_solicitacoes_vagas b on a.acolhido_id = b.acolhido_id';
$innerJoin[] = 'inner join rec_municipios c on b.municipio_id = c.municipio_id';
$innerJoin[] = 'left join rec_servicos d on b.servico_id = d.servico_id';

if ($perfil == 4) {
    $where = 'b.status_registro = 1 AND b.executora_id = ' . $_SESSION['pfv'] . ' AND b.status_vaga_id = 1';
} else {
    $where = 'b.status_registro = 1 AND b.status_vaga_id = 5';
}

$sistema->innerJoin($campos, $from, $innerJoin, $where, '', 'a.acolhido_endereco_fixo , b.data_cadastro');
$result = $sistema->getResult();

if (!is_array($result) || count($result) == 0) {
    echo "<script>$('#boxTabelaSolicitacoesVagas').addClass('d-none');</script>";
    exit;
}

echo "<table class='table'>";
echo "<thead>";
echo "<tr>";
echo "<th scope='col'>Nome do Acolhido</th>";
echo "<th scope='col'>NIS</th>";
echo "<th scope='col'>CPF</th>";
echo "<th scope='col'>Porta de Entrada</th>";

if ($perfil == 7) {
    echo "<th scope='col'>Servico</th>";
    echo "<th scope='col'>Genero</th>";
}

echo "<th scope='col'>Data da Solicitacao</th>";
echo "<th scope='col'></th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

for ($i = 0; $i < count($result); $i++) {
    $aviso = "";
    $classe = "";

    if ($result[$i]["acolhido_endereco_fixo"] == 'NAO') {
        $aviso = "<span class='text-danger' style='font-size:12px;'>(Situacao de rua)</span>";
        $classe = "class='table-warning'";
    }

    echo "<tr " . $classe . ">";
    $nis = trim($result[$i]["acolhido_nis"]) == "" ? "-" : $result[$i]["acolhido_nis"];
    $cpf = trim($result[$i]["acolhido_cpf"]) == "" ? "-" : $result[$i]["acolhido_cpf"];
    echo "<td>" . utf8_encode($result[$i]["acolhido_nome_completo"]) . " " . $aviso . "</td>";
    echo "<td>" . $nis . "</td>";
    echo "<td>" . $cpf . "</td>";
    echo "<td>" . utf8_encode($result[$i]["municipio_orgao_publico"]) . "</td>";

    if ($perfil == 7) {
        $servicoDesc = $result[$i]["servico_descricao"] == "" ? "-" : utf8_encode($result[$i]["servico_descricao"]);
        $generoSolicitado = $result[$i]["genero_solicitado"] == "" ? "-" : $result[$i]["genero_solicitado"];
        echo "<td>" . $servicoDesc . "</td>";
        echo "<td>" . $generoSolicitado . "</td>";
    }

    echo "<td width='200px;'>" . $sistema->convertData($result[$i]["data_cadastro"]) . " " . substr($result[$i]["data_cadastro"], 11, 8) . "</td>";

    if ($perfil == 4) {
        echo "<td width='15%' align='right'><button type='button' class='btn btn-success' onclick='pergunta(1 , 1 , " . $result[$i]["solicitacao_vaga_id"] . ")'>Reservar vaga</button> <button type='button' class='btn btn-danger' onclick='pergunta(1 , 0 , " . $result[$i]["solicitacao_vaga_id"] . ")'>Negar</button></td>";
    } else {
        $generoJs = addslashes($result[$i]["genero_solicitado"]);
        echo "<td width='15%' align='right'><button type='button' class='btn btn-primary' onclick='abreEncaminhamento(" . $result[$i]["solicitacao_vaga_id"] . "," . $result[$i]["municipio_id"] . "," . intval($result[$i]["servico_id"]) . ",\"" . $generoJs . "\")'>Encaminhar para OSC</button></td>";
    }

    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
