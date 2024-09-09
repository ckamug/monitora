<?php
include "../../../../classes/sistema.php";
session_start();

$dados["executora_nome_fantasia"] = $_POST["txtNomeFantasia"];
$dados["executora_razao_social"] = $_POST["txtRazaoSocial"];
$dados["executora_cnpj"] = $_POST["txtCnpj"];
$dados["executora_cnae"] = $_POST["txtCnae"];
$dados["executora_cep"] = $_POST["txtCep"];
$dados["executora_endereco"] = $_POST["txtEndereco"];
$dados["executora_numero"] = $_POST["txtNumero"];
$dados["executora_complemento"] = $_POST["txtComplemento"];
$dados["executora_bairro"] = $_POST["txtBairro"];
$dados["cidade_id"] = intval($_POST["slcMunicipios"]);
$dados["executora_email"] = $_POST["txtEmail"];
$dados["executora_telefone"] = $_POST["txtTelefone"];
$dados["executora_vagas"] = $_POST["txtVagas"];

if(base64_decode($_SESSION["usr"])==1 OR base64_decode($_SESSION["usr"])==18 OR base64_decode($_SESSION["usr"])==22){
    $dados["executora_valor_previsto_rh"] = str_replace("R$ ","",$_POST["txtRh"]);
    $dados["executora_valor_previsto_custeio"] = str_replace("R$ ","",$_POST["txtCusteio"]);
    $dados["executora_valor_previsto_terceiros"] = str_replace("R$ ","",$_POST["txtServicosTerceiros"]);
}


foreach($_POST["chkGenero"] as $genero)
{
    $generos .= $genero . ", ";
}

foreach($_POST["chkServico"] as $servico)
{
    $servicos .= $servico . ", ";
}

$dados["executora_generos"] = $generos;
$dados["executora_servicos_id"] = $servicos;
$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["tipo_responsavel"] = intval($_SESSION["pf"]);
$dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_executoras',$dados);

$dadosResponsavel["executora_id"] = intval($sistema->newID);
$sistema->update("rec_executoras_responsaveis",$dadosResponsavel,"executora_id = 0 AND usuario_id = " . base64_decode($_SESSION["usr"]));
$sistema->update("rec_executoras_casas",$dadosResponsavel,"executora_id = 0 AND usuario_id = " . base64_decode($_SESSION["usr"]));

if(base64_decode($_SESSION["usr"])==1 OR base64_decode($_SESSION["usr"])==18 OR base64_decode($_SESSION["usr"])==22){
    $sistema->select("rec_executoras_casas","*","executora_id = " . $dadosResponsavel["executora_id"],"","executora_casa_id");
    $res = $sistema->getResult();

    for($i=0;$i<count($res);$i++){

        $dadosCasas["casa_valor_previsto_rh"] = str_replace("R$ ","",$_POST["txtRh".$i]);
        $dadosCasas["casa_valor_previsto_custeio"] = str_replace("R$ ","",$_POST["txtCusteio".$i]);
        $dadosCasas["casa_valor_previsto_terceiros"] = str_replace("R$ ","",$_POST["txtServicosTerceiros".$i]);

        $sistema->update("rec_executoras_casas",$dadosCasas,"executora_casa_id = " . $res[$i]["executora_casa_id"]);

    }
}

echo base64_encode($dadosResponsavel["executora_id"]);