<?php
include('../../../includes/connect.php');

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));

$pergunta = "select id, nome, senha, token, status_whats_desc from usuarios where id = '".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao,$pergunta);
$d = mysqli_fetch_object($resultado);

$nome = explode(" ",$d->nome);

$tipo = "getStatus";

$token = $d->token;


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
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/'.$tipo );
curl_setopt( $ch,CURLOPT_GET, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
$result = curl_exec($ch );
curl_close( $ch );
	
//echo $result."<br><br>";

if($ch){

	$jsonObjlista = json_decode($result);
	$status = $jsonObjlista->status;
	$mensagem = $jsonObjlista->mensagem;

	if($status->servidor){
		$CheckStatus = $status->servidor;
	}else{
		$CheckStatus = $status;
	}

	echo "<br>Status: ".$CheckStatus."<br>";
	echo "<br>Mensagem: ".$mensagem."<br>";

	if($d->status_whats_desc!=$CheckStatus){
		mysqli_query($conexao," update usuarios set status_whats_desc = '".$CheckStatus."' where id = '".$d->id."' ");
	} 	

	$statusDb = $d->status_whats_desc;
	
}else{
	
	$CheckStatus = 'ERRO';
	$statusDb = 'ERRO';
	echo "<br>ERRO<br>";
	
}


?>
<script>parent.VoltarStatus('<?=$CheckStatus?>','<?=$statusDb?>');</script>