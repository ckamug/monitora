<?php
include "../../../../classes/sistema.php";
session_start();

$mes = explode("-",str_replace("/" , "-" , $_POST["mes"] ));
$mesRef = $mes[1] . "-" . $mes[0];

$sistema = new Sistema();

//$sistema->debug=true;
//$sistema->select("rec_prestacoes","prestacao_id","prestacao_mes_referencia = '" . $mesRef. "'");
//$result = $sistema->getResult();

$campos = 'a.prestacao_id , a.tipo_prestacao_id , a.executora_id as executora , a.celebrante_id as celebrante , b.executora_nome_fantasia , c.celebrante_nome_fantasia';
$from = 'rec_prestacoes a';
$innerJoin[] = 'left join rec_executoras b on a.executora_id = b.executora_id';
$innerJoin[] = 'left join rec_celebrantes c on a.celebrante_id = c.celebrante_id';

$where = "a.prestacao_mes_referencia = '" . $mesRef. "' AND a.prestacao_ciencia = 1";

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.executora_id');
$result = $sistema->getResult();

$diretorio = '../../notas/model/anexos/prestacoes/';
$zip = new ZipArchive();

if($zip->open($diretorio . 'Documentos Complementares '.str_replace("/" , "-" , $_POST["mes"] ).'.zip', ZIPARCHIVE::CREATE) == TRUE)
{

    for($i=0;$i<=count($result);$i++){

        if($result[$i]["celebrante"]==0){
            $nome_fantasia = str_replace("/" , "-" , utf8_encode($result[$i]["executora_nome_fantasia"]));
        }
        else{
            $nome_fantasia = str_replace("/" , "-" , utf8_encode($result[$i]["celebrante_nome_fantasia"]));
        }

        switch($result[$i]["tipo_prestacao_id"]){
            case 1:
                $nome_fantasia = $nome_fantasia . "_valor_fixo";
            break;
            case 2:
                $nome_fantasia = $nome_fantasia . "_valor_variável";
            break;
            case 3:
                $nome_fantasia = $nome_fantasia . "_bonificação";
            break;
            case 4:
                $nome_fantasia = $nome_fantasia . "_implantação";
            break;
            case 5:
                $nome_fantasia = $nome_fantasia . "_parcial";
            break;
            case 6:
                $nome_fantasia = $nome_fantasia . "_anual";
            break;
        }



        $zip->addFile($diretorio.'DocCompl'.base64_encode($result[$i]["prestacao_id"]).'.pdf',''.$nome_fantasia.'.pdf');

    }
    
    echo "<a href='https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/anexos/prestacoes/Documentos Complementares ".str_replace("/" , "-" , $_POST["mes"] ) . ".zip' target='_blank'><img src='https://portal.seds.sp.gov.br/coed/images/zip.png' width='100px' border='0'></a>";
}
else
{
    echo 0;
}


$zip->close();