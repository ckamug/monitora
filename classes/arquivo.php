<?php

require_once('../../../../../classes/sistema.php');

class arquivo extends sistema
{
	private $dimensaoMaxima=150; 
	private $tamanhoMaximo=3072000; 
	private $arquivo; 
	private $caminho;
	
	public function upload($arquivo,$caminho,$dimensaoMaxima,$tamanhoMaximo){
	
		if(is_uploaded_file($arquivo['tmp_name'])){
			
			$mime = $arquivo['type'];
			
			if(($mime == "image/jpeg")||($mime == "image/pjpeg")){
				
				if($arquivo['size'] < $tamanhoMaximo){
					
					list($larg_orig, $alt_orig) = getimagesize($arquivo['tmp_name']);
					$razao_orig = $larg_orig/$alt_orig;
					if($razao_orig < 1){
						
						$larg = $dimensaoMaxima*$razao_orig;
						$alt = $dimensaoMaxima;
					
					}
					else{
						
						$alt = $dimensaoMaxima/$razao_orig;
						$larg = $dimensaoMaxima;
					
					}
					$imagem_nova = imagecreatetruecolor($larg, $alt);
					$imagem = imagecreatefromjpeg($arquivo['tmp_name']);
					imagecopyresampled($imagem_nova, $imagem, 0, 0, 0, 0, $larg, $alt, $larg_orig, $alt_orig);
					$nomeAleatorio = md5(uniqid(time()).$arquivo['name']);
					imagejpeg($imagem_nova,$caminho.$nomeAleatorio.".jpg");
					return $nomeAleatorio.".jpg";
				}
				return 4; //se o tamanho do arquivo é maior que o tamanho permitido
			}
			return 3; //se o arquivo nao for do tipo JPEG
		}
		return 2; //se o arquivo não foi recebido
	}

}