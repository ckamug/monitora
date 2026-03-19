<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_usuarios","*");
$result = $sistema->getResult();

for($i=0;$i<count($result);$i++){
    
    $dados["usuario_id"] = intval($result[$i]["usuario_id"]);
    $dados["perfil_id"] = intval($result[$i]["perfil_id"]);
    if($result[$i]["perfil_id"]==4){
        $dados["executora_id"] = intval($result[$i]["perfil_vinculo_id"]);
        $dados["celebrante_id"] = intval(0);
    }else if($result[$i]["perfil_id"]==2){
        $dados["executora_id"] = intval(0);
        $dados["celebrante_id"] = intval($result[$i]["perfil_vinculo_id"]);
    }
    else{
        $dados["celebrante_id"] = intval(0);
        $dados["executora_id"] = intval(0);
    }
    
    $dados["usuario_cadastro_id"] = intval($result[$i]["usuario_cadastro_id"]);
    $dados["data_cadastro"] = date("Y-m-d h:i:s");
    
    $sistema = new Sistema();
    //$sistema->debug=true;
    //$sistema->insert('rec_usuarios_vinculos',$dados);


    echo $i . "<br>";
}