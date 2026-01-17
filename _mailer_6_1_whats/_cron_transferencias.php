<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

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

	if ($celularDb[1] == '92' or $celularDb[1] == '85' or $celularDb[1] == '82' || $celularDb[1] == '51' || $celularDb[1] == '75' || $celularDb[1] == '63') {
		$tira = str_replace("-", "", $celularDb[3]);;
		$cells = $celularDb[0] . '' . $celularDb[1] . '' . $tira;
	} else {
		$cells = $celularDb[0] . '' . $celularDb[1] . '' . $tira;
		//$cells = $celularDb[0].''.$celularDb[1].''.$celularDb[2].''.$tira; //caso acrecente o 9
	}

	$parts = array_filter([
		$d->email,
		$d->emails   // se vazio, será filtrado pelo array_filter()
	]);
	$emails = implode(',', $parts);

	ListaConexao($conexao2, $d->id, $timezone, $api_token, $d->token, $ip, $porta, $cells, $d->celulares, $d->nome, $emails);
}


function ListaConexao($conexao2, $id_user, $timezone, $api_token, $token, $ip, $porta, $celular, $celulares, $nome, $emails)
{

	$AnoMEs = date("Y-m");
	$transferencias = "select a.* from minhas_transferencias a 
		where 
		a.status='PENDING'

		limit 0,100 ";
	$Rtransferencias = mysqli_query($conexao2, $transferencias);
	while ($c = mysqli_fetch_object($Rtransferencias)) {

		StatusTransferencia($conexao2, $id_user, $timezone, $api_token, $token, $ip, $porta, $c->id, $c->nome, $c->status, $celular, $celulares, $nome, $emails);
	}
}




function StatusTransferencia($conexao2, $id_user, $timezone, $api_token, $token, $ip, $porta, $id_trans, $cliente, $statusDb, $celss, $celulares, $nome, $emails)
{

	$clienteDb = ($cliente);
	$emailsDb = explode(",", $emails);

	$headers = array(
		'Content-Type: application/json',
		'access_token: ' . $api_token

	);

	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/transfers?dateCreated=$data&type=$tipo");
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/transfers?id=$id_trans");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	curl_close($ch);

	//echo $response . "<hr>";

	$json_str = '{"Dados": ' . '[' . $response . ']}';
	$jsonObjTransfer = json_decode($json_str);
	$ListaTransfer = $jsonObjTransfer->Dados;

	foreach ($ListaTransfer as $e) {

		$code = json_encode($e->bankAccount->bank->code);
		$name = json_encode($e->bankAccount->bank->name);
		$accountName = json_encode($e->bankAccount->accountName);
		$ownerName = json_encode($e->bankAccount->ownerName);
		$cpfCnpj = json_encode($e->bankAccount->cpfCnpj);
		$agency = json_encode($e->bankAccount->agency);
		$agencyDigit = json_encode($e->bankAccount->agencyDigit);
		$account = json_encode($e->bankAccount->account);
		$accountDigit = json_encode($e->bankAccount->accountDigit);

		if ($statusDb != $e->status) {
			mysqli_query(
				$conexao2,
				"
				update minhas_transferencias set
				dateCreated = '" . $e->dateCreated . "',
				status = '" . $e->status . "',
				effectiveDate = '" . $e->effectiveDate . "',
				type = '" . $e->type . "',
				value = '" . $e->value . "',
				netValue = '" . $e->netValue . "',
				transferFee = '" . $e->transferFee . "',
				scheduleDate = '" . $e->scheduleDate . "',
				authorized = '" . $e->authorized . "',
				code = '" . $code . "',
				name = '" . ($name) . "',
				accountName = '" . ($accountName) . "',
				ownerName = '" . ($ownerName) . "',
				cpfCnpj = '" . $cpfCnpj . "',
				agency = '" . $agency . "',
				agencyDigit = '" . $agencyDigit . "',
				account = '" . $account . "',
				accountDigit = '" . $accountDigit . "',
				transactionReceiptUrl = '" . $e->transactionReceiptUrl . "'
				where id = '" . $e->id . "'
			   "
			);

			$NomeCona = ($accountName);
			$ownerName = ($ownerName);
			$cpfCnpj = ($cpfCnpj);
			$agencyDb = $agency . " " . $agencyDigit;
			$accountDb = $account . " " . $accountDigit;

			NotificarWhats($ip, $porta, $token, $celss, $celulares, $e->status, $NomeCona, $ownerName, $cpfCnpj, $agencyDb, $accountDb, $e->value);
			EnviarEmail($nome, $emailsDb, $clienteDb, $e->status, $NomeCona, $ownerName, $cpfCnpj, $agency, $account, $e->value);	//foi pausada			
		}
	}
}


