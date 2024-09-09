<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{
	if($_POST['id']>0){
		$select = new select('recomeco','tbl_cidades','slcMunicipios','cidade_id','cidade_descricao','cidade_descricao',$_POST['id'],'carregaRegioes(this.value)','estado_id = 25');
	}
	else{
		$select = new select('recomeco','tbl_cidades','slcMunicipios','cidade_id','cidade_descricao','cidade_descricao','','carregaRegioes(this.value)','estado_id = 25');		
	}

	echo $select;
}