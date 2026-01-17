<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

function dataBr($d){
	$l = explode(" ",$d);
	$dt = explode("-",$l[0]);
	if($dt[2] and $dt[1] and $dt[0]){
		return $dt[2]."/".$dt[1]."/".$dt[0].(($l[1]) ? " ".$l[1] : false);
	}else{
		return false;
	}
}


/*$idUsuario = 1;
$idCliente = 332;*/
$idUsuario = $_POST['idUsuario'];
$idCliente = $_POST['idCliente'];

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

	list($tituloWhat,$descricaoWhat,$imgWhat) = mysqli_fetch_row(mysqli_query($conexao2, "select titulo,descricao,img from whats_config "));

	ListaConexao($conexao,$conexao2,$d->id,$timezone,$api_token,$d->token,$ip,$porta,$idCliente,$tituloWhat,$descricaoWhat,$imgWhat);



function ListaConexao($conexao,$conexao2,$id_user,$timezone,$api_token,$token,$ip,$porta,$idCliente,$tituloWhat,$descricaoWhat,$imgWhat){

		$cobrancas = "select * from clientes where id = '".$idCliente."' ";
		$Rcobranca = mysqli_query($conexao2,$cobrancas);

		$ValorG = "";
		
		while ($c = mysqli_fetch_object($Rcobranca)){

			$nomeCliente = ($c->nome);
			
			//echo $c->id ." - ". $nomeCliente" - ".$c->celular. " - " .$c->email ." - ".$c->emails ."<br>";

			$AnoMes = date('Y-m',time());
			$servicos = "select * from servicos where id_cliente='".$c->id."' and data_adesao='0000-00-00' or id_cliente='".$c->id."' and data_adesao like '%$AnoMes%' ";
			$Rservicos = mysqli_query($conexao2,$servicos);
			$Vtotal = "";
			$desconto = "";
			$descUnific = array();
			while ($s = mysqli_fetch_object($Rservicos)){
				//echo ($s->descricao). " - ". $s->valor ." <br>";

				$ValorG += $s->valor;
				$Vtotal += $s->valor;	
				
				$desconto += $s->valor_desconto;
				
				
				$descUnific[] = ($s->descricao). " no valor de R$ ".$s->valor." R$ ".$s->valor_desconto." ";	

			}

			
			//echo "Total: ".$Vtotal."<br>";
			//echo "<hr>";


			if($c->envio=='1'){			
				foreach ($descUnific as $descSeparado) {

					$trataInfo = explode("R$",$descSeparado);
					$trataInfo1 = $trataInfo[0]." R$ ".$trataInfo[1];

					$id_asaas = $c->id_asaas;
					$billingType = $c->billingType;
					$dueDate = date('Y-m-').$c->dia_vencimento; //Data de vencimento da cobrança
					$value = $trataInfo[1]; //Valor da cobrança
					$description = $trataInfo1; //Descrição da cobrança
					$externalReference = "x"; //Campo livre para busca
					$discountValue = $trataInfo[2]; //Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
					$dueDateLimitDays = 0; //Dias antes do vencimento para aplicar desconto. Ex: 0 = até o vencimento, 1 = até um dia antes, 2 = até dois dias antes, e assim por diante
					$fineValue = 1; //Percentual de multa sobre o valor da cobrança para pagamento após o vencimento
					$interestValue = 1; //Percentual de juros ao mês sobre o valor da cobrança para pagamento após o vencimento			

					GerarFatura($conexao,$ip,$porta,$token,$conexao2,$c->id,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$c->celular,$tituloWhat,$descricaoWhat,$imgWhat,$id_user,$nomeCliente,$c->email,$c->emails);

					//echo "Total em 1: ".$trataInfo[1]."<br>";
					//echo "<hr>";
				}
			}else{

				$id_asaas = $c->id_asaas;
				$billingType = $c->billingType;
				$dueDate = date('Y-m-').$c->dia_vencimento; //Data de vencimento da cobrança
				$value = $Vtotal; //Valor da cobrança
				$description = implode(", ",$descUnific); //Descrição da cobrança
				$externalReference = "x"; //Campo livre para busca
				$discountValue = $desconto; //Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
				$dueDateLimitDays = 0; //Dias antes do vencimento para aplicar desconto. Ex: 0 = até o vencimento, 1 = até um dia antes, 2 = até dois dias antes, e assim por diante
				$fineValue = 1; //Percentual de multa sobre o valor da cobrança para pagamento após o vencimento
				$interestValue = 1; //Percentual de juros ao mês sobre o valor da cobrança para pagamento após o vencimento	

				GerarFatura($conexao,$ip,$porta,$token,$conexao2,$c->id,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$c->celular,$tituloWhat,$descricaoWhat,$imgWhat,$id_user,$nomeCliente,$c->email,$c->emails);


				//echo "Total em else: ".$Vtotal."<br>";

				//echo "<hr>";
			}	   



		}	
	
	
	
}




