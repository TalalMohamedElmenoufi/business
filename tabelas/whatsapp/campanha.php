<?php
$cliente = 12;

$con = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

$pergunta1 = "select * from usuarios where id = '".$cliente."' ";
$resultado1 = mysqli_query($con, $pergunta1);
$u = mysqli_fetch_object($resultado1);

list($ip,$porta) = mysqli_fetch_row(mysqli_query($con, "select ip, porta from server "));


$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("<center>Autenticação invalida!</center>");


date_default_timezone_set($timezone);

$bosco = "@boscosaraiva";

$pergunta = "select * from t_".date('mY')."_smgStatuses where mensagem like '%$bosco%' and campanha = '0' ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){

$dataAtual = date( "Y-m-d H:i:s", time() );	
	
$data = $d->date;
$duracao = '24:00:00'; 
$v = explode(':', $duracao);
$agendada = date('Y-m-d H:i:s', strtotime("{$data} + {$v[0]} hours {$v[1]} minutes {$v[2]} seconds"));

	
	//echo "data db:".$d->date."<br>";
	//echo "data futura:".$agendada."<br>";
	//echo "data atual:".$dataAtual."<br>";
	
	if(strtotime($dataAtual) >= strtotime($agendada)){
		echo "enviar para:".$d->destination."<br><hr>";
		bemVindo($conexao,$d->destination,$ip,$porta,$u->token);
	}
	
	
	//echo "<hr>";
	
}

function bemVindo($conexao,$destination,$ip,$porta,$token){

	$mensagem = "Fico muito feliz em estar conectado com você aqui pelo Whatsapp!😃\nSinta-se à vontade para comentar e enviar sugestões.\nAh, e não se esqueça de salvar este contato na sua agenda.\nAbraço e até mais! 🤗\nBosco Saraiva.";
	
	$numeros[] = $destination;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagem ,
		'numbers' => $numeros
	);

	$headers = array
	(
	'Content-Type: application/json',	
	'Authorization: ' . $authorization

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/text' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );	
	
	//echo "<br>".$result;
	
	if($result){
		mysqli_query($conexao," update t_".date('mY')."_smgStatuses set campanha = '2' where destination = '".$destination."' ");
	}
	
	
}
?>