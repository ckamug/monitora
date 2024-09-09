<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$campos = 'a.* , b.cidade_descricao';
$from = 'rec_municipios a';
$innerJoin[] = 'inner join tbl_cidades b on a.cidade_id = b.cidade_id';

$sistema->innerJoin($campos,$from,$innerJoin,'','','a.municipio_orgao_publico');
$result = $sistema->getResult();

echo "<table id='tblMunicipios' class='table datatable table-hover table-striped'>";
echo "    <thead>";
echo "        <tr>";
echo "        <th scope='col'>Órgão Público</th>";
echo "        <th scope='col'>Município</th>";
echo "        <th scope='col'>CNPJ</th>";
echo "        <th scope='col'>E-mail</th>";
echo "        <th scope='col'>Telefone</th>";
echo "        </tr>";
echo "    </thead>";

for($i=0;$i<count($result);$i++){

    echo "<tr onclick=municipio('".base64_encode($result[$i]["municipio_id"])."')>";
    echo "    <td>".utf8_encode($result[$i]["municipio_orgao_publico"])."</td>";
    echo "    <td>".utf8_encode($result[$i]["cidade_descricao"])."</td>";
    echo "    <td>".$result[$i]["municipio_cnpj"]."</td>";
    echo "    <td>".$result[$i]["municipio_email_institucional"]."</td>";
    echo "    <td>".$result[$i]["municipio_telefone"]."</td>";
    echo "</tr>";

}

echo "</table>";