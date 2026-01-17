<?php
error_reporting(0);
function acentos($palavra){
	$acentos = array(
		 'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C');
	$acao = strtr($palavra, $acentos);
	return $acao ;
}

/*ratamento timezones*/
$timezones = array(
'AC' => 'America/Rio_branco',
'AL' => 'America/Maceio',
'AP' => 'America/Belem',   
'AM' => 'America/Manaus',
'BA' => 'America/Bahia',
'CE' => 'America/Fortaleza',
'ES' => 'America/Sao_Paulo',
'MA' => 'America/Fortaleza',
'GO' => 'America/Sao_Paulo',
'MS' => 'America/Campo_Grande',
'MT' => 'America/Cuiaba',
'PR' => 'America/Sao_Paulo',
'MG' => 'America/Sao_Paulo',
'PA' => 'America/Belem',
'PB' => 'America/Fortaleza',
'PI' => 'America/Fortaleza',
'PE' => 'America/Recife',
'RN' => 'America/Fortaleza',
'RJ' => 'America/Sao_Paulo',
'RO' => 'America/Porto_Velho',
'RS' => 'America/Sao_Paulo',
'SC' => 'America/Sao_Paulo',
'RR' => 'America/Boa_Vista',
'SP' => 'America/Sao_Paulo',
'SE' => 'America/Maceio',
'DF' => 'America/Sao_Paulo',
'TO' => 'America/Araguaia'
);
/*----------------------------------------------*/


$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("erro na conexao");

$pergunta = "select * from usuarios ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){
	
	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];		
	
	dispararSms( $d->id,$timezone );
	date_default_timezone_set($timezone);
}



function dispararSms($cliente,$timezone){

	$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("erro na conexao");		

	date_default_timezone_set($timezone);

	
	$smsStatuses = "CREATE TABLE `t_".date('mY')."_smsStatuses` (
	`codigo` int(20) NOT NULL AUTO_INCREMENT,
	`id_grupo` int(11) NOT NULL,
	`id` varchar(40) NOT NULL,
	`id_lote` varchar(40) NOT NULL,
	`correlationId` varchar(25) NOT NULL,
	`carrierId` int(11) NOT NULL,
	`carrierName` varchar(30) NOT NULL,
	`destination` varchar(15) NOT NULL,
	`sentStatusCode` int(11) NOT NULL,
	`sentStatus` varchar(30) NOT NULL,
	`sentDate` datetime NOT NULL,
	`sentAt` int(11) NOT NULL,
	`deliveredStatusCode` varchar(20) NOT NULL,
	`deliveredStatus` varchar(30) NOT NULL,
	`deliveredDate` datetime NOT NULL,
	`deliveredAt` varchar(30) NOT NULL,
	`updatedDate` datetime NOT NULL,
	`updatedAt` varchar(30) NOT NULL,
	`mes_ano` varchar(10) NOT NULL,
	PRIMARY KEY (codigo)
	)  ";
	$rsmsStatuses = mysqli_query($conexao2,$smsStatuses);	
	
	
	
	$pergunta2 = "select * from t_".date('Y')."_smsAgendamento where status = '0' ";
	$resultado2 = mysqli_query($conexao2, $pergunta2);


	while($d = mysqli_fetch_object($resultado2)){

		
		$data1 = $d->data ;
		$data2 = date( "Y-m-d H:i:s", time() ) ;

		//echo "Data1 ".$data1."<br>";
		//echo "Data2 ".$data2."<br>";	
		
		// Comparando as Datas
		if(strtotime($data1) > strtotime($data2))
		{
		echo 'A data 1 é maior que a data 2.';
		}
		elseif(strtotime($data1) == strtotime($data2))
		{
		echo 'A data 1 é igual a data 2.';
		EnviarSMS($conexao2,$d->id,$d->data,$d->grupos,($d->mensagem),$timezone);
		}
		else
		{
		echo 'A data 1 é menor a data 2.';
		EnviarSMS($conexao2,$d->id,$d->data,$d->grupos,($d->mensagem),$timezone);
		}		

	}

}







function EnviarSMS($conexao2,$codigo,$data,$grupos,$mensagem,$timezone){
	
		date_default_timezone_set($timezone);
	
		echo $grupos."<br>";
	
		$categoria = $grupos;
	
		$pergunta = "select * from cadastro where categoria in (".$categoria.") and situacao = '0' and tel_tipo = 'Invalido' ";
		$resultado = mysqli_query($conexao2, $pergunta);

		
	
		while($d = mysqli_fetch_object($resultado)){
			
			$Tnome = ($d->nome);

			$nome = explode(' ',$Tnome);
			$celular = $d->cod_pais.' ('.$d->cod_estado.') '.$d->telefone;
			$mensagem_tratada = str_replace('[nome]',$nome[0],$mensagem);
			$mensagem_enviar  = str_replace('[celular]',$celular,$mensagem_tratada);


			$cells = $d->cod_pais.''.$d->cod_estado.''.$d->telefone;
			$correlationId = "tme_".date( "YmdHis", time() )  ;

			//echo $cells . " | " . ($mensagem_enviar) .  "<br>";
			
			$cart_details[] = array(
			"destination" => $cells,
			"correlationId" => $correlationId,
			"messageText"=> $mensagem_enviar
			);
			
			
		}

	/*$data = array
	(
	'messages' => $cart_details,

		'defaultValues' =>
			$data2 = array
			(
			'messageText' => $mensagem_enviar
			)

	);
	*/

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

					$query = "insert into t_".date('mY')."_smsErros set id_grupo= '$codigo', '$e->errorCode', errorCode= '$e->errorCode', errorMessage= '$e->errorMessage', mes_ano= '".date('mY')."'	";

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

					$query = "insert into t_".date('mY')."_smsStatuses set id_grupo= '$codigo', id_lote= '$idLote',  id= '$e->id', correlationId= '$e->correlationId', mes_ano= '".date('mY')."'	";
					$result = mysqli_query($conexao2,$query);			

					//echo $query;

				}
			   //Fim o Envio da mensagem	

				if($idLote){
					mysqli_query($conexao2,"update t_".date('Y')."_smsAgendamento set status='1' where id='".$codigo."' ");
				}else{
					mysqli_query($conexao2,"replace eventos_sms set id='".$codigo."', evento='".$response."',   id_grupo='".$codigo."' ");
				}	


	}	
	
	
	


}