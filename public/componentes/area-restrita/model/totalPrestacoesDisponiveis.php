<?php
include "../../../../classes/sistema.php";
session_start();

$_SESSION["consultaPrestacaoId"] = 0;
$_SESSION["consultaPrestacaoTipo"] = 'celebrante';

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_prestacoes","COUNT(prestacao_id) as total",'prestacao_disponibilizada = 1 AND prestacao_status = 0','');

$result = $sistema->getResult();

echo $result[0]["total"];