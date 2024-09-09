<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$sistema->select("rec_acolhidos","COUNT(acolhido_id) as total","","","");
$result = $sistema->getResult();

echo $result[0]["total"];