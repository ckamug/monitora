<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$sistema->select("rec_usuarios","usuario_id",'usuario_cpf = "' . $_POST["cpf"] . '"');
$result = $sistema->getResult();

echo count($result);