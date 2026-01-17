<?php
include("../../includes/connect.php");


$Conf[script] = 'tabelas/categoria_cadastro/categoria_cadastro';
$Script = md5($Conf[script]);


$pergunta = "select * from categoria_cadastro where id='".$_GET[cod]."' ";
$resultado = mysqli_query($conexao2, $pergunta);
$d = mysqli_fetch_object($resultado);



if(isset($_POST["alterar_".$Script])){
	
	
				$query = "update categoria_cadastro set  
									descricao='".($_POST[descricao])."'

								    where id='".$_POST[cod]."'
																				";
		$result = mysqli_query($conexao2, $query);

		echo "<script>parent.retornar_$Script('".$_POST[cod]."')</script>";
		echo $query;
		exit();
}
	
	

if(isset($_POST["salvar_".$Script])){	

	
		$query = "insert into categoria_cadastro set 
									descricao='".($_POST[descricao])."'
									
															";
			$result = mysqli_query($conexao2, $query);												
		
		echo "<script>parent.retornar_$Script('".$_POST[cod]."')</script>";
		echo $query;
		exit();
}


?>




<div class="card" >
<div class="card-body">
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>
		
		<form action="<?=$Conf[script]?>_form.php" method="post" target="pagina" enctype="multipart/form-data" >


		<div class="form-group">
			 <span class="TituloForms">Descrição</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm id="descricao" type="text" name="descricao" class="form-control" placeholder="Descricao"  value="<?=($d->descricao)?>" /></p>
		</div>

                 
	 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:1px;margin-top:8px;">

	  <button type="submit" id="salvar" name="<?=(($_GET[op]=='novo') ? 'salvar_'.$Script : 'alterar_'.$Script)?>" class="btn btn-success"><?=(($_GET[op]=='novo') ? 'Salvar' : 'Alterar')?></button>                  

	  <button type="button" id="cancelar_<?=$Script?>" name="cancelar" class="btn btn-danger">Cancelar</button>                  

	 </div>


	  <input type="hidden" id="cod_<?=$Script?>" name="cod" value="<?=$_GET[cod]?>" />
        
        
     </form>	


		
		
</div>
</div>


<script type="text/javascript" charset="iso-8859-1">
		

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
 
</script>