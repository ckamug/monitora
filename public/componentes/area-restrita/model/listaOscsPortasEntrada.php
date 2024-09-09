<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

	
if($_SESSION['pf']==3){

    if($_SESSION['pfv'] == 322){

        //$sistema->debug=true;
		$campos = 'a.executora_nome_fantasia , a.executora_vagas , COUNT(b.executora_id) AS total';
		$from = 'rec_executoras a';
		$innerJoin[] = 'left join rec_solicitacoes_vagas b on a.executora_id = b.executora_id';
		$where = 'a.executora_id = 35 OR a.executora_id = 34 OR a.executora_id = 24 OR a.executora_id = 38 OR a.executora_id = 14 OR a.executora_id = 40 OR a.executora_id = 41 OR a.executora_id = 42 OR a.executora_id = 6 OR a.executora_id = 75 OR a.executora_id = 48 OR a.executora_id = 44 OR a.executora_id = 32 OR a.executora_id = 17 OR a.executora_id = 22 OR a.executora_id = 16 OR a.executora_id = 67';

		$sistema->innerJoin($campos,$from,$innerJoin,$where,'a.executora_id','');
		$res = $sistema->getResult();
        
	}else{

		//$sistema->debug=true;
		$campos = 'a.cidade_id , b.regiao_administrativa_id , b.macroregiao_id';
		$from = 'rec_municipios a';
		$innerJoin[] = 'inner join tbl_cidades b on a.cidade_id = b.cidade_id';
		$where = 'a.municipio_id = ' . $_SESSION['pfv'];

		$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
		$regadm = $sistema->getResult();

		$res = $sistema->getResult();
		$cidadesArray = array();

		if(count($res)>0){

			for($i=0;$i<count($res);$i++){
				$cidadesArray[] = $res[$i]["cidade_id"];
			}

			$select = new select('recomeco','rec_executoras','slcOscsExecutoras','executora_id','executora_nome_fantasia','executora_nome_fantasia','','carregaDetalhesOsc(this.value)','executora_servicos_id like "%'.$_POST['id'].'%" AND executora_generos like "%'.$_POST['genero'].'%" AND cidade_id IN (' . implode(',', array_map('intval', $cidadesArray)) . ')','');
			echo $select;

		}
		else{
			echo "Não existe OSC disponível na região.";
		}

    }

    echo "<table id='tblOscsVagas' class='table datatable table-hover table-striped'>";
    echo "    <thead>";
    echo "        <tr>";
    echo "        <th scope='col' class=''>OSC</th>";
    echo "        <th scope='col' class='text-center' style='width:170px;'>Vagas Ocupadas</th>";
    echo "        <th scope='col' class='text-center' style='width:170px;'>Vagas Disponíveis</th>";
    echo "        </tr>";
    echo "    </thead>";

    for($i=0;$i<count($res);$i++){

        $vagasDisponiveis = $res[$i]["executora_vagas"] - $res[$i]["total"];

        echo "<tr>";
        echo "    <td>".utf8_encode($res[$i]["executora_nome_fantasia"])."</td>";
        echo "    <td class='text-center'>".$res[$i]["total"]."</td>";
        echo "    <td class='text-center'>".$vagasDisponiveis."</td>";
        echo "</tr>";

    }

    echo "</table>";

}