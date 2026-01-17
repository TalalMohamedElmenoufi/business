<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");
 
list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));	


$pergunta = "select * from usuarios where status_whats_desc = 'CONFLICT' ";
$resultado = mysqli_query($conexao, $pergunta);


while($d = mysqli_fetch_object($resultado)){
	
	onzap($ip,$porta,$d->token);

}


function onzap($ip,$porta,$token){
	
	$authorization = "Bearer $token";

	$headers = array
	(
	'Content-Type: application/json',
	'Authorization: ' . $authorization	
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/onzap');
	curl_setopt( $ch,CURLOPT_GET, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	$result = curl_exec($ch );
	curl_close( $ch );	
	
	
}

?>