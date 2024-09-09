<?php

/*
- Classe para montar o menu superior com os botões
- @access (public)
- @author Cauê J. Martinez <cauejm@yahoo.com.br>
- @version 1.0.1
- @param String $modulo - Nome do módulo para definir os botões do menu
- @param String $label - Informação para o nome do botão
- @param String $link - url do botão
- @param String $html - monta o menu e os botões
*/
require_once('sistema.php');

class Navegacao
{
  public $modulo;
  public $label;
  public $link;
  public $html;
  
/*
- Função para montar o menu
- access public
- return String
*/
  function montarMenu()
  {
    $this->modulo = $this->buscaIDModulo($_GET['lnk']);
	$modulo = $this->modulo;
//	if($modulo != 10)
//	{
		$sistema = new Sistema();
		//$sistema->debug=true;
		$sistema->select('tbl_navegacao','*','id_modulo =' . $modulo,'ordem');
		$resultado = $sistema->getResult();
		$tam_resultado = sizeof($resultado);

		for($i=0;$i<$tam_resultado;$i++)
		{
		  $this->html .= "<li id=" . $resultado[$i]["id_navegacao"] . ">
		   <a href = " . $resultado[$i]["link"] . ">" . $resultado[$i]["label"] . "</a> </li>\n";
		}
		
		$html = $this->html;
		return $html;
//	}
  }//fecha função montarMenu
  
/*
- Função para buscar o id correto referente ao módulo
- access public
- return int
*/
  function buscaIDModulo($IDModulo)
  {
    $sistema = new Sistema();
	//$sistema->debug=true;
	$sistema->select('lnk_modules','id_modulo,nome_modulo',"nome_modulo = '" . $IDModulo ."'");
	$resultado = $sistema->getResult();
	$id_modulo = $resultado[0]['id_modulo'];
   return $id_modulo;
  }
  
}//fecha classe navegacao*/