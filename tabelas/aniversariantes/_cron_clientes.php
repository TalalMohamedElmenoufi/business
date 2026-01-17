<?php
error_reporting(0);
function acentos($palavra){
	$acentos = array(
		 'Ã¡' => 'a', 'Ã ' => 'a', 'Ã£' => 'a', 'Ã¢' => 'a', 'Ã©' => 'e', 'Ãª' => 'e', 'Ã­' => 'i', 'Ã³' => 'o', 'Ã´' => 'o', 'Ãµ' => 'o', 'Ãº' => 'u', 'Ã¼' => 'u', 'Ã§' => 'c', 'Ã?' => 'A', 'Ã€' => 'A', 'Ãƒ' => 'A', 'Ã‚' => 'A', 'Ã‰' => 'E', 'ÃŠ' => 'E', 'Ã?' => 'I', 'Ã“' => 'O', 'Ã”' => 'O', 'Ã•' => 'O', 'Ãš' => 'U', 'Ãœ' => 'U', 'Ã‡' => 'C');
	$acao = strtr($palavra, $acentos);
	return $acao ;
}

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


$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("erro na conexao");


	list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));

$pergunta = "select * from usuarios ";
$resultado = mysqli_query($conexao, $pergunta);


while($d = mysqli_fetch_object($resultado)){

	
	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];		
	
		ListaClientes($conexao,$d->id,$timezone,$ip,$porta,$d->token);

}



function ListaClientes($conexao,$cliente,$timezone,$ip,$porta,$token){
	
	date_default_timezone_set($timezone);	

	$MesAno = intval(date('my',time()));	

	$dataHoje = date('md',time());	

	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("erro na conexao");		

	
	list($idNiver, $Mensagem) = mysqli_fetch_row(mysqli_query($conexao2, "select id, mensagem from mensagem_niver where id = '".$MesAno."' and data_hoje = '".$dataHoje."' "));
	
	
	/*Para o WHATSAPP Cretito atual*/
	list($creditoWhats) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_msg from usuarios where id = '".$cliente."' "));

	
	$dataNiver = date('m-d',time());	
	list($QtEnviosWhats) = mysqli_fetch_row(mysqli_query($conexao, "select count(id) from usuarios where id = '".$cliente."' and data_nascimento like '%$dataNiver%' "));

	$credito_atual_whats = ($creditoWhats - $QtEnviosWhats) ;
	/*---------------------------*/		

	
	if($creditoWhats > $QtEnviosWhats and $QtEnviosWhats > 0){
	
		echo $cliente ." - ". $dataNiver ." - ". $creditoWhats." - ".$credito_atual_whats." - " .$QtEnviosWhats."<br>";	
		
	}
	
}














function SendWhats($conexao2,$timezone,$ip,$porta,$token,$mensagem,$whatsapp){
	

	$tratPerg = explode(" ",$mensagem) ;
	foreach ($tratPerg as $key => $value ) {  
	$tratPerg1 = explode("#",$value) ;
	$parte1 = substr($tratPerg1[1],0, 5);
	$parte2 = substr($tratPerg1[1], -5);
	$Emoji = " '\ ".$parte1." \'".$parte2   ;
	$Emoji = str_replace("'","",$Emoji);
	$Emoji = str_replace(" ","",$Emoji);
	$Emoji = json_decode('"'.$Emoji.'"');		
	$returnPerg[] = ($tratPerg1[0]) .''. (($tratPerg1[1])?$Emoji:'')   ;
	}
	$mensagemEnviar = implode(' ',$returnPerg) ;		

	$numeros[] = $whatsapp;	

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

	//echo $result."<br><br>";	
	
	date_default_timezone_set($timezone);
	$data = date('Y-m-d H:i:s', time() ) ;
	
	mysqli_query($conexao2, " insert into t_".date('mY')."_smgStatuses set mensagem='".($mensagem)."', destination='".$whatsapp."', date = '".$data."', enviado='1' ");
	
}



?>