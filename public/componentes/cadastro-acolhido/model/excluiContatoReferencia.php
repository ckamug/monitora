<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->delete('rec_acolhidos_referencias','referencia_id='.$_POST['id']);