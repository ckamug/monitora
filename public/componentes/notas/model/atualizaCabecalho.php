<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$campos = 'a.* , b.*';
$from = 'rec_notas_fiscais a';
$innerJoin[] = 'inner join rec_prestacoes b on a.prestacao_id = b.prestacao_id';
$where = "a.nota_fiscal_id = " . $_POST["id"];

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$result = $sistema->getResult();


if($result[0]["tipo_prestacao_id"]!=4){ // SE O TIPO DA PRESTAÇÃO FOR DIFERENTE DE IMPLEMENTAÇÃO

    if($result[0]["celebrante_id"]>0){
        $where = "celebrante_id = " . $result[0]["celebrante_id"] . " AND cabecalho_mes_referencia = '" . $result[0]["prestacao_mes_referencia"] . "' ";    
    }
    else{
        $where = "executora_id = " . $result[0]["executora_id"] . " AND cabecalho_mes_referencia = '" . $result[0]["prestacao_mes_referencia"] . "'";
    }

    $where .= " AND tipo_repasse_id = " . $result[0]["tipo_prestacao_id"];

    $sistema->select("rec_cabecalhos","*",$where);
    $resCabecalho = $sistema->getResult();

    $valorNotaOriginal = str_replace("," , "." , str_replace("." , "" , $result[0]["valor_nota"]));
    $valorExecutadoOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["valor_executado"]));
    $valorNaoExecutadoOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["valor_nao_executado"]));


    if(intval($_POST["status"])==0){

        $valorExecutado = floatval($valorExecutadoOriginal) + floatval($valorNotaOriginal);
        $valorNaoExecutado = floatval($valorNaoExecutadoOriginal) - floatval($valorNotaOriginal);

        $dadosValores["valor_executado"] =  number_format($valorExecutado,2,",",".");
        $dadosValores["valor_nao_executado"] =  number_format($valorNaoExecutado,2,",",".");

        switch($result[0]["categoria_id"]){
            case '1':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["custeio_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + floatval($valorNotaOriginal);
                $dadosValores["custeio_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '2':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["recursos_humanos_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + floatval($valorNotaOriginal);
                $dadosValores["recursos_humanos_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '3':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["servicos_terceiros_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + floatval($valorNotaOriginal);
                $dadosValores["servicos_terceiros_executado"] =  number_format($valorRubrica,2,",",".");
            break;
        }
        
    }
    /***  OS SCRIPTS DESABILITADOS ABAIXO FORAM SOLICITADOS PELA COED - VALORES GLOSADOS SERÃO CONSIDERADOS EXECUTADOS /** */
    else if(intval($_POST["status"])==4 OR intval($_POST["status"])==7){ //NOTA GLOSADA

        
        if($_POST["status"]==7){
            $valorNotaOriginal = str_replace("," , "." , str_replace("." , "" , str_replace("R$ " , "" , $_POST['valorGlosa']))); // VALOR DO ÍTEM A SER GLOSADO
        }
        else{
            $valorNotaOriginal = str_replace("," , "." , str_replace("." , "" , $result[0]["valor_nota"]));
        }

        //$valorNaoExecutado = floatval($valorNaoExecutadoOriginal) + floatval($valorNotaOriginal);
        //$valorExecutado = floatval($valorExecutadoOriginal) - floatval($valorNotaOriginal);

        //$dadosValores["valor_executado"] =  number_format($valorExecutado,2,",",".");
        //$dadosValores["valor_nao_executado"] =  number_format($valorNaoExecutado,2,",",".");

        $valorGlosadoOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["valor_glosado"]));
        $valorGlosado = floatval($valorGlosadoOriginal) + floatval($valorNotaOriginal);

        $dadosValores["valor_glosado"] =  number_format($valorGlosado,2,",",".");
        //$dadosValores["valor_nao_executado"] =  number_format($valorNaoExecutado,2,",",".");
        /*
        switch($result[0]["categoria_id"]){
            case '1':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["custeio_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginal);
                $dadosValores["custeio_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '2':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["recursos_humanos_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginal);
                $dadosValores["recursos_humanos_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '3':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["servicos_terceiros_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginal);
                $dadosValores["servicos_terceiros_executado"] =  number_format($valorRubrica,2,",",".");
            break;
        }
        */

    }
    else if(intval($_POST["status"])==5){

        $valorNaoExecutado = floatval($valorNaoExecutadoOriginal) + floatval($valorNotaOriginal);
        $valorExecutado = floatval($valorExecutadoOriginal) - floatval($valorNotaOriginal);

        $dadosValores["valor_executado"] =  number_format($valorExecutado,2,",",".");
        $dadosValores["valor_nao_executado"] =  number_format($valorNaoExecutado,2,",",".");

        switch($result[0]["categoria_id"]){
            case '1':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["custeio_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginal);
                $dadosValores["custeio_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '2':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["recursos_humanos_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginal);
                $dadosValores["recursos_humanos_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '3':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["servicos_terceiros_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginal);
                $dadosValores["servicos_terceiros_executado"] =  number_format($valorRubrica,2,",",".");
            break;
        }

    }
    else if(intval($_POST["status"])==97){ //NOTA COM ALTERAÇÃO DE VALOR

        $valorNotaOriginalAnterior = str_replace("," , "." , str_replace("." , "" , strval($_POST["valor"])));
        
        $valorExecutado = floatval($valorExecutadoOriginal) + (floatval($valorNotaOriginal) - floatval($valorNotaOriginalAnterior));
        $valorNaoExecutado = (floatval($valorNaoExecutadoOriginal) - floatval($valorNotaOriginal)) + floatval($valorNotaOriginalAnterior);
        
        $dadosValores["valor_executado"] =  number_format($valorExecutado,2,",",".");
        $dadosValores["valor_nao_executado"] =  number_format($valorNaoExecutado,2,",",".");

        switch($result[0]["categoria_id"]){
            case '1':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["custeio_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + (floatval($valorNotaOriginal) - floatval($valorNotaOriginalAnterior));
                $dadosValores["custeio_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '2':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["recursos_humanos_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + (floatval($valorNotaOriginal)-floatval($valorNotaOriginalAnterior));
                $dadosValores["recursos_humanos_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '3':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["servicos_terceiros_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + (floatval($valorNotaOriginal)-floatval($valorNotaOriginalAnterior));
                $dadosValores["servicos_terceiros_executado"] =  number_format($valorRubrica,2,",",".");
            break;
        }

    }
    else if(intval($_POST["status"])==98){ //NOTA COM ALTERAÇÃO DE RUBRICA

        switch($result[0]["categoria_id"]){
            case '1':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["custeio_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + floatval($valorNotaOriginal);
                $dadosValores["custeio_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '2':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["recursos_humanos_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + floatval($valorNotaOriginal);
                $dadosValores["recursos_humanos_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '3':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["servicos_terceiros_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + floatval($valorNotaOriginal);
                $dadosValores["servicos_terceiros_executado"] =  number_format($valorRubrica,2,",",".");
            break;
        }

        switch($_POST["categoria"]){
            case '1':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["custeio_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginal);
                $dadosValores["custeio_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '2':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["recursos_humanos_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginal);
                $dadosValores["recursos_humanos_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '3':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["servicos_terceiros_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginal);
                $dadosValores["servicos_terceiros_executado"] =  number_format($valorRubrica,2,",",".");
            break;
        }

    }
    else if(intval($_POST["status"])==99){ //NOTA COM ALTERAÇÃO DE VALOR

        $valorNotaOriginalAnterior = str_replace("," , "." , str_replace("." , "" , strval($_POST["valor"])));
        
        $valorExecutado = floatval($valorExecutadoOriginal) + floatval($valorNotaOriginal) - floatval($valorNotaOriginalAnterior);
        $valorNaoExecutado = floatval($valorNaoExecutadoOriginal) - floatval($valorNotaOriginal) + floatval($valorNotaOriginalAnterior);
        

        $dadosValores["valor_executado"] =  number_format($valorExecutado,2,",",".");
        $dadosValores["valor_nao_executado"] =  number_format($valorNaoExecutado,2,",",".");


        switch($result[0]["categoria_id"]){
            case '1':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["custeio_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + floatval($valorNotaOriginal);
                $dadosValores["custeio_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '2':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["recursos_humanos_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + floatval($valorNotaOriginal);
                $dadosValores["recursos_humanos_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '3':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["servicos_terceiros_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) + floatval($valorNotaOriginal);
                $dadosValores["servicos_terceiros_executado"] =  number_format($valorRubrica,2,",",".");
            break;
        }

        switch($_POST["categoria"]){
            case '1':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["custeio_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginalAnterior);
                $dadosValores["custeio_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '2':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["recursos_humanos_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginalAnterior);
                $dadosValores["recursos_humanos_executado"] =  number_format($valorRubrica,2,",",".");
            break;
            case '3':
                $valorRubricaOriginal = str_replace("," , "." , str_replace("." , "" , $resCabecalho[0]["servicos_terceiros_executado"]));
                $valorRubrica = floatval($valorRubricaOriginal) - floatval($valorNotaOriginalAnterior);
                $dadosValores["servicos_terceiros_executado"] =  number_format($valorRubrica,2,",",".");
            break;
        }

    }
    else{

    }

    //$sistema->debug=true;
    $sistema->update('rec_cabecalhos',$dadosValores,'cabecalho_id = ' . $resCabecalho[0]["cabecalho_id"]);

}

echo base64_encode($result[0]["prestacao_id"]);