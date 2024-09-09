<?php
include "../../../../classes/sistema.php";
session_start();

if($_SESSION["pf"]==4){
    $sistema = new Sistema();
    //$sistema->debug=true;
    $campos = 'a.executora_id , b.usuario_id';
    $from = 'rec_prestacoes a';
    $innerJoin[] = 'inner join rec_usuarios_vinculos b on a.executora_id = b.executora_id';

    $where = "a.prestacao_id = " . base64_decode($_POST["prestacao"]) . " AND b.usuario_id = " . base64_decode($_SESSION["usr"]) . " AND a.executora_id = " . $_SESSION["pfv"];

    $sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
    $result = $sistema->getResult();

    echo count($result);
}
else{
    echo 1;
}