function GerarFatura($conexao,$ip,$porta,$token,$conexao2,$id_user,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$celss,$tituloWhat,$descricaoWhat,$imgWhat,$idUser,$nome,$emailDb,$emails){
	
/*	echo 
	$ip."<br>".
	$porta."<br>".
	$token."<br>".
	$id_user."<br>".	
	$api_token."<br>".
	$id_asaas."<br>".
	$billingType."<br>".
	$dueDate."<br>".
	$value."<br>".
	$description."<br>".
	$externalReference."<br>".
	$discountValue."<br>".
	$dueDateLimitDays,
	$fineValue."<br>".
	$interestValue."<br>".
	$tituloWhat."<br>".
	$descricaoWhat."<br>".
	$imgWhat."<br>".	
	"Cell: ".$celss."<br>";*/
	
	
	$fields = array
	( 
		'customer' => $id_asaas,
		'billingType' => $billingType,
		'dueDate' => $dueDate,
		'value' => $value,
		'description' => $description,
		'externalReference' => $externalReference,
		'discount' =>
			array
			(
			'value' => $discountValue,
			'dueDateLimitDays' => $dueDateLimitDays,
			'type' => 'FIXED',	
			),
		'fine' =>
			array
			(
			'value' => $fineValue,	
			),
		'interest' =>
			array
			(
			'value' => $interestValue,	
			),
		'postalService' => false,
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);	
	
	
	//var_dump($response); comente
	$obj = json_decode($response);
	
	$insert = " insert into cobrancas set
				object = '".$obj->object."',
				id = '".$obj->id."',
				dateCreated = '".$obj->dateCreated."',
				customer = '".$obj->customer."',
				value = '".$obj->value."',
				netValue = '".$obj->netValue."',
				originalValue = '".$obj->originalValue."',
				interestValue = '".$obj->interestValue."',
				description = '".($obj->description)."',
				billingType = '".$obj->billingType."',
				status = '".$obj->status."',
				dueDate = '".$obj->dueDate."',
				originalDueDate = '".$obj->originalDueDate."',
				paymentDate = '".$obj->paymentDate."',
				clientPaymentDate = '".$obj->clientPaymentDate."',
				invoiceUrl = '".$obj->invoiceUrl."',
				invoiceNumber = '".$obj->invoiceNumber."',
				externalReference = '".$obj->externalReference."',
				deleted = '".$obj->deleted."',
				anticipated = '".$obj->anticipated."',
				creditDate = '".$obj->creditDate."',
				estimatedCreditDate = '".$obj->estimatedCreditDate."',
				bankSlipUrl = '".$obj->bankSlipUrl."',
				lastInvoiceViewedDate = '".$obj->lastInvoiceViewedDate."',
				lastBankSlipViewedDate = '".$obj->lastBankSlipViewedDate."'
	";
	$Eviado = mysqli_query($conexao2,$insert);	
	

	$cel = explode(" ",$celss);
	$cel2 = str_replace("-","",$cel[3]);
	$cel1 = $cel[0]."".$cel[1].$cel2; //Sem o 9
	$cel2 = $cel[0]."".$cel[1].$cel[2].$cel2; //Com o 9
	
	//$celular = $cel1.",".$cel2;
	
	if($cel[1]=='92' || $cel[1]=='51' || $cel[1]=='75' || $cel[1]=='63' || $cel[1]=='41'){
		$celular = $cel1;
	}else{
		$celular = $cel2;
	}

	 
	$link = $obj->bankSlipUrl;
	$link2 = $obj->invoiceUrl;
	
	 
	//$description = "Teste integracao"; //comente
	//$link = 'https://elmenoufi.com.br'; //comente
	//$link2 = 'https://elmenoufi.com.br'; //comente
		
	$titulo = "Boleto de cobrança";
	$descBole = "Sistema business T M Elmenoufi";	
	
	//Atualização
	$dataCriada = $obj->dateCreated;
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
	$valorCobrado = " R$ ". number_format($obj->value,2,",",".");
	$Vn = explode("-",$obj->dueDate); ;
	$vencimento = $Vn[2]."/".$Vn[1]."/".$Vn[0];		
	//--------------------------
	
	if($Eviado){
		$mesSeginte = date("Y-m",strtotime("+1 month"));
		//echo "<br>"."Mes seguinte ". $mesSeginte ."<br>"; comente
		EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat,$referente,$valorCobrado,$vencimento);
	    mysqli_query($conexao2," update clientes set ano_mes = '".$mesSeginte."' where id='".$id_user."' ");
		
		list($logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado) = mysqli_fetch_row(mysqli_query($conexao, "select logo, empresa, cpf_cnpj, site, email, contato, endereco, cep, cidade, estado from cobrancas_empresa where id_usuario = '$idUser' "));
		if($email){
			SendEmail($nome, $obj->value, $obj->dueDate, $description, $obj->invoiceUrl, $emailDb, $emails, $logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado);
		}		
		
	}
	
	//EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat);
	
}

