<?php
include "../../../../classes/sistema.php";
session_start();

//Quando queremos testar 
//if(base64_decode($_SESSION["usr"])==1 or base64_decode($_SESSION["usr"])==12 or base64_decode($_SESSION["usr"])==168 or base64_decode($_SESSION["usr"])==148 or base64_decode($_SESSION["usr"])==169 or base64_decode($_SESSION["usr"])==204 or base64_decode($_SESSION["usr"])==236){

$sistema = new Sistema();

//$campos = 'a.* , b.cidade_descricao , c.status_vaga_id , d.status_vaga_descricao , e.status as status_acolhimento , f.acolhido_desligamento_id';
$campos = 'a.*,
           b.cidade_descricao,
           c.status_vaga_id,
           c.data_cadastro as data_status_atual,
           d.status_vaga_descricao,
           if(f.data_desligamento is not null and (c.data_cadastro is null or f.data_desligamento >= c.data_cadastro), 1, 0) as ultimo_status_desligamento,
           f.tipo_desligamento_descricao';

$from = 'rec_acolhidos a';
$innerJoin[] = 'left join tbl_cidades b on a.cidade_id = b.cidade_id';
//$innerJoin[] = 'left join rec_solicitacoes_vagas c on a.acolhido_id = c.acolhido_id';
$innerJoin[] = 'left join rec_solicitacoes_vagas c on c.solicitacao_vaga_id = (
    select c2.solicitacao_vaga_id
    from rec_solicitacoes_vagas c2
    where c2.acolhido_id = a.acolhido_id
      and c2.status_registro = 1
    order by c2.data_cadastro desc, c2.solicitacao_vaga_id desc
    limit 1
)';

$innerJoin[] = 'left join rec_status_vaga d on c.status_vaga_id = d.status_vaga_id';
$innerJoin[] = 'left join (
    select
        x.acolhido_id,
        y.data_desligamento,
        t.tipo_desligamento_descricao
    from rec_acolhidos_entradas x
    inner join rec_acolhidos_desligamentos y on y.acolhido_entrada_id = x.acolhido_entrada_id
    left join rec_tipos_desligamentos t on t.tipo_desligamento_id = y.tipo_desligamento_id
    where y.acolhido_desligamento_id = (
        select y2.acolhido_desligamento_id
        from rec_acolhidos_entradas x2
        inner join rec_acolhidos_desligamentos y2 on y2.acolhido_entrada_id = x2.acolhido_entrada_id
        where x2.acolhido_id = x.acolhido_id
        order by y2.data_desligamento desc, y2.acolhido_desligamento_id desc
        limit 1
    )
) f on f.acolhido_id = a.acolhido_id';

$where = '(c.status_registro = 1 or c.status_registro is null)';

if($_SESSION["pf"]==3){
    $where .= " and a.porta_entrada_id = " . $_SESSION["pfv"];
}
else if($_SESSION["pf"]==4){
    $where .= " and c.status_registro = 1 and c.executora_id = " . $_SESSION["pfv"];
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
echo "        <th scope='col'>NIS</th>";
echo "        <th scope='col'>CPF</th>";
echo "        <th scope='col'>Município</th>";
echo "        <th scope='col'>Data de Cadastro</th>";
echo "        <th scope='col'>Status</th>";
echo "        <th scope='col'></th>";
echo "        </tr>";
echo "    </thead>";

for($i=0;$i<count($result);$i++){

    $icoProntuario = "";

    //if adicionado para nao deixar mostrar o status da vaga quando o acolhido ja estiver desligado
    if ((int)$result[$i]["ultimo_status_desligamento"] === 1) {
        $motivoDesligamento = trim($result[$i]["tipo_desligamento_descricao"]) == "" ? "Motivo nao informado" : utf8_encode($result[$i]["tipo_desligamento_descricao"]);
        $avisoVaga = "<span class='badge bg-secondary'>".$motivoDesligamento."</span>";
    } else {

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
        case '5':
            $avisoVaga = "<span class='badge bg-info'>".utf8_encode($result[$i]["status_vaga_descricao"])."</span>";
        break;
        default:
            $avisoVaga = "";
        break;
    }
    }

    $nis = trim($result[$i]["acolhido_nis"]) == "" ? "-" : $result[$i]["acolhido_nis"];
    echo "<tr>";
    echo "    <td>".utf8_encode($result[$i]["acolhido_nome_completo"])."</td>";
    echo "    <td>".$nis."</td>";
    echo "    <td>".$result[$i]["acolhido_cpf"]."</td>";
    echo "    <td>".utf8_encode($result[$i]["cidade_descricao"])."</td>";
    echo "    <td>".$sistema->convertData(substr($result[$i]["data_cadastro"],0,10))."</td>";
    echo "    <td>".$avisoVaga."</td>";
    echo "    <td class='text-end'><button type='button' class='btn btn-primary' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='top' title='Cadastro' onclick=acolhido('".base64_encode($result[$i]["acolhido_id"])."')><i class='bi bi-person-badge'></i></button> ".$icoProntuario."</td>";
    echo "</tr>";

}

echo "</table>";

//}
