<?php	
include("../../includes/connect.php");	


	$Conf[script] = 'tabelas/asaas/asaas';
	$Script = md5($Conf[script]);
    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao, "select count(id) from asaas  "));
	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/asaas/asaas';
	$_SESSION[$Script][titulo] = 'Cadastro Asaas';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){
		mysqli_query($conexao,"delete from asaas where id in (".@implode(",",$_POST[CheckOpc]).")");
		
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
	
</style>


<div class="card" >
<div class="card-body">

		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>

			
		<?php
	    include("../../list/list.php");	
			
		$pergnta = "select a.*, b.nome from asaas a
					left join usuarios b on b.id=a.id_usuario
					where b.nome like '%$busca%' or 
						  a.status like '%$busca%' 

					  ".$_SESSION[$Script][campo]."
					  ".$_SESSION[$Script][ordenar]."

					limit $pn,$pg ";
		$resultado = mysqli_query($conexao, $pergnta);
		//echo $pergnta;			
			
		?>

		<div class="table-responsive" style="overflow-x:auto;">
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
          <table class="table table-striped">
            <thead >
              <tr >
                <th><?=$_SESSION[$Script][checkbox]?></th>
                <th campo ordem='b.nome'><?=$_SESSION[$Script][ordem][$opi]?> Cliente</th>
                <th campo ordem='a.token_producao'><?=$_SESSION[$Script][ordem][$opi]?> Api token</th>
                <th campo ordem='a.token_producao'><?=$_SESSION[$Script][ordem][$opi]?> Status</th>
              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
        ?> 
              <tr class="AoclicarList">
                <td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" > </td>
				  
                <td Editar cod="<?=$d->id?>" ><?=($d->nome)?> </td>
				<td Editar cod="<?=$d->id?>" ><?=($d->token_producao)?> </td><td Editar cod="<?=$d->id?>" ><?=($d->status)?> </td>

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

	
</script>