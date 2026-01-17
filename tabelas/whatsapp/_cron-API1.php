<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

	
list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server   "));

/*ratamento timezones*/
$timezones = array(
'AC' => 'America/Rio_branco',
'AL' => 'America/Maceio',
'AP' => 'America/Belem',   
'AM' => 'America/Manaus',
'BA' => 'America/Bahia',
'CE' => 'America/Fortaleza',
'ES' => 'America/Sao_Paulo',
'MA' => 'America/Fortaleza',
'GO' => 'America/Sao_Paulo',
'MS' => 'America/Campo_Grande',
'MT' => 'America/Cuiaba',
'PR' => 'America/Sao_Paulo',
'MG' => 'America/Sao_Paulo',
'PA' => 'America/Belem',
'PB' => 'America/Fortaleza',
'PI' => 'America/Fortaleza',
'PE' => 'America/Recife',
'RN' => 'America/Fortaleza',
'RJ' => 'America/Sao_Paulo',
'RO' => 'America/Porto_Velho',
'RS' => 'America/Sao_Paulo',
'SC' => 'America/Sao_Paulo',
'RR' => 'America/Boa_Vista',
'SP' => 'America/Sao_Paulo',
'SE' => 'America/Maceio',
'DF' => 'America/Sao_Paulo',
'TO' => 'America/Araguaia'
);
/*----------------------------------------------*/


$pergunta = "select * from usuarios where status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){
	

	
	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];		
	
	dispararSms($conexao, $d->id,$timezone,$d->token, $d->status_whats_desc, $ip,$porta );

}

function dispararSms($conexao, $cliente,$timezone,$token, $status_whats_desc, $ip,$porta){

$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("<center>Autenticação invalida!</center>");			

	
	date_default_timezone_set($timezone);

	
	$smgStatuses = "CREATE TABLE `t_".date('mY')."_smgStatuses` (
	`id` int(20) NOT NULL AUTO_INCREMENT,
	`id_grupo` int(11) NOT NULL,
	`mensagem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
	`destination` varchar(15) NOT NULL,
	`id_pesquisa` int(11) NOT NULL,
	`img` varchar(255) NOT NULL,
	`img_ext` varchar(4) NOT NULL,
	`pdf` varchar(255) NOT NULL,
	`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`enviado` enum('0','1') NOT NULL,
	`aniversario` enum('enviado','num_valido','num_invalido') NOT NULL,
	`campanha` enum('0','1','2') NOT NULL,
	PRIMARY KEY (id)
	)  ";
	$rsmgStatuses = mysqli_query($conexao2,$smgStatuses);	
	
	
	$pergunta2 = "select * from t_".date('Y')."_smgAgendamento where status = '0' ";
	$resultado2 = mysqli_query($conexao2, $pergunta2);


	while($d = mysqli_fetch_object($resultado2)){

		$data1 =  $d->data ;
		$data2 = date( "Y-m-d H:i:s", time() );

	    //echo "Timezone ".$timezone."<br>";
		//echo "Data1 ".$data1."<br>";
		//echo "Data2 ".$data2."<br>";	
		
		if(strtotime($data1) > strtotime($data2))
		{
		echo '1 - A data 1 é maior que a data 2.<br>';
		}
		elseif(strtotime($data1) == strtotime($data2))
		{
		echo '2 - A data 1 é igual a data 2.<br>';
		ListaUsuarios($conexao,$conexao2,$d->id,$timezone,$token, $status_whats_desc, $ip,$porta, $cliente);
		}
		else
		{
		echo '3 - A data 1 é menor a data 2.<br>';
		ListaUsuarios($conexao,$conexao2,$d->id,$timezone,$token, $status_whats_desc, $ip,$porta, $cliente);
		}		

	}

}

