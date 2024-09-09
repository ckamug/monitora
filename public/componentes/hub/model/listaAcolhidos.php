<?php
include "../../../../classes/sistema.php";
session_start();


$sistema = new Sistema();
$campos = 'a.* , b.*';
$from = 'rec_acolhidos_hub a';
$innerJoin[] = 'left join rec_executoras b on a.executora_id = b.executora_id';

if($_SESSION["pf"]==1){
    $where = "";
}
else{
    $where .= "a.executora_id = " . $_SESSION["pfv"];
}

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.acolhido_nome');
$result = $sistema->getResult();

echo "<table id='tblAcolhidos' class='table datatable table-hover table-striped'>";
echo "    <thead>";
echo "        <tr>";
echo "        <th scope='col'>Nome Completo</th>";
echo "        <th scope='col'>Data de Nascimento</th>";
echo "        <th scope='col'>Data de Entrada</th>";
echo "        <th scope='col'>Data de Saída</th>";
echo "        <th scope='col'>Tipo de Alta</th>";
if($_SESSION["pf"]==1){
    echo "        <th scope='col'>Executora</th>";
}
echo "        <th scope='col'></th>";
echo "        </tr>";
echo "    </thead>";

for($i=0;$i<count($result);$i++){

    echo "<tr>";
    echo "    <td>".utf8_encode($result[$i]["acolhido_nome"])."</td>";
    echo "    <td>".$sistema->convertData(substr($result[$i]["data_nascimento"],0,10))."</td>";
    echo "    <td>".$sistema->convertData(substr($result[$i]["data_entrada"],0,10))."</td>";
    echo "    <td>".$sistema->convertData(substr($result[$i]["data_saida"],0,10))."</td>";
    echo "    <td>".utf8_encode($result[$i]["tipo_desligamento"])."</td>";
    if($_SESSION["pf"]==1){
        echo "    <td width='300px'>".utf8_encode($result[$i]["executora_razao_social"])."</td>";
    }
    echo "    <td class='text-end'><button type='button' class='btn btn-primary' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='top' title='Cadastro' onclick=acolhido_hub('".base64_encode($result[$i]["acolhido_hub_id"])."')><i class='bi bi-person-badge'></i></button> </td>";
    echo "</tr>";

}

echo "</table>";