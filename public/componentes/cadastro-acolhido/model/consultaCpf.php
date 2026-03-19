<?php
include "../../../../classes/sistema.php";
include_once __DIR__ . "/nis.php";
session_start();

$cpf = isset($_POST["cpf"]) ? $_POST["cpf"] : "";
$nis = normalizarNis(isset($_POST["nis"]) ? $_POST["nis"] : "");
$idAtual = isset($_POST["id"]) ? $_POST["id"] : "";
$idAtualNumerico = isset($_POST["id_atual"]) ? $_POST["id_atual"] : "";

if (!nisEhValido($nis)) {
    responderNisInvalidoJson();
}

if (is_numeric($idAtualNumerico) && intval($idAtualNumerico) > 0) {
    $idAtual = intval($idAtualNumerico);
} else if ($idAtual != "") {
    $idAtual = urldecode($idAtual);
    if (is_numeric($idAtual)) {
        $idAtual = intval($idAtual);
    } else {
        $idAtual = base64_decode($idAtual);
        if (!is_numeric($idAtual)) {
            $idAtual = 0;
        } else {
            $idAtual = intval($idAtual);
        }
    }
} else {
    $idAtual = 0;
}

$retorno = array(
    "usuario_existe" => false,
    "acolhido" => null
);

// if ($cpf != "" || $nis != "") {
//     $sistema = new Sistema();
//     $sistema->select(
//         "rec_acolhidos",
//         "acolhido_id, acolhido_cpf",
//         '(acolhido_cpf = "' . $cpf . '" OR acolhido_nis = "' . $nis . '") AND (acolhido_cpf <> "" OR acolhido_nis <> "")',
//         "",
//         "data_cadastro DESC LIMIT 1"
//     );
//     $resAcolhido = $sistema->getResult();

//     if (!empty($resAcolhido) && is_array($resAcolhido)) {
//         $retorno["usuario_existe"] = true;
//         $retorno["acolhido"] = $resAcolhido[0];
//     }
// }

if ($cpf != "" || $nis != "") {
    $sistema = new Sistema();

    $condicoes = array();

    if ($cpf != "") {
        $condicoes[] = 'acolhido_cpf = "' . $cpf . '"';
    }

    if ($nis != "") {
        $condicoes[] = 'acolhido_nis = "' . $nis . '"';
    }

    $where = implode(" OR ", $condicoes);
    if ($idAtual > 0) {
        $where = "(" . $where . ") AND acolhido_id <> " . $idAtual;
    }

    $sistema->select(
        "rec_acolhidos",
        "acolhido_id, acolhido_cpf, acolhido_nis",
        $where,
        "",
        "data_cadastro DESC LIMIT 1"
    );

    $resAcolhido = $sistema->getResult();

    if (!empty($resAcolhido) && is_array($resAcolhido)) {
        $retorno["usuario_existe"] = true;
        $retorno["acolhido"] = $resAcolhido[0];
    }
}


header("Content-Type: application/json; charset=utf-8");
echo json_encode($retorno);
