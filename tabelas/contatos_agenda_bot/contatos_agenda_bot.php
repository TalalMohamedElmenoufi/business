<?php	
include("../../includes/connect.php");
include("../../includes/funcoes.php");

	$Conf[script] = 'tabelas/contatos_agenda_bot/contatos_agenda_bot';
	$Script = md5($Conf[script]);

    if(!$_SESSION[$Script][CodCat]){$_SESSION[$Script][CodCat] = $_GET[CodCat];}
	if($_GET[CodCat]){$_SESSION[$Script][CodCat] = $_GET[CodCat];}

	list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from contatos_bot "));

	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/contatos_agenda_bot/contatos_agenda_bot';
	$_SESSION[$Script][titulo] = 'Contatos agendas';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){

		mysqli_query($conexao2,"delete from contatos_bot where id in (".@implode(",",$_POST[CheckOpc]).") ");

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
	

</style>


   

<div class="card" >
<div class="card-body">
		
			<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>

		    </div>
			
		<?php
	    include("../../list/list.php");	    

		$pergnta = "select a.* from contatos_bot a

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
                <th campo ordem='a.nome'><?=$_SESSION[$Script][ordem][$opi]?> Nome</th>
				<th campo ordem='a.email'><?=$_SESSION[$Script][ordem][$opi]?> E-mail</th>  
                <th campo ordem='a.whatsapp'><?=$_SESSION[$Script][ordem][$opi]?> Celular</th>
                <th campo ordem='a.data_nascimento'><?=$_SESSION[$Script][ordem][$opi]?> Nascimento</th>
                <th campo ordem='a.situacao'><?=$_SESSION[$Script][ordem][$opi]?> Status</th>
              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
			
		$CellOk = '<i class="fas fa-check" style="color:#7FE387"></i>';	

        ?> 
              <tr class="AoclicarList" >
                <td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" > </td>
                <td Editar cod="<?=$d->id?>" ><?=($d->nome)?> </td>
				<td Editar cod="<?=$d->id?>" ><?=($d->email)?> </td>  
				<td Editar cod="<?=$d->id?>" ><?= $d->whatsapp ?> </td>
                <td Editar cod="<?=$d->id?>" ><?= dataBr($d->data_nascimento) ?>  </td>
                
                <td Editar cod="<?=$d->id?>" ><?= (($d->situacao=='1')?'Bloqueado':'Liberado') ?>  </td>
                
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
				  $('body>.tooltip').remove();
			  }
			});

	});



	$("th[campo]").click( function(){ 


		$("#CARREGANDO").html('');
		$('body>.tooltip').remove();

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