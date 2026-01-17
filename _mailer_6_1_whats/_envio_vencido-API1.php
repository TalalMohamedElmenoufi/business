<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));

//$idUsuario = 1; 
//$idCobranca = 142;
$idUsuario = $_POST[idUsuario];
$idCobranca = $_POST[idCobranca];

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



$pergunta = "select * from usuarios where id = '".$idUsuario."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);


	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];	
	
	
	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$d->id);

	list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));	

	list($api_token_cliente) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '".$d->id."' and status = 'Liberado' "));	

	if($api_token_dono==$api_token_cliente){
		$api_token = $api_token_dono ;
	}else{
		$api_token = $api_token_cliente ;	
	}	

	list($tituloWhat,$descricaoWhat,$imgWhat) = mysqli_fetch_row(mysqli_query($conexao2, "select titulo,descricao,img from whats_config "));

	ListaConexao($conexao2,$d->id,$timezone,$api_token,$d->token,$ip,$porta,$idCobranca,$tituloWhat,$descricaoWhat,$imgWhat);



function ListaConexao($conexao2,$id_user,$timezone,$api_token,$token,$ip,$porta,$idCobranca,$tituloWhat,$descricaoWhat,$imgWhat){

		$cobrancas = "select a.*, b.celular from cobrancas a
					left join clientes b on b.id_asaas=a.customer
		where a.id_rg = '".$idCobranca."' ";
		$Rcobranca = mysqli_query($conexao2,$cobrancas);

		$ValorG = "";
		while ($c = mysqli_fetch_object($Rcobranca)){

			
				$cel = explode(" ",$c->celular);
				$cel2 = str_replace("-","",$cel[3]);
				$cel1 = $cel[0]."".$cel[1].$cel2; //Sem o 9
				$cel2 = $cel[0]."".$cel[1].$cel[2].$cel2; //Com o 9
			
				if($cel[1]=='92' || $cel[1]=='51' || $cel[1]=='75' || $cel[1]=='63'){
					$celular = $cel1;
				}else{
					$celular = $cel2;
				}

			
			
			
				$description = ($c->description);
				$link = $c->bankSlipUrl;
				$link2 = $c->invoiceUrl;

			
			//echo $celular ." <br> ".$c->value ." <br> ". ($c->description)." <br> ".$c->bankSlipUrl."<br>".$c->invoiceUrl."<br>";
			
			
		  EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$tituloWhat,$descricaoWhat,$imgWhat);
			
		}
	
	
	
}





function EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$tituloWhat,$descricaoWhat,$imgWhat){
 
//$tratar = "559291725319";	
//$tratarCelss = explode(",",$tratar);	
	
$tratarCelss = explode(",",$celular);	 
	
$Ola = "Olá obrigado por utilizar os nossos serviços!";	
$titulo = "*Lembrete* Boleto vencido!";
$descBole = "Sistema business T M Elmenoufi";
	
$mensagemEnviar = $Ola."\n".$titulo."\n".$descBole ;		
	
	
$authorization = "Bearer $token";
	
$fields = array
(
	'numbers' => $tratarCelss,
	'link' => $link2,
	'descricao' => $mensagemEnviar
);
	
$headers = array
(
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
$result = curl_exec($ch );
curl_close( $ch );
	
//echo $result;	
	
	echo "<script>parent.LembreteEnviado();</script>";
	
}



?>