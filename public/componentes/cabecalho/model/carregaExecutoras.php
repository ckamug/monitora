<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";
session_start();

montaCombo();

function montaCombo()
{
	if($_SESSION["pf"]==2){
		$select = new select('recomeco','rec_executoras','slcExecutoras','executora_id','executora_nome_fantasia','executora_nome_fantasia','',"listaCabecalhos(this.value,'executora')",'tipo_responsavel = ' . $_SESSION["pf"]);
	}
	else{
		$select = new select('recomeco','rec_executoras','slcExecutoras','executora_id','executora_nome_fantasia','executora_nome_fantasia','',"listaCabecalhos(this.value,'executora')",'');
	}
	
	echo $select;
}