function EnviarWhatsapp($ip,$porta,$token,$celular,$description,$link,$link2,$titulo,$descBole,$tituloWhat,$descricaoWhat,$imgWhat,$referente,$valorCobrado,$vencimento){
	
$tituloFx = "Olá obrigado por utilizar os nossos serviços!\n*Boleto de cobrança*";
$descBoleFx = "Sistema business T M Elmenoufi";	
	
$tituloDb = ($tituloWhat);
$descBoleDb = ($descricaoWhat);

$descricaoServerco = ($description);	
	

$mensagens  = (($tituloDb)?$tituloDb:$tituloFx)."\n".(($descBoleDb)?$descBoleDb:$descBoleFx)."\n";	
$mensagens .= "*Referente*: a ".$referente."\n";	
$mensagens .= "*Descrição*: ".$descricaoServerco."\n";	
$mensagens .= "*Vencimento*: ".$vencimento."\n";	
$mensagens .= "*Valor*: ".$valorCobrado."\n\n";	
$mensagens .= "*".$link2."*";	
	
	
$tratarCelss = explode(",",$celular);	
	
//echo $celular;		
//exit;	
	
$numeros = $tratarCelss;	
	
$authorization = "Bearer $token";
	
$fields = array
(
	'mensagem' => $mensagens ,
	'numbers' => $numeros
);
	
$headers = array
(
'Content-Type: application/json',	
'Authorization: ' . $authorization

);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/text' );
//url_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/link' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );

//echo json_encode( $fields ) . "<br>";
//echo $result;	
	
	
	echo "<script>parent.Enviado();</script>";
}




function SendEmail($nome, $value, $dueDate, $description, $invoiceUrl, $emailSend, $emails, $logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado){
    // Montagem do assunto e do corpo em HTML
    $infoEmail   = "Olá, sua cobrança";
    $SubjectRaw  = $empresa;
    $html  = '<img src="'. $logo .'" alt="Logo"><br>';
    $html .= '<strong style="font-size:20pt;">'. $empresa .'</strong><br><br>';
    $html .= '<strong style="font-size:16pt;">Olá, '. htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') .'</strong><br><br>';
    $html .= 'Lembramos que a sua cobrança gerada por <strong>'. $empresa .'</strong> no valor de <strong>R$ '. number_format($value,2,",",".") .'</strong> vence em <strong>'. date('d/m/Y', strtotime($dueDate)) .'</strong>.<br>';
    $html .= 'Descrição da cobrança: '. htmlspecialchars($description, ENT_QUOTES, 'UTF-8') .'<br><br>';
    $html .= '<a href="'. $invoiceUrl .'"><button style="padding:10px 20px;border:none;border-radius:4px;cursor:pointer;">Visualizar cobrança</button></a><br><br>';
    $html .= 'Ou acesse: <a href="'. $invoiceUrl .'">'. $invoiceUrl .'</a><br><br>';
    $html .= 'Atenciosamente,<br>';
    $html .= $empresa .'<br>';
    $html .= $cpf_cnpj .'<br>';
    $html .= $site .'<br>';
    $html .= $email .'<br>';
    $html .= $contato .'<br>';
    $html .= $endereco .'<br>';
    $html .= 'CEP: '. $cep .'<br>';
    $html .= $cidade .'-'. $estado .'<br>';

    // Inicializa o PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'elmenoufinegocios@gmail.com';
        $mail->Password   = 'juihsnwughykliky';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // ** Charset e codificação de assunto **
        $mail->CharSet    = 'UTF-8';
        $mail->Subject    = '=?UTF-8?B?'. base64_encode($SubjectRaw) . '?=';

        // Remetente e destinatários
        $mail->setFrom('elmenoufinegocios@gmail.com', $infoEmail);
        $mail->addAddress(trim($emailSend), $nome);
        foreach (explode(',', $emails) as $emailLoop) {
            $mail->addAddress(trim($emailLoop), $nome);
        }

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Body    = $html;
        $mail->AltBody = strip_tags($html);

        // Envio
        $mail->send();
        echo "Enviado com sucesso!";
    } catch (Exception $e) {
        echo "Erro ao enviar: " . $mail->ErrorInfo;
    }
}

?>