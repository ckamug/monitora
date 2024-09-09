<?php

/*
- Classe para validar se o usu�rio tem acesso a determinado m�dulo da Plataforme Recomeço
- @access (public)
- @author ---
- @version 1.0.1
- @param $usuarioID - ID do usu�rio
- @param $usuarioNome - Nome do usu�rio
- @param $aplicacao - Nome da aplica��o
- @param $conexao - Resultado da conex�o com o banco de dados
- @param $bd - Resultado da sele��o do banco de dados
- @param $result - Select da busca no banco de dados
- @param $row - Linha do select da busca no banco de dados
- @param $informacoesUsuario - Informa��es sobre o usu�rio
- @param $textoSup - Texto da parte superior da mensagem
- @param $textoInf - Texto da parte inferior da mensagem
- @param $verifica - Autoriza��o do usu�rio
*/


//session_start();
final class validaLogin
{
	protected static $usuarioID;
	protected static $usuarioNome;
	protected $aplicacao;
	protected $conexao;
	protected $bd;
	protected $result;
	protected $row;
	protected $informacoesUsuario;
	protected $textoSup;
	protected $textoInf;
	protected static $verifica;
	
/*
- Fun��o que inicia a classe
- access public
*/

	function __construct()
	{
		$usuarioID = $_COOKIE["userID"];
		$usuarioNome = $_COOKIE["userNome"];
		$usuarioDepto = $_COOKIE["userDepartamento"];
	
		$_SESSION['departamentoUsuario'] = $usuarioDepto;		
	
        if($usuarioID != '' && $usuarioNome != '')
		{
		  //session_start();
		  if($_SESSION['userID'] && $_SESSION['nomeUsuario'])
		  {
		    $this->validacao($usuarioID,$usuarioNome);
		  }
		  else
		  {
		    $_SESSION['userID'] = $usuarioID;
		    $_SESSION['nomeUsuario'] = $usuarioNome;
			$_SESSION['departamentoUsuario'] = $usuarioDepto;
		    $this->validacao($usuarioID,$usuarioNome);
		  }
		}
		else
		{
		  $textoSup = "Voc� n�o est� logado! Clique no link abaixo para acessar a Intranet e depois clique em voltar!";
		  $textoInf = "<a href='http://intranet/seds/index.asp?mode=patrimonio' title='P�gina de Login'>Intranet</a>";
		  $this->exibeTexto($textoSup,$textoInf);
		  exit;
		}	
	}//fecha __construct()
	
/*
- Fun��o para verificar se o usu�rio tem autoriza��o para acessar a aplica��o
- access public
*/

	function validacao($usuarioID,$usuarioNome)
	{
	  $this->conectaBD();
	  $aplicacao = $this->buscaAplicacao($_GET['lnk']);
	  $informacoesUsuario = $this->verificaUsuario($usuarioID,$aplicacao);
	  if($informacoesUsuario == 0)
	  {
	    $textoSup = utf8_decode ($_SESSION['nomeUsuario']) . ", voc� n�o tem autoriza��o para acessar esta aplica��o!!";
	    $textoInf = "Contate o administrador!!";
		$this->exibeTexto($textoSup,$textoInf);
		exit;
	  }
	  else
	  {
	    $this->executa($informacoesUsuario);
	  }
	}//fecha fun��o validacao()
	
/*
- Fun��o para criar as sess�es
- Access public
*/

	function executa($informacoesUsuario)
	{
	  $_SESSION['permissaoConsulta'] = $informacoesUsuario[2];
	  $_SESSION['permissaoCadastro'] = $informacoesUsuario[3];
	  $_SESSION['permissaoAlteracao'] = $informacoesUsuario[4];
	  $_SESSION['permissaoExclusao'] = $informacoesUsuario[5];
	}//fecha fun��o executa()
	
/*
- Fun��o para exibir mensagem
- access public
*/

	function exibeTexto($textoSup,$textoInf)
	{
		    echo "<div class ='fieldset'>";
		      echo "<div class = 'dvImageBlock'>";
		        echo "<img src='images/block.png'/>";
		      echo "</div>";//fecha dvImageBlock
		      echo "<div class = 'dvTexto'>";
			    echo $textoSup;
			  echo "</div>";//fecha dvTexto
			  echo "<div class = 'dvTexto'>";
			    echo $textoInf;
			  echo "</div>";//fecha dvTexto
		    echo "</div>";//fecha fieldset
	}//fecha fun��o exibeTexto()
	
/*
- Fun��o para conectar no banco de dados
- access public
*/

	function conectaBD()
	{
	  $conexao = mssql_connect("SRV-INTRANET","sa","r39@ad") or die("N�o foi possivel conectar ao banco de dados!!");
	  $bd = mssql_select_db("Intranet",$conexao) or die("N�o foi possivel selecionar o banco de dados!!");
  
	}//fecha fun��o conectaBD()
	
/*
- Fun��o para buscar o id da aplica��o
- access public
- return int
*/


	function buscaAplicacao($aplicacao)
	{
	  $result = mssql_query("SELECT codaplicacao FROM sec_aplicacoes WHERE modulo = '" . $aplicacao . "'");
	  $row = mssql_fetch_array($result);
	  $row = $row[0];
	  return $row;
	}//buscaAplicacao()
	
/*
- Fun��o para verificar se o usu�rio tem autoriza��o para acessar a determinada aplica��o
- access public
- return int ou string
*/

	function verificaUsuario($usuarioID,$aplicacao)
	{
	  $result = mssql_query('SELECT usu_id,codaplicacao,pms_consulta,pms_cadastro,pms_alteracao,pms_exclusao FROM tbl_permissao WHERE usu_id = ' . $usuarioID);
	  $verifica = 0;
	  
	  while($row = mssql_fetch_array($result))
	  {

		if($row[1] == $aplicacao)
		{
		  $verifica = 1;
		  $informacoesUsuario[0] = $row[0];
		  $informacoesUsuario[1] = $row[1];
		  $informacoesUsuario[2] = $row[2];
		  $informacoesUsuario[3] = $row[3];
		  $informacoesUsuario[4] = $row[4];
		  $informacoesUsuario[5] = $row[5];
		}
	  }//fecha while
	  if($verifica == 0)
	  {
	    return $verifica;
	  }
	  else
	  {
	    return $informacoesUsuario;
	  }
	}//verificaUsuario()

}//fecha classe