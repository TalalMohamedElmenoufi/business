<?php	
include("../../includes/connect.php");
include("../../includes/funcoes.php");

	$Conf[script] = 'tabelas/mailmarketing/mailmarketing';
	$Script = md5($Conf[script]);

    if(!$_SESSION[$Script][CodCat]){$_SESSION[$Script][CodCat] = $_GET[CodCat];}
	if($_GET[CodCat]){$_SESSION[$Script][CodCat] = $_GET[CodCat];}

    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from mailmarketing "));


	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/mailmarketing/mailmarketing';
	$_SESSION[$Script][titulo] = 'mailmarketing';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){

		mysqli_query($conexao2,"delete from mailmarketing where id in (".@implode(",",$_POST[CheckOpc]).") ");

		exit;
	}	

?>




<style>
	.Copiar{
		font-size:22px;
		cursor:pointer;
	}
	.Copiar:hover{
		color:#046A03;
	}

</style>






<div class="card" >
<div class="card-body">
		
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>		
	
	
	
		<?php
			include("../../list/list.php");	
			$pergnta = "select a.* from mailmarketing a

						where 
							  a.nome like '%$busca%' or				
							  a.email like '%$busca%'

						  ".$_SESSION[$Script][campo]."
						  ".$_SESSION[$Script][ordenar]."

						limit $pn,$pg ";
			$resultado = mysqli_query($conexao2, $pergnta);
			//echo $pergnta;
		?>		
	
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
			
          <table class="table table-striped">
            <thead >
              <tr >
                <th><?=$_SESSION[$Script][checkbox]?></th>
				<th campo ordem='a.id'><?=$_SESSION[$Script][ordem][$opi]?> ID</th>  
                <th campo ordem='a.nome'><?=$_SESSION[$Script][ordem][$opi]?> Nome</th>
				<th campo ordem='a.email'><?=$_SESSION[$Script][ordem][$opi]?> E-mail</th> 
				<th width="140"><?=$_SESSION[$Script][ordem][$opi]?> Copiar link</th>  
              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
			
		$CellOk = '<i class="fas fa-check" style="color:#7FE387"></i>';	

        ?> 
              <tr >
                <td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" > </td>
				<td Editar cod="<?=$d->id?>" ><?=($d->id)?> </td>   
                <td Editar cod="<?=$d->id?>" ><?=($d->nome)?> </td>
				<td Editar cod="<?=$d->id?>" ><?=($d->email)?> </td> 
				<td class="text-center" > 
					<i class="fas fa-link Copiar" MeuLink="https://elmenoufi.com.br/business/campanha/?u=c&db=<?=$_SESSION[id_usuario]?>&id=<?=$d->id?>" campanha="<?=($d->nome)?>" data-toggle="tooltip" data-placement="left" title="Copiar link de nº <?=$d->id?>!" >
					</i>  
				</td>   
                
              </tr>
        
        <?php
        }
        ?>
            </tbody>
          </table>

        
        </form>
	</section> 

	<input type="text" id="CopyLink" style="opacity:0" > 
	
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
					 $('#CARREGANDO').html('');
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
					  $('#CARREGANDO').html('');
				  }
				});

		});	

	
	$(".Copiar").click(function(){
		
		var campanha = $(this).attr('campanha');
		
		var Text = $(this).attr('MeuLink');
		$('#CopyLink').val(Text);
		
		var copyText = document.getElementById("CopyLink");
		copyText.select();
		copyText.setSelectionRange(0, 99999)
		document.execCommand("copy");
		

		$.confirm({
			title: "<span style='color:blue'>Atenção!</span>",
			content: "Link copiado com sucesso da campanha <b>"+campanha+"</b>",
			columnClass:"col-md-4 col-md-offset-4",
			theme: "light",
			buttons: {
				Od: {
					btnClass: "btn-success",
					action: function(){
					}
				},
			}				
		  });		
		
		
		
	});
	
</script>
