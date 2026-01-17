<?php
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot");

$id_usuario = $_GET[instance];
GerarToken($conexao,$id_usuario);

function GerarToken($conexao,$id_usuario){

	
	$pergunta = "select id, nome, senha, token from usuarios where id = '".$id_usuario."' ";
	$resultado = mysqli_query($conexao,$pergunta);
	$d = mysqli_fetch_object($resultado);

	list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));	
	
	$nome0 = explode(" ",$d->nome);	
	$nome = ($nome0[0]);
	
	$fields = array
	(
		'username' => $nome,
		'password' => $d->senha,
		'instance' => $d->id,
	);

	$headers = array
	(
	'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/getToken' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );	
	$jsonObjlista = json_decode($result);
	$token = $jsonObjlista->accessToken;	

	if($d->token==""){
		mysqli_query($conexao," update usuarios set token = '".$token."' where id = '".$d->id."' ");	
	}

	echo "<center>$token</center>";
	
	
}
?>