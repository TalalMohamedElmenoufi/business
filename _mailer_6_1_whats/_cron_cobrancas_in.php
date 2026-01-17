<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

	list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

/*ratamento timezones*/
$timezones = array(
'AC' => 'America/Rio_branco',   'AL' => 'America/Maceio',
'AP' => 'America/Belem',        'AM' => 'America/Manaus',
'BA' => 'America/Bahia',        'CE' => 'America/Fortaleza',
'ES' => 'America/Sao_Paulo',    'MA' => 'America/Fortaleza',
'GO' => 'America/Sao_Paulo',    'MS' => 'America/Campo_Grande',
'MT' => 'America/Cuiaba',	    'PR' => 'America/Sao_Paulo',
'MG' => 'America/Sao_Paulo',    'PA' => 'America/Belem',   
'PB' => 'America/Fortaleza',    'PI' => 'America/Fortaleza',
'PE' => 'America/Recife',		'RN' => 'America/Fortaleza',
'RJ' => 'America/Sao_Paulo',    'RO' => 'America/Porto_Velho',
'RS' => 'America/Sao_Paulo',    'SC' => 'America/Sao_Paulo',
'RR' => 'America/Boa_Vista',    'SP' => 'America/Sao_Paulo',
'SE' => 'America/Maceio',       'DF' => 'America/Sao_Paulo', 
'TO' => 'America/Araguaia',     
//'DF' => 'America/Brasilia',
);
/*----------------------------------------------*/
 


$pergunta = "select * from usuarios where token != '' and status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){

	
	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];	
	
	
	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$d->id);

	list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));	

	list($api_token_cliente) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '".$d->id."' and status = 'Liberado' "));	

	if($api_token_dono==$api_token_cliente){
		$api_token = $api_token_dono ;
	}else{
		$api_token = $api_token_cliente ;	
	}	

	
	list($tituloWhat,$descricaoWhat,$imgWhat) = mysqli_fetch_row(mysqli_query($conexao2, "select titulo,descricao,img from whats_config "));	
	
	ListaConexao($conexao2,$d->id,$timezone,$api_token,$d->token,$ip,$porta,$tituloWhat,$descricaoWhat,$imgWhat);
	
}







function ListaConexao($conexao2,$id_user,$timezone,$api_token,$token,$ip,$porta,$tituloWhat,$descricaoWhat,$imgWhat){

		$cobrancas = "select * from clientes where ano_mes='".date('Y-m')."' and gerar = '1' ";
		$Rcobranca = mysqli_query($conexao2,$cobrancas);

		$ValorG = "";
		while ($c = mysqli_fetch_object($Rcobranca)){

			//echo $c->id ." - ". ($c->nome)." - ".$c->celular."<br>";

			$AnoMes = date('Y-m',time());
			$servicos = "select * from servicos where id_cliente='".$c->id."' and data_adesao='0000-00-00' or id_cliente='".$c->id."' and data_adesao like '%$AnoMes%' ";
			$Rservicos = mysqli_query($conexao2,$servicos);
			$Vtotal = "";
			$descUnific = "";
			while ($s = mysqli_fetch_object($Rservicos)){
				//echo ($s->descricao). " - ". $s->valor ." <br>";

				$ValorG += $s->valor;
				$Vtotal += $s->valor;	
				$descUnific[] = ($s->descricao). " no valor de R$ ".$s->valor." ";	

			}
			//echo "Total: ".$Vtotal."<br>";
			//echo "<hr>";


			if($c->envio=='1'){			
				foreach ($descUnific as $descSeparado) {

					$trataInfo = explode("R$",$descSeparado);
					$trataInfo1 = $trataInfo[0]." R$ ".$trataInfo[1];					
					
					$id_asaas = $c->id_asaas;
					$billingType = $c->billingType;
					$dueDate = date('Y-m-').$c->dia_vencimento; //Data de vencimento da cobrança
					$value = $trataInfo[1]; //Valor da cobrança
					$description = $trataInfo1; //Descrição da cobrança
					$externalReference = "x"; //Campo livre para busca
					$discountValue = 0; //Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
					$dueDateLimitDays = 0; //Dias antes do vencimento para aplicar desconto. Ex: 0 = até o vencimento, 1 = até um dia antes, 2 = até dois dias antes, e assim por diante
					$fineValue = 1; //Percentual de multa sobre o valor da cobrança para pagamento após o vencimento
					$interestValue = 1; //Percentual de juros ao mês sobre o valor da cobrança para pagamento após o vencimento			

					GerarFatura($ip,$porta,$token,$conexao2,$c->id,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$c->celular,$tituloWhat,$descricaoWhat,$imgWhat,$c->nome,$c->email,$c->emails);


					//echo "<hr>";
				}
			}else{

				$id_asaas = $c->id_asaas;
				$billingType = $c->billingType;
				$dueDate = date('Y-m-').$c->dia_vencimento; //Data de vencimento da cobrança
				$value = $Vtotal; //Valor da cobrança
				$description = implode(", ",$descUnific); //Descrição da cobrança
				$externalReference = "x"; //Campo livre para busca
				$discountValue = 0; //Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
				$dueDateLimitDays = 0; //Dias antes do vencimento para aplicar desconto. Ex: 0 = até o vencimento, 1 = até um dia antes, 2 = até dois dias antes, e assim por diante
				$fineValue = 1; //Percentual de multa sobre o valor da cobrança para pagamento após o vencimento
				$interestValue = 1; //Percentual de juros ao mês sobre o valor da cobrança para pagamento após o vencimento	

				GerarFatura($ip,$porta,$token,$conexao2,$c->id,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$c->celular,$tituloWhat,$descricaoWhat,$imgWhat,$c->nome,$c->email,$c->emails);




				//echo "<hr>";
			}	   



		}	
	
	
	
}