function NotificarWhats($ip, $porta, $token, $celss, $celulares, $statusDb, $NomeCona, $ownerName, $cpfCnpj, $agency, $account, $value)
{


	$status = ((($statusDb == 'PENDING') ? 'PENDENTE' : (($statusDb == 'BANK_PROCESSING') ? 'PROCESSAMENTO DE BANCO' : (($statusDb == 'DONE') ? 'FEITA' :

				$statusDb
			))));


	if ($celulares) {
		$todos = $celss . "," . $celulares;
		$numeros = explode(",", $todos);
	} else {
		$numeros[] = $celss;
	}


	$CNPJ = str_replace("\\", "", $cpfCnpj);

	$mensagens  = "*Transferência*:\n";
	$mensagens .= "*Conta AP*: " . $NomeCona . "\n";
	$mensagens .= "*Conta*: " . $ownerName . "\n";
	$mensagens .= "*CPF/CNPJ*: " . $CNPJ . "\n";
	$mensagens .= "*Agencia*: " . $agency . "\n";
	$mensagens .= "*Nº da Conta*: " . $account . "\n";
	$mensagens .= "*No valor de*: " . $value . "\n";
	$mensagens .= "*Com status*: " . $status . "\n";


	$authorization = "Bearer $token";

	$fields = array(
		'mensagem' => $mensagens,
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


	//echo json_encode( $fields ) . "<br>";

	//echo $result."<br>";	

}



function EnviarEmail($nome, $emails, $cliente, $statusDb, $NomeConta, $ownerName, $cpfCnpj, $agency, $account, $value)
{
	// Mapeamento de status
	$status = ($statusDb === 'PENDING' ? 'PENDENTE'
		: ($statusDb === 'BANK_PROCESSING' ? 'EM PROCESSAMENTO BANCÁRIO'
			: ($statusDb === 'DONE' ? 'CONCLUÍDA'
				: $statusDb)));

	// Codificação MIME
	$infoEmail = mb_encode_mimeheader("Olá, sua transferência", "UTF-8", "B");
	$Subject   = mb_encode_mimeheader("Status: $status", "UTF-8", "B");
	$nomeEnc   = mb_encode_mimeheader($nome, "UTF-8", "B");

	// Layout HTML aprimorado
	$html = '
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Notificação de Transferência</title>
    </head>
    <body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding:20px 0;">
      <tr>
        <td align="center">
          <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden;">
            
            <!-- Header -->
            <tr>
              <td style="background-color:#4A90E2; text-align:center; padding:20px;">
                <h1 style="color:#ffffff; margin:0; font-size:24px;">Notificação de Transferência</h1>
              </td>
            </tr>
            
            <!-- Saudação -->
            <tr>
              <td style="padding:20px;">
                <p style="font-size:16px; margin:0 0 10px;">Olá, <strong>' . htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') . '</strong>,</p>
                <p style="font-size:14px; line-height:1.6; margin:0 0 20px;">
                  Segue o detalhamento da sua transferência solicitada.
                </p>
              </td>
            </tr>
            
            <!-- Detalhes da Transferência -->
            <tr>
              <td style="padding:0 20px 20px;">
                <table width="100%" cellpadding="5" cellspacing="0" style="border:1px solid #e0e0e0; border-radius:4px;">
                  <tr style="background:#fafafa;">
                    <td style="font-weight:700; width:35%;">Cliente</td>
                    <td>' . htmlspecialchars($cliente, ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>
                  <tr>
                    <td style="font-weight:700;">Conta Origem</td>
                    <td>' . htmlspecialchars($NomeConta, ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>
                  <tr style="background:#fafafa;">
                    <td style="font-weight:700;">Titular</td>
                    <td>' . htmlspecialchars($ownerName, ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>
                  <tr>
                    <td style="font-weight:700;">CPF/CNPJ</td>
                    <td>' . htmlspecialchars($cpfCnpj, ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>
                  <tr style="background:#fafafa;">
                    <td style="font-weight:700;">Agência</td>
                    <td>' . htmlspecialchars($agency, ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>
                  <tr>
                    <td style="font-weight:700;">Conta</td>
                    <td>' . htmlspecialchars($account, ENT_QUOTES, 'UTF-8') . '</td>
                  </tr>
                  <tr style="background:#fafafa;">
                    <td style="font-weight:700;">Valor</td>
                    <td>R$ ' . number_format($value, 2, ',', '.') . '</td>
                  </tr>
                  <tr>
                    <td style="font-weight:700;">Status</td>
                    <td>' . $status . '</td>
                  </tr>
                </table>
              </td>
            </tr>
            
            <!-- Rodapé -->
            <tr>
              <td style="background:#f9f9f9; text-align:center; padding:15px; font-size:12px; color:#777;">
                Este é um e-mail automático. Por favor, não responda diretamente a esta mensagem.<br>
                &copy; ' . date('Y') . ' Neuron Health Tech – Todos os direitos reservados.
              </td>
            </tr>

          </table>
        </td>
      </tr>
    </table>

    </body>
    </html>';

	// Configuração do PHPMailer
	$mail = new PHPMailer(true);
	try {
		$mail->isSMTP();
		$mail->Host       = 'smtp.gmail.com';
		$mail->SMTPAuth   = true;
		$mail->Username   = 'elmenoufinegocios@gmail.com';
		$mail->Password   = 'juihsnwughykliky';
		$mail->SMTPSecure = 'tls';
		$mail->Port       = 587;

		$mail->CharSet   = 'UTF-8';
		$mail->Encoding  = PHPMailer::ENCODING_BASE64;

		$mail->setFrom('elmenoufinegocios@gmail.com', $infoEmail);
		foreach ($emails as $emailist) {
			$mail->addAddress($emailist, $nomeEnc);
		}

		$mail->isHTML(true);
		$mail->Subject = $Subject;
		$mail->Body    = $html;
		$mail->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $html));

		$mail->send();
	} catch (Exception $e) {
		error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
	}
}
