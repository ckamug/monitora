<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";
session_start();

montaCombo();

function montaCombo()
{

	if($_SESSION["tipo_prestacao"]==4){ // CASO TIPO DE PRESTAÇÃO SEJA IMPLANTAÇÃO
		$where = "categoria_id = 4";
	}
	else{
		$where = "categoria_id <> 4";
	}

	if($_POST['id']>0){
		
		$select = new select('recomeco','rec_categorias','slcCategorias','categoria_id','categoria_descricao','categoria_descricao',$_POST['id'],'carregaSubcategorias(0,this.value)',$where);
	
	}
	else{
		
		$select = new select('recomeco','rec_categorias','slcCategorias','categoria_id','categoria_descricao','categoria_descricao','','carregaSubcategorias(0,this.value)',$where);
	
	}

	echo $select;
}