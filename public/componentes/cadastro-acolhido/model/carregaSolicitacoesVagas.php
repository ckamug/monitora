<?php
include "../../../../classes/sistema.php";
session_start();

if (is_numeric($_POST["id"])) {
    $id = $_POST["id"];
} else {
    $id = base64_decode($_POST["id"]);
}

if ($id != "") {

    $sistema = new Sistema();
    $eventos = array();

    $campos = 'a.* , a.acolhido_id as acolhido , b.* , b.data_cadastro as data_cadastro_solicitacao , c.* , d.executora_razao_social , e.* , f.usuario_nome , g.servico_descricao';
    $from = 'rec_acolhidos a';
    $innerJoin = array();
    $innerJoin[] = 'left join rec_solicitacoes_vagas b on a.acolhido_id = b.acolhido_id';
    $innerJoin[] = 'left join rec_status_vaga c on b.status_vaga_id = c.status_vaga_id';
    $innerJoin[] = 'left join rec_executoras d on b.executora_id = d.executora_id';
    $innerJoin[] = 'left join rec_solicitacoes_vagas_justificativas e on b.solicitacao_vaga_id = e.solicitacao_vaga_id';
    $innerJoin[] = 'left join rec_usuarios f on b.usuario_id = f.usuario_id';
    $innerJoin[] = 'left join rec_servicos g on b.servico_id = g.servico_id';
    $where = 'a.acolhido_id = ' . $id;

    $sistema->innerJoin($campos, $from, $innerJoin, $where, '', 'b.data_cadastro DESC');
    $result = $sistema->getResult();

    if (!empty($result) && is_array($result)) {
        for ($i = 0; $i < count($result); $i++) {
            $statusVaga = intval($result[$i]["status_vaga_id"]);

            if ($statusVaga == 1) {
                $eventos[] = array(
                    "ordem_data" => $result[$i]["data_cadastro_solicitacao"],
                    "classe_alerta" => "alert-warning",
                    "titulo" => utf8_encode($result[$i]["executora_razao_social"]),
                    "status" => utf8_encode($result[$i]["status_vaga_descricao"]),
                    "justificativa" => "",
                    "label_data" => "Data da solicitacao:",
                    "data_evento" => $result[$i]["data_cadastro_solicitacao"],
                    "usuario" => utf8_encode($result[$i]["usuario_nome"])
                );
            } else if ($statusVaga == 2) {
                $eventos[] = array(
                    "ordem_data" => $result[$i]["data_cadastro_solicitacao"],
                    "classe_alerta" => "alert-primary",
                    "titulo" => utf8_encode($result[$i]["executora_razao_social"]),
                    "status" => utf8_encode($result[$i]["status_vaga_descricao"]),
                    "justificativa" => "",
                    "label_data" => "Data da reserva:",
                    "data_evento" => $result[$i]["data_cadastro_solicitacao"],
                    "usuario" => utf8_encode($result[$i]["usuario_nome"])
                );
            } else if ($statusVaga == 3) {
                $eventos[] = array(
                    "ordem_data" => $result[$i]["data_cadastro_solicitacao"],
                    "classe_alerta" => "alert-success",
                    "titulo" => utf8_encode($result[$i]["executora_razao_social"]),
                    "status" => utf8_encode($result[$i]["status_vaga_descricao"]),
                    "justificativa" => "",
                    "label_data" => "Data da aceitacao:",
                    "data_evento" => $result[$i]["data_cadastro_solicitacao"],
                    "usuario" => utf8_encode($result[$i]["usuario_nome"])
                );
            } else if ($statusVaga == 4) {
                $eventos[] = array(
                    "ordem_data" => $result[$i]["data_cadastro_solicitacao"],
                    "classe_alerta" => "alert-danger",
                    "titulo" => utf8_encode($result[$i]["executora_razao_social"]),
                    "status" => utf8_encode($result[$i]["status_vaga_descricao"]),
                    "justificativa" => utf8_encode($result[$i]["solicitacao_vaga_justificativa_descricao"]),
                    "label_data" => "Data da negativa:",
                    "data_evento" => $result[$i]["data_cadastro_solicitacao"],
                    "usuario" => utf8_encode($result[$i]["usuario_nome"])
                );
            } else if ($statusVaga == 5) {
                $tituloEncaminhamento = trim($result[$i]["servico_descricao"]) != "" ? utf8_encode($result[$i]["servico_descricao"]) : "Aguardando encaminhamento";
                $eventos[] = array(
                    "ordem_data" => $result[$i]["data_cadastro_solicitacao"],
                    "classe_alerta" => "alert-info",
                    "titulo" => $tituloEncaminhamento,
                    "status" => utf8_encode($result[$i]["status_vaga_descricao"]),
                    "justificativa" => "",
                    "label_data" => "Data da solicitacao:",
                    "data_evento" => $result[$i]["data_cadastro_solicitacao"],
                    "usuario" => utf8_encode($result[$i]["usuario_nome"])
                );
            }
        }
    }

    $camposDesligamento = 'a.acolhido_entrada_id , a.data_entrada , b.data_desligamento , b.tipo_desligamento_id , c.executora_razao_social , d.usuario_nome , e.tipo_desligamento_descricao';
    $fromDesligamento = 'rec_acolhidos_entradas a';
    $innerJoinDesligamento = array();
    $innerJoinDesligamento[] = 'inner join rec_acolhidos_desligamentos b on a.acolhido_entrada_id = b.acolhido_entrada_id';
    $innerJoinDesligamento[] = 'left join rec_executoras c on a.executora_id = c.executora_id';
    $innerJoinDesligamento[] = 'left join rec_usuarios d on b.usuario_id = d.usuario_id';
    $innerJoinDesligamento[] = 'left join rec_tipos_desligamentos e on b.tipo_desligamento_id = e.tipo_desligamento_id';
    $whereDesligamento = 'a.acolhido_id = ' . $id . ' and a.status = 2';

    $sistema->innerJoin($camposDesligamento, $fromDesligamento, $innerJoinDesligamento, $whereDesligamento, '', 'b.data_desligamento DESC');
    $resDesligamentos = $sistema->getResult();

    if (!empty($resDesligamentos) && is_array($resDesligamentos)) {
        for ($i = 0; $i < count($resDesligamentos); $i++) {
            $usuarioDesligamento = utf8_encode($resDesligamentos[$i]["usuario_nome"]);
            if (trim($usuarioDesligamento) == "") {
                $usuarioDesligamento = "Sistema";
            }

            $motivoDesligamento = trim($resDesligamentos[$i]["tipo_desligamento_descricao"]) == "" ? "Motivo nao informado" : utf8_encode($resDesligamentos[$i]["tipo_desligamento_descricao"]);
            $eventos[] = array(
                "ordem_data" => $resDesligamentos[$i]["data_desligamento"],
                "classe_alerta" => "alert-secondary",
                "titulo" => utf8_encode($resDesligamentos[$i]["executora_razao_social"]),
                "status" => $motivoDesligamento,
                "justificativa" => "",
                "label_data" => "Data do desligamento:",
                "data_evento" => $resDesligamentos[$i]["data_desligamento"],
                "usuario" => $usuarioDesligamento
            );
        }
    }

    usort($eventos, function ($a, $b) {
        return strcmp($b["ordem_data"], $a["ordem_data"]);
    });

    for ($i = 0; $i < count($eventos); $i++) {
        echo '<div class="alert ' . $eventos[$i]["classe_alerta"] . ' alert-dismissible fade show" role="alert">';
        echo '<h4 class="alert-heading">' . $eventos[$i]["titulo"] . '</h4>';
        echo '<p><b>Status:</b> ' . $eventos[$i]["status"] . '</p>';
        if ($eventos[$i]["justificativa"] != "") {
            echo '<p><b>Justificativa:</b> ' . $eventos[$i]["justificativa"] . '</p>';
        }
        echo '<hr>';
        echo '<p class="mb-0">' . $eventos[$i]["label_data"] . ' ' . $sistema->convertData($eventos[$i]["data_evento"]) . ' as ' . substr($eventos[$i]["data_evento"], 11, 5) . ' por ' . $eventos[$i]["usuario"] . '</p>';
        echo '</div>';
    }
}
