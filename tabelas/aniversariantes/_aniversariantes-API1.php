<?php
error_reporting(0);
function acentos($palavra){
	$acentos = array(
		 'Ã¡' => 'a', 'Ã ' => 'a', 'Ã£' => 'a', 'Ã¢' => 'a', 'Ã©' => 'e', 'Ãª' => 'e', 'Ã­' => 'i', 'Ã³' => 'o', 'Ã´' => 'o', 'Ãµ' => 'o', 'Ãº' => 'u', 'Ã¼' => 'u', 'Ã§' => 'c', 'Ã?' => 'A', 'Ã€' => 'A', 'Ãƒ' => 'A', 'Ã‚' => 'A', 'Ã‰' => 'E', 'ÃŠ' => 'E', 'Ã?' => 'I', 'Ã“' => 'O', 'Ã”' => 'O', 'Ã•' => 'O', 'Ãš' => 'U', 'Ãœ' => 'U', 'Ã‡' => 'C');
	$acao = strtr($palavra, $acentos);
	return $acao ;
}

/*ratamento timezones*/
$timezones = array(
'AC' => 'America/Rio_branco',   'AL' => 'America/Maceio',
'AP' => 'America/Belem',        'AM' => 'America/Manaus',
'BA' => 'America/Bahia',        'CE' => 'America/Fortaleza',
'ES' => 'America/Sao_Paulo',    'MA' => 'America/Fortaleza',
'GO' => 'America/Sao_Paulo',    'MS' => 'America/Campo_Grande',
'MT' => 'America/Cuiaba',	    'PR' => 'America/Sao_Paulo',
'MG' => 'America/Sao_Paulo',    'PA' => 'America/Belem',   
'PB' => 'America/Fortaleza',    'PI' => 'America/Fortaleza',
'PE' => 'America/Recife',		'RN' => 'America/Fortaleza',
'RJ' => 'America/Sao_Paulo',    'RO' => 'America/Porto_Velho',
'RS' => 'America/Sao_Paulo',    'SC' => 'America/Sao_Paulo',
'RR' => 'America/Boa_Vista',    'SP' => 'America/Sao_Paulo',
'SE' => 'America/Maceio',       'DF' => 'America/Sao_Paulo', 
'TO' => 'America/Araguaia',     
//'DF' => 'America/Brasilia',
);
/*----------------------------------------------*/


$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("erro na conexao");

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));

$pergunta = "select * from usuarios where status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){

	//echo "CLIENTE: $d->id - $d->nome <hr>"; 
	
	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];		
	
	criarBase($conexao,$d->id,$d->aniversariantes,$d->aniversariantes_whats,$timezone,$ip,$porta,$d->token);
	
	
}



function criarBase($conexao,$cliente,$aniversariantes,$aniversariantes_whats,$timezone,$ip,$porta,$token){
	
$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("erro na conexao");	


$aniversariantes = "CREATE TABLE IF NOT EXISTS `t_".date('mY')."_aniversariantes` (
`id` int(20) NOT NULL AUTO_INCREMENT,
`id_cadastro` int(11) NOT NULL,
`mensagem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
`destination` varchar(15) NOT NULL,
`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`enviado` enum('0','1') NOT NULL,
`aniversario` enum('enviado','num_valido','num_invalido') NOT NULL,
PRIMARY KEY (id)
)  ";
$raniversariantes = mysqli_query($conexao2,$aniversariantes);	

	
date_default_timezone_set($timezone);	
	
$MesAno = intval(date('my',time()));	
	
$dataHoje = date('md',time());	
	
$dataNiver = date('m-d',time());
	

list($mensagem) = mysqli_fetch_row(mysqli_query($conexao2, "select mensagem from mensagem_niver where id = '".$MesAno."' and data_hoje = '".$dataHoje."' "));

//echo "MesAno:$MesAno - dataHoje:$dataHoje - mensagem:$mensagem <hr>";	
	
	
	
/*Para o WHATSAPP Cretito atual*/
list($creditoWhats) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_msg from usuarios where id = '".$cliente."' "));

