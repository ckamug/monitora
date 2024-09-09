<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$campos = 'a.acolhido_nome_completo';
$from = 'rec_acolhidos a';
$innerJoin[] = 'left join rec_solicitacoes_vagas b on a.acolhido_id = b.acolhido_id';
$innerJoin[] = 'left join rec_status_vaga c on b.status_vaga_id = c.status_vaga_id';

$where = 'a.acolhido_id = ' . base64_decode($_POST["id"]);

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$result = $sistema->resultToJSON();

echo $result;