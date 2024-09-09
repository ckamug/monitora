<?php
include "../../../../classes/sistema.php";
session_start();

$url = explode('/' , $_SERVER["REQUEST_URI"]);

if(isset($_SESSION['usr'])){
    
    $sistema = new Sistema();
    $campos = 'a.usuario_nome , b.perfil_id as perfil , b.executora_id , b.celebrante_id , c.executora_nome_fantasia , d.celebrante_nome_fantasia , e.perfil_descricao , f.municipio_orgao_publico';
    $from = 'rec_usuarios a';
    $innerJoin[] = 'inner join rec_usuarios_vinculos b on a.usuario_id = b.usuario_id';
    $innerJoin[] = 'left join rec_executoras c on b.executora_id = c.executora_id'; 
    $innerJoin[] = 'left join rec_celebrantes d on b.celebrante_id = d.celebrante_id';
    $innerJoin[] = 'left join rec_municipios f on b.municipio_id = f.municipio_id';
    $innerJoin[] = 'left join rec_perfis e on b.perfil_id = e.perfil_id';
    
    if($_SESSION["pf"]==2){
        $where = 'a.usuario_id = "' . base64_decode($_SESSION["usr"]) . '" AND b.celebrante_id = ' . $_SESSION["pfv"];
    }else if($_SESSION["pf"]==4){
        $where = 'a.usuario_id = "' . base64_decode($_SESSION["usr"]) . '" AND b.executora_id = ' . $_SESSION["pfv"];
    }else if($_SESSION["pf"]==3){
        $where = 'a.usuario_id = "' . base64_decode($_SESSION["usr"]) . '" AND b.municipio_id = ' . $_SESSION["pfv"];
    }
    else{
        $where = 'a.usuario_id = "' . base64_decode($_SESSION["usr"]) . '" AND b.perfil_id = ' . $_SESSION["pf"];
    }

    //$sistema->debug=true;
    $sistema->innerJoin($campos,$from,$innerJoin,$where,'');
    $result = $sistema->resultToJSON();

    echo str_replace("[" , "" , str_replace("]" , "" , $result));
    
}
else{
    echo 0;
}