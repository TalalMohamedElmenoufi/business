<?php	
include("../../includes/connect.php");	


	$Conf[script] = 'tabelas/categoria_cadastro/categoria_cadastro';
	$Script = md5($Conf[script]);
    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from categoria_cadastro  "));
	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/categoria_cadastro/categoria_cadastro';
	$_SESSION[$Script][titulo] = 'Categoria cadastros';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){
		mysqli_query($conexao2,"delete from categoria_cadastro where id in (".@implode(",",$_POST[CheckOpc]).")");
		
		mysqli_query($conexao2,"delete from cadastro where categoria in (".@implode(",",$_POST[CheckOpc]).")");		
		
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
			
		$pergnta = "select a.* from categoria_cadastro a

					where a.descricao like '%$busca%' 

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
                <th campo ordem='a.descricao'><?=$_SESSION[$Script][ordem][$opi]?> Categiria</th>
                <th class="text-right" > Baixar</th>
                <th class="text-right" > Contatos</th>
              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
        ?> 
              <tr class="AoclicarList">
                <td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" > </td>
				  
                <td Editar cod="<?=$d->id?>" ><?=($d->descricao)?> </td>
                
				<?php
				$QtOs = mysqli_num_rows(mysqli_query($conexao2, "select * from cadastro where categoria='".$d->id."' "));
				?>				  
				  
                <td > 
					<div  class="col-sm-12 text-right" style="padding: 0">
						<i class="fas fa-cloud-download-alt BaixarExcel" Cod="<?=$d->id?>" data-toggle="tooltip" data-placement="top" title="Baixar lista de contatos (<?=$QtOs?>)"></i>
					</div> 
                </td>
				
              	<td class="Bordas">
             		<div  class="col-sm-12 text-right" style="padding: 0">
					<label IdCategoria Cod="<?=$d->id?>" class="btn btn-gradient-success btn-sm " data-toggle="tooltip" data-placement="top" title="Selecione os contatos" caminho="tabelas/cadastro/cadastro">
					&nbsp; <i class="far fa-address-book" style="font-size:18px;padding:5px;"></i> &nbsp;
					<div class="round hollow-xs ">
					<span> <?=$QtOs?> </span>
					</div>					
					</label>
					</div> 
               </td>
				  
				  
				  

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


	
	
	
	
	$('label[IdCategoria]').click(function(){

		var Cod = $(this).attr('Cod') ;
		var pagina = $(this).attr('caminho') ;

		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

		$.ajax({
		type: "GET",
		url: "<?=$url?>/"+pagina+".php",
		data: {'CodCat':Cod},
		success: function(dados) {
			$("#CARREGANDO").html('');
			$('#CONTEUDOS').html(dados);
			$('body>.tooltip').remove();
		}
		});			

	});	
	
	
	$('.BaixarExcel').click(function(){
		
		var Cat = $(this).attr('Cod') ;

		window.open('./tabelas/categoria_cadastro/gerar_excel.php?Cat='+Cat, '_blank');
		
	});
	
</script>