<?php	
include("../../includes/connect.php");
include("../../includes/funcoes.php");

	$Conf[script] = 'tabelas/cadastro/cadastro';
	$Script = md5($Conf[script]);

    if(!$_SESSION[$Script][CodCat]){$_SESSION[$Script][CodCat] = $_GET[CodCat];}
	if($_GET[CodCat]){$_SESSION[$Script][CodCat] = $_GET[CodCat];}

    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria = '".$_SESSION[$Script][CodCat]."'  "));

	list($_SESSION[$Script][categoria]) = mysqli_fetch_row(mysqli_query($conexao2, "select descricao from categoria_cadastro where id = '".$_SESSION[$Script][CodCat]."'  "));

	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/cadastro/cadastro';
	$_SESSION[$Script][titulo] = 'Cadastros';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){
 
		$meuArray = $_POST[CheckOpc] ;
		foreach ( $meuArray as $id ) {
			
			list($Cell) = mysqli_fetch_row(mysqli_query($conexao2, "select destination from cadastro where id = '".$id."' "));	
			
			mysqli_query($conexao2,"delete from cadastro_erro where destination = '".$Cell."' ");
			
		}

		mysqli_query($conexao2,"delete from cadastro where id in (".@implode(",",$_POST[CheckOpc]).") ");

		exit;
	}	
	


list($QtWhats) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria = '".$_SESSION[$Script][CodCat]."' and tel_tipo='WhatsApp'  "));
list($QtWhatsInv) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria = '".$_SESSION[$Script][CodCat]."' and tel_tipo='Invalido'  "));
list($QtCellErro) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria = '".$_SESSION[$Script][CodCat]."' and tel_tipo='Celular Erro'  "));
list($QtPendentes) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria = '".$_SESSION[$Script][CodCat]."' and tel_tipo=''  "));
	

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
	.voltarCategoria{
		float:right;
	}
	
	.WhatsappValidar{
		font-size:32px;
		color:#2cc04e;
		cursor:pointer;
		margin-right:6px;
		float:left;
	}
	.WhatsappValidar:hover{
		color:#63B375;
	}	
	
	
	.ValidarWhatsapp{
		font-size:32px;
		color:#2cc04e;
		cursor:pointer;
		margin-right:6px;
		float:left;
	}
	.ValidarWhatsapp:hover{
		color:#63B375;
	}
	.InvalidoWhatsapp{
		font-size:32px;
		color:#D40003;
		cursor:pointer;
		margin-right:6px;
		float:left;
		margin-left:80px;
	}
	.CellErro{
		font-size:20px;
		color:#D40003;
		cursor:pointer;
		margin-right:6px;
		float:left;
		margin-left:80px;
	}	
	
	.QtWhats<?=$_SESSION[$Script][CodCat]?>{
		border: rgba(0, 0, 0, 0.40) solid 1px;
		margin-top:0px;
		padding:4px;
		font-size:11px;
		color:#000273;
		font-weight: bold;
		width:auto;
		height:auto;
		position:absolute;
		border-radius:35%;
	}	
	
	.PendentesNumeros{
		font-size:24px;
		color:#2cc04e;
		cursor:pointer;
		margin-right:6px;
		float:left;
		margin-left:80px;
		display:<?=(($QtPendentes)?'block':'none')?>;
	}	
	
	.QtWhatsInv<?=$_SESSION[$Script][CodCat]?>{
		border: rgba(0, 0, 0, 0.40) solid 1px;
		margin-top:0px;
		padding:4px;
		font-size:11px;
		color:#B70003;
		font-weight: bold;
		width:auto;
		height:auto;
		position:absolute;
		border-radius:35%;
	}	
	
	.QtCellErro<?=$_SESSION[$Script][CodCat]?>{
		border: rgba(0, 0, 0, 0.40) solid 1px;
		margin-top:0px;
		padding:4px;
		font-size:11px;
		color:#B70003;
		font-weight: bold;
		width:auto;
		height:auto;
		position:absolute;
		border-radius:35%;
	}		
	
	.NumerosPendentes<?=$_SESSION[$Script][CodCat]?>{
		border: rgba(0, 0, 0, 0.40) solid 1px;
		margin-top:0px;
		padding:4px;
		font-size:11px;
		color:#0E089E;
		font-weight: bold;
		width:auto;
		height:auto;
		position:absolute;
		border-radius:35%;
		
	}	
