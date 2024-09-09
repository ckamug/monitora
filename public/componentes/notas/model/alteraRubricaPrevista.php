<?php
include "../../../../classes/sistema.php";
session_start();

$sistema = new Sistema();

$dadosValores["recursos_humanos_previsto"] = str_replace("R$ ","",$_POST["rh"]);
$dadosValores["custeio_previsto"] = str_replace("R$ ","",$_POST["custeio"]);
$dadosValores["servicos_terceiros_previsto"] = str_replace("R$ ","",$_POST["terceiros"]);

//$sistema->debug=true;
$sistema->update('rec_cabecalhos',$dadosValores,'cabecalho_id = ' . $_POST["cabecalho"]);