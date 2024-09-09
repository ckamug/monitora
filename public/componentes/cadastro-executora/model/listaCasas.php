<?php
include "../../../../classes/sistema.php";
session_start();

$id = $_POST["id"];

$sistema = new Sistema();
$campos = '*';
$from = 'rec_executoras_casas a';
$innerJoin[] = 'left join rec_executoras b on a.executora_id = b.executora_id';

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.executora_casa_id');
$result = $sistema->getResult();



for($i=0;$i<count($result);$i++){

    echo '<div class="col-md-12 p-2 border-bottom" style="position:relative; float:left;">';
    echo '<div class="col-md-2 pt-3" style="position:relative; float:left;">';
    echo '<h3><i class="bi bi-house-gear"></i> CASA '.($i+1).'</h3>';
    echo '</div>';
    echo '<div class="col-md-3 me-2" style="position:relative; float:left;">';
    echo '<div class="form-floating">';
    echo '<input type="text" class="form-control" id="txtRh' . $i . '" name="txtRh' . $i . '" placeholder="Recursos Humanos">';
    echo '<label for="txtResponsavel">Recursos Humanos</label>';
    echo '</div>';
    echo '</div>';
    echo '<div class="col-md-3 me-2" style="position:relative; float:left;">';
    echo '<div class="form-floating">';
    echo '<input type="text" class="form-control" id="txtCusteio' . $i . '" name="txtCusteio' . $i . '" placeholder="Custeio">';
    echo '<label for="txtCpf">Custeio</label>';
    echo '</div>';
    echo '</div>';
    echo '<div class="col-md-3" style="position:relative; float:left;">';
    echo '<div class="form-floating">';
    echo '<input type="text" class="form-control" id="txtServicosTerceiros' . $i . '" name="txtServicosTerceiros' . $i . '" placeholder="Serviços Terceiros">';
    echo '<label for="txtCpf">Serviços Terceiros</label>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<script>';
    echo '$("#txtRh'.$i.' , #txtCusteio'.$i.' , #txtServicosTerceiros'.$i.'").maskMoney({';
    echo 'prefix: "R$ ",';
    echo 'decimal: ",",';
    echo 'thousands: "."';
    echo '});';
    echo '</script>';

}