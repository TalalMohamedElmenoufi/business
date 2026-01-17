#!/usr/bin/php -q
<?php
error_reporting(0);
//error_reporting(E_ALL);

$host = "207.180.219.129";
$port = 2015;

$clients = array();
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,$host,$port);
socket_listen($socket);
socket_set_nonblock($socket);

while(true)
{
	
    if(($newc = socket_accept($socket)) !== false){
		
	echo "Client $newc has connected\n";
	$clients[] = $newc;
		
    }else{
	
	$con = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot");
	
	list($id) = mysqli_fetch_row(mysqli_query($con, "select MIN(id) from movimentacao "));
		
	list($id2) = mysqli_fetch_row(mysqli_query($con, "select MIN(id) from movimentacao_ack "));	
	
	list($instancia,$id_whats,$de_quem,$para_quem,$mensagem,$retorno_log,$data_registro) = mysqli_fetch_row(mysqli_query($con, "select instancia,id_whats,de_quem,para_quem,mensagem,retorno_log,data_registro from movimentacao where id = '".$id."' "));
		
	list($instancia2,$id_whats2,$de_quem2,$para_quem2,$mensagem2,$retorno_log2,$ackRes,$data_registro2) = mysqli_fetch_row(mysqli_query($con, "select instancia,id_whats,de_quem,para_quem,mensagem,retorno_log,ackRes,data_registro from movimentacao_ack where id = '".$id2."' "));
	
	$mensagemDb = ($mensagem);
	$mensagemDb2 = ($mensagem2);	
	$data = date('d/m/Y H:i:s');	
		
	if($id){
		EnviaTME($con,$id,$instancia,$id_whats,$de_quem,$para_quem,$mensagemDb,$retorno_log,$data_registro);
		
		
		
	}
	elseif($id2){
		EnviaTME2($con,$id2,$instancia2,$id_whats2,$de_quem2,$para_quem2,$mensagemDb2,$retorno_log2,$ackRes,$data_registro2);

		
	}	
	else{
		//echo "Data: $data \n";
	}

	//echo "Data: $data ID:$id instancia:$instancia mensagem:$mensagemDb \n";		


		
//Reponsavel pelo menu
/*MEU SCRIPT*/	
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));
		
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
 


$pergunta = "select * from usuarios where status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);

$Conteudos = array();		

$ConteudosAll = false;		
	
		
while($d = mysqli_fetch_object($resultado)){
	
	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];		
	
	date_default_timezone_set($timezone);

	
	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_$d->id") or die("<center>Autenticação 2 invalida</center>");
	
    list($id_saldacao,$palavra_chave,$saldacao) = mysqli_fetch_row(mysqli_query($conexao2, "select  id, palavra_chave, saldacao from bot_whats_saldacao where instancia = '".$d->id."' "));
	
	$pergunta2 = "select a.* from bot_whats_menu a where instancia = '".$d->id."' and id_saldacao = '".$id_saldacao."' order by a.opcao ";
	$resultado2 = mysqli_query($conexao2, $pergunta2);

	//echo $saldacao."\n";
	
	$Conteudos = array();
	
	while($d2 = mysqli_fetch_object($resultado2)){

		//echo $d2->conteudo."<br>";
			$Conteudos[] = $d2->conteudo;

	}
	
	if($saldacao or $palavra_chave){
		$ConteudosAll = $saldacao ."\n". implode("\n",$Conteudos) ;

		$buscas	= array();
		ChecarBot($conexao2,$d->id,$d->token,$ConteudosAll,$palavra_chave,$ip,$porta,$d->whatsapp_conectado);
		
		//echo $d->id ." ".$ConteudosAll." ".$palavra_chave." ";
		 
	}

		//para o envio da resposta
		ChecarBotR($conexao2,$d->id,$d->token,$ip,$porta,$d->whatsapp_conectado);
		//---------------------------
	
}
/*FIM DO SCRIPT*/			
//---------------------------------------		

	usleep(3000000);	
		
	}
	