</style>


   

<div class="card" >
<div class="card-body">
		
			<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
			
			<a href="#" id="Voltar" class="voltarCategoria" data-toggle="tooltip" data-placement="left" title="Voltar para lista das categorias" > Voltar categorias </a>
		    </div>
			
		<?php
	    include("../../list/list.php");	    

		$pergnta = "select a.*, a.destination as CellErro1, b.destination as CellErro2 from cadastro a

					left join cadastro_erro b on b.destination=a.destination

					where a.nome like '%$busca%' and 
					a.categoria = '".$_SESSION[$Script][CodCat]."' or 
					a.telefone like '%$busca%' and 
					a.categoria = '".$_SESSION[$Script][CodCat]."' or 
					a.email like '%$busca%' and 
					a.categoria = '".$_SESSION[$Script][CodCat]."' or 
					a.tel_tipo like '%$busca%' and 
					a.categoria = '".$_SESSION[$Script][CodCat]."'



					  ".$_SESSION[$Script][campo]."
					  ".$_SESSION[$Script][ordenar]."

					limit $pn,$pg ";
		$resultado = mysqli_query($conexao2, $pergnta);
		//echo $pergnta;									  
									  
         ?>
                
       
<!--INICIO ENVIAR PARA EXCEL-->              
     


<form id="formId" action="tabelas/cadastro/enviar_excel/excel.php" method="post" target="pagina" enctype="multipart/form-data" >
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right" style="padding: 0; margin-top:6px;">

	
	<?php
	if($QtPendentes){
		$cssWhats = "ValidarWhatsapp";
		$tituloWhats = "Clique aqui para validar numeros WhatsApp";
	}else{
		$cssWhats = "WhatsappValidar";
		$tituloWhats = "WhatsApp validos";
	}
	?>
	
	
	<i class="BuscaNumeros mdi mdi-whatsapp <?=$cssWhats?>" data-toggle="tooltip" data-placement="top" title="<?=$tituloWhats?>" Catg="<?=$_SESSION[$Script][CodCat]?>" TipoNumber="WhatsApp">
		<span class="QtWhats<?=$_SESSION[$Script][CodCat]?>" ><?=$QtWhats?></span>
	</i>
	<i class="BuscaNumeros mdi mdi-whatsapp InvalidoWhatsapp" Catg="<?=$_SESSION[$Script][CodCat]?>" data-toggle="tooltip" data-placement="top" title="WhatsApp invalidos" TipoNumber="Invalido">
		<span class="QtWhatsInv<?=$_SESSION[$Script][CodCat]?>" ><?=$QtWhatsInv?></span>
	</i> 
	<i class="BuscaNumeros fas fa-phone-slash CellErro" Catg="<?=$_SESSION[$Script][CodCat]?>" data-toggle="tooltip" data-placement="top" title="Celulares com Erros" TipoNumber="Celular Erro">
		<span class="QtCellErro<?=$_SESSION[$Script][CodCat]?>" ><?=$QtCellErro?></span>
	</i> 
	<i class="fas fa-coins PendentesNumeros" Catg="<?=$_SESSION[$Script][CodCat]?>" data-toggle="tooltip" data-placement="top" title="Numeros pendentes">
		<span class="NumerosPendentes<?=$_SESSION[$Script][CodCat]?>" ><?=$QtPendentes?></span>
	</i> 
	
	
	<img src="./img/renovar.png" Catg="<?=$_SESSION[$Script][CodCat]?>" class="img-rounded RenovarTipo" data-toggle="tooltip" data-placement="top" title="Renovar o tipo" style="margin-right:20px; cursor:pointer; width:30px;">
	
	
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
                <th campo ordem='a.tel_tipo'><?=$_SESSION[$Script][ordem][$opi]?> Tipo</th>
                <th campo ordem='a.data_nascimento'><?=$_SESSION[$Script][ordem][$opi]?> Nascimento</th>
                <th campo ordem='a.situacao'><?=$_SESSION[$Script][ordem][$opi]?> Status</th>
                <th campo ordem='a.aceito'><?=$_SESSION[$Script][ordem][$opi]?> Aceitar</th>
              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
			
		$CellOk = '<i class="fas fa-check" style="color:#7FE387"></i>';	

        ?> 
              <tr class="AoclicarList" style="color:<?= (($d->aceito!='SIM')?'#FF0004':false) ?>" >
                <td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>" > </td>
                <td Editar cod="<?=$d->id?>" ><?=($d->nome)?> </td>
				<td Editar cod="<?=$d->id?>" ><?=($d->email)?> </td>  
				<td Editar cod="<?=$d->id?>" ><?= '('.$d->cod_estado.') '. $d->telefone ?> </td>

                <td Editar cod="<?=$d->id?>" style="color:#F4595B" ><?= (($d->CellErro1==$d->CellErro2)?$d->CellErro1:$CellOk)  ?>  </td>
                
                <td Editar cod="<?=$d->id?>" ><?= $d->tel_tipo ?>  </td>
				<td Editar cod="<?=$d->id?>" ><?= dataBr($d->data_nascimento) ?>  </td>
                
                <td Editar cod="<?=$d->id?>" ><?= (($d->situacao=='1')?'Bloqueado':'Liberado') ?>  </td>
				  
                <td Editar cod="<?=$d->id?>" ><?= $d->aceito ?>  </td>
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

