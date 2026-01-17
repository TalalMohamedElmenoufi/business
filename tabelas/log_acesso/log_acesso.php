<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/log_acesso/log_acesso';
$Script = md5($Conf[script]);

$_SESSION[$Script][tabela] = 'log_acesso_'.date('Y');

list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from ".$_SESSION[$Script][tabela]."  "));

$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/log_acesso/log_acesso';
$_SESSION[$Script][titulo] = 'Tabela config log acesso';

$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
$Md5 = $_SESSION[$Script][Script];


if($_POST){
	mysqli_query($conexao2,"delete from ".$_SESSION[$Script][tabela]." where id in (".@implode(",",$_POST[CheckOpc]).")");
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
				include("../../list/list.php");
				$pergunta = "select a.* from ".$_SESSION[$Script][tabela]." a
				where a.ip like '%$busca%' or a.city like '%$busca%'
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
						<th campo ordem='a.ip' title="Ordenar por ip"><?=$_SESSION[$Script][ordem][$opi]?> IP</th>
						<th campo ordem='a.city' title="Ordenar por cidade"><?=$_SESSION[$Script][ordem][$opi]?> Cidade</th>
						<th campo ordem='a.data' title="Ordenar por data"><?=$_SESSION[$Script][ordem][$opi]?> Data log</th>
					</tr>
				  </thead>
				  <tbody>
					  
					<?php
					while($d = mysqli_fetch_object($resultado)){
					?> 
						  <tr class="AoclicarList" title="Editar ID <?=$d->id?>">
							<td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" ></td> 
							<td Editar cod="<?=$d->id?>" ><?=$d->id?></td> 
							<td Editar cod="<?=$d->id?>" ><?=($d->ip)?> </td>
							<td Editar cod="<?=$d->id?>" ><?=($d->city)?> </td>
							<td Editar cod="<?=$d->id?>" ><?=dataBr($d->data)?> </td>

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

var c = $(this).attr('cod');

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	$.ajax({
	  url: './<?=$_SESSION[$Script][url]?>_form.php?op=editar&cod='+c,
	  success: function(data) {
	  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
		 $("#CARREGANDO").html('');
	  }
	});

});
	
	
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

	
	
	
 $('.Perfil').click(function(){

	 var id = $(this).attr('IdUs');

	 Perfil = $.confirm({
		title: "Perfil de acesso",
		content: "url:./tabelas/log_acesso/perfil.php?id="+id,
		columnClass:"col-md-8 col-md-offset-2",
		theme: "",
		buttons: {
			fechar: {
				btnClass: "btn-danger",
				action: function(){
				}
			},
		}				
	  });	 

 });	
	
</script>