mysqli_close($conexao);	
}


function EnviaTME($con,$id,$instancia,$id_whats,$de_quem,$para_quem,$mensagemDb,$retorno_log,$data_registro){
	
	//echo "Enviar para TME via CURL \n";
	
	$fields = array
	(
		'id' => $id ,
		'instancia' => $instancia,
		'id_whats' => $id_whats,
		'de_quem' => $de_quem,
		'para_quem' => $para_quem,
		'mensagem' => $mensagemDb,
		'retorno_log' => ($retorno_log),
		'data_registro' => $data_registro
	);

	$headers = array
	(
	'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://www.grupoelmenoufi.com.br/business/_mov_bot/salvar.php' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result_id = curl_exec($ch );
	curl_close( $ch );
	
	echo $result_id."\n";
	
	mysqli_query($con,"delete from movimentacao where id = '".$result_id."' ");
	
	mysqli_close($con);
}

function EnviaTME2($con,$id,$instancia,$id_whats,$de_quem,$para_quem,$mensagemDb,$retorno_log,$ackRes,$data_registro){
	
	//echo "Enviar para TME via CURL \n";
	
	$fields = array
	(
		'id' => $id ,
		'instancia' => $instancia,
		'id_whats' => $id_whats,
		'de_quem' => $de_quem,
		'para_quem' => $para_quem,
		'mensagem' => $mensagemDb,
		'retorno_log' => ($retorno_log),
		'ackRes' => $ackRes,
		'data_registro' => $data_registro
	);

	$headers = array
	(
	'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://www.grupoelmenoufi.com.br/business/_mov_bot/salvar2.php' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result_id = curl_exec($ch );
	curl_close( $ch );
	
	//echo $result_id."\n";
	
	//echo json_encode( $fields )."\n";
	
	mysqli_query($con,"delete from movimentacao_ack where id = '".$result_id."' ");
	
	
	mysqli_close($con);
}




//Responsavel pelo menu
function ChecarBot($conexao2,$instancia,$token,$ConteudosAll,$palavra_chave,$ip,$porta,$whatsapp_conectado){
	
	$buscas	= array();
	
	$chavesPalavras = explode(",",$palavra_chave);
	
	foreach ($chavesPalavras as $palavras) {

		$palavras = trim($palavras);
		
		$PalavraSis = "@elmenoufi";
		
		//$buscas[] = " status_bot='0' and mensagem LIKE '%$palavras%' and mensagem NOT LIKE '%$PalavraSis%' ";
		$buscas[] = " status_bot='0' and mensagem = '$palavras' and mensagem NOT LIKE '%$PalavraSis%' ";
	}
	
		$pergunta = "select * from mov_bot_".date('mY')." where instancia = '".$instancia."' and ".implode(" or",$buscas)." ";
		$resultado = mysqli_query($conexao2, $pergunta);
		 
		//echo $pergunta."\n";
		//echo "\n $instancia e $ConteudosAll \n";
	 
		while($d = mysqli_fetch_object($resultado)){

			$de_quem = explode("@",$d->de_quem);
			$para_quem = explode("@",$d->para_quem);
			//echo $ConteudosAll. " => de_quem:$de_quem[0] para_quem:$para_quem[0]  id:$d->id \n\n";
			 
			$numeros = array();
			
			if($whatsapp_conectado!=$de_quem[0]){
				EnviarWhatsapp($conexao2,$token,$de_quem[0],$ConteudosAll,$d->id,$ip,$porta);
			}
			
			
		}

}



function EnviarWhatsapp($conexao2,$token,$whatsapp,$mensagem,$id,$ip,$porta){


	$numeros[] = $whatsapp;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagem. "\n\n@elmenoufi" ,
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

	if($result){
		mysqli_query($conexao2, "update mov_bot_".date('mY')." set status_bot = '1' where id = '$id' ");
	}
	
	//echo $result."\n";
	
	mysqli_close($conexao2);
}
//------------------------------------------------




//Uso da resposta
function ChecarBotR($conexao2,$instancia,$token,$ip,$porta,$whatsapp_conectado){

		$pergunta = "select * from mov_bot_".date('mY')." where instancia = '".$instancia."' and status_bot = '0' ";
		$resultado = mysqli_query($conexao2, $pergunta);
		 
		//echo $pergunta."<br>";
	
		while($d = mysqli_fetch_object($resultado)){

			$de_quem = explode("@",$d->de_quem);
			
			//enviar boleto
			$P1cnpj = substr($d->mensagem,0,2) ;
			$P2cnpj = substr($d->mensagem,2,3) ;
			$P3cnpj = substr($d->mensagem,5,3) ;
			$P4cnpj = substr($d->mensagem,8,4) ;
			$P5cnpj = substr($d->mensagem,12,2) ;
			
			$P1cpf = substr($d->mensagem,0,3) ;
			$P2cpf = substr($d->mensagem,3,3) ;
			$P3cpf = substr($d->mensagem,6,3) ;
			$P4cpf = substr($d->mensagem,9,2) ;
	
			
			$cnpj = $P1cnpj.".".$P2cnpj.".".$P3cnpj."/".$P4cnpj."-".$P5cnpj;			
			
			$cpf = $P1cpf.".".$P2cpf.".".$P3cpf."-".$P4cpf;
			
			//echo $d->instancia ." e $cnpj e $cpf \n";
			
			list($id_asaas,$nome,$cpf_cnpj) = mysqli_fetch_row(mysqli_query($conexao2, "select id_asaas, nome, cpf_cnpj from clientes where cpf_cnpj = '".$cnpj."' or cpf_cnpj = '".$cpf."' or cpf_cnpj = '".$d->mensagem."' "));
			
			if($id_asaas){
				//echo $id_asaas." e ".$nome." e ".$cpf_cnpj."\n" ;
				
				list($id_rg) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id_rg) from cobrancas where customer = '".$id_asaas."' "));
				
				list($description,$status,$bankSlipUrl) = mysqli_fetch_row(mysqli_query($conexao2, "select description, status, bankSlipUrl from cobrancas where id_rg = '".$id_rg."' "));
				
				EnviarBoleto($conexao2,$instancia,$token,$ip,$porta,$d->id,$nome,$de_quem[0],$description,$status,$bankSlipUrl);
				
			}
			//----------------------
			
			
			list($id_menu,$conteudo,$arquivo,$ext_arquivo) = mysqli_fetch_row(mysqli_query($conexao2, "select id_menu, conteudo, arquivo, ext_arquivo from bot_whats_menu_resposta where instancia = '".$instancia."' and id_menu = '".$d->mensagem."' "));
			
			$de_quem = explode("@",$d->de_quem);
			$para_quem = explode("@",$d->para_quem);
				//echo $conteudo. " e ".$de_quem[0]."  id: $d->id <hr>";

			$numeros = array();
			
			if($arquivo){
				if($whatsapp_conectado!=$de_quem[0]){
					EnviarWhatsappArquivo($conexao2,$token,$de_quem[0],$conteudo,$d->id,$arquivo,$ext_arquivo,$ip,$porta);
				}
			}else{
				if($id_menu and $whatsapp_conectado!=$de_quem[0]){
					EnviarWhatsappR($conexao2,$token,$de_quem[0],$conteudo,$d->id,$ip,$porta);	
				}
			}
			

			
		}

}	




function EnviarWhatsappR($conexao2,$token,$whatsapp,$mensagem,$id,$ip,$porta){


	$numeros[] = $whatsapp;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagem. "\n\n@elmenoufi" ,
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

	if($result){
		mysqli_query($conexao2, "update mov_bot_".date('mY')." set status_bot = '1' where id = '$id' ");
	}
	
	//echo $result."\n";
	
	mysqli_close($conexao2);
}



function EnviarWhatsappArquivo($conexao2,$token,$whatsapp,$mensagem,$id,$arquivo,$ext_arquivo,$ip,$porta){
	
if($ext_arquivo=="pdf" or $ext_arquivo=="PDF"){
	$Extensao = "application/pdf"; 
	$send = "pdf";
	$tipo = "pdf";
}else{
	$Extensao = "image/".$ext_arquivo;
	$send = "image";
	$tipo = "img";
}	

$cfile = "/var/www/public_html/grupoelmenoufi/business/tabelas/atendimento/upload/".$arquivo;	
//echo $whatsapp ."\n". $Extensao."\n".$send."\n".$tipo."\n$cfile";	
	
$numeros[] = $whatsapp;	

$authorization = "Bearer $token";
	
$fields = array
(
	$tipo => $cfile,
	'numbers' => $numeros,
	'mensagem' => $mensagem. "\n\n@elmenoufi"
);

$headers = array
(
//'Content-Type: multipart/form-data',
'Content-Type: application/json',	
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/'.$send );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
//curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );	
curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
$result = curl_exec($ch );
curl_close( $ch );	

	if($result){
		mysqli_query($conexao2, "update mov_bot_".date('mY')." set status_bot = '1' where id = '$id' ");
	}	
	
//echo $result."\n";	

mysqli_close($conexao2);	
	
}
//---------------------------------






//enviar boleto ao cliente
function EnviarBoleto($conexao2,$instancia,$token,$ip,$porta,$id,$nome,$whatsapp,$description,$statusDb,$bankSlipUrl){

		$status = ( (($statusDb=='PENDING')?'PENDENTE':  
				   
				    ( ($statusDb=='CONFIRMED')?'CONFIRMADO': 
				   
				    ( ($statusDb=='RECEIVED')?'PAGO': 
				   
				    ( ($statusDb=='RECEIVED_IN_CASH')?'PAGO EM DINHEIRO': 
				   
				    ( ($statusDb=='OVERDUE')?'VENCIDO': 
				   
				    ( ($statusDb=='REFUND_REQUESTED')?'REEMBOLSO SOLICITADO': 
				   
				    ( ($statusDb=='REFUNDED')?'DEVOLVEU': 
				   
				    ( ($statusDb=='CHARGEBACK_REQUESTED')?'COBRANÇA SOLICITADA':
				   
				    ( ($statusDb=='CHARGEBACK_DISPUTE')?'RECUPERAR A DISPUTA': 
				   
				    ( ($statusDb=='AWAITING_CHARGEBACK_REVERSAL')?'AGUARDANDO REVERSÃO DE COBRANÇA': 
				   
				    ( ($statusDb=='DUNNING_REQUESTED')?'DUNNING SOLICITADO': 
				   
				    ( ($statusDb=='DUNNING_RECEIVED')?'DUNNING RECEBIDO': 
				   
				    ( ($statusDb=='AWAITING_RISK_ANALYSIS')?'AGUARDANDO ANÁLISE DE RISCO':
				   
				    '' 
		            ))))))))))))));		
	
	$cliente = ($nome);
	$descricao = ($description);
	
	$mensagem = "*Cliente*: $cliente \n*Descrição*: $descricao \n*Status*: $status \n*Link*: *$bankSlipUrl*" ;
	
	//echo $mensagem;
	
	$numeros[] = $whatsapp;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagem. "\n\n@elmenoufi" ,
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

	if($result){
		mysqli_query($conexao2, "update mov_bot_".date('mY')." set status_bot = '1' where id = '$id' ");
	}
	
	//echo $result."\n";
	
	mysqli_close($conexao2);	
	
}
//-------------------------------------




?>