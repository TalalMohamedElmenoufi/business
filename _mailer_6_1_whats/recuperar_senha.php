<?php
include("../includes/connect.php");

$pergunta = "select nome, senha_ver from usuarios where email='".$_POST[email]."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = 1;                          // Enable verbose debug output
    $mail->isSMTP();                                 // Send using SMTP
    $mail->Host       = 'elmenoufi.com.br';          // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                        // Enable SMTP authentication
    $mail->Username   = 'cap@captartalentos.com.br'; // SMTP username
    $mail->Password   = 'c@pt@l3nt0s';               // SMTP password

    $mail->SMTPSecure = 'tls'; 						 //tls or ssl
	$mail->Port       = 587; 					     // TCP port to connect to 587  ou 465 
	
	$mail->setFrom('suporte@elmenoufi.com.br', 'Suporte T M Elmenoufi');
	$mail->addAddress($_POST[email], $_POST[email]);

	$info = 'Olá '.$d->nome.' sua senha é: ';
	$senha = $d->senha_ver;
	
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Recuperar Senha';
    $mail->Body    = ($info). ' <b>'.($senha).'</b>';
    $mail->AltBody = ($info). ' <b>'.($senha).'</b>';

    $mail->send();
	$status = 'foi enviada';
	echo("<script>parent.VoltarRecSenha('$_POST[email]','$status');</script>");
} catch (Exception $e) {
	$status = 'Erro no enviado '; //.$mail->ErrorInfo;
	echo("<script>parent.VoltarRecSenha('$_POST[email]','$status');</script>");
}


?>