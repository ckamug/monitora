<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";
session_start();

montaCombo();

function montaCombo()
{
	$sistema = new Sistema();

	if($_SESSION['pfv'] == 322){

		$select = new select('recomeco','rec_executoras','slcOscsExecutoras','executora_id','executora_nome_fantasia','executora_nome_fantasia','','carregaDetalhesOsc(this.value)','executora_servicos_id like "%'.$_POST['id'].'%" AND executora_generos like "%'.$_POST['genero'].'%" AND (executora_id = 35 OR executora_id = 34 OR executora_id = 24 OR executora_id = 38 OR executora_id = 14 OR executora_id = 40 OR executora_id = 41 OR executora_id = 42 OR executora_id = 6 OR executora_id = 75 OR executora_id = 48 OR executora_id = 44 OR executora_id = 32 OR executora_id = 17 OR executora_id = 22 OR executora_id = 16 OR executora_id = 67)','');
		echo $select;

	}else{

		//$sistema->debug=true;
		$campos = 'a.cidade_id , b.regiao_administrativa_id , b.macroregiao_id';
		$from = 'rec_municipios a';
		$innerJoin[] = 'inner join tbl_cidades b on a.cidade_id = b.cidade_id';
		$where = 'a.municipio_id = ' . $_SESSION['pfv'];

		$sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
		$regadm = $sistema->getResult();

		if($_POST["genero"]=="Feminino"){
			$sistema->select("tbl_cidades","cidade_id","macroregiao_id = " . $regadm[0]["macroregiao_id"]);
		}
		else{
			$sistema->select("tbl_cidades","cidade_id","regiao_administrativa_id = " . $regadm[0]["regiao_administrativa_id"]);
		}

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

}