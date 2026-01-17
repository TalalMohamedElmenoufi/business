<div id="RecuperarSenha">
<?php
include("../includes/connect.php");

list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server "));
list($token) = mysqli_fetch_row(mysqli_query($conexao, "select token from usuarios where id = '1' "));

	
if($_POST){
	
	list($nome, $senha) = mysqli_fetch_row(mysqli_query($conexao, "select nome, senha_ver from usuarios where celular = '".$_POST[Reculperacelular]."' "));
	
	$celular = explode(" ",$_POST[Reculperacelular]);
	
	if($celular[1]=='92' or $celular[1]=='85' or $celular[1]=='82'){
		$tira = str_replace("-","",$celular[3]); ;
		$cells = $celular[0].''.$celular[1].''.$tira;
	}else{
		$cells = $celular[0].''.$celular[1].''.$celular[2].''.$tira;
	}	
	
	$whatsapp = $cells  ;
	
	$nomeDb = ($nome);
	
$mensagemPrep = "Ol· $nome tudo bem? 
Obrigado por fazer parte da nossa rede
Sua senha: *$senha*
";
$mensagem = ($mensagemPrep);
	
	EnviarSenha($ip,$porta,$token,$whatsapp,$mensagem);

	
}	

	
function EnviarSenha($ip,$porta,$token,$whatsapp,$mensagem){
	

	$numeros[] = $whatsapp;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagem ,
		'numbers' => $numeros
	);

	$headers = array
	(
	'Content-Type: application/json',	
	'Authorization: ' . $authorization

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/text' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );

	
	echo "<script>Rsenha.close();</script>";
}	
	
?>
<form class="pt-3" id="Reculperar" >
	
<div class="form-group">
<label for="Reculperacelular">Celular</label>
<input type="text" class="form-control" id="Reculperacelular" name="Reculperacelular" placeholder="Seu celular">
</div>	
	
<div class="input-group-append">
  <button type="submit" class="btn btn-sm btn-gradient-primary">Recuperar</button>
</div>
</form>	

<script>

	
$('#Reculperar').validate({
	rules : {
		Reculperacelular : {
			required : true,
			remote: {
				url: "./acoes/checar_celular_reculpera.php",
				type: "post"
			}
		}
	},
	messages : {
		Reculperacelular : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu celular.</div>',
			remote: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Este celular n√£o esta cadastrado.</div>',
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

	$.ajax({
		type: "POST",
		url: "./acoes/recuperar_senha.php",
		data: dados,
		success: function( data )
		{
			$("#RecuperarSenha").html(data);

		}
	});

	return false;
}

});	

$("#Reculperacelular").mask("55 99 9 9999-9999");
	
</script>
</div>