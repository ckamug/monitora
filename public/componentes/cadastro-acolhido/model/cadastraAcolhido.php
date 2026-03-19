<?php
include "../../../../classes/sistema.php";
include_once __DIR__ . "/contatoReferencia.php";
include_once __DIR__ . "/nis.php";
session_start();

$sistema = new Sistema();
$nis = normalizarNis(isset($_POST["txtNis"]) ? $_POST["txtNis"] : "");
$contatoReferenciaTempId = resolverIdAcolhidoOuTemporario(isset($_POST["hidContatoReferenciaTempId"]) ? $_POST["hidContatoReferenciaTempId"] : "");

if (!nisEhValido($nis)) {
    abortarNisInvalidoTexto();
}

$dados["acolhido_nome_completo"] = $_POST["txtNomeCompleto"];
$dados["acolhido_data_nascimento"] = $sistema->convertData($_POST["txtDataNascimento"]);
$dados["acolhido_sexo"] = $_POST["slcSexo"];
$dados["acolhido_nome_social"] = $_POST["txtNomeSocial"];
$dados["acolhido_identidade_genero"] = $_POST["slcIdentidadeGenero"];
$dados["acolhido_orientacao_sexual"] = $_POST["slcOrientacaoSexual"];
$dados["acolhido_filiacao1"] = $_POST["txtFiliacao1"];
$dados["acolhido_filiacao2"] = $_POST["txtFiliacao2"];
$dados["acolhido_filiacao3"] = $_POST["txtFiliacao3"];
$dados["acolhido_estado_civil"] = $_POST["slcEstadoCivil"];
$dados["acolhido_cpf"] = $_POST["txtCpf"];
$dados["acolhido_nis"] = $nis;
$dados["acolhido_rg"] = $_POST["txtRg"];

$dados["acolhido_primeiro_acolhimento"] = $_POST["radAcolhimento"];

if($_POST["radAcolhimento"]=="NAO"){
    $dados["acolhido_reincidencia"] = $_POST["txtReincidencia"];
}
else{
    $dados["acolhido_reincidencia"] = 0;
}

$dados["acolhido_telefone_pessoal"] = $_POST["txtTelefonePessoal"];
$dados["acolhido_telefone_residencial"] = $_POST["txtTelefoneResidencial"];
$dados["acolhido_endereco_fixo"] = $_POST["radEndereco"];

if($_POST["radEndereco"]=="SIM"){
    $dados["acolhido_endereco"] = $_POST["txtEndereco"];
    $dados["acolhido_numero"] = $_POST["txtNumero"];
    $dados["acolhido_complemento"] = $_POST["txtComplemento"];
    $dados["acolhido_bairro"] = $_POST["txtBairro"];
    $dados["cidade_id"] = $_POST["slcMunicipios"];
    $dados["acolhido_cep"] = $_POST["txtCep"];
}
else{
    $dados["acolhido_tempo_situacao_rua"] = $_POST["slcTempoSituacaoRua"];
}

foreach($_POST["chkComorbidade"] as $comorbidade)
{
    $comorbidades .= $comorbidade . ", ";
}

$dados["acolhido_deficiencia"] = $_POST["radDeficiencia"];
if($_POST["radDeficiencia"]=="SIM"){
    
    foreach($_POST["chkDeficiencia"] as $deficiencia)
    {
        $deficiencias .= $deficiencia . ", ";
    }

}

foreach($_POST["chkCuidadosTerceiros"] as $cuidado)
{
    $cuidados .= $cuidado . ", ";
}

foreach($_POST["chkSubstanciaPreferencia"] as $preferencia)
{
    $susbtanciaPreferencia .= $preferencia . ", ";
}

$dados["acolhido_comorbidade"] = $comorbidades;
if (isset($_POST["chkComorbidade"]) && in_array("Outra", $_POST["chkComorbidade"])) {
    $dados["acolhido_outra_comorbidade"] = $_POST["txtOutraComorbidade"];
} else {
    $dados["acolhido_outra_comorbidade"] = "";
}
$dados["acolhido_deficiencia_fisica"] = $deficiencias . ' ';
$dados["acolhido_deficiencia_cuidados"] = $cuidados . ' ';
$dados["acolhido_substancia_preferencia"] = $susbtanciaPreferencia;
if (isset($_POST["chkSubstanciaPreferencia"]) && in_array("Outra", $_POST["chkSubstanciaPreferencia"])) {
    $dados["acolhido_outra_substancia_preferencia"] = $_POST["txtOutraSubstanciaPreferencia"];
} else {
    $dados["acolhido_outra_substancia_preferencia"] = "";
}
$dados["acolhido_tempo_utiliza_substancias"] = $_POST['slcTempoUtilizaSubstancia'];
$dados["acolhido_historico"] = $_POST["txtHistorico"];

$dados["acolhido_unidade_hospitalar"] = $_POST["radUnidadeHospitalar"];
if($_POST["radUnidadeHospitalar"]=="SIM"){
    
    $dados["acolhido_qual_unidade_hospitalar"] = $_POST["slcUnidadeHospitalar"];

    if($_POST["slcUnidadeHospitalar"]=='Outra'){
        $dados["acolhido_outra_unidade_hospitalar"] = $_POST["txtOutraUnidadeHospitalar"];
    }
    else{
        $dados["acolhido_outra_unidade_hospitalar"] = "";
    }

}
else{
    $dados["acolhido_qual_unidade_hospitalar"] = "";
    $dados["acolhido_outra_unidade_hospitalar"] = "";
}

$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["porta_entrada_id"] = intval($_SESSION["pfv"]);
$dados["data_cadastro"] = date("Y-m-d h:i:s");

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->insert('rec_acolhidos',$dados);

$dadosUpdate['acolhido_id'] = $_SESSION["sessionForIdInserted"];
if ($contatoReferenciaTempId !== "") {
    $sistema->update("rec_acolhidos_referencias",$dadosUpdate,"acolhido_id = '" . $contatoReferenciaTempId . "'");
}


echo base64_encode($_SESSION["sessionForIdInserted"]);
