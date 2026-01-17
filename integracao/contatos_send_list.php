<?php
$retorno = trim(file_get_contents("php://input"));
$dadosOut = json_decode($retorno, true);

$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("erro na conexao");


if($_POST){
	$email = $_POST[email];
	$senha = $_POST[senha];
}else{
	$email = $dadosOut[email];
	$senha = $dadosOut[senha];
}

$getHash = mysqli_query($conexao,"SELECT id, senha, creditos, creditos_msg, token FROM usuarios WHERE email = '$email'");
$dados = mysqli_fetch_assoc($getHash);
$hash = $dados['senha'];

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));

if(password_verify($senha, $hash)){

$statusSms = (($dados['creditos']>0)?'Saldo SMS disponivel':'Sem saldo de SMS');
$statusWhatsapp = (($dados['creditos_msg']>0)?'Saldo Whatsapp disponivel':'Sem saldo de Whatsapp');
$token = (($dados['token'])?'Token ok':'Token invalido');

$QtEnvios = count( explode(",",$numbers) );	
	
	$data = array
	(
		'infos' =>
			$data = array
			(
				'token' => $token,
				'sms' => $dados['creditos'],
				'whatsapp' => $dados['creditos_msg'],
				'status_sms' => $statusSms,
				'status_whatsapp' => $statusWhatsapp,
				'qnt_numbers' => $QtEnvios,	
			)
	);	
	header('Content-Type: application/json');
	//echo json_encode($data);
	
	if( $dados['creditos_msg'] >= $QtEnvios ){
		Enviar($ip,$porta,$conexao,$dados[id],$dados[token],$QtEnvios);
	}else{
		NaoEnviar();
	}
	
	
}else{
	
	$data = array
	(
		'infos' =>
			$data = array
			(
				'status_db' => 'ERRO AO CONECTAR AO BANCO'	
			)
	);
	header('Content-Type: application/json');
	echo json_encode($data);	
	
}






function Enviar($ip,$porta,$conexao,$codigo,$token,$QtEnvios){

	$authorization = "Bearer $token";

	$headers = array
	(
	'Content-Type: application/json',	
	'Authorization: ' . $authorization

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/contatos' );
	curl_setopt( $ch,CURLOPT_GET, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	$result = curl_exec($ch );
	curl_close( $ch );
	
	echo $result;

	/*Descontar creditos*/
	list($creditoMsg) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_msg from usuarios where id = '".$codigo."' "));

	$credito_atual = ($creditoMsg - $QtEnvios) ;

	mysqli_query($conexao, "update usuarios set creditos_msg='".$credito_atual."' where id = '".$codigo."' ");
	/*--------------------------------------------*/
	 
}


function NaoEnviar(){
	
	$data = array
	(
		'infos' =>
			$data = array
			(
				'credito' => 'ERRO NO ENVIO SEM CREDITOS'	
			)
	);
	header('Content-Type: application/json');
	echo json_encode($data);		
	
}