list($QtEnviosWhats) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where data_nascimento like '%$dataNiver%' and tel_tipo = 'WhatsApp' "));

$credito_atual_whats = ($creditoWhats - $QtEnviosWhats) ;
/*---------------------------*/		

//echo "cliente:$cliente | creditoWhats:$creditoWhats - QtEnviosWhats:$QtEnviosWhats - credito_atual_whats:$credito_atual_whats <hr>";	
	
	
	
if($creditoWhats > $QtEnviosWhats and $QtEnviosWhats > 0){
	
		list($maxId) = mysqli_fetch_row(mysqli_query($conexao2, "select max(id) from cadastro where data_nascimento like '%$dataNiver%' and tel_tipo = 'WhatsApp' "));
	
		$pergunta = "select * from cadastro where data_nascimento like '%$dataNiver%' and tel_tipo = 'WhatsApp' ";
		$resultado = mysqli_query($conexao2, $pergunta);

		//echo "CLI:<b style='color:red'>$cliente</b> ". $pergunta."<br>";	
		
		while($d = mysqli_fetch_object($resultado)){

			if($mensagem){
			
				$Tnome = ($d->nome);

				$nome = explode(' ',$Tnome);
				$celular = $d->cod_pais.' ('.$d->cod_estado.') '.$d->telefone;
				$mensagem_tratada = str_replace('[nome]',$nome[0],$mensagem);
				$mensagem_enviar  = str_replace('[celular]',$celular,$mensagem_tratada);

				$whatsapp = $d->cod_pais."".$d->cod_estado."". substr($d->telefone, 1) ;

				list($idCadastro) = mysqli_fetch_row(mysqli_query($conexao2, "select id_cadastro from t_".date('mY')."_aniversariantes where id_cadastro = '".$d->id."' "));

				$data = date('Y-m-d H:i:s', time() ) ;
				if(!$idCadastro){
					mysqli_query($conexao2, " insert into t_".date('mY')."_aniversariantes set id_cadastro = '".$d->id."', mensagem='".($mensagem_enviar)."', destination='".$whatsapp."', date = '".$data."', enviado='0', aniversario='enviado' ");
				}
				
				//echo "$idCadastro - $mensagem_enviar para $whatsapp<hr>";

				
				
		   }
			
		}
	
		ListaAniversariantes($conexao,$conexao2,$maxId,$ip,$porta,$token,$credito_atual_whats,$cliente);
	
}else{
	AtualizarMensagem($conexao,$conexao2);
}
	
	
	
}



function ListaAniversariantes($conexao,$conexao2,$maxId,$ip,$porta,$token,$credito_atual_whats,$cliente){

		$pergunta = "select * from t_".date('mY')."_aniversariantes where enviado = '0' limit 0,20 ";
		$resultado = mysqli_query($conexao2, $pergunta);
		
		while($d = mysqli_fetch_object($resultado)){
			
			//echo "$maxId é= $d->id_cadastro - $d->mensagem para $d->destination <br>";
			ValidarNumeros($conexao,$conexao2,$maxId,$ip,$porta,$token,$d->id_cadastro,$d->id,$d->destination,$d->mensagem,$credito_atual_whats,$cliente);
		}

}




