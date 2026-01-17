<?php
error_reporting(0);
function acentos($palavra){
	$acentos = array(
		 'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c', '�?' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'É' => 'E', 'Ê' => 'E', '�?' => 'I', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C');
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


list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));

$pergunta = "select * from usuarios ";
$resultado = mysqli_query($conexao, $pergunta);


while($d = mysqli_fetch_object($resultado)){

	
	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];		
	
		dispararSms($conexao,$d->id,$d->aniversariantes,$d->aniversariantes_whats,$timezone,$ip,$porta,$d->token);

}

function dispararSms($conexao,$cliente,$aniversariantes,$aniversariantes_whats,$timezone,$ip,$porta,$token){

date_default_timezone_set($timezone);	
	
$MesAno = intval(date('my',time()));	
	
$dataHoje = date('md',time());	
	
	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("erro na conexao");		
	
	$pergunta2 = "select * from mensagem_niver where id = '".$MesAno."' and data_hoje = '".$dataHoje."'  ";
	$resultado2 = mysqli_query($conexao2, $pergunta2);
	
	//echo "Cliente ".$cliente."<br>"; 
	//echo $pergunta2."<br><br>"; 
    
	while($d = mysqli_fetch_object($resultado2)){
		
		$dataHoje = date('m-d',time());

		/*Para o SMS Cretito atual*/
		list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_sms from usuarios where id = '".$cliente."' "));

		list($QtEnvios) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where data_nascimento like '%$dataHoje%' and tel_tipo = 'Invalido' or data_nascimento like '%$dataHoje%' and tel_tipo = 'WhatsApp' "));

		$credito_atual = ($creditoSms - $QtEnvios) ;
		/*---------------------------*/
		
		/*Para o WHATSAPP Cretito atual*/
		list($creditoWhats) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_msg from usuarios where id = '".$cliente."' "));

		list($QtEnviosWhats) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where data_nascimento like '%$dataHoje%' and tel_tipo = 'WhatsApp' "));

		$credito_atual_whats = ($creditoWhats - $QtEnviosWhats) ;
		/*---------------------------*/		
		

		
		//echo "ID=".$d->id." e creditoSms=". $creditoSms ." e QtEnvios=".$QtEnvios . " credito_atual=" .$credito_atual. "<br>";
		
		
		if($creditoSms > $QtEnvios and $QtEnvios > 0  or  $creditoWhats > $QtEnviosWhats and $QtEnviosWhats > 0){
			//echo "<br>ENVIAR CLI=$cliente<br>";
			
			if($aniversariantes=='S' and $aniversariantes_whats=='N'){
				$tipoEnvio = 'so_sms';
				EnviarSMS($conexao2,$d->id,($d->mensagem),$conexao,$credito_atual,$cliente,$timezone,$tipoEnvio);
			}
			elseif($aniversariantes=='N' and $aniversariantes_whats=='S'){
				$tipoEnvio = 'so_whats';
				EnviarWHATS($conexao2,$d->id,($d->mensagem),$conexao,$credito_atual_whats,$cliente,$timezone,$tipoEnvio,$ip,$porta,$token);
			}
			elseif($aniversariantes=='S' and $aniversariantes_whats=='S'){
				$tipoEnvio = 'sms_whats';
				EnviarSMS($conexao2,$d->id,($d->mensagem),$conexao,$credito_atual,$cliente,$timezone,$tipoEnvio);
			}
			
			//mysqli_query($conexao, "update usuarios set creditos_sms='".$credito_atual."' where codigo = '".$cliente."' ");
            }
		
			
		   else{
			
                    $Hoje = date('d-m-Y',time());

                    $dia = date("d",time()); // dia desejado
                    $mes = date("m",time()); // Mês desejado
                    $ano = date("Y",time()); // Ano atual
                    $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); 

                    if( $ultimo_dia == $dia ){
					
					$MesAnoAnterior = intval(date('my',time()));	
						
                    $MesAno = intval( date('my', strtotime('+1 month', strtotime( $Hoje ))) );

                    $HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));
						
					mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAnoAnterior."', mensagem='".($d->mensagem)."', data_hoje='".$HojeAlt."' ");	
						
					mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', mensagem='".($d->mensagem)."', data_hoje='".$HojeAlt."' ");	
						
                    }else{
                    $MesAno = intval(date('my',time()));

                    $HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));					
					mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', mensagem='".($d->mensagem)."', data_hoje='".$HojeAlt."' ");	
						

                    }                    
                    
                }
		

	}

}







