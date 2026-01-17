<?php
include("../../includes/connect.php");

$Conf[script] = 'tabelas/pesquisa_bot/pesquisa_bot';
$Script = md5($Conf[script]);

$pergunta = "select * from pesquisa_bot where id='".$_GET[cod]."' ";
$resultado = mysqli_query($conexao2, $pergunta);
$d = mysqli_fetch_object($resultado);


if(isset($_POST["alterar_".$Script])){
	
				$query = "update pesquisa_bot set  
							pesquisa='".($_POST[pesquisa])."'
							where id='".$_POST[cod]."'
							"; 
		$result = mysqli_query($conexao2, $query);

		echo "<script>parent.retornar_$Script('".$_POST[cod]."')</script>";
		exit();
}
	
	

if(isset($_POST["salvar_".$Script])){	

		$query = "insert into pesquisa_bot set 
				id_logado='".$_SESSION[id_usuario]."',
				pesquisa='".($_POST[pesquisa])."'
				";
		$result = mysqli_query($conexao2, $query);										

	    if($result){
			list($SeusCreditos) = mysqli_fetch_row(mysqli_query($conexao, "select creditos from usuarios where id = '".$_SESSION[id_usuario]."'  "));
			$CreditosAt = $SeusCreditos - 1;
			mysqli_query($conexao, " update usuarios set creditos = '".$CreditosAt."' where id = '".$_SESSION[id_usuario]."' ");
		}
	
		echo "<script>parent.retornar_$Script('".$_POST[cod]."')</script>";
		exit();
}


?>




</style>


<div class="card" >
<div class="card-body">
		
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>
		
		<form id="FormPesquisas" action="<?=$Conf[script]?>_form.php" method="post" target="pagina" enctype="multipart/form-data" >
		
		<div class="form-group">
			 <span class="TituloForms">Nome da pesquisa</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm id="pesquisa" type="text" name="pesquisa" class="form-control" placeholder="Nome da pesquisa"  value="<?=($d->pesquisa)?>" /></p>
		</div>

		
		<div class="form-group">

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

		$("#CARREGANDO").html('<div id="loader"></div>');
	
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
					
					$("#CARREGANDO").html('<div id="loader"></div>');
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


	
$("#FormPesquisas").validate({
	rules : {
		pesquisa: {
			required : true
		}
	},
	messages : {
		pesquisa : {
			required : "<span class='AlertCampo'><i class='fa fa-info-circle'></i> Informe a pesquisa .</span>"
		}
	}

});	
	
</script>