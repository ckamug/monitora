<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

$id = $_POST['id'];

montaCombo($id);

function montaCombo($id)
{
	if($id>0){
		$select = new select('recomeco','rec_tipos_desligamentos','slcTiposDesligamentos','tipo_desligamento_id','tipo_desligamento_descricao','tipo_desligamento_descricao',$id,'abreTipoCarregamento(this.value)','');
	}
	else{
		$select = new select('recomeco','rec_tipos_desligamentos','slcTiposDesligamentos','tipo_desligamento_id','tipo_desligamento_descricao','tipo_desligamento_descricao','','abreTipoCarregamento(this.value)','');
	}
	
	
	echo $select;
}