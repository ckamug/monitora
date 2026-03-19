<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo($_POST['id'] , $_POST['vinculo_id']);

function montaCombo($id,$vinculo_id)
{
	$select = '';

	switch($id){	
		case 1:
			
		break;
		case 2:
			$select = new select('recomeco','rec_celebrantes','slcPerfilVinculo','celebrante_id','celebrante_nome_fantasia','celebrante_nome_fantasia',$vinculo_id,'','');
		break;
		case 3:
			$select = new select('recomeco','rec_municipios','slcPerfilVinculo','municipio_id','municipio_orgao_publico','municipio_orgao_publico',$vinculo_id,'','');
		break;
		case 4:
			$select = new select('recomeco','rec_executoras','slcPerfilVinculo','executora_id','executora_nome_fantasia','executora_nome_fantasia',$vinculo_id,'carregaCasas(this.value)','');
		break;
		case 5:
			
		break;
		case 7:
			
		break;
	}
	
	echo $select;
}
