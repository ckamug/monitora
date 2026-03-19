<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$campos = 'a.* , b.* , b.data_cadastro as data_apontamento , c.usuario_nome , d.*';
$from = 'rec_notas_fiscais a';
$innerJoin[] = 'left join rec_notas_apontamentos b on a.nota_fiscal_id = b.nota_fiscal_id';
$innerJoin[] = 'left join rec_usuarios c on b.usuario_id = c.usuario_id';
$innerJoin[] = 'left join rec_prestacoes d on a.prestacao_id = d.prestacao_id';

$sistema->innerJoin($campos,$from,$innerJoin,'a.nota_fiscal_id = ' . $_POST["id"],'','');
$result = $sistema->getResult();

echo '<h5 class="card-title ms-2" id="tituloApontamentos">Apontamentos e Justificativas</h5>';

for($i=0;$i<count($result);$i++){

    if($result[$i]["nota_apontamento_id"]>0){

        $dataApontamento = $sistema->convertData($result[$i]["data_apontamento"]) . " às " . substr($result[$i]["data_apontamento"],11,5) . ' por ' . utf8_encode($result[$i]["usuario_nome"]);

        echo '<div class="alert alert-warning" role="alert">';
        echo '  <h4 class="alert-heading">Apontamento</h4>';
        echo '  <p>'.utf8_encode($result[$i]["nota_apontamento_descricao"]).'</p>';
        echo '  <hr>';
        echo '  <p class="mb-0">'.$dataApontamento.'</p>';
        echo '</div>';

        
        if(base64_decode($_SESSION["usr"])==1 AND $i==1){
            //$sistema->debug=true;
        }
        
        $campos = 'a.* , b.usuario_nome';
        $from = 'rec_notas_justificativas a';
        $innerJoinJus[] = 'inner join rec_usuarios b on a.usuario_id = b.usuario_id';
        
        $sistema->innerJoin($campos,$from,$innerJoinJus,'nota_apontamento_id='.$result[$i]["nota_apontamento_id"],'a.nota_apontamento_id','');
        $res_justificativa = $sistema->getResult();

        if(count($res_justificativa)>0){

            $dataJustificativa = $sistema->convertData($res_justificativa[0]["data_cadastro"]) . " às " . substr($res_justificativa[0]["data_cadastro"],11,5) . ' por ' . utf8_encode($res_justificativa[0]["usuario_nome"]);
            echo '<div class="row">';
            echo '<div class="col-1 text-end">';
            echo '<h5><i class="bi bi-arrow-return-right"></i></h5>';
            echo '</div>';
            echo '<div class="alert alert-success alert-dismissible fade show col-11" role="alert">';
            echo '<h4 class="alert-heading">Justificativa</h4>';
            echo '<p id="textoJustificativa">'.utf8_encode($res_justificativa[0]["nota_justificativa_descricao"]).'</p>';
            echo '<hr>';
            echo '<p class="mb-0" id="dataJustificativa">'.$dataJustificativa.'</p>';
            echo '</div>';
            echo '</div>';

            if($result[$i]["analise_coed"]==1 AND $_SESSION["pf"]==1 AND count($res_justificativa)==1 AND $i==count($result)-1 AND $result[$i]["nota_status"]!=3){
                mostraCampoApontamento($_POST["id"]);
            }

        }
        else{
        
            if($_SESSION["pf"]==4 or ($result[$i]["celebrante_id"]>0 AND $_SESSION["pf"]==2)){
                mostraCampoJustificativa();
                echo "<script>";
                if($_SESSION["pf"] != 6){
                    $botoes = '<button type="button" class="btn btn-success" id="btnEditar">Registrar Informações</button> <button type="button" class="btn btn-secondary" onclick="cancelaCadNota('.$_SESSION["pf"].')">Cancelar</button>';
                    echo "$('#boxBotoes').html('".$botoes."');";
                    echo "$('#btnEditar').click(function() {editaNotaFiscal(1,".$_POST["id"].");registraJustificativa(".$result[$i]["nota_apontamento_id"].",".$_POST["id"].")});";
                }
                echo "</script>";
            }

        }

    }
    else{
        if($_SESSION["pf"]==1 or $_SESSION["pf"]==2)
        {
            if(($_SESSION["pf"]==1) or ($_SESSION["pf"]==2 and $result[$i]["analise_coed"]==0)){
                if($result[$i]["nota_status"]!=3){
                    mostraCampoApontamento($_POST["id"]);
                }
            }
        }
        else{
            echo "<div>Nenhum apontamento registrado.</div>";
        }
    }
    echo '</div>';

    unset($innerJoinJus);

}

function mostraCampoApontamento($id){

    echo '<div class="form-floating" id="boxCampoApontamento">';
    echo '    <textarea class="form-control" placeholder="Escreva o apontamento" id="txtApontamento" name="txtApontamento" style="height: 100px"></textarea>';
    echo '    <label for="txtApontamento">Apontamento</label>';
    echo '    <button type="button" class="btn btn-success mt-2" id="btnRegistraApontamento" onclick="registraApontamento('.$id.')">Registrar Apontamento</button>';
    echo '</div>';
}

function mostraCampoJustificativa(){
    echo '<div class="form-floating mt-3">';
    echo '    <textarea class="form-control" placeholder="Escreva a justificativa aqui" id="txtJustificativa" name="txtJustificativa" style="height: 100px"></textarea>';
    echo '    <label for="txtJustificativa">Informe a Justificativa para o último apontamento</label>';
    echo '</div>';
}