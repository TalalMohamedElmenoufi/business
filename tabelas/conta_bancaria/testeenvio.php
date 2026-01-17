<?php
$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_1");

$api_token = '$aact_prod_000MzkwODA2MWY2OGM3MWRlMDU2NWM3MzJlNzZmNGZhZGY6OjgyMTQ0YzYyLTRhMjgtNGJhZC05NTg2LWU3ZjE5ZGU1NGNiNzo6JGFhY2hfZTAyODE4ODQtMzAwNi00MGY2LWEwYWUtMWIzM2QwMzRlNTQ3'; //chave correspondento da conta
$arquivo  = "cnh_jessica.pdf";
$ext_file  = "pdf";
$documentType_limited_doc  = "IDENTIFICATION";
$documentGroupType_limited_doc  = "MEI";

$value = '3800.00';
$code = '104';
$accountName = 'Caixa Econômica Federal Cef';
$ownerName = 'T M ELMENOUFI INFORMATICA LTDA';
$ownerBirthDate = '1979-09-02';
$cpfCnpj = '07342423000139';
$agency = '1549';
$account = '621';
$accountDigit = '4';
$bankAccountType = 'CONTA_CORRENTE';


$id = '29a41dcc-2381-4965-9c2b-97b07b56eaf2';
$data = '2023-06-22';
$tipo = 'BANK_ACCOUNT';



//ListaTransferencias($conexao2,$api_token,$id,$data,$tipo);

//Transferencia($api_token,$value,$code,$accountName,$ownerName,$ownerBirthDate,$cpfCnpj,$agency,$account,$accountDigit,$bankAccountType);

//EviarDoc1($api_token,$arquivo,$ext_file,$documentType_limited_doc,$documentGroupType_limited_doc);

//Status($api_token);
//Status2($api_token);

ListartCobrancas($conexao2,$api_token);

//ReculperaCobranca($conexao2,$api_token);


//ListaClientes($conexao2,$api_token);

//AtualizarCobranca($conexao2,$api_token);

//ListaContas($conexao2,$api_token);

//sendCadastroAsaas($conexao2,$api_token);

