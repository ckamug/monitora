<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$campos = 'a.* , b.* , c.* , d.* , e.* , f.*';
$from = 'tbl_cidades a';
$innerJoin[] = 'left join rec_regioes_administrativas b on a.regiao_administrativa_id = b.regiao_administrativa_id';
$innerJoin[] = 'left join rec_regioes_governo c on a.regiao_governo_id = c.regiao_governo_id';
$innerJoin[] = 'left join rec_regioes_metropolitanas d on a.regiao_metropolitana_id = d.regiao_metropolitana_id';
$innerJoin[] = 'left join rec_aglomeracoes_urbanas e on a.aglomeracao_urbana_id = e.aglomeracao_urbana_id';
$innerJoin[] = 'left join rec_macroregioes f on a.macroregiao_id = f.macroregiao_id';

$where = "a.cidade_id = " . $_POST["id"];

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$result = $sistema->resultToJSON();

echo $result;