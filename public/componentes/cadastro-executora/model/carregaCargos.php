<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{
	$select = new select('recomeco','rec_cargos','slcCargos','cargo_id','cargo_descricao','cargo_id','','','');		
	echo $select;
}