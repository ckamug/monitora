<?php
include "../../../../classes/sistema.php";

$cpf = $_POST['cpf'];

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select('rec_usuarios','usuario_id','usuario_cpf = "' . $cpf . '" AND senha_alterada = 1');
$result = $sistema->getResult();
echo count($result);