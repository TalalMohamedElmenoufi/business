<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));	

//$idUsuario = 1;
//$iidPay = "";
$idUsuario = $_POST[idUsuario];
$idPay = $_POST[Pay];

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
 



$pergunta = "select * from usuarios where id = '".$idUsuario."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);


	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];	
	
	
	$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$d->id);

	list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));	

	list($api_token_cliente) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '".$d->id."' and status = 'Liberado' "));	

	if($api_token_dono==$api_token_cliente){
		$api_token = $api_token_dono ;
	}else{
		$api_token = $api_token_cliente ;	
	}	

	list($tituloWhat,$descricaoWhat,$imgWhat) = mysqli_fetch_row(mysqli_query($conexao2, "select titulo,descricao,img from whats_config "));

	ListaConexao($conexao2,$d->id,$timezone,$api_token,$d->token,$ip,$porta,$idPay,$tituloWhat,$descricaoWhat,$imgWhat);



function ListaConexao($conexao2,$id_user,$timezone,$api_token,$token,$ip,$porta,$idPay,$tituloWhat,$descricaoWhat,$imgWhat){

		$cobrancas = "select * from cobrancas where id = '".$idPay."' ";
		$Rcobranca = mysqli_query($conexao2,$cobrancas);

		while ($f = mysqli_fetch_object($Rcobranca)){

   		
				


		}	
	
	
	
}




function GerarFatura($ip,$porta,$token,$conexao2,$id_user,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$celss,$tituloWhat,$descricaoWhat,$imgWhat){
	
	/*echo 
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
		'payment' => $payment,
		'installment' => $installment,
		'serviceDescription' => $dueDate,
		'observations' => $observations,
		'value' => $value,
		'deductions' => $deductions,
		'effectiveDate' => $effectiveDate,
		'taxes' =>
			array
			(
			'retainIss' => $retainIss,
			'iss' => $iss,	
			'cofins' => $cofins,	
			'csll' => $csll,	
			'inss' => $inss,	
			'ir' => $ir,	
			'pis' => $pis,	
			),
		'municipalServiceId' => $municipalServiceId,
		'municipalServiceCode' => $municipalServiceCode,
		'municipalServiceName' => $municipalServiceName,
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/invoices");
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
	$celular = $cel[0]."".$cel[1].$cel2;
	$link = $obj->bankSlipUrl;
	//$description = "Teste integracao"; comente
	//$link = 'https://elmenoufi.com.br'; comente
	$titulo = "Boleto de cobrança";
	$descBole = "Sistema business T M Elmenoufi";	
	
	if($Eviado){
		$mesSeginte = date("Y-m",strtotime("+1 month"));
		//echo "<br>"."Mes seguinte ". $mesSeginte ."<br>"; comente
		EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat);
	    mysqli_query($conexao2," update clientes set ano_mes = '".$mesSeginte."' where id='".$id_user."' ");
	}
	//EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat); para integracao comente
}


function EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat){
	 
$Ola = "Olá obrigado por utilizar os nosso serviços!";	
$tituloDb = ($tituloWhat);
$descBoleDb = ($descricaoWhat);
	
$mensagemEnviar = $Ola."\n".$tituloDb."\n".$descBoleDb."\n".$link ;	

$numeros[] = $celular;	
	
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

//echo "<hr>".$result."<br><br>";		
	
NotificarWhats($ip,$porta,$token,$celular,$description,$link,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat);	
	
	
}



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

//echo $cfile .' e '. $tituloDb .' e '.$descBoleDb.'<br>';	
	
	
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

//echo "<hr>".$result."<br><br>";	

//unlink("/var/www/whats_elmenoufi/uploads/img-1596292589270.img"); 	

echo "<script>parent.Enviado();</script>";
	
}

?>