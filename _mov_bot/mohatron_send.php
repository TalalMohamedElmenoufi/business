#!/usr/bin/php -q
<?php
error_reporting(0);
//error_reporting(E_ALL);

date_default_timezone_set('America/Manaus');

$host = "161.97.75.98";
$port = 1976;

$clients = array();
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,$host,$port);
socket_listen($socket);
socket_set_nonblock($socket);

while(true)
{
    if(($newc = socket_accept($socket)) !== false){
		
	echo "Client $newc has connected\n";
	$clients[] = $newc;
		
    }else{
	
	$con = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_21");
	$con2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_26");
		
	$pergunta = "select * from mov_bot_".date('mY')." where entregue = '0' ";
	$resultado = mysqli_query($con,$pergunta);	

	while($d = mysqli_fetch_object($resultado)){
		
	$de_quem = explode("@",$d->de_quem);
	$para_quem = explode("@",$d->para_quem);
    $mensagem = ($d->mensagem);
    //$mensagem = str_replace("\n"," ", ($d->mensagem));		
    
		if($mensagem){
			EnviaMOH1($con,$d->id,$de_quem[0],$para_quem[0],$mensagem,$d->data_registro);
		}		
	
	}

	$pergunta2 = "select * from mov_bot_".date('mY')." where entregue = '0' ";
	$resultado2 = mysqli_query($con2,$pergunta2);	

	while($d = mysqli_fetch_object($resultado2)){
		
	$de_quem = explode("@",$d->de_quem);
	$para_quem = explode("@",$d->para_quem);
    $mensagem = ($d->mensagem);
    //$mensagem = str_replace("\n"," ", ($d->mensagem));		
    
		if($mensagem){
			EnviaMOH1($con2,$d->id,$de_quem[0],$para_quem[0],$mensagem,$d->data_registro);
		}		

	}	
		
		
		
		
	usleep(15000000);
		
	}
}
  

function EnviaMOH1($con,$id,$de_quem,$para_quem,$mensagem,$data_registro){
	
	$fields = array
	(
		'id' => $id, 
		'de_quem' => $de_quem, 
		'para_quem' => $para_quem, 
		'mensagem' => $mensagem, 
		'data_registro' => $data_registro
	);

	$headers = array
	(
	'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://www.moh1.com.br/wapp/webhook.php' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );
	
	//echo "Result: ".$result."\n";
	
	if($result){

		mysqli_query($con," update mov_bot_".date('mY')." set entregue = '1' where id = '".$id."' ");

	}

	
}
?>