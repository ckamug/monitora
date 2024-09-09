<?php
include "../../../../classes/sistema.php";

$senha = base64_encode($_POST['senha']);
$options = ['cost' => 8];
$hash = password_hash($senha,  PASSWORD_DEFAULT, $options);

$sistema = new Sistema();
$sistema->select('rec_usuarios','*','usuario_cpf = "' . $_POST['cpf'] . '"');
$result = $sistema->getResult();

    $dados['usuario_senha'] = $senha;
    $dados['usuario_hash'] = $hash;
    $dados['senha_alterada'] = 1;

    $sistema->update('rec_usuarios',$dados,'usuario_cpf="'.$_POST["cpf"].'"');