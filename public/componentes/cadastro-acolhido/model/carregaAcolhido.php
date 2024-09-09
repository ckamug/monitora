<?php
include "../../../../classes/sistema.php";
session_start();

if(is_numeric($_POST["id"])){
    $id = $_POST["id"];
}
else{
    $id = base64_decode($_POST["id"]);
}

if(!$id){
    $id = 0;
}

$sistema = new Sistema();
$campos = 'a.* , a.acolhido_id as acolhido , b.* , b.status_vaga_id as id_status_vaga , b.data_cadastro as data_cadastro_solicitacao , c.* , d.executora_razao_social , d.executora_servicos_id';
$from = 'rec_acolhidos a';
$innerJoin[] = 'left join rec_solicitacoes_vagas b on a.acolhido_id = b.acolhido_id';
$innerJoin[] = 'left join rec_status_vaga c on b.status_vaga_id = c.status_vaga_id';
$innerJoin[] = 'left join rec_executoras d on b.executora_id = d.executora_id';

$where = 'a.acolhido_id = ' . $id;

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$result = $sistema->resultToJSON();

$dados = json_decode($result);

if($dados->{'executora_servicos_id'}!=''){
    $servico = explode(",",$dados->{'executora_servicos_id'});
    //$sistema->debug=true;
    $sistema->select("rec_servicos","*","servico_id = " . $servico[0] );
    $resServico = $sistema->getResult();
    $dados->{'servico'} = utf8_encode($resServico[0]["servico_descricao"]);
}

$dados->{'acolhido_data_nascimento'} = $sistema->convertData($dados->{'acolhido_data_nascimento'});
$dados->{'data_solicitacao_vaga'} = $sistema->convertData($dados->{'data_cadastro_solicitacao'});
$dados->{'perfil_logado'} = $_SESSION["pf"];
$dados->{'local_logado'} = $_SESSION["pfv"];

$result = json_encode($dados);

echo $result;