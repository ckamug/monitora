<?php
include "../../../../classes/sistema.php";
session_start();

$id = $_POST["id"];
$tipo = $_POST["tipo"];

switch($tipo){
    case 'Et':
        $campos = 'a.* , b.* , a.data_cadastro as data_acao , e.tipo_registro_descricao';
        $tabela = "rec_prontuario_equipe_tecnica";
        $order = "prontuario_equipe_tecnica_id";
    break;
    case 'Psi':
        $campos = 'a.* , b.* , a.data_cadastro as data_acao , c.* , d.* , e.tipo_registro_descricao';
        $tabela = "rec_prontuario_psicologia";
        $order = "prontuario_psicologia_id";
    break;
    case 'Ss':
        $campos = 'a.* , b.* , a.data_cadastro as data_acao , c.* , d.* , e.tipo_registro_descricao';
        $tabela = "rec_prontuario_servico_social";
        $order = "prontuario_servico_social_id";
    break;
}

$sistema = new Sistema();
//$sistema->debug=true;
$from = $tabela . ' a';
$innerJoin[] = 'left join rec_usuarios b on a.usuario_id = b.usuario_id';

if($tipo!="Et"){
    $innerJoin[] = 'left join rec_tipos_atendimentos c on a.tipo_atendimento_id = c.tipo_atendimento_id';
    $innerJoin[] = 'left join rec_subtipos_atendimentos d on a.subtipo_atendimento_id = d.subtipo_atendimento_id';
}
$innerJoin[] = 'left join rec_tipos_registro e on b.tipo_registro_id = e.tipo_registro_id';
$where = "a.prontuario_entrada_id = " . $id;

$sistema->innerJoin($campos,$from,$innerJoin,$where,'','a.'.$order.' DESC');
$result = $sistema->getResult();

$types = array( 'pdf','doc','docx','xls','xlsx','png','jpg','jpeg','gif','bmp');

for($i=0;$i<count($result);$i++){

    if($tipo!="Et"){
        $tipoAtendimento = ' - ' . $result[$i]["tipo_atendimento_descricao"];
        $subTipoAtendimento = ($result[$i]["subtipo_atendimento_id"]>0) ? $result[$i]["subtipo_atendimento_id"] : utf8_encode($result[$i]["descricao_outro_tipo_atendimento"]);
    }

    $arquivo = '';

    $path = 'anexos/'.$result[$i]["prontuario_entrada_id"];
    $dir = new DirectoryIterator($path);

    foreach ($dir as $fileInfo) {

        $ext = strtolower( $fileInfo->getExtension() );

        if( in_array( $ext, $types ) ){

            if(str_replace("." . $fileInfo->getExtension(),"",$fileInfo->getFilename())==strtolower($tipo).".".$result[$i][$order].".".md5($result[$i]["data_acao"])){

                switch($fileInfo->getExtension()){
                    case 'pdf':
                        $img = "pdf.gif";
                    break;
                    case 'doc':
                    case 'docx':
                        $img = "doc.png";
                    break;
                    case 'xls':
                    case 'xlsx':
                        $img = "xls.png";
                    break;
                    case 'png':
                    case 'jpg':
                    case 'jpeg':
                    case 'gif':
                    case 'bmp':
                        $img = "img.png";
                    break;
                    default:
                        $img = "file.png";
                    break;
                }

                $arquivo = '<div class="col-12"><a href="../public/componentes/prontuario_acolhido/model/'.$path.'/'.$fileInfo->getFilename().'" target="_blank"><img src="../images/'.$img.'" border="0"></a></div>';

            }

        }
    }

    if($result[$i]["sigiloso"]==1){

        if($result[$i]["usuario_id"] == base64_decode($_SESSION["usr"])){
            echo '<div class="alert bg-warning bg-opacity-10 col-11 mt-3 border ms-5" role="alert">';
            echo '    <h5 class="alert-heading">'.$sistema->convertData($result[$i]["data_acao"]).' às '.substr($result[$i]["data_acao"],11,5).'hs'.$tipoAtendimento.' - ' . $subTipoAtendimento . '</h5>';
            echo '    <p>'.nl2br(utf8_encode($result[$i]["descricao_acao"])).'</p>';
            echo $arquivo;
            echo '    <hr>';
            echo '    <div class="row">';
            echo '        <div class="col-3">'.utf8_encode($result[$i]["usuario_nome"]).'</div>';
            echo '        <div class="col-3">'.$result[$i]["tipo_registro_descricao"].' '.$result[$i]["numero_registro"].'</div>';
            echo '    </div>';
            echo '</div>';
        }

    }
    else{

        echo '<div class="alert bg-warning bg-opacity-10 col-11 mt-3 border ms-5" role="alert">';
            echo '    <h5 class="alert-heading">'.$sistema->convertData($result[$i]["data_acao"]).' às '.substr($result[$i]["data_acao"],11,5).'hs'.$tipoAtendimento.' - ' . $subTipoAtendimento . '</h5>';
            echo '    <p>'.nl2br(utf8_encode($result[$i]["descricao_acao"])).'</p>';
            echo $arquivo;
            echo '    <hr>';
            echo '    <div class="row">';
            echo '        <div class="col-3">'.utf8_encode($result[$i]["usuario_nome"]).'</div>';
            echo '        <div class="col-3">'.$result[$i]["tipo_registro_descricao"].' '.$result[$i]["numero_registro"].'</div>';
            echo '    </div>';
            echo '</div>';

    }

}