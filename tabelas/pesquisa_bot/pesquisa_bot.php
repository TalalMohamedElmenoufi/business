<?php	
include("../../includes/connect.php");	

	$Conf[script] = 'tabelas/pesquisa_bot/pesquisa_bot';
	$Script = md5($Conf[script]);
    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from pesquisa_bot  "));
	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/pesquisa_bot/pesquisa_bot';
	$_SESSION[$Script][titulo] = 'Pesquisas';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){
		mysqli_query($conexao2,"delete from pesquisa_bot where id in (".@implode(",",$_POST[CheckOpc]).")");
		
		mysqli_query($conexao2,"delete from grupos_bot where id_pesquisa in (".@implode(",",$_POST[CheckOpc]).")");
		
		mysqli_query($conexao2,"delete from perguntas_bot where id_pesquisa in (".@implode(",",$_POST[CheckOpc]).")");
		
		mysqli_query($conexao2,"delete from resposta_bot where id_pesquisa in (".@implode(",",$_POST[CheckOpc]).")");
		
		exit;
	}	

	

list($Seuscreditos) = mysqli_fetch_row(mysqli_query($conexao, "select creditos from usuarios where id = '".$_SESSION[id_usuario]."'  "));
?>

<style>
td[Push]:hover{
	color:#F7030B;
	cursor:pointer;
}
td[Editar]:hover{
	cursor:pointer;
}
	
	th[campo]:hover{
		color: #0A9835;
	}

	
	button[Montar]{
		padding:4px;
	}
</style>

			
			
<div class="card" >
<div class="card-body">
				

	<div class="panel-heading">

		<h2><?=($_SESSION[$Script][titulo])?></h2>

		<div style="float:right" >
			<span class="label label-danger"><?=$Seuscreditos?></span>
			<span>Crédito<?=(($Seuscreditos>1)?'s':'')?></span>
		</div>
	</div>

			<?php
				include("../../list/list_bot.php");
				$pergnta = "select a.* from pesquisa_bot a

							where a.pesquisa like '%$busca%' 

							  ".$_SESSION[$Script][campo]."
							  ".$_SESSION[$Script][ordenar]."

							limit $pn,$pg ";
				$resultado = mysqli_query($conexao2, $pergnta);
				//echo $pergnta;				
			?>
 
			<div style="overflow-x:auto;">		
			<form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>" >
 
			  <table class="table table-striped CStabelas">
				<thead >
				  <tr >
					<th><?=$_SESSION[$Script][checkbox]?></th>
					<th campo ordem='a.id'> <?=$_SESSION[$Script][ordem][$opi]?>  Id</th>
					<th campo ordem='a.pesquisa'><?=$_SESSION[$Script][ordem][$opi]?> Nome da pesquisa</th>
					  
					<th width="120" >Link</th> 
					  
					<th width="120" >Montar</th>   
				  </tr>
				</thead>

			 <tbody>
			<?php
			while($d = mysqli_fetch_object($resultado)){
			?> 
				  <tr class="AoclicarList">
					<td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" > </td>
					<td Editar cod="<?=$d->id?>" > <?=$d->id?> </td>
					<td Editar cod="<?=$d->id?>" ><?=($d->pesquisa)?> </td>
					  
					<td >
						<a href="/bot?u=login&c=<?=$_SESSION[id_usuario]?>&p=<?=$d->id?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ir a pesquisa: <?=($d->pesquisa)?>" >Ver Pesquisa</a> 
					</td>   
					  
					<td>
						<button Montar type="button" Cod="<?=$d->id?>" class="btn btn-success">Montar <?=$d->id?> </button> 						
					</td>  
					  
				  </tr>

			<?php
			}
			?>
				</tbody>
			  </table>


				
			</form>
			</div>
			
					  
	
	</div>
</div>			
			
	



	
<script language="javascript">

		$("td[Editar]").click( function(){ 
		
		var c = $(this).attr("cod");

				$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
	
				$.ajax({
				  url: "./<?=$_SESSION[$Script][url]?>_form.php?op=editar&cod="+c,
				  success: function(data) {
				  $("#<?=$_SESSION[$Script][dialog]?>").html(data);
					$("#CARREGANDO").html(''); 
				  }
				});
	
		});



		$("th[campo]").click( function(){ 


			$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

			var ordem = $(this).attr("ordem");
					for ( var i = 0; i < 2; i++ ) {
							if(i == "<?=$opi?>"){ var opi = 1 ;  break; }
							if(i == "<?=$opi?>"){ var opi = 2 ;  break;  }
							if(i == i){ var opi = i ;  break;  }
						}

				$.ajax({
				  url: "./<?=$_SESSION[$Script][url]?>.php?ordem="+ordem+"&opi="+opi,
				  success: function(data) {
				  $("#<?=$_SESSION[$Script][dialog]?>").html(data);
					  $("#CARREGANDO").html('');
				  }
				});

		});	

	
	$("button[Montar]").click( function(){ 
	
		var Cod = $(this).attr("Cod");
		
		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');		
		
		$.ajax({
		  url: "./tabelas/chat_bot/chat_bot.php?Cod="+Cod,
		  success: function(data) {
		     $("#<?=$_SESSION[$Script][dialog]?>").html(data);
			  $("#CARREGANDO").html('');
		  }
		});		
		
		
    });	
</script>