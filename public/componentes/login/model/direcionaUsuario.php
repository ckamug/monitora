<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
$campos = 'a.usuario_nome , b.perfil_id as perfil , b.executora_id , b.celebrante_id , b.municipio_id , c.executora_nome_fantasia , d.celebrante_nome_fantasia , e.perfil_descricao , f.municipio_orgao_publico';
$from = 'rec_usuarios a';
$innerJoin[] = 'inner join rec_usuarios_vinculos b on a.usuario_id = b.usuario_id';
$innerJoin[] = 'left join rec_executoras c on b.executora_id = c.executora_id';
$innerJoin[] = 'left join rec_celebrantes d on b.celebrante_id = d.celebrante_id';
$innerJoin[] = 'left join rec_perfis e on b.perfil_id = e.perfil_id';
$innerJoin[] = 'left join rec_municipios f on b.municipio_id = f.municipio_id';

$where = 'b.usuario_vinculo_id = ' . $_POST["id"];

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'');
$result = $sistema->getResult();

$_SESSION['pf'] = $result[0]["perfil"];

switch($result[0]["perfil"]){
    case 1:
        $_SESSION['pfv'] = 0;
    break;
    case 2:
        $_SESSION['pfv'] = $result[0]["celebrante_id"];
        $_SESSION['vnm'] = $result[0]["celebrante_nome_fantasia"];
    break;
    case 3:
        $_SESSION['pfv'] = $result[0]["municipio_id"];
        $_SESSION['vnm'] = $result[0]["municipio_orgao_publico"];
    break;
    case 4:
        $_SESSION['pfv'] = $result[0]["executora_id"];
        $_SESSION['vnm'] = $result[0]["executora_nome_fantasia"];
    break;
    default:
        $_SESSION['vnm'] = "-";
    break;
}

echo 'area-restrita';