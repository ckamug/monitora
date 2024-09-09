<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{
	$select = new select('recomeco','rec_tipos_atendimentos','slcTiposAtendimentos','tipo_atendimento_id','tipo_atendimento_descricao','tipo_atendimento_descricao','','','');		
	echo $select;
}