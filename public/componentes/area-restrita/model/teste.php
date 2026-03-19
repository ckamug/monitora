<?php
include "../../../../classes/sistema.php";
// Verifique se os arquivos existem antes de incluir
$smtp_path = "../../../../classes/class.smtp.php";
$phpmailer_path = "../../../../classes/class.phpmailer.php";

if (file_exists($smtp_path) && file_exists($phpmailer_path)) {
    include $smtp_path;
    include $phpmailer_path;
} else {
    die("Arquivos do PHPMailer não encontrados!");
}

  $Html_Page= '
	<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<body>
		<table width="100%" style="font-family:verdana; font-size:14px; color:#666666;">
			<tr>
				<td></td>
			</tr>
		</table>
	</body>
	</html>';

	$PHPMailer = new PHPMailer();
	$PHPMailer->IsSMTP();
	$PHPMailer->Host     = "10.1.5.62";
	//$PHPMailer->SMTPAuth = true;
	$PHPMailer->Port = 25;
	//$PHPMailer->Username = "adm.prosocial@sp.gov.br";
	//$PHPMailer->Password = "";
	$PHPMailer->From     = "adm.prosocial@sp.gov.br";
	$PHPMailer->FromName = "Teste";
	$PHPMailer->AddAddress("ckmugnaini@sp.gov.br","Teste");
	$PHPMailer->AddBCC("ckamug@gmail.com","Caio");
	$PHPMailer->WordWrap = 50;
	$PHPMailer->IsHTML(true);
	$PHPMailer->Subject = "Teste";
	$PHPMailer->Body = $Html_Page;
	$PHPMailer->AltBody = "";
	$PHPMailer->SMTPDebug = true;
	$PHPMailer->Send();
  
  echo "OK";

  ?>