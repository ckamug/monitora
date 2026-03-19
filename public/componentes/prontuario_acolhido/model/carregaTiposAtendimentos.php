<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{
	$where = 'status = 1';

	if(isset($_POST["idsPermitidos"]) && trim($_POST["idsPermitidos"])!=""){
		$ids = preg_replace('/[^0-9,]/', '', $_POST["idsPermitidos"]);
		$ids = trim($ids, ',');
		if($ids!=""){
			$where .= ' AND tipo_atendimento_id IN ('.$ids.')';
		}
	}

	$select = new select('recomeco','rec_tipos_atendimentos','slcTiposAtendimentos','tipo_atendimento_id','tipo_atendimento_descricao','tipo_atendimento_id','','carregaSubAtendimento(this.value)',$where);		
	echo $select;
}
