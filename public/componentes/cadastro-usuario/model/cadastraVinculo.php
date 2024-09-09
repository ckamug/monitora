<?php
include "../../../../classes/sistema.php";
session_start();

if($_POST["perfil"]==1 or $_POST["perfil"]==2 or $_POST["perfil"]==6){
    $subPerfil = 0;
}
else{
    $subPerfil = $_POST["subperfil"];
}

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_usuarios_vinculos","usuario_vinculo_id","(usuario_id = " . base64_decode($_POST["id"]) . " AND perfil_id = " . $_POST["perfil"] . ") AND (executora_id = " . $subPerfil . " OR celebrante_id = " . $subPerfil . " OR ( executora_id = 0 AND celebrante_id = 0))");
$result = $sistema->getResult();


if(count($result)==0){

    $dados["usuario_id"] = base64_decode($_POST["id"]);
    $dados["perfil_id"] = $_POST["perfil"];

    switch($_POST["perfil"]){
        case 2:
            $dados["celebrante_id"] = $_POST["subperfil"];
        break;
        case 3:
            $dados["municipio_id"] = $_POST["subperfil"];
        break;
        case 4:
            $dados["executora_id"] = $_POST["subperfil"];
        break;
    }

    $dados["usuario_cadastro_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d h:i:s");

    $sistema = new Sistema();
    //$sistema->debug=true;
    $sistema->insert('rec_usuarios_vinculos',$dados);

    echo 1;

}
else{
    echo 0;
}