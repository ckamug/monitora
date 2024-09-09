<?php

    $prestacao = $_POST["prestacao"];
    $nota = $_POST["nota"];

    if($nota==0){
        $arquivo = $prestacao;
    }
    else{
        $arquivo = '../model/anexos/' . $prestacao . '/' . $nota . '.pdf';
    }

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