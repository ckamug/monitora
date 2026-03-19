<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$sistema->select("rec_prestacoes","*","prestacao_id = " . base64_decode($_POST["prestacao"]));
$res_osc = $sistema->getResult();

if($res_osc[0]["executora_id"]==0){
    $where = "tipo_repasse_id = " .$res_osc[0]["tipo_prestacao_id"]. " AND cabecalho_mes_referencia = '" . $res_osc[0]["prestacao_mes_referencia"] . "' AND celebrante_id = " . $res_osc[0]["celebrante_id"];
}else{
    $where = "tipo_repasse_id = " .$res_osc[0]["tipo_prestacao_id"]. " AND cabecalho_mes_referencia = '" . $res_osc[0]["prestacao_mes_referencia"] . "' AND executora_id = " . $res_osc[0]["executora_id"];
}

//$sistema->debug=true;
$sistema->select("rec_cabecalhos","*",$where);
$result = $sistema->getResult();

if($res_osc[0]["tipo_prestacao_id"]!=4){ // Se tipo de prestação for diferente de Implantação

    switch($_POST["rubrica"]){
        case 1:
            $rubricaPrevisto = $result[0]["custeio_previsto"];
            $rubricaExecutado = $result[0]["custeio_executado"];
        break;
        case 2:
            $rubricaPrevisto = $result[0]["recursos_humanos_previsto"];
            $rubricaExecutado = $result[0]["recursos_humanos_executado"];
        break;
        case 3:
            $rubricaPrevisto = $result[0]["servicos_terceiros_previsto"];
            $rubricaExecutado = $result[0]["servicos_terceiros_executado"];
        break;
    }

    // Função para converter para número
    function converterParaFloat($valor) {
        // Remove os pontos e troca vírgula por ponto
        $numero = str_replace(['.', ','], ['', '.'], $valor);
        return (float)$numero;
    }

    $v1 = converterParaFloat($rubricaExecutado);
    $v2 = converterParaFloat($_POST["valor"]);
    $v3 = converterParaFloat($rubricaPrevisto);
    $v4 = converterParaFloat($_POST["valorOriginal"]);

    // Subtrai e compara
    if($v4>0){
        $soma = ($v1-$v4) + $v2;
    }else{
        $soma = $v1 + $v2;
    }

    if(base64_decode($_SESSION['usr']==1)){

        echo "Previsto:" . $rubricaPrevisto . "<br>";
        echo "Executado:" . $rubricaExecutado . "<br>";
        echo "V1:" . $v1 . "<br>";
        echo "V2:" . $v2 . "<br>";
        echo "V3:" . $v3 . "<br>";
        echo "V4:" . $v4 . "<br>";
        echo "Soma:" . $soma . "<br>";
        exit;

    }

    if ($soma > ($v3+0.009)) {
        echo 1;
    } else{
        echo 0;
    }
}
else{
    echo 0;
}