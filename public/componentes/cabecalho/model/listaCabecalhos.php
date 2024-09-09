<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$campos = 'a.* , b.tipo_repasse_descricao';
$from = 'rec_cabecalhos a';
$innerJoin[] = 'inner join rec_tipos_repasse b on a.tipo_repasse_id = b.tipo_repasse_id';

if($_POST['tipo']=='executora'){
    $where = 'a.executora_id = ' . $_POST["id"];
}
else{
    $where = 'a.celebrante_id > 0 ';
}

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.cabecalho_id DESC');
$result = $sistema->getResult();

if(count($result)>0){

    for($i=0;$i<count($result);$i++){

        echo '<div class="card border-success mb-3 ms-3" style="max-width: 25rem;" data-bs-toggle="modal" data-bs-target="#encaminhamentoModal">';
            echo '<div class="card-header bg-transparent border-success">Tipo de Repasse: '.utf8_encode($result[$i]["tipo_repasse_descricao"]).'</div>';
            echo '<div class="card-body">';
                echo '<p class="card-text mt-3">Mês: '.substr($sistema->convertData($result[$i]["cabecalho_mes_referencia"]),1).'</p>';
                echo '<p class="card-text">Valor de Repasse: R$'.$result[$i]["valor_repasse"].'</p>';
            echo '</div>';
            echo '<div class="card-footer bg-transparent border-success text-success">Fechada</div>';
        echo '</div>';

    }

}
else{
    
    if($_POST['tipo']=='executora'){
        echo "Não existem cabeçalhos cadastrados para a OSC selecionada";
    }
    else{
        echo "Não existem cabeçalhos cadastrados para a Celebrante";
    }    

}