$(".BuscaNumeros").click( function(){ 	

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
	
	let b = $(this).attr("TipoNumber");
	
	$.get('./<?=$_SESSION[$Script][url]?>.php?busca='+b,
	function(data) {
	$('#<?=$_SESSION[$Script][dialog]?>').html(data);
	$("#CARREGANDO").html('');	
		$('body>.tooltip').remove();
	});	
	
});
	
	
	
	$(".ValidarWhatsapp").click( function(){ 
		
    $("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	var Catg = $(this).attr('Catg');

			$.ajax({
			  url: './tabelas/cadastro/_checar_number_whats.php?Catg='+Catg,
			  success: function(data0) {
				  
					$.ajax({
					  url: './<?=$_SESSION[$Script][url]?>.php',
					  success: function(data) {
					  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
						 $("#CARREGANDO").html('');
						  $('body>.tooltip').remove();
					  }
					});				  
				  
			  }
			});	
		


	});	

	
	
	
	
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


	
	
	$('#Voltar').click(function(){	
		
		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
		$.ajax({
		url:"<?=$url?>/tabelas/categoria_cadastro/categoria_cadastro.php",
		success: function(dados){
			$("#CARREGANDO").html('');
			$('#CONTEUDOS').html(dados);
			$('body>.tooltip').remove();
			
		}
		});		
		
	});
	

$("#Excel").change(function () {

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	$("#formId").submit();

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
		content: 'url:tabelas/cadastro/modelo.php',
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
	
$(".RenovarTipo").click(function(){	

	var Catg = $(this).attr("Catg");

	$.confirm({
		title: 'Atenção esta opção',
		content: 'Irá remover toda a categoria: <b>'+Catg+'</b><br>Deseja realmente zerar o <b>Tipo</b>?',
		columnClass:"col-md-4 col-md-offset-4",
		theme: 'light',
		buttons: {
			Sim: {
				btnClass: 'btn-danger',
				action: function(){
					
					
					$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

					$.ajax({
					  url: './tabelas/cadastro/zerar_tipo.php?Catg='+Catg,
					  success: function(data0) {

							$.ajax({
							  url: './<?=$_SESSION[$Script][url]?>.php',
							  success: function(data) {
							  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
								 $("#CARREGANDO").html('');
								  $('body>.tooltip').remove();
							  }
							});				  

					  }
					});					
					
				}
			},
			Não: {
				btnClass: 'btn-green',
				action: function(){
					
				}
			},
		}
	});	
	
});	
</script> 