<?php
$ip = "http://207.180.219.129";
$porta = 3000;
$token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZSI6IjEiLCJpYXQiOjE2MzczNDc5NjcsImV4cCI6MTY2ODg4Mzk2N30.Lh5wVosyy_3Mp_s4oa9ylktG-qUzcZr_inA9CgVHRNU";
$celular = "559291725319";

$img = "20210824100510_2408.jpg"; 


Sendimagem($passos,$pdf,$img,$img_ext,$token,$celular,$ip,$porta,$id,$ultimoRegistro,$data);


function Sendimagem($passos,$pdf,$img,$img_ext,$token,$celular,$ip,$porta,$id,$ultimoRegistro,$data){
	
$numeros[] = $celular;	
	
$authorization = "Bearer $token";
	
$fields = array
(
	'img' => $img,
	'numbers' => $numeros,
	'mensagem' => 'BLZ 222'
);
	

$headers = array
(
//'Content-Type: multipart/form-data',
'Content-Type: application/json',	
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/image' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
$result = curl_exec($ch );
curl_close( $ch );
	
echo $result;	
	
}




?>