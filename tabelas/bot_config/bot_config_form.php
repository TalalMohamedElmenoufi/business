<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/bot_config/bot_config';
$Script = md5($Conf[script]);

$pergunta = "select * from bot_config where id='".$_GET[cod]."' ";
$resultado = mysqli_query($conexao2, $pergunta);
$d = mysqli_fetch_object($resultado);

if(isset($_POST["salvar_".$Script])){

	$trat1 = RetiraEspaco($_FILES['logo']['name']) ;
	$logo = date('YmdHis')."_".acentos($trat1) ;
	
	$trat2 = RetiraEspaco($_FILES['logo_login']['name']) ;
	$logo_login = date('YmdHis')."_".acentos($trat2) ;
	
	
	$uploaddir = '../../img/logos/';
	move_uploaded_file($_FILES['logo']['tmp_name'], $uploaddir.$logo);
	if($_FILES['logo']['name']){ unlink($uploaddir.$_POST[logo_antiga]); } //para remover foto antiga	

	move_uploaded_file($_FILES['logo_login']['tmp_name'], $uploaddir.$logo_login);
	if($_FILES['logo_login']['name']){ unlink($uploaddir.$_POST[logo_login_antiga]); } //para remover foto antiga	

	$query = "insert into bot_config set
	id_pesquisa='".$_POST[id_pesquisa]."',
	empresa='".($_POST[empresa])."',
	titulo_login='".($_POST[titulo_login])."',
	descricao_login='".($_POST[descricao_login])."',
	logo='".(($_FILES['logo']['name'])?$logo:$_POST[logo_antiga])."',
	logo_login='".(($_FILES['logo_login']['name'])?$logo_login:$_POST[logo_login_antiga])."',
	cor_menu='".$_POST[cor_menu]."'
	";
	$result = mysqli_query($conexao2, $query);		

	mysqli_query($conexao2, "update pesquisa_bot set id_config='".$_POST[cod]."' where id='".$_POST[id_pesquisa]."' ");
	
	echo "<script>parent.retornar_$Script('".$_POST[cod]."')</script>";
	exit();
}


if(isset($_POST["alterar_".$Script])){

	$trat1 = RetiraEspaco($_FILES['logo']['name']) ;
	$logo = date('YmdHis')."_".acentos($trat1) ;
	
	$trat2 = RetiraEspaco($_FILES['logo_login']['name']) ;
	$logo_login = date('YmdHis')."_".acentos($trat2) ;

	$uploaddir = '../../img/logos/';
	move_uploaded_file($_FILES['logo']['tmp_name'], $uploaddir.$logo);
	if($_FILES['logo']['name']){ unlink($uploaddir.$_POST[logo_antiga]); } //para remover foto antiga	

	move_uploaded_file($_FILES['logo_login']['tmp_name'], $uploaddir.$logo_login);
	if($_FILES['logo_login']['name']){ unlink($uploaddir.$_POST[logo_login_antiga]); } //para remover foto antiga	

	$query = "update bot_config set
	id_pesquisa='".$_POST[id_pesquisa]."',
	empresa='".($_POST[empresa])."',
	titulo_login='".($_POST[titulo_login])."',
	descricao_login='".($_POST[descricao_login])."',
	logo='".(($_FILES['logo']['name'])?$logo:$_POST[logo_antiga])."',
	logo_login='".(($_FILES['logo_login']['name'])?$logo_login:$_POST[logo_login_antiga])."',
	cor_menu='".$_POST[cor_menu]."'
	where id='".$_POST[cod]."'
	";
	$result = mysqli_query($conexao2, $query);		

	mysqli_query($conexao2, "update pesquisa_bot set id_config='".$_POST[cod]."' where id='".$_POST[id_pesquisa]."' ");
	
	echo "<script>parent.retornar_$Script('".$_POST[cod]."')</script>";
	exit();	
	
	
}



?>
<style>
	
	.ConfigBot{
		margin-top:20px !important; 
	}	
	
.fieldset-border {
  border: 1px groove #ddd !important;
  padding: 0 0.4em 0.4em 0.4em !important;
  margin: 0 0 1.5em 0 !important;
  -webkit-box-shadow: 0px 0px 0px 0px #000;
  box-shadow: 0px 0px 0px 0px #000;
}

.fieldset-border .legend-border {
  font-size: 1.2em !important;
  text-align: left !important;
  width: auto;
  padding: 0 3px;
  border-bottom: none;
}
	
	label{
		padding: 0 !important ;
		margin-left:1px !important;
	}	
</style>



