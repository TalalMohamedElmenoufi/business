<?php	
include("../../includes/connect.php");	


	$Conf[script] = 'tabelas/login_acesso/login_acesso';
	$Script = md5($Conf[script]);
    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from login_acesso  "));
	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/login_acesso/login_acesso';
	$_SESSION[$Script][titulo] = 'Cadastro login_acesso';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){
		mysqli_query($conexao2,"delete from login_acesso where id in (".@implode(",",$_POST[CheckOpc]).")");
		
		exit;
	}	

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

	.BaixarExcel{
		font-size:22px;
		cursor:pointer;
	}	
	.BaixarExcel:hover{
		color: #1B4B0C; 
	}
	
	.IdCategoria{
		padding:1px !important;
		cursor:pointer !important;
	}
	
	.Bordas{
		padding:2px !important;
	}
	
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
			
		$pergnta = "select a.* from login_acesso a
					where a.nome like '%$busca%' 

					  ".$_SESSION[$Script][campo]."
					  ".$_SESSION[$Script][ordenar]."

					limit $pn,$pg ";
		$resultado = mysqli_query($conexao2, $pergnta);
		//echo $pergnta;			
			
		?>

		<div class="table-responsive" style="overflow-x:auto;">
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
          <table class="table table-striped">
            <thead >
              <tr >
                <th><?=$_SESSION[$Script][checkbox]?></th>
				  
				<th campo ordem='a.id' title="Ordenar por ID"> <?=$_SESSION[$Script][ordem][$opi]?>  ID</th>				  
				  
                <th campo ordem='a.nome'><?=$_SESSION[$Script][ordem][$opi]?> Nome</th>
                <th campo ordem='a.email'><?=$_SESSION[$Script][ordem][$opi]?> E-mail</th>
              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
        ?> 
              <tr class="AoclicarList">
                <td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" > </td>
				  
				<th scope="row" Editar cod="<?=$d->id?>" ><?=$d->id?>						<button type="button" class="btn btn-success Perfil" IdUs="<?=$d->id?>">Perfil</button></th> 				  
				  
                <td Editar cod="<?=$d->id?>" ><?=($d->nome)?> </td>
				<td Editar cod="<?=$d->id?>" ><?=($d->email)?> </td>

              </tr>
        
        <?php
        }
        ?>
            </tbody>
          </table>
		  </div>
        
        </form>


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
			  $('body>.tooltip').remove();
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
			  $('body>.tooltip').remove();
		  }
		});

});	

 $('.Perfil').click(function(){

	 var id = $(this).attr('IdUs');

	 Perfil = $.confirm({
		title: "Perfil de acesso",
		content: "url:./tabelas/login_acesso/perfil.php?id="+id,
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