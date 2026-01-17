<title>T M Elmenoufi</title>


<link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../../assets/css/style.css">
<link rel="stylesheet" href="../../assets/css/estilo.css">    
<link rel="shortcut icon" href="../../assets/images/icon.ico" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>  	  
<script type="text/javascript" src="/_libes_tme/bootstrap/4/bootstrap_4_5_0.js" charset="utf-8"></script>	

<?php
if($_POST){
	$email = $_POST[email];
	$senha = $_POST[senha];
	
	EnviarWhats($email,$senha);
}
 
function EnviarWhats($email,$senha){

	$fields = array
	(
			'email' => $email,
			'senha' => $senha
	);

	$headers = array
	(
	'Content-Type: application/json'
	);

	
	//echo json_encode( $fields )."<br>";
	
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://elmenoufi.com.br/business/integracao/contatos_send_list.php' );
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

<div class="card" >
<div class="card-body">
		
		
<div class="panel-heading">
	<h3>API de integração</h3>
</div>


<form action="modelo_contatos_list.php" method="post" enctype="multipart/form-data" >
	
	<input type="text" name="email" placeholder="nome@dominio.com.br" class="form-control"/>
	<input type="text" name="senha" placeholder="Sua senha de autenticação" class="form-control"/>
	
	<input type="submit" name="Listar" value="Listar" class="btn btn-success"/>
	
</form>
	
</div>	
</div>	