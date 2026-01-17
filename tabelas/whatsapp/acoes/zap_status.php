<?php
include('../../../includes/connect.php');

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));

$pergunta = "select token, status_whats_desc from usuarios where id = '".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao,$pergunta);
$d = mysqli_fetch_object($resultado);

$token = $d->token;

if($d->status_whats_desc=='CONNECTED'){
	StartSt($ip,$porta,$token,$tipo);
}else{
	echo "<script>parent.VoltarStatus('LerQrs','Iniciar Qr');</script>";
}


if($_GET[onzap]=='1'){
	StartSt($ip,$porta,$token,$tipo);
}

function StartSt($ip,$porta,$token,$tipo){
	
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

	echo "<script>parent.VoltarStatus('$status','$mensagem');</script>";

}
?>