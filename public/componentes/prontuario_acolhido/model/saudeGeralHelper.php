<?php
include "../../../../classes/sistema.php";

function sgPostValue($key)
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}

function sgResolveAcolhidoEntradaId($idRecebido)
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

function sgBuscaSaudeGeralIdPorEntrada($entradaId)
{
    $entradaId = intval($entradaId);
    if ($entradaId <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_saude_geral",
        "prontuario_saude_geral_id",
        "acolhido_entrada_id = " . $entradaId,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["prontuario_saude_geral_id"])) {
        return intval($result[0]["prontuario_saude_geral_id"]);
    }

    return 0;
}

function sgBuscaEntradaPorSaudeGeralId($saudeGeralId)
{
    $saudeGeralId = intval($saudeGeralId);
    if ($saudeGeralId <= 0) {
        return 0;
    }

    $sistema = new Sistema();
    $sistema->select(
        "rec_prontuario_saude_geral",
        "acolhido_entrada_id",
        "prontuario_saude_geral_id = " . $saudeGeralId,
        "",
        ""
    );
    $result = $sistema->getResult();

    if ($result && isset($result[0]["acolhido_entrada_id"])) {
        return intval($result[0]["acolhido_entrada_id"]);
    }

    return 0;
}

function sgNormalizaSimNao($valor)
{
    $valor = trim($valor);
    if ($valor === "") {
        return "";
    }

    $valorLower = strtolower($valor);
    if ($valorLower === "sim" || $valor === "1") {
        return 1;
    }
    if ($valorLower === "não" || $valorLower === "nao" || $valor === "0") {
        return 0;
    }

    return "";
}

function sgNormalizaIdReferencia($valor, $tabela, $campoId, $campoDescricao)
{
    $valor = trim($valor);

    if ($valor === "" || $valor === "0") {
        return "";
    }

    if (ctype_digit($valor)) {
        $valorInt = intval($valor);
        return $valorInt > 0 ? $valorInt : "";
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

function sgBuscaDoencaIdPorDescricao($descricao)
{
    $descricao = trim($descricao);
    if ($descricao === "") {
        return 0;
    }

    $sistema = new Sistema();
    $where = 'doenca_descricao = "' . addslashes($descricao) . '"';
    $sistema->select("rec_doencas", "doenca_id", $where, "", "");
    $result = $sistema->getResult();

    if ($result && isset($result[0]["doenca_id"])) {
        return intval($result[0]["doenca_id"]);
    }

    return 0;
}

function sgNormalizaListaIdsReferencia($campoPost, $tabela, $campoId, $campoDescricao)
{
    $lista = array();

    if (!isset($_POST[$campoPost]) || !is_array($_POST[$campoPost])) {
        return $lista;
    }

    foreach ($_POST[$campoPost] as $valor) {
        $id = sgNormalizaIdReferencia($valor, $tabela, $campoId, $campoDescricao);
        if ($id !== "") {
            $id = intval($id);
            if (!in_array($id, $lista)) {
                $lista[] = $id;
            }
        }
    }

    return $lista;
}

function sgMontaPayloadSaudeGeral($entradaId)
{
    $payload = array(
        "dados" => array(),
        "doencas" => array()
    );

    $dados = array();
    $dados["acolhido_entrada_id"] = intval($entradaId);

    $tratamento = sgNormalizaSimNao(sgPostValue("radTratamentoMedicoAmbulatorial"));
    if ($tratamento !== "") {
        $dados["realiza_tratamento_medico_ambulatorial"] = $tratamento;
    }

    if ($tratamento === 1) {
        $ondeTratamento = sgPostValue("txtOndeTratamentoMedicoAmbulatorial");
        if ($ondeTratamento !== "") {
            $dados["onde_tratamento_medico_ambulatorial"] = $ondeTratamento;
        }
    }

    $doencas = sgNormalizaListaIdsReferencia(
        "chkPossuiDoenca",
        "rec_doencas",
        "doenca_id",
        "doenca_descricao"
    );

    $idNaoTenho = sgBuscaDoencaIdPorDescricao("Não tenho");
    if ($idNaoTenho > 0 && in_array($idNaoTenho, $doencas)) {
        $doencas = array($idNaoTenho);
    }

    $idOutra = sgBuscaDoencaIdPorDescricao("Outra");
    if ($idOutra > 0 && in_array($idOutra, $doencas)) {
        $outraDoenca = sgPostValue("txtOutraDoencaSaudeGeral");
        if ($outraDoenca !== "") {
            $dados["outra_doenca_descricao"] = $outraDoenca;
        }
    }

    $payload["dados"] = $dados;
    $payload["doencas"] = $doencas;
    return $payload;
}

function sgRemoveSaudeGeralExistente($saudeGeralId)
{
    $saudeGeralId = intval($saudeGeralId);
    if ($saudeGeralId <= 0) {
        return;
    }

    $sistema = new Sistema();
    $sistema->delete("rec_prontuario_sg_doencas", "prontuario_saude_geral_id = " . $saudeGeralId);
    $sistema->delete("rec_prontuario_saude_geral", "prontuario_saude_geral_id = " . $saudeGeralId);
}

function sgSalvaSaudeGeralPorEntrada($entradaId)
{
    $entradaId = intval($entradaId);
    if ($entradaId <= 0) {
        return 0;
    }

    $saudeGeralIdExistente = sgBuscaSaudeGeralIdPorEntrada($entradaId);
    if ($saudeGeralIdExistente > 0) {
        sgRemoveSaudeGeralExistente($saudeGeralIdExistente);
    }

    $payload = sgMontaPayloadSaudeGeral($entradaId);
    $dados = $payload["dados"];

    $dados["usuario_id"] = base64_decode($_SESSION["usr"]);
    $dados["data_cadastro"] = date("Y-m-d H:i:s");

    $sistema = new Sistema();
    $sistema->insert("rec_prontuario_saude_geral", $dados);

    $novoId = isset($_SESSION["sessionForIdInserted"]) ? intval($_SESSION["sessionForIdInserted"]) : 0;
    if ($novoId <= 0) {
        return 0;
    }

    foreach ($payload["doencas"] as $doencaId) {
        $sistema = new Sistema();
        $sistema->insert("rec_prontuario_sg_doencas", array(
            "prontuario_saude_geral_id" => $novoId,
            "doenca_id" => intval($doencaId)
        ));
    }

    return $novoId;
}

