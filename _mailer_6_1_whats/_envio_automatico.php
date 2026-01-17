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



$pergunta = "select * from usuarios where status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);
while ($d = mysqli_fetch_object($resultado)){
	
	
	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];	

	date_default_timezone_set($timezone);


	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$d->id);

	list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));	

	list($api_token_cliente) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '".$d->id."' and status = 'Liberado' "));	

	if($api_token_dono==$api_token_cliente){
		$api_token = $api_token_dono ;
	}else{
		$api_token = $api_token_cliente ;	
	}	

	list($tituloWhat,$descricaoWhat,$imgWhat) = mysqli_fetch_row(mysqli_query($conexao2, "select titulo,descricao,img from whats_config "));

	ListaConexao($conexao,$conexao2,$d->id,$timezone,$api_token,$d->token,$ip,$porta,$tituloWhat,$descricaoWhat,$imgWhat);	

}









function ListaConexao($conexao,$conexao2,$id_user,$timezone,$api_token,$token,$ip,$porta,$tituloWhat,$descricaoWhat,$imgWhat){

		$mesAtual = date("Y-m");
	
		$cobrancas = "select * from clientes where gerar = '1' and ativo = '0' order by dia_vencimento ";
		$Rcobranca = mysqli_query($conexao2,$cobrancas);

		//echo $cobrancas."<br>";
	
		$ValorG = "";
		while ($c = mysqli_fetch_object($Rcobranca)){

			
		$nomeCliente = ($c->nome);
			
		$data1 = date("Y-m");
		$data2 = $c->ano_mes  ;
		
		$seDia = (($c->dia_vencimento)?$c->dia_vencimento:'10');	
		
		$dataHoje = date('Y-m-d H:i:s');	
		$dataVencimento = $data1.'-'. (($seDia<10)?'0'.$seDia:$seDia) . ' 09:00:00' ;
		$diaDisparo = date('Y-m-d H:i:s', strtotime($dataVencimento. ' - 5 days'));	
		$W = date( 'w' ) % 6 ? 'Não é fim de semana' : 'é fim de semana';
		$fimDeSemana = date( 'w' ) % 6;	
		
		//echo "$dataHoje e $diaDisparo <br>";	
			
			
			
	    
			
			
		if(strtotime($data1) >= strtotime($data2)){
			
			
			list($dateCreated) = mysqli_fetch_row(mysqli_query($conexao2, "select dateCreated from cobrancas where customer = '".$c->id_asaas."' and dateCreated like '%$mesAtual%' "));
			


			
			if(!$dateCreated){

				
				$fimDeSemana = new DateTime($diaDisparo);
				if($fimDeSemana->format('w')=='0'){//domingo	
					//echo "<b>adiciona 1 dia</b> [".$fimDeSemana->format('w')."]  <br>";
					$dataDisparo = date('Y-m-d H:i:s', strtotime($diaDisparo. ' +1 days'));	
				}
				elseif($fimDeSemana->format('w')=='6'){//sabado
					//echo "<b>adiciona 2 dia</b> [".$fimDeSemana->format('w')."] <br>";
					$dataDisparo = date('Y-m-d H:i:s', strtotime($diaDisparo. ' +2 days'));	
				}else{
					$dataDisparo = $diaDisparo;
				}				
				
				
				//echo 'A data SIS('.$data1.') é maior que a data DB('.$data2.').<br>';
				//echo 'W '.$W.'<br>';	
							
				/*echo 'ID USER: '.$id_user.'<br>';
				echo 'Cliente: '.$nomeCliente.' CB: '.$dateCreated.'  <br>';
				echo 'Data vencimento <span style="color:red">'.$dataVencimento.'</span>';
				echo '<br>';
				echo 'Data hoje <span style="color:blue">'.$dataHoje.'</span><br>';
				echo 'Data Disparo <span style="color:green">'.$diaDisparo.'</span><br>';
				echo 'Data Disparo sem fim de semana <span style="color:green">'.$dataDisparo.'</span><br>';
				 */


				//echo "nomeCliente: $nomeCliente - dataHoje: $dataHoje e dataDisparo: $dataDisparo  <hr> ";	
				
			}
				
			if(strtotime($dataHoje) >= strtotime($dataDisparo)){
				if(!$dateCreated){echo '<span style="color:green">Disparar</span><br>';}
				if($fimDeSemana){
					//echo 'Não é fim de semana<br>';
					
					if(!$dateCreated){
						
					/*-------------------------------------------------------------------*/
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

						$descUnific[] = ($s->descricao). " no valor de R$".$s->valor." R$".$s->valor_desconto." ";	

					} 


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

							GerarFatura($conexao,$ip,$porta,$token,$conexao2,$c->id,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$c->celular,$tituloWhat,$descricaoWhat,$imgWhat,$id_user,$nomeCliente,$c->email,$c->emails,$c->frequencia);

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

						GerarFatura($conexao,$ip,$porta,$token,$conexao2,$c->id,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$c->celular,$tituloWhat,$descricaoWhat,$imgWhat,$id_user,$nomeCliente,$c->email,$c->emails,$c->frequencia);

					}	   


					//echo "$id_user|$c->id,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$c->celular,$tituloWhat,$descricaoWhat,$imgWhat<hr>";			



					}/*else{
						echo 'A data SIS('.$data1.') é menor que a data DB('.$data2.').<br>';	
					}*/	
					/*-------------------------------------------------------------------*/	
						
						
					}
					//echo "<hr>";

					
					
					
				}else{
					//echo 'é fim de semana<br>';
					//echo "<hr>";
				}
			}else{
				//echo '<span style="color:red">Não Disparar</span><br>';
			}
			
			
			
	
			
		}	
	
	
	
}




