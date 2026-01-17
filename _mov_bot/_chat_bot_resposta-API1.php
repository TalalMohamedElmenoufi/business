<?php
error_reporting(0);

$host = "161.97.75.98";
$port = 1979;

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

/*MEU SCRIPT*/	
$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));
		
/*ratamento timezones*/
$timezones = array(
'AC' => 'America/Rio_branco',   'AL' => 'America/Maceio',
'AP' => 'America/Belem',        'AM' => 'America/Manaus',
'BA' => 'America/Bahia',        'CE' => 'America/Fortaleza',
'ES' => 'America/Sao_Paulo',    'MA' => 'America/Fortaleza',
'GO' => 'America/Sao_Paulo',    'MS' => 'America/Campo_Grande',
'MT' => 'America/Cuiaba',	    'PR' => 'America/Sao_Paulo',
'MG' => 'America/Sao_Paulo',    'PA' => 'America/Belem',   
'PB' => 'America/Fortaleza',    'PI' => 'America/Fortaleza',
'PE' => 'America/Recife',		'RN' => 'America/Fortaleza',
'RJ' => 'America/Sao_Paulo',    'RO' => 'America/Porto_Velho',
'RS' => 'America/Sao_Paulo',    'SC' => 'America/Sao_Paulo',
'RR' => 'America/Boa_Vista',    'SP' => 'America/Sao_Paulo',
'SE' => 'America/Maceio',       'DF' => 'America/Sao_Paulo', 
'TO' => 'America/Araguaia',     
//'DF' => 'America/Brasilia',
);
/*----------------------------------------------*/
 
$pergunta = "select * from usuarios ";
$resultado = mysqli_query($conexao, $pergunta);
	
while($d = mysqli_fetch_object($resultado)){


	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];		
	
	date_default_timezone_set($timezone);
	
	$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_$d->id") or die("<center>Autenticação 2 invalida</center>");
	

		ChecarBot($conexao2,$d->id,$d->token,$ip,$porta);

}

	
/*FIM DO SCRIPT*/		
		
	usleep(15000000);
		
	}
}



	

function ChecarBot($conexao2,$instancia,$token,$ip,$porta){

		$pergunta = "select * from mov_bot_".date('mY')." where instancia = '".$instancia."' and status_bot = '0' ";
		$resultado = mysqli_query($conexao2, $pergunta);
		 
		//echo $pergunta."<br>";
	
		while($d = mysqli_fetch_object($resultado)){

			list($id_menu,$conteudo,$arquivo,$ext_arquivo) = mysqli_fetch_row(mysqli_query($conexao2, "select id_menu, conteudo, arquivo, ext_arquivo from bot_whats_menu_resposta where instancia = '".$instancia."' and id_menu = '".$d->mensagem."' "));
			
			$de_quem = explode("@",$d->de_quem);
				//echo $conteudo. " e ".$de_quem[0]."  id: $d->id <hr>";

			$numeros = array();
			
			if($arquivo){
				EnviarWhatsappArquivo($conexao2,$token,$de_quem[0],$conteudo,$d->id,$arquivo,$ext_arquivo,$ip,$porta);
			}else{
				if($id_menu){
					EnviarWhatsapp($conexao2,$token,$de_quem[0],$conteudo,$d->id,$ip,$porta);		
				}
			}
			

			
		}


}	




function EnviarWhatsapp($conexao2,$token,$whatsapp,$mensagem,$id,$ip,$porta){


	$numeros[] = $whatsapp;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagem. "\n\n@elmenoufi" ,
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

	if($result){
		mysqli_query($conexao2, "update mov_bot_".date('mY')." set status_bot = '1' where id = '$id' ");
	}
	
	//echo $result."<br><br>";
	
}



function EnviarWhatsappArquivo($conexao2,$token,$whatsapp,$mensagem,$id,$arquivo,$ext_arquivo,$ip,$porta){
	
if($ext_arquivo=="pdf" or $ext_arquivo=="PDF"){
	$Extensao = "application/pdf"; 
	$send = "pdf";
	$tipo = "pdf";
}else{
	$Extensao = "image/".$ext_arquivo;
	$send = "image";
	$tipo = "img";
}	

	
//echo $whatsapp .", ". $Extensao.", ".$send.", ".$tipo;	
	
$cfile = curl_file_create('/home/elmenoufi/public_html/business/tabelas/atendimento/upload/'.$arquivo,$Extensao);
	
	
$numeros[] = $whatsapp;	

$authorization = "Bearer $token";
	
$fields = array
(
	$tipo => $cfile,
	'numbers' => implode(",",$numeros),
	'mensagem' => $mensagem. "\n\n@elmenoufi"
);

$headers = array
(
'Content-Type: multipart/form-data',
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/'.$send );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields );
curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
$result = curl_exec($ch );
curl_close( $ch );	

	if($result){
		mysqli_query($conexao2, "update mov_bot_".date('mY')." set status_bot = '1' where id = '$id' ");
	}	
	
//echo $result."<br><br>";	
	
}
?>