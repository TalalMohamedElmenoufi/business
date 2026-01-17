<?php	
include("../../includes/connect.php");
include("../../includes/funcoes.php");

	$Conf[script] = 'tabelas/contatos_agenda/contatos_agenda';
	$Script = md5($Conf[script]);

    if(!$_SESSION[$Script][CodCat]){$_SESSION[$Script][CodCat] = $_GET[CodCat];}
	if($_GET[CodCat]){$_SESSION[$Script][CodCat] = $_GET[CodCat];}

	list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from contatos_agenda  "));


	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/contatos_agenda/contatos_agenda';
	$_SESSION[$Script][titulo] = 'Contatos agendas';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){

		$meuArray = $_POST[CheckOpc] ;
		foreach ( $meuArray as $id ) {
			
			list($Cell) = mysqli_fetch_row(mysqli_query($conexao2, "select destination from contatos_agenda where id = '".$id."' "));	
			
			mysqli_query($conexao2,"delete from contatos_agenda_erro where destination = '".$Cell."' ");
			
		}

		mysqli_query($conexao2,"delete from contatos_agenda where id in (".@implode(",",$_POST[CheckOpc]).") ");

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

		$pergnta = "select a.*, a.destination as CellErro1 from contatos_agenda a

					where a.nome like '%$busca%' 

					  ".$_SESSION[$Script][campo]."
					  ".$_SESSION[$Script][ordenar]."

					limit $pn,$pg ";
		$resultado = mysqli_query($conexao2, $pergnta);
		//echo $pergnta;									  
									  
         ?>
                
       
<!--INICIO ENVIAR PARA EXCEL-->              
<style>
.btn-bs-file{
    position:relative;
}
.btn-bs-file input[type="file"]{
    position: absolute;
    top: -9999999;
    filter: alpha(opacity=0);
    opacity: 0;
    width:0;
    height:0;
    outline: none;
    cursor: inherit;
}
</style>       

     


<form id="formId2" action="tabelas/contatos_agenda/enviar_excel/excel.php" method="post" target="pagina" enctype="multipart/form-data" >
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right" style="padding: 0; margin-top:6px;">
	
	<img src="./img/excel.png" class="img-rounded Excelmodelo" data-toggle="tooltip" data-placement="top" title="Visualizar modelo Excel" style="margin-right:20px; cursor:pointer; width:30px;">
	
	<label class="btn-bs-file btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Arquivo Excel extensão xls ou xlsx">
	    <i class="far fa-file-excel" aria-hidden="true"></i> 
		&nbsp; <b>EXCEL</b> 
		<input id="Excel" type="file" name="arquivo" value="arquivo" id="arquivo" >
	</label>
</div> 
</form> 
<!--FIM ENVIAR PARA EXCEL-->         
       
       
       
        <div class="table-responsive" style="overflow-x:auto;"> 
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
          <table class="table table-striped">
            <thead >
              <tr >
                <th><?=$_SESSION[$Script][checkbox]?></th>
                <th campo ordem='a.nome'><?=$_SESSION[$Script][ordem][$opi]?> Nome</th>
				<th campo ordem='a.email'><?=$_SESSION[$Script][ordem][$opi]?> E-mail</th>  
                <th campo ordem='a.telefone'><?=$_SESSION[$Script][ordem][$opi]?> Celular</th>
                <th campo ordem='b.destination'><?=$_SESSION[$Script][ordem][$opi]?> Celular Erro</th>
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
				<td Editar cod="<?=$d->id?>" ><?= '('.$d->cod_estado.') '. $d->telefone ?> </td>

                <td Editar cod="<?=$d->id?>" style="color:#F4595B" ><?= (($d->CellErro1==$d->CellErro2)?$d->CellErro1:$CellOk)  ?>  </td>
                
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


	

$("#Excel").change(function () {

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	$("#formId2").submit();

});

function excel(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	$.ajax({
	  url: './<?=$_SESSION[$Script][url]?>.php',
	  success: function(data) {
	  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
		  $("#CARREGANDO").html('');
		  $('body>.tooltip').remove();
	  }
	});
	
}


	
$(".Excelmodelo").click(function(){
	
	$.confirm({
		title: 'Modelo Excel',
		content: 'url:tabelas/contatos_agenda/modelo.php',
		columnClass:'col-md-12',
		theme: 'supervan',
		buttons: {
			Entendi: {
				btnClass: 'btn-green',
				action: function(){
				}
			},
		}
	});
	
});
	
</script> 