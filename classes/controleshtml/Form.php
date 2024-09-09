<?php
require_once 'classes/crud2.php';
class XformFor extends crud
{
	private $Form = "<div class='tlCinza'>";
	public function __construct($table)
	{
		self::formulario($table);
	}
	public function formulario($table)
	{
		$crud = new crud();
		$crud->connect();
		$crud->selectColumns($table);
		$result = $crud->getResult();
		$this->Form .= $crud->Xform;
		$this->Form .= "</div>";
		//var_dump($result);
		echo "<br/><br/><br/><br/>";
	}
	public function display()
	{
		echo $this->Form;
	}
	
	
}