<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("acolhidos_hub_temp","*","");
$result = $sistema->getResult();

for($i=0;$i<count($result);$i++){

    $dados["acolhido_hub_id"] = $result[$i]["acolhido_id"];
    $dados["acolhido_nome"] = str_replace("'","´",utf8_encode($result[$i]["nome"]));
    $dados["data_nascimento"] = $result[$i]["nascimento"];
    $dados["data_entrada"] = $result[$i]["entrada"];
    $dados["data_saida"] = $result[$i]["saida"];
    $dados["tipo_desligamento"] = utf8_encode($result[$i]["status"]);
    $dados["executora_id"] = $result[$i]["cnpj"];
    $dados["data_cadastro"] = date("Y-m-d H:i:s");
    $dados["data_alteracao"] = date("Y-m-d H:i:s");
    

    //$sistema->insert("rec_acolhidos_hub",$dados);

    echo utf8_encode($result[$i]["nome"]) . "<br>";

}