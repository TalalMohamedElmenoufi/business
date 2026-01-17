<?php
$ip = "http://207.180.219.129";
$porta = 4000;
$token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZSI6IjEiLCJpYXQiOjE2NDg5MTA4NzAsImV4cCI6MTY4MDQ0Njg3MH0.AWAJqf4hRidvwEZXUS70Gu7teKGznegWLgTAda79wAY";


$celular = "559291725319";


$subject = "Assunto Whats teste";
$nome = "Talal Mohamed";
$telefone = "559291725319";
$message = "Ola mundo 2";
$subject = "Teste envio whatsap";
$meuIp = "1928.908.098";
$hostname = "host name";
$timezone = "Manaus";


EnviarWhatsapp($ip,$porta,$token,$subject,$nome,$telefone,$message,$celular,$meuIp,$hostname,$timezone);



function EnviarWhatsapp($ip,$porta,$token,$subject,$nome,$telefone,$message,$celular,$meuIp,$hostname,$timezone){

	 
$subjectDb = ($subject);
$nomeDb = ($nome);
$telefoneDb = ($telefone);
$messageDb = ($message);

	
$mensagemEnviar = "*Tipo:* ".$subjectDb."\n*Nome:* ".$nomeDb."\n*Telefone:* ".$telefoneDb."\n*Mensagem:* ".$messageDb."\n\n*IP:* $meuIp"."\n*Hostname:* $hostname"."\n*Timezone:* $timezone";	
	 
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
curl_setopt( $ch,CURLOPT_SSLVERSION, 1 );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );

echo $result ;
	  
} 


?>