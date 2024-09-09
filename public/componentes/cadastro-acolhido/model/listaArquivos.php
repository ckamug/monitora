<?php

$path = "anexos/acolhidos/" . base64_decode($_POST["id"]);
$diretorio = dir($path);
$total = 0;

while($arquivo = $diretorio -> read()){
    
    if($arquivo!="." and $arquivo!=".."){    
        
        $ext = substr($arquivo,-4);

        if(strtolower($ext)==".pdf"){
            $icone = "pdf.gif";
        }
        else{
            $icone = "img.png";
        }

        $total++;

        $quadro = explode("_" , $arquivo);
        
        switch($quadro[0]){
            case 'documentos':
                $documentos .= "<div class='col-12 p-2 text-start' style='font-size:11px;'><a href='../public/componentes/cadastro-acolhido/model/anexos/acolhidos/".base64_decode($_POST["id"])."/".$arquivo."' style='color:#000' target='_blank'><img src='../images/" . $icone . "'> ".$arquivo."</a></div>";
            break;
            case 'avaliacoes':
                $avaliacoes .= "<div class='col-12 p-2 text-start' style='font-size:11px;'><a href='../public/componentes/cadastro-acolhido/model/anexos/acolhidos/".base64_decode($_POST["id"])."/".$arquivo."' style='color:#000' target='_blank'><img src='../images/" . $icone . "'> ".$arquivo."</a></div>";
            break;
            case 'exames':
                $exames .= "<div class='col-12 p-2 text-start' style='font-size:11px;'><a href='../public/componentes/cadastro-acolhido/model/anexos/acolhidos/".base64_decode($_POST["id"])."/".$arquivo."' style='color:#000' target='_blank'><img src='../images/" . $icone . "'> ".$arquivo."</a></div>";
            break;
        }
        
    }

}

$dados->{'documentos'} = $documentos;
$dados->{'avaliacoes'} = $avaliacoes;
$dados->{'exames'} = $exames;
$result = json_encode($dados);

echo $result;

$diretorio -> close();