<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));

  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

function dataBr($d){
	$l = explode(" ",$d);
	$dt = explode("-",$l[0]);
	if($dt[2] and $dt[1] and $dt[0]){
		return $dt[2]."/".$dt[1]."/".$dt[0].(($l[1]) ? " ".$l[1] : false);
	}else{
		return false;
	}
}


$idUsuario = $_POST[idUsuario];
  
$pergunta = "select * from usuarios where id = '".$idUsuario."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);

$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$d->id);


function dataMysql($d){
	$l = explode(" ",$d);
	$dt = explode("/",$l[0]);
	if($dt[2] and $dt[1] and $dt[0]){
		return $dt[2]."-".$dt[1]."-".$dt[0].(($l[1]) ? " ".$l[1] : false);
	}else{
		return false;		
	}
}





$pergunta = "select * from clientes where id='".$_POST[cliente_id]."' ";
$resultado = mysqli_query($conexao2, $pergunta);
$c = mysqli_fetch_object($resultado);	
	
$valorCob = str_replace(',', '.', $_POST[valor_cobranca]);	
$valorDesc = str_replace(',', '.', $_POST[valor_desconto]);	
	
$id_asaas = $c->id_asaas;
$billingType = $c->billingType;
$dueDate = dataMysql($_POST[data_vencimento]); //Data de vencimento da cobrança
$value = $valorCob; //Valor da cobrança
$description = ($_POST[desc_cobranca]); //Descrição da cobrança
$externalReference = "x"; //Campo livre para busca
$discountValue = $valorDesc; //Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
$dueDateLimitDays = 0; //Dias antes do vencimento para aplicar desconto. Ex: 0 = até o vencimento, 1 = até um dia antes, 2 = até dois dias antes, e assim por diante
$fineValue = 1; //Percentual de multa sobre o valor da cobrança para pagamento após o vencimento
$interestValue = 1; //Percentual de juros ao mês sobre o valor da cobrança para pagamento após o vencimento	




list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));	

list($api_token_cliente) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '".$d->id."' and status = 'Liberado' "));	

if($api_token_dono==$api_token_cliente){
	$api_token = $api_token_dono ;
}else{
	$api_token = $api_token_cliente ;	
}



list($tituloWhat,$descricaoWhat,$imgWhat) = mysqli_fetch_row(mysqli_query($conexao2, "select titulo,descricao,img from whats_config "));


$nomeCliente = ($c->nome);

