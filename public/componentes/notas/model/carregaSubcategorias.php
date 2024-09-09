<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

montaCombo();

function montaCombo()
{

	if($_SESSION["tipo_prestacao"]==4){ // CASO TIPO DE PRESTAÇÃO SEJA IMPLANTAÇÃO
		$where = "categoria_id = 4";
		$subCategoria = '49';
	}
	else{
		$where = "categoria_id = " . $_POST['categoria_id'];
		$subCategoria = $_POST['id'];
	}

	if($subCategoria>0){
		$select = new select('recomeco','rec_subcategorias','slcSubcategorias','subcategoria_id','subcategoria_descricao','subcategoria_descricao',$subCategoria,'',$where);
	}
	else{
		$select = new select('recomeco','rec_subcategorias','slcSubcategorias','subcategoria_id','subcategoria_descricao','subcategoria_descricao','','',$where);		
	}

	echo $select;
}