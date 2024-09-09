<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dadosUpdate["prestacao_disponibilizada"] = intval(0);
$dadosUpdate["prestacao_pre_finalizada"] = intval(0);
$dadosUpdate["prestacao_status"] = intval(0);

$sistema->update('rec_prestacoes',$dadosUpdate,'prestacao_id = ' . base64_decode($_POST["prestacao"]));

echo 0;