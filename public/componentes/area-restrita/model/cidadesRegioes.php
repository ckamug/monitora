<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$sistema->select("cidades","*","","","");
$result = $sistema->getResult();

for($i=0;$i<count($result);$i++){
    
    $dados["macroregiao_id"] = intval($result[$i]["macroregiao_id"]);

    //$sistema->debug=true;
    $sistema->update("tbl_cidades",$dados,"cidade_id = " . $result[$i]["cidade_id"]);

    echo $i . "<br>";
}