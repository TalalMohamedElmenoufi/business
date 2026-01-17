<?php
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot");

$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_1");

 
function dataBr($d){
	$l = explode(" ",$d);
	$dt = explode("-",$l[0]);
	if($dt[2] and $dt[1] and $dt[0]){
		return $dt[2]."/".$dt[1]."/".$dt[0].(($l[1]) ? " ".$l[1] : false);
	}else{
		return false;
	}
}


$cobrancas = "select a.*, b.nome, b.celular, b.email, b.emails from cobrancas a
			left join clientes b on b.id_asaas=a.customer
where a.id_rg = '591' ";
$Rcobranca = mysqli_query($conexao2,$cobrancas);

$c = mysqli_fetch_object($Rcobranca);


//echo $c->emails."<br>";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


list($logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado  ) = mysqli_fetch_row(mysqli_query($conexao, "select logo, empresa, cpf_cnpj, site, email, contato, endereco, cep, cidade, estado from cobrancas_empresa where id_usuario = '1' "));

		
if($email){
	SendEmail($c->nome, $c->value, $c->description, $c->invoiceUrl, $c->email, $c->emails, $logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado);
}
	

function SendEmail($nome, $value, $description, $invoiceUrl, $emailSend, $emails, $logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado){		

    $infoEmail = "Olá, sua cobrança";
    $Subject = $empresa;

	$html  .= '<img src="'.$logo.'">&nbsp;&nbsp;';

	$html  .= '<span style="font-weight:700;font-size:20pt;">'.$empresa.'</span><br><br>';
	
	$html  .= '<span style="font-weight:700;font-size:16pt;">Ol&aacute;, '.$nome.'</span><br><br>';
    
	$html  .= '<span style="font-weight:100;font-size:14pt;">Lembramos que a sua cobran&ccedil;a gerada por '.$empresa.' no valor de <b>R$ '.number_format($value,2,",",".").'</b> vence em <b>'.dataBr($dueDate).'</b></span>.<br>';
 
	$html  .= 'Descri&ccedil;&atilde;o da cobran&ccedil;a: '.$description.'<br><br>';

	$html  .= '<span style="font-weight:700;font-size:14pt;">Clique no bot&atilde;o abaixo para visualizar a cobran&ccedil;a.</span><br><br>';

	$html  .= ' <span style="color:black"><a href="'.$invoiceUrl.'"><span style="font-size:10.5pt;font-family:"Open Sans",sans-serif;text-decoration:none"><img border=0 width=186 height=36 style="width:1.9375in;height:.375in" src="https://www.grupoelmenoufi.com.br/business/img/visualizar_cobranca.png" alt="Visualizar cobrança"></span></a></span> <br> <br> ';


    $html  .= '<span style="font-weight:50;font-size:12pt;"> Ou acesse '.$invoiceUrl.'</span>';



    $html  .= '<br><br><br>';
    $html  .= 'Atenciosamente,<br><br>';


    $html  .= $empresa.'<br>';
    $html  .= $cpf_cnpj.'<br>';
    $html  .= $site.'<br>';
    $html  .= $email.'<br>';
    $html  .= $contato.'<br>';
    $html  .= $endereco.'<br>';
    $html  .= 'CEP: '.$cep.'<br>';
    $html  .= $cidade.'-'.$estado.'<br>';


	$mail = new PHPMailer(true);

	try {
		//Server settings
		//$mail->SMTPDebug = 1;                              // Enable verbose debug output
		$mail->isSMTP();                                     // Send using SMTP
		$mail->Host       = 'elmenoufi.com.br';              // Set the SMTP server to send through
		$mail->SMTPAuth   = true;                            // Enable SMTP authentication
		$mail->Username   = 'nao-responda@elmenoufi.com.br'; // SMTP username
		$mail->Password   = '3lm3n0uf!2023';                 // SMTP password
		
		$mail->SMTPSecure = 'tls'; 						     //tls or ssl
		$mail->Port       = 587;				             // TCP port to connect to 587  ou 465 
		
		$mail->setFrom('nao-responda@elmenoufi.com.br', ($infoEmail) );
		$mail->addAddress( trim($emailSend) , $nome);
		
		$arrayEmails = explode(",",$emails);
		foreach ($arrayEmails as $emailLoop) {
			$mail->addAddress( trim($emailLoop) , $nome);
		}
		
		
		
		// Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $Subject;
		$mail->Body    = $html;
		$mail->AltBody = $html;

		$mail->send();
		
		echo "Enviado com sucesso!";
           

	} catch (Exception $e) {
		$mail->ErrorInfo;

	}

}
?>
