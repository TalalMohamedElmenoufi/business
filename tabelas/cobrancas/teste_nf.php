<?php
$keyAsaas = "7317fcb9917ffbb61e9ba01e80c97d32673c1aefb27d486364d77a40d41098e3";

/*	$fields = array
	(
		'object' => 'invoice',
		'id' => 'inv_000000000232',
		'status' => 'SCHEDULED',
		'customer' => 'cus_000000002750',
		'type' => 'NFS-e',
		'statusDescription' => null,
		'serviceDescription' => 'Nota fiscal da Fatura 101940. \nDescrição dos Serviços: ANÁLISE E DESENVOLVIMENTO DE SISTEMAS',
		'pdfUrl' => null,
		'xmlUrl' => null,
		'rpsSerie' => null,
		'rpsNumber' => null,
		'number' => null,
		'validationCode' => null,
		'value' => 300,
		'deductions' => 0,
		'effectiveDate' => '2018-07-03',
		'observations' => 'Mensal referente aos trabalhos de Junho.',
		'estimatedTaxesDescription' => '',
		'payment' => 'pay_145059895800',
		'installment' => null,
			'taxes' => 
				array
				(
				'retainIss' => false,
				'iss' => 3,
				'cofins' => 3,
				'csll' => 1,
				'inss' => 0,
				'ir' => 1.5,
				'pis' => 0.65
			    ),
		'municipalServiceId' => null,
		'municipalServiceCode' => '1.01',
		'municipalServiceName' => 'Análise e desenvolvimento de sistemas'
	);


	foreach ($fields as $key => $val ){

		if($key!="taxes"){
			echo $key .": ".$val."<br>"; 
		}

		foreach ($val as $key2 => $val2 ){
				echo $key2 .": ".$val2."<br>"; 
		}					

	}


echo "<br><br>";*/





$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/customerFiscalInfo/municipalOptions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: $keyAsaas"
));

$response = curl_exec($ch);
curl_close($ch);

//var_dump($response);
$obj = json_decode( $response );

foreach ($obj as $key => $val ){

	echo $key .": ".$val."<br>"; 

	
	
}

?>