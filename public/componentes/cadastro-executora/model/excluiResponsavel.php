<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->delete('rec_executoras_responsaveis','executora_responsavel_id='.$_POST["id"]);