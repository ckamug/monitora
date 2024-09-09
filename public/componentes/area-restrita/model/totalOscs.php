<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$sistema->select("rec_executoras","COUNT(executora_id) as total","executora_status = 1","","");
$result = $sistema->getResult();

echo $result[0]["total"];