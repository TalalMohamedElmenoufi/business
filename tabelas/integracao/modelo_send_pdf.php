<title>T M Elmenoufi</title>


<link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../../assets/css/style.css">
<link rel="stylesheet" href="../../assets/css/estilo.css">    
<link rel="shortcut icon" href="../../assets/images/icon.ico" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>  	  
<script type="text/javascript" src="/_libes_tme/bootstrap/4/bootstrap_4_5_0.js" charset="utf-8"></script>	

<style>
.btn-bs-file{
    position:relative;
	width:100%;
}
.btn-bs-file input[type="file"]{
    position: absolute;
    top: -9999999;
    filter: alpha(opacity=0);
    opacity: 0;
    width:0;
    height:0;
    outline: none;
    cursor: inherit;
}

</style>

<?php
function acentos($palavra){
	$acentos = array(
		 'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C');
	$acao = strtr($palavra, $acentos);
	return $acao ;
}

function RetiraEspaco($espaco){
 $acao = str_replace(' ', '_', $espaco);
 return $acao ;
}

$trat2 = RetiraEspaco($_FILES['arquivo_pdf']['name']) ;
$arquivo_pdf = date('YmdHis')."_".acentos($trat2) ;

if($_POST){
	
$ultimosPdf3 = substr($arquivo_pdf , -3);

	if($ultimosPdf3 == 'pdf' or $ultimosPdf3 == 'PDF'){
		$uploaddir2 = './upload/';
		move_uploaded_file($_FILES['arquivo_pdf']['tmp_name'], $uploaddir2.$arquivo_pdf);

		if(($_FILES['arquivo_pdf']['name'])){
		$pdf = $arquivo_pdf;	
		}else{	
		$pdf = '';	
		}

	}
	
	$caminho_pdf = 'home-elmenoufi-public_html-business-tabelas-integracao-upload'; //cainho abesoluto 'Cada pasta separada por - conforme o exemplo'
	
	$email = $_POST[email];
	$senha = $_POST[senha];
	$numbers = $_POST[numeros]; //Apenas os 11 numeros, retirar o numero '9' exemplo '559284914625,559291725319'
	$mensagem = $_POST[mensagem];
	
	EnviarWhats($email,$senha,$numbers,$mensagem,$caminho_pdf,$pdf);
}

function EnviarWhats($email,$senha,$numeros,$mensagem,$caminho_pdf,$pdf){
	
	$fields = array
	(
			'email' => $email,
			'senha' => $senha,
			'numeros' => $numeros,
			'mensagem' => $mensagem,
			'caminho_pdf' => $caminho_pdf,
			'pdf' => $pdf
	);

	$headers = array
	(
	'Content-Type: application/json'
	);

	
	echo json_encode( $fields )."<br>";
	
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://elmenoufi.com.br/business/integracao/pdf.php' );
	curl_setopt( $ch,CURLOPT_GET, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
	$result = curl_exec($ch );
	curl_close( $ch );

	echo $result;
}



?>

<form action="modelo_send_pdf.php" method="post" enctype="multipart/form-data" >
	
	<div class="row">
	
	<input type="text" name="email" placeholder="nome@dominio.com.br" class="form-control"/>
	<input type="text" name="senha" placeholder="Sua senha de autenticação" class="form-control"/>
	<input type="text" name="numeros" placeholder="559284914625,559291725319" class="form-control"/>
	<input type="text" name="mensagem" placeholder="HELO WORD API" class="form-control"/>
	
	<div class="col-md-4 col-lg-4">
	
	<label class="btn-bs-file btn btn-info" data-toggle="tooltip" data-placement="top" title="Carregar imagem">
	&nbsp; <b><span id="NomeImg">PDF</span>
	<input id="arquivo_pdf" type="file" name="arquivo_pdf" value="arquivo_pdf" id="arquivo_pdf" >
	</label>	
	
	</div>	
	<div class="col-md-4 col-lg-4">	
	<input type="submit" name="Enviar" value="Enviar" class="btn btn-success"/>
	</div>
		
	</div>	
		
</form>