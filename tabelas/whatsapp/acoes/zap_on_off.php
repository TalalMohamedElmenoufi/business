<?php
include('../../../includes/connect.php');

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));

$pergunta = "select id, nome, senha, token from usuarios where id = '".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao,$pergunta);
$d = mysqli_fetch_object($resultado);

$nome = explode(" ",$d->nome);

$tipo = $_POST[onOff];

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
$mensagem = $jsonObjlista->mensagem;
echo $mensagem;


mysqli_query($conexao," update usuarios set status_whats = '".$_POST[status]."' where id = '".$_SESSION[id_usuario]."' "); 

?>