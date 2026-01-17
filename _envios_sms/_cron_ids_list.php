<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("erro na conexao");

$pergunta = "select * from usuarios ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){
	
	dispararSmsStatuses($d->id);

}



function dispararSmsStatuses($cliente){

	$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("erro na conexao");	

	$pergunta = "select * from t_".date('mY')."_smsStatuses where destination = '' ";
	$resultado = mysqli_query($conexao2, $pergunta);

	while($d = mysqli_fetch_object($resultado)){

		$ids[] = $d->id;

	}



	if($ids){

		$fields = array
		(
		'ids' => $ids
		);

		echo json_encode( $fields ) . "<br><br>";

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api-messaging.movile.com/v1/sms/status/search",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode( $fields ) ,	
		  CURLOPT_HTTPHEADER => array(
			"authenticationtoken: 7uuZ1cHg6x_ZiU-_mD8uHn6joXLnQBN6i_z4QD1o",
			"username: talal@elmenoufi.com.br",
			"content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  //echo $response."<br><br>";

			//string json (array contendo 3 elementos)
			$json_str = $response;

			//faz o parsing da string, criando o array "smsStatuses"
			$jsonObj = json_decode($json_str);
			$smsStatuses = $jsonObj->smsStatuses;

			$total = $jsonObj->total;

			echo "<b>Total <span style='color:blue'>".$total."</span> </b>";	

			//navega pelos elementos do array, imprimindo cada empregado
			foreach ( $smsStatuses as $e )
			{
				echo "
				id: $e->id
				correlationId: $e->correlationId
				carrierId: $e->carrierId
				carrierName: $e->carrierName
				destination: $e->destination
				sentStatusCode: $e->sentStatusCode
				sentStatus: $e->sentStatus
				sentDate: $e->sentDate
				sentAt: $e->sentAt
				deliveredStatusCode: $e->deliveredStatusCode
				deliveredStatus: $e->deliveredStatus
				deliveredDate: $e->deliveredDate
				deliveredAt: $e->deliveredAt
				updatedDate: $e->updatedDate
				updatedAt: $e->updatedAt

				<hr>";


				$query = "update t_".date('mY')."_smsStatuses set  
				carrierId='".$e->carrierId."',
				carrierName='".$e->carrierName."',
				destination='".$e->destination."',
				sentStatusCode='".$e->sentStatusCode."',
				sentStatus='".$e->sentStatus."',
				sentDate='".$e->sentDate."',
				sentAt='".$e->sentAt."',
				deliveredStatusCode='".$e->deliveredStatusCode."',
				deliveredStatus='".$e->deliveredStatus."',
				deliveredDate='".$e->deliveredDate."',
				deliveredAt='".$e->deliveredAt."',
				updatedDate='".$e->updatedDate."',
				updatedAt='".$e->updatedAt."'

				where id='".$e->id."'
				";
				$result = mysqli_query($conexao2, $query);	
				
				if($e->carrierName=='UNKNOWN'){
					mysqli_query($conexao2, "insert into numeros_invalidos set
					id='".$e->id."',
					carrierName='".$e->carrierName."',
					correlationId='".$e->correlationId."',
					destination='".$e->destination."'
					");
					
					$TiraDdarea = substr($e->destination, 4);
					mysqli_query($conexao2, "update cadastro set
					tel_tipo='Celular Erro'
					where telefone='".$TiraDdarea."'
					");
					
				}
				

			}


		}


	}


	
}
?>