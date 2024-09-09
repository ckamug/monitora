<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

//$sistema->debug=true;
$sistema->select("rec_prestacoes","*","prestacao_id = " . base64_decode($_POST["id"]));
$resultPrestacao = $sistema->getResult();

if(base64_decode($_SESSION["usr"])==1){
    //$sistema->debug=true;
}

if($resultPrestacao[0]["celebrante_id"]>0){
    $campos = 'a.* , b.tipo_repasse_descricao , c.celebrante_nome_fantasia';
    $from = 'rec_cabecalhos a';
    $innerJoin[] = 'inner join rec_tipos_repasse b on a.tipo_repasse_id = b.tipo_repasse_id';
    $innerJoin[] = 'inner join rec_celebrantes c on a.celebrante_id = c.celebrante_id';
}
else{
    $campos = 'a.* , b.tipo_repasse_descricao , c.executora_vagas , c.executora_nome_fantasia';
    $from = 'rec_cabecalhos a';
    $innerJoin[] = 'inner join rec_tipos_repasse b on a.tipo_repasse_id = b.tipo_repasse_id';
    $innerJoin[] = 'inner join rec_executoras c on a.executora_id = c.executora_id';
}

if($resultPrestacao[0]["celebrante_id"]>0){
    $where = "a.celebrante_id = " . $resultPrestacao[0]["celebrante_id"] . " AND cabecalho_mes_referencia = '" . $resultPrestacao[0]["prestacao_mes_referencia"] . "' AND a.tipo_repasse_id = " . $resultPrestacao[0]["tipo_prestacao_id"];
}
else{
    $where = "a.executora_id = " . $resultPrestacao[0]["executora_id"] . " AND cabecalho_mes_referencia = '" . $resultPrestacao[0]["prestacao_mes_referencia"] . "' AND a.tipo_repasse_id = " . $resultPrestacao[0]["tipo_prestacao_id"];
}

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$result = $sistema->resultToJSON();

$dados = json_decode($result);
$dados->{'cabecalho_mes_referencia'} = substr($sistema->convertData($dados->{'cabecalho_mes_referencia'}),1);
if($resultPrestacao[0]["celebrante_id"]>0){
    $dados->{'executora_vagas'} = "--";
    $dados->{'localNome'} = $dados->{'celebrante_nome_fantasia'};
}
else{
    $dados->{'localNome'} = $dados->{'executora_nome_fantasia'};
}
$dados->{'perfil'} = $_SESSION["pf"];
$dados->{'logado'} = base64_decode($_SESSION["usr"]);
$dados->{'tipoPrestacao'} = $resultPrestacao[0]["tipo_prestacao_id"];
$dados->{'prestacaoDisponibilizada'} = $resultPrestacao[0]["prestacao_disponibilizada"];

$result = json_encode($dados);

echo $result;