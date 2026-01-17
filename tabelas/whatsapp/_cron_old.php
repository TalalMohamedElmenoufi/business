<?php
$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");


list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server   "));

$pergunta = "select * from usuarios ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){

	
	dispararSms($conexao, $d->id,$d->timezone,$d->token, $d->status_whats_desc, $ip,$porta );

}


function dispararSms($conexao, $cliente,$timezone,$token, $status_whats_desc, $ip,$porta){

$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("<center>Autenticação invalida!</center>");			

	
	date_default_timezone_set($timezone);

	$pergunta2 = "select * from t_".date('Y')."_smgAgendamento where status = '0' ";
	$resultado2 = mysqli_query($conexao2, $pergunta2);


	while($d = mysqli_fetch_object($resultado2)){

		
		$data1 =  $d->data ;
		$data2 = date( "Y-m-d H:i:s", time() );

		
	    echo "Timezone ".$timezone."<br>";
		echo "Data1 ".$data1."<br>";
		echo "Data2 ".$data2."<br>";	
		
		if(strtotime($data1) > strtotime($data2))
		{
		echo 'A data 1 é maior que a data 2.';
		}
		elseif(strtotime($data1) == strtotime($data2))
		{
		echo 'A data 1 é igual a data 2.';
		//ListaUsuarios($conexao,$conexao2,$d->id,$d->data,$d->grupos,($d->mensagem),$timezone,$token, $status_whats_desc, $ip,$porta, $cliente);
		}
		else
		{
		echo 'A data 1 é menor a data 2.';
		//ListaUsuarios($conexao,$conexao2,$d->id,$d->data,$d->grupos,($d->mensagem),$timezone,$token, $status_whats_desc, $ip,$porta, $cliente);
		}		

	}

}






function ListaUsuarios($conexao,$conexao2,$codigo,$data,$grupos,$mensagem,$timezone,$token,$status_whats_desc, $ip,$porta, $cliente){

		date_default_timezone_set($timezone);
	
		echo $grupos."<br>";
	
		$categoria = $grupos;

		list($totalNumeros) = mysqli_fetch_row(mysqli_query($conexao2, "select count(telefone) from cadastro where categoria in (".$categoria.") and situacao='0' "));
	
		$pergunta = "select * from cadastro where categoria in (".$categoria.") and situacao='0' ";
		$resultado = mysqli_query($conexao2, $pergunta);

		$numeros = "";
	    $w = 0;
		while($d = mysqli_fetch_object($resultado)){
			
			$Tnome = ($d->nome);

			$nome = explode(' ',$Tnome);
			$celular = $d->cod_pais.' ('.$d->cod_estado.') '.$d->telefone;
			$mensagem_tratada = str_replace('[nome]',$nome[0],$mensagem);
			$mensagem_enviar  = str_replace('[celular]',$celular,$mensagem_tratada);

			$correlationId = "tme_".date( "YmdHis", time() )  ;

			if($d->cod_estado=='92' or $d->cod_estado=='85' or $d->cod_estado=='82'){
				$tira9 = substr($d->telefone, 1);
				$cells = $d->cod_pais.''.$d->cod_estado.''.$tira9;
			}else{
				$cells = $d->cod_pais.''.$d->cod_estado.''.$d->telefone;
			}

			echo "<br> $status_whats_desc | ".$cells." | ".$mensagem_enviar."<br>";
		    $w++;

			if($status_whats_desc == "TIMEOUT"){
				timeout($conexao,$cliente,$status_whats_desc,$ip,$porta);
			}
			elseif($status_whats_desc == "CONNECTED"){
				EnviarWhatsapp($conexao,$conexao2, $codigo, $token, ($mensagem_enviar) ,$cells, $w, $totalNumeros, $ip,$porta );	
			}
			
			
		}
	
}



function timeout($conexao,$cliente,$status_whats_desc,$ip,$porta){
	
	echo "status: $status_whats_desc <br>";	
}




function EnviarWhatsapp($conexao,$conexao2,$codigo,$token,$mensagem,$whatsapp,$w,$totalNumeros, $ip,$porta ){
	
	
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
	
echo $result."<br><br>";
	
//echo json_encode( $fields )."<br><br>" ;
echo "Codigo=".$codigo ." e Total=". $totalNumeros ." e da Vez=". $w." e ".$ip.":".$porta."<br>";

if($w >= $totalNumeros){
	mysqli_query($conexao2, " update t_".date('Y')."_smgAgendamento set status='1' where id='".$codigo."' ");
}
	
	
}