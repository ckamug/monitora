<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
//$sistema->select("rec_usuarios","perfil_id , perfil_vinculo_id","usuario_id = " . base64_decode($_SESSION["usr"]) , "" , "");
//$result = $sistema->getResult();


$sistema->select('rec_notas_fiscais','*','nota_fiscal_id = ' . $_POST["id"]);
$result = $sistema->getResult();

$alteraCabecalhoRubrica = 0;
$alteraValores = 0;

if($result[0]["categoria_id"]!=$_POST["slcCategorias"] AND str_replace("R$ " , "" , $_POST["txtValorNotaFiscal"]) == $result[0]["valor_nota"]){
    $alteraCabecalhoRubrica = 1;
    $alteraValores = 0;
}
else if($result[0]["categoria_id"]!=$_POST["slcCategorias"] AND str_replace("R$ " , "" , $_POST["txtValorNotaFiscal"]) != $result[0]["valor_nota"]){
    $alteraCabecalhoRubrica = 1;
    $alteraValores = 1;
}
else if($result[0]["categoria_id"]==$_POST["slcCategorias"] AND str_replace("R$ " , "" , $_POST["txtValorNotaFiscal"]) != $result[0]["valor_nota"]){
    $alteraCabecalhoRubrica = 0;
    $alteraValores = 1;
}
else{

}

$dir = "anexos/" . base64_decode($_POST["hidIdPrestacao"]);

if(file_exists( $dir . "/" . $_POST["id"] . ".pdf" ) or $_FILES["arquivo"]['tmp_name']==""){

    $dados["data_nota_fiscal"] = $_POST["txtDataNota"];
    $dados["numero_nota_fiscal"] = $_POST["txtNumeroNotaFiscal"];
    $dados["categoria_id"] = $_POST["slcCategorias"];
    $dados["subcategoria_id"] = $_POST["slcSubcategorias"];
    $dados["valor_nota"] = str_replace("R$ " , "" , $_POST["txtValorNotaFiscal"]);
    $dados["data_pagamento"] = $_POST["txtDataPagamento"];

    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->update('rec_notas_fiscais',$dados,'nota_fiscal_id = ' . $_POST["id"]);

    $dadosUpdate["nota_status"] = intval(1);
    $sistema->update('rec_notas_fiscais',$dadosUpdate,'nota_fiscal_id = ' . $_POST["id"]);
    
    if($alteraCabecalhoRubrica == 1 AND $alteraValores == 0){
                
        $retorno = array(intval($result[0]["categoria_id"]),98);
        echo json_encode($retorno); 

    }
    else if($alteraCabecalhoRubrica == 1 AND $alteraValores == 1){
        
        $retorno = array(intval($result[0]["categoria_id"]),99,$result[0]["valor_nota"]);
        echo json_encode($retorno); 
    
    }
    else if($alteraCabecalhoRubrica == 0 AND $alteraValores == 1){
        
        $retorno = array(intval($result[0]["categoria_id"]),97,$result[0]["valor_nota"]);
        echo json_encode($retorno); 
    
    }
    else{
        echo 3;
    }

}
else{

    if(!is_dir($dir)){
        mkdir($dir, 0755, true);
    }

    $ext = extensao($_FILES["arquivo"]['name']);
    $filename = $_POST["id"] . '.'.$ext;
    $src = $_FILES["arquivo"]['tmp_name'];

    if($ext=="pdf"){
        
        $output_dir = $dir . "/" . $filename;
        
        if(move_uploaded_file($src, $output_dir )){

            $dados["data_nota_fiscal"] = $_POST["txtDataNota"];
            $dados["numero_nota_fiscal"] = $_POST["txtNumeroNotaFiscal"];
            $dados["categoria_id"] = $_POST["slcCategorias"];
            $dados["subcategoria_id"] = $_POST["slcSubcategorias"];
            $dados["valor_nota"] = str_replace("R$ " , "" , $_POST["txtValorNotaFiscal"]);
            $dados["data_pagamento"] = $_POST["txtDataPagamento"];

            $sistema = new Sistema();
            //$sistema->debug=true;
            $sistema->update('rec_notas_fiscais',$dados,'nota_fiscal_id = ' . $_POST["id"]);

            $dadosUpdate["nota_status"] = intval(1);
            $sistema->update('rec_notas_fiscais',$dadosUpdate,'nota_fiscal_id = ' . $_POST["id"]);

            if($alteraCabecalhoRubrica == 1 AND $alteraValores == 0){
                
                $retorno = array(intval($result[0]["categoria_id"]),98);
                echo json_encode($retorno); 
        
            }
            else if($alteraCabecalhoRubrica == 1 AND $alteraValores == 1){
                
                $retorno = array(intval($result[0]["categoria_id"]),99,$result[0]["valor_nota"]);
                echo json_encode($retorno); 
            
            }
            else if($alteraCabecalhoRubrica == 0 AND $alteraValores == 1){
        
                $retorno = array(intval($result[0]["categoria_id"]),97,$result[0]["valor_nota"]);
                echo json_encode($retorno); 
            
            }
            else{
                echo 2;
            }

        }else{
            echo 1; //Erro no envio do arquivo
        }

    }
    else{
        echo 0; //Formato de arquivo inválido
    }

}

function extensao($arquivo){
    $arquivo = strtolower($arquivo);
    $explode = explode(".", $arquivo);
    $arquivo = end($explode);
    
    return ($arquivo);
}