<?php
include("../../includes/connect.php");
	
list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));
//list($token) = mysqli_fetch_row(mysqli_query($conexao, "select token from usuarios where id = '".$_SESSION[id_usuario]."' "));

list($token) = mysqli_fetch_row(mysqli_query($conexao, "select token from usuarios where id = '1' "));

//".$_GET[Catg]."

$pergunta = "select a.* from cadastro a
where a.tel_tipo = '' and a.categoria='".$_GET[Catg]."'
limit 0,500
";
$resultado = mysqli_query($conexao2, $pergunta);
//echo $pergunta;									  

 while($d = mysqli_fetch_object($resultado)){

	$d->id; 
	$d->cod_pais;
	$d->cod_estado;	
	$telTrat = substr($d->telefone, 1);

	$celular = $d->cod_pais."".$d->cod_estado."".$telTrat;

	//echo $celular."<br>"; 

	ValidarNumerosOK($conexao2,$d->id,$celular,$token,$ip,$porta);
 }	


/*function ValidarNumeros($conexao2,$id_reg,$celular,$token,$ip,$porta){ //Versao 2
	
$authorization = "Bearer $token";
	
$numeros = $celular;		
	
	
$fields = array
(
	'numbers' => $numeros
);
	
$headers = array
(
'Content-Type: application/json',	
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/valida-numeros' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
	
$jsonObj = json_decode($result);
$mensagem = $jsonObj->result;		
	

	
if($mensagem == "success"){
	mysqli_query($conexao2,"update cadastro set tel_tipo = 'WhatsApp' where id = '".$id_reg."' ");
}else{
	mysqli_query($conexao2,"update cadastro set tel_tipo = 'Invalido' where id = '".$id_reg."' ");
}
	
}*/




function ValidarNumeros($conexao2,$id_reg,$celular,$token,$ip,$porta){ //versao 1
	
$authorization = "Bearer $token";
	
$numeros[] = $celular;		
	
$fields = array
(
	'numbers' => $numeros
);
	
$headers = array
(
'Content-Type: application/json',	
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/valida-numeros' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
	
//echo $result."<br>";
	
$jsonObj = json_decode($result);
$mensagem = $jsonObj->log;		
foreach ( $mensagem as $e ){
	foreach ( $e as $f ){
	}
}
if($f){
	mysqli_query($conexao2,"update cadastro set tel_tipo = 'WhatsApp' where id = '".$id_reg."' ");
}else{
	mysqli_query($conexao2,"update cadastro set tel_tipo = 'Invalido' where id = '".$id_reg."' ");
}
	
}


function ValidarNumerosOK($conexao2,$id_reg,$celular,$token,$ip,$porta){
	

	mysqli_query($conexao2,"update cadastro set tel_tipo = 'WhatsApp' where id = '".$id_reg."' ");

	
}


?>