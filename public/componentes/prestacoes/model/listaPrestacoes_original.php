<?php
include "../../../../classes/sistema.php";
session_start();

$_SESSION["tipo_prestacao"] = 0;

$sistema = new Sistema();
$campos = 'a.* , b.tipo_prestacao_descricao , c.cabecalho_mes_referencia , c.valor_repasse';
$from = 'rec_prestacoes a';
$innerJoin[] = 'inner join rec_tipos_prestacao b on a.tipo_prestacao_id = b.tipo_prestacao_id';

if($_SESSION["pf"]==1){
    
    if($_POST["tipo"]=="executora"){
        $innerJoin[] = 'left join rec_cabecalhos c on a.executora_id = c.executora_id';
        $where = 'a.executora_id = ' . $_POST["id"];
    }
    else{
        $innerJoin[] = 'left join rec_cabecalhos c on a.celebrante_id = c.celebrante_id';
        $where = 'a.celebrante_id > 0';
    }    
    

}
else if($_SESSION["pf"]==2){

    if($_POST["id"]>0){
        $innerJoin[] = 'left join rec_cabecalhos c on a.executora_id = c.executora_id';
        $where = 'a.executora_id = ' . $_POST["id"];
    }
    else{
        $innerJoin[] = 'left join rec_cabecalhos c on a.celebrante_id = c.celebrante_id';
        $where = 'a.celebrante_id = ' . $_SESSION["pfv"] ;
    }

}
else if($_SESSION["pf"]==4){
    $innerJoin[] = 'left join rec_cabecalhos c on a.executora_id = c.executora_id';
    $where = $where = 'a.executora_id = ' . $_SESSION["pfv"];
}
else{
    $innerJoin[] = 'left join rec_cabecalhos c on a.executora_id = c.executora_id';
}

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.prestacao_id DESC');
$result = $sistema->getResult();

if(count($result)>0){

    for($i=0;$i<count($result);$i++){

        if($result[$i]["prestacao_mes_referencia"] == $result[$i]["cabecalho_mes_referencia"]){

            if($result[$i]["valor_repasse"]>0){
                if($result[$i]["prestacao_status"]==0){
                    
                    if($result[$i]["prestacao_disponibilizada"]==0){
                        $valorRepasse = $result[$i]["valor_repasse"];
                        $statusPrestacao = "Aguardando disponibilização";
                        $class = "primary";
                        echo "<div class='card border-primary mb-3 ms-3' style='max-width: 20rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal' onclick=abrePrestacao('".base64_encode($result[$i]["prestacao_id"])."')>";
                    }
                    else{
                        $valorRepasse = $result[$i]["valor_repasse"];
                        $statusPrestacao = "Em análise";
                        $class = "warning";
                        echo "<div class='card border-warning mb-3 ms-3' style='max-width: 20rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal' onclick=abrePrestacao('".base64_encode($result[$i]["prestacao_id"])."')>";
                    }

                }
                else{
                    $valorRepasse = $result[$i]["valor_repasse"];
                    $statusPrestacao = "Finalizada";
                    $class = "success";
                    echo "<div class='card border-success mb-3 ms-3' style='max-width: 20rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal' onclick=abrePrestacao('".base64_encode($result[$i]["prestacao_id"])."')>";
                }

            }
            else{
                $valorRepasse = "0,00";
                $statusPrestacao = "Aguardando Cabeçalho";
                $class = "secondary";
                echo "<div class='card border-secondary mb-3 ms-3' style='max-width: 20rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal'>";
            }

            echo '<div class="card-header bg-transparent border-'.$class.'">'.utf8_encode($result[$i]["tipo_prestacao_descricao"]).'</div>';
            echo '<div class="card-body">';
            echo '<p class="card-text mt-3">Mês: '.substr($sistema->convertData($result[$i]["prestacao_mes_referencia"]),1).'</p>';
            echo '<p class="card-text">Valor: '.$valorRepasse.'</p>';
            echo '</div>';
            echo '<div class="card-footer bg-transparent border-'.$class.' text-'.$class.'">'.$statusPrestacao.'</div>';
            echo '</div>';
        
        }
        else if(!$result[$i]["cabecalho_mes_referencia"]){
            echo "<div class='card border-secondary mb-3 ms-3' style='max-width: 18rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal'>";
            echo '<div class="card-header bg-transparent border-secondary">'.utf8_encode($result[$i]["tipo_prestacao_descricao"]).'</div>';
            echo '<div class="card-body">';
            echo '<p class="card-text mt-3">Mês: '.substr($sistema->convertData($result[$i]["prestacao_mes_referencia"]),1).'</p>';
            echo '<p class="card-text">Valor: 0,00</p>';
            echo '</div>';
            echo '<div class="card-footer bg-transparent border-secondary text-secondary">Aguardando Cabeçalho</div>';
            echo '</div>';
        }
        else{
        }
    
    }

}
else{
    echo "Não existem prestações de contas cadastradas";
}