<?php

$curl = curl_init();
 
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.abacatepay.com/v1/customer/create",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'name' => 'Talal Mohamed Elmenoufi',
    'cellphone' => '92991725319',
    'email' => 'talal@elmenoufi.com.br',
    'taxId' => '64255352291'
  ]),
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer abc_prod_bgLMews2xZs301zjc5LzR3kp",
    "Content-Type: application/json"
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