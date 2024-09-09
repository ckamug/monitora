<?php
include "../../../../classes/sistema.php";
session_start();

$id = $_POST["id"];

$sistema = new Sistema();
$campos = 'a.* , b.*';
$from = 'rec_acolhidos_dados_sensiveis a';
$innerJoin[] = 'left join rec_usuarios b on a.usuario_id = b.usuario_id';
$where = 'a.acolhido_entrada_id = ' . $id;

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$result = $sistema->resultToJSON();

echo $result;