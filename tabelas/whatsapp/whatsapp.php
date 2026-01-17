<?php
$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

$ids = "1";

$pergunta = "select * from usuarios where id in ($ids) ";
$resultado = mysqli_query($conexao,$pergunta);
while($d = mysqli_fetch_object($resultado)){

	$trat1 = str_replace("-","",$d->celular);
	$trat2 = explode(" ",$trat1);
	$celular = $trat2[0]."".$trat2[1]."".$trat2[3];
	
	$estado = ( ($trat2[1]=='92')?'':$trat2[2] );
	$tratCell = $trat2[0]."".$trat2[1]."".$estado."".$trat2[3];
	
	$whatsapp[] = $tratCell;

	//echo $tratCell."<br>";
	 
}

$key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6ImhlbGlvIiwicGFzc3dvcmQiOiIxMjM1NDYiLCJpYXQiOjE1OTM5MDA2MjYsImV4cCI6MTYyNTQzNjYyNn0.myYSSHHWe8nuSojIG7tczoczXGgj8CdJVu1sTIzKMkY";

$mensagem = "MENSAGEM DE TESTE DE INTEGRACAO API T M ELMENOUFI LTDA DATA 05/07/2020 ";

EnviarWhatsapp($conexao,$key,$mensagem,$whatsapp);

function EnviarWhatsapp($conexao,$key,$mensagem,$whatsapp) //não esta sendo usado no momento
{

$authorization = "Bearer $key";


	
$fields = array
(
	'mensagem' => $mensagem,
	'numbers' => $whatsapp
	//'numbers' => array('559291725319','559294124054')
);
	
$headers = array
(
'Content-Type: application/json',	
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'http://207.180.219.129:3000/send/text' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result."<br><br>";
	
echo json_encode( $fields )."<br><br>" ;
	
	
}

?>
