<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

if($_SESSION['pf']==2){
    $dados["celebrante_id"] = intval($_SESSION["pfv"]);
    $where = "celebrante_id = " . $_SESSION["pfv"];
}
else{
    $dados["executora_id"] = intval($_SESSION["pfv"]);
    $where = "executora_id = " . $_SESSION["pfv"];
}

$data = explode("/" , $_POST["txtMesReferencia"]);

$where .= " AND tipo_prestacao_id = " . $_POST["slcTiposPrestacao"] . " AND prestacao_mes_referencia = '" . $data[1] . "-" . $data[0] . "'";

$sistema->select("rec_prestacoes","prestacao_id",$where);
$result = $sistema->getResult();

if(count($result)>0){
    echo 0;
}
else{

    $dados["tipo_prestacao_id"] = intval($_POST["slcTiposPrestacao"]);
    $dados["prestacao_mes_referencia"] = $data[1] . "-" . $data[0];

    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");

    //$sistema->debug=true;
    $sistema->insert('rec_prestacoes',$dados);

    echo 1;
}