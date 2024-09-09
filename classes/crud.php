<?php
/*
 * Arquivo Nome: crud.php
 * Data: 14/12/2010
 * 
*/

class crud
{
    private $db_host = '10.22.0.106';
    private $db_user = 'dbrecomeco';
    private $db_pass = 'Rec0mec02022!';
    private $db_name = 'recomeco';
    private $db_port = '32769';
    public $Xform;
    private $result = array();
	public $newID;
    public $rowsAffected;
	public $ShowQuery;
	
	public function setter($key, $value)
	{
		$this->$key = $value;
	}
	
	public function getter($key)
	{
		return $this->$key;
	}
	
    public function connect()
    {
		$this->link = mysqli_connect($this->db_host,$this->db_user,$this->db_pass,$this->db_name,$this->db_port);
        //$this->link = mysqli_connect($this->db_host,$this->db_user,$this->db_pass,$this->db_name) or die("Erro de Conexão");
        if($this->link){
            mysqli_select_db($this->link,$this->db_name);
        }
        else
        {
            echo "Erro de Conexão";
        }
    }

    public function setDatabase($name)
    {
        if($this->link)
        {
            if(mysqli_close())
            {
                $this->con = false;
                $this->results = null;
                $this->db_name = $name;
                $this->connect();
            }
        }
    }

        public function select($table, $rows = '*', $where = null, $group = null, $order = null)
    {

        $q = '';
        $q = 'SELECT '.$rows.' FROM '.$table;
        if($where != null)
            $q .= ' WHERE '.$where;
        if($group != null)
            $q .= ' GROUP BY '.$group;
        if($order != null)
            $q .= ' ORDER BY '.$order;

           if($this->ShowQuery == true)
           {
			echo $q;
			exit;
           }

        $query = mysqli_query($this->link , $q) or die (mysqli_error($this->link));
		
		if($query)
        {
            $this->numResults = mysqli_num_rows($query);
            for($i = 0; $i < $this->numResults; $i++)
            {
                $r = mysqli_fetch_array($query);
                $key = array_keys($r);
                for($x = 0; $x < count($key); $x++)
                {
                    if(!is_int($key[$x]))
                    {
                        if(mysqli_num_rows($query) >= 1)
                            $this->result[$i][$key[$x]] = $r[$key[$x]];
                        else if(mysqli_num_rows($query) < 1)
                            $this->result = null;
                        else
                            $this->result[$key[$x]] = $r[$key[$x]];
                    }
                }
            }
            return true;
        }
        else
        {
			return false;
        }
    }

    public function innerJoin($campos, $from, $innerJoin, $where = null, $group = null, $order = null)
    {
        $join = "";
		$totalJoins = sizeof($innerJoin);
		
		for($i=0;$i<$totalJoins;$i++){
			$join .= ' ' . $innerJoin[$i];
		}
		
		$q = '';
        $q = 'SELECT '.$campos.' FROM '.$from.$join;
        if($where != null)
            $q .= ' WHERE '.$where;
        if($group != null)
            $q .= ' GROUP BY '.$group;
        if($order != null)
            $q .= ' ORDER BY '.$order;
				//echo $q;
				//exit;
           if($this->ShowQuery == true)
           {
			echo $q;
			exit;
           }
		//echo $q;
		//exit;		
        $query = mysqli_query($this->link , $q) or die (mysqli_error($this->link));
        
		if($query)
        {
            $this->numResults = mysqli_num_rows($query);
            for($i = 0; $i < $this->numResults; $i++)
            {
                $r = mysqli_fetch_array($query);
                $key = array_keys($r);
                for($x = 0; $x < count($key); $x++)
                {
                    if(!is_int($key[$x]))
                    {
                        if(mysqli_num_rows($query) >= 1)
                            $this->result[$i][$key[$x]] = $r[$key[$x]];
                        else if(mysqli_num_rows($query) < 1)
                            $this->result = null;
                        else
                            $this->result[$key[$x]] = $r[$key[$x]];
                    }
                }
            }
            return true;
        }
        else
        {
			return false;
        }
    }

	public function insert($table,$values,$rows = null)
    {
        
            $insert = 'INSERT INTO '.$table;
            if($rows != null)
            {
                $insert .= ' ('.$rows.')';
            }

            /*for($i = 0; $i < count($values); $i++)
            {
                if(is_string($values[$i]))
                    $values[$i] = ''.$values[$i].'"';
            }*/
            $values = implode(',',$values);
            $insert .= ' VALUES ('.$values.')';
        	if($this->ShowQuery == true)
           	{
				echo $insert;
				exit;
           	}
			//echo $insert;
			//exit;
			mysqli_query($this->link , "SET NAMES 'utf8'");
			mysqli_query($this->link , 'SET character_set_connection=utf8');
			mysqli_query($this->link , 'SET character_set_client=utf8');
			mysqli_query($this->link , 'SET character_set_results=utf8');
            $ins = mysqli_query($this->link , $insert) or die (mysqli_error($this->link));

            if($ins)
            {
				$this->result = true;
				/*echo mysql_insert_id();
				exit;*/
				$this->newID = mysqli_insert_id($this->link);
				$this->SetSession();
                return true;
            }
            else
            {
				$this->result = false;
                return false;
            }
    }

    public function delete($table,$where = null)
    {
		
		$this->connect();
		if($where == null)
		{
			$delete = 'DELETE '.$table;
		}
		else
		{
			$delete = 'DELETE FROM '.$table.' WHERE ' . $where;
		}
		//echo $delete;
		//exit;
		$del = mysqli_query($this->link , $delete) or die (mysqli_error($this->link));
		if($del)
		{
			return true;
		}
		else
		{
			return false;
		}
    }
    
	public function update($table,$rows,$where)
    {

        for($i = 0; $i < 1; $i++)
        {
            if($i%2 != 0)
            {
                if(is_string($where[$i]))
                {
                    if(($i+1) != null)
                        $where[$i] = '"'.$where[$i].'" AND ';
                    else
                        $where[$i] = '"'.$where[$i].'"';
                }
            }
        }
        //$where = implode('',$where);

        $update = 'UPDATE '.$table.' SET ';
        $keys = array_keys($rows);
        
        for($i = 0; $i < count($rows); $i++)
        {
            if(is_string($rows[$keys[$i]]))
            {
                $update .= $keys[$i].'="'.$rows[$keys[$i]].'"';
            }
            else
            {
                $update .= $keys[$i].'='.$rows[$keys[$i]];
            }

            if($i != count($rows)-1)
            {
                $update .= ',';
            }
        }
        $update .= ' WHERE '.$where;
        if($this->ShowQuery == true)
        {
            
            echo $update;
            exit;
        }
        mysqli_query($this->link , "SET NAMES 'utf8'");
        mysqli_query($this->link , 'SET character_set_connection=utf8');
        mysqli_query($this->link , 'SET character_set_client=utf8');
        mysqli_query($this->link , 'SET character_set_results=utf8');
        $query = mysqli_query($this->link , $update) or die (mysqli_error($this->link));
        
        $this->rowsAffected = 1;
        
        //$this->rowsAffected = mysqli_affected_rows($this->link);

        if($query)
        {
            
            return true;
        }
        else
        {
            return false;
        }

    }
    public function getResult()
    {
        return $this->result;
    }
	public function SetSession()
	{
		//session_start();
		$_SESSION['sessionForIdInserted'] = $this->newID;
	}

    public function getAffectedRows(){
        return $this->rowsAffected;
    }
}
?>