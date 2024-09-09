<?php
include "../../../../classes/sistema.php";
include "../../../../classes/controleshtml/select.php";

$sistema = new Sistema();
//$sistema->debug=true;
$sistema->select('rec_executoras_casas','*','executora_id = ' . $_POST['id']);
$result = $sistema->getResult();

if(count($result)>0){

	for($i=0;$i<count($result);$i++){

		echo "<div class='form-check form-check-inline mt-4 ms-2'>";
		echo "	<input class='form-check-input' type='checkbox' id='chkCasas".$i."' name='chkCasas[]' value='".$result[$i]["executora_casa_descricao"]."'>";
		echo "	<label class='form-check-label' for='chkCasas".$i."'>".$result[$i]["executora_casa_descricao"]."</label>";
		echo "</div>";

	}

}
else{
	echo 0;
}