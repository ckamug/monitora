<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";
session_start();


montaCombo();

function montaCombo()
{

	if($_SESSION['pf']==2){
		$where = 'nota_status_id <> 5 AND nota_status_id <> 3 AND nota_status_id <> 2';
	}
	else if($_SESSION['pf']==1){
		$where = 'nota_status_id <> 5 AND nota_status_id <> 2';
	}
	else{
		$where = 'nota_status_id <> 5 AND nota_status_id <> 1 AND nota_status_id <> 6';
	}

	if($_POST['id']>0){
		$select = new select('recomeco','rec_notas_status','slcNotasStatus','nota_status_id','nota_status_descricao','nota_status_descricao',$_POST['id'],'trataStatus(this.value)',$where);
	}
	else{
		$select = new select('recomeco','rec_notas_status','slcNotasStatus','nota_status_id','nota_status_descricao','nota_status_descricao','','trataStatus(this.value)',$where);		
	}

	echo $select;
}