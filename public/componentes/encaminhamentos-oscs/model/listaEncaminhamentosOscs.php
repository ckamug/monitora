<?php
include "../../../../classes/sistema.php";
session_start();

if (!isset($_SESSION["pf"]) || !in_array(intval($_SESSION["pf"]), array(1, 7), true)) {
    echo "<div class='alert alert-warning mt-3'>Perfil sem permissao para visualizar encaminhamentos.</div>";
    exit;
}

$sistema = new Sistema();

$campos = 'a.acolhido_nome_completo,
           a.acolhido_nis,
           a.acolhido_cpf,
           c.data_cadastro as data_encaminhamento,
           c.status_vaga_id,
           d.executora_razao_social,
           e.status_vaga_descricao';
$from = 'rec_acolhidos a';
$innerJoin = array();
$innerJoin[] = 'left join rec_solicitacoes_vagas c on c.solicitacao_vaga_id = (
    select c2.solicitacao_vaga_id
    from rec_solicitacoes_vagas c2
    where c2.acolhido_id = a.acolhido_id
      and c2.status_registro = 1
    order by c2.data_cadastro desc, c2.solicitacao_vaga_id desc
    limit 1
)';
$innerJoin[] = 'left join rec_executoras d on c.executora_id = d.executora_id';
$innerJoin[] = 'left join rec_status_vaga e on c.status_vaga_id = e.status_vaga_id';
$innerJoin[] = 'left join (
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
) f on f.acolhido_id = a.acolhido_id';
$where = 'c.status_registro = 1
          and c.executora_id > 0
          and (f.data_desligamento is null or c.data_cadastro > f.data_desligamento)';

$sistema->innerJoin($campos, $from, $innerJoin, $where, '', 'a.acolhido_nome_completo');
$result = $sistema->getResult();

if (!is_array($result) || count($result) == 0) {
    echo "<div class='alert alert-info mt-3'>Nenhum encaminhamento para OSC encontrado.</div>";
    exit;
}

echo "<table id='tblEncaminhamentosOscs' class='table datatable table-hover table-striped'>";
echo "    <thead>";
echo "        <tr>";
echo "            <th scope='col'>Nome Completo</th>";
echo "            <th scope='col'>NIS</th>";
echo "            <th scope='col'>CPF</th>";
echo "            <th scope='col'>Data de Encaminhamento</th>";
echo "            <th scope='col'>OSC</th>";
echo "            <th scope='col'>Status</th>";
echo "        </tr>";
echo "    </thead>";
echo "    <tbody>";

for ($i = 0; $i < count($result); $i++) {
    $nomeAcolhido = utf8_encode($result[$i]["acolhido_nome_completo"]);
    $nis = trim($result[$i]["acolhido_nis"]) == "" ? "-" : $result[$i]["acolhido_nis"];
    $cpf = trim($result[$i]["acolhido_cpf"]) == "" ? "-" : $result[$i]["acolhido_cpf"];
    $data = $sistema->convertData(substr($result[$i]["data_encaminhamento"], 0, 10)) . " " . substr($result[$i]["data_encaminhamento"], 11, 8);
    $nomeOsc = trim($result[$i]["executora_razao_social"]) == "" ? "-" : utf8_encode($result[$i]["executora_razao_social"]);
    $statusDesc = trim($result[$i]["status_vaga_descricao"]) == "" ? "-" : utf8_encode($result[$i]["status_vaga_descricao"]);

    switch($result[$i]["status_vaga_id"]){
        case '1':
            $status = "<span class='badge bg-warning'>" . $statusDesc . "</span>";
        break;
        case '2':
            $status = "<span class='badge bg-primary'>" . $statusDesc . "</span>";
        break;
        case '3':
            $status = "<span class='badge bg-success'>" . $statusDesc . "</span>";
        break;
        case '4':
            $status = "<span class='badge bg-danger'>" . $statusDesc . "</span>";
        break;
        case '5':
            $status = "<span class='badge bg-info'>" . $statusDesc . "</span>";
        break;
        default:
            $status = "<span class='badge bg-secondary'>" . $statusDesc . "</span>";
        break;
    }

    echo "        <tr>";
    echo "            <td>" . $nomeAcolhido . "</td>";
    echo "            <td>" . $nis . "</td>";
    echo "            <td>" . $cpf . "</td>";
    echo "            <td>" . $data . "</td>";
    echo "            <td>" . $nomeOsc . "</td>";
    echo "            <td>" . $status . "</td>";
    echo "        </tr>";
}

echo "    </tbody>";
echo "</table>";
