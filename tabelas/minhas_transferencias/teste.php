<?php
$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_1");
$api_token = "7317fcb9917ffbb61e9ba01e80c97d32673c1aefb27d486364d77a40d41098e3";


$id = '29a41dcc-2381-4965-9c2b-97b07b56eaf2';
$data = '2020-08-18';
$tipo = 'BANK_ACCOUNT';

ListaTransferencias($api_token,$id,$data,$tipo);


function ListaTransferencias($api_token,$id,$data,$tipo){
	
	//$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_1");
	
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: ' . $api_token

	);	
	
	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/transfers?dateCreated=$data&type=$tipo");
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/transfers?id=$id");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);
	
	echo $response ."<br><br><hr>";

	$partesX = explode(":{",$response);
	$partesY = explode("},",$partesX[2]);
	
	$parte1 = str_replace('"bankAccount"','',$partesX[0]) ;
	$parte2 = $partesY[0].",";
	$parte3 = $partesY[1].",";
	$parte4 = $partesY[2];
	
	$unir = $parte1."".$parte2."".$parte3."".$parte4;

	$json_str = '{"ListaStatus": '.	'[' . $unir . ']}';
	$jsonObjStatus = json_decode($json_str);
	$ListaStatus = $jsonObjStatus->ListaStatus;		
		foreach ( $ListaStatus as $e ){

			/*mysqli_query($conexao2, 
			"
				insert into minhas_transferencias set
				id = '".$e->id."',
				dateCreated = '".$e->dateCreated."',
				status = '".$e->status."',
				effectiveDate = '".$e->effectiveDate."',
				type = '".$e->type."',
				value = '".$e->value."',
				netValue = '".$e->netValue."',
				transferFee = '".$e->transferFee."',
				scheduleDate = '".$e->scheduleDate."',
				authorized = '".$e->authorized."',
				code = '".$e->code."',
				name = '".($e->name)."',
				accountName = '".($e->accountName)."',
				ownerName = '".($e->ownerName)."',
				cpfCnpj = '".$e->cpfCnpj."',
				agency = '".$e->agency."',
				agencyDigit = '".$e->agencyDigit."',
				account = '".$e->account."',
				accountDigit = '".$e->accountDigit."',
				transactionReceiptUrl = '".$e->transactionReceiptUrl."'
			"   
		    );*/
			
			echo "id: $e->id - dateCreated: $e->dateCreated  - status: $e->status - effectiveDate: $e->effectiveDate - type: $e->type - value: $e->value - netValue: $e->netValue - transferFee: $e->transferFee - scheduleDate: $e->scheduleDate - authorized: $e->authorized  - code: $e->code - name: $e->name - accountName: $e->accountName - ownerName: $e->ownerName - cpfCnpj: $e->cpfCnpj - agency: $e->agency - agencyDigit: $e->agencyDigit - account: $e->account - accountDigit: $e->accountDigit - transactionReceiptUrl: $e->transactionReceiptUrl   <br><br>";  
			
			
		}

	
	
}