<?php
$api_token = "7317fcb9917ffbb61e9ba01e80c97d32673c1aefb27d486364d77a40d41098e3";
$codPlano = 1;	
$id_asaas = "cus_000014856396";
$billingType = "BOLETO";
$dueDate = "2020-07-29"; //Data de vencimento da cobrança
$value = 18.34; //Valor da cobrança
$description = "Credito WhatsApp"; //Descrição da cobrança
$externalReference = "x"; //Campo livre para busca
$discountValue = 0; //Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
$dueDateLimitDays = 0; //Dias antes do vencimento para aplicar desconto. Ex: 0 = até o vencimento, 1 = até um dia antes, 2 = até dois dias antes, e assim por diante
$fineValue = 1; //Percentual de multa sobre o valor da cobrança para pagamento após o vencimento
$interestValue = 1; //Percentual de juros ao mês sobre o valor da cobrança para pagamento após o vencimento	
	

$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_1");

//GerarFatura($conexao2,$api_token,$codPlano,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue);
	
 
//ListaCliente($conexao2,$api_token);
function ListaCliente($conexao2,$api_token){
	
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/customers/cus_000014856396");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: $api_token"
));

$response = curl_exec($ch);
curl_close($ch);

//var_dump($response);	
	$obj = json_decode($response);
	
	
	foreach($obj as $key => $value) {
		$CamposInsert = " $key = '". $value ."' " ;
		echo $CamposInsert."<br>";
		$CamposInsertDb[] = $CamposInsert ;
	}
	InserirDados($CamposInsertDb);

	
}




	
function GerarFatura($conexao2,$api_token,$codPlano,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue){

	$fields = array
	(
		'customer' => $id_asaas,
		'billingType' => $billingType,
		'dueDate' => $dueDate,
		'value' => $value,
		'description' => $description,
		'externalReference' => $externalReference,
		'discount' =>
			$fields = array
			(
			'value' => $discountValue,
			'dueDateLimitDays' => $dueDateLimitDays,	
			),
		'fine' =>
			$fields = array
			(
			'value' => $fineValue,	
			),
		'interest' =>
			$fields = array
			(
			'value' => $interestValue,	
			),
		'postalService' => false,
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) ); 	
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);	

	
	$obj = json_decode($response);
	echo $obj->object . "<br>";
	echo $obj->bankSlipUrl . "<br>";
	
	InsertDb();
    //var_dump($response);



}
function InsertDb(){
	echo "<br>OK";
}


/*string(833) "{"object":"payment","id":"pay_361505628701","dateCreated":"2020-07-27","customer":"cus_000014856396","value":30.65,"netValue":29.66,"originalValue":null,"interestValue":null,"description":"Credito WhatsApp","billingType":"BOLETO","status":"PENDING","dueDate":"2020-07-29","originalDueDate":"2020-07-29","paymentDate":null,"clientPaymentDate":null,"invoiceUrl":"https://api.asaas.com/i/361505628701","invoiceNumber":"34762822","externalReference":null,"deleted":false,"anticipated":false,"creditDate":null,"estimatedCreditDate":null,"bankSlipUrl":"https://api.asaas.com/b/pdf/361505628701","lastInvoiceViewedDate":null,"lastBankSlipViewedDate":null,"discount":{"value":0,"limitDate":null,"dueDateLimitDays":0,"type":"PERCENTAGE"},"fine":{"value":0,"type":"PERCENTAGE"},"interest":{"value":0,"type":"PERCENTAGE"},"postalService":false}"*/




$payId = "pay_361505628701";
//RemoverCobranca($api_token,$payId);

function RemoverCobranca($api_token,$payId){
	
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments/$payId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: $api_token"
));

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);	
	
}

/*string(40) "{"deleted":true,"id":"pay_361505628701"}"*/




	

//inserit cliente



$cidade = "Manaus";
$estado = "Amazonas";

$email = "texte@empresa.com";
$name = "Conta teste";
$notes = "Business Corporativo";
$phone = "5592991725319";
$phone_prefix = "92";
$cpf_cnpj = "64255352291";
$cc_emails = "";
$zip_code = "69028200";
$number = "10";
$street = "";
$city = $cidade;
$state = $estado;
$district = $_POST['bairro'];
$complement = "";	


//----------------------------------			

//sendCadastroAsaas($conexao,$api_token ,$email,$name,$notes,$phone,$phone_prefix,$cpf_cnpj,$cc_emails,$zip_code,$number,$street,$city,$state,$district,$complement);	


/*Inicio asaas*/
function sendCadastroAsaas($conexao,$api_token ,$email,$name,$notes,$phone,$phone_prefix,$cpf_cnpj,$cc_emails,$zip_code,$number,$street,$city,$state,$district,$complement){
	
	
	$fields = array
	(
		'name' => ($name),
		'email' => $email,
		'phone' => $phone,
		'mobilePhone' => '',
		'cpfCnpj' => $cpf_cnpj,
		'postalCode' => $zip_code,
		'address' => ($street),
		'addressNumber' => $number,
		'complement' => ($complement),
		'province' => ($district),
		'externalReference' => '',
		'notificationDisabled' => false,
		'additionalEmails' => $cc_emails,
		'municipalInscription' => '',
		'stateInscription' => '',
		'observations' => $notes		
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/customers");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);

var_dump($response);	
}
/*Fim asaas*/




?>