function ListaUsuarios($conexao,$conexao2,$idGrupo,$timezone,$token,$status_whats_desc, $ip,$porta, $cliente){

	date_default_timezone_set($timezone);

	
	$pergunta3 = "select * from t_".date('mY')."_smgStatuses where id_grupo='".$idGrupo."' and enviado='0' limit 0,50 ";
	$resultado3 = mysqli_query($conexao2,$pergunta3);
	while( $d3 = mysqli_fetch_object($resultado3) ){
		
		$mensagem = ($d3->mensagem);
		$celular = $d3->destination;
		
		if($status_whats_desc == "OFFLINE" or $status_whats_desc == "TIMEOUT"){
			timeout($conexao,$cliente,$status_whats_desc,$ip,$porta);
		}
		elseif($status_whats_desc == "CONNECTED"){
			EnviarWhatsapp($conexao,$conexao2,$timezone,$cliente,$idGrupo,$d3->id,$d3->id_pesquisa,$d3->img,$d3->img_ext,$d3->pdf,$token,$mensagem,$celular,$ip,$porta);
		}				
	}
	
	
	
}


function timeout($conexao,$cliente,$status_whats_desc,$ip,$porta){
	
	echo "status: $status_whats_desc <br>";	
}


function EnviarWhatsapp($conexao,$conexao2,$timezone,$cliente,$idGrupo,$id,$id_pesquisa,$img,$img_ext,$pdf,$token,$mensagem,$whatsapp,$ip,$porta){
	
	list($ultimoRegistro) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id) from t_".date('mY')."_smgStatuses where id_grupo='".$idGrupo."' "));
	
	//echo "IdV:$id - IdM:$ultimoRegistro<br>";
	//echo "mensagem:$mensagem<br>";	
	//echo "whatsapp:$whatsapp<br>";
	
	date_default_timezone_set($timezone);
	$data = date('Y-m-d H:i:s', time() ) ;

	/*$tratPerg = explode(" ",$mensagem) ;
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
	$mensagemEnviar = implode(' ',$returnPerg) ;*/		

	$numeros[] = $whatsapp;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagem ,
		'numbers' => $numeros
	);

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
	curl_close( $ch );

	//echo $result."<br><br>";

	
	mysqli_query($conexao2, " update t_".date('mY')."_smgStatuses set date = '".$data."', enviado='1' where id='".$id."' ");
	
	if($id == $ultimoRegistro){
		mysqli_query($conexao2, " update t_".date('Y')."_smgAgendamento set status='1' where id='".$idGrupo."' ");
	}
	
	
	
	
	if($id_pesquisa and $img and $pdf){
		$passos = 'pip';
		Sendimagem($passos,$pdf,$img,$img_ext,$token,$whatsapp,$ip,$porta,$id,$ultimoRegistro,$data);
	}
	elseif($img and $pdf){
		$passos = 'ip';
		Sendimagem($passos,$pdf,$img,$img_ext,$token,$whatsapp,$ip,$porta,$id,$ultimoRegistro,$data);
	}
	elseif($id_pesquisa and $pdf){
		$passos = 'pp';
		SendPdf($passos,$pdf,$token,$whatsapp,$ip,$porta,$id,$ultimoRegistro,$data);
	}
	elseif($id_pesquisa and $img){
		$passos = 'pi';
		Sendimagem($passos,$pdf,$img,$img_ext,$token,$whatsapp,$ip,$porta,$id,$ultimoRegistro,$data);
	}
	elseif($id_pesquisa){
		SendPesquisa($conexao2,$cliente,$id_pesquisa,$token,$whatsapp,$ip,$porta,$data);
	}
	elseif($img){
		$passos = '';
		Sendimagem($passos,$pdf,$img,$img_ext,$token,$whatsapp,$ip,$porta,$id,$ultimoRegistro,$data);
	}
	elseif($pdf){
		$passos = '';
		SendPdf($passos,$pdf,$token,$whatsapp,$ip,$porta,$id,$ultimoRegistro,$data);
	}
	
	
	

	
	
	
}


