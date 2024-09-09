<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$sistema->select("rec_usuarios","*","","","usuario_nome");
$result = $sistema->getResult();

echo "<table id='tblUsuarios' class='table datatable table-hover table-striped'>";
echo "    <thead>";
echo "        <tr>";
echo "        <th scope='col'>Nome</th>";
echo "        <th scope='col'>CPF</th>";
echo "        <th scope='col'>E-mail</th>";
echo "        </tr>";
echo "    </thead>";

for($i=0;$i<count($result);$i++){

    echo "<tr onclick=usuario('".base64_encode($result[$i]["usuario_id"])."')>";
    echo "    <td>".utf8_encode($result[$i]["usuario_nome"])."</td>";
    echo "    <td>".$result[$i]["usuario_cpf"]."</td>";
    echo "    <td>".$result[$i]["usuario_email"]."</td>";
    echo "</tr>";

}

echo "</table>";