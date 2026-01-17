<?php



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

SendEmail();

function SendEmail(){
    
    $infoEmail = "Olá, sua cobrança";
    $Subject   = 'T M Elmenoufi';

    $html  = '<!DOCTYPE html><html><head>';
    $html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
    $html .= '</head><body>';
    $html  = '<img src="https://www.grupoelmenoufi.com.br/business/?u=sistema" alt="Logotipo">&nbsp;&nbsp;';
    $html .= '<span style="font-weight:700;font-size:20pt;">T M Elmenoufi</span><br><br>';
    $html .= '<span style="font-weight:700;font-size:16pt;">Olá, Talal</span><br><br>';
    $html .= '<span style="font-weight:100;font-size:14pt;">Lembramos que sua cobrança gerada por T M Elmenoufi no valor de <b>R$ 000</b> vence em <b> data</b>.</span><br>';
    $html .= 'Descrição da cobrança: Teste<br><br>';
    $html .= '<span style="font-weight:700;font-size:14pt;">Clique no botão abaixo para visualizar a cobrança:</span><br><br>';
    $html .= '<a href="#" style="text-decoration:none;">';
    $html .= '  <img src="https://www.grupoelmenoufi.com.br/business/img/visualizar_cobranca.png" ';
    $html .= '       alt="Visualizar cobrança" border="0" width="186" height="36" ';
    $html .= '       style="width:186px;height:36px;">';
    $html .= '</a><br><br>';
    $html .= '<span style="font-weight:100;font-size:12pt;">Ou acesse diretamente: <a href="X">X</a></span>';
    $html .= '<br><br><br>';
    $html .= 'Atenciosamente,<br><br>';
    $html .=  'T M Elmenoufi<br>';
    $html .= '0000000000<br>';
    $html .= '<a href="X">X</a><br>';
    $html .= '<a href="mailto:x">@</a><br>';
    $html .= 'contato<br>';
    $html .= 'endereço<br>';
    $html .= 'CEP: 00000000<br>';
    $html .= 'Manaus - AM<br>';
    $html .= '</body></html>';

    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'elmenoufinegocios@gmail.com';
        $mail->Password   = 'juihsnwughykliky';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // configuração de charset e encoding
        $mail->CharSet   = 'UTF-8';
        $mail->Encoding  = PHPMailer::ENCODING_BASE64;

        $mail->setFrom('elmenoufinegocios@gmail.com', $infoEmail);
        $mail->addAddress(trim('talal@elmenoufi.com.br'), 'Talal');


        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = $Subject;
        $mail->Body    = $html;
        $mail->AltBody = mb_convert_encoding(
                             strip_tags(str_replace('<br>', "\n", $html)),
                             'UTF-8', 'HTML-ENTITIES'
                         );

        $mail->send();
        // echo "Enviado com sucesso!";
    } catch (Exception $e) {
        error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
    }
}


