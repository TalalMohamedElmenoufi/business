<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida!</center>");

list($ip, $porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

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
	'TO' => 'America/Araguaia',
	//'DF' => 'America/Brasilia',
);
/*----------------------------------------------*/



$pergunta = "select * from usuarios where token != '' and status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);

while ($d = mysqli_fetch_object($resultado)) {


	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '" . $d->id . "' "));
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '" . $CodEstado . "' "));
	$timezone = $timezones[$sigla];


	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_" . $d->id);

	list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));

	list($api_token_cliente) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '" . $d->id . "' and status = 'Liberado' "));

	if ($api_token_dono == $api_token_cliente) {
		$api_token = $api_token_dono;
	} else {
		$api_token = $api_token_cliente;
	}


	$celularDb = explode(" ", $d->celular);

	if ($celularDb[1] == '92' or $celularDb[1] == '85' or $celularDb[1] == '82' or $celularDb[1] == '51' or $celularDb[1] == '75' or $celularDb[1] == '63') {
		$tira = str_replace("-", "", $celularDb[3]);;
		$cells = $celularDb[0] . '' . $celularDb[1] . '' . $tira;
	} else {
		$cells = $celularDb[0] . '' . $celularDb[1] . '' . $celularDb[2] . '' . $tira; //caso acrecente o 9
	}

	$emails = $d->email . "," . $d->emails;

	ListaConexao($conexao2, $d->id, $timezone, $api_token, $d->token, $ip, $porta, $cells, $d->celulares, $d->nome, $emails);
}


function ListaConexao($conexao2, $id_user, $timezone, $api_token, $token, $ip, $porta, $celular, $celulares, $nome, $emails)
{

	$AnoMEs = date("Y-m");
	$cobrancas = "select a.*, b.nome, b.celular from cobrancas a 
		left join clientes b on b.id_asaas=a.customer
		where 
		a.status='PENDING' or 
		a.status='RECEIVED_IN_CASH' or
		a.status='OVERDUE' or 
		a.status='REFUND_REQUESTED' or 
		a.status='REFUNDED' or 
		a.status='CHARGEBACK_REQUESTED' or 
		a.status='CHARGEBACK_DISPUTE' or 
		a.status='AWAITING_CHARGEBACK_REVERSAL' or 
		a.status='DUNNING_REQUESTED' or 
		a.status='DUNNING_RECEIVED'  or 
		a.status='AWAITING_RISK_ANALYSIS' or 
		a.status='CONFIRMED' or
		a.status=''
		limit 0,100 
		
		";
	$Rcobranca = mysqli_query($conexao2, $cobrancas);

	//echo $cobrancas;

	while ($c = mysqli_fetch_object($Rcobranca)) {

		StatusFatura($conexao2, $id_user, $timezone, $api_token, $token, $ip, $porta, $c->id, $c->nome, $c->status, $celular, $celulares, $nome, $emails);
	}
}




function StatusFatura($conexao2, $id_user, $timezone, $api_token, $token, $ip, $porta, $pay_id, $cliente, $statusDb, $celss, $celulares, $nome, $emails)
{

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments/$pay_id");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"access_token: $api_token"
	));

	$response = curl_exec($ch);
	curl_close($ch);

	$obj = json_decode($response);


	$dueDate = $obj->dueDate;
	$value = $obj->value;
	$netValue = $obj->netValue;
	$originalValue = $obj->originalValue;
	$interestValue = $obj->interestValue;
	$status = $obj->status;
	$description = $obj->description;

	$paymentDate = $obj->paymentDate;
	$clientPaymentDate = $obj->clientPaymentDate;
	$lastInvoiceViewedDate = $obj->lastInvoiceViewedDate;
	$lastBankSlipViewedDate = $obj->lastBankSlipViewedDate;

	/*	echo ($cliente)."<br>";
	echo $status."<br>";
	echo $value."<br>";
	echo $netValue."<br>";
	echo $description."<br>";
	echo $paymentDate."<br>";
	echo $clientPaymentDate."<br>";*/

	$clienteDb = ($cliente);
	$descriptionApi = ($description);

	if ($statusDb != $status) {

		if ($status) {
			//echo "Salvar DB<br>";
			mysqli_query($conexao2, " update cobrancas set  dueDate='" . $dueDate . "',  value='" . $value . "', netValue='" . $netValue . "', originalValue='" . $originalValue . "', interestValue='" . $interestValue . "', paymentDate='" . $paymentDate . "', clientPaymentDate='" . $clientPaymentDate . "', lastInvoiceViewedDate='" . $lastInvoiceViewedDate . "', lastBankSlipViewedDate='" . $lastBankSlipViewedDate . "', status='" . $status . "' where id='" . $pay_id . "' ");
			NotificarWhats($ip, $porta, $token, $descriptionApi, $clienteDb, $celss, $celulares, $status);

			$emailsDb = explode(",", $emails);
			EnviarEmail($nome, $emailsDb, $clienteDb, $descriptionApi, $status);	//foi pausada		
		}
	} else {
		//echo "Não Salvar<br>";

		//mysqli_query($conexao2," update cobrancas set  value='".$value."', netValue='".$netValue."', originalValue='".$originalValue."', interestValue='".$interestValue."', paymentDate='".$paymentDate."', clientPaymentDate='".$clientPaymentDate."', status='".$status."' where id='".$pay_id."' ");
		//NotificarWhats($ip,$porta,$token,$celular,$descriptionApi,$clienteDb,$celss,$celulares,$status);
	}
	//echo "<hr>";
	//var_dump($response);

	//$emailsDb = explode(",",$emails);
	//EnviarEmail($nome,$emailsDb,$clienteDb,$descriptionApi,$status);

}


