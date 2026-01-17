<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida 1</center>");

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
	
	echo $d->id."<br>";
	
	dispararSms($conexao, $d->id,$timezone,$d->token, $d->status_whats_desc, $ip,$porta );

}


function dispararSms($conexao, $cliente,$timezone,$token, $status_whats_desc, $ip,$porta){

$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("<center>Autenticação invalida 2!</center>");			
	
		
	date_default_timezone_set($timezone);

	$pergunta2 = "select * from agenda where status = '0' ";
	$resultado2 = mysqli_query($conexao2, $pergunta2);


	while($d = mysqli_fetch_object($resultado2)){

		
		$data1 = $d->data.' '.$d->hora ;
		$data2 = date( "Y-m-d H:i:s" );

		echo "Data1 ".$data1."<br>";
		echo "Data2 ".$data2."<br>";	
		
		if(strtotime($data1) > strtotime($data2))
		{
		echo 'A data 1 é maior que a data 2.<br>';
		}
		elseif(strtotime($data1) == strtotime($data2))
		{
		echo 'A data 1 é igual a data 2.<br>';
		ListaUsuarios($conexao,$conexao2,$d->id,$d->data,$d->compartilhar,($d->lembrete),$timezone,$token, $status_whats_desc, $ip,$porta, $cliente);
		}
		else
		{
		echo 'A data 1 é menor a data 2.<br>';
		ListaUsuarios($conexao,$conexao2,$d->id,$d->data,$d->compartilhar,($d->lembrete),$timezone,$token, $status_whats_desc, $ip,$porta, $cliente);
		}		

	}

}






function ListaUsuarios($conexao,$conexao2,$codigo,$data,$grupos,$mensagem,$timezone,$token,$status_whats_desc, $ip,$porta, $cliente){

		date_default_timezone_set($timezone);
	
		echo "<br> Compartilhar com: ".$grupos."<br>";
	
		$categoria = $grupos;

		list($totalNumeros) = mysqli_fetch_row(mysqli_query($conexao2, "select count(telefone) from contatos_agenda where id in (".$categoria.")  "));
	
		$pergunta = "select * from contatos_agenda where id in (".$categoria.")  ";
		$resultado = mysqli_query($conexao2, $pergunta);
	
		$numeros = "";
	    $w = 0;
		while($d = mysqli_fetch_object($resultado)){
			
			$Nome = ($d->nome);

			$correlationId = "tme_".date( "YmdHis", time() )  ;

			if($d->cod_estado=='92' or $d->cod_estado=='85' or $d->cod_estado=='82'){
				$tira9 = substr($d->telefone, 1);
				$cells = $d->cod_pais.''.$d->cod_estado.''.$tira9;
			}else{
				$tira9 = substr($d->telefone, 1);
				$cells = $d->cod_pais.''.$d->cod_estado.''.$tira9;
			}

			echo "<br> $status_whats_desc : | $Nome | ".$cells." | ".$mensagem."<br>";
		    $w++;

			if($status_whats_desc == "TIMEOUT"){
				timeout($conexao,$cliente,$status_whats_desc,$ip,$porta);
			}
			elseif($status_whats_desc == "CONNECTED"){
				EnviarWhatsapp($conexao,$conexao2, $codigo, $token, ($mensagem) ,$cells, $w, $totalNumeros, $ip,$porta );	
			}
			
			
		}
	
}



function timeout($conexao,$cliente,$status_whats_desc,$ip,$porta){
	
	echo "status: $status_whats_desc <br>";	
}



function EnviarWhatsapp($conexao,$conexao2,$codigo,$token,$mensagem,$whatsapp,$w,$totalNumeros, $ip,$porta ){
	

$numeros[] = $whatsapp;	
	
$authorization = "Bearer $token";
	
$fields = array
(
	'mensagem' => $mensagem ."\n\n@elmenoufi",
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
	
echo $result."<br><br>";
	
echo "Codigo=".$codigo ." e Total=". $totalNumeros ." e da Vez=". $w." e ".$ip.":".$porta."<br>";

if($w >= $totalNumeros){
	mysqli_query($conexao2, " update agenda set status='1' where id='".$codigo."' ");
}
	
	
}