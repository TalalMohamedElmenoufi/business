<?php
error_reporting(0);
function acentos($palavra){
$acentos = array(
     'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C');
$acao = strtr($palavra, $acentos);
	return $acao ;
}

$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("erro na conexao 1");

 
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
'TO' => 'America/Araguaia'
);
/*----------------------------------------------*/

$pergunta = "select * from usuarios where status_whats_desc = 'CONNECTED' ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){

	list($CodEstado) = mysqli_fetch_row(mysqli_query($conexao, "select estado from usuarios where id = '".$d->id."' "));	
	list($sigla) = mysqli_fetch_row(mysqli_query($conexao, "select sigla from estados where cod_estados = '".$CodEstado."' "));
	$timezone = $timezones[$sigla];		
	
	dispararEmail($conexao, $d->id, $timezone, $mail );

}



function dispararEmail($conexao,$cliente,$timezone){
	
	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("erro na conexao 2");		


	$pergunta2 = "select * from t_".date('Y')."_emailAgendamento where status = '0' ";
	$resultado2 = mysqli_query($conexao2, $pergunta2);

	
	while($d = mysqli_fetch_object($resultado2)){
		
		
		$data1 =  $d->data ;
		$data2 = date( "Y-m-d H:i:s", time() );

	    //echo "Timezone ".$timezone."<br>";
		//echo "Data1 ".$data1."<br>";
		//echo "Data2 ".$data2."<br>";	
		
		if(strtotime($data1) > strtotime($data2))
		{
		//echo 'A data 1 é maior que a data 2.<br>';
		}
		elseif(strtotime($data1) == strtotime($data2))
		{
		//echo 'A data 1 é igual a data 2.<br>';
		ListaUsuarios($conexao,$conexao2,$cliente,$d->id,$d->data,$d->grupos,$d->campanha,$d->lote,$d->processados,$timezone);
		}
		else
		{
		//echo 'A data 1 é menor a data 2.<br>';
		ListaUsuarios($conexao,$conexao2,$cliente,$d->id,$d->data,$d->grupos,$d->campanha,$d->lote,$d->processados,$timezone);
		}		
		

	}

}



function ListaUsuarios($conexao,$conexao2,$cliente,$idAgenda,$data,$grupos,$campanha,$lote,$processados,$timezone){

	$pergunta3 = "select a.id as idReg, a.id_grupo, a.nome nomePara, a.email as emailPara, a.campanha, b.nome as nomeDe, b.email as emailDe, b.mensagem from t_".date('mY')."_emailStatuses a 
	left join mailmarketing b on b.id=a.campanha
	where a.enviado='0' limit 0,150 ";
	$resultado3 = mysqli_query($conexao2,$pergunta3);
	while( $d3 = mysqli_fetch_object($resultado3) ){

		//echo $d3->campanha ." e De:".$d3->nomePara ." e De:".$d3->emailDe. " Para: ".$d3->nomeDe."  Para: ".$d3->emailPara."<br>";
		//echo "$cliente,$idAgenda,$data,$grupos,$campanha,$lote,$processados,$timezone<br>";
		
		
		//tratamento da mensagem
		$Tnome = ($d3->nomePara);
		$nome = explode(' ',$Tnome);					
		$trataNome = str_replace('[nome]',$nome[0],$d3->mensagem);
		$EnviarMensagem = str_replace('[email]',$nome[0],$trataNome);	
		//_________________________________	 		
		
		
		//EnvirEmails($conexao,$conexao2,$timezone,$cliente,$idAgenda,$d3->idReg,$d3->id_grupo,$d3->nomeDe,$d3->emailDe,$d3->nomePara,$d3->emailPara,$EnviarMensagem);	//foi pausada			
	}
	
	
	
}


		

function EnvirEmails($conexao,$conexao2,$timezone,$cliente,$idAgenda,$idReg,$id_grupo,$nomeDe,$emailDe,$nomePara,$emailPara,$mensagem){

	date_default_timezone_set($timezone);
	$data = date('Y-m-d H:i:s');
	
	list($MaxId) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id) from t_".date('mY')."_emailStatuses where id_grupo = '".$id_grupo."' "));
	
	
	//echo "MaxId:$MaxId<br>";
	//echo "cliente:$cliente, idAgenda:$idAgenda, id:$idReg, id_grupo:$id_grupo nomeDe:$nomeDe, emailDe:$emailDe, nomePara:$nomePara, emailPara:$emailPara, mensagem:$mensagem<br>";
	
	
	$id = new PHPMailer(true);	
	
		try {
			//Server settings
			//id->SMTPDebug = 1;                            // Enable verbose debug output
			$id->isSMTP();                                  // Send using SMTP
			$id->Host       = 'elmenoufi.com.br';      // Set the SMTP server to send through
			$id->SMTPAuth   = true;                         // Enable SMTP authentication
			$id->Username   = 'marketing@elmenoufi.com.br';  // SMTP username
			$id->Password   = '3lm3n0uf!';                // SMTP password

			$id->SMTPSecure = 'tls';						//tls or ssl
			$id->Port       = 587; 					        // TCP port to connect to 587  ou 465 

			$id->setFrom($emailDe, $nomeDe);
			$id->addAddress($emailPara, $nomePara);

			// Content
			$id->isHTML(true);                               // Set email format to HTML
			$id->Subject = ($emailDe);
			$id->Body    = ($mensagem);
			$id->AltBody = ($emailDe);

			$id->send();
			echo 'Enviado para: '.$emailPara.'<br>';

			mysqli_query($conexao2," update t_".date('mY')."_emailStatuses set enviado = '1', date='".$data."' where id = '".$idReg."' ");
			
			if($MaxId==$idReg){
				mysqli_query($conexao2," update t_".date('Y')."_emailAgendamento set status = '1' where id = '".$idAgenda."' ");	
			}
			
		
		} catch (Exception $e) {
			echo "Error no envio: {$id->ErrorInfo} <br>";

			mysqli_query($conexao2," update t_".date('mY')."_emailStatuses set enviado = '2', log_erro = '".$id->ErrorInfo."', date='".$data."' where id = '".$idReg."' ");

		}	
	
			

	}
	

?>