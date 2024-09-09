<?php
include "../../../../classes/sistema.php";

$sistema = new Sistema();


$sistema->select("rec_prestacoes","prestacao_id","executora_id > 0 AND prestacao_mes_referencia = '2023-04'");
$retorno = $sistema->getResult();


$html = "
    <style>

        table,th,td{
            border: 1px solid black;
            border-collapse: collapse;
            text-align:center;
            padding: 3px;
            font-size: 14px;
        }

    </style>

    <table align='center' width='100%'>
        <thead>
            <tr bgcolor='#EEE'><th colspan='5'>DEMONSTRATIVO GERAL DE DESPESAS</th></tr>
            <tr>
                <th>ENTIDADE</th>
                <th>TOTAL EXECUTADO NOTAS</th>
                <th>TOTAL EXECUTADO RUBRICAS</th>
            </tr>
        </thead>
        <tbody>
    ";


for($i=0;$i<count($retorno);$i++){


    //$sistema->debug=true;
    $campos = 'a.* , b.categoria_descricao , c.subcategoria_descricao , d.* , d.prestacao_id as prestacao , d.executora_id as executora , d.celebrante_id as celebrante , e.* , f.* , g.executora_responsavel_nome';
    $from = 'rec_notas_fiscais a';
    $innerJoin[] = 'inner join rec_categorias b on a.categoria_id = b.categoria_id';
    $innerJoin[] = 'inner join rec_subcategorias c on a.subcategoria_id = c.subcategoria_id';
    $innerJoin[] = 'inner join rec_prestacoes d on a.prestacao_id = d.prestacao_id';
    $innerJoin[] = 'left join rec_executoras e on d.executora_id = e.executora_id';
    $innerJoin[] = 'left join rec_celebrantes f on d.celebrante_id = f.celebrante_id';
    $innerJoin[] = 'left join rec_executoras_responsaveis g on d.executora_id = g.executora_id';

    $where = "a.prestacao_id = " . $retorno[$i]["prestacao_id"] . " AND a.nota_status != 5 AND a.nota_status != 0";

    $sistema->innerJoin($campos,$from,$innerJoin,$where,'a.nota_fiscal_id','a.data_nota_fiscal');
    $result = $sistema->getResult();

    if($result[0]["executora_servicos_id"]!=""){
        //$sistema->debug=true;
        $sistema->select("rec_servicos","servico_descricao","servico_id IN (".substr(str_replace(" " , "" , $result[0]["executora_servicos_id"]),0,-1).")","","servico_descricao");
        $servicos = $sistema->getResult();
        $servicosDescricao = "";
    }
    else{
        $servicosDescricao = "Nenhum serviço definido. ";
    }

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

    $sistema = new Sistema();
    //$sistema->debug=true;

    if($result[0]["celebrante_id"]>0){
        $where = "celebrante_id = ".$result[0]["celebrante_id"]." AND cabecalho_mes_referencia = '" . $result[0]["prestacao_mes_referencia"] . "' AND tipo_repasse_id = " . $result[0]["tipo_prestacao_id"];
    }
    else{
        $where = "executora_id = ".$result[0]["executora_id"]." AND cabecalho_mes_referencia = '" . $result[0]["prestacao_mes_referencia"] . "' AND tipo_repasse_id = " . $result[0]["tipo_prestacao_id"];
    }

    $sistema->select("rec_cabecalhos","*",$where);
    $resultCabecalho = $sistema->getResult();




    if($result[0]["celebrante_id"]>0){
        $entidade = utf8_encode($result[0]["celebrante_razao_social"]);
        $cnpj = $result[0]["celebrante_cnpj"];
        $endereco = utf8_encode($result[0]["celebrante_endereco"]) . ', ' . $result[0]["celebrante_numero"];
        $responsavel = '-';
    }
    else{
        $entidade = utf8_encode($result[0]["executora_razao_social"]);
        $cnpj = $result[0]["executora_cnpj"];
        $endereco = utf8_encode($result[0]["executora_endereco"]) . ', ' . $result[0]["executora_numero"];
        $responsavel = utf8_encode($result[0]["executora_responsavel_nome"]);
    }

    
    $totalDepesas = ('0,00');

    for($i=0;$i<count($result);$i++){

        $valorNotaOriginal = str_replace("," , "." , str_replace("." , "" , $result[$i]["valor_nota"]));
        $totalDepesas = floatval($valorNotaOriginal) + floatval($totalDepesas);

    }

        $valorCusteioOriginal = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["custeio_executado"]));
        $valorRHOriginal = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["recursos_humanos_executado"]));
        $valorTerceirosOriginal = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["servicos_terceiros_executado"]));
                
        $totalDepesasRubrica = floatval($valorCusteioOriginal) + floatval($valorRHOriginal) + floatval($valorTerceirosOriginal);
        
    $html .= "      <tr>
                        <td><strong>".$entidade."</strong></td>
                        <td><strong>".number_format($totalDepesas,2,",",".")."</strong></td>
                        <td><strong>".number_format($totalDepesasRubrica,2,",",".")."</strong></td>
                    </tr>
           ";
 
           unset($innerJoin);

}

    $html .= "</tbody></table>";

    echo $html;