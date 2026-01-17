<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';


function dataBr($d){
	$l = explode(" ",$d);
	$dt = explode("-",$l[0]);
	if($dt[2] and $dt[1] and $dt[0]){
		return $dt[2]."/".$dt[1]."/".$dt[0].(($l[1]) ? " ".$l[1] : false);
	}else{
		return false;
	}
}


list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));





//$idUsuario = 1; 
//$idCobranca = 591;
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



$pergunta = "select * from usuarios where id = '".$idUsuario."' and status_whats_desc = 'CONNECTED'  ";
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

	list($tituloWhat,$descricaoWhat,$imgWhat) = mysqli_fetch_row(mysqli_query($conexao2, "select titulo_vencida,descricao_vencida,img from whats_config "));

	ListaConexao($conexao,$conexao2,$d->id,$timezone,$api_token,$d->token,$ip,$porta,$idCobranca,$tituloWhat,$descricaoWhat,$imgWhat);


function ListaConexao($conexao,$conexao2,$id_user,$timezone,$api_token,$token,$ip,$porta,$idCobranca,$tituloWhat,$descricaoWhat,$imgWhat){

		$cobrancas = "select a.*, b.nome, b.celular, b.email, b.emails from cobrancas a
					left join clientes b on b.id_asaas=a.customer
		where a.id_rg = '".$idCobranca."' ";
		$Rcobranca = mysqli_query($conexao2,$cobrancas);

		$ValorG = "";
		while ($c = mysqli_fetch_object($Rcobranca)){

			
				$cel = explode(" ",$c->celular);
				$cel2 = str_replace("-","",$cel[3]);
				$cel1 = $cel[0]."".$cel[1].$cel2; //Sem o 9
				$cel2 = $cel[0]."".$cel[1].$cel[2].$cel2; //Com o 9
			
			    if($cel[1]=='92' || $cel[1]=='51' || $cel[1]=='75' || $cel[1]=='63' || $cel[1]=='41'){
					$celular = $cel1;	
				}else{
					$celular = $cel2;	
				}

			
				$nome = ($c->nome);
				$description = ($c->description);
				$link = $c->bankSlipUrl;
				$link2 = $c->invoiceUrl;

			
			//echo $celular ." <br> ".$c->value ." <br> ". ($c->description)." <br> ".$c->bankSlipUrl."<br>".$c->invoiceUrl."<br>";
			
			
			
	//Atualização
	$dataCriada = $c->dateCreated;
	$Dc = explode("-",$dataCriada);
	$mes = (
			(($Dc[1]=="01") ? "Janeiro" : 
			(($Dc[1]=="02") ? "Fevereiro" : 
			(($Dc[1]=="03") ? "Março" : 
			(($Dc[1]=="04") ? "Abril" : 
			(($Dc[1]=="05") ? "Maio" :
			(($Dc[1]=="06") ? "Junho" :
			(($Dc[1]=="07") ? "Julho" : 
			(($Dc[1]=="08") ? "Agosto" :
			(($Dc[1]=="09") ? "Setembro" :
			(($Dc[1]=="10") ? "Outubro" :
			(($Dc[1]=="11") ? "Novembro" :
			(($Dc[1]=="12") ? "Dezembro" :
			"")))))))))))));		
			
			
	$referente = $mes ." de ".$Dc[0];
	$valorCobrado = " R$ ". number_format($c->value,2,",",".");
	$Vn = explode("-",$c->dueDate); ;
	$vencimento = $Vn[2]."/".$Vn[1]."/".$Vn[0];		
	//--------------------------
			
		   list($logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado) = mysqli_fetch_row(mysqli_query($conexao, "select logo, empresa, cpf_cnpj, site, email, contato, endereco, cep, cidade, estado from cobrancas_empresa where id_usuario = '$id_user' "));	
		    
			if($email){
				SendEmail($nome, $c->value, $c->dueDate, $description, $c->invoiceUrl, $c->email, $c->emails, $logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado);
			}
			
			EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$tituloWhat,$descricaoWhat,$imgWhat,$referente,$valorCobrado,$vencimento);
			
		}
	
	
	
}



function EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$tituloWhat,$descricaoWhat,$imgWhat,$referente,$valorCobrado,$vencimento){
  
//$tratar = "559291725319";	
//$tratarCelss = explode(",",$tratar);	
	 
$tratarCelss = explode(",",$celular);	 
	
$titulo = "Olá obrigado por utilizar os nossos serviços!\n*Boleto de cobrança*";
$descBole = "Sistema business T M Elmenoufi";
	
$tituloDb = ($tituloWhat);
$descBoleDb = ($descricaoWhat);	
	
$descricaoServerco = ($description);	

$mensagens .= (($tituloDb)?$tituloDb:$titulo)."\n".(($descBoleDb)?$descBoleDb:$descBole)."\n";	
$mensagens .= "*Referente*: a ".$referente."\n";	
$mensagens .= "*Descrição*: ".$descricaoServerco."\n";	
$mensagens .= "*Vencimento*: ".$vencimento."\n";	
$mensagens .= "*Valor*: ".$valorCobrado."\n\n";	
$mensagens .= "*".$link2."*";	
	
	
$authorization = "Bearer $token";
	
$fields = array
(

	'mensagem' => $mensagens ,
	'numbers' => $tratarCelss
		
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
	
	echo "<script>parent.LembreteEnviado();</script>";
	
}











function SendEmail($nome, $value, $dueDate, $description, $invoiceUrl, $emailSend, $emails, $logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado){
	 
    $infoEmail = "Olá, sua cobrança";
    $Subject = $empresa;

	$html  .= '<img src="'.$logo.'">&nbsp;&nbsp;';

	$html  .= '<span style="font-weight:700;font-size:20pt;">'.$empresa.'</span><br><br>';
	
	$html  .= '<span style="font-weight:700;font-size:16pt;">Ol&aacute;, '.($nome).'</span><br><br>';
    
	$html  .= '<span style="font-weight:100;font-size:14pt;">Lembramos que a sua cobran&ccedil;a gerada por '.$empresa.' no valor de <b>R$ '.number_format($value,2,",",".").'</b> vence em <b>'.dataBr($dueDate).'</b></span>.<br>';
 
	$html  .= 'Descri&ccedil;&atilde;o da cobran&ccedil;a: '.($description).'<br><br>';

	$html  .= '<span style="font-weight:700;font-size:14pt;">Clique no bot&atilde;o abaixo para visualizar a cobran&ccedil;a.</span><br><br>';

	$html  .= ' <span style="color:black"><a href="'.$invoiceUrl.'"><span style="font-size:10.5pt;font-family:"Open Sans",sans-serif;text-decoration:none"><img border=0 width=186 height=36 style="width:1.9375in;height:.375in" src="https://www.grupoelmenoufi.com.br/business/img/visualizar_cobranca.png" alt="Visualizar cobrança"></span></a></span> <br> <br> ';


    $html  .= '<span style="font-weight:50;font-size:12pt;"> Ou acesse '.$invoiceUrl.'</span>';



    $html  .= '<br><br><br>';
    $html  .= 'Atenciosamente,<br><br>';


    $html  .= $empresa.'<br>';
    $html  .= $cpf_cnpj.'<br>';
    $html  .= $site.'<br>';
    $html  .= $email.'<br>';
    $html  .= $contato.'<br>';
    $html  .= $endereco.'<br>';
    $html  .= 'CEP: '.$cep.'<br>';
    $html  .= $cidade.'-'.$estado.'<br>';


	$mail = new PHPMailer(true);

	try {
		//Server settings
		//$mail->SMTPDebug = 1;                              // Enable verbose debug output
		$mail->isSMTP();                                     // Send using SMTP
		$mail->Host       = 'elmenoufi.com.br';              // Set the SMTP server to send through
		$mail->SMTPAuth   = true;                            // Enable SMTP authentication
		$mail->Username   = 'nao-responda@elmenoufi.com.br'; // SMTP username
		$mail->Password   = '3lm3n0uf!2023';                 // SMTP password
		
		$mail->SMTPSecure = 'tls'; 						     //tls or ssl
		$mail->Port       = 587;				             // TCP port to connect to 587  ou 465 
		
		$mail->setFrom('nao-responda@elmenoufi.com.br', ($infoEmail) );
		$mail->addAddress( trim($emailSend) , $nome);
		
		$arrayEmails = explode(",",$emails);
		foreach ($arrayEmails as $emailLoop) {
			$mail->addAddress( trim($emailLoop) , $nome);
		}
		
		
		
		// Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $Subject;
		$mail->Body    = $html;
		$mail->AltBody = $html;

		$mail->send();
		
		echo "Enviado com sucesso!";
           

	} catch (Exception $e) {
		$mail->ErrorInfo;

	}
	
}


?>