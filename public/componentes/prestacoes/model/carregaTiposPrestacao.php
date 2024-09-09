<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{
	$select = new select('recomeco','rec_tipos_prestacao','slcTiposPrestacao','tipo_prestacao_id','tipo_prestacao_descricao','tipo_prestacao_descricao','','','');		
	echo $select;
}