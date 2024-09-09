<?php
include "../../../../classes/sistema.php";
session_start();

if(base64_decode($_SESSION["usr"])==1 or base64_decode($_SESSION["usr"])==12 or base64_decode($_SESSION["usr"])==168 or base64_decode($_SESSION["usr"])==148 or base64_decode($_SESSION["usr"])==169){

$sistema = new Sistema();
$campos = 'a.* , b.cidade_descricao , c.status_vaga_id , d.status_vaga_descricao';
$from = 'rec_acolhidos a';
$innerJoin[] = 'left join tbl_cidades b on a.cidade_id = b.cidade_id';
$innerJoin[] = 'left join rec_solicitacoes_vagas c on a.acolhido_id = c.acolhido_id';
$innerJoin[] = 'left join rec_status_vaga d on c.status_vaga_id = d.status_vaga_id';

$where = '(c.status_registro = 1 or c.status_registro is null)';

if($_SESSION["pf"]==3){
    $where .= " and porta_entrada_id = " . $_SESSION["pfv"];
}
else if($_SESSION["pf"]==4){
    $where .= " and status_registro = 1 and executora_id = " . $_SESSION["pfv"];
}
else{

}
//$sistema->debug=true;
$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.acolhido_nome_completo');
$result = $sistema->getResult();

echo "<table id='tblAcolhidos' class='table datatable table-hover table-striped'>";
echo "    <thead>";
echo "        <tr>";
echo "        <th scope='col'>Nome Completo</th>";
echo "        <th scope='col'>CPF</th>";
echo "        <th scope='col'>Município</th>";
echo "        <th scope='col'>Data de Cadastro</th>";
echo "        <th scope='col'>Status</th>";
echo "        <th scope='col'></th>";
echo "        </tr>";
echo "    </thead>";

for($i=0;$i<count($result);$i++){

    $icoProntuario = "";

    switch($result[$i]["status_vaga_id"]){
        case '1':
            $avisoVaga = "<span class='badge bg-warning'>".utf8_encode($result[$i]["status_vaga_descricao"])."</span>";
        break;
        case '2':
            $avisoVaga = "<span class='badge bg-primary'>".utf8_encode($result[$i]["status_vaga_descricao"])."</span>";
        break;
        case '3':
            $avisoVaga = "<span class='badge bg-success'>".utf8_encode($result[$i]["status_vaga_descricao"])."</span>";
            
            if($_SESSION["pf"]!=3){
                $icoProntuario = "<button type='button' class='btn btn-success ms-1' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='top' title='Prontuário' onclick=prontuario('".base64_encode($result[$i]["acolhido_id"])."')><i class='bi bi-journal-medical'></i></button>";
            }
            
        break;
        case '4':
            $avisoVaga = "<span class='badge bg-danger'>".utf8_encode($result[$i]["status_vaga_descricao"])."</span>";
        break;
        default:
            $avisoVaga = "";
        break;
    }

    echo "<tr>";
    echo "    <td>".utf8_encode($result[$i]["acolhido_nome_completo"])."</td>";
    echo "    <td>".$result[$i]["acolhido_cpf"]."</td>";
    echo "    <td>".utf8_encode($result[$i]["cidade_descricao"])."</td>";
    echo "    <td>".$sistema->convertData(substr($result[$i]["data_cadastro"],0,10))."</td>";
    echo "    <td>".$avisoVaga."</td>";
    echo "    <td class='text-end'><button type='button' class='btn btn-primary' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='top' title='Cadastro' onclick=acolhido('".base64_encode($result[$i]["acolhido_id"])."')><i class='bi bi-person-badge'></i></button> ".$icoProntuario."</td>";
    echo "</tr>";

}

echo "</table>";

}