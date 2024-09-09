<?php
include "../../../../classes/sistema.php";
session_start();

$_SESSION["tipo_prestacao"] = 0;

$sistema = new Sistema();


$sistema->select("rec_prestacoes","tipo_prestacao_id , executora_id , celebrante_id , prestacao_pre_finalizada , prestacao_disponibilizada","prestacao_id = " . base64_decode($_POST["prestacao"]));
$tipoPrestacao = $sistema->getResult();

$_SESSION["tipo_prestacao"] = $tipoPrestacao[0]["tipo_prestacao_id"];

if(base64_decode($_SESSION["usr"])==1){
    //$sistema->debug=true;
}


$campos = 'a.* , b.categoria_descricao , c.subcategoria_descricao , d.* , e.tipo_prestacao_id , e.celebrante_id , e.prestacao_disponibilizada , e.prestacao_pre_finalizada , e.usuario_id_finalizou , e.prestacao_status , f.valor_glosa_parcial , g.usuario_nome as usuario_disponibilizou , h.usuario_nome as usuario_pre_finalizou , i.usuario_nome as usuario_finalizou';
$from = 'rec_notas_fiscais a';
$innerJoin[] = 'inner join rec_categorias b on a.categoria_id = b.categoria_id';
$innerJoin[] = 'inner join rec_subcategorias c on a.subcategoria_id = c.subcategoria_id';
$innerJoin[] = 'inner join rec_notas_status d on a.nota_status = d.nota_status_id';
$innerJoin[] = 'inner join rec_prestacoes e on a.prestacao_id = e.prestacao_id';
$innerJoin[] = 'left join rec_notas_motivos_glosa f on a.nota_fiscal_id = f.nota_fiscal_id';
$innerJoin[] = 'left join rec_usuarios g on e.usuario_id_disponibilizou = g.usuario_id';
$innerJoin[] = 'left join rec_usuarios h on e.usuario_id_pre_finalizou = h.usuario_id';
$innerJoin[] = 'left join rec_usuarios i on e.usuario_id_finalizou = i.usuario_id';

$where = "a.prestacao_id = " . base64_decode($_POST["prestacao"]) . " AND a.nota_status != 5";

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.nota_status ASC');
$result = $sistema->getResult();

if($tipoPrestacao[0]["prestacao_disponibilizada"]==0){

    if($_SESSION['pf']!=1 and $_SESSION['pf']!=2 and $_SESSION['pf']!=6){
        echo '<button type="button" class="btn btn-primary mb-2" id="btnAdicionarNotas">Adicionar Notas Fiscais</button>';
    }
    else if($_SESSION["pf"]==2){
        echo '<button type="button" class="btn btn-primary mb-2 d-none" id="btnAdicionarNotas">Adicionar Notas Fiscais</button>';
    }
    else{}

}

echo "<table id='tblNotasFiscais' class='table datatable table-hover table-striped'>";
echo "    <thead>";
echo "        <tr>";
echo "        <th scope='col'>Data da NF</th>";
echo "        <th scope='col'>Detalhes do Documento</th>";
echo "        <th scope='col'>Categoria</th>";
echo "        <th scope='col'>Subcategoria</th>";
echo "        <th scope='col'>Valor (R$)</th>";
echo "        <th scope='col'>Última Alteração</th>";
echo "        <th scope='col'></th>";

if(base64_decode($_SESSION["usr"])==1){

    if($result[0]["prestacao_disponibilizada"]==0 AND ($_SESSION['pf']==2 OR $_SESSION['pf']==4)){
        if($_SESSION['pf']==4 OR ($_SESSION['pf']==2 AND $result[0]["celebrante_id"]>0)){
            echo "<th scope='col'></th>";
        }
    }

}

echo "        </tr>";
echo "    </thead>";

$fechaPrestacao = 1;
$encerraPrestacao = 1;

