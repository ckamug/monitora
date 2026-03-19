<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo($_POST['id']);

function montaCombo($id)
{
	if($id>0){	
		$select = new select('recomeco','rec_tipos_registro','slcTipoRegistro','tipo_registro_id','tipo_registro_descricao','tipo_registro_id',$id,'','');
	}
	else{
		$select = new select('recomeco','rec_tipos_registro','slcTipoRegistro','tipo_registro_id','tipo_registro_descricao','tipo_registro_id','','','');
	}
	
	echo $select;
}