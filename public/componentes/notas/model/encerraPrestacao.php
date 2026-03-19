<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dadosUpdate["usuario_id_pre_finalizou"] = intval(base64_decode($_SESSION["usr"]));
$dadosUpdate["data_pre_finalizou"] = date("Y-m-d H:i:s");
$dadosUpdate["prestacao_pre_finalizada"] = intval(1);

$sistema->update('rec_prestacoes',$dadosUpdate,'prestacao_id = ' . base64_decode($_POST["prestacao"]));

echo 0;