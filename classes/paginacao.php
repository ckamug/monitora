<?php

require_once('../../../../../classes/sistema.php');

class paginacao
{

	private $itensPorPagina = 6;
	private $pagina;
	private $inicio;
	private $fim;
	public  $totalPaginas;

	function montarPagina($tabela,$campos,$where,$itensPorPagina,$pagina)
	{
		
		if (!$pagina){ 
			$inicio=0; 
			$pagina=1; 
		} 
		else { 
			$inicio = ($pagina - 1) * $itensPorPagina; 
		}
		
		$sistema = new Sistema();
		//$sistema->debug = true;
		$sistema->select($tabela,$campos,$where);
		$result = $sistema->getResult();
		
		$num_total_registros = count($result);
		
		//$sistema->setter(totalPaginas, ceil($num_total_registros / $itensPorPagina));

		$sistema->select($tabela,$campos,$where." LIMIT " . $inicio . "," . $itensPorPagina);
		$result = $sistema->getResult();
		
		$this->totalPaginas=ceil($num_total_registros / $itensPorPagina);

		return $result;

	}


	function contarPaginas(){
		return $this->totalPaginas;
	}
	
}