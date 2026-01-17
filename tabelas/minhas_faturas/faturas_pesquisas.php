<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');



?>

<style>

	
</style>

<div class="card" >
<div class="card-body">

<div class="row">	
	<div class="col-lg-12 col-md-12" style="border: #FF0004 solid 0px; padding:0;"> 
		<div class="panel-heading">
			
		<i class="fas fa-shopping-cart mdi-24px "></i> Pesquisas
		</div>

		<button type="button" class="btn btn-gradient-primary btn-icon-text btn-sm Home" style="float:right">HOME</button> 
	</div>
</div>	
	
<div class="row">

<button type="button" id="MeusPlanos" class="btn btn-success">Meus planos</button> 	

	
<div class="col-lg-12 col-md-12" style="margin-top:10px;">

	
<center>EM DESENVOLVIMENTO...</center>
	
	
</div>	
	
	
</div>
	
</div>	
</div>

<script>

	
$("#MeusPlanos").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

	$.ajax({
	  url: "./tabelas/ecommerce/pesquisa.php",
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
	  $("#CARREGANDO").html('');
	  }
	});
	
});
	
	
	
$(".Home").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

	$.ajax({
	  url: "./paginas/pagina_inicial.php",
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
	  $("#CARREGANDO").html('');
	  }
	});
	
});	
	

</script>