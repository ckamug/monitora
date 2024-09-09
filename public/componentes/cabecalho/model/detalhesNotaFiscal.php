<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$campos = 'a.* , b.usuario_id , b.perfil_id , b.perfil_vinculo_id';
$from = 'rec_notas_fiscais a';
$innerJoin[] = 'inner join rec_usuarios b on a.usuario_id = b.usuario_id';

$sistema->innerJoin($campos,$from,$innerJoin,'nota_fiscal_id = ' . base64_decode($_POST["id"]),'','');
$result = $sistema->resultToJSON();


$dados = json_decode($result);

$arquivo = "anexos/" . $dados->{'perfil_id'} . "_" . $dados->{'perfil_vinculo_id'} . "/" . $dados->{'numero_nota_fiscal'} . ".pdf";

if(file_exists( $arquivo )){
    $dados->{'arquivo'} = 1;
}
else{
    $dados->{'arquivo'} = 0;
}

$result = json_encode($dados);

echo $result;