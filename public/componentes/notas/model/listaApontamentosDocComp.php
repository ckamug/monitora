<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$campos = 'a.* , b.usuario_nome';
$from = 'rec_apontamentos_documentos_complementares a';
$innerJoin[] = 'inner join rec_usuarios b on a.usuario_id = b.usuario_id';

$where = "a.prestacao_id = " . base64_decode($_POST["prestacao"]);

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.data_cadastro DESC');
$result = $sistema->getResult();

if(count($result)>0){

    echo "<table id='tblApontamentosDocComp' class='table datatable table-hover table-striped'>";
    echo "    <thead>";
    echo "        <tr>";
    echo "        <th scope='col'>Data</th>";
    echo "        <th scope='col'>Mensagem</th>";
    echo "        <th scope='col'>Enviado por</th>";
    echo "        </tr>";
    echo "    </thead>";
    

    for($i=0;$i<count($result);$i++){

        if($result[$i]["apontamento_doc_id"]>0){
            echo "<tr>";
            echo "    <td style='font-size:12px;'>".$sistema->convertData($result[$i]["data_cadastro"]). " " . substr($result[$i]["data_cadastro"],11,5) . "</td>";
            echo "    <td>".utf8_encode($result[$i]["apontamento_descricao"])."</td>";
            echo "    <td style='font-size:12px;'>".utf8_encode($result[$i]["usuario_nome"])."</td>";
            echo "</tr>";
        }

    }

    echo "</table>";
}
else{
    echo "Não existem apontamentos";
}