//sendContaBanco($conexao2,$api_token);

 
function sendContaBanco($conexao2,$api_token){
	 
	$fields = array
	(
		'accountName' => 'J S DO VALE',
		'thirdPartyAccount' => true,
		'bank' => '033',
		'agency' => '1340',
		'agencyDigit' => '',
		'account' => '13002271',
		'accountDigit' => '6',
		'bankAccountType' => 'CONTA_CORRENTE',
		'name' => 'Jessica Sena do Vale',
		'cpfCnpj' => '04842917000194',
		'responsiblePhone' => '5592984467548',
		'responsibleEmail' => 'gerencia@casadoeletricistatorquato.com.br',	
	);

	$headers = array
	(
	  "Content-Type: application/json",
	  "access_token: $api_token"
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/api/v3/bankAccounts/mainAccount");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	
	$response = curl_exec($ch);
	curl_close($ch);
	
	echo $response ;	
	
	
}




function sendCadastroAsaas($conexao2,$api_token){ //precisa do token conta principal
	
	$fields = array
	(
		'name' => 'Jessica Sena do Vale',
		'email' => 'gerencia@casadoeletricistatorquato.com.br',
		'loginEmail' => 'gerencia@casadoeletricistatorquato.com.br',
		'cpfCnpj' => '04842917000194',
		'companyType' => 'mei',
		'phone' => '5592984467548',
		'mobilePhone' => '5592984467548',
		'address' => 'Avenida Torquato Tapajos',
		'addressNumber' => '7800',
		'complement' => '',
		'province' => 'AM',
		'postalCode' => '69093415',	
	);

	$headers = array
	(
	  "Content-Type: application/json",
	  "access_token: $api_token"
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/api/v3/accounts");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	
	$response = curl_exec($ch);
	curl_close($ch);
	
	echo "R: ".$response ."<br>".json_encode( $fields );	
}


function sendCadastroAsaasX($conexao2,$api_token){
	
	$fields = array
	(
		'address' => 'Avenida Torquato Tapajos',
		'addressNumber' => '7800',
		'companyType' => 'mei',
		'complement' => '',
		'cpfCnpj' => '04842917000194',
		'email' => 'gerencia@casadoeletricistatorquato.com.br',
		'mobilePhone' => '5592984467548',
		'name' => 'Jessica Sena do Vale',
		'phone' => '5592984467548',
		'postalCode' => '69093415',
		'province' => 'AM',

		'bankAccount' =>
			$fields = array
			([
			'account' => '13002271',
			'accountDigit' => '6',	
			'accountName' => 'J S DO VALE',	
			'agency' => '1340',	
			'bank' => '33',	
			'bankAccountType' => 'CONTA_CORRENTE',	
			'cpfCnpj' => '04842917000194',	
			'name' => 'Jessica Sena do Vale',
			])
		
	);

	$headers = array
	(
	  "Content-Type: application/json",
	  "access_token: $api_token"
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/api/v3/accounts");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	
	$response = curl_exec($ch);
	curl_close($ch);
	
	echo "R: ".$response ."<br>".json_encode( $fields );	
}



function ListaContas($conexao2,$api_token){
	
	
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/accounts");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: $api_token"
));

$response = curl_exec($ch);
curl_close($ch);

echo $response;	
	
}






function AtualizarCobranca($conexao2,$api_token){ 
	
$idFatura = "pay_150954110658";	
	
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/api/v3/payments/$idFatura");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

	
	$fields = array(
		'billingType' => 'BOLETO',
		'dueDate' => '2020-10-30',
		'value' => 392.72,
		'description' => 'Hospedagem de 3 dominós e e-mails no valor de R$392.72',
		'externalReference' => 'pay_150954110658',
		'discount' =>
			array(
				'value' => 0,
				'dueDateLimitDays' => 0
			),
		'fine' =>
			array(
				'value' => 0
			),
		'interest' =>
			array(
				'value' => 0
			),
		'postalService' => false
	);	
	
	
curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields );


curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: $api_token"
));

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);	
	
}