for($i=0;$i<count($result);$i++){

    $glosaParcial = "";
    $analiseCoed = "";
    
    if($_SESSION["pf"]==1 and ($result[$i]["nota_status"]==1 or $result[$i]["nota_status"]==2)){// CASO PERFIL LOGADO SEJA COED
        $fechaPrestacao=0;
    }
    
    if($_SESSION["pf"]==2 and ($result[$i]["nota_status"]==1 or $result[$i]["nota_status"]==2)){ // CASO PERFIL LOGADO SEJA CELEBRANTE
        $encerraPrestacao=0;
    }
    
    if($result[$i]["valor_glosa_parcial"] > '0,00'){
        $glosaParcial =  "<i class='bi bi-currency-dollar' style='color:red; text-size:6px;'></i>";
    }

    if($result[$i]["analise_coed"] > 0){
        $analiseCoed =  "<i class='bi bi-search' style='color:blue;'></i>";
    }
    

    switch($result[$i]["nota_status"]){
        case 1:
            $icnStatus = "<p class='text-warning'>".utf8_encode($result[$i]["nota_status_descricao"])." " . $glosaParcial . " ".$analiseCoed."</p>";
        break;
        case 2:
            $icnStatus = "<p class='text-success'>".$result[$i]["nota_status_descricao"]." " . $glosaParcial . " ".$analiseCoed. "</p>";
        break;
        case 3:
            $icnStatus = "<p class='text-primary'>".$result[$i]["nota_status_descricao"]." " . $glosaParcial . " ".$analiseCoed. "</p>";
        break;
        case 4:
            $icnStatus = "<p class='text-danger'>".$result[$i]["nota_status_descricao"]." " . $glosaParcial . " ".$analiseCoed. "</p>";
        break;
        case 6:
            $icnStatus = "<p class='text-primary text-opacity-25'>".utf8_encode($result[$i]["nota_status_descricao"])." " . $glosaParcial . " ".$analiseCoed. "</p>";
        break;
        case 7:
            $icnStatus = "<p class='text-warning'>".$result[$i]["nota_status_descricao"]." " . $glosaParcial . " ".$analiseCoed. "</p>";
        break;
        case 8:
            $icnStatus = "<p class='text-muted'>".utf8_encode($result[$i]["nota_status_descricao"])." " . $glosaParcial . " ".$analiseCoed. "</p>";
        break;
        default:
            $icnStatus = "<p class='text-warning'>".$result[$i]["nota_status_descricao"]." " . $glosaParcial . " ".$analiseCoed. "</p>";
        break;
    }

    if($result[$i]["nota_status"]>0){
        echo "<tr onclick=detalhesNotaFiscal('".base64_encode($result[$i]["nota_fiscal_id"])."')>";
        echo "    <td>".$sistema->convertData($result[$i]["data_nota_fiscal"])."</td>";
        echo "    <td>".utf8_encode($result[$i]["numero_nota_fiscal"])."</td>";
        echo "    <td>".utf8_encode($result[$i]["categoria_descricao"])."</td>";
        echo "    <td>".utf8_encode($result[$i]["subcategoria_descricao"])."</td>";
        echo "    <td>".$result[$i]["valor_nota"]."</td>";
        echo "    <td>".$sistema->convertData($result[$i]["data_cadastro"])."</td>";
        echo "    <td class='text-end'>".$icnStatus."</td>";
        
        if($result[0]["prestacao_disponibilizada"]==0 AND ($_SESSION['pf']==2 OR $_SESSION['pf']==4)){
            if($_SESSION['pf']==4 OR ($_SESSION['pf']==2 AND $result[0]["celebrante_id"]>0)){
                echo "<td class='text-end'><i class='bi bi-trash text-danger fs-5' style='cursor:pointer' onclick='criaPergunta(2,".$result[$i]["prestacao_id"].",".$result[$i]["nota_fiscal_id"].")'></i></td>";
            }
        }        

        echo "</tr>";
    }

}

