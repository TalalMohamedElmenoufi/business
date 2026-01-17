<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = 1;                          // Enable verbose debug output
    $mail->isSMTP();                                 // Send using SMTP
    $mail->Host       = 'elmenoufi.com.br';          // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                        // Enable SMTP authentication
    $mail->Username   = 'marketing@elmenoufi.com.br'; // SMTP username
    $mail->Password   = '3lm3n0uf!';               // SMTP password

    $mail->SMTPSecure = 'tls'; 						 //tls or ssl
	$mail->Port       = 587; 					     // TCP port to connect to 587  ou 465 
	
	$mail->setFrom('talal@elmenoufi.com.br', 'T M Elmenoufi');
    $mail->addAddress('elmenoufinegocios@gmail.com', 'Talal  Gmail');
	$mail->addAddress('talalelmenoufi@hotmail.com', 'Talal  Hotmail');
	$mail->addAddress('talal@acaitc.com.br', 'Talal  Açaí');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Teste T M Elmenoufi';
    $mail->Body    = 'This is the HTML message body <b>Teste 0003</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>