function EviarDoc1($api_token,$arquivo,$ext_file,$documentType_limited_doc,$documentGroupType_limited_doc){

	 
	if($ext_file=='pdf' or $ext_file=='PDF'){
		$TipoExt = 'application';
	}else{
		$TipoExt = 'image';
	}

	$cfile = curl_file_create('/var/www/public_html/grupoelmenoufi/business/tabelas/conta_bancaria/upload/'.$arquivo,$TipoExt.'/'.$ext_file);

	$fields = array
	(
		'documentType' => $documentType_limited_doc,
		'documentGroupType' => $documentGroupType_limited_doc,
		'documentFile' => $cfile
	);

	$headers = array
	(
	'Content-Type: multipart/form-data',
	'access_token: ' . $api_token

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://api.asaas.com/api/v2/documents' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields );
	curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
	$result = curl_exec($ch );
	curl_close( $ch );

	echo $result;
}


function Status($api_token){

	$headers = array
	(
	'Content-Type: application/json',
	'access_token: ' . $api_token

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://api.asaas.com/api/v3/myAccount/status/documentation/' );
	curl_setopt( $ch,CURLOPT_GET, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
	$result = curl_exec($ch );
	curl_close( $ch );

	echo $result."<br><br>";	

	
	    $jsonObjStatusErro = json_decode($result);
		$ListaDocsStatusErro = $jsonObjStatusErro->errors;
		foreach ( $ListaDocsStatusErro as $e ){
			
			echo "code: $e->code - description: $e->description <br>"; 
			
		}
		echo "<br>"; 
	
		$json_str = '{"ListaDocsStatus": '.	'[' . $result . ']}';

		$jsonObjStatus = json_decode($json_str);
		$ListaDocsStatus = $jsonObjStatus->ListaDocsStatus;
	
		foreach ( $ListaDocsStatus as $e ){
			
			//echo "status: $e->status - observations: $e->observations <br>"; 
			$stObs = $e->status;
			$Obs = $e->observations;
			foreach ( $e->documents as $d ){
				//echo "status: $d->status - group $d->group - type $d->type <br>"; 
				$stDoc = $d->status ;
				$Grupo = $d->group;
				$Tipo = $d->type;
					foreach ( $d->files as $f ){
						$stArq = $f->status ;
						$Arquivo = $f->name;
						//echo "status: $f->status - group $f->name <br>"; 
					}
			}

			
		}	
		
	    echo "<br>Dados para DB:<br> stObs:$stObs - Obs:$Obs - stDoc:$stDoc - Grupo:$Grupo - Tipo:$Tipo - stArq:$stArq - Arquivo:$Arquivo " ;
	    
}

function Status2($api_token){

	$headers = array
	(
	'Content-Type: application/json',
	'access_token: ' . $api_token

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://api.asaas.com/api/v3/myAccount/status/' );
	curl_setopt( $ch,CURLOPT_GET, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
	$result = curl_exec($ch );
	curl_close( $ch );

	echo $result."<br><br>";	

		$json_str = '{"ListaDocsStatus": '.	'[' . $result . ']}';

		$jsonObjStatus = json_decode($json_str);
		$ListaDocsStatus = $jsonObjStatus->ListaDocsStatus;
	
		foreach ( $ListaDocsStatus as $e ){
			
			echo "commercialInfo: $e->commercialInfo - bankAccountInfo: $e->bankAccountInfo - documentation: $e->documentation - general: $e->general <br>"; 
			
		}	

	    
}



function Transferencia($api_token,$value,$code,$accountName,$ownerName,$ownerBirthDate,$cpfCnpj,$agency,$account,$accountDigit,$bankAccountType){

	$fields = array(
		'value' => $value,
		'bankAccount' =>
			array(
				'bank' =>
				array(
					'code' => $code
				),
			'accountName' => $accountName,
			'ownerName' => $ownerName,
			'ownerBirthDate' => $ownerBirthDate,
			'cpfCnpj' => $cpfCnpj,
			'agency' => $agency,
			'account' => $account,
			'accountDigit' => $accountDigit,
			'bankAccountType' => $bankAccountType
			)
	);	
	
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: ' . $api_token

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://api.asaas.com/api/v3/transfers' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
	$result = curl_exec($ch );
	curl_close( $ch );
	
	//echo $result."<br>";

	$partesX = explode(":{",$result);
	$partesY = explode("},",$partesX[2]);
	
	$parte1 = str_replace('"bankAccount"','',$partesX[0]) ;
	$parte2 = $partesY[0].",";
	$parte3 = $partesY[1].",";
	$parte4 = $partesY[2];
	
	$unir = $parte1."".$parte2."".$parte3."".$parte4;

	//echo $unir . "<hr>";

	$json_str = '{"ListaStatus": '.	'[' . $unir . ']}';
	$jsonObjStatus = json_decode($json_str);
	$ListaStatus = $jsonObjStatus->ListaStatus;		
		foreach ( $ListaStatus as $e ){

			echo "id: $e->id - dateCreated: $e->dateCreated  - status: $e->status - effectiveDate: $e->effectiveDate - type: $e->type - value: $e->value - netValue: $e->netValue - transferFee: $e->transferFee - scheduleDate: $e->scheduleDate - authorized: $e->authorized  - code: $e->code - name: $e->name - accountName: $e->accountName - ownerName: $e->ownerName - cpfCnpj: $e->cpfCnpj - agency: $e->agency - agencyDigit: $e->agencyDigit - account: $e->account - accountDigit: $e->accountDigit - transactionReceiptUrl: $e->transactionReceiptUrl   <br><br>";  
			
			
		}	
	
	
	
}


function ListaTransferencias($conexao2,$api_token,$id,$data,$tipo){
	

	$headers = array
	(
	'Content-Type: application/json',
	'access_token: ' . $api_token

	);	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/api/v3/transfers?dateCreated=$data&type=$tipo");
	//curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/api/v3/transfers?id=$id");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);
	
	//echo $response ."<br><br><hr>";

	
	
	$jsonObjStatus = json_decode($response);
	$ListaStatus = $jsonObjStatus->data;		
	foreach ( $ListaStatus as $e ){

		$code = json_encode($e->bankAccount->bank->code) ;		 
		$name = json_encode($e->bankAccount->bank->name) ;		 
		$accountName = json_encode($e->bankAccount->accountName) ;
		$ownerName = json_encode($e->bankAccount->ownerName) ;
		$cpfCnpj = json_encode($e->bankAccount->cpfCnpj) ;
		$agency = json_encode($e->bankAccount->agency) ;
		$agencyDigit = json_encode($e->bankAccount->agencyDigit) ;
		$account = json_encode($e->bankAccount->account) ;
		$accountDigit = json_encode($e->bankAccount->accountDigit) ;		
		 
		
			$retorno = "insert into minhas_transferencias set
				id = '".$e->id."',
				dateCreated = '".$e->dateCreated."',
				status = '".$e->status."',
				effectiveDate = '".$e->effectiveDate."',
				type = '".$e->type."',
				value = '".$e->value."',
				netValue = '".$e->netValue."',
				transferFee = '".$e->transferFee."',
				scheduleDate = '".$data."',
				authorized = '".$e->authorized."',
				code = '".$code."',
				name = '".($name)."',
				accountName = '".($accountName)."',
				ownerName = '".($ownerName)."',
				cpfCnpj = '".$cpfCnpj."',
				agency = '".$agency."',
				agencyDigit = '".$agencyDigit."',
				account = '".$account."',
				accountDigit = '".$accountDigit."',
				transactionReceiptUrl = '".$e->transactionReceiptUrl."'";
				//mysqli_query($conexao2,$retorno);
			
		
			
		

		 

		   
		
		echo "id: $e->id - dateCreated: $e->dateCreated  - status: $e->status - effectiveDate: $e->effectiveDate - type: $e->type - value: $e->value - netValue: $e->netValue - transferFee: $e->transferFee - scheduleDate: $data - authorized: $e->authorized  - code: $code - name: $name - accountName: $accountName - ownerName: $ownerName - cpfCnpj: $cpfCnpj - agency: $agency - agencyDigit: $agencyDigit - account: $account - accountDigit: $accountDigit - transactionReceiptUrl: $e->transactionReceiptUrl   <br><br>"; 

	}
	
	

}





echo "<br><br>";


function ListartCobrancas($conexao2,$api_token){
	
	$pay_id = "cus_000046476642";
	
	//payments?customer=&billingType=&status=&subscription=&installment=&externalReference=&paymentDate=&anticipated=&paymentDate%5Bge%5D=&paymentDate%5Ble%5D=&dueDate%5Bge%5D=&dueDate%5Ble%5D=&offset=&limit=
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments?dateCreated%5Bge%5D=2025-09-01&dateCreated%5Ble%5D=2025-09-30");
	//curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/api/v3/payments/$pay_id");
	//curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/api/v3/payments?customer=$pay_id&limit=1");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json",
	  "access_token: $api_token"
	));

	$response = curl_exec($ch);
	curl_close($ch);

	
	//var_dump($response);
	
	
	
	$jsonObjStatus = json_decode($response);
	$ListaDocsStatus = $jsonObjStatus->data;

	if($ListaDocsStatus){
	
		foreach ( $ListaDocsStatus as $e ){	

			echo "P1: ".$e->id . " dateCreated: ". $e->dateCreated." - $e->customer - $e->value<br>";
			
			$query = "insert into cobrancas set 
			object = '".$e->object."',
			id = '".$e->id."',
			dateCreated='".$e->dateCreated."',
			customer='".$e->customer."',
			value='".$e->value."',
			netValue='".$e->netValue."',
			originalValue='".$e->originalValue."',
			interestValue='".$e->interestValue."',
			description='".$e->description."',
			billingType='".$e->billingType."',
			status='".$e->status."',
			dueDate='".$e->dueDate."',
			originalDueDate='".$e->originalDueDate."',
			paymentDate='".$e->paymentDate."',
			clientPaymentDate='".$e->clientPaymentDate."',
			invoiceUrl='".$e->invoiceUrl."',
			invoiceNumber='".$e->invoiceNumber."',
			externalReference='".$e->externalReference."',
			deleted='".$e->deleted."',
			anticipated='".$e->anticipated."',
			creditDate='".$e->creditDate."',
			estimatedCreditDate='".$e->estimatedCreditDate."',
			bankSlipUrl='".$e->bankSlipUrl."',
			lastInvoiceViewedDate='".$e->lastInvoiceViewedDate."',
			lastBankSlipViewedDate='".$e->lastBankSlipViewedDate."'
			";
			//$result = mysqli_query($conexao2, $query);		

		}
		
	}else{
		
		$e = json_decode($response);
		
		echo "P2 ".$e->id . " dateCreated: ". $e->dateCreated."<br>";
		
			$query = "insert into cobrancas set 
			object = '".$e->object."',
			id = '".$e->id."',
			dateCreated='".$e->dateCreated."',
			customer='".$e->customer."',
			value='".$e->value."',
			netValue='".$e->netValue."',
			originalValue='".$e->originalValue."',
			interestValue='".$e->interestValue."',
			description='".$e->description."',
			billingType='".$e->billingType."',
			status='".$e->status."',
			dueDate='".$e->dueDate."',
			originalDueDate='".$e->originalDueDate."',
			paymentDate='".$e->paymentDate."',
			clientPaymentDate='".$e->clientPaymentDate."',
			invoiceUrl='".$e->invoiceUrl."',
			invoiceNumber='".$e->invoiceNumber."',
			externalReference='".$e->externalReference."',
			deleted='".$e->deleted."',
			anticipated='".$e->anticipated."',
			creditDate='".$e->creditDate."',
			estimatedCreditDate='".$e->estimatedCreditDate."',
			bankSlipUrl='".$e->bankSlipUrl."',
			lastInvoiceViewedDate='".$e->lastInvoiceViewedDate."',
			lastBankSlipViewedDate='".$e->lastBankSlipViewedDate."'
			";
			//$result = mysqli_query($conexao2, $query);	
		
	}
	
}
	




function ReculperaCobranca($conexao2,$api_token){
	
$pay_id = "cus_000014971307";	
	
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/api/v3/payments/$pay_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: $api_token"
));

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);	
	
}



