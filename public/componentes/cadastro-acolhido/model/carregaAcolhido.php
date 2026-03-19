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
$innerJoin[] = 'left join rec_solicitacoes_vagas b on b.solicitacao_vaga_id = (
    select b2.solicitacao_vaga_id
    from rec_solicitacoes_vagas b2
    where b2.acolhido_id = a.acolhido_id
      and b2.status_registro = 1
    order by b2.data_cadastro desc, b2.solicitacao_vaga_id desc
    limit 1
)';
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

// verifica se existe entrada ativa
$sistema->select(
    "rec_acolhidos_entradas",
    "COUNT(*) as total_ativas",
    "acolhido_id = " . $id . " AND status = 1"
);
$resAtivas = $sistema->getResult();
$dados->{'tem_entrada_ativa'} = (!empty($resAtivas) && is_array($resAtivas) && intval($resAtivas[0]["total_ativas"]) > 0);

// verifica se existe solicitacao ativa (aguardando/ reservada)
$sistema->select(
    "rec_solicitacoes_vagas",
    "COUNT(*) as total_solicitacoes",
    "acolhido_id = " . $id . " AND status_registro = 1 AND status_vaga_id IN (1,2,5)"
);
$resSolic = $sistema->getResult();
$dados->{'solicitacao_ativa'} = (!empty($resSolic) && is_array($resSolic) && intval($resSolic[0]["total_solicitacoes"]) > 0);

$result = json_encode($dados);

echo $result;