function EnviarSMS($conexao2,$codigo,$mensagem,$conexao,$credito_atual,$cliente,$timezone,$tipoEnvio){
	
		date_default_timezone_set($timezone);
	
		$dataHoje = date('m-d',time()); 
	
		//Invalido e WhatsApp  | so_sms so_whats sms_whats
	    if($tipoEnvio=='so_sms'){
			$filtro = "where data_nascimento like '%$dataHoje%' and tel_tipo = 'Invalido' ";
		}
		elseif($tipoEnvio=='sms_whats'){
			$filtro = "where data_nascimento like '%$dataHoje%' and tel_tipo = 'Invalido' or data_nascimento like '%$dataHoje%' and tel_tipo = 'WhatsApp' ";
		}
		//------------------------------------------------

	
		$pergunta = "select * from cadastro ".$filtro." ";
		$resultado = mysqli_query($conexao2, $pergunta);

		//echo $pergunta;
	
		while($d = mysqli_fetch_object($resultado)){
			
			$Tnome = ($d->nome);

			$nome = explode(' ',$Tnome);
			$celular = $d->cod_pais.' ('.$d->cod_estado.') '.$d->telefone;
			$mensagem_tratada = str_replace('[nome]',$nome[0],$mensagem);
			$mensagem_enviar  = str_replace('[celular]',$celular,$mensagem_tratada);


			$cells = ( $d->cod_pais.''.$d->cod_estado.''.$d->telefone ) ;
			
			$correlationId = "tme_".date('YmdHis',time());

			
			//echo $cells . " | " . ($mensagem_enviar) .  "<br>";
			

			$cart_details[] = array(
			"destination" => $cells,
			"correlationId" => $correlationId,
			"messageText"=> $mensagem_enviar
			);
			
			
		}


//echo "<br>". $mensagem ."<br>";
	

	$data = array
	(
	'messages' => $cart_details
	);


	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api-messaging.movile.com/v1/send-bulk-sms",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => json_encode($data) ,
	  CURLOPT_HTTPHEADER => array(
		"authenticationtoken: 7uuZ1cHg6x_ZiU-_mD8uHn6joXLnQBN6i_z4QD1o",
		"content-type: application/json",
		"username: talal@elmenoufi.com.br"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;


				//Inseri o Erro do envio	
				$json_str = '{"erroSms": '.	'[' . $response . ']}';

				$jsonObjSmsErro = json_decode($json_str);
				$erroSms = $jsonObjSmsErro->erroSms;


				foreach ( $erroSms as $e )
				{
				echo "errorCode: $e->errorCode - errorMessage: $e->errorMessage  <br>"; 

					$query = "insert into t_".date('mY',time())."_smsErros set id_grupo= '$codigo', '$e->errorCode', errorCode= '$e->errorCode', errorMessage= '$e->errorMessage', mes_ano= '".date('mY',time())."'	";

					//echo $query;

				if($e->errorCode>0){
					$result = mysqli_query($conexao2,$query);
				}


				}	
				//Fim o Erro do envio	



				//Inseri o Envio da mensagem	
				$json_envio = $response;

				$jsonObjEnvio = json_decode($json_envio);
				$smsEnvio = $jsonObjEnvio->messages;

				$idLote = $jsonObjEnvio->id;

				echo "<b>Id Lote <span style='color:blue'>".$idLote."</span> </b>";	

				foreach ( $smsEnvio as $e )
				{
					echo "
					id: $e->id
					correlationId: $e->correlationId

					<hr>";

					$query = "insert into t_".date('mY',time())."_smsStatuses set id_grupo= '$codigo', id_lote= '$idLote',  id= '$e->id', correlationId= '$e->correlationId', mes_ano= '".date('mY',time())."'	";
					$result = mysqli_query($conexao2,$query);			

					//echo $query;

				}
			   //Fim o Envio da mensagem	

				if($idLote){
					
					
						
					//------Atualizar mensagem
					list($data) = mysqli_fetch_row(mysqli_query($conexao2, "select max(data) from mensagem_niver  "));

					list($idR,$mensagemAt) = mysqli_fetch_row(mysqli_query($conexao2, "select id, mensagem from mensagem_niver where data = '".$data."'  "));
					//-------------------------						
				
					$Hoje = date('d-m-Y',time());

					$dia = date("d",time()); // dia desejado
					$mes = date("m",time()); // Mês desejado
					$ano = date("Y",time()); // Ano atual
					$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); 

					if( $ultimo_dia == $dia ){
					
					$MesAnoAnterior = intval(date('my',time()));	
						
					$MesAno = intval( date('my', strtotime('+1 month', strtotime( $Hoje ))) );
						
					$HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));		
						
					mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAnoAnterior."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");		
						
					mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");	
						
					}else{
					$MesAno = intval(date('my',time()));
						
					$HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));
						
					mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");	
					}
					
					
					mysqli_query($conexao, "update usuarios set creditos_sms='".$credito_atual."' where id = '".$cliente."' ");

					
				}else{
					mysqli_query($conexao2,"replace eventos_sms set id='".$codigo."', evento='".$response."', id_grupo='".$codigo."' ");
				}	


	}	
	

	echo "SMS<hr>";
}


