<?php

function normalizarNis($valor)
{
    return preg_replace('/\D+/', '', (string) $valor);
}

function nisEhValido($nis)
{
    return $nis === "" || strlen($nis) === 11;
}

function abortarNisInvalidoTexto()
{
    http_response_code(422);
    echo "Informe o NIS com 11 digitos.";
    exit;
}

function responderNisInvalidoJson()
{
    http_response_code(422);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(array(
        "usuario_existe" => false,
        "acolhido" => null,
        "nis_invalido" => true,
        "mensagem" => "Informe o NIS com 11 digitos."
    ));
    exit;
}
