<?php
include "../../../../classes/sistema.php";
session_start();

$dados['senha_alterada'] = 0;

$sistema = new Sistema();
$sistema->update("rec_usuarios",$dados,"usuario_id=".$_POST['id']);