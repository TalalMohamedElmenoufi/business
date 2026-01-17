<?php

$api_token = "7317fcb9917ffbb61e9ba01e80c97d32673c1aefb27d486364d77a40d41098e3";

$pay = "pay_7091092924007353";
$data = "2023-12-14";
$valor = 86;

//AtualizarCobranca($api_token,$pay,$data,$valor);



    $address = "Rua 85";
    $addressNumber = "531";
    $companyType = "mei";//limited
    $complement = "3ª etapa";
    $cpfCnpj = "29546495000130";
    $email = "jorgemartinsjw@gmail.com";
    $mobilePhone = "85996291420";
    $name = "PAULO JORGE MARTINS 92121535349"; 
    $phone = "11 32300606";
    $postalCode = "60751-060";
    $province = "Fortaleza-ce";

        $account = "183704";
        $accountDigit = "4";
        $accountName = "*Banco C6 S. A*"; //apenas um campo aberto
        $agency = "0001";
        $bank = "336";
        $bankAccountType = "CONTA_CORRENTE";  //CONTA_POUPANCA ou CONTA_CORRENTE
        $cpfCnpj2 = "29546495000130";
        $name2 = "PAULO JORGE MARTINS 92121535349"; //nome pessoa fisica ou juridica conforme a razão social

//sendCadastroAsaas($conexao,$api_token ,$address,$addressNumber,$companyType,$complement,$cpfCnpj,$email,$mobilePhone,$name,$phone,$postalCode,$province,$account,$accountDigit,$accountName,$agency,$bank,$bankAccountType,$cpfCnpj2,$name2 );
 
/*Inicio asaas criar conta vinculada*/
function sendCadastroAsaas($conexao,$api_token ,$address,$addressNumber,$companyType,$complement,$cpfCnpj,$email,$mobilePhone,$name,$phone,$postalCode,$province,$account,$accountDigit,$accountName,$agency,$bank,$bankAccountType,$cpfCnpj2,$name2 ){
	

	
	$fields = array
	(
		'address' => ($address),
		'addressNumber' => $addressNumber,
		'companyType' => $companyType,
		'complement' => $complement,
		'cpfCnpj' => $cpfCnpj,
		'email' => $email,
		'mobilePhone' => $mobilePhone,
		'name' => $name,
		'phone' => $phone,
		'postalCode' => $postalCode,
		'province' => $province,

		'bankAccount' =>
			$fields = array
			([
			'account' => $account,
			'accountDigit' => $accountDigit,	
			'accountName' => $accountName,	
			'agency' => $agency,	
			'bank' => $bank,	
			'bankAccountType' => $bankAccountType,	
			'cpfCnpj' => $cpfCnpj2,	
			'name' => $name2,
			]),
		
	);
	
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/accounts");
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




function AtualizarCobranca($api_token,$pay,$data,$valor){

	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://api.asaas.com/v3/payments/$pay",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "PUT",
	  CURLOPT_POSTFIELDS => json_encode([
		'dueDate' => $data,
		'value' => $valor
	  ]),
	  CURLOPT_HTTPHEADER => [
		"accept: application/json",
		"access_token: ".$api_token,
		"content-type: application/json"
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	}	
	
}




//Retorno
/*string(510) "
{
"object":"account",
"name":"PAULO JORGE MARTINS 92121535349",
"email":"jorgemartinsjw@gmail.com",
"loginEmail":"jorgemartinsjw@gmail.com",
"phone":"1132300606",
"mobilePhone":"85996291420",
"address":"Rua 85",
"addressNumber":"531",
"complement":"3ª etapa",
"province":"Fortaleza-ce",
"postalCode":"60751060",
"cpfCnpj":"29546495000130",
"personType":"JURIDICA",
"companyType":null,
"city":7072,"state":"CE",
"country":"Brasil",
"tradingName":null,
"apiKey":"b5af210e10a3147dcbbc0126e4288dd98cdff9d49339663a206b938bb8d597fd"
}"*/


?>