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
 


list($api_token) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));
list($token,$celular,$celulares) = mysqli_fetch_row(mysqli_query($conexao, "select token,celular,celulares from usuarios where id = '1' "));

$pergunta = "select * from usuarios where token != '' and status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);
//echo $pergunta."<br>";
while($d = mysqli_fetch_object($resultado)){
	
	
	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];	
	
	
	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$d->id);
	
	
	$celularDb = explode(" ",$d->celular);
	
	if($celularDb[1]=='92' or $celularDb[1]=='85' or $celularDb[1]=='82' || $celularDb[1]=='51' || $celularDb[1]=='75' || $celularDb[1]=='63'){
		$tira = str_replace("-","",$celularDb[3]);
		$cells = $celularDb[0].''.$celularDb[1].''.$tira;
	}else{
		$cells = $celularDb[0].''.$celularDb[1].''.$celularDb[2].''.$tira;//caso acrecente o 9
	}
	
	
$AnoMEs = date("Y-m");		
$pergunta2 = "select id_rg, id, quantidade_plano, description, value, status, customer from elmenoufi_bot_".$d->id.".asaas_cobranca_whatsapp 
where

status='PENDING' or 
status='RECEIVED_IN_CASH' or
status='OVERDUE' or 
status='REFUND_REQUESTED' or 
status='REFUNDED' or 
status='CHARGEBACK_REQUESTED' or 
status='CHARGEBACK_DISPUTE' or 
status='AWAITING_CHARGEBACK_REVERSAL' or 
status='DUNNING_REQUESTED' or 
status='DUNNING_RECEIVED'  or 
status='AWAITING_RISK_ANALYSIS' or 
status=''
";
$resultado2 = mysqli_query($conexao2, $pergunta2);
echo $pergunta2."<br>";
while($d2 = mysqli_fetch_object($resultado2)){
	//echo $d->id ." | ". $d2->id_rg." - ".$d2->quantidade_plano." - ".$d2->customer."<br>";
	
$emails = $d2->email.",".$d2->emails;	
ListaConexao($conexao,$conexao2,$timezone,$api_token,$token,$celular,$celulares,$ip,$porta,$d->id,$d2->id_rg,$d2->id,$d2->quantidade_plano,$d2->description,$d2->value,$d2->status,$d2->nome,$emails);	

}
	

}

function ListaConexao($conexao,$conexao2,$timezone,$api_token,$token,$celular,$celulares,$ip,$porta,$idCli,$id_rg,$pay_id,$quantidade_plano,$description,$value,$statusDb,$nome,$emails){
	
	list($nome,$creditos_msg) = mysqli_fetch_row(mysqli_query($conexao, "select nome,creditos_msg from usuarios where id = '".$idCli."' "));
	
	$Clientes = ($nome);
	$celularDb = explode(" ",$celular);
	if($celularDb[1]=='92' or $celularDb[1]=='85' or $celularDb[1]=='82'){
		$tira = str_replace("-","",$celularDb[3]); ;
		$cells = $celularDb[0].''.$celularDb[1].''.$tira;
	}else{
		$cells = $celularDb[0].''.$celularDb[1].''.$tira;
		//$cells = $celularDb[0].''.$celularDb[1].''.$celularDb[2].''.$tira;//caso acrecente o 9
	}	
	
	
	//echo "$api_token<br>";
	//echo "$token<br>";
	//echo "$cells,$celulares<br>";
	//echo "idCli:$idCli e id_rg:$id_rg pay_id:$pay_id<br>";
	//echo "Tabela $Clientes e $description e $value e $statusDb <hr>";
	
	StatusFatura($conexao,$conexao2,$timezone,$api_token,$token,$ip,$porta,$Clientes,$description,$value,$cells,$celulares,$idCli,$id_rg,$pay_id,$statusDb,$creditos_msg,$quantidade_plano,$nome,$emails);	
	
	
}

