<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida 1</center>");

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



$pergunta = "select * from usuarios WHERE status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);

while ($d = mysqli_fetch_object($resultado)) {



    list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '" . $d->id . "' "));
    list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '" . $CodEstado . "' "));
    $timezone = $timezones[$sigla];

    echo $d->id . "<br>";

    dispararSms($conexao, $d->id, $timezone, $d->nome, $d->email, $d->token, $d->status_whats_desc, $ip, $porta);
}


function dispararSms($conexao, $cliente, $timezone, $nome, $email, $token, $status_whats_desc, $ip, $porta)
{

    $conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_" . $cliente) or die("<center>Autenticação invalida 2!</center>");


    date_default_timezone_set($timezone);

    $pergunta2 = "select * from agenda where status = '0' ";
    $resultado2 = mysqli_query($conexao2, $pergunta2);


    while ($d = mysqli_fetch_object($resultado2)) {


        $data1 = $d->data . ' ' . $d->hora;
        $data2 = date("Y-m-d H:i:s");

        $x_horas        = $d->x_horas;
        $repetir_dia    = $d->repetir_dia;
        $repetir_horas  = $d->repetir_horas;
        $repetir_semana = $d->repetir_semana;
        $repetir_mes    = $d->repetir_mes;


        // "Data1 ".$data1."<br>";
        //echo "Data2 ".$data2."<br>";	

        if (strtotime($data1) > strtotime($data2)) {
            //echo 'A data 1 é maior que a data 2.<br>';
        } elseif (strtotime($data1) == strtotime($data2)) {
            //echo 'A data 1 é igual a data 2.<br>';
            ListaUsuarios($conexao, $conexao2, $d->id, $d->data, $d->compartilhar, ($d->lembrete), $timezone, $token, $status_whats_desc, $ip, $porta, $cliente, $nome, $email, $x_horas, $repetir_dia, $repetir_horas, $repetir_semana, $repetir_mes);
        } else {
            //echo 'A data 1 é menor a data 2.<br>';
            ListaUsuarios($conexao, $conexao2, $d->id, $d->data, $d->compartilhar, ($d->lembrete), $timezone, $token, $status_whats_desc, $ip, $porta, $cliente, $nome, $email, $x_horas, $repetir_dia, $repetir_horas, $repetir_semana, $repetir_mes);
        }
    }
}






function ListaUsuarios($conexao, $conexao2, $codigo, $data, $grupos, $mensagem, $timezone, $token, $status_whats_desc, $ip, $porta, $cliente, $nome, $email, $x_horas, $repetir_dia, $repetir_horas, $repetir_semana, $repetir_mes)
{

    date_default_timezone_set($timezone);

    //echo "<br> Compartilhar com: ".$grupos."<br>";

    $categoria = $grupos;

    list($totalNumeros) = mysqli_fetch_row(mysqli_query($conexao2, "select count(telefone) from contatos_agenda where id in (" . $categoria . ")  "));

    $pergunta = "select * from contatos_agenda where id in (" . $categoria . ")  ";
    $resultado = mysqli_query($conexao2, $pergunta);

    $numeros = "";
    $w = 0;
    while ($d = mysqli_fetch_object($resultado)) {

        $Nome = ($d->nome);

        $correlationId = "tme_" . date("YmdHis", time());

        if ($d->cod_estado == '92' or $d->cod_estado == '85' or $d->cod_estado == '82') {
            $tira9 = substr($d->telefone, 1);
            $cells = $d->cod_pais . '' . $d->cod_estado . '' . $tira9;
        } else {
            $tira9 = substr($d->telefone, 1);
            $cells = $d->cod_pais . '' . $d->cod_estado . '' . $tira9;
        }

        //echo "<br> $status_whats_desc : | $Nome | ".$cells." | ".$mensagem."<br>";
        $w++;

        if ($status_whats_desc == "TIMEOUT") {
            timeout($conexao, $cliente, $status_whats_desc, $ip, $porta);
        } elseif ($status_whats_desc == "CONNECTED") {
            EnviarWhatsapp($conexao, $conexao2, $codigo, $token, ($mensagem), $cells, $w, $totalNumeros, $ip, $porta);
            EnviarEmail($conexao2, $codigo, $mensagem, $nome, $email, $w, $totalNumeros);
        }
    }
}



function timeout($conexao, $cliente, $status_whats_desc, $ip, $porta)
{

    //echo "status: $status_whats_desc <br>";	
}



