<?php
include("../../../includes/connect.php");

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server   "));

list($token) = mysqli_fetch_row(mysqli_query($conexao, "select token from usuarios where id = '".$_SESSION[id_usuario]."' "));

$whatsapp = $_POST[celular];
$mensagem = $_POST[mensagem];
	
EnviarWhatsapp($conexao,$token,$whatsapp,$mensagem,$ip,$porta);	

function EnviarWhatsapp($conexao,$token,$whatsapp,$mensagem,$ip,$porta){


	$numeros[] = $whatsapp;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagem. "\n\n@elmenoufi",
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

	//echo $result."<br><br>";
	
}

?>