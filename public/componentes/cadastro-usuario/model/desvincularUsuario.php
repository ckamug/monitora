<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$sistema->delete("rec_usuarios_vinculos","usuario_vinculo_id=".$_POST['id']);