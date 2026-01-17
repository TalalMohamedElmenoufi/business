<?php
include("../../includes/connect.php");

$Conf['script'] = 'tabelas/usuarios/usuarios';
$Script = md5($Conf['script']);
list($_SESSION[$Script]['nr']) = mysqli_fetch_row(mysqli_query($conexao, "select count(id) from usuarios  "));
$_SESSION[$Script]['tabela'] = 'usuarios';
$_SESSION[$Script]['dialog'] = 'CONTEUDOS';
$_SESSION[$Script]['url'] = 'tabelas/usuarios/usuarios';
$_SESSION[$Script]['titulo'] = 'Tabela de registro de acesso ao sistema';


$_SESSION[$Script]['Script'] = md5($_SESSION[$Script]['url']);
$Md5 = $_SESSION[$Script]['Script'];


if($_POST){
	mysqli_query($conexao,"delete from usuarios where id in (".@implode(",",$_POST['CheckOpc']).")");
	exit;
}



?>


<style>
.Perfil{
	padding:5px;
}
	.Whats{
		border:#12D522 solid 1px;
		padding:5px;
		border-radius:10px;
		position:relative;
		margin-top:-35px;
		margin-right:2px;
	}
	
</style>


<div class="card" >
<div class="card-body">
		
		
		<div class="panel-heading">
			<?=($_SESSION[$Script]['titulo'])?>
		</div>

			
			<?php
				include("../../list/list.php");
				$pergunta = "select a.* from ".$_SESSION[$Script]['tabela']." a

				where a.nome like '%$busca%'

				".$_SESSION[$Script]['campo']."
				".$_SESSION[$Script]['ordenar']."

				limit $pn,$pg ";
				$resultado = mysqli_query($conexao, $pergunta);			
				//echo $pergunta;
			?>
			<div class="table-responsive" style="overflow-x:auto;">
		        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script]['excluir']?>">				
				
				<table class="table table-striped">
				  <thead>
					<tr>
						<th><?=$_SESSION[$Script]['checkbox']?></th>
						<th campo ordem='a.id' title="Ordenar por ID"> <?=$_SESSION[$Script]['ordem'][$opi]?>  ID</th>
						<th campo ordem='a.empresa' title="Ordenar por Empresa"><?=$_SESSION[$Script]['ordem'][$opi]?> Empresa</th>
						<th campo ordem='a.nome' title="Ordenar por Nome"><?=$_SESSION[$Script]['ordem'][$opi]?> Nome</th>
						<th campo ordem='a.email' title="Ordenar por E-mail"><?=$_SESSION[$Script]['ordem'][$opi]?> E-mail</th>
						<th campo ordem='a.data_nascimento' title="Ordenar por Data nascimento"><?=$_SESSION[$Script]['ordem'][$opi]?> Data nascimento</th>
						<th colspan="3" >Créditos</th>
					</tr>
				  </thead>
				  <tbody>
					  
					<?php
					while($d = mysqli_fetch_object($resultado)){
					?> 
						  <tr class="AoclicarList" title="Editar ID <?=$d->id?>">
							<td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script]['MT']?>" value="<?=$d->id?>" ></td>
							<th scope="row" Editar cod="<?=$d->id?>" ><?=$d->id?>						<button type="button" class="btn btn-success Perfil" IdUs="<?=$d->id?>">Perfil</button></th>  
							<td Editar cod="<?=$d->id?>" ><?=($d->empresa)?> </td>
							<td Editar cod="<?=$d->id?>" ><?=($d->nome)?> </td>
							<td Editar cod="<?=$d->id?>" ><?=($d->email)?> </td>
							<td Editar cod="<?=$d->id?>" ><?=$d->data_nascimento?> </td>
							  
							<td Editar cod="<?=$d->id?>" >
								<i class="fab fa-whatsapp"></i>
								<span class="Whats"><?=$d->creditos_msg?> </span>
							</td>  
							<td Editar cod="<?=$d->id?>" >
								<i class="far fa-comment-alt"></i>
								<span class="Whats"><?=$d->creditos_sms?> </span>
							</td>   
							  
							<td Editar cod="<?=$d->id?>" >
								<i class="far fa-envelope"></i>
								<span class="Whats"><?=$d->credito_email?> </span>
							</td>  
							  
							  
							<td Editar cod="<?=$d->id?>" >
								<i class="fas fa-search"></i>
								<span class="Whats"><?=$d->creditos?> </span>
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

var c = $(this).attr('cod');

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	$.ajax({
	  url: './<?=$_SESSION[$Script]['url']?>_form.php?op=editar&cod='+c,
	  success: function(data) {
	  $('#<?=$_SESSION[$Script]['dialog']?>').html(data);
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
		  url: './<?=$_SESSION[$Script]['url']?>.php?ordem='+ordem+'&opi='+opi,
		  success: function(data) {
		  $('#<?=$_SESSION[$Script]['dialog']?>').html(data);
			  $("#CARREGANDO").html('');
		  }
		});

});	

	
	
	
 $('.Perfil').click(function(){

	 var id = $(this).attr('IdUs');

	 Perfil = $.confirm({
		title: "Perfil de acesso",
		content: "url:./tabelas/usuarios/perfil.php?id="+id,
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