<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dadosUpdate["usuario_id_finalizou"] = intval(base64_decode($_SESSION["usr"]));
$dadosUpdate["data_finalizou"] = date("Y-m-d H:i:s");
$dadosUpdate["prestacao_status"] = intval(1);

$sistema->update('rec_prestacoes',$dadosUpdate,'prestacao_id = ' . base64_decode($_POST["prestacao"]));

echo 0;