<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug = true;
$campos = 'a.* , b.*';
$from = 'rec_executoras a';
$innerJoin[] = 'inner join tbl_cidades b on a.cidade_id = b.cidade_id';
$where = 'a.executora_id = ' . $_POST["id"];

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
$result = $sistema->getResult();

//$sistema->debug=true;
$sistema->select("rec_solicitacoes_vagas","count(solicitacao_vaga_id) as total","status_vaga_id <> 4 and status_registro = 1 and executora_id = " . $result[0]["executora_id"],"executora_id");
$res_solicitacoes = $sistema->getResult();

$vagasDisponiveis = $result[0]["executora_vagas"] - $res_solicitacoes[0]["total"];

if($vagasDisponiveis<=0){
    $qtdVagas = 0;
    $estilo = "text-danger";
    if($vagasDisponiveis==-1){
        $aviso = '<span>('.($vagasDisponiveis * -1).' acolhido em fila de espera)</span>';
    }
    else{
        $aviso = '<span>('.($vagasDisponiveis * -1).' acolhidos em fila de espera)</span>';
    }
}
else{
    $qtdVagas = $vagasDisponiveis;
    $estilo = "text-success";
    $aviso = "";
}

echo '<div class="col-md-12 mt-2 '.$estilo.'"><strong>Vagas disponíveis:</strong> '.$qtdVagas . " " . $aviso;
echo '<div class="col-md-12 text-secondary mt-2"><strong>Gênero:</strong>  '.substr($result[0]["executora_generos"],0,-2).'</div>';
echo '<div class="col-md-12 text-secondary mt-2 mb-3"><strong>Endereço:</strong> '.utf8_encode($result[0]["executora_endereco"]).', '.$result[0]["executora_numero"].' - '.utf8_encode($result[0]["executora_bairro"]).' - '.utf8_encode($result[0]["cidade_descricao"]).'</div>';