function ListaClientes($conexao2,$api_token){
	
	$ch = curl_init();

	//curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/customers?limit=100&offset=0");
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/customers?cpfCnpj=51626757000126");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
  
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "access_token: $api_token"
	));

	$response = curl_exec($ch);
	curl_close($ch);

	var_dump($response)."<br><br>";
	
	$jsonObjStatus = json_decode($response);
	$ListaDocsStatus = $jsonObjStatus->data;
	
	if($ListaDocsStatus){

		$i = 1;
		foreach ( $ListaDocsStatus as $e ){

			$address = str_replace("'","`",$e->address);
			$province = str_replace("'","`",$e->province);
			
			
			$id = explode("_",$e->id);

			list($id_asaas) = mysqli_fetch_row(mysqli_query($conexao2, "select id_asaas from clientes where id_asaas = '".$e->id."' "));

			if($id_asaas != $e->id){
			
				//echo "I: $i -  id: $e->id - dateCreated: $e->dateCreated - name: $e->name - email: $e->email  - company: $e->company - phone: $e->phone - mobilePhone: $e->mobilePhone - address: $address  - addressNumber: $e->addressNumber - complement: $e->complement  - province: $province - postalCode: $e->postalCode - cpfCnpj: $e->cpfCnpj - personType: $e->personType - deleted: $e->deleted  - additionalEmails: $e->additionalEmails - externalReference: $e->externalReference - notificationDisabled: $e->notificationDisabled  - observations: $e->observations - city: $e->city - state: $e->state - country: $e->country  - foreignCustomer: $e->foreignCustomer <br><br>"; 	
				
			$query = "replace into clientes set 
			
			id = '".$id[1]."',
			id_asaas = '".$e->id."',
			data_cadastro = '".$e->dateCreated."',
			nome='".($e->name)."',
			email='email_repetido_".$id[1]."',
			emails='".$e->email."',
			tipo_pessoa='".$e->personType."',
			cpf_cnpj='".$e->cpfCnpj."',
			celular='rep_".$id[1]."',
			celulares='55".$e->mobilePhone."',
			estado='".$e->state."',
			cidade='".$e->city."',
			cep='".$e->postalCode."',
			endereco='".($address)."',
			numero='".$e->addressNumber."',
			bairro='".($province)."',
			complemento='".($e->complement)."',
			ano_mes='".date('Y-m')."'
			";
			//$result = mysqli_query($conexao2, $query);

				
			}else{
			
			$query = "replace into clientes set 
			
			id = '".$id[1]."',
			id_asaas = '".$e->id."',
			data_cadastro = '".$e->dateCreated."',
			nome='".($e->name)."',
			email='".$e->email."',
			tipo_pessoa='".$e->personType."',
			cpf_cnpj='".$e->cpfCnpj."',
			celular='55".$e->mobilePhone."',
			estado='".$e->state."',
			cidade='".$e->city."',
			cep='".$e->postalCode."',
			endereco='".($address)."',
			numero='".$e->addressNumber."',
			bairro='".($province)."',
			complemento='".($e->complement)."',
			ano_mes='".date('Y-m')."'
			";
			//$result = mysqli_query($conexao2, $query);
				
			}


			
			
			
	
			
		
			$i++;
		}			
		
		
	}else{
		
		$e = json_decode($response);
		
			echo "id: $e->id - dateCreated: $e->dateCreated - name: $e->name - email: $e->email  - company: $e->company - phone: $e->phone - mobilePhone: $e->mobilePhone - address: $e->address  - addressNumber: $e->addressNumber - complement: $e->complement  - province: $e->province - postalCode: $e->postalCode - cpfCnpj: $e->cpfCnpj - personType: $e->personType - deleted: $e->deleted  - additionalEmails: $e->additionalEmails - externalReference: $e->externalReference - notificationDisabled: $e->notificationDisabled  - observations: $e->observations - city: $e->city - state: $e->state - country: $e->country  - foreignCustomer: $e->foreignCustomer <br>"; 
		
			$query = "insert into clientes set 
			id_asaas = '".$e->id."',
			data_cadastro = '".$e->dateCreated."',
			nome='".($e->name)."',
			email='".$e->email."',
			tipo_pessoa='".$e->personType."',
			cpf_cnpj='".$e->cpfCnpj."',
			celular='55".$e->mobilePhone."',
			estado='".$e->state."',
			cidade='".$e->city."',
			cep='".$e->postalCode."',
			endereco='".($e->address)."',
			numero='".$e->addressNumber."',
			bairro='".($e->province)."',
			complemento='".($e->complement)."',
			ano_mes='".date('Y-m')."'
			";
			//$result = mysqli_query($conexao2, $query);		
		
	}
	
	

}




//Retorno DOC
//{"errors":[{"code":"invalid_action","description":"Seus documentos ainda não foram analisados."}]}

//AO ENVIAR O DOC
/*{"object":"customerDocumentFile","id":455234,"status":"PENDING","editAllowed":true,"lastAnalysisDate":null,"lastVersion":{"object":"customerDocumentFileVersion","id":463965,"file":{"publicId":"KCGnSxLxriZL6RxsZkkSZXjKu69XRRBICEcrEz7yvfg0qvbmTIBCD2fxRmVEYJng","originalName":"20200819173401_contrato-social.pdf","size":1861225,"extension":"pdf","previewUrl":"/file/preview/KCGnSxLxriZL6RxsZkkSZXjKu69XRRBICEcrEz7yvfg0qvbmTIBCD2fxRmVEYJng","downloadUrl":"/file/downloadDocument/KCGnSxLxriZL6RxsZkkSZXjKu69XRRBICEcrEz7yvfg0qvbmTIBCD2fxRmVEYJng"}},"isFirstDocumentSent":false}*/
?>