function EnviarWHATS($conexao2,$codigo,$mensagem,$conexao,$credito_atual,$cliente,$timezone,$tipoEnvio,$ip,$porta,$token){

	date_default_timezone_set($timezone);

	$dataHoje = date('m-d',time());
	
	$pergunta = "select * from cadastro where data_nascimento like '%$dataHoje%' and tel_tipo = 'WhatsApp' ";
	$resultado = mysqli_query($conexao2, $pergunta);

	while($d = mysqli_fetch_object($resultado)){

		$Tnome = ($d->nome);

		$nome = explode(' ',$Tnome);
		$celular = $d->cod_pais.' ('.$d->cod_estado.') '.$d->telefone;
		$mensagem_tratada = str_replace('[nome]',$nome[0],$mensagem);
		$mensagem_enviar  = str_replace('[celular]',$celular,$mensagem_tratada);

		$whatsapp = $d->cod_pais."".$d->cod_estado."". substr($d->telefone, 1) ;
		SendWhats($conexao2,$timezone,$ip,$porta,$token,$mensagem_enviar,$whatsapp);
		
	}	

	
	
	
	//------Atualizar mensagem
	list($data) = mysqli_fetch_row(mysqli_query($conexao2, "select max(data) from mensagem_niver  "));

	list($idR,$mensagemAt) = mysqli_fetch_row(mysqli_query($conexao2, "select id, mensagem from mensagem_niver where data = '".$data."'  "));
	//-------------------------						

	$Hoje = date('d-m-Y',time());

	$dia = date("d",time()); // dia desejado
	$mes = date("m",time()); // Mês desejado
	$ano = date("Y",time()); // Ano atual
	$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); 

	if( $ultimo_dia == $dia ){

	$MesAnoAnterior = intval(date('my',time()));	

	$MesAno = intval( date('my', strtotime('+1 month', strtotime( $Hoje ))) );

	$HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));		

	mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAnoAnterior."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");		

	mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");	

	}else{
	$MesAno = intval(date('my',time()));

	$HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));

	mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");	
	}


	mysqli_query($conexao, "update usuarios set creditos_msg='".$credito_atual."' where id = '".$cliente."' ");		
	
	
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
