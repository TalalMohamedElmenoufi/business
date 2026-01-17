<div id="SenhaMudar">
<?php
include("../includes/connect.php");

$Script = 'AlterarSenha';
if($_GET[TipoLog]){$_SESSION[$Script][TipoLog] = $_GET[TipoLog];}
if(!$_SESSION[$Script][TipoLog]){$_SESSION[$Script][TipoLog] = $_GET[TipoLog];}	

if($_SESSION[$Script][TipoLog]=="ADM"){
   $tabela = "usuarios";
   $idLogado = $_SESSION[id_usuario];
   $COX = $conexao;	
}
else if($_SESSION[$Script][TipoLog]="USER"){
   $tabela = "login_acesso";
   $idLogado = $_SESSION[id_user];	
   $COX = $conexao2;		
} 	
	
if($_POST){
	
	$senha = password_hash($_POST[senha], PASSWORD_DEFAULT);
	
	mysqli_query($COX, " update ".$tabela." set senha = '$senha', senha_ver = '".$_POST[senha]."' where id = '".$idLogado."' " );
	
	echo "<script> parent.VoltarReculp(); </script>";
	
}	


	
?>
<form class="pt-3" id="Reculperar" >
	
	  <div class="form-group">
		<span class="InfoDesc">Antiga senha:</span>   
		<input type="password" class="form-control form-control-lg" placeholder="Antiga senha" name="senha_antiga" >
	  </div>
	
	  <div class="form-group">
		<span class="InfoDesc">Nova senha:</span>   
		<input type="password" class="form-control form-control-lg" placeholder="Senha" name="senha" id="senha" >
	  </div>
	
	  <div class="form-group">
		<span class="InfoDesc">Repetir a senha:</span>    
		<input type="password" class="form-control form-control-lg" placeholder="Repetir a senha" name="repetir_senha">
	  </div>
	
	  <input type="hidden" id="TipiUser" value="<?=$_SESSION[$Script][TipoLog]?>" />
	
<div class="input-group-append">
  <button type="submit" class="btn btn-sm btn-gradient-success ">Alterar</button>
</div>
</form>	

<script>
	
TipiUser = $("#TipiUser").val();;

if(TipiUser=="ADM"){
   Pasta = "checar_senha";
}
else if(TipiUser="USER"){
   Pasta = "checar_senha_user";
}  		
	

$('#Reculperar').validate({
	rules : { 
		senha_antiga : {
			required : true,
			remote: {
				url: "./acoes/"+Pasta+".php",
				type: "post"
			 }			
		},
		senha : {
			required : true
		},
		repetir_senha : {
			equalTo:"#senha"
		}
	},
	messages : {
		senha_antiga : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a atiga senha.</div>',
			remote: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> A senha antiga não confere.</div>',
		},
		senha : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a nova senha.</div>'
		},
		repetir_senha : {
			equalTo : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> A nova senha não confere.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
	$.ajax({
		type: "POST",
		url: "./acoes/alterar_senha.php",
		data: dados,
		success: function( data )
		{
			$("#SenhaMudar").html(data);

		}
	});

	return false;
}

});	
	
function VoltarReculp(id,senha){
	
	$.confirm({
		title: "",
		content: "Senha alterada com sucesso!",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){
					$("#CARREGANDO").html('');
				}
			},
		}				
	});	
	AlterarSenha.close();
	
}	
	
</script>
</div>