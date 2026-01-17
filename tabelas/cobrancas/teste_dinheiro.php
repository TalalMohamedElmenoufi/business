<?php

	
	$api_token = '7317fcb9917ffbb61e9ba01e80c97d32673c1aefb27d486364d77a40d41098e3';
	$value = '1614.19';
	$idCobranca = 'pay_577172018547' ;
	$paymentDate = '2020-08-20';

	PagoEmDinheio($api_token,$idCobranca,$paymentDate,$value);


function PagoEmDinheio($api_token,$idCobranca,$paymentDate,$value){
	
	$fields = array
	(
		'paymentDate' => $paymentDate,
		'value' => $value,
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments/$idCobranca/receiveInCash");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, "TRUE");
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json",
	  "access_token: $api_token"
	));

	$response = curl_exec($ch);
	curl_close($ch);	
	
	echo $response;
	
}


?>