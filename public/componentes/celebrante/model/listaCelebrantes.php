<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$campos = 'a.* , b.cidade_descricao';
$from = 'rec_celebrantes a';
$innerJoin[] = 'inner join tbl_cidades b on a.cidade_id = b.cidade_id';

$sistema->innerJoin($campos,$from,$innerJoin,'','','a.celebrante_razao_social');
$result = $sistema->getResult();

echo "<table id='tblCelebrantes' class='table datatable table-hover table-striped'>";
echo "    <thead>";
echo "        <tr>";
echo "        <th scope='col'>Nome Fantasia</th>";
echo "        <th scope='col'>Município</th>";
echo "        <th scope='col'>CNPJ</th>";
echo "        <th scope='col'>E-mail</th>";
echo "        <th scope='col'>Telefone</th>";
echo "        </tr>";
echo "    </thead>";

for($i=0;$i<count($result);$i++){

    echo "<tr onclick=celebrante('".base64_encode($result[$i]["celebrante_id"])."')>";
    echo "    <td>".utf8_encode($result[$i]["celebrante_razao_social"])."</td>";
    echo "    <td>".utf8_encode($result[$i]["cidade_descricao"])."</td>";
    echo "    <td>".$result[$i]["celebrante_cnpj"]."</td>";
    echo "    <td>".$result[$i]["celebrante_email"]."</td>";
    echo "    <td>".$result[$i]["celebrante_telefone"]."</td>";
    echo "</tr>";

}

echo "</table>";