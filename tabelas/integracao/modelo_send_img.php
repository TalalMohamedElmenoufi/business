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

$trat = RetiraEspaco($_FILES['arquivo_img']['name']) ;
$arquivo_img = date('YmdHis')."_".acentos($trat) ;


if($_POST){
	
	$ultimos4 = substr($arquivo_img , -4);

	if($ultimos4 == '.png' or $ultimos4 == '.PNG' or $ultimos4 == '.jpg' or  $ultimos4 == '.JPG' ){
		$img_ext_file = substr($ultimos4, 1);
	}elseif($ultimos4 == 'JPEG' or $ultimos4 == 'jpeg'){
		$img_ext_file = $ultimos4;
	}

	$uploaddir = './upload/';
	move_uploaded_file($_FILES['arquivo_img']['tmp_name'], $uploaddir.$arquivo_img);
	if(($_FILES['arquivo_img']['name'])){
	$img = $arquivo_img;
	$img_ext = $img_ext_file;

	}else{
	$img = '';
	$img_ext = '' ;			
	}
	
	$caminho_img = 'home-elmenoufi-public_html-business-tabelas-integracao-upload'; //cainho abesoluto 'Cada pasta separada por - conforme o exemplo'
	
	$email = $_POST[email];
	$senha = $_POST[senha];
	$numbers = $_POST[numeros]; //Apenas os 11 numeros, retirar o numero '9' exemplo '559284914625,559291725319'
	$mensagem = $_POST[mensagem];
	
	EnviarWhats($email,$senha,$numbers,$mensagem,$caminho_img,$img,$img_ext);
}
 
function EnviarWhats($email,$senha,$numeros,$mensagem,$caminho_img,$img,$img_ext){
	
	$fields = array
	(
			'email' => $email,
			'senha' => $senha,
			'numeros' => $numeros,
			'mensagem' => $mensagem,
			'caminho_img' => $caminho_img,
			'img' => $img,
			'img_ext' => $img_ext
	);

	$headers = array
	(
	'Content-Type: application/json'
	);

	
	echo json_encode( $fields )."<br>";
	
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://elmenoufi.com.br/business/integracao/image.php' );
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

<form action="modelo_send_img.php" method="post" enctype="multipart/form-data" >
	
	<div class="row">
	
	<input type="text" name="email" placeholder="nome@dominio.com.br" class="form-control"/>
	<input type="text" name="senha" placeholder="Sua senha de autenticação" class="form-control"/>
	<input type="text" name="numeros" placeholder="559284914625,559291725319" class="form-control"/>
	<input type="text" name="mensagem" placeholder="HELO WORD API" class="form-control"/>
	
	<div class="col-md-4 col-lg-4">
	
	<label class="btn-bs-file btn btn-info" data-toggle="tooltip" data-placement="top" title="Carregar imagem">
	&nbsp; <b><span id="NomeImg">Imagem</span>
	<input id="arquivo_img" type="file" name="arquivo_img" value="arquivo_img" id="arquivo_img" >
	</label>	
	
	</div>	
	<div class="col-md-4 col-lg-4">	
	<input type="submit" name="Enviar" value="Enviar" class="btn btn-success"/>
	</div>
		
	</div>	
		
</form>