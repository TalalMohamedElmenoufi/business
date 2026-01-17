<?php	
include("../../includes/connect.php");	

	$Conf[script] = 'tabelas/bot_config/bot_config';
	$Script = md5($Conf[script]);
    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from bot_config  "));
	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/bot_config/bot_config';
	$_SESSION[$Script][titulo] = 'Config bot';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){
		mysqli_query($conexao2,"delete from bot_config where id in (".@implode(",",$_POST[CheckOpc]).")");
		
		exit;
	}	

	
?>

<style>
td[Editar]:hover{
	cursor:pointer;
}
	
th[campo]:hover{
	color: #0A9835;
}


</style>

			
			
<div class="card" >
<div class="card-body">


		<div class="panel-heading">
			<h2><?=($_SESSION[$Script][titulo])?></h2>
		</div>
		
			
			
			
			<?php
				include("../../list/list.php");
				$pergnta = "select a.* from bot_config a

							where a.empresa like '%$busca%' or
							      a.titulo_login like '%$busca%'

							  ".$_SESSION[$Script][campo]."
							  ".$_SESSION[$Script][ordenar]."

							limit $pn,$pg ";
				$resultado = mysqli_query($conexao2, $pergnta);
				//echo $pergnta;			
					
			?>
 
			<div style="overflow-x:auto;">		
			<form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>" >
 
			  <table class="table table-striped">
				<thead >
				  <tr >
					<th><?=$_SESSION[$Script][checkbox]?></th>
					<th campo ordem='a.id'> <?=$_SESSION[$Script][ordem][$opi]?>  Id</th>
					<th campo ordem='a.pesquisa'><?=$_SESSION[$Script][ordem][$opi]?> Empresa</th> 
					<th campo ordem='a.titulo_login'><?=$_SESSION[$Script][ordem][$opi]?> Titulo login</th> 
				  </tr>
				</thead>

			 <tbody>
			<?php
			while($d = mysqli_fetch_object($resultado)){
			?> 
				  <tr class="AoclicarList">
					<td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" > </td>
					<td Editar cod="<?=$d->id?>" > <?=$d->id?> </td>
					<td Editar cod="<?=$d->id?>" ><?=($d->empresa)?> </td>
					<td Editar cod="<?=$d->id?>" ><?=($d->titulo_login)?> </td>

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
	
</script>