function GerarFatura($ip,$porta,$token,$conexao2,$id_user,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$celss,$tituloWhat,$descricaoWhat,$imgWhat,$nome,$email,$emails){
	
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
	
	//var_dump($response);
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
	

	//$cel = explode(" ",$celss);
	//$cel2 = str_replace("-","",$cel[3]);
	//$celular = $cel[0]."".$cel[1].$cel2;
	
	$cel = explode(" ",$celss);
	$cel2 = str_replace("-","",$cel[3]);
	$cel1 = $cel[0]."".$cel[1].$cel2;
	$cel2 = $cel[0]."".$cel[1].$cel[2].$cel2;	
	//$celular = $cel1.",".$cel2;
	
	if($cel[1]=='92' || $cel[1]=='51' || $cel[1]=='75' || $cel[1]=='63' || $cel[1]=='41'){
		$celular = $cel1;	
	}else{
		$celular = $cel2;	
	}
	
	
	$link = $obj->bankSlipUrl;
	$link2 = $obj->invoiceUrl;
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
		echo "<br>"."Mes seguinte ". $mesSeginte ."<br>";
		EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat,$referente,$valorCobrado,$vencimento);
	    mysqli_query($conexao2," update clientes set ano_mes = '".$mesSeginte."' where id='".$id_user."' ");
		
		$emails = $email.",".$emails;	
		$emailsDb = explode(",",$emails);
		//EnviarEmail($nome,$emailsDb,$description,$obj->status); //foi pausada
	}
	
}



function EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat,$referente,$valorCobrado,$vencimento){

$Ola = "Olá obrigado por utilizar os nosso serviços!";		
$tituloDb = ($tituloWhat);
$descBoleDb = ($descricaoWhat);
	
	
$mensagemEnviar = $Ola."\n".$tituloDb."\n".$descBoleDb."\n*Referente*: a ".$referente."\n*Vencimento*: ".$vencimento."\n*Valor*:".$valorCobrado."\n\n*".$link2."*" ;
		
	
$tratarCelss = explode(",",$celular);	
$numeros = $tratarCelss;
	
$authorization = "Bearer $token";
	
$fields = array
(
	
	'numbers' => $numeros,
	'mensagem' => $mensagemEnviar
	
);
	
$headers = array
(
'Content-Type: application/json',	
'Authorization: ' . $authorization
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/link' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );


	
	
}





/*function EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat){

$Ola = "Olá obrigado por utilizar os nosso serviços!";		
$tituloDb = ($tituloWhat);
$descBoleDb = ($descricaoWhat);
	
$mensagemEnviar = $Ola."\n".$tituloDb."\n".$descBoleDb."\n".$link ;		

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

NotificarWhats($ip,$porta,$token,$celular,$description,$link,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat);	
	
EnviarEmail($nome,$emailsDb,$description,$status); //foi pausada	

}*/




function NotificarWhats($ip,$porta,$token,$celular,$description,$link,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat){

	
$ultimos3 = substr($imgWhat , -3);
$ultimos4 = substr($imgWhat , -4);	
if($ultimos3 == 'png' or $ultimos3 == 'PNG' or $ultimos3 == 'jpg' or  $ultimos3 == 'JPG'){
	$ext = $ultimos3;
}
elseif($ultimos4 == 'JPEG' or $ultimos4 == 'jpeg'){
	$ext = $ultimos4;
}	
	
	
if($imgWhat){
	$cfile = curl_file_create('/home/elmenoufi/public_html/business/img/whats/'.$imgWhat,'image/'.$ext);
	$tituloDb = ($tituloWhat);
	$descBoleDb = ($descricaoWhat);
}else{
    $cfile = curl_file_create('/home/elmenoufi/public_html/business/img/cobrancas/whatsapp-tme.jpg','image/jpg');
	$tituloDb = ($titulo);
	$descBoleDb = ($descBole);	
}
	
$numeros[] = $celular;	
	
$authorization = "Bearer $token";
	
$fields = array
(
	'img' => $cfile,
	'link' => $link,
	'numbers' => implode(",",$numeros) ,
	'titulo' => $tituloDb,
	'descricao' => $descBoleDb
);
	
$headers = array
(
'Content-Type: multipart/form-data',
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/link' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields );
curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
$result = curl_exec($ch );
curl_close( $ch );

//echo $result."<br><br>";	

//unlink("/var/www/whats_elmenoufi/uploads/img-1596292589270.img"); 	

}



function EnviarEmail($nome,$emailsDb,$description,$statusDb){
	
		$status = ( (($statusDb=='PENDING')?'PENDENTE':  
				   
				    ( ($statusDb=='CONFIRMED')?'CONFIRMADO': 
				   
				    ( ($statusDb=='RECEIVED')?'RECEBIDO': 
				   
				    ( ($statusDb=='RECEIVED_IN_CASH')?'RECEBIDO EM DINHEIRO': 
				   
				    ( ($statusDb=='OVERDUE')?'VENCIDO': 
				   
				    ( ($statusDb=='REFUND_REQUESTED')?'REEMBOLSO SOLICITADO': 
				   
				    ( ($statusDb=='REFUNDED')?'DEVOLVEU': 
				   
				    ( ($statusDb=='CHARGEBACK_REQUESTED')?'COBRANÇA SOLICITADA':
				   
				    ( ($statusDb=='CHARGEBACK_DISPUTE')?'RECUPERAR A DISPUTA': 
				   
				    ( ($statusDb=='AWAITING_CHARGEBACK_REVERSAL')?'AGUARDANDO REVERSÃO DE COBRANÇA': 
				   
				    ( ($statusDb=='DUNNING_REQUESTED')?'DUNNING SOLICITADO': 
				   
				    ( ($statusDb=='DUNNING_RECEIVED')?'DUNNING RECEBIDO': 
				   
				    ( ($statusDb=='AWAITING_RISK_ANALYSIS')?'AGUARDANDO ANÁLISE DE RISCO':
				   
				    '' 
		            ))))))))))))));		
	
	
	$mensagemEnviar = "Cobrança T M Elmenoufi<br>Cliente: $nome<br>Serviço: $description<br>Com status: <b>$status</b>.";	
	 
	$mail = new PHPMailer(true);

	try {
		//Server settings
		//$mail->SMTPDebug = 1;                          // Enable verbose debug output
		$mail->isSMTP();                                 // Send using SMTP
		$mail->Host       = 'elmenoufi.com.br';          // Set the SMTP server to send through
		$mail->SMTPAuth   = true;                        // Enable SMTP authentication
		$mail->Username   = 'marketing@elmenoufi.com.br'; // SMTP username
		$mail->Password   = '3lm3n0uf!';               // SMTP password

		$mail->SMTPSecure = 'tls'; 						 //tls or ssl
		$mail->Port       = 587; 					     // TCP port to connect to 587  ou 465 

		$mail->setFrom('financeiro@elmenoufi.com.br', 'Financeiro - T M Elmenoufi');
		
		foreach ($emailsDb as $emailist) {
			$mail->addAddress($emailist, $nome);
		}	
 
		// Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'T M Elmenoufi';
		$mail->Body    = ($mensagemEnviar);
		$mail->AltBody = 'E-mail enviado com sucesso!';

		$mail->send();
		echo 'Message has been sent';
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}	
	
	
}


?>