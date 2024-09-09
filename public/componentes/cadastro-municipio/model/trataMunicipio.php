<?php
include "../../../../classes/sistema.php";

$sistema = new Sistema();
$sistema->select('rec_municipios' , 'municipio_id , cidade_id','','','cidade_id DESC');
$retorno = $sistema->getResult();

for($i=0;$i<count($retorno);$i++){

    //$sistema->debug=true;
    $sistema->select('tbl_cidades' , 'cidade_id , cidade_descricao','cidade_descricao = "'.utf8_encode($retorno[$i]["cidade_id"]).'" AND estado_id = 25','');
    $cidade = $sistema->getResult();

        if(count($cidade)>0){

            $dados["cidade_id"] = $cidade[0]["cidade_id"];

            //$sistema->debug=true;
            $sistema->update('rec_municipios',$dados,'municipio_id = ' . $retorno[$i]["municipio_id"]);

        }

    echo utf8_encode($retorno[$i]["cidade_id"]) . " - " . count($cidade) . "<br>";

}