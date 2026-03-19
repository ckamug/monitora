<?php
include "../../../../classes/sistema.php";
include_once __DIR__ . "/contatoReferencia.php";
session_start();

$id = resolverIdAcolhidoOuTemporario(isset($_POST['id']) ? $_POST['id'] : "");

if ($id === "") {
    exit;
}

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select("rec_acolhidos_referencias","*","acolhido_id = '" . $id . "'");
$result = $sistema->getResult();

if(count($result)>0){

    echo '<table class="table table-striped w-75">';
    echo '<thead>';
    echo '<th scope="col">Nome do Contato</th>';
    echo '<th scope="col">Telefone do Contato</th>';
    echo '<th scope="col">Grau de Parentesco</th>';
    echo '<th scope="col"></th>';

    for($i=0;$i<count($result);$i++){

        echo '  <tr>';
        echo '      <td>'. utf8_encode($result[$i]["referencia_nome"]) .'</td>';
        echo '      <td>'. $result[$i]["referencia_contato"] .'</td>';
        echo '      <td>'. utf8_encode($result[$i]["referencia_parentesco"]) .'</td>';
        echo '      <td><i class="bi bi-trash text-danger" style="cursor:pointer;" onclick="excluirContatoReferencia('.$result[$i]["referencia_id"].')" data-bs-toggle="tooltip" data-bs-placement="right" title="Remover contato de referência"></i></td>';
        echo '  </tr>';

    }

    echo '</thead>';
    echo '</table>';

}
