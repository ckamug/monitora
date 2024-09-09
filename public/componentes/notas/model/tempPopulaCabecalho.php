<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;

$sistema->select("temp","*");
$result = $sistema->getResult();

for($i=0;$i<count($result);$i++){

    $dados["recursos_humanos_previsto"] = $result[$i]["rh"];
    $dados["custeio_previsto"] = $result[$i]["custeio"];
    $dados["servicos_terceiros_previsto"] = $result[$i]["terceiro"];
        
    //$sistema->debug=true;
    //$sistema->update('rec_cabecalhos',$dados,'cabecalho_mes_referencia = "2023-04" AND executora_id = ' . $result[$i]["osc"]);

    unset($dados);

    echo $i . "<br>";
}