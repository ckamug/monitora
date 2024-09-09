<?php

include "../../../../classes/sistema.php";

$sistema = new Sistema();
$sistema->select("executoras_temp",'*');
$result=$sistema->getResult();

for($i=0;$i<count($result);$i++){

    $dados["executora_valor_previsto_rh"]=$result[$i]["rh"];
    $dados["executora_valor_previsto_custeio"]=$result[$i]["custeio"];
    $dados["executora_valor_previsto_terceiros"]=$result[$i]["terceiros"];

    // $sistema->update("rec_executoras",$dados,"executora_id=".$result[$i]["executora_id"]);

    // echo $result[$i]["executora_id"] . "<br>";

}