<?php

session_start();
final class requestViews
{
	protected $view; //HTML QUE DEVER� SER MOSTRADO PARA O USU�RIO
	private $cssFiles = array();//LISTA DE ARQUIVOS CSS QUE O SEU M�DULO UTILIZA
	private $javascriptFiles = array(); //LISTA DE ARQUIVOS JAVASCRIPT QUE O SEU M�DULO UTILIZA
	private $javascriptFilesProject = array(); //LISTA DE ARQUIVOS JAVASCRIPT QUE O PROJETO UTILIZA, INDEPENDENTE DO M�DULO
	
	public function loadComponent() // CARREGA O M�DULO DO SISTEMA INDICADO PELA $GET[lnk] 
	{
		$view = explode('/', getCurrentView());

		$file = "public/componentes/" . $view[0] . "/index.php";
		if(file_exists($file))
		{
			require($file);
		}
	}
	
	public function addClass($file) // CARREGA AS CLASSES UTILIZADAS PELO CONTROLADOR 
	{
		$view = explode('/',$_GET["view"]);
		$file = "classes/" . $view[0] . ".php";
		echo $file;
		exit;
		if(file_exists($file))
		{
			require($file);
		}
	}
	
	public function addCss($file)
	{
		//ADICIONA OS ARQUIVOS CSS � CLASSE
		$this->cssFiles[] = $file;
	}
	
	public function addJavaScript($file)
	{
		//ADICIONA OS ARQUIVOS JAVASCRIPT � CLASSE
		$this->javascriptFiles[] = $file;
	}
	
	public function addJavaScriptProject($file)
	{
		//ADICIONA OS ARQUIVOS JAVASCRIPT � CLASSE
		$this->javascriptFilesProject[] = $file;
	}
	
	public function makeCssforView()
	{
		//IMPORTA OS ARQUIVOS ARMAZENADOS NA VARI�VEL $cssFiles PARA A VIEW (HTML)
		$view = explode('/',$_GET["view"]);
		foreach ($this->cssFiles as &$fl) {
    		$file = "public/componentes/" . $view[0] . "/css/" . $fl;
    		//echo $file;
    		if(file_exists($file))
    		{
    			echo "<link rel='stylesheet' type='text/css' href='" .  URL . "public/componentes/" . $view[0] . "/css/" . $fl ." '/>";
    		}
		}
	}
	
	public function makeJavaScriptforView()
	{
		//IMPORTA OS ARQUIVOS ARMAZENADOS NA VARI�VEL $javascriptFiles PARA A VIEW (HTML)
		$view = explode('/',$_GET["view"]);
		foreach ($this->javascriptFiles as &$fl) {
    		$file = "public/componentes/" . $view[0] . "/js/" . $fl;
    		//echo $file;
    		if(file_exists($file))
    		{
    			echo "<script type='text/javascript' language='javascript'  src='" .  URL . "public/componentes/" . $view[0] . "/js/" . $fl ." '></script>";
    		}
		}
	}

	public function makeJavaScriptforProject()
	{
		//IMPORTA OS ARQUIVOS ARMAZENADOS NA VARI�VEL $javascriptFiles PARA A VIEW (HTML)
		$view = explode('/',$_GET["view"]);
		foreach ($this->javascriptFiles as &$fl) {
    		$file = "public/" . $view[0] . "/js/" . $fl;
    		//echo $file;
    		if(file_exists($file))
    		{
    			echo "<script type='text/javascript' language='javascript'  src='" .  URL . "public/" . $view[0] . "/js/" . $fl ." '></script>";
    		}
		}
	}

	public function getView()
	{

		$view = explode('/',$_GET["view"]);

		$file = "public/componentes/". $view[0] ."/views/FrontEnd.php";	
		
		$fileError = "404.html";
		if(sizeof($this->cssFiles)>0)
		{
			self::makeCssforView();
		}
	    if(sizeof($this->javascriptFiles)>0)
	    {
	    	self::makeJavaScriptforView();
	    }
	    if(sizeof($this->javascriptFilesProject)>0)
	    {
	    	self::makeJavaScriptforProject();
	    }
		if(file_exists($file))
		{
			require($file);
		}
		else
		{
			require($fileError);
		}
	}
}