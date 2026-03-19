<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();
//$sistema->debug=true;
$campos = 'a.* , b.usuario_vinculo_id , b.perfil_id as perfil , b.executora_id , b.celebrante_id , c.executora_nome_fantasia , d.celebrante_nome_fantasia , e.perfil_descricao , f.municipio_orgao_publico';
$from = 'rec_usuarios a';
$innerJoin[] = 'inner join rec_usuarios_vinculos b on a.usuario_id = b.usuario_id';
$innerJoin[] = 'left join rec_executoras c on b.executora_id = c.executora_id';
$innerJoin[] = 'left join rec_celebrantes d on b.celebrante_id = d.celebrante_id';
$innerJoin[] = 'left join rec_perfis e on b.perfil_id = e.perfil_id';
$innerJoin[] = 'left join rec_municipios f on b.municipio_id = f.municipio_id';

$where = 'a.usuario_id = ' . base64_decode($_SESSION['usr']);

//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'');
$result = $sistema->getResult();

$_SESSION["qtdvinculos"] = count($result);

for($i=0;$i<count($result);$i++){

    switch($result[$i]["perfil"]){
        case 1:
            $nomeLocal = utf8_encode($result[$i]["perfil_descricao"]);
        break;
        case 2:
            $nomeLocal = utf8_encode($result[$i]["celebrante_nome_fantasia"]);
        break;
        case 3:
            $nomeLocal = utf8_encode($result[$i]["municipio_orgao_publico"]);
        break;
        case 4:
            $nomeLocal = utf8_encode($result[$i]["executora_nome_fantasia"]);
        break;
        case 7:
            $nomeLocal = utf8_encode($result[$i]["perfil_descricao"]);
        break;
        case 8:
            $nomeLocal = utf8_encode($result[$i]["perfil_descricao"]);
        break;
        default:
            $nomeLocal = "-";
        break;
    }
    
    echo '<div class="card col-md-5" style="cursor:pointer;position:relative;float:left;margin-left:5px;height:150px;" onclick="direcionaUsuario('.$result[$i]["usuario_vinculo_id"].')">';
    echo '    <div class="row g-0">';
    echo '        <div class="col-md-3 text-center">';
    echo '          <img src="images/imgLocal.png" width="100px" class="img-fluid rounded-start" alt="Local">';
    echo '        </div>';
    echo '        <div class="col-md-9">';
    echo '          <div class="card-body">';
    echo '               <h5 class="card-title">'.$nomeLocal.'</h5>';
    echo '                <p class="card-text">Perfil: '.utf8_encode($result[$i]["perfil_descricao"]).'</p>';
    echo '          </div>';
    echo '        </div>';
    echo '    </div>';
    echo '</div>';

}
