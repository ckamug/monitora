<?php
set_time_limit(0);
include "../../../../classes/sistema.php";

$sistema = new Sistema();

//$sistema->debug=true;

$sistema->select("ouvidoria" , 'cpf' , '' , '' , 'cpf LIMIT 0,500');
$result = $sistema->getResult();


for($i=0;$i<count($result);$i++){

    if($result[$i]["cpf"]!="" or $result[$i]["cpf"]!=0){

        $cpf = str_replace('.','',$result[$i]["cpf"]);
        $cpf = str_replace('-','',$cpf);

    }
    else{

        $cpf = 0;

    }

    $sistema->select('cecad_08_2021' , 'nome_pessoa , num_cpf_pessoa , num_nis_pessoa_atual' , 'num_cpf_pessoa = ' . $cpf);
    $resPessoa = $sistema->getResult();

    if($resPessoa[0]["nome_pessoa"]!=""){

        echo "Nome: " . $resPessoa[0]["nome_pessoa"] . " - " . "NIS: " . $resPessoa[0]["num_nis_pessoa_atual"] . " - " . "CPF: " . $resPessoa[0]["num_cpf_pessoa"] . "<br>";

    }


}


/*

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

*/