function StatusFatura($conexao,$conexao2,$timezone,$api_token,$token,$ip,$porta,$clienteDb,$description,$value,$cells,$celulares,$idCli,$id_rg,$pay_id,$statusDb,$creditos_msg,$quantidade_plano,$nome,$emails){
	
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments/$pay_id");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json",
	  "access_token: $api_token"
	));

	$response = curl_exec($ch);
	curl_close($ch);

	$obj = json_decode($response);
	$dueDate = $obj->dueDate ;
	$statusApi = $obj->status ;
	$originalValue = $obj->originalValue ;
	$interestValue = $obj->interestValue ;	
	$valueApi = $obj->value ;
	$netValueApi = $obj->netValue ;
	$descriptionApi = $obj->description ;

	$paymentDate = $obj->paymentDate ;
	$clientPaymentDate = $obj->clientPaymentDate ;
	$lastInvoiceViewedDate = $obj->lastInvoiceViewedDate ;
	$lastBankSlipViewedDate = $obj->lastBankSlipViewedDate ;	
	
	
	//echo $idCli."<br>";
	//echo $statusDb."<br>";
	//echo $statusApi."<br>";
	//echo $valueApi."<br>";
	//echo $netValueApi."<br>";
	//echo $valueApi - $netValueApi ."<br>";
	//echo $descriptionApi."<br>";
	//echo $creditos_msg."<br>";
	//echo $quantidade_plano."<br>";

	$CreditarWhast = (($creditos_msg) + ($quantidade_plano));
	//echo $CreditarWhast."<br>";
	
	
	if($statusDb!=$statusApi){
		//echo "Salvar DB<br>";
		
		if($statusApi){
	 		mysqli_query($conexao2," update asaas_cobranca_whatsapp set  dueDate='".$dueDate."',  value='".$valueApi."', netValue='".$netValueApi."', originalValue='".$originalValue."', interestValue='".$interestValue."', paymentDate='".$paymentDate."', clientPaymentDate='".$clientPaymentDate."', lastInvoiceViewedDate='".$lastInvoiceViewedDate."', lastBankSlipViewedDate='".$lastBankSlipViewedDate."', status='".$statusApi."' where id='".$pay_id."' ");		

			if($statusApi=='RECEIVED'){

				mysqli_query($conexao," update usuarios set creditos_msg = '".$CreditarWhast."', alert_what = '0' where id='".$idCli."' ");

			}
			NotificarWhats($ip,$porta,$token,$cells,$celulares,$descriptionApi,$valueApi,$quantidade_plano,$clienteDb,$statusApi);

			$emailsDb = explode(",",$emails);
			//EnviarEmail($nome,$emailsDb,$clienteDb,$descriptionApi,$statusApi); //foi pausada			
		}

		
	}else{
		//echo "Não Salvar<br>";
		//NotificarWhats($ip,$porta,$token,$cells,$celulares,$descriptionApi,$valueApi,$quantidade_plano,$clienteDb,$statusApi);
	}
	
	
}



function NotificarWhats($ip,$porta,$token,$cells,$celulares,$descriptionApi,$valueApi,$quantidade_plano,$clienteDb,$statusDb){

	
		$statusApi = ( (($statusDb=='PENDING')?'PENDENTE':  
				   
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
	
	
	$NoValor = number_format($valueApi,2,",",".");
	
	if($celulares){
		$todos = $cells.",".$celulares;
		$numeros = explode(",",$todos) ;
	}else{
		$numeros[] = $cells;
	}

	$mensagemEnviar = "*Cobrança T M Elmenoufi*\n*Cliente:* $clienteDb\n*Serviço*: $descriptionApi no valor de R$ $NoValor na quantidade de *$quantidade_plano* \n*Com status*: *$statusApi*.";

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
	
//echo $result."<br>";	
	
}


function EnviarEmail($nome,$emails,$cliente,$description,$statusDb){
	
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
	
	
	$mensagemEnviar = "Cobrança T M Elmenoufi<br>Cliente: $cliente<br>Serviço: $description<br>Com status: <b>$status</b>.";	
	 
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
		
		foreach ($emails as $emailist) {
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