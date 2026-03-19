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
echo "        <th scope='col'></th>";
echo "        </tr>";
echo "    </thead>";

for($i=0;$i<count($result);$i++){

    if($result[$i]["senha_alterada"]==1){
        $icone = "<button class='btn btn-warning' onclick='criaPergunta(".$result[$i]["usuario_id"].")'><i class='bi bi-key'></i></button>";
    }
    else{
        $icone = "";
    }

    echo "<tr>";
    echo "    <td onclick=usuario('".base64_encode($result[$i]["usuario_id"])."')>".utf8_encode($result[$i]["usuario_nome"])."</td>";
    echo "    <td onclick=usuario('".base64_encode($result[$i]["usuario_id"])."')>".$result[$i]["usuario_cpf"]."</td>";
    echo "    <td onclick=usuario('".base64_encode($result[$i]["usuario_id"])."')>".$result[$i]["usuario_email"]."</td>";
    echo "    <td style='text-align:right;'>".$icone."</td>";
    echo "</tr>";

}

echo "</table>";