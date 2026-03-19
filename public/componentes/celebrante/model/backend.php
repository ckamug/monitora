<?php
require_once('../../../../../classes/sistema.php');


// 1º - COLETAR OS DADOS ENVIADOS PELO JS E ARMAZENAR NA VARIÁVEL ARRAY $dados
// OBS: O ÍNDICE DA VARIÁVEL $dados PRECISA TER O MESMO NOME DO CAMPO DA TABELA UTILIZADA PARA INCLUSÃO
// OBS: APÓS RESGATADOS OS VALORES, ESTES SERÃO ARMAZENADOS NA VARIÁVEL $dadosInsert
$dadosInsert = coletaDados();

// 2º - GRAVAR AS INFORMAÇÕES NA TABELA, ATRAVÉS DA FUNÇÃO inserirDados(), ENVIANDO O RESULTADO DA FUNÇÃO coletaDados() COMO PARÂMETRO
$insert = inserirDados($dadosInsert);


// 3º - TRATAR O RESULTADO DA INSERÇÃO, QUE ESTÁ ARMAZENADO NA VARIÁVEL $insert, E ENVIAR O RESULTADO PARA O JS
if($insert == false)
{
	echo 0;
	exit;
}
else
{
	echo 'Cadastro efetuado com sucesso!';
	exit;
}

// FUNÇÃO PARA COLETAR DADOS ENVIADOS PELO JS
function coletaDados()
{
	$sistema = new Sistema();
	$dados['campo_tabela1'] = $_POST['campo1'];
	$dados['campo_tabela2'] = $_POST['campo2'];
	$dados['campo_tabela3'] = $_POST['campo3'];

	$dados['dt_cadastro'] = $sistema->convertData(date('d/m/Y'));
	return $dados;
}

// FUNÇÃO PARA INSERIR DADOS
function inserirDados($dadosInsert)
{
	$tabela = 'NOME_DA_TABELA';	
	$sistema = new Sistema();
	$sistema->insert($tabela,$dadosInsert);
	$result = $sistema->getResult();
	return $result;
}