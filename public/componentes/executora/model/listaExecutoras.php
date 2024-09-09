<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$campos = 'a.* , b.cidade_descricao';
$from = 'rec_executoras a';
$innerJoin[] = 'inner join tbl_cidades b on a.cidade_id = b.cidade_id';
$innerJoin[] = 'inner join rec_usuarios c on a.usuario_id = c.usuario_id';

$where = '';

if($_SESSION['pf']==2){
    $where = 'a.tipo_responsavel = 2';
}

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.executora_nome_fantasia');
$result = $sistema->getResult();

echo "<table id='tblExecutoras' class='table datatable table-hover table-striped'>";
echo "    <thead>";
echo "        <tr>";
echo "        <th scope='col'>Razão Social</th>";
echo "        <th scope='col'>Município</th>";
echo "        <th scope='col'>CNPJ</th>";
echo "        <th scope='col'>E-mail Institucional</th>";
echo "        <th scope='col'>Telefone</th>";
echo "        </tr>";
echo "    </thead>";

for($i=0;$i<count($result);$i++){

    echo "<tr onclick=executora('".base64_encode($result[$i]["executora_id"])."')>";
    echo "    <td>".utf8_encode($result[$i]["executora_razao_social"])."</td>";
    echo "    <td>".utf8_encode($result[$i]["cidade_descricao"])."</td>";
    echo "    <td>".$result[$i]["executora_cnpj"]."</td>";
    echo "    <td>".$result[$i]["executora_email"]."</td>";
    echo "    <td>".$result[$i]["executora_telefone"]."</td>";
    echo "</tr>";

}

echo "</table>";