GerarFatura($conexao,$ip,$porta,$d->token,$conexao2,$c->id,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$c->celular,$tituloWhat,$descricaoWhat,$imgWhat,$d->id,$nomeCliente,$c->email,$c->emails,$d->status_whats_desc);


 
function GerarFatura($conexao,$ip,$porta,$token,$conexao2,$id_user,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$celss,$tituloWhat,$descricaoWhat,$imgWhat,$idUser,$nome,$emailDb,$emails,$status_whats_desc){
	
/*	echo 
	$ip."<br>".
	$porta."<br>".
	$token."<br>".
	$id_user."<br>".	
	$api_token."<br>".
	$id_asaas."<br>".
	$billingType."<br>".
	$dueDate."<br>".
	$value."<br>".
	$description."<br>".
	$externalReference."<br>".
	$discountValue."<br>".
	$dueDateLimitDays,
	$fineValue."<br>".
	$interestValue."<br>".
	$tituloWhat."<br>".
	$descricaoWhat."<br>".
	$imgWhat."<br>".	
	"Cell: ".$celss."<br>";*/
	
	
	$fields = array
	(
		'customer' => $id_asaas,
		'billingType' => $billingType,
		'dueDate' => $dueDate,
		'value' => $value,
		'description' => $description,
		'externalReference' => $externalReference,
		'discount' =>
			array
			(
			'value' => $discountValue,
			'dueDateLimitDays' => $dueDateLimitDays,	
			'type' => 'FIXED',
			),
		'fine' =>
			array
			(
			'value' => $fineValue,	
			),
		'interest' =>
			array
			(
			'value' => $interestValue,	
			),
		'postalService' => false,
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);	
	
	
	//var_dump($response); comente
	$obj = json_decode($response);
	
	$insert = " insert into cobrancas set
				object = '".$obj->object."',
				id = '".$obj->id."',
				dateCreated = '".$obj->dateCreated."',
				customer = '".$obj->customer."',
				value = '".$obj->value."',
				netValue = '".$obj->netValue."',
				originalValue = '".$obj->originalValue."',
				interestValue = '".$obj->interestValue."',
				description = '".($obj->description)."',
				billingType = '".$obj->billingType."',
				status = '".$obj->status."',
				dueDate = '".$obj->dueDate."',
				originalDueDate = '".$obj->originalDueDate."',
				paymentDate = '".$obj->paymentDate."',
				clientPaymentDate = '".$obj->clientPaymentDate."',
				invoiceUrl = '".$obj->invoiceUrl."',
				invoiceNumber = '".$obj->invoiceNumber."',
				externalReference = '".$obj->externalReference."',
				deleted = '".$obj->deleted."',
				anticipated = '".$obj->anticipated."',
				creditDate = '".$obj->creditDate."',
				estimatedCreditDate = '".$obj->estimatedCreditDate."',
				bankSlipUrl = '".$obj->bankSlipUrl."',
				lastInvoiceViewedDate = '".$obj->lastInvoiceViewedDate."',
				lastBankSlipViewedDate = '".$obj->lastBankSlipViewedDate."'
	";
	$Eviado = mysqli_query($conexao2,$insert);	
	

	$cel = explode(" ",$celss);
	$cel2 = str_replace("-","",$cel[3]);
	$cel1 = $cel[0]."".$cel[1].$cel2; //Sem o 9
	$cel2 = $cel[0]."".$cel[1].$cel[2].$cel2; //Com o 9
	
	//$celular = $cel1.",".$cel2;
	
	if($cel[1]=='92' || $cel[1]=='51' || $cel[1]=='75' || $cel[1]=='63' || $cel[1]=='41'){
		$celular = $cel1;
	}else{
		$celular = $cel2;
	}

	 
	$link = $obj->bankSlipUrl;
	$link2 = $obj->invoiceUrl;
	
	
	//$description = "Teste integracao"; //comente
	//$link = 'https://elmenoufi.com.br'; //comente
	//$link2 = 'https://elmenoufi.com.br'; //comente
		
	$titulo = "Boleto de cobrança";
	$descBole = "Sistema business T M Elmenoufi";	
	
	//Atualização
	$dataCriada = $obj->dateCreated;
	$Dc = explode("-",$dataCriada);
	$mes = (
			(($Dc[1]=="01") ? "Janeiro" : 
			(($Dc[1]=="02") ? "Fevereiro" : 
			(($Dc[1]=="03") ? "Março" : 
			(($Dc[1]=="04") ? "Abril" : 
			(($Dc[1]=="05") ? "Maio" :
			(($Dc[1]=="06") ? "Junho" :
			(($Dc[1]=="07") ? "Julho" : 
			(($Dc[1]=="08") ? "Agosto" :
			(($Dc[1]=="09") ? "Setembro" :
			(($Dc[1]=="10") ? "Outubro" :
			(($Dc[1]=="11") ? "Novembro" :
			(($Dc[1]=="12") ? "Dezembro" :
			"")))))))))))));		
			
			
	$referente = $mes ." de ".$Dc[0];
	$valorCobrado = " R$ ". number_format($obj->value,2,",",".");
	$Vn = explode("-",$obj->dueDate); ;
	$vencimento = $Vn[2]."/".$Vn[1]."/".$Vn[0];		
	//--------------------------

	
	if($Eviado){
		$mesSeginte = date("Y-m",strtotime("+1 month"));
		//echo "<br>"."Mes seguinte ". $mesSeginte ."<br>"; comente

		if($status_whats_desc=='CONNECTED'){
			EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat,$referente,$valorCobrado,$vencimento);
		}
		mysqli_query($conexao2," update clientes set ano_mes = '".$mesSeginte."' where id='".$id_user."' ");

		list($logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado) = mysqli_fetch_row(mysqli_query($conexao, "select logo, empresa, cpf_cnpj, site, email, contato, endereco, cep, cidade, estado from cobrancas_empresa where id_usuario = '$idUser' "));
		if($email){
			SendEmail($nome, $obj->value, $obj->dueDate, $description, $obj->invoiceUrl, $emailDb, $emails, $logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado);
		}		
		
		
	}
    

	
}




function EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat,$referente,$valorCobrado,$vencimento){
	
$tituloFx = "Olá obrigado por utilizar os nossos serviços!\n*Boleto de cobrança*";
$descBoleFx = "Sistema business T M Elmenoufi";	
	
$tituloDb = ($tituloWhat);
$descBoleDb = ($descricaoWhat);

$mensagemEnviar = (($tituloDb)?$tituloDb:$tituloFx)."\n".(($descBoleDb)?$descBoleDb:$descBoleFx)."\n*Referente*: a ".$referente."\n*Vencimento*: ".$vencimento."\n*Valor*:".$valorCobrado."\n\n*".$link2."*";	
	
$tratarCelss = explode(",",$celular);	

$numeros = $tratarCelss;	
	
$authorization = "Bearer $token";
	
$fields = array
(
	'mensagem' => $mensagemEnviar ,
	'numbers' => $numeros
);
	
$headers = array
(
'Content-Type: application/json',	
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/text' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );


	echo "<script>parent.EnviadonNewCob('');</script>";
}



function SendEmail($nome, $value, $dueDate, $description, $invoiceUrl, $emailSend, $emails, $logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado){
	
    $infoEmail = "Olá, sua cobrança";
    $Subject = $empresa;

	$html  .= '<img src="'.$logo.'">&nbsp;&nbsp;';

	$html  .= '<span style="font-weight:700;font-size:20pt;">'.$empresa.'</span><br><br>';
	
	$html  .= '<span style="font-weight:700;font-size:16pt;">Ol&aacute;, '.($nome).'</span><br><br>';
    
	$html  .= '<span style="font-weight:100;font-size:14pt;">Lembramos que a sua cobran&ccedil;a gerada por '.$empresa.' no valor de <b>R$ '.number_format($value,2,",",".").'</b> vence em <b>'.dataBr($dueDate).'</b></span>.<br>';
 
	$html  .= 'Descri&ccedil;&atilde;o da cobran&ccedil;a: '.($description).'<br><br>';

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