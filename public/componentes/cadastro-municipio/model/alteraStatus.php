<?php
include "../../../../classes/sistema.php";
session_start();

$dados["municipio_status"] = $_POST["status"];

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->update('rec_municipios',$dados,'municipio_id = ' . base64_decode($_POST["id"]));