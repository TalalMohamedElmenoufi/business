<?php



$ip = "http://207.180.219.129";
$porta = 4000;
$token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZSI6IjEiLCJpYXQiOjE2NjkxMzgyODksImV4cCI6MTcwMDY3NDI4OX0.b6F1Qc84G0wIvXm4ZLxDQlWmOeyodzkpIX18UcX736g";
$celular = "559291725319";

//EnviarWhatsappText($ip,$porta,$token,$celular);
EnviarWhatsapp($ip,$porta,$token,$celular);

function EnviarWhatsapp($ip,$porta,$token,$celular){

$numeros[] = $celular;		
	
$authorization = "Bearer $token";
	
$fields = array
(
	
	'mensagem1' => 'Informações dos produtos',
	'mensagem2' => 'Abrir menu',
	'numbers' => $numeros,
	'select' => 'Selecione o produto',
		'atributos' =>
			array
			(
			'title' => 'Ver lista dos produtos',
				'rows' =>

					array("id" => "Sim", "title" => "Sim"),
					array("id" => "Não", "title" => "Não"),
					
			)
	
	
);
	
$headers = array
(
'Content-Type: application/json',	
'Authorization: ' . $authorization
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/botao' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
	
	echo $result;
}




function EnviarWhatsappText($ip,$porta,$token,$celular){
		

	$numeros[] = $celular;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => "OLA MUNDO! API" ,
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

	echo $result."<br><br>";

	
}


?>