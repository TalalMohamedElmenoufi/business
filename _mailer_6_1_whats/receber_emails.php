<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpImap\Mailbox;

require 'vendor/autoload.php';

// Configurações do servidor de e-mail
$servidor_imap = '{imap.elmenoufi.com.br:993/imap/ssl}INBOX';
$usuario_imap = 'nao-responda@elmenoufi.com.br';
$senha_imap = '3lm3n0uf!2023'; // Substitua pela sua senha IMAP

$servidor_smtp = 'smtp.elmenoufi.com.br';
$usuario_smtp = 'talal@elmenoufi.com.br';
$senha_smtp = '3lm3n0uf!'; // Substitua pela sua senha SMTP

// Configurações do PHPMailer
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = $servidor_smtp;
$mail->SMTPAuth = true;
$mail->Username = $usuario_smtp;
$mail->Password = $senha_smtp;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

try {
    // Conexão IMAP para recebimento de e-mails
    $imap = new Mailbox($servidor_imap, $usuario_imap, $senha_imap);

    // Verifique se a conexão IMAP foi bem-sucedida
    if ($imap->connect()) {
        echo "Conexão bem-sucedida com o servidor IMAP.<br>";

        // Obtenha as mensagens recebidas
        $emails = $imap->searchMailbox('ALL');

        // Processamento das mensagens recebidas
        foreach ($emails as $email) {
            // Obtenha o cabeçalho da mensagem
            $header = $imap->getMailHeader($email);

            // Exiba informações básicas do e-mail
            echo "De: " . $header->fromAddress . "<br>";
            echo "Assunto: " . $header->subject . "<br>";

            // Obtenha o corpo da mensagem
            $body = $imap->getMail($email)->textPlain;

            // Exiba o corpo da mensagem
            echo "Corpo: " . $body . "<br>";
            echo "<hr>";
        }

        // Feche a conexão IMAP
        $imap->disconnect();
    } else {
        echo "Erro ao conectar-se ao servidor IMAP.<br>";
    }
} catch (Exception $e) {
    echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
}
?>
