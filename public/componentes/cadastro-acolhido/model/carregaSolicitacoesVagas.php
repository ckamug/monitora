<?php
include "../../../../classes/sistema.php";
session_start();

if(is_numeric($_POST["id"])){
    $id = $_POST["id"];
}
else{
    $id = base64_decode($_POST["id"]);
}

if($id!=""){

    $sistema = new Sistema();
    $campos = 'a.* , a.acolhido_id as acolhido , b.* , b.data_cadastro as data_cadastro_solicitacao , c.* , d.executora_razao_social , e.* , f.usuario_nome';
    $from = 'rec_acolhidos a';
    $innerJoin[] = 'left join rec_solicitacoes_vagas b on a.acolhido_id = b.acolhido_id';
    $innerJoin[] = 'left join rec_status_vaga c on b.status_vaga_id = c.status_vaga_id';
    $innerJoin[] = 'left join rec_executoras d on b.executora_id = d.executora_id';
    $innerJoin[] = 'left join rec_solicitacoes_vagas_justificativas e on b.solicitacao_vaga_id = e.solicitacao_vaga_id';
    $innerJoin[] = 'left join rec_usuarios f on b.usuario_id = f.usuario_id';

    $where = 'a.acolhido_id = ' . $id;

    //$sistema->debug=true;
    $sistema->innerJoin($campos,$from,$innerJoin,$where,'','b.data_cadastro DESC');
    $result = $sistema->getResult();

    for($i=0;$i<count($result);$i++){

        if($result[$i]["status_vaga_id"]==1){
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
            $label = 'Data da solicitação:';
        }
        else if($result[$i]["status_vaga_id"]==2){
            echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">';
            $label = 'Data da reserva:';
        }
        else if($result[$i]["status_vaga_id"]==3){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            $label = 'Data da aceitação:';
        }
        else if($result[$i]["status_vaga_id"]==4){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            $label = 'Data da negativa:';
        }
        else{}

        echo '<h4 class="alert-heading">' . utf8_encode($result[$i]["executora_razao_social"]) . '</h4>';
        echo '<p><b>Status:</b> ' . utf8_encode($result[$i]["status_vaga_descricao"]) . '</p>';
        if($result[$i]["status_vaga_id"]==4){
            echo '<p><b>Justificativa:</b> ' . utf8_encode($result[$i]["solicitacao_vaga_justificativa_descricao"]) . '</p>';
        }
        echo '<hr>';
        echo '<p class="mb-0">' . $label . '  ' . $sistema->convertData($result[$i]["data_cadastro_solicitacao"]) . ' às ' . substr($result[$i]["data_cadastro_solicitacao"],11,5) . ' por ' . utf8_encode($result[$i]["usuario_nome"]) . '</p>';
        echo '</div>';

    }

}