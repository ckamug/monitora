<?php

use Mpdf\Mpdf;
require_once "../../../../vendor/autoload.php";
include "../../../../classes/sistema.php";

$sistema = new Sistema();
//$sistema->debug=true;
$campos = 'a.* , b.categoria_descricao , c.subcategoria_descricao , d.* , d.prestacao_id as prestacao , d.executora_id as executora , d.celebrante_id as celebrante , e.* , f.* , g.executora_responsavel_nome';
$from = 'rec_notas_fiscais a';
$innerJoin[] = 'inner join rec_categorias b on a.categoria_id = b.categoria_id';
$innerJoin[] = 'inner join rec_subcategorias c on a.subcategoria_id = c.subcategoria_id';
$innerJoin[] = 'inner join rec_prestacoes d on a.prestacao_id = d.prestacao_id';
$innerJoin[] = 'left join rec_executoras e on d.executora_id = e.executora_id';
$innerJoin[] = 'left join rec_celebrantes f on d.celebrante_id = f.celebrante_id';
$innerJoin[] = 'left join rec_executoras_responsaveis g on d.executora_id = g.executora_id';

$where = "a.prestacao_id = " . base64_decode($_GET["p"]) . " AND a.nota_status != 5 AND a.nota_status != 0";

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

$mes_extenso = array('01' => 'JANEIRO','02' => 'FEVEREIRO','03' => 'MARÇO','04' => 'ABRIL','05' => 'MAIO','06' => 'JUNHO','07' => 'JULHO','08' => 'AGOSTO','09' => 'SETEMBRO','10' => 'OUTUBRO','11' => 'NOVEMBRO','12' => 'DEZEMBRO');

if(substr($result[0]["prestacao_mes_referencia"],5,2)=='10'){
    $mesReferencia = $mes_extenso['10'];
}
else{
    $mesReferencia = $mes_extenso[str_replace('0','',substr($result[0]["prestacao_mes_referencia"],5,2))];
}

if(date('m')=='10'){
    $mesExtenso = $mes_extenso['10'];
}
else{
    $mesExtenso = $mes_extenso[str_replace('0','',substr($result[0]["prestacao_mes_referencia"],5,2))];
}

$totalDepesas = ('0,00');

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

<div style='width:100%;'>
    <div style='position:relative; float:left; width:40%'></div>
    <div style='position:relative; float:right; width:40%;font-size:12px;text-align:center;'>
        <h3>REPASSES AO TERCEIRO SETOR<br>DEMONSTRATIVO DA RECEITA E DESPESA</h3>
    </div>
</div>

<div style='width:100%;font-size:12px;'>
    <p>";

if($result[0]["tipo_responsavel"]==1){
    $html .= "<strong>ÓRGÃO CONCESSOR:</strong> SECRETARIA DE DESENVOLVIMENTO SOCIAL<br>";
}
else{
    $html .= "<strong>ÓRGÃO CONCESSOR:</strong> SAMARITANO SÃO FRANCISCO DE ASSIS<br>";
}
        
    $html .= "<strong>ENTIDADE BENEFICIÁRIA:</strong> ".$entidade."<br>
        <strong>CNPJ:</strong> ".$cnpj."<br>
        <strong>ENDEREÇO:</strong> ".$endereco."<br>
        <strong>RESPONSÁVEL PELA ENTIDADE:</strong> ".$responsavel."<br>
        <strong>COMPETÊNCIA:</strong> ".$mesReferencia."/".substr($result[0]["prestacao_mes_referencia"],0,4)."<br>
        <strong>TIPOS DE SERVIÇO:</strong> ";

        for($i=0;$i<count($servicos);$i++){
            $servicosDescricao .= utf8_encode($servicos[$i]["servico_descricao"]);
            if($i<count($servicos)){
                $servicosDescricao .= ", ";
            }
        }

$html .= substr($servicosDescricao,0,-2) . "<br>
        <strong>VAGAS:</strong> ".$result[0]["executora_vagas"]." &nbsp;&nbsp;&nbsp;<strong>VALOR DO REPASSE:</strong> R$ ".$resultCabecalho[0]["valor_repasse"]."<br>
    </p>
