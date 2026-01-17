<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/bot_config/whats_config';
$Script = md5($Conf[script]);

$pergunta = "select * from whats_config where id='1' ";
$resultado = mysqli_query($conexao2, $pergunta);
$d = mysqli_fetch_object($resultado);

if(isset($_POST["salvarWats"])){
	
	$trat2 = RetiraEspaco($_FILES['img']['name']) ;
	$img = date('YmdHis')."_".acentos($trat2) ;

	$uploaddir = '../../img/whats/';
	move_uploaded_file($_FILES['img']['tmp_name'], $uploaddir.$img);
	if($_FILES['img']['name']){ unlink($uploaddir.$_POST[img_antiga]); } //para remover foto antiga	

	$query = "replace into whats_config set
	id='1',
	titulo='".($_POST[titulo])."',
	descricao='".($_POST[descricao])."',
	titulo_vencida='".($_POST[titulo_vencida])."',
	descricao_vencida='".($_POST[descricao_vencida])."',
	img='".(($_FILES['img']['name'])?$img:$_POST[img_antiga])."'
	";
	$result = mysqli_query($conexao2, $query);		

	
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
			Config WhatsApp
		</div>
		
		<div class="panel-body">
	
		<form id="FormConfig" action="<?=$Conf[script]?>.php" method="post" target="pagina" enctype="multipart/form-data" >
		

			
		<fieldset class="fieldset-border">
		  <legend class="legend-border">CONFIG ALERTA COBRANÇA</legend>
			
			<div class="row">
			
			<div class="col-lg-4">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
						<img src="<?=(($d->img)?'./img/whats/'.$d->img:'assets/img/demoUpload.jpg')?>" alt="<?=$d->img?>" />
					</div>
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
					<div>
						<span class="btn btn-file btn-primary btn-sm">
							<span class="fileupload-new">Selecione</span>
							<span class="fileupload-exists">Mudar</span>
							<input type="file" name="img" />
						</span>
						<a href="#" class="btn btn-danger fileupload-exists btn-sm" data-dismiss="fileupload">Remover</a>
					</div>
				</div>
			</div>			
			<input type="hidden" name="img_antiga" id="img_antiga" value="<?=(($d->img)?$d->img:false)?>">
			
			<div class="col-lg-8" >
				<p>
				<span class="TituloForms">Titulo</span>
				<p><input type='text' name="titulo" value="<?=($d->titulo)?>" id="titulo" class="form-control" placeholder="Titulo" /></p>	
				</p>			
				
			</div>
			
			</div>
			    
			    <div class="row">
					
				<div class="col-lg-12">
					<span class="TituloForms">Descrição</span>
					<textarea name="descricao" class="form-control" placeholder="Descrição" ><?=($d->descricao)?></textarea>
				</div>			


				</div>

			
		</fieldset>	
			

		
	
		<fieldset class="fieldset-border">
		  <legend class="legend-border">CONFIG ALERTA COBRANÇA VENDIDA</legend>
			
			<div class="row">
			
		
				<div class="col-lg-12" >
					<p>
					<span class="TituloForms">Titulo vencimento</span>
					<p><input type='text' name="titulo_vencida" value="<?=($d->titulo_vencida)?>" id="titulo_vencida" class="form-control" placeholder="Titulo vencimento" /></p>	
					</p>			

				</div>

				<div class="col-lg-12">
					<span class="TituloForms">Descrição vencimento</span>
					<textarea name="descricao_vencida" class="form-control" placeholder="Descrição vencimento" ><?=($d->descricao_vencida)?></textarea>
				</div>			


				</div>

		</fieldset>		
	
	
		<input type="hidden" name="cod" value="<?=$_GET[cod]?>" />	
			
		<div class="row">

			<button type="submit" id="salvar" name="salvarWats" class="btn btn-success">Salvar</button>                   

		</div>	

		</form>
			
			
	</div>
			
			


	</div>
</div>
			
<script language="javascript">

	
//opção de salvar
function retornar_<?=$Script?>(cod){

	$.confirm({

		title: "<span style='color:#000'>Ação! </span>",
		content: "Dados salvo com sucesso!",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
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
		titulo: {
			required : true
		},
		descricao: {
			required : true
		},
		titulo_vencida: {
			required : true
		},
		descricao_vencida: {
			required : true
		}
	},
	messages : {
		titulo : {
			required : "<span class='AlertCampo'><i class='fa fa-info-circle'></i> Informe o titulo da cobrança.</span>"
		},
		descricao : {
			required : "<span class='AlertCampo'><i class='fa fa-info-circle'></i> Informe a descrição cobrança.</span>"
		},
		titulo_vencida : {
			required : "<span class='AlertCampo'><i class='fa fa-info-circle'></i> Informe a titulo cobrança vencida.</span>"
		},
		descricao_vencida : {
			required : "<span class='AlertCampo'><i class='fa fa-info-circle'></i> Informe a descrição cobrança vencida.</span>"
		},
	}

});	
	
</script>