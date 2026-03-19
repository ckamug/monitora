<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{
	
	if($_POST['id']>0){
		$where = 'tipo_atendimento_id = ' . $_POST['id'];
		if($_POST['id']==2){
			$where .= ' AND subtipo_atendimento_id <> 11';
		}
		$select = new select('recomeco','rec_subtipos_atendimentos','slcSubTiposAtendimentos','subtipo_atendimento_id','subtipo_descricao','subtipo_atendimento_id','','',$where);
	}
	else{
		$select = new select('recomeco','rec_subtipos_atendimentos','slcSubTiposAtendimentos','subtipo_atendimento_id','subtipo_descricao','subtipo_atendimento_id','','','');
	}

	echo $select;

}
