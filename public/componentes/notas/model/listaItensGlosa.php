<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
if(base64_decode($_SESSION["usr"])==1){
    //$sistema->debug=true;
}
$sistema->select("rec_notas_motivos_glosa","*","valor_glosa_parcial > '0,00' AND nota_fiscal_id = " . $_POST["id"]);
$result = $sistema->getResult();

if(count($result)>0){

    echo "<h3 class='text-danger'>Glosa Parcial</h3>";
    echo "<table class='table table-danger'>";
    echo "    <thead>";
    echo "        <tr class='table-danger'>";
    echo "        <th scope='col'>Data da Glosa</th>";
    echo "        <th scope='col'>Item/Motivo</th>";
    echo "        <th scope='col'>Valor glosado (R$)</th>";
    echo "        </tr>";
    echo "    </thead>";

    for($i=0;$i<count($result);$i++){

        echo "<tr class='table-danger'>";
        echo "    <td>".$sistema->convertData($result[$i]["data_cadastro"])."</td>";
        echo "    <td>".utf8_encode($result[$i]["motivo_glosa_descricao"])."</td>";
        echo "    <td>R$ ".$result[$i]["valor_glosa_parcial"]."</td>";
        echo "</tr>";

    }

    echo "</table>";
}
else{
    echo 0;
}