<?php
include "../../../../classes/sistema.php";
session_start();

$data = explode("/",$_POST["data"]);

if($data[0]==01){
    $dataRef = ($data[1]-1) . "-12";
}
else{
    $dataRef = $data[1] . "-" . sprintf("%02d", ($data[0]-1));
}

$sistema = new Sistema();

$sistema->select("rec_executoras","executora_valor_previsto_rh , executora_valor_previsto_custeio , executora_valor_previsto_terceiros","executora_id = " . $_POST["id"]);
$repasse = $sistema->getResult();

//$sistema->debug=true;
$campos = 'a.* , b.*';
$from = 'rec_cabecalhos a';
$innerJoin[] = 'left join rec_prestacoes b on a.tipo_repasse_id = b.tipo_prestacao_id';
$innerJoin[] = 'left join rec_executoras c on a.executora_id = c.executora_id';

$where = 'a.tipo_repasse_id = 1 AND cabecalho_mes_referencia = "' . $dataRef . '" AND prestacao_mes_referencia = "' . $dataRef . '" AND a.executora_id = ' . $_POST["id"] . ' AND b.executora_id = ' . $_POST["id"];

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$resultCabecalho = $sistema->getResult();

if(count($resultCabecalho)>0){
    
    //$sistema->debug=true;
    $camposC = 'a.* , b.*';
    $fromC = 'rec_notas_motivos_glosa a';
    $innerJoinC[] = 'inner join rec_notas_fiscais b on a.nota_fiscal_id = b.nota_fiscal_id';

    $whereC = 'b.prestacao_id = ' . $resultCabecalho[0]["prestacao_id"];

    $sistema->innerJoin($camposC,$fromC,$innerJoinC,$whereC,'','');
    $result = $sistema->getResult();

    $totalGlosaCusteio = 0;
    $totalGlosaRH = 0;
    $totalGlosaTerceiros = 0;

    for($i=0;$i<count($result);$i++){

        if($result[$i]["valor_glosa_parcial"]>'0,00'){
            $valorOriginal = str_replace("," , "." , str_replace("." , "" , $result[$i]["valor_glosa_parcial"]));
        }
        else{
            $valorOriginal = str_replace("," , "." , str_replace("." , "" , $result[$i]["valor_nota"]));
        }

        switch($result[$i]["categoria_id"]){
            case 1:
                $totalGlosaCusteio = floatval($totalGlosaCusteio) + floatval($valorOriginal);
            break;
            case 2:
                $totalGlosaRH = floatval($totalGlosaRH) + floatval($valorOriginal);
            break;
            case 3:
                $totalGlosaTerceiros = floatval($totalGlosaTerceiros) + floatval($valorOriginal);
            break;
        }

    }


    $valorCusteioOriginal = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["custeio_previsto"]));
    $valorRHOriginal = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["recursos_humanos_previsto"]));
    $valorTerceirosOriginal = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["servicos_terceiros_previsto"]));

    $valorCusteioExecutado = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["custeio_executado"]));
    $valorRHExecutado = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["recursos_humanos_executado"]));
    $valorTerceirosExecutado = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["servicos_terceiros_executado"]));

    $valorCusteioPrevistoFixo = str_replace("," , "." , str_replace("." , "" , $repasse[0]["executora_valor_previsto_custeio"]));
    $valorRHPrevistoFixo = str_replace("," , "." , str_replace("." , "" , $repasse[0]["executora_valor_previsto_rh"]));
    $valorTerceirosPrevistoFixo = str_replace("," , "." , str_replace("." , "" , $repasse[0]["executora_valor_previsto_terceiros"]));

    $valorCusteio = (floatval($valorCusteioOriginal) - floatval($valorCusteioExecutado)) - floatval($totalGlosaCusteio);
    $valorRH = (floatval($valorRHOriginal) - floatval($valorRHExecutado)) - floatval($totalGlosaRH);
    $valorTerceiros = (floatval($valorTerceirosOriginal) - floatval($valorTerceirosExecutado)) - floatval($totalGlosaTerceiros);

    $valorPrevistoCusteio = floatval($valorCusteio) + floatval($valorCusteioPrevistoFixo);
    $valorPrevistoRH = floatval($valorRH) + floatval($valorRHPrevistoFixo);
    $valorPrevistoTerceiros = floatval($valorTerceiros) + floatval($valorTerceirosPrevistoFixo);

    $totalGlosaCusteio = number_format($totalGlosaCusteio,2,",",".");
    $totalGlosaRH = number_format($totalGlosaRH,2,",",".");
    $totalGlosaTerceiros = number_format($totalGlosaTerceiros,2,",",".");

    $totalCusteio = number_format($valorCusteio,2,",",".");
    $totalRH = number_format($valorRH,2,",",".");
    $totalTerceiros = number_format($valorTerceiros,2,",",".");

    $totalPrevistoCusteio = number_format($valorPrevistoCusteio,2,",",".");
    $totalPrevistoRH = number_format($valorPrevistoRH,2,",",".");
    $totalPrevistoTerceiros = number_format($valorPrevistoTerceiros,2,",",".");

    $dados = json_decode($result);

    $dados->{'totalCusteioPrevisto'} = $resultCabecalho[0]["custeio_previsto"];
    $dados->{'totalRHPrevisto'} = $resultCabecalho[0]["recursos_humanos_previsto"];
    $dados->{'totalTerceirosPrevisto'} = $resultCabecalho[0]["servicos_terceiros_previsto"];

    $dados->{'saldoCusteio'} = $resultCabecalho[0]["custeio_executado"];
    $dados->{'saldoRH'} = $resultCabecalho[0]["recursos_humanos_executado"];
    $dados->{'saldoTerceiros'} = $resultCabecalho[0]["servicos_terceiros_executado"];

    $dados->{'totalGlosaCusteio'} = $totalGlosaCusteio;
    $dados->{'totalGlosaRH'} = $totalGlosaRH;
    $dados->{'totalGlosaTerceiros'} = $totalGlosaTerceiros;

    $dados->{'totalCusteio'} = $totalCusteio;
    $dados->{'totalRH'} = $totalRH;
    $dados->{'totalTerceiros'} = $totalTerceiros;

    $dados->{'previstoFixoCusteio'} = $repasse[0]["executora_valor_previsto_custeio"];
    $dados->{'previstoFixoRH'} = $repasse[0]["executora_valor_previsto_rh"];
    $dados->{'previstoFixoTerceiros'} = $repasse[0]["executora_valor_previsto_terceiros"];

    $dados->{'totalPrevistoCusteio'} = $totalPrevistoCusteio;
    $dados->{'totalPrevistoRH'} = $totalPrevistoRH;
    $dados->{'totalPrevistoTerceiros'} = $totalPrevistoTerceiros;

    $result = json_encode($dados);

}
else{
    
    $dados = json_decode($resultCabecalho);
    $dados->{'totalCusteioPrevisto'} = "0,00";
    $dados->{'totalRHPrevisto'} = "0,00";
    $dados->{'totalTerceirosPrevisto'} = "0,00";
    $dados->{'saldoCusteio'} = "0,00";
    $dados->{'saldoRH'} = "0,00";
    $dados->{'saldoTerceiros'} = "0,00";
    $dados->{'totalGlosaCusteio'} = "0,00";
    $dados->{'totalGlosaRH'} = "0,00";
    $dados->{'totalGlosaTerceiros'} = "0,00";
    $dados->{'totalCusteio'} = "0,00";
    $dados->{'totalRH'} = "0,00";
    $dados->{'totalTerceiros'} = "0,00";
    
    $dados->{'previstoFixoCusteio'} = $repasse[0]["executora_valor_previsto_custeio"];
    $dados->{'previstoFixoRH'} = $repasse[0]["executora_valor_previsto_rh"];
    $dados->{'previstoFixoTerceiros'} = $repasse[0]["executora_valor_previsto_terceiros"];
    $dados->{'totalPrevistoCusteio'} = $repasse[0]["executora_valor_previsto_custeio"];
    $dados->{'totalPrevistoRH'} = $repasse[0]["executora_valor_previsto_rh"];
    $dados->{'totalPrevistoTerceiros'} = $repasse[0]["executora_valor_previsto_terceiros"];
    $result = json_encode($dados);

}

echo $result;