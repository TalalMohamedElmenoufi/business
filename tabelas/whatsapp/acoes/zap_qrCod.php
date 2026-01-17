<?php
include('../../../includes/connect.php');
?>
<style>
	
	.Refresh{
		cursor:pointer;
		border:#FFFFFF solid 1px;
	}
	.Refresh:hover{
		border:#5EE300 solid 1px;
	}
	

</style>
<?php
list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));

$pergunta = "select id, nome, senha, token, status_whats_desc from usuarios where id = '".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao,$pergunta);
$d = mysqli_fetch_object($resultado);

$token = $d->token;
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

$returnStatus = explode(" ",$d->status_whats_desc);

/*
if($returnStatus[0]=='CONNECTED'){
	echo "<img src='./img/conectado.jpg' width='220' ><br>".date('H:i:s', time());
}elseif($d->status_whats_desc=='INICIALIZANDO'){
	echo "<img src='./img/inicializando.gif' width='220' ><br>".date('H:i:s', time());
}
elseif($d->status_whats_desc=='TIMEOUT'){
	echo "<img src='./img/timeout.png' width='220' ><br>".date('H:i:s', time());
}
elseif($d->status_whats_desc=='PAIRING'){
	echo "<img src='./img/timeout.png' width='220' ><br>".date('H:i:s', time());
}elseif($d->status_whats_desc=='OFFLINE' or $d->status_whats_desc=='UNPAIRED'  or $d->status_whats_desc=='UNPAIRED_IDLE' or $d->status_whats_desc=='UNPAIRED' ){
	echo "<img src='./img/refresh.png' width='220' class='Refresh' onclick='Refresh()' ><br>".date('H:i:s', time());
}
elseif($d->status_whats_desc=='PEND QRCODE' ){
	echo "<img src='data:image/jpeg;charset=utf-8;base64,$GetQr' ><br>".date('H:i:s', time());
}
*/


echo "<img src='data:image/jpeg;charset=utf-8;base64,$GetQr' ><br>".date('H:i:s', time());

?>