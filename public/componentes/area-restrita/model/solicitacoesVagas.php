<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

//$sistema->debug=true;
$campos = 'a.acolhido_nome_completo , a.acolhido_endereco_fixo , b.solicitacao_vaga_id , b.status_vaga_id , b.data_cadastro , c.municipio_orgao_publico';
$from = 'rec_acolhidos a';
$innerJoin[] = 'inner join rec_solicitacoes_vagas b on a.acolhido_id = b.acolhido_id';
$innerJoin[] = 'inner join rec_municipios c on b.municipio_id = c.municipio_id';
$where = 'status_registro = 1 AND b.executora_id = ' . $_SESSION['pfv'];

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','acolhido_endereco_fixo , data_cadastro');
$result = $sistema->getResult();

$solicitacoes = 0;

    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>Nome do Acolhido</th>";
    echo "<th scope='col'>Porta de Entrada</th>";
    echo "<th scope='col'>Data da Solicitação</th>";
    echo "<th scope='col'></th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";


    for($i=0;$i<count($result);$i++){
        if($result[$i]["status_vaga_id"]==1){
            $aviso = "";
            $classe = "";
            
            if($result[$i]["acolhido_endereco_fixo"]=='NAO'){
                $aviso = "<span class='text-danger' style='font-size:12px;'>(Situação de rua)</span>";
                $classe = "class='table-warning'";
            }

            echo "<tr ".$classe.">";
            echo "<td>".utf8_encode($result[$i]["acolhido_nome_completo"]). " " . $aviso . "</td>";
            echo "<td>".utf8_encode($result[$i]["municipio_orgao_publico"])."</td>";
            echo "<td width='200px;'>".$sistema->convertData($result[$i]["data_cadastro"])." " .substr($result[$i]["data_cadastro"],11,8). "</td>";
            echo "<td width='15%' align='right'><button type='button' class='btn btn-success' onclick='pergunta(1 , 1 , ".$result[$i]["solicitacao_vaga_id"].")'>Reservar vaga</button> <button type='button' class='btn btn-danger' onclick='pergunta(1 , 0 , ".$result[$i]["solicitacao_vaga_id"].")'>Negar</button></td>";
            echo "</tr>";
            $solicitacoes++;
        }
    }

    echo "</tbody>";
    echo "</table>";

if($solicitacoes==0){
    echo "<script>$('#boxTabelaSolicitacoesVagas').addClass('d-none');</script>";
}