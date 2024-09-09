<?php
// require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/crud.php');
require_once('crud.php');

class Sistema extends crud
{
	protected $result;
	protected $aviso;
	protected $erro;
	protected $campo;
	protected $method;
	public $dataBase;
	public $newID; 
	public $rowsAffected;
	public $debug;
	private $itensPorPagina = 15;
	private $pagina;
	private $inicio;
	private $fim;
	public $totalPaginas;
	public $paginarConsulta;
	public $urlPagina;
	
	public function insert($table, $values, $rows = null)
	{
		
		if(is_bool(isset($_SESSION['permissaoCadastro']) == true))
		{
		  $vals = self::validateValue($values);
		  //echo $vals;
		  $rows = self::ImplodePOST($values);
		  $crud = new crud();
		  if($this->dataBase !="")
		  {
			$crud->setter('db_name',$this->dataBase);
		  }
		  $crud->connect();
		  if($this->debug == true)
		  {
			$crud->ShowQuery = true;
		  }
		  $crud->insert($table,array($vals),$rows);
		  $result = $crud->getResult();
		  if($result)
		  {
			$this->newID = $crud->newID;
			$this->result = true;
			$crud->SetSession();
		  }
		  else
		  {
			$this->result = false;
		  }
		}
		else
		{
		  $this->result = false;
		}
	}
	
	public function update($table, $rows, $where = "")
	{
		
		if(is_bool(isset($_SESSION['permissaoAlteracao']) == true))
		{
		  $values = self::setArrayValue($rows);
		  $crud = new crud();
		  if($this->dataBase!="")
		  {
			$crud->setter('db_name',$this->dataBase);
		  }
		  $crud->connect();
		  if($this->debug == true)
		  {
			$crud->ShowQuery = true;
		  }
		  $this->result = $crud->update($table, $values, $where);
		}
		else
		{
		  $this->result = false;
		}		
	}
	
	public function select($table,$rows = "*",$where = "",$group = "",$order = "") {
	
		$this->method = "select";
		$crud = new crud();
		if($this->dataBase !="")
		{
			//echo $this->dataBase;
			//exit;
			$crud->setter('db_name',$dataBase);
		}
		$crud->connect();
		if($this->debug == true)
		{
			$crud->ShowQuery = true;
		}
		
		if($this->paginarConsulta == true)
		{
			$result = $this->montarPagina($table,$rows,$where,$group,$order);
			//var_dump($result);
			return $result;
		}
		else
		{
			if($this->debug == true)
			{
				$crud->ShowQuery = true;
			}
			$crud->select($table,$rows,$where,$group,$order);
			$this->result = $crud->getResult();
		}
	} 
	

	public function innerJoin($campos, $from, $innerJoin, $where = "",$group = "",$order = "") {
	
	//var_dump($innerJoin);
		$this->method = "innerJoin";
		$crud = new crud();
		if($this->dataBase !="")
		{
			//echo $this->dataBase;
			//exit;
			$crud->setter('db_name',$dataBase);
		}
		$crud->connect();
		if($this->debug == true)
		{
			$crud->ShowQuery = true;
		}
		$crud->innerJoin($campos, $from, $innerJoin,$where,$group,$order);
		$this->result = $crud->getResult();
	} 
	
	protected function ImplodePOST($POST)
	{
			$i = 0;
			foreach($POST as $key => $val)
			{
				$values[$i] = $key;
    			$i ++; 
   			}
  			return implode(",", $values);
	}
	
	protected function validateValue($POST)
	{
			$i = 0;
			foreach($POST as $key=>$val)
			{
				$values[$i] = "'" . $val . "'";
    			$i ++; 
   			}
   			return implode(",", $values);
	}
	
	public function __set($key,$val)
	{
		$this->$key = $val;
	}
	
	protected function setArrayValue($array)
	{
			$i = 0;
			//$values[];
			foreach($array as $key=>$val)
			{
				$values[$key] = $val;
    			$i ++; 
   			}
  			//var_dump($values);
  			//exit;
  			return $values;
	}
	
	public function CamposVazios($campos)
	{
		foreach ($campos as $this->campo => $valor)
		if(empty($valor))
		{
			$this->erro[] = $this->campo;
		}
		return $this->erro;
	}
	
	public function dataValida($dat)
	{
		$data = explode("/","$dat"); // fatia a string $dat em pedados, usando / como refer�ncia
		$d = $data[0];
		$m = $data[1];
		$y = $data[2];
		$res = checkdate($m,$d,$y);
		if ($res == 1)
		{
		   return true;
		}
		 else
		{
		   return false;
		}
	}
	
	public function emailValido($mail)
	{
		$conta = "^[a-zA-Z0-9\._-]+@";
		$domino = "[a-zA-Z0-9\._-]+.";
		$extensao = "([a-zA-Z]{2,4})$";
		$pattern = $conta.$domino.$extensao;
		if (ereg($pattern, $mail))
		return true;
		else
		return false;
	}
	
	public function getResult()
	{
		return $this->result;
	}
	
	public function resultToXML()
	{
		if($this->method == "select")
		{
			$i = 0;
			$xml = "<result>\n";
			while(list($key, $val) = each($this->result))
			{
				$xml .= "	<" . $key . ">" . $val ."</" . $key . ">\n";
   			}
			$xml .= "</result>\n";
  			return $xml;			
		}
	}
	
