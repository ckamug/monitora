<?php
include "../../../../classes/sistema.php";
session_start();

if(is_numeric($_POST["id"])){
    $id = $_POST["id"];
}
else{
    $id = base64_decode($_POST["id"]);
}

$sistema = new Sistema();
//$sistema->debug = true;
$sistema->select("rec_usuarios","*","usuario_id = " . $id , "" , "");
$result = $sistema->resultToJSON();	

echo $result;