<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/log_acesso/log_acesso';
$Script = md5($Conf[script]);


$pergunta = "select * from ".$_SESSION[$Script][tabela]." where id='".$_GET[cod]."' ";
$resultado = mysqli_query($conexao2, $pergunta);
$d = mysqli_fetch_object($resultado);


?>


<div class="card" >
<div class="card-body">
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>

	
        <form id="ValidarContatos" action="<?=$Conf[script]?>_form.php" method="post" target="pagina" enctype="multipart/form-data" >

             <div class="row">
                <div class="col-lg-12" >
                     <span class="TituloForms">LOG</span><br>
                      <p><textarea InputForm class="form-control" placeholder="LOG" readonly><?=$d->log?></textarea></p>
                </div>
             </div>   
  
	 <div class="form-group">

	  <!--<button type="submit" id="salvar" name="<?=(($_GET[op]=='novo') ? 'salvar_'.$Script : 'alterar_'.$Script)?>" class="btn btn-success"><?=(($_GET[op]=='novo') ? 'Salvar' : 'Alterar')?></button> -->                 

	  <button type="button" id="cancelar_<?=$Script?>" name="cancelar" class="btn btn-danger">Voltar</button>                  

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
					}
		        });
		});
	 

	
</script>