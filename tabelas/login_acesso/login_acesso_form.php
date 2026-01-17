<?php
include("../../includes/connect.php");


$Conf[script] = 'tabelas/login_acesso/login_acesso';
$Script = md5($Conf[script]);


$pergunta = "select * from login_acesso where id='".$_GET[cod]."' ";
$resultado = mysqli_query($conexao2, $pergunta);
$d = mysqli_fetch_object($resultado);

if($_POST){
	
$senha = password_hash($_POST[senha], PASSWORD_DEFAULT);

if(isset($_POST["alterar_".$Script])){

	$query = "update login_acesso set  
	nome='".($_POST[nome])."',
	email='".($_POST[email])."',
	celular='".($_POST[celular])."',
	data_nascimento='".($_POST[data_nascimento])."',
	sexo='".($_POST[sexo])."',
	senha='".$senha."',
	senha_ver='".$_POST[senha]."',
	data_cadastro=NOW()
	where id='".$_POST[cod]."'
	";
	$result = mysqli_query($conexao2, $query);

	echo "<script>parent.retornar_$Script()</script>";
	exit();
	
}
	
	

if(isset($_POST["salvar_".$Script])){	

	$query = "insert into login_acesso set 
	nome='".($_POST[nome])."',
	email='".($_POST[email])."',
	celular='".($_POST[celular])."',
	data_nascimento='".($_POST[data_nascimento])."',
	sexo='".($_POST[sexo])."',
	senha='".$senha."',
	senha_ver='".$_POST[senha]."'
	";
	$result = mysqli_query($conexao2, $query);											
	echo "<script>parent.retornar_$Script()</script>";
	exit();
}	
	
}



?>




<div class="card" >
<div class="card-body">
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>
		
		<form id="login_acesso" action="<?=$Conf[script]?>_form.php" method="post" target="pagina" enctype="multipart/form-data" >

			
		<div class="form-group">
			 <span class="TituloForms">Nome</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm id="nome" type="text" name="nome" class="form-control" placeholder="Nome"  value="<?=($d->nome)?>" /></p>
		</div>		
			
			
		<div class="form-group">
			 <span class="TituloForms">E-mail</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm id="email" type="text" name="email" class="form-control" placeholder="E-mail"  value="<?=($d->email)?>" /></p>
		</div>	
			
		<div class="form-group">
			 <span class="TituloForms">Celular</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm type="text" name="celular"  id="celular" class="form-control" placeholder="Celular"  value="<?=($d->celular)?>" /></p>
		</div>	
			
		<div class="form-group">
			 <span class="TituloForms">Data nascimento</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm type="date" name="data_nascimento"  id="data_nascimento" class="form-control" placeholder="Data nascimento"  value="<?=($d->data_nascimento)?>" /></p>
		</div>
			
		<div class="form-group">
			<span class="TituloForms">Sexo</span><br>			
			<div class="form-check form-check-success">
			<label class="form-check-label">
			<input type="radio" class="form-check-input" name="sexo" id="Masculino"  value="M" <?=(($d->sexo=='M')?'checked':false)?> > Masculino <i class="input-helper"></i></label>
			</div>				
			<div class="form-check form-check-primary">
			<label class="form-check-label">
			<input type="radio" class="form-check-input" name="sexo" id="Feminino" value="F" <?=(($d->sexo=='F')?'checked':false)?> > Feminino <i class="input-helper"></i></label>
			</div>			
		</div>			
              
		<div class="form-group">
			 <span class="TituloForms">Senha</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm type="password" name="senha" id="senha" class="form-control" placeholder="Senha"  value="<?=($d->senha_ver)?>" /></p>
		</div>			
			
			
	 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:1px;margin-top:8px;">

	  <button type="submit" id="salvar" name="<?=(($_GET[op]=='novo') ? 'salvar_'.$Script : 'alterar_'.$Script)?>" class="btn btn-success"><?=(($_GET[op]=='novo') ? 'Salvar' : 'Alterar')?></button>                  

	  <button type="button" id="cancelar_<?=$Script?>" name="cancelar" class="btn btn-danger">Cancelar</button>                  

	 </div>


	  <input type="hidden" id="cod_<?=$Script?>" name="cod" value="<?=$_GET[cod]?>" />
        
        
     </form>	


		
		
</div>
</div>


<script type="text/javascript">

//opção de cancelar
$("#cancelar_<?=$Script?>").click(function(){

	   $("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
		$.ajax({
			url: './<?=$Conf[script]?>.php',
			success: function(data) {
			$('#<?=$_SESSION[$Script][dialog]?>').html(data);
				$("#CARREGANDO").html('');
				$('body>.tooltip').remove();
			}
		});
});

//opção de salvar
function retornar_<?=$Script?>(cod){

	 $.confirm({
		title: "",
		content: "<b>Dados salvo com sucesso!</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){

					$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
						$.ajax({
						url: './<?=$Conf[script]?>.php',
						success: function(data) {
						$('#<?=$_SESSION[$Script][dialog]?>').html(data);
							$("#CARREGANDO").html('');
							$('body>.tooltip').remove();
						}
					}); 					

				}
			},
		}
	  });

}	 
 
	
$('#login_acesso').validate({
	rules : {
		nome : {
			required : true
		},
		email: {
			required: true,
			email: true
		},
		celular : {
			required : true,
			minlength: 17
		}	

	},
	messages : {
		nome : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o nome.</div>'
		},
		email : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu e-mail.</div>',
			email: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Digite um endereço de e-mail valido.</div>'
		},
		celular : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu celular.</div>',
			minlength: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Esta faltando número.</div>',
		}		
	}

});		
	
	
$("#celular").mask('55 99 9 9999-9999');	
</script>