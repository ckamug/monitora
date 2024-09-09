<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{
	if($_POST['id']>0){
		$select = new select('recomeco','rec_perfis','slcPerfis','perfil_id','perfil_descricao','perfil_descricao',$_POST['id'],'perfilVinculo(this.value,0)','');
	}
	else{
		$select = new select('recomeco','rec_perfis','slcPerfis','perfil_id','perfil_descricao','perfil_descricao','','perfilVinculo(this.value,0)','');
	}

	echo $select;
}