<div class="card ConfigBot" >
<div class="card-body">
		
		<div class="panel-heading">
			Config menu
		</div>
		
		<form id="FormConfig" action="<?=$Conf[script]?>_form.php" method="post" target="pagina" enctype="multipart/form-data" >
		<div class="panel-body">

			
		<fieldset class="fieldset-border">
		  <legend class="legend-border">CONFIG BOT LOGIN</legend>
			
			<div class="row">
			
			<div class="col-lg-4">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
						<img src="<?=(($d->logo_login)?'./img/logos/'.$d->logo_login:'assets/img/demoUpload.jpg')?>" alt="<?=$d->logo_login?>" />
					</div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
						<span class="btn btn-file btn-primary btn-sm">
							<span class="fileupload-new">Selecione</span>
							<span class="fileupload-exists">Mudar</span>
							<input type="file" name="logo_login" />
						</span>
						<a href="#" class="btn btn-danger fileupload-exists btn-sm" data-dismiss="fileupload">Remover</a>
					</div>
				</div>
			</div>			
			<input type="hidden" name="logo_login_antiga" id="logo_login_antiga" value="<?=(($d->logo_login)?$d->logo_login:false)?>">
			
			<div class="col-lg-8" >
						<span class="TituloForms">Pesquisa</span>
						<p>
							<div class="form-group">
							  <select class="form-control" id="id_pesquisa" name="id_pesquisa">
								<option value="">::Selecione a pesquisa::</option>
								<?php 
								$sql = "select * from pesquisa_bot where id_config='0' or id_config='".$d->id."' " ;
			  					$resl = mysqli_query($conexao2,$sql);
			  					while($d2 = mysqli_fetch_object($resl)){
								?>  
								<option value="<?=$d2->id?>" <?=(($d->id_pesquisa==$d2->id)?'selected':false)?> ><?=$d2->pesquisa?></option>
						        <?php } ?>
							  </select>
							</div>							
					    </p>			
				
				</div>
			
			</div>
			    
			    <div class="row">
			
				<div class="col-lg-12" >
						<span class="TituloForms">Titulo login</span>
						<p><input type='text' name="titulo_login" value="<?=($d->titulo_login)?>" id="titulo_login" class="form-control" placeholder="Titulo login" /></p>			
				</div>
				<div class="col-lg-12">
					<span class="TituloForms">Descrição login</span>
					<textarea name="descricao_login" class="form-control" placeholder="Descrição login" ><?=($d->descricao_login)?></textarea>
				</div>
					
				</div>
			
			</div>
			
			
			
		</fieldset>	
			
		<fieldset class="fieldset-border">
		  <legend class="legend-border">CONFIG CHAT BOT</legend>			
			
			<div class="row">
			<div class="col-lg-4">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
						<img src="<?=(($d->logo)?'./img/logos/'.$d->logo:'assets/img/demoUpload.jpg')?>" alt="<?=$d->logo?>" />
					</div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
						<span class="btn btn-file btn-primary btn-sm">
							<span class="fileupload-new">Selecione</span>
							<span class="fileupload-exists">Mudar</span>
							<input type="file" name="logo" />
						</span>
						<a href="#" class="btn btn-danger fileupload-exists btn-sm" data-dismiss="fileupload">Remover</a>
					</div>
				</div>
			</div>			
			<input type="hidden" name="logo_antiga" id="logo_antiga" value="<?=(($d->logo)?$d->logo:false)?>">
			
			<div class="col-lg-4" >
				<span class="TituloForms">Nome empresa</span>
				<p><input type='text' name="empresa" value="<?=($d->empresa)?>" id="empresa" class="form-control" placeholder="Nome da empresa" /></p>
			</div>
			
			
			
			<div class="col-lg-4" >
				<span class="TituloForms">Cor do menu</span>
				<p><input type='text' name="cor_menu" value="<?=$d->cor_menu?>" id="custom" /></p>
			</div>
				
			</div>
		</fieldset>

		
		<input type="hidden" name="cod" value="<?=$_GET[cod]?>" />	
			
		<div class="row">

			<button type="submit" id="salvar" name="<?=(($_GET[op]=='novo') ? 'salvar_'.$Script : 'alterar_'.$Script)?>" class="btn btn-success"><?=(($_GET[op]=='novo') ? 'Salvar' : 'Alterar')?></button>                   

			<button type="button" id="cancelar_<?=$Script?>" name="cancelar" class="btn btn-danger">Cancelar</button>                  

		</div>			
		</form>
			
	</div>
</div>
			
<script language="javascript">

$("#custom").spectrum({
color: "<?=$d->cor_menu?>",
preferredFormat: "rgb",
showInput: true,
showPalette: true,
showAlpha: true,	
palette: [[ "<?=$d->cor_menu?>" ]]			
});

	
	
//opção de cancelar
$("#cancelar_<?=$Script?>").click(function(){

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
	$.ajax({
	url: "./<?=$Conf[script]?>.php",
	success: function(data) {
	$("#<?=$_SESSION[$Script][dialog]?>").html(data);
		$("#CARREGANDO").html('');
	}
   }); 	
	
});
	
	
//opção de salvar
function retornar_<?=$Script?>(cod){

	$.confirm({

		title: "<span style='color:#000'>Ação! </span>",
		content: "Dados salvo com sucesso!",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "",
		buttons: {
			ok: {
				btnClass: "btn-green",
				action: function(){
					
					$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
					$.ajax({
					url: "./<?=$Conf[script]?>.php",
					success: function(data) {
					$("#<?=$_SESSION[$Script][dialog]?>").html(data);
						$("#CARREGANDO").html('');
					}
				   });						

				}

			},
		}				

	});	
	
	

}	
	
	
	
$("#FormConfig").validate({
	rules : {
		id_pesquisa: {
			required : true
		},
		empresa: {
			required : true
		},
		titulo_login: {
			required : true
		},
		descricao_login: {
			required : true
		}
	},
	messages : {
		id_pesquisa : {
			required : "<span class='AlertCampo'><i class='fa fa-info-circle'></i> Informe a pesquisa .</span>"
		},
		empresa : {
			required : "<span class='AlertCampo'><i class='fa fa-info-circle'></i> Informe nome empresa .</span>"
		},
		titulo_login : {
			required : "<span class='AlertCampo'><i class='fa fa-info-circle'></i> Informe o titulo login .</span>"
		},
		descricao_login : {
			required : "<span class='AlertCampo'><i class='fa fa-info-circle'></i> Informe a descrição login .</span>"
		}
	}

});	
	
</script>