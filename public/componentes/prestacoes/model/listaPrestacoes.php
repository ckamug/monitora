<?php
include "../../../../classes/sistema.php";
session_start();

$_SESSION["tipo_prestacao"] = 0;

if($_POST["id"]>0){
    $id = $_POST["id"];
    $tipo = $_POST["tipo"];    
}
else{
    $id = $_SESSION["consultaPrestacaoId"];
    $tipo = $_SESSION["consultaPrestacaoTipo"];

    if($tipo=='celebrante'){
        echo '<script>$("#txtTituloPrestacao").html(" - Celebrante");</script>';
    }
    else{
        echo '<script>setTimeout(\'$("#txtTituloPrestacao").html(" - " + $("#slcExecutoras option:selected").text())\',100);</script>';
    }

}

$sistema = new Sistema();

if($_SESSION["pf"]==1 or $_SESSION["pf"]==6){
    
    if($tipo=="executora"){
         $where = 'executora_id = ' . $id;
    }
    else{
         $where = 'celebrante_id > 0';
    }    

}
else if($_SESSION["pf"]==2){

    if($id>0 and $tipo!='celebrante'){
         $where = 'executora_id = ' . $id;
    }
    else{
         $where = 'celebrante_id = ' . $_SESSION["pfv"] ;
    }

}
else if($_SESSION["pf"]==4){
    $where = 'executora_id = ' . $_SESSION["pfv"];
}
else{

}

if($_SESSION["pfv"]==78){
    //$sistema->debug=true;
}
$sistema->select("rec_cabecalhos",'*',$where,'','cabecalho_id DESC');
$cabecalhos = $sistema->getResult();


if(count($cabecalhos)>0){

    for($i=0;$i<count($cabecalhos);$i++){

        if($_SESSION["pf"]==1 or $_SESSION["pf"]==6){
    
            if($tipo=="executora"){
                 $where = 'executora_id = ' . $id . " AND prestacao_mes_referencia = '" . $cabecalhos[$i]["cabecalho_mes_referencia"] . "' AND a.tipo_prestacao_id = " . $cabecalhos[$i]["tipo_repasse_id"];
            }
            else{
                 $where = 'celebrante_id > 0' . " AND prestacao_mes_referencia = '" . $cabecalhos[$i]["cabecalho_mes_referencia"] . "' AND a.tipo_prestacao_id = " . $cabecalhos[$i]["tipo_repasse_id"];
            }    
        
        }
        else if($_SESSION["pf"]==2){
        
            if($id>0 and $tipo!='celebrante'){
                 $where = 'executora_id = ' . $id . " AND prestacao_mes_referencia = '" . $cabecalhos[$i]["cabecalho_mes_referencia"] . "' AND a.tipo_prestacao_id = " . $cabecalhos[$i]["tipo_repasse_id"];
            }
            else{
                 $where = 'celebrante_id = ' . $_SESSION["pfv"] . " AND prestacao_mes_referencia = '" . $cabecalhos[$i]["cabecalho_mes_referencia"] . "' AND a.tipo_prestacao_id = " . $cabecalhos[$i]["tipo_repasse_id"];
            }
        
        }
        else if($_SESSION["pf"]==4){
            $where = 'executora_id = ' . $_SESSION["pfv"] . " AND prestacao_mes_referencia = '" . $cabecalhos[$i]["cabecalho_mes_referencia"] . "' AND a.tipo_prestacao_id = " . $cabecalhos[$i]["tipo_repasse_id"];
        }
        else{
        
        }
        
        unset($innerJoin);

        $campos = 'a.* , b.*';
        $from = 'rec_prestacoes a';
        $innerJoin[] = 'inner join rec_tipos_prestacao b on a.tipo_prestacao_id = b.tipo_prestacao_id';
        
        if(base64_decode($_SESSION["usr"])==1){
            //$sistema->debug=true;
        }
        
        $sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
        $result = $sistema->getResult();

        if(count($result)>0){

                if($result[0]["prestacao_mes_referencia"] == $cabecalhos[$i]["cabecalho_mes_referencia"]){
        
                    if($cabecalhos[$i]["cabecalho_id"]>0){
                        if($result[0]["prestacao_status"]==0){
                            
                            if($result[0]["prestacao_disponibilizada"]==0){
                                $valorRepasse = $cabecalhos[$i]["valor_repasse"];
                                $statusPrestacao = "Aguardando disponibilização";
                                $class = "primary";
                                echo "<div class='card border-primary mb-3 ms-3' style='max-width: 20rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal' onclick=abreTermo('".base64_encode($result[0]["prestacao_id"])."')>";
                            }
                            else{
                                $valorRepasse = $cabecalhos[$i]["valor_repasse"];
                                $statusPrestacao = "Em análise";
                                $class = "warning";
                                echo "<div class='card border-warning mb-3 ms-3' style='max-width: 20rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal' onclick=abreTermo('".base64_encode($result[0]["prestacao_id"])."')>";
                            }
        
                        }
                        else{
                            $valorRepasse = $cabecalhos[$i]["valor_repasse"];
                            $statusPrestacao = "Finalizada";
                            $class = "success";
                            echo "<div class='card border-success mb-3 ms-3' style='max-width: 20rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal' onclick=abrePrestacao('".base64_encode($result[0]["prestacao_id"])."')>";
                        }
        
                    }
                    else{
                        $valorRepasse = "0,00";
                        $statusPrestacao = "Aguardando Cabeçalho";
                        $class = "secondary";
                        echo "<div class='card border-secondary mb-3 ms-3' style='max-width: 20rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal'>";
                    }
        
                    echo '<div class="card-header bg-transparent border-'.$class.'">'.utf8_encode($result[0]["tipo_prestacao_descricao"]).'</div>';
                    echo '<div class="card-body">';
                    echo '<p class="card-text mt-3">Mês: '.substr($sistema->convertData($result[0]["prestacao_mes_referencia"]),1).'</p>';
                    echo '<p class="card-text">Valor: '.$valorRepasse.'</p>';
                    echo '</div>';
                    echo '<div class="card-footer bg-transparent border-'.$class.' text-'.$class.'">'.$statusPrestacao.'</div>';
                    echo '</div>';
                
                }
                else if(!$cabecalhos[$i]["cabecalho_mes_referencia"]){
                    echo "<div class='card border-secondary mb-3 ms-3' style='max-width: 18rem; cursor:pointer;' data-bs-toggle='modal' data-bs-target='#encaminhamentoModal'>";
                    echo '<div class="card-header bg-transparent border-secondary">'.utf8_encode($result[0]["tipo_prestacao_descricao"]).'</div>';
                    echo '<div class="card-body">';
                    echo '<p class="card-text mt-3">Mês: '.substr($sistema->convertData($result[0]["prestacao_mes_referencia"]),1).'</p>';
                    echo '<p class="card-text">Valor: 0,00</p>';
                    echo '</div>';
                    echo '<div class="card-footer bg-transparent border-secondary text-secondary">Aguardando Cabeçalho</div>';
                    echo '</div>';
                }
                else{
                }
            

        
        }


    }

}