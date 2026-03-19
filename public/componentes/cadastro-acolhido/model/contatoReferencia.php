<?php

function resolverIdAcolhidoOuTemporario($valor)
{
    $valor = trim((string) $valor);

    if ($valor === "") {
        return "";
    }

    if (preg_match('/^-?\d+$/', $valor)) {
        return $valor;
    }

    $decodificado = base64_decode(urldecode($valor), true);
    if ($decodificado !== false && preg_match('/^\d+$/', $decodificado)) {
        return $decodificado;
    }

    return "";
}
