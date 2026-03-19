<?php
require_once('../../../../classes/crud.php');
require_once('../../../../classes/sistema.php');
class select extends crud
{
	protected $html;
	
	public function __construct($dataBase = "",$table,$idSelect = "",$val = "",$labelValue = "",$orderBy = "",$optionSelected = "",$js = null,$where = null,$novoItem = "")
	{
	
		$crud = new Sistema();
		if($dataBase !="")
		{
			$crud->setter('db_name',$dataBase);
		}
		$crud->connect();
		if($orderBy == "")
		{
			$orderBy = $labelValue;
		}
		if($where != "")
		{
		  $where = $where;
		}
		else{
			$where = "";
		}
		//echo $this->js;
		if($js != "")
		{
		  $javascript = $js;
		}
		else{
			$javascript = "";
		}
		
		//$crud->debug=true;
		$crud->select($table,"*",$where,"",$orderBy);
		$result = $crud->getResult();

		
		$this->html .= "<select class='form-select' id='" . $idSelect ."' name='". $idSelect ."' onchange=\"" . $javascript . "\"aria-label='Municipio' required>\n";
		$this->html .="<option value = '0'></option>\n";
		
		foreach($result as $value)
		{
			$Selected = ($optionSelected == $value[$val])? "selected='selected'" : "";
			$this->html .= "<option value='". $value[$val]. "' title='" . utf8_encode($value[$labelValue]) . "' " . $Selected . ">" . utf8_encode($value[$labelValue]) . "</option>\n";		  
		}
		
		if($novoItem!=""){
			$this->html .="<option value = '9999'>".$novoItem."</option>\n";
		}
		$this->html .= "</select>\n";
		
		switch($table){
			case "tbl_cidades":
				$this->html .= "<label for='slcPerfil'>Município</label>";
			break;
			case "rec_cargos":
				$this->html .= "<label for='slcPerfil'>Cargos</label>";
			break;
			case "rec_perfis":
				$this->html .= "<label for='slcPerfil'>Perfis</label>";
			break;
			case "rec_celebrantes":
				$this->html .= "<label for='slcPerfil'>Celebrante</label>";
			break;
			case "rec_municipios":
				$this->html .= "<label for='slcPerfil'>Município (Porta de Entrada)</label>";
			break;
			case "rec_executoras":
				$this->html .= "<label for='slcPerfil'>OSC Executora</label>";
			break;
			case "rec_categorias":
				$this->html .= "<label for='slcCategorias'>Categorias</label>";
			break;
			case "rec_subcategorias":
				$this->html .= "<label for='slcSubcategorias'>Subcategorias</label>";
			break;
			case "rec_notas_status":
				$this->html .= "<label for='slcSubcategorias'>Status da Nota</label>";
			break;
			case "rec_tipos_repasse":
				$this->html .= "<label for='slcSubcategorias'>Tipos de Repasse</label>";
			break;
			case "rec_tipos_prestacao":
				$this->html .= "<label for='slcSubcategorias'>Tipos de Prestação de Contas</label>";
			break;
			case "rec_servicos":
				$this->html .= "<label for='slcSubcategorias'>Referenciamento Serviço</label>";
			break;
			case "rec_tipos_atendimentos":
				$this->html .= "<label for='slcSubcategorias'>Tipo de Atendimento</label>";
			break;
			case "rec_subtipos_atendimentos":
				$this->html .= "<label for='slcSubcategorias'>Detalhes do Tipo</label>";
			break;
			case "rec_tipos_desligamentos":
				$this->html .= "<label for='slcTiposDesligamentos'>Tipo de Desligamento</label>";
			break;
			case "rec_tipos_registro":
				$this->html .= "<label for='slcTiposDesligamentos'>Tipo</label>";
			break;
			default:
				$this->html .= "<label for='slcPerfil'></label>";
			break;
		}
	}
	public function __toString()
	{
		return $this->html;
	}
}