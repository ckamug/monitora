<?php

//require_once('../../../../../classes/sistema.php');
require_once('../../../../classes/sistema.php');

	$sistema = new Sistema();

    $sistema->select("conselhos_tmp",'*','','','id_ports DESC');
    $res = $sistema->getResult();

    
    for($i=0;$i<count($res);$i++){

        //$sistema->debug=true;
        $sistema->select("ports",'id','nome = "' . $res[$i]['id_ports'] . '"' ,'','');
        $res_mun = $sistema->getResult();

        if($res_mun){

            $dados['id_ports'] = $res_mun[0]["id"];

            //$sistema->debug=true;
            $sistema->update("conselhos_tmp",$dados,'id_ports = "' . utf8_encode($res[$i]['id_ports']) . '"');

            echo utf8_encode($res[$i]['id_ports']) . "<br>";

        }

        //echo utf8_encode($res[$i]['id_municipio']) . "<br>";

    }

/*
        $dados['campo_tabela1'] = $_POST['campo1'];
        $dados['campo_tabela2'] = $_POST['campo2'];
        $dados['campo_tabela3'] = $_POST['campo3'];

        $dados['dt_cadastro'] = $sistema->convertData(date('d/m/Y'));
*/

/*
- Excluir casa de passagem; OK
- Municipio cadastra acolhidos;
- OSC cadastra prontuário;
- COED distribui as vagas para as OSCs;
- Alterar Cadastrar Vagas para Vagas;
- Alterar Liberar Vagas para Distribuir vagas;
- Em cadastro de responsável, inclui combo com cargo: Presidente,  Coordenador e Presidente e Coordenador. Se Presidente, incluir opção se é remunerado ou não;
- Em porta de entrada trocar CNPJ por Municipio;
- Porta de Entrada é Municipio; OK
*/

