<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");


list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));	

$pergunta = "select * from usuarios order by id ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){

	
	echo $d->id ." - ". $d->status_whats_desc . "<br>";
	if(!$d->status_whats_desc or $d->status_whats_desc == "PEND QRCODE" or $d->status_whats_desc == "UNPAIRED_IDLE" or $d->status_whats_desc == "CONFLICT" or $d->status_whats_desc == "UNPAIRED"){
		OffZap($conexao,$d->id,$ip,$porta );
	}

	
	StatusWhats($conexao,$d->id,$d->nome,$d->senha,$d->token,$d->status_whats_desc,$ip,$porta);
}



function StatusWhats($conexao,$cliente,$nomeDb,$senha,$token,$status_whats_desc,$ip,$porta){
	
	
	
$nomeDb = explode(" ",$nome);

$tipo = "getStatus";

$authorization = "Bearer $token";

$fields = array
(
	'username' => $nome[0],
	'password' => $senha,
	'instance' => $cliente,
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

	echo "<br>CheckStatus: ".$CheckStatus."<br>";
	echo "<br>Mensagem: ".$mensagem."<br>";

	if($status_whats_desc!=$CheckStatus){
		mysqli_query($conexao," update usuarios set status_whats_desc = '".$CheckStatus."' where id = '".$cliente."' ");
	} 	

	$statusDb = $status_whats_desc;
	
}else{
	
	$CheckStatus = 'ERRO';
	$statusDb = 'ERRO';
	echo "<br>ERRO<br>";
	
}	
	
	
}




function OffZap($conexao,$cliente,$ip,$porta){
	
$pergunta = "select id, nome, senha, token from usuarios where id = '".$cliente."' ";
$resultado = mysqli_query($conexao,$pergunta);
$d = mysqli_fetch_object($resultado);

$nome = explode(" ",$d->nome);

$tipo = "offzap"; 

$authorization = "Bearer $d->token";

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
	
//echo $result;

	
$jsonObjlista = json_decode($result);
$status = $jsonObjlista->status;
$mensagem = $jsonObjlista->mensagem;

if($status->servidor){
	$CheckStatus = $status->servidor;
}else{
	$CheckStatus = $status;
}
echo $CheckStatus;

mysqli_query($conexao," update usuarios set  status_whats = '0', status_whats_desc='".$CheckStatus."' where id = '".$cliente."' "); 	
	
}
?>