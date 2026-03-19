<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";
session_start();

montaCombo();

function montaCombo()
{

	$select = new select('recomeco','rec_servicos','slcServicos','servico_id','servico_descricao','servico_descricao','',"carregaOscsExecutoras()",'','');
	echo $select;

}