function NotificarWhats($ip, $porta, $token, $description, $cliente, $celss, $celulares, $statusDb)
{


	$status = ((($statusDb == 'PENDING') ? 'PENDENTE' : (($statusDb == 'CONFIRMED') ? 'CONFIRMADO' : (($statusDb == 'RECEIVED') ? 'RECEBIDO' : (($statusDb == 'RECEIVED_IN_CASH') ? 'RECEBIDO EM DINHEIRO' : (($statusDb == 'OVERDUE') ? 'VENCIDO' : (($statusDb == 'REFUND_REQUESTED') ? 'REEMBOLSO SOLICITADO' : (($statusDb == 'REFUNDED') ? 'DEVOLVEU' : (($statusDb == 'CHARGEBACK_REQUESTED') ? 'COBRANÇA SOLICITADA' : (($statusDb == 'CHARGEBACK_DISPUTE') ? 'RECUPERAR A DISPUTA' : (($statusDb == 'AWAITING_CHARGEBACK_REVERSAL') ? 'AGUARDANDO REVERSÃO DE COBRANÇA' : (($statusDb == 'DUNNING_REQUESTED') ? 'DUNNING SOLICITADO' : (($statusDb == 'DUNNING_RECEIVED') ? 'DUNNING RECEBIDO' : (($statusDb == 'AWAITING_RISK_ANALYSIS') ? 'AGUARDANDO ANÁLISE DE RISCO' :

														''
													))))))))))))));


	if ($celulares) {
		$todos = $celss . "," . $celulares;
		$numeros = explode(",", $todos);
	} else {
		$numeros[] = $celss;
	}



	$mensagemEnviar = "*Cobrança T M Elmenoufi*\n*Cliente:* $cliente\n*Serviço*: $description\n*Com status*: *$status*.";

	$authorization = "Bearer $token";

	$fields = array(
		'mensagem' => $mensagemEnviar,
		'numbers' => $numeros
	);

	$headers = array(
		'Content-Type: application/json',
		'Authorization: ' . $authorization

	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $ip . ':' . $porta . '/send/text');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	curl_close($ch);

	echo $result . "<br>";
}


function EnviarEmail($nome, $emails, $cliente, $description, $statusDb)
{
	// Mapeamento de status
	$map = [
		'PENDING'                     => 'PENDENTE',
		'CONFIRMED'                   => 'CONFIRMADO',
		'RECEIVED'                    => 'RECEBIDO',
		'RECEIVED_IN_CASH'            => 'RECEBIDO EM DINHEIRO',
		'OVERDUE'                     => 'VENCIDO',
		'REFUND_REQUESTED'            => 'REEMBOLSO SOLICITADO',
		'REFUNDED'                    => 'DEVOLVEU',
		'CHARGEBACK_REQUESTED'        => 'COBRANÇA SOLICITADA',
		'CHARGEBACK_DISPUTE'          => 'RECUPERAR A DISPUTA',
		'AWAITING_CHARGEBACK_REVERSAL' => 'AGUARDANDO REVERSÃO DE COBRANÇA',
		'DUNNING_REQUESTED'           => 'DUNNING SOLICITADO',
		'DUNNING_RECEIVED'            => 'DUNNING RECEBIDO',
		'AWAITING_RISK_ANALYSIS'      => 'AGUARDANDO ANÁLISE DE RISCO',
	];
	$status = $map[$statusDb] ?? '';

	// Monta a mensagem em UTF-8
	$mensagemEnviar = "
        <html>
        <head>
            <meta charset=\"UTF-8\">
        </head>
        <body>
            <p>Cobrança T M Elmenoufi</p>
            <p><strong>Cliente:</strong> " . htmlspecialchars($cliente, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Serviço:</strong> " . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Status:</strong> <b>" . $status . "</b></p>
        </body>
        </html>
    ";

	$mail = new PHPMailer(true);

	try {
		// Configurações do servidor
		$mail->isSMTP();
		$mail->Host       = 'smtp.gmail.com';
		$mail->SMTPAuth   = true;
		$mail->Username   = 'elmenoufinegocios@gmail.com';
		$mail->Password   = 'juihsnwughykliky';
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port       = 587;

		// Charset e Encoding
		$mail->CharSet    = 'UTF-8';
		$mail->Encoding   = 'quoted-printable';

		$mail->setFrom('elmenoufinegocios@gmail.com', 'Financeiro - T M Elmenoufi');
		foreach ($emails as $emailist) {
			$mail->addAddress($emailist, $nome);
		}

		// Conteúdo
		$mail->isHTML(true);
		$mail->Subject = 'T M Elmenoufi';
		$mail->Body    = $mensagemEnviar;
		$mail->AltBody = 'E-mail enviado com sucesso!';

		$mail->send();
		//echo 'Mensagem enviada com sucesso.';
	} catch (Exception $e) {
		//echo "Não foi possível enviar o e-mail. Erro: {$mail->ErrorInfo}";
	}
}
