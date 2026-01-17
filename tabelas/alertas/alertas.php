<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/alertas/alertas';
$Script = md5($Conf[script]);

$_SESSION[$Script][tabela] = 'alertas';

list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from ".$_SESSION[$Script][tabela]."  "));

$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/alertas/alertas';
$_SESSION[$Script][titulo] = 'Tabela de alertas';

$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
$Md5 = $_SESSION[$Script][Script];


if($_POST){

	mysqli_query($conexao2,"delete from alertas where id in (".@implode(",",$_POST[CheckOpc]).") ");

	exit;
}

?>


<style>
.Perfil{
	padding:5px;
}

</style>


<div class="card" >
<div class="card-body">
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>
			
			<?php
				include("../../list/list3.php");
				$pergunta = "select a.* from ".$_SESSION[$Script][tabela]." a
				where a.alerta like '%$busca%'
				".$_SESSION[$Script][campo]."
				".$_SESSION[$Script][ordenar]."
				limit $pn,$pg ";
				$resultado = mysqli_query($conexao2, $pergunta);			
				//echo $pergunta;
			?>
			<div class="table-responsive" style="overflow-x:auto;">
		        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>">				
				
				<table class="table table-striped table-bordered table-hover">
				  <thead>
					<tr>
						<th><?=$_SESSION[$Script][checkbox]?></th>
						<th campo ordem='a.id' title="Ordenar por id"><?=$_SESSION[$Script][ordem][$opi]?> ID</th>
						<th campo ordem='a.alerta' title="Ordenar por alerta"><?=$_SESSION[$Script][ordem][$opi]?> Alerta</th>
						<th campo ordem='a.data' title="Ordenar por data"><?=$_SESSION[$Script][ordem][$opi]?> Data</th>
						<th campo ordem='a.lido' title="Ordenar por status"><?=$_SESSION[$Script][ordem][$opi]?> Status</th>
					</tr>
				  </thead>
				  <tbody>
					  
					<?php
					while($d = mysqli_fetch_object($resultado)){
					?> 
						  <tr class="AoclicarList">
							<td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" ></td> 
							<td Editar cod="<?=$d->id?>" ><?=$d->id?></td> 
							<td Editar cod="<?=$d->id?>" ><?=($d->alerta)?> </td>
							<td Editar cod="<?=$d->id?>" ><?=dataBr($d->data)?> </td>
							<td Editar cod="<?=$d->id?>" ><?=(($d->lido=='S')?'Lida':'Não lida')?> </td>
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

	
$("th[campo]").click( function(){ 


	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	var ordem = $(this).attr('ordem');
			for ( var i = 0; i < 2; i++ ) {
					if(i == <?=$opi?>){ var opi = 1 ;  break; }
					if(i == <?=$opi?>){ var opi = 2 ;  break;  }
					if(i == i){ var opi = i ;  break;  }
				}

		$.ajax({
		  url: './<?=$_SESSION[$Script][url]?>.php?ordem='+ordem+'&opi='+opi,
		  success: function(data) {
		  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
			  $("#CARREGANDO").html('');
		  }
		});

});		
	
</script>