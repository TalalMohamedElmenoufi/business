<?php
include("../includes/connect.php");

list($api_token) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id = '1' "));

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.asaas.com/v3/finance/balance",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "access_token: $api_token"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  //echo $response;
	
  $obj = json_decode($response);
  echo "R$ ". number_format($obj->balance,2,",",".") ;	
	
}

?>