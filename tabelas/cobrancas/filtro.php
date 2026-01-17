<div id="ReloadBuscaCobrancas">
<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");
	
$Conf[script] = 'tabelas/cobrancas/filtro';
$Script = md5($Conf[script]);

$Conf[script2] = 'tabelas/cobrancas/cobrancas';
$Script2 = md5($Conf[script2]);
$_SESSION[$Script2][dialog] = 'CONTEUDOS';
$_SESSION[$Script2][url] = 'tabelas/cobrancas/cobrancas';	
	
	
if($_POST){

	$_SESSION[$Script2][data_inicio] = $_POST[data_inicio];
	$_SESSION[$Script2][data_fim] = $_POST[data_fim];
	
	echo "<script>parent.VoltarCobrancas();</script>";

}
	
	
if($_GET[LimpaFiltro]==1){

	$_SESSION[$Script2][data_inicio] = "";
	$_SESSION[$Script2][data_fim] = "";
		
}	
	
?>	
<style>
.SalvarBusca{
	position:relative;
	margin-right:0px;
	float:right;
}

</style>	
	
<form id="AcaoCobrancas" class="form-horizontal" action="#" method="post">	
	
<div class="col-lg-12 col-md-12" style="height:400px; overflow-x:hidden" >
	
	
	<div class="row">
			
		<div class="form-group col-lg-6 col-md-6">
			 <span class="TituloForms">Data inicio </span><br>
			  <i class="far fa-calendar-alt Icons"></i>
			  <p><input InputForm type="date" id="data_inicio" name="data_inicio" class="form-control" placeholder="Data inicio" value="<?=$_SESSION[$Script2][data_inicio]?>" /></p>
		</div>
			
		<div class="form-group col-lg-6 col-md-6">
			 <span class="TituloForms">Data fim </span><br>
			  <i class="far fa-calendar-alt Icons"></i>
			  <p><input InputForm type="date" id="data_fim" name="data_fim" class="form-control" placeholder="Data fim" value="<?=$_SESSION[$Script2][data_fim]?>" /></p>
		</div>
		
	</div>
	
</div>
	
<button type="submit" class="btn btn-success SalvarBusca" >Salvar filtro busca</button>
<button type="button" class="btn btn-warning" id="LimparBusca">Limpar filtro busca</button>		
</form>	

<div id="GravarBuscaCobrancas" style="display:none"></div>	
	
<script>
$('#AcaoCobrancas').validate({
	rules : {
		data_inicio : {
			required : true
		},
		data_fim : {
			required : true
		}

	},
	messages : {
		data_inicio : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a data inicio.</div>'
		},
		data_fim : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a data fim.</div>'
		}
	},
	submitHandler: function( form ){
		var dados = $( form ).serialize();

		$.ajax({
			type: "POST",
			url: "./tabelas/cobrancas/filtro.php",
			data: dados,
			success: function( data )
			{
				$("#GravarBuscaCobrancas").html(data);

			}
		});

		return false;

	}

 
});
	
	
	
$("#LimparBusca").click(function(){
	
	$.ajax({
	  url: './tabelas/cobrancas/filtro.php?LimpaFiltro=1',
	  success: function(data) {
	  $('#ReloadBuscaCobrancas').html(data);
		  
			$.ajax({
			  url: './tabelas/cobrancas/filtro.php',
			  success: function(data) {
			  $('#ReloadBuscaCobrancas').html(data);
				  
					$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
					$.ajax({
					  url: './tabelas/cobrancas/cobrancas.php',
					  success: function(data) {
					  $('#<?=$_SESSION[$Script2][dialog]?>').html(data);
						  $("#CARREGANDO").html('');
					  }
					});				  
				  
			  }
			});		  
		  
	  }
	});	
	
});		
	
function VoltarCobrancas(){

	$("#CARREGANDO").html('<div id="loader"></div>');
	$.ajax({
	  url: './tabelas/cobrancas/cobrancas.php',
	  success: function(data) {
	  $('#<?=$_SESSION[$Script2][dialog]?>').html(data);
		  $("#CARREGANDO").html('');
		  Filtro_<?=$_SESSION[$Script2][Filtro]?>.close();
	  }
	});	  

}	
	
</script>	
</div>