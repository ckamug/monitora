<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$sistema->select("rec_municipios","COUNT(municipio_id) as total","municipio_status = 1","","");
$result = $sistema->getResult();

echo $result[0]["total"];