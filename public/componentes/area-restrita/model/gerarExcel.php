<?php

include "../../../../classes/sistema.php";

$sistema = new Sistema();

//$sistema->debug=true;
$campos = ' b.osc_nome_fantasia , b.osc_tipos_servicos , a.aco_nome , a.aco_dt_nasc , a.aco_rg , a.aco_cpf , a.aco_dt_entrada , a.aco_dt_saida , a.aco_status';
$from = 'acolhidos a';
$innerJoin[] = 'left join osc b on a.aco_osc = b.id';

//$where = "aco_dt_entrada BETWEEN '2024-04-01 00:00:00' AND '2024-06-31 23:59:59' and (b.id = 151)";

$where = "aco_dt_entrada BETWEEN '2024-01-01 00:00:00' AND '2024-09-02 23:59:59'";
$order = "b.osc_razao_social";

$sistema->innerJoin($campos,$from,$innerJoin,$where,'',$order);
$result = $sistema->getResult();

$StringJson = "["; 
if ( count($result) ) {
    
    $data_inicial = '2024-01-01';
    $data_final = '2024-09-02';
    $diferenca = strtotime($data_final) - strtotime($data_inicial);
    $dias = floor($diferenca / (60 * 60 * 24));
    $diaMesAno = ' 02/09/2024';

    for($i=0;$i<=$dias;$i++){
        $diaMesAno = $diaMesAno . ";" . date('d/m/Y', strtotime('+'.$i.' days', strtotime($data_inicial)));
    }

// Gera arquivo CSV
$fp = fopen("planilhas/arquivo.csv", "w"); // o "a" indica que o arquivo será sobrescrito sempre que esta função for executada.
$escreve = fwrite($fp, "OSC;TIPO DE SERVICO;ACOLHIDO;NASCIMENTO;RG;CPF;ENTRADA;SAIDA;STATUS;FREQUENCIA$diaMesAno");

    foreach($result as $registro) 
    { 

        $marcacao = "";
        
        switch($registro['aco_status']){
            case 0:
                $status = "EM ACOLHIMENTO";
            break;
            case 2:
                $status = "RESERVA FINALIZADA";
            break;
            case 11:
                $status = "ALTA TERAPEUTICA";
            break;
            case 12:
                $status = "ALTA SOLICITADA";
            break;
            case 13:
                $status = "ALTA ADMINISTRATIVA";
            break;
            case 14:
                $status = "EVASAO";
            break;
        }


        $data_inicial_freq = substr($registro['aco_dt_entrada'],0,10);

        if($registro['aco_dt_saida']==""){
            $data_saida_freq = substr(date("Y-m-d"),0,10);
        }
        else{
            $data_saida_freq = substr($registro['aco_dt_saida'],0,10);
        }

        $diferenca_freq = strtotime($data_saida_freq) - strtotime($data_inicial_freq);
        $dias_freq = floor(($diferenca_freq / (60 * 60 * 24))+1);


        for($i=0;$i<=$dias;$i++){
            $data = date('Y-m-d', strtotime('+'.$i.' days', strtotime($data_inicial)));

            if(strtotime($data) >= strtotime($data_inicial_freq) and strtotime($data) <= strtotime($data_saida_freq)){
                $marcacao .= "1;";
            }
            else{
                $marcacao .= " ;";
            }

        }

        $escreve = fwrite($fp, "\n$registro[osc_nome_fantasia];$registro[osc_tipos_servicos];$registro[aco_nome];$registro[aco_dt_nasc];$registro[aco_rg];$registro[aco_cpf];$data_inicial_freq;$data_saida_freq;$status;$dias_freq;$marcacao");

    }  
        
    fclose($fp);

    echo "arquivo.csv gerado às " . date("d/m/Y h:i:s") . " - <a href='https://portal.seds.sp.gov.br/coed/public/componentes/area-restrita/model/planilhas/arquivo.csv' target='_blank'>Baixar Arquivo</a>";
    
}