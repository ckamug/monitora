<?php
include "../../../../classes/sistema.php";
session_start();

$repasse = strval($_POST["txtValorRepasse"]);
$rh = strval($_POST["txtValorRecursosHumanos"]);
$custeio = strval($_POST["txtValorCusteio"]);
$terceiros = strval($_POST["txtValorServicosTerceiros"]);

$sistema = new Sistema();

if($_POST["tipo"]=='celebrante'){

    $sistema->select("rec_celebrantes","celebrante_id","celebrante_status=1");
    $result = $sistema->getResult();

    $dados["celebrante_id"] = intval($result[0]["celebrante_id"]);
    $where = "celebrante_id = " . intval($result[0]["celebrante_id"]);
}
else{
    $dados["executora_id"] = intval($_POST["id"]);
    $where = "executora_id = " . intval($_POST["id"]);
}

$data = explode("/" , $_POST["txtMesReferencia"]);
$where .= " AND tipo_repasse_id = " . $_POST["slcTiposRepasse"] . " AND cabecalho_mes_referencia = '" . $data[1] . "-" . $data[0] . "'";

//$sistema->debug=true;
$sistema->select("rec_cabecalhos","cabecalho_id",$where);
$result1 = $sistema->getResult();

if(count($result1)>0){
    echo 0;
}
else{

    $dados["tipo_repasse_id"] = intval($_POST["slcTiposRepasse"]);
    $dados["cabecalho_mes_referencia"] = $data[1] . "-" . $data[0];
    $dados["valor_repasse"] = str_replace("R$" , "" , $repasse);
    $dados["valor_nao_executado"] = str_replace("R$" , "" , $repasse);
    $dados["recursos_humanos_previsto"] = str_replace("R$" , "" , $rh);
    $dados["custeio_previsto"] = str_replace("R$" , "" , $custeio);
    $dados["servicos_terceiros_previsto"] = str_replace("R$" , "" , $terceiros);

    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d h:i:s");

    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->insert('rec_cabecalhos',$dados);

    echo 1;

}