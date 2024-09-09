<?php
include "../../../../classes/sistema.php";
session_start();

$prestacao = $_POST["prestacao"];
$nota = $_POST["nota"];

$sistema = new Sistema();

$dados["data_alteracao"] = date("Y-m-d H:i:s");
$dados["nota_status"] = intval(5);

$sistema->update("rec_notas_fiscais",$dados,"nota_fiscal_id = " . $nota);

$arquivo = '../model/anexos/' . $prestacao . '/' . $nota . '.pdf';
    
    if(file_exists( $arquivo )){
        unlink($arquivo);
        if (strpos($arquivo, 'prestacoes') !== false) {
            echo 1;
        }
        else{
            echo 0;
        }
    }
    else{
        echo "Arquivo não encontrado";
    }