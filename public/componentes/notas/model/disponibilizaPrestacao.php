<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

if(base64_decode($_POST["prestacao"])!=18 OR base64_decode($_POST["prestacao"])!=22){

    $dadosUpdate["usuario_id_disponibilizou"] = intval(base64_decode($_SESSION["usr"]));
    $dadosUpdate["data_disponibilizou"] = date("Y-m-d H:i:s");
    $dadosUpdate["prestacao_disponibilizada"] = intval(1);

    $sistema->update('rec_prestacoes',$dadosUpdate,'prestacao_id = ' . base64_decode($_POST["prestacao"]));

}
echo 0;