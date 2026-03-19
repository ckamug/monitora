<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
if(base64_decode($_SESSION["usr"])==1){
    //$sistema->debug=true;
}

$campos = 'a.* , b.usuario_id , b.perfil_id , b.perfil_vinculo_id , c.nota_apontamento_id , c.nota_apontamento_descricao , c.data_cadastro as data_apontamento , d.nota_justificativa_id , d.nota_justificativa_descricao , d.data_cadastro as data_justificativa , e.nota_status_descricao , f.prestacao_id , f.tipo_prestacao_id , f.executora_id , f.celebrante_id , f.prestacao_disponibilizada , f.prestacao_status , g.motivo_glosa_id , g.motivo_glosa_descricao , g.valor_glosa_parcial , g.data_cadastro as data_motivo_glosa , h.usuario_nome as nome_apontamento , i.usuario_nome as nome_justificativa , j.usuario_nome as nome_glosa';
$from = 'rec_notas_fiscais a';
$innerJoin[] = 'inner join rec_usuarios b on a.usuario_id = b.usuario_id';
$innerJoin[] = 'left join rec_notas_apontamentos c on a.nota_fiscal_id = c.nota_fiscal_id';
$innerJoin[] = 'left join rec_notas_justificativas d on c.nota_apontamento_id = d.nota_apontamento_id';
$innerJoin[] = 'inner join rec_notas_status e on a.nota_status = e.nota_status_id';
$innerJoin[] = 'inner join rec_prestacoes f on a.prestacao_id = f.prestacao_id';
$innerJoin[] = 'left join rec_notas_motivos_glosa g on a.nota_fiscal_id = g.nota_fiscal_id';
$innerJoin[] = 'left join rec_usuarios h on c.usuario_id = h.usuario_id';
$innerJoin[] = 'left join rec_usuarios i on d.usuario_id = i.usuario_id';
$innerJoin[] = 'left join rec_usuarios j on g.usuario_id = j.usuario_id';

$sistema->innerJoin($campos,$from,$innerJoin,'a.nota_fiscal_id = ' . base64_decode($_POST["id"]),'','');
$result = $sistema->resultToJSON();

$dados = json_decode($result);

if($dados->{'executora_id'}>0){
    $arquivo = "anexos/" . $dados->{'prestacao_id'} . "/" . $dados->{'nota_fiscal_id'} . ".pdf";
}
else{
    $arquivo = "anexos/" . $dados->{'prestacao_id'} . "/" . $dados->{'nota_fiscal_id'} . ".pdf";
}

if(file_exists( $arquivo )){
    $dados->{'arquivo'} = 1;
}
else{
    $dados->{'arquivo'} = 0;
}

$dados->{'nota_apontamento_descricao'} = nl2br($dados->{'nota_apontamento_descricao'});
$dados->{'nota_justificativa_descricao'} = nl2br($dados->{'nota_justificativa_descricao'});
$dados->{'motivo_glosa_descricao'} = nl2br($dados->{'motivo_glosa_descricao'});

$dados->{'data_apontamento'} = $sistema->convertData($dados->{'data_apontamento'}) . " às " . substr($dados->{'data_apontamento'},11,5) . ' por ' . utf8_encode($dados->{'nome_apontamento'});
$dados->{'data_justificativa'} = $sistema->convertData($dados->{'data_justificativa'}) . " às " . substr($dados->{'data_justificativa'},11,5) . ' por ' . utf8_encode($dados->{'nome_justificativa'});
$dados->{'data_motivo_glosa'} = $sistema->convertData($dados->{'data_motivo_glosa'}) . " às " . substr($dados->{'data_motivo_glosa'},11,5) . ' por ' . utf8_encode($dados->{'nome_glosa'});

$sistema->select("rec_perfis","perfil_descricao","perfil_id = 1");
$perfilAnalise = $sistema->getResult();
$dados->{'perfil_analise_descricao'} = count($perfilAnalise) > 0 ? utf8_encode($perfilAnalise[0]["perfil_descricao"]) : "";
$dados->{'perfil_logado_id'} = $_SESSION["pf"];
$dados->{'usuario_logado_id'} = base64_decode($_SESSION["usr"]);

$result = json_encode($dados);

echo $result;