function EnviarWhatsapp($conexao, $conexao2, $codigo, $token, $mensagem, $whatsapp, $w, $totalNumeros, $ip, $porta)
{


    $numeros[] = $whatsapp;

    $authorization = "Bearer $token";

    $fields = array(
        'mensagem' => $mensagem . "\n\n@elmenoufi",
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

    echo $result . "<br><br>";

    echo "Codigo=" . $codigo . " e Total=" . $totalNumeros . " e da Vez=" . $w . " e " . $ip . ":" . $porta . "<br>";
}





function EnviarEmail($conexao2, $codigo, $mensagem, $nome, $email, $w, $totalNumeros)
{
    // === Configurações gerais ===
    $smtpConfig = [
        'host'       => 'smtp.gmail.com',
        'port'       => 587,
        'username'   => 'elmenoufinegocios@gmail.com',
        'password'   => 'juihsnwughykliky',
        'encryption' => PHPMailer::ENCRYPTION_STARTTLS, // ou 'tls'
    ];

    $fromAddress = $smtpConfig['username'];
    $fromName    = 'Financeiro - T M Elmenoufi';
    $subjectRaw  = 'Nova agenda';

    // === Montagem do conteúdo ===
    $safeNome    = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
    $safeMensagem = nl2br(htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'));

    // Layout básico de e-mail (pode expandir com mais CSS inline)
    $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{$subjectRaw}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; }
        .header { background-color: #0a6ebd; padding: 20px; color: #fff; text-align: center; }
        .content { padding: 30px; }
        .footer { background-color: #f2f2f2; padding: 10px; font-size: 12px; text-align: center; color: #777; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #0a6ebd; color: #fff; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Olá, {$safeNome}!</h1>
    </div>
    <div class="content">
        <p>{$safeMensagem}</p>
        <a href="#" class="btn">Ver detalhes</a>
    </div>
    <div class="footer">
        <p>Este e-mail foi enviado por T M Elmenoufi. Por favor, não responda diretamente a esta mensagem.</p>
    </div>
</body>
</html>
HTML;

    // Texto alternativo (sem HTML)
    $altBody = strip_tags(str_replace(['</p>', '<br>', '<br/>'], ["\n\n"], $htmlBody));

    // === Enviando com PHPMailer ===
    $mail = new PHPMailer(true);
    try {
        // Servidor SMTP
        $mail->isSMTP();
        $mail->Host       = $smtpConfig['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpConfig['username'];
        $mail->Password   = $smtpConfig['password'];
        $mail->SMTPSecure = $smtpConfig['encryption'];
        $mail->Port       = $smtpConfig['port'];

        // Charset e assunto
        $mail->CharSet    = 'UTF-8';
        $mail->Subject    = "=?UTF-8?B?" . base64_encode($subjectRaw) . "?=";

        // Remetente e destinatário
        $mail->setFrom($fromAddress, $fromName);
        $mail->addAddress(trim($email), $nome);

        // Conteúdo
        $mail->isHTML(true);
        $mail->Body    = $htmlBody;
        $mail->AltBody = $altBody;

        $mail->send();
        echo "E-mail enviado com sucesso para {$safeNome} ({$email})!";
    } catch (Exception $e) {
        echo "Falha ao enviar e-mail: {$mail->ErrorInfo}";
    }


    if ($w >= $totalNumeros) {
        // 1) marca o registro atual como processado
        mysqli_query($conexao2, "UPDATE agenda SET status='1' WHERE id='{$codigo}'");

        // 2) busca todos os dados originais
        $resOrig = mysqli_query($conexao2, "SELECT * FROM agenda WHERE id='{$codigo}'");
        $orig    = mysqli_fetch_assoc($resOrig);   

        // 3) monta timestamp inicial
        $ts = strtotime("{$orig['data']} {$orig['hora']}");

        // 4) aplica a regra de repetição
        if ($orig['repetir_dia']) {
            $ts = strtotime('+1 day',   $ts);
        } elseif ($orig['repetir_semana']) {
            $ts = strtotime('+7 days',  $ts);
        } elseif ($orig['repetir_mes']) {
            $ts = strtotime('+1 month', $ts);
        } elseif ($orig['repetir_horas']) {
            $ts = strtotime("+{$orig['x_horas']} days", $ts);
        } else {
            // não há repetição configurada
            return;
        }

        // 5) formata nova data e hora
        $novaData = date('Y-m-d',   $ts);
        $novaHora = date('H:i:s',   $ts);

        // 6) escapa os campos de texto
        $lembrete     = mysqli_real_escape_string($conexao2, $orig['lembrete']);
        $compartilhar = mysqli_real_escape_string($conexao2, $orig['compartilhar']);

        // 7) prepara flags e x_horas
        $repDia    = (int)$orig['repetir_dia'];
        $repHoras  = (int)$orig['repetir_horas'];
        $repSemana = (int)$orig['repetir_semana'];
        $repMes    = (int)$orig['repetir_mes'];
        $xHoras    = (int)$orig['x_horas'];

        // 8) insere o novo registro
        $sql = "
        INSERT INTO agenda
            (data, hora, lembrete, compartilhar,
             repetir_dia, repetir_horas, repetir_semana, repetir_mes, x_horas)
        VALUES
            ('{$novaData}', '{$novaHora}', '{$lembrete}', '{$compartilhar}',
             {$repDia}, {$repHoras}, {$repSemana}, {$repMes}, {$xHoras})
        ";
        mysqli_query($conexao2, $sql);
    }

}
