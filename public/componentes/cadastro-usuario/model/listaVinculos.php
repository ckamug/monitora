<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$campos = 'a.* , b.perfil_id , b.perfil_descricao , c.executora_nome_fantasia , d.celebrante_nome_fantasia , e.municipio_orgao_publico';
$from = 'rec_usuarios_vinculos a';
$innerJoin[] = 'inner join rec_perfis b on a.perfil_id = b.perfil_id';
$innerJoin[] = 'left join rec_executoras c on a.executora_id = c.executora_id';
$innerJoin[] = 'left join rec_celebrantes d on a.celebrante_id = d.celebrante_id';
$innerJoin[] = 'left join rec_municipios e on a.municipio_id = e.municipio_id';

$where = "a.usuario_id = " . base64_decode($_POST["id"]);

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$result = $sistema->getResult();

if(count($result)>0){

    echo "<table id='tblVinculos' class='table datatable table-hover table-striped'>";
    echo "    <thead>";
    echo "        <tr>";
    echo "        <th scope='col'>Perfil</th>";
    echo "        <th scope='col'>Vinculo</th>";
    echo "        <th scope='col'>Data</th>";
    echo "        </tr>";
    echo "    </thead>";

    for($i=0;$i<count($result);$i++){

        echo "<tr>";
        echo "    <td>".utf8_encode($result[$i]["perfil_descricao"])."</td>";
        if($result[$i]["perfil_id"]==4){
            echo "    <td>".utf8_encode($result[$i]["executora_nome_fantasia"])."</td>";
        }
        elseif($result[$i]["perfil_id"]==2){
            echo "    <td>".utf8_encode($result[$i]["celebrante_nome_fantasia"])."</td>";
        }
        elseif($result[$i]["perfil_id"]==3){
            echo "    <td>".utf8_encode($result[$i]["municipio_orgao_publico"])."</td>";
        }
        else{
            echo "    <td> - </td>";
        }
        echo "    <td>".$sistema->convertData($result[$i]["data_cadastro"])."</td>";
        
        echo "</tr>";

    }

    echo "</table>";
}
else{
    echo "Nenhum vínculo registrado.";
}