echo "</table>";
echo '<div class="col-12 text-end"><a href="https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/gerarPdf.php?p='.base64_encode($result[0]["prestacao_id"]).'" target="_blank"><img src="https://portal.seds.sp.gov.br/coed/images/pdf.gif" border="0"></a>';
if($_SESSION["pf"]==1){
    echo '<img src="https://portal.seds.sp.gov.br/coed/images/csv.gif" border="0" style="cursor:pointer" onclick=gerarCsv("'.base64_encode($result[0]["prestacao_id"]).'")></div>';
}

if($result[0]["prestacao_disponibilizada"]==1){
    $finalizacao = "<strong>Disponibilizada por:</strong> " . utf8_encode($result[0]["usuario_disponibilizou"]);
}

if($result[0]["prestacao_pre_finalizada"]==1){
    $finalizacao .= " | <strong>Pré-finalizada por:</strong> " . utf8_encode($result[0]["usuario_pre_finalizou"]);
}

if($result[0]["usuario_id_finalizou"]>0){
    $finalizacao .= " | <strong>Finalizada por:</strong> " . utf8_encode($result[0]["usuario_finalizou"]);
}


if($_SESSION["pf"]==1){
    echo '<div class="row"><div class="col-12">'.$finalizacao.'</div></div>';
}

echo "<script>validaStatusNotasFiscais(".$_SESSION["pf"].",".$result[0]["prestacao_disponibilizada"].")</script>";

if($encerraPrestacao==1 AND $_SESSION["pf"]==2 AND $tipoPrestacao[0]["prestacao_pre_finalizada"]==0){
    echo '<div class="row justify-content-start text-start col-3"><div><button type="button" class="btn btn-success" id="btnEncerraPrestacao" onclick="criaPergunta(4,0,0)"><i class="bi bi-clipboard2-check"></i> Encerrar Prestação</button></div><div>';
}

if($fechaPrestacao==1 AND $_SESSION["pf"]==1 AND $result[0]["prestacao_status"]==0){
    echo '<div class="row justify-content-start text-start col-3"><div><button type="button" class="btn btn-success" id="btnFinalizarPrestacao" onclick="criaPergunta(5,0,0)"><i class="bi bi-clipboard2-check"></i> Finalizar Prestação</button></div><div>';
}


if($result[0]["prestacao_status"]==1){
    echo '<script>$("#btnAdicionarNotas").addClass("d-none");</script>';

    if(base64_decode($_SESSION['usr'])==1){
        echo '<script>mostraFerramentasCoed()</script>';
    }

}

if($result[0]["prestacao_disponibilizada"]==1){
    if($_SESSION['pf']==1 and (base64_decode($_SESSION['usr'])==1 or base64_decode($_SESSION['usr'])==18 or base64_decode($_SESSION['usr'])==22)){
        echo '<script>mostraFerramentasCoed()</script>';
    }
}

$docComplementar = "anexos/prestacoes/DocCompl".$_POST["prestacao"].".pdf";

if(file_exists($docComplementar)){
    echo "<script>carregaDocumentoComplementar('DocCompl".$_POST["prestacao"].".pdf',".$_SESSION["pf"].",".$result[0]["celebrante_id"].")</script>";
}
else{
    if($_SESSION["pf"]==1 or ($_SESSION["pf"]==2 and $result[0]["celebrante_id"]==0)){
        echo "<script>$('#boxEnvioDocComplementar').html('Não existe arquivo complementar para essa prestação de contas.')</script>";
    }
}

if($tipoPrestacao[0]["celebrante_id"]==0){
    $_SESSION["consultaPrestacaoId"] = $tipoPrestacao[0]["executora_id"];
    $_SESSION["consultaPrestacaoTipo"] = 'executora';
}
else{
    $_SESSION["consultaPrestacaoId"] = $tipoPrestacao[0]["celebrante_id"];
    $_SESSION["consultaPrestacaoTipo"] = 'celebrante';
}

//echo "<script>$('#boxBreadCrumbs').html('<a href=/coed/prestacoes/".base64_encode($tipoPrestacao[0]["tipo_prestacao_id"]).">Prestação de Contas</a>')</script>";