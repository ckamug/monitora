<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{
	$select = new select('recomeco','rec_tipos_repasse','slcTiposRepasse','tipo_repasse_id','tipo_repasse_descricao','tipo_repasse_descricao','','','');		
	echo $select;
}