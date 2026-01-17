<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");
 
list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));	

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
);
/*----------------------------------------------*/

list($token) = mysqli_fetch_row(mysqli_query($conexao, "select token from usuarios where id = '1' "));

$pergunta = "select * from usuarios where alert_what = '0' and  token != '' and status_whats_desc = 'CONNECTED' or alert_sms = '0' and  token != '' and status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);


while($d = mysqli_fetch_object($resultado)){
	

	
list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
$timezone = $timezones[$sigla];	

$celular = str_replace("-","",$d->celular);
$celular = explode(" ",$celular);
$celular = $celular[0]."".$celular[1]."".$celular[3];

	
	
	
	
$stsms = (($d->creditos_sms < 150 and $d->alert_sms=='0')?'SIM':'NÃO');
$stwhats = (($d->creditos_msg < 150 and $d->alert_what=='0')?'SIM':'NÃO');	
	
	
if($d->creditos_sms < 150 and $d->alert_sms=='0'){
	$tipo = "SMS Corporativo";
	$campo = "alert_sms";
	EnviarWhatsapp($conexao,$ip,$porta,$d->id,$token,$celular,$d->nome,$tipo,$d->creditos_sms,$campo);
}
if($d->creditos_msg < 150 and $d->alert_what=='0'){
	$tipo = "Whatsapp Business";
	$campo = "alert_what";
	EnviarWhatsapp($conexao,$ip,$porta,$d->id,$token,$celular,$d->nome,$tipo,$d->creditos_msg,$campo);
}	


}


function EnviarWhatsapp($conexao,$ip,$porta,$codigo,$token,$celular,$nome,$tipo,$creditos,$campo){

$Ola = "Olá obrigado por utilizar os nosso serviços!\n";		
$nomeDb = ($nome);
	
$mensagemEnviar = $Ola."\nTudo bem? *".$nomeDb."*\n*Informações:*\nSeu Saldo de $tipo: esta baixo de 150 (envios)\nSeu saldo atual: *$creditos* (envios) " ;		

	
//echo $mensagemEnviar . "<br>";	
	
	
$numeros[] = $celular;	
	
$authorization = "Bearer $token";
	
$fields = array
(
	'mensagem' => $mensagemEnviar ,
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

//echo $result;	
	
	if($result){
		mysqli_query($conexao, "update usuarios set $campo = '1' where id = '".$codigo."' ");
	}

}
?>