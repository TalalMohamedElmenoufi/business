<?php
$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot");

$instance = $_GET['instance']; 
$key = $_GET['key'];
qrcode($key);

function qrcode($conexao,$instance,$key){



$pergunta = "select id, nome, senha, token, status_whats_desc from usuarios where id = '".$instance."' ";
$resultado = mysqli_query($conexao,$pergunta);
$d = mysqli_fetch_object($resultado);	
	
list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));	
	
$token = $key;
$nome = explode(" ",$d->nome);


$authorization = "Bearer $token";

$fields = array
(
	'username' => $nome[0],
	'password' => $d->senha,
	'instance' => $d->id,
);


$headers = array
(
'Content-Type: application/json',
'Authorization: ' . $authorization		
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/qrcode' );
curl_setopt( $ch,CURLOPT_GET, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_GETFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );	
//echo $result."<br><br>";
	
$jsonObjlista = json_decode($result);
$GetQr = $jsonObjlista->qrcode;

	echo "<center>$GetQr</center>";
	
	
}
?>