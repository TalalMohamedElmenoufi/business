<?php
include("../../includes/connect.php");


$Conf[script] = 'tabelas/asaas/asaas';
$Script = md5($Conf[script]);


$pergunta = "select * from asaas where id='".$_GET[cod]."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);



if(isset($_POST["alterar_".$Script])){

	$query = "update asaas set  
	id_usuario='".($_POST[id_usuario])."',
	token_producao='".($_POST[token_producao])."',
	status='".($_POST[status])."'
	where id='".$_POST[cod]."'
	";
	$result = mysqli_query($conexao, $query);

	echo "<script>parent.retornar_$Script()</script>";
	exit();
	
}
	
	

if(isset($_POST["salvar_".$Script])){	

	$query = "insert into asaas set 
	id_usuario='".($_POST[id_usuario])."',
	token_producao='".($_POST[token_producao])."',
	status='".($_POST[status])."'
	";
	$result = mysqli_query($conexao, $query);											
	echo "<script>parent.retornar_$Script()</script>";
	exit();
}


?>




<div class="card" >
<div class="card-body">
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>
		
		<form id="FormApiAsaas" action="<?=$Conf[script]?>_form.php" method="post" target="pagina" enctype="multipart/form-data" >

		<div class="row">
			<div class="col-md-8 col-lg-8">
			<div class="form-group">
				<span class="InfoDesc">Cliente:</span>
				<select InputForm class="form-control input-text-select" id="id_usuario" name="id_usuario">
				<option InputForm value="">Procurar por cliente</option>	
				<?php
				$query = "select * from usuarios order by nome";
				$result = mysqli_query($conexao, $query);
				while($dc = mysqli_fetch_object($result)){
				?>
				<option InputForm value="<?=$dc->id?>" <?=(($d->id_usuario==$dc->id)?'selected':false)?> ><?=($dc->nome)?></option>
				<?php
				}
				?>
				</select> 
			</div>
			</div>  

			<div class="col-md-4 col-lg-4">	
			<div class="form-check form-check-success">
			<label class="form-check-label">
			<input type="radio" class="form-check-input" name="status" id="Liberado" value="Liberado" <?=(($d->status=="Liberado")?'checked':false)?> > Liberado <i class="input-helper"></i></label>
			</div>

			<div class="form-check form-check-info">
			<label class="form-check-label">
			<input type="radio" class="form-check-input" name="status" id="Bloqueado" value="Bloqueado" <?=(($d->status=="Bloqueado")?'checked':false)?> > Bloqueado <i class="input-helper"></i></label>
			</div> 
			</div>  
		</div>		
			
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="form-group">
				<span class="InfoDesc">Token Asaas:</span>
				<input type="text" class="form-control" name="token_producao" id="token_producao" value="<?=$d->token_producao?>" />
				
			</div>	
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
 
$('#FormApiAsaas').validate({
	rules : {
		id_usuario : {
			required : true
		},
		token_producao : {
			required : true
		}

	},
	messages : {
		id_usuario : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o nome do cliente.</div>'
		},
		token_producao : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o token asaas.</div>'
		}
	}

});		
	
</script>