function ValidarNumeros($conexao,$conexao2,$maxId,$ip,$porta,$token,$id,$idSmg,$whatsapp,$mensagem_enviar,$credito_atual_whats,$cliente){
	
$authorization = "Bearer $token";
	
$numeros[] = $whatsapp;		
	
$fieldschValidar = array
(
	'numbers' => $numeros
);
	
$headersValidar = array
(
'Content-Type: application/json',	
'Authorization: ' . $authorization

);

$chValidar = curl_init();
curl_setopt( $chValidar,CURLOPT_URL, $ip.':'.$porta.'/valida-numeros' );
curl_setopt( $chValidar,CURLOPT_POST, true );
curl_setopt( $chValidar,CURLOPT_HTTPHEADER, $headersValidar );
curl_setopt( $chValidar,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $chValidar,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $chValidar,CURLOPT_POSTFIELDS, json_encode( $fieldschValidar ) );
$result = curl_exec($chValidar );

//echo $result."<br>";
	
$jsonObj = json_decode($result);
$mensagem = $jsonObj->log;		
foreach ( $mensagem as $e ){
	foreach ( $e as $f ){
	}
}
if($f){
	SendWhats($conexao,$conexao2,$maxId,$ip,$porta,$token,$id,$idSmg,$whatsapp,$mensagem_enviar,$credito_atual_whats,$cliente);
}else{
	mysqli_query($conexao2,"update cadastro set tel_tipo = 'Invalido' where id = '".$id."' ");
	mysqli_query($conexao2, " update t_".date('mY')."_aniversariantes set enviado = '1', aniversario='num_invalido' where id = '".$idSmg."' ");
	

		if($id === $maxId){
			mysqli_query($conexao, "update usuarios set creditos_msg='".$credito_atual_whats."' where id = '".$cliente."' ");
			AtualizarMensagem($conexao,$conexao2);
		}
	
}
	
curl_close( $chValidar );	
}



function SendWhats($conexao,$conexao2,$maxId,$ip,$porta,$token,$id,$idSmg,$whatsapp,$mensagemDb,$credito_atual_whats,$cliente){

	$whatsappDb = trim($whatsapp);

	$mensagem = ($mensagemDb);

	$tratPerg = explode(" ",$mensagem) ;
	foreach ($tratPerg as $key => $value ) {  
	$tratPerg1 = explode("#",$value) ;
	$parte1 = substr($tratPerg1[1],0, 5);
	$parte2 = substr($tratPerg1[1], -5);
	$Emoji = " '\ ".$parte1." \'".$parte2   ;
	$Emoji = str_replace("'","",$Emoji);
	$Emoji = str_replace(" ","",$Emoji);
	$Emoji = json_decode('"'.$Emoji.'"');		
	$returnPerg[] = ($tratPerg1[0]) .''. (($tratPerg1[1])?$Emoji:'')   ;
	}
	$mensagemEnviar = implode(' ',$returnPerg) ;	
	
	$numeros[] = $whatsappDb;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagemEnviar ,
		'numbers' => $numeros
	);
	
	//echo json_encode( $fields )."<br>";
	
	$headers = array
	(
	'Content-Type: application/json',	
	'Authorization: ' . $authorization

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/text' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
		
	
	
	if($result){

		date_default_timezone_set($timezone);
		$data = date('Y-m-d H:i:s', time() ) ;		
		mysqli_query($conexao2, " update t_".date('mY')."_aniversariantes set enviado = '1', aniversario='num_valido', date = '".$data."' where id = '".$idSmg."' ");


		if($id === $maxId){
			mysqli_query($conexao, "update usuarios set creditos_msg='".$credito_atual_whats."' where id = '".$cliente."' ");
			AtualizarMensagem($conexao,$conexao2);
		}				

	}	
	
	curl_close( $ch );
	
	
}





function AtualizarMensagem($conexao,$conexao2){
	
	//------Atualizar mensagem
	list($data) = mysqli_fetch_row(mysqli_query($conexao2, "select max(data) from mensagem_niver  "));

	list($idR,$mensagemAt) = mysqli_fetch_row(mysqli_query($conexao2, "select id, mensagem from mensagem_niver where data = '".$data."'  "));
	//-------------------------						

	$Hoje = date('d-m-Y',time());

	$dia = date("d",time()); // dia desejado
	$mes = date("m",time()); // MÃªs desejado
	$ano = date("Y",time()); // Ano atual
	$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); 

	if( $ultimo_dia == $dia ){

	$MesAnoAnterior = intval(date('my',time()));	

	$MesAno = intval( date('my', strtotime('+1 month', strtotime( $Hoje ))) );

	$HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));		

	mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAnoAnterior."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");		

	mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");	

	}else{
	$MesAno = intval(date('my',time()));

	$HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));

	mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");	
	}	
	

}
?>