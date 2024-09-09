<?php
include "../../../../classes/sistema.php";
session_start();

    $dados["prestacao_id"] = intval(base64_decode($_POST["id"]));
    $dados["apontamento_descricao"] = $_POST["apontamento"];
    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");

    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->insert('rec_apontamentos_documentos_complementares',$dados);