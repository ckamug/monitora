<?php
include "../../../../classes/sistema.php";
session_start();

$id = $_POST["id"];

$sistema = new Sistema();
$campos = 'a.* , b.cargo_descricao';
$from = 'rec_executoras_responsaveis a';
$innerJoin[] = 'inner join rec_cargos b on a.cargo_id = b.cargo_id';

if($id<>""){
    $where = "executora_responsavel_status = 1 AND executora_id = " . base64_decode($id);
}
else{
    $where = "executora_responsavel_status = 1 AND executora_id = 0 AND usuario_id = " . base64_decode($_SESSION["usr"]);
}

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','b.cargo_id');
$result = $sistema->getResult();

if(count($result)>0){
    echo "<table class='table table-striped'>";
    echo "    <thead>";
    echo "        <tr>";
    echo "            <th scope='col'>Nome</th>";
    echo "            <th scope='col'>CPF</th>";
    echo "            <th scope='col'>Cargo</th>";
    echo "            <th scope='col'></th>";
    echo "        </tr>";
    echo "    </thead>";

    for($i=0;$i<count($result);$i++){

        echo "<tr>";
        echo "    <td>".utf8_encode($result[$i]["executora_responsavel_nome"])."</td>";
        echo "    <td>".$result[$i]["executora_responsavel_cpf"]."</td>";
        echo "    <td>".utf8_encode($result[$i]["cargo_descricao"])."</td>";
        echo "    <td><button type='button' class='btn btn-danger' onclick='excluiResponsavel(".$result[$i]["executora_responsavel_id"].")'>Excluir</button></td>";
        echo "</tr>";

    }

    echo "</table>";
}