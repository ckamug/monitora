<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";
session_start();

montaCombo($_POST["tipo"]);

function montaCombo($tipo)
{

//	if($_SESSION["pf"]==2){
//		$select = new select('recomeco','rec_executoras','slcExecutoras','executora_id','executora_nome_fantasia','executora_nome_fantasia','',"listaPrestacoes(this.value,'executora')",'tipo_responsavel = ' . $_SESSION["pf"]);
//	}
//	else{

	if($_SESSION["consultaPrestacaoTipo"]=='celebrante'){
		$idSelected = 0;
	}
	else{
		$idSelected = $_SESSION["consultaPrestacaoId"];
	}

		$sistema = new Sistema();

		if($tipo=='Disponibilizadas'){
			$where = "prestacao_disponibilizada = 1 AND prestacao_pre_finalizada = 0 AND prestacao_status = 0";
		}
		else if($tipo=='Encerradas'){
			$where = "prestacao_pre_finalizada = 1 AND prestacao_status = 0";
		}
		else if($tipo=='Finalizadas'){
			if(date("m")==01){
				$mesReferencia = (date("Y")-1) . '-12';
			}
			else{
				$mesReferencia = date("Y") . '-' . date("m",strtotime('-1 month'));
			}
			
			$where = "prestacao_status = 1 and prestacao_mes_referencia = '" . $mesReferencia . "'";

		}
		else{
			$where = "";
		}

		//$sistema->debug=true;
		$sistema->select("rec_prestacoes","executora_id",$where,'executora_id');
		$resultado = $sistema->getResult();

		if(count($resultado)==0){
			$id[] = 0;
		}
		else{
			for($i=0;$i<count($resultado);$i++){
				$id[] = $resultado[$i]["executora_id"];
			}
		}

		if($_SESSION["pf"]==2){
			$select = new select('recomeco','rec_executoras','slcExecutoras','executora_id','executora_nome_fantasia','executora_nome_fantasia',$idSelected,"listaPrestacoes(this.value,'executora')",'tipo_responsavel = 2 AND executora_id IN (' . implode(',', array_map('intval', $id)) . ')');
		}
		else{
			$select = new select('recomeco','rec_executoras','slcExecutoras','executora_id','executora_nome_fantasia','executora_nome_fantasia',$idSelected,"listaPrestacoes(this.value,'executora')",'executora_id IN (' . implode(',', array_map('intval', $id)) . ')');
		}
	
//	}

	echo $select;
}