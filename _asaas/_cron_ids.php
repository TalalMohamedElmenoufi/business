<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

list($api_token) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas "));

$query = "select * from usuarios where id_asaas = '' ";
$result = mysqli_query($conexao, $query);
while($d = mysqli_fetch_object($result)){

	$cpfCnpj0 = str_replace('.','',$d->cpf_cnpj);
	$cpfCnpj = str_replace('-','',$cpfCnpj0);
	$email = $d->email;
	sendId($conexao,$api_token,$cpfCnpj,$email);

}


function sendId($conexao,$api_token,$cpfCnpj,$email){

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/customers?email=$email&cpfCnpj=$cpfCnpj");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "access_token: $api_token"
	));

	$response = curl_exec($ch);
	curl_close($ch);


	$jsonObjlista = json_decode($response);

	$Listando = $jsonObjlista->data;

	foreach ( $Listando as $e )
	{
		mysqli_query($conexao,"update usuarios set id_asaas='".trim($e->id)."' where email = '".$e->email."'  ");
		//echo "id: $e->id - email: $e->email   - cpfCnpj: $e->cpfCnpj  <br>"; 

	}
	
	
//echo ($response);
}		
?>