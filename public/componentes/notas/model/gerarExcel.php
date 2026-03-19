<?php

include "../../../../classes/sistema.php";

$sistema = new Sistema();
//$sistema->debug=true;
$campos = 'a.* , b.categoria_descricao , c.subcategoria_descricao , d.* , d.prestacao_id as prestacao , d.executora_id as executora , d.celebrante_id as celebrante , e.* , f.*';
$from = 'rec_notas_fiscais a';
$innerJoin[] = 'inner join rec_categorias b on a.categoria_id = b.categoria_id';
$innerJoin[] = 'inner join rec_subcategorias c on a.subcategoria_id = c.subcategoria_id';
$innerJoin[] = 'inner join rec_prestacoes d on a.prestacao_id = d.prestacao_id';
$innerJoin[] = 'left join rec_executoras e on d.executora_id = e.executora_id';
$innerJoin[] = 'left join rec_celebrantes f on d.celebrante_id = f.celebrante_id';

$where = "a.prestacao_id = " . base64_decode($_POST["prestacao"]) . " AND a.nota_status != 5";

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.data_nota_fiscal');
$result = $sistema->getResult();

//VALIDAÇÃO DE USUÁRIO
if($_SESSION["pf"]==4){
    $sistema = new Sistema();
    //$sistema->debug=true;
    $campos = 'a.executora_id , b.usuario_id';
    $from = 'rec_prestacoes a';
    $innerJoin[] = 'inner join rec_usuarios_vinculos b on a.executora_id = b.executora_id';

    $where = "a.prestacao_id = " . $result[0]["prestacao"] . " AND b.usuario_id = " . base64_decode($_SESSION["usr"]) . " AND a.executora_id = " . $_SESSION["pfv"];

    $sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
    $result = $sistema->getResult();

    if(count($result)==0){
        echo "<script>window.close()</script>";
    }

}

if($result[0]["celebrante_id"]>0){
    $entidade = str_replace("/","-",utf8_encode($result[0]["celebrante_razao_social"]));
}
else{
    $entidade = str_replace("/","-",utf8_encode($result[0]["executora_razao_social"]));
}

if ( count($result) ) {
    
    // Gera arquivo CSV
    $fp = fopen("planilhas/" . $entidade.".csv", "w"); // o "a" indica que o arquivo será sobrescrito sempre que esta função for executada.
    $escreve = fwrite($fp, "Data da NF;Detalhes do Documento;Categoria;Subcategoria;Valor(R$);Data do Pagamento");
    
    foreach($result as $registro) 
    { 		  			
        $escreve = fwrite($fp, "\n$registro[data_nota_fiscal];$registro[numero_nota_fiscal];$registro[categoria_descricao];$registro[subcategoria_descricao];$registro[valor_nota];$registro[data_pagamento]");
    }  
    
    fclose($fp);

    echo $entidade.".csv";
    
    
}