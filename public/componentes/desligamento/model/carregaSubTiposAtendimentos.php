<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{
	
	if($_POST['id']>0){
		$select = new select('recomeco','rec_subtipos_atendimentos','slcSubTiposAtendimentos','subtipo_atendimento_id','subtipo_descricao','subtipo_atendimento_id','','','tipo_atendimento_id = ' . $_POST['id']);
	}
	else{
		$select = new select('recomeco','rec_subtipos_atendimentos','slcSubTiposAtendimentos','subtipo_atendimento_id','subtipo_descricao','subtipo_atendimento_id','','','');
	}

	echo $select;

}