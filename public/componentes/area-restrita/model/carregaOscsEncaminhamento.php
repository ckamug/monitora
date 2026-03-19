<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";
session_start();

if (intval($_SESSION["pf"]) != 7) {
    exit;
}

$municipioId = intval($_POST["municipio_id"]);
$servicoId = intval($_POST["servico_id"]);
$genero = $_POST["genero"];

$sistema = new Sistema();

if ($municipioId == 322) {
    $where = 'executora_servicos_id like "%' . $servicoId . '%" AND executora_generos like "%' . $genero . '%" AND (executora_id = 35 OR executora_id = 34 OR executora_id = 24 OR executora_id = 38 OR executora_id = 14 OR executora_id = 40 OR executora_id = 41 OR executora_id = 42 OR executora_id = 6 OR executora_id = 75 OR executora_id = 48 OR executora_id = 44 OR executora_id = 32 OR executora_id = 17 OR executora_id = 22 OR executora_id = 16 OR executora_id = 67)';
    $select = new select('recomeco', 'rec_executoras', 'slcOscsEncaminhamento', 'executora_id', 'executora_nome_fantasia', 'executora_nome_fantasia', '', 'carregaDetalhesOscEncaminhamento(this.value)', $where, '');
    echo $select;
    exit;
}

$campos = 'a.cidade_id , b.regiao_administrativa_id , b.macroregiao_id';
$from = 'rec_municipios a';
$innerJoin[] = 'inner join tbl_cidades b on a.cidade_id = b.cidade_id';
$where = 'a.municipio_id = ' . $municipioId;

$sistema->innerJoin($campos, $from, $innerJoin, $where, '', '');
$regadm = $sistema->getResult();

if (!is_array($regadm) || count($regadm) == 0) {
    echo "<select class='form-select' id='slcOscsEncaminhamento' disabled><option value='0'>Sem OSC disponivel</option></select>";
    exit;
}

if ($genero == "Feminino") {
    $sistema->select("tbl_cidades", "cidade_id", "macroregiao_id = " . $regadm[0]["macroregiao_id"]);
} else {
    $sistema->select("tbl_cidades", "cidade_id", "regiao_administrativa_id = " . $regadm[0]["regiao_administrativa_id"]);
}

$res = $sistema->getResult();
$cidadesArray = array();

if (count($res) > 0) {
    for ($i = 0; $i < count($res); $i++) {
        $cidadesArray[] = $res[$i]["cidade_id"];
    }

    $whereOsc = 'executora_servicos_id like "%' . $servicoId . '%" AND executora_generos like "%' . $genero . '%" AND cidade_id IN (' . implode(',', array_map('intval', $cidadesArray)) . ')';
    $select = new select('recomeco', 'rec_executoras', 'slcOscsEncaminhamento', 'executora_id', 'executora_nome_fantasia', 'executora_nome_fantasia', '', 'carregaDetalhesOscEncaminhamento(this.value)', $whereOsc, '');
    echo $select;
} else {
    echo "<select class='form-select' id='slcOscsEncaminhamento' disabled><option value='0'>Sem OSC disponivel</option></select>";
}
