<?php
include('../../../includes/connect.php');

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));

$pergunta = "select token, status_whats_desc from usuarios where id = '".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao,$pergunta);
$d = mysqli_fetch_object($resultado);

$token = $d->token;
 
onzap($ip,$porta,$token,$tipo,$conexao);

function onzap($ip,$porta,$token,$tipo,$conexao){
	
	mysqli_query($conexao,"update usuarios set status_whats_desc = 'START' where id = '".$_SESSION[id_usuario]."' ");
	 
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

	$jsonObjlista = json_decode($result);
	$status = $jsonObjlista->status;
	$mensagem = $jsonObjlista->mensagem;

	
}
?>