function Sendimagem($passos,$pdf,$img,$img_ext,$token,$celular,$ip,$porta,$id,$ultimoRegistro,$data){

$cfile = "https://www.grupoelmenoufi.com.br/business/tabelas/whatsapp/upload/".$img;	
	
$numeros[] = $celular;	
	
$authorization = "Bearer $token";
	
$fields = array
(
	'img' => $cfile,
	'numbers' => $numeros,
	'mensagem' => '-'
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

echo "IMG: ".$result."<br>".$passos."<br>$img,$img_ext<br>";	

	if($id == $ultimoRegistro){
	 	//unlink("/home/elmenoufi/public_html/business/tabelas/whatsapp/upload/".$img); 
	}
	
	if($passos=='pip' or $passos=='ip'){
		SendPdf($passos,$pdf,$token,$celular,$ip,$porta,$id,$ultimoRegistro,$data);	
	}elseif($passos=='pi'){
		SendPesquisa($conexao2,$cliente,$id_pesquisa,$token,$celular,$ip,$porta,$data);
	}
	
	mysqli_query($conexao2, " update t_".date('mY')."_smgStatuses set date = '".$data."', enviado='1' where id='".$id."' "); //atualizado em 28/06/2021
	
}


function SendPdf($passos,$pdf,$token,$celular,$ip,$porta,$data){

$cfile = "https://www.grupoelmenoufi.com.br/business/tabelas/whatsapp/upload/".$pdf;		
	
$numeros[] = $celular;	
	
$authorization = "Bearer $token";
	
$fields = array
(
	'pdf' => $cfile,
	'numbers' => $numeros,
	'mensagem' => '-'
	
	
);
	

	
$headers = array
(
//'Content-Type: multipart/form-data',
'Content-Type: application/json',	
'Authorization: ' . $authorization
	
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/pdf' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
$result = curl_exec($ch );
curl_close( $ch );

echo "PDF: ".$result."<br>$celular<br>$pdf<br>";	

	if($passos=='pip' or $passos=='pp'){
		SendPesquisa($conexao2,$cliente,$id_pesquisa,$token,$celular,$ip,$porta,$data);	
	}
	
}


function SendPesquisa($conexao2,$cliente,$id_pesquisa,$token,$celular,$ip,$porta,$data){
	
list($logo,$tituloWhat,$descricaoWhat) = mysqli_fetch_row(mysqli_query($conexao2, "select logo_login, titulo_login, descricao_login from bot_config where id_pesquisa='".$id_pesquisa."' "));	

$ultimos3 = substr($logo , -3);
$ultimos4 = substr($logo , -4);	
if($ultimos3 == 'png' or $ultimos3 == 'PNG' or $ultimos3 == 'jpg' or  $ultimos3 == 'JPG'){
	$ext = $ultimos3;
}
elseif($ultimos4 == 'JPEG' or $ultimos4 == 'jpeg'){
	$ext = $ultimos4;
}	

$link = "https://www.elmenoufi.com.br/bot/?u=login&c=$cliente&p=$id_pesquisa";
	
if($logo){
	$cfile = curl_file_create('/var/www/public_html/grupoelmenoufi/business/img/logos/'.$logo,'image/'.$ext);
	$tituloDb = ($tituloWhat);
	$descBoleDb = ($descricaoWhat);
}else{
    $cfile = curl_file_create('/var/www/public_html/grupoelmenoufi/business/img/cobrancas/whatsapp-tme.jpg','image/jpg');
	$tituloDb = "T M Elmenoufi Ltda";
	$descBoleDb = "Sistema business T M Elmenoufi";	
}
	
$numeros[] = $celular;
	
$authorization = "Bearer $token";
	
$fields = array
(
	'img' => $cfile,
	'link' => $link,
	'numbers' => $numeros,
	'titulo' => $tituloDb,
	'descricao' => $descBoleDb
);
	
$headers = array
(
//'Content-Type: multipart/form-data',
'Content-Type: application/json',	
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/link' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
$result = curl_exec($ch );
curl_close( $ch );

echo "<hr>LINK: ".$result."<br><br>";	
	
}