function GerarFatura($conexao,$ip,$porta,$token,$conexao2,$id_user,$api_token,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue,$celss,$tituloWhat,$descricaoWhat,$imgWhat,$idUser,$nome,$emailDb,$emails,$frequencia){
	
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
	
	if($cel[1]=='92' || $cel[1]=='51' || $cel[1]=='75' || $cel[1]=='63' || $cel[1]=='41'){
		$celular = $cel1;
	}else{
		$celular = $cel2;
	}
	 
	$link = $obj->bankSlipUrl;
	$link2 = $obj->invoiceUrl;
		
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
		
		$numF = (
		($frequencia=="Mensal")?1:
		(
		($frequencia=="Trimestral")?3:
		(
		($frequencia=="Semestral")?6:
		(
		($frequencia=="Anual")?12:1)
		)
		)
		);
		
		$mesSeginte = date("Y-m",strtotime("+$numF month"));
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
//curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/link' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );

//echo json_encode( $fields ) . "<br>";
//echo $result;	

}




function SendEmail($nome, $value, $dueDate, $description, $invoiceUrl, $emailSend, $emails, $logo, $empresa, $cpf_cnpj, $site, $email, $contato, $endereco, $cep, $cidade, $estado){
    // prepara assunto e nome com codificação MIME em UTF-8
    $infoEmail = mb_encode_mimeheader("Olá, sua cobrança", "UTF-8", "B");
    $Subject   = mb_encode_mimeheader($empresa,         "UTF-8", "B");
    $nomeEnc   = mb_encode_mimeheader($nome,            "UTF-8", "B");

    $html  = '<!DOCTYPE html><html><head>';
    $html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
    $html .= '</head><body>';
    $html .= '<img src="'.$logo.'" alt="Logotipo">&nbsp;&nbsp;';
    $html .= '<span style="font-weight:700;font-size:20pt;">'.$empresa.'</span><br><br>';
    $html .= '<span style="font-weight:700;font-size:16pt;">Olá, '.$nome.'</span><br><br>';
    $html .= '<span style="font-weight:100;font-size:14pt;">Lembramos que sua cobrança gerada por '.$empresa.' no valor de <b>R$ '.number_format($value,2,",",".").'</b> vence em <b>'.dataBr($dueDate).'</b>.</span><br>';
    $html .= 'Descrição da cobrança: '.$description.'<br><br>';
    $html .= '<span style="font-weight:700;font-size:14pt;">Clique no botão abaixo para visualizar a cobrança:</span><br><br>';
    $html .= '<a href="'.$invoiceUrl.'" style="text-decoration:none;">';
    $html .= '  <img src="https://www.grupoelmenoufi.com.br/business/img/visualizar_cobranca.png" ';
    $html .= '       alt="Visualizar cobrança" border="0" width="186" height="36" ';
    $html .= '       style="width:186px;height:36px;">';
    $html .= '</a><br><br>';
    $html .= '<span style="font-weight:100;font-size:12pt;">Ou acesse diretamente: <a href="'.$invoiceUrl.'">'.$invoiceUrl.'</a></span>';
    $html .= '<br><br><br>';
    $html .= 'Atenciosamente,<br><br>';
    $html .= $empresa.'<br>';
    $html .= $cpf_cnpj.'<br>';
    $html .= '<a href="'.$site.'">'.$site.'</a><br>';
    $html .= '<a href="mailto:'.$email.'">'.$email.'</a><br>';
    $html .= $contato.'<br>';
    $html .= $endereco.'<br>';
    $html .= 'CEP: '.$cep.'<br>';
    $html .= $cidade.' - '.$estado.'<br>';
    $html .= '</body></html>';

    $mail = new PHPMailer(true);

    try {
        // servidor
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

        // remetente e destinatários
        $mail->setFrom('elmenoufinegocios@gmail.com', $infoEmail);
        $mail->addAddress(trim($emailSend), $nomeEnc);

        foreach (explode(',', $emails) as $emailLoop) {
            $mail->addAddress(trim($emailLoop), $nomeEnc);
        }

        // conteúdo
        $mail->isHTML(true);
        $mail->Subject = $Subject;
        $mail->Body    = $html;
        $mail->AltBody = mb_convert_encoding(
                             strip_tags(str_replace('<br>', "\n", $html)),
                             'UTF-8', 'HTML-ENTITIES'
                         );

        $mail->send();
    } catch (Exception $e) {
        error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
    }
}


?>