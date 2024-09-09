<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug = true;
$sistema->select("rec_celebrantes","*","celebrante_id = " . base64_decode($_POST["id"]) , "" , "");
$result = $sistema->resultToJSON();	

echo $result;