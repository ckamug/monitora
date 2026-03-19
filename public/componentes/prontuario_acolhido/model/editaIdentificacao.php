<?php
include "../../../../classes/sistema.php";
session_start();

function postValue($key)
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}

function resolveAcolhidoEntradaId($idRecebido)
{
    $idRecebido = intval($idRecebido);
    if ($idRecebido <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_acolhidos_entradas",
        "acolhido_entrada_id",
        "acolhido_entrada_id = " . $idRecebido,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["acolhido_entrada_id"])) {
        return intval($result[0]["acolhido_entrada_id"]);
    }

    return 0;
}

function normalizaDataBanco($valor)
{
    if ($valor == "") {
        return "";
    }

    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $valor)) {
        $partes = explode("/", $valor);
        return $partes[2] . "-" . $partes[1] . "-" . $partes[0];
    }

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
        return $valor;
    }

    return "";
}

function normalizaIdReferencia($valor, $tabela, $campoId, $campoDescricao)
{
    $valor = trim($valor);

    if ($valor == "" || $valor == "0") {
        return "";
    }

    if (ctype_digit($valor)) {
        return intval($valor) > 0 ? intval($valor) : "";
    }

    $sistema = new Sistema();
    $where = $campoDescricao . ' = "' . addslashes($valor) . '"';
    $sistema->select($tabela, $campoId, $where, "", "");
    $result = $sistema->getResult();

    if ($result && isset($result[0][$campoId])) {
        return intval($result[0][$campoId]);
    }

    return "";
}

function montaDadosIdentificacao()
{
    $dados = array();

    $dados["nacionalidade"] = postValue("txtNacionalidade");
    $dados["naturalidade"] = postValue("txtNaturalidade");

    $etniaId = normalizaIdReferencia(postValue("slcEtnia"), "rec_etnias", "etnia_id", "etnia_descricao");
    if ($etniaId !== "") {
        $dados["etnia_id"] = $etniaId;
    }

    $registroCartorioId = normalizaIdReferencia(
        postValue("radRegistroCartorio"),
        "rec_opcoes_registro_cartorio",
        "registro_cartorio_opcao_id",
        "registro_cartorio_opcao_descricao"
    );
    if ($registroCartorioId !== "") {
        $dados["registro_cartorio_opcao_id"] = $registroCartorioId;
    }

    $tipoCertidaoId = normalizaIdReferencia(
        postValue("slcTipoCertidao"),
        "rec_tipos_certidao",
        "tipo_certidao_id",
        "tipo_certidao_descricao"
    );
    if ($tipoCertidaoId !== "") {
        $dados["tipo_certidao_id"] = $tipoCertidaoId;
    }

    $dados["nome_cartorio"] = postValue("txtNomeCartorio");

    $dataRegistro = normalizaDataBanco(postValue("txtDataRegistro"));
    if ($dataRegistro != "") {
        $dados["data_registro"] = $dataRegistro;
    }

    $dados["numero_livro"] = postValue("txtNLivro");
    $dados["numero_folha"] = postValue("txtNFolha");
    $dados["numero_termo_rani"] = postValue("txtNTermo");
    $dados["matricula"] = postValue("txtMatricula");

    $estadoId = normalizaIdReferencia(postValue("slcUfRegistro"), "tbl_estados", "estado_id", "estado_descricao");
    if ($estadoId !== "") {
        $dados["estado_registro_id"] = $estadoId;
    }

    $cidadeId = normalizaIdReferencia(postValue("slcMunicipioCertidao"), "tbl_cidades", "cidade_id", "cidade_descricao");
    if ($cidadeId !== "") {
        $dados["cidade_registro_id"] = $cidadeId;
    }

    return $dados;
}

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$entradaId = isset($_POST["entrada_id"]) ? intval($_POST["entrada_id"]) : 0;
$entradaId = resolveAcolhidoEntradaId($entradaId);

$sistema = new Sistema();

if ($id <= 0 && $entradaId > 0) {
    $sistema->select(
        "rec_prontuario_identificacao",
        "prontuario_identificacao_id",
        "prontuario_entrada_id = " . $entradaId,
        "",
        ""
    );
    $res = $sistema->getResult();
    if ($res && isset($res[0]["prontuario_identificacao_id"])) {
        $id = intval($res[0]["prontuario_identificacao_id"]);
    }
}

if ($id <= 0) {
    echo "Registro de identificação não localizado para o acolhimento informado";
    exit;
}

$dados = montaDadosIdentificacao();
$dados["usuario_id"] = base64_decode($_SESSION["usr"]);
$dados["data_atualizacao"] = date("Y-m-d H:i:s");

$sistema->update(
    "rec_prontuario_identificacao",
    $dados,
    "prontuario_identificacao_id = " . $id
);

echo $id;
