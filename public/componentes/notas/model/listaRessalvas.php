<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$campos = 'a.* , b.* , b.data_cadastro as data_ressalva , c.usuario_nome , d.*';
$from = 'rec_notas_fiscais a';
$innerJoin[] = 'left join rec_notas_ressalvas b on a.nota_fiscal_id = b.nota_fiscal_id';
$innerJoin[] = 'left join rec_usuarios c on b.usuario_id = c.usuario_id';
$innerJoin[] = 'left join rec_prestacoes d on a.prestacao_id = d.prestacao_id';

$sistema->innerJoin($campos,$from,$innerJoin,'a.nota_fiscal_id = ' . $_POST["id"],'','');
$result = $sistema->getResult();

    if($result[0]["ressalva_id"]>0){

        $dataRessalva = $sistema->convertData($result[0]["data_ressalva"]) . " às " . substr($result[0]["data_ressalva"],11,5) . ' por ' . utf8_encode($result[0]["usuario_nome"]);

        echo '<div class="alert alert-secondary" role="alert">';
        echo '  <h4 class="alert-heading text-danger">Ressalva</h4>';
        echo '  <p>'.utf8_encode($result[0]["ressalva_descricao"]).'</p>';
        echo '  <hr>';
        echo '  <p class="mb-0">'.$dataRessalva.'</p>';
        echo '</div>';
        
    }