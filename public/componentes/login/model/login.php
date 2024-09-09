<?php
include "../../../../classes/sistema.php";
session_start();

$cpf = $_POST['cpf'];
$senha = $_POST['senha'];

if($senha==$cpf){
    $senha = str_replace(".","",str_replace("-","",$senha));
}

$sistema = new Sistema();
//$sistema->debug=true;

$sistema->select('rec_usuarios','*','usuario_cpf = "' . $cpf . '" AND usuario_senha = "'.base64_encode($senha).'" AND usuario_status = 1');
$result = $sistema->getResult();

if($result){

    if(password_verify(base64_encode($senha),$result[0]["usuario_hash"])){

        if(count($result)==1){

            //$sistema->debug=true;
            $campos = 'a.* , b.*';
            $from = 'rec_usuarios a';
            $innerJoin[] = 'left join rec_usuarios_vinculos b on a.usuario_id = b.usuario_id';
            $where = "a.usuario_status = 1 AND a.usuario_id = " . $result[0]["usuario_id"];
            $sistema->innerJoin($campos,$from,$innerJoin,$where,'','');
            $resUsuario = $sistema->getResult();

            if(count($resUsuario)==1){
                
                $_SESSION['usr'] = base64_encode($result[0]["usuario_id"]);
                $_SESSION['pf'] = $resUsuario[0]["perfil_id"];
                
                if($resUsuario[0]["perfil_id"]==2){
                    $_SESSION['pfv'] = $resUsuario[0]["celebrante_id"];
                }
                else if($resUsuario[0]["perfil_id"]==4){
                    $_SESSION['pfv'] = $resUsuario[0]["executora_id"];
                }
                else{
                    $_SESSION['pfv'] = intval(0);
                }
                
                $_SESSION['nm'] = utf8_encode($result[0]["usuario_nome"]);
                $_SESSION['hs'] = $result[0]["usuario_hash"];
                
                
                if($resUsuario[0]["perfil_id"]==1){
                    echo 'area-restrita';
                }
                else if($resUsuario[0]["perfil_id"]==2){
                    echo 'area-restrita';
                }
                else if($resUsuario[0]["perfil_id"]==3){
                    echo 'area-restrita';
                }
                else if($resUsuario[0]["perfil_id"]==4){
                    echo 'prestacoes';
                }
                else if($resUsuario[0]["perfil_id"]==5){
                    echo 'area-restrita';
                }
                else if($resUsuario[0]["perfil_id"]==6){
                    echo 'area-restrita';
                }
                else{}
            }
            else if(count($resUsuario)>1){

                $_SESSION['usr'] = base64_encode($result[0]["usuario_id"]);
                $_SESSION['nm'] = utf8_encode($result[0]["usuario_nome"]);
                $_SESSION['hs'] = $result[0]["usuario_hash"];

                echo 1;
            
            }
            else{
                echo 0;
            }

        }


    }
    else{
        echo 0;
    }

}else{
    echo 0;
}