</div>



<table align='center' width='100%'>
    <thead>
        <tr bgcolor='#EEE'><th colspan='5'>DEMONSTRATIVO GERAL DE DESPESAS</th></tr>
        <tr>
            <th>DATA</th>
            <th>Nº NF</th>
            <th width='230px'>NATUREZA DAS DESPESAS</th>
            <th>SUBITEM DE DESPESA</th>
            <th width='150px'>VALOR</th>
        </tr>
    </thead>
    <tbody>
";

for($i=0;$i<count($result);$i++){

    $html .= "<tr>
                 <td>".$sistema->convertData($result[$i]["data_nota_fiscal"])."</td>
                 <td>".utf8_encode($result[$i]["numero_nota_fiscal"])."</td>
                 <td>".utf8_encode($result[$i]["categoria_descricao"])."</td>
                 <td>".utf8_encode($result[$i]["subcategoria_descricao"])."</td>
                 <td>R$".$result[$i]["valor_nota"]."</td>
              </tr>";

        
        $valorNotaOriginal = str_replace("," , "." , str_replace("." , "" , $result[$i]["valor_nota"]));
    
        $totalDepesas = floatval($valorNotaOriginal) + floatval($totalDepesas);

}
    
    
$html .= "      <tr>
                    <td colspan='4' align='right'><strong>TOTAL DAS DESPESAS</strong></td>
                    <td><strong>R$".number_format($totalDepesas,2,",",".")."</strong></td>
                </tr>
            </tbody>
        </table>";

$html .= "
<div style='width:100%;font-size:12px;text-align:center;'>
    <div style='position:relative; float:left; width:45%;margin-top:30px;'>
        <p>DECLARO NA QUALIDADE DE RESPONSÁVEL PELA <strong>".$entidade."</strong> SOB AS PENALIDADES DA LEI, QUE A DOCUMENTAÇÃO ACIMA RELACIONADA COMPROVA A EXATA APLICAÇÃO DOS RECURSOS RECEBIDOS PARA OS FINS INDICADOS</p>
        <div style='width:100%;margin-top:30px;'>SÃO PAULO, ".date("d")." de ".$mes_extenso[date("m")]." de ".date("Y")."</div>
        <div style='width:100%;margin-top:40px;'>
        _________________________________________________________<br><i>RESPONSÁVEL LEGAL - PRESIDENTE</i>
        </div>
    </div>
    
    <div style='position:relative; float:right; width:55%;font-size:12px;text-align:center;'>
        <table align='center'>
        <thead>
            <tr>
                <th>SÍNTESE NATUREZA DAS DESPESAS</th>
                <th width='150px'>VALOR EM R$</th>
            </tr>
        </thead>
        <tbody>
    ";
    
        $html .= "
                <tr>
                    <td align='left'>CUSTEIO</td>
                    <td>R$".$resultCabecalho[0]["custeio_executado"]."</td>
                </tr>
                <tr>
                    <td align='left'>RECURSOS HUMANOS</td>
                    <td>R$".$resultCabecalho[0]["recursos_humanos_executado"]."</td>
                </tr>
                <tr>
                    <td align='left'>TERCEIROS</td>
                    <td>R$".$resultCabecalho[0]["servicos_terceiros_executado"]."</td>
                </tr>";

            
            $valorCusteioOriginal = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["custeio_executado"]));
            $valorRHOriginal = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["recursos_humanos_executado"]));
            $valorTerceirosOriginal = str_replace("," , "." , str_replace("." , "" , $resultCabecalho[0]["servicos_terceiros_executado"]));
            
            $totalDepesasRubrica = floatval($valorCusteioOriginal) + floatval($valorRHOriginal) + floatval($valorTerceirosOriginal);

        
        
    $html .= "      <tr>
                        <td align='right'><strong>TOTAL DAS DESPESAS</strong></td>
                        <td><strong>R$".number_format($totalDepesasRubrica,2,",",".")."</strong></td>
                    </tr>
                </tbody>
            </table>  
    </div>
</div>
";

$mpdf=new Mpdf(['orientation' => 'L']);
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output();