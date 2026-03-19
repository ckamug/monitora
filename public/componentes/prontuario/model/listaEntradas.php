<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$campos = 'a.* , a.acolhido_entrada_id as entrada_id , a.status as status_acolhimento , b.* , c.* , d.* , e.* , f.* , g.tipo_desligamento_descricao';
$from = 'rec_acolhidos_entradas a';
$innerJoin[] = 'left join rec_solicitacoes_vagas b on a.solicitacao_vaga_id = b.solicitacao_vaga_id';
$innerJoin[] = 'left join rec_municipios c on b.municipio_id = c.municipio_id';
$innerJoin[] = 'left join rec_executoras d on a.executora_id = d.executora_id';
$innerJoin[] = 'left join rec_acolhidos e on a.acolhido_id = e.acolhido_id';
$innerJoin[] = 'left join rec_acolhidos_desligamentos f on a.acolhido_entrada_id = f.acolhido_entrada_id';
$innerJoin[] = 'left join rec_tipos_desligamentos g on f.tipo_desligamento_id = g.tipo_desligamento_id';
        
//$sistema->debug=true;

$where = "a.acolhido_id = " . base64_decode($_POST["id"]);

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$result = $sistema->getResult();

for($i=0;$i<count($result);$i++){

    echo "<div class='card col-3 card-custom bg-white border-white border-0 ms-5' style='cursor: pointer;' onclick=location.href='../prontuario_acolhido/".$result[$i]["entrada_id"]."'>";
    if($result[$i]["status_acolhimento"]==2){
        echo "      <div class='card-custom-img' style='background-image: url(../assets/img/bgprontuario_off.jpg);'></div>";
    }
    else{
        echo "      <div class='card-custom-img' style='background-image: url(../assets/img/bgprontuario.jpg);'></div>";
    }
    echo "      <div class='card-custom-avatar'>";
    echo "<img class='img-fluid bg-white' src='../assets/img/avatarM.png' alt='Avatar' />";
    echo "      </div>";
    echo "      <div class='card-body' style='overflow-y: auto'>";
    echo "<h4 class='card-title'>".utf8_encode($result[$i]["executora_razao_social"])."</h4>";
    echo "<p class='card-text'>";
    echo "<strong>PORTA DE ENTRADA</strong><br>";
    echo "".utf8_encode($result[$i]["municipio_orgao_publico"])."";
    echo "</p>";
    echo "<p class='card-text'>";
    echo "<strong>DATA DE ENTRADA:</strong> ".$sistema->convertData($result[$i]["data_entrada"]);
    if($result[$i]["status_acolhimento"]==2){
        echo "<br><strong>DATA DE DESLIGAMENTO:</strong> ".$sistema->convertData($result[$i]["data_desligamento"]);
    }
    echo "</p>";
    echo "      </div>";
    echo "      <div class='card-footer' style='background: inherit; border-color: inherit;'>";
    if($result[$i]["status_acolhimento"]==2){
        $motivoDesligamento = trim($result[$i]["tipo_desligamento_descricao"]) == "" ? "Motivo nao informado" : utf8_encode($result[$i]["tipo_desligamento_descricao"]);
        echo "      <div class='alert alert-secondary' role='alert'>".$motivoDesligamento."</div>";
    }
    else{
        echo "      <div class='alert alert-success' role='alert'>Em acolhimento</div>";
    }
    
    echo "      </div>";
    echo "</div>";

}
