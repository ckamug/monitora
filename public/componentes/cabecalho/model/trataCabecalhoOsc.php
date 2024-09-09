<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_cabecalhos","*","executora_id = 70 and tipo_repasse_id = 1");
$result = $sistema->getResult();


$repasse_rh = str_replace("," , "." , str_replace("." , "" , "32.256,07"));
$repasse_custeio = str_replace("," , "." , str_replace("." , "" , "7.550,00"));
$repasse_terceiros = str_replace("," , "." , str_replace("." , "" , "5.193,93"));


for($i=0;$i<count($result);$i++){

    unset($innerJoin);
    $glosaCusteio = 0;
    $glosaRh = 0;
    $glosaTerceiros = 0;
    
    
    // BUSCA VALORES GLOSADOS    
    $campos = 'a.* , b.* , c.*';
    $from = 'rec_notas_motivos_glosa a';
    $innerJoin[] = 'inner join rec_notas_fiscais b ON a.nota_fiscal_id = b.nota_fiscal_id';
    $innerJoin[] = 'inner join rec_prestacoes c ON b.prestacao_id = c.prestacao_id';
    
    $where = "b.nota_status <> 5 and c.executora_id = 74 and c.tipo_prestacao_id = 1 and c.prestacao_mes_referencia = '" . $result[$i]["cabecalho_mes_referencia"] . "'";

    $sistema->debug=true;
    $sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
    $glosa = $sistema->getResult();

    for($a=0;$a<count($glosa);$a++){

        if($glosa[$a]["valor_glosa_parcial"]!=''){
            $valorGlosa = str_replace("," , "." , str_replace("." , "" , $glosa[$a]["valor_glosa_parcial"]));
        }
        else{
            $valorGlosa = str_replace("," , "." , str_replace("." , "" , $glosa[$a]["valor_nota"]));
        }

        switch($glosa[$a]["categoria_id"]){
            case 1:
                $glosaCusteio = floatval($glosaCusteio) + floatval($valorGlosa);
            break;
            case 2:
                $glosaRh = floatval($glosaRh) + floatval($valorGlosa);
            break;
            case 3:
                $glosaTerceiros = floatval($glosaTerceiros) + floatval($valorGlosa);
            break;
        }

    }
    //--------------------------------------------------------------------------------------------------------------------

    /* CALCULO DO SALDO DO MES */
    
    $rh = str_replace("," , "." , str_replace("." , "" , $result[$i]["recursos_humanos_previsto"]));
    $custeio = str_replace("," , "." , str_replace("." , "" , $result[$i]["custeio_previsto"]));
    $terceiros = str_replace("," , "." , str_replace("." , "" , $result[$i]["servicos_terceiros_previsto"]));

    $rh_executado = str_replace("," , "." , str_replace("." , "" , $result[$i]["recursos_humanos_executado"]));
    $custeio_executado = str_replace("," , "." , str_replace("." , "" , $result[$i]["custeio_executado"]));
    $terceiros_executado = str_replace("," , "." , str_replace("." , "" , $result[$i]["servicos_terceiros_executado"]));

    $saldoRh = floatval($rh) - floatval($rh_executado);
    $saldoCusteio = floatval($custeio) - floatval($custeio_executado);
    $saldoTerceiros = floatval($terceiros) - floatval($terceiros_executado);

    $totalRh = floatval($rh) - floatval($rh_executado) + floatval($repasse_rh) - floatval($glosaRh);
    $totalCusteio = floatval($custeio) - floatval($custeio_executado) + floatval($repasse_custeio) - floatval($glosaCusteio);
    $totalTerceiros = floatval($terceiros) - floatval($terceiros_executado) + floatval($repasse_terceiros) - floatval($glosaTerceiros);


    echo "<br><br>RH: " . floatval($rh) ."-". floatval($rh_executado) ."+". floatval($repasse_rh) ."-". floatval($glosaRh);
    echo "<br>Custeio: " . floatval($custeio) ."-". floatval($custeio_executado) ."+". floatval($repasse_custeio) ."-". floatval($glosaCusteio);
    echo "<br>Terceiros: " . floatval($terceiros) ."-". floatval($terceiros_executado) ."+". floatval($repasse_terceiros) ."-". floatval($glosaTerceiros);




/*
echo "ID Atual: " . $result[$i]["cabecalho_id"];
echo "<br>Saldo RH: " . $saldoRh;
echo "<br>Saldo Custeio: " . $saldoCusteio;
echo "<br>Saldo Terceiros: " . $saldoTerceiros;

echo "<br><br>ID Seguinte: " . $result[$i+1]["cabecalho_id"];
echo "<br>Total RH: " . $totalRh;
echo "<br>Total Custeio: " . $totalCusteio;
echo "<br>Total Terceiros: " . $totalTerceiros;
*/

    $totalRh = number_format($totalRh,2,",",".");
    $totalCusteio = number_format($totalCusteio,2,",",".");
    $totalTerceiros = number_format($totalTerceiros,2,",",".");

    $dados["recursos_humanos_previsto"] = $totalRh;
    $dados["custeio_previsto"] = $totalCusteio;
    $dados["servicos_terceiros_previsto"] = $totalTerceiros;

    $id = $result[$i+1]["cabecalho_id"];

    if($id!=""){
        //echo "Alteração desabilitada";
        $sistema->update("rec_cabecalhos",$dados,"cabecalho_id=".$id);
        //echo $id . " - OK<br>";
    }

}