	public function resultToJSON()
	{
		$arr = array();
		while(list($key, $val) = each($this->result[0]))
		{
			$arr[$key] = utf8_encode($val);
   		}
		$r = json_encode($arr);
		return $r;
	}
	
	public function montarPagina($tabela,$campos,$where,$group,$order)
	{
		
		if (!$this->pagina){ 
			$inicio=0; 
			$this->pagina=1; 
		} 
		else { 
			$inicio = ($this->pagina - 1) * $this->itensPorPagina; 
		}
		
		$sistema = new Sistema();
		//$sistema->debug=true;
		$sistema->select($tabela,$campos,$where,$group,$order);
		$result = $sistema->getResult();
		//var_dump($result);
		
		$num_total_registros = count($result);

		//$sistema->debug=true;
		//$sistema->select($tabela,$campos,$where." LIMIT " . $inicio . "," . $this->itensPorPagina,$group,$order);
		$sistema->select($tabela,$campos,$where,$group,$order . " LIMIT " . $inicio . "," . $this->itensPorPagina);
		$result = $sistema->getResult();
		
		$this->totalPaginas=ceil($num_total_registros / $this->itensPorPagina);

		return $result;

	}
	
	function montarPaginacao(){
		
		//echo '<div class="divSeparadorHorizontal"></div>';
		echo '<div class="boxNumerosPaginacao">';

		if($this->pagina%10==0){
			$np = $this->pagina;
		}
		else{
			$np = $this->pagina;
		}

		if($this->pagina>=10){
			$np = (floor($this->pagina / 10)*10);
			if(($np-10)==0){
				$pgAnterior=1;
			}
			else{
				$pgAnterior=($np-10);
			}
			echo '<a href="http://'.$this->urlPagina.'&pg='.($pgAnterior).'"><div class="boxPaginacaoMaior">'.$pgAnterior.' - '.($np-1).'</div></a>';
		}

		$boxPagina=0;
		
		
		if($np < 10) {$np = 1;}
		for($i=$np;$i<=$this->totalPaginas;$i++){
			
			$boxPagina++;
			
			if(!$this->pagina and $i==$np){
				$estilo = "boxPaginacaoAtivo";
			}
			else if($i==$this->pagina){
				$estilo = "boxPaginacaoAtivo";
			}
			else{
				$estilo = "boxPaginacao";
			}
			
			if($this->pagina<10)
			{
				
				if($boxPagina<10)
				{
					echo '<a href="http://'.$this->urlPagina.'&pg='.$i.'"><div class="'.$estilo.'">'.$i.'</div></a>';
				}
			}
			else
			{
				if($boxPagina<=10)
				{
					echo '<a href="http://'.$this->urlPagina.'&pg='.$i.'"><div class="'.$estilo.'">'.$i.'</div></a>';
				}
			}
		
		}
		//if($this->totalPaginas > 10)
		if($this->totalPaginas > $this->pagina)
		{
			//echo $this->totalPaginas . "</br>";
			//echo $np . "</br>";
			//echo $this->pagina . "</br>";
			if($this->pagina<10)
			{
				//echo $this->totalPaginas;
				$np = 0;
				if($this->totalPaginas >= 10)
				{
					echo '<a href="http://'.$this->urlPagina.'&pg='.($np+10).'"><div class="boxPaginacaoMaior">'.($np+10).' - '.($np+20).'</div></a>';
				}
				
			}
			else
			{
				//echo $np;
				//echo $this->totalPaginas;
				 if($np+10 <= $this->totalPaginas)
				{
					if($np + 20 > $this->totalPaginas)
					{
						echo '<a href="http://'.$this->urlPagina.'&pg='.($np+10).'"><div class="boxPaginacaoMaior">'.($np+10).' - '. $this->totalPaginas .'</div></a>';
					}
					else
					{
						echo '<a href="http://'.$this->urlPagina.'&pg='.($np+10).'"><div class="boxPaginacaoMaior">'.($np+10).' - '.($np + 20).'</div></a>';
					}
				}
				else
				{
					//	echo '<a href="http://'.$this->urlPagina.'&pg='.($np+10).'"><div class="boxPaginacaoMaior">'.($np+10).' - '.($np + 20).'</div></a>';
				} 
					
			}
		}

			echo '</div>';
			echo '<div class="divSeparadorHorizontal"></div>';
		}
		
		
		//Fun��o para converter data do padr�o americano para brasileiro e vice-versa
		//@author Cau� J. Martinez <cauejm@yahoo.com.br>
		public function convertData($data)
		{

			if($data){
				$pos = stripos($data,'-');
				if($pos == true)
				{
					$data = substr($data,0,10);
					$data = explode("-",$data);
					$ano = $data[0];
					$mes = $data[1];
					$dia = $data[2];
					
					$data = $dia . "/" . $mes . "/" . $ano;
					
					return $data;
				}
				else
				{
					$data = explode("/",$data);
					$dia = $data[0];
					$mes = $data[1];
					$ano = $data[2];
					
					$data = $ano . "-" . $mes . "-" . $dia . " " . date("H:i:s");
					
					return $data;
				}
			}
		}
}
	
