<?php
include "../../../../classes/sistema.php";
session_start();

$dados["executora_responsavel_status"] = 0;

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->update('rec_executoras_responsaveis',$dados,'executora_responsavel_id='.$_POST["id"]);