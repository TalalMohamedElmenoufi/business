<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');

$Conf[script] = 'tabelas/ecommerce/whatsapp';
$Script = md5($Conf[script]);

$pergunta = "select * from whatsapp_planos order by ordem";
$resultado = mysqli_query($conexao2, $pergunta);

?>

<style>
	.ValorPlano{
		font-size:25px;
	}
	.Comprar{
		float:right;
		
	}
</style>

<div class="card" >
<div class="card-body">

<div class="row">	
	<div class="col-lg-12 col-md-12" style="border: #FF0004 solid 0px; padding:15px;"> 
		<div class="panel-heading">
			WhatsApp
		</div>

		<button type="button" class="btn btn-gradient-primary btn-icon-text btn-sm Home" style="float:right">HOME</button> 
	</div>
</div>	
	
<div class="row">
	
<?php
while ($d = mysqli_fetch_object($resultado)){
	
$valorPlano = ($d->quantidade * $d->valor_unitario) ; 	
?>	
	
	<div class="col-lg-4 col-md-4 w3-card " >	

			<h4 class="card-title"><?=$d->nome?></h4>
			<p class="card-description">Plano no valor R$<code class="ValorPlano"><?=number_format($valorPlano,2,",",".")?></code><br>
		
			<span>R$<code style="color:#010096"><?=number_format($d->valor_unitario,4,",",".")?></code>/whats</span>	
		    </p>

			<ul class="list-ticked">
			  <li>Dispara para aniversariantes.</li>
			  <li>Agendamento de eventos.</li>
			  <li>Cobranças aos clientes.</li>
			  <li>Alertas agendas particulares.</li>
			  <li>Dentre outros ...</li>
			</ul>
		
		  <div style="height:40px;" >
			<button type="button" class="btn btn-gradient-info btn-sm Comprar" Cod="<?=$d->id?>" ValorPlano="<?=number_format($valorPlano,2,",",".")?>"  ValorWhast="<?=number_format($d->valor_unitario,4,",",".")?>"   ValorPlanoSend="<?=$valorPlano?>" QuantidadePlano="<?=$d->quantidade?>">Comprar</button>
		  </div>
		  
	</div>
 
<?php
}
?>		

	
</div>	
	
	
</div>
</div>	

<script>
$(".Comprar").click(function(){

	let Cod = $(this).attr("Cod");
	let ValorPlano = $(this).attr("ValorPlano");
	let ValorWhast = $(this).attr("ValorWhast");
	let ValorPlanoSend = $(this).attr("ValorPlanoSend");
	let QuantidadePlano = $(this).attr("QuantidadePlano");
	
     SolicitandoCredir = $.confirm({
		title: "<span style='color:green'>INFORMAÇÕES!</span>",
		content: "url:./tabelas/ecommerce/info_vencimento_whats.php?Cod="+Cod+"&ValorPlano="+ValorPlano+"&ValorWhast="+ValorWhast+"&ValorPlanoSend="+ValorPlanoSend+"&QuantidadePlano="+QuantidadePlano,
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Cancelar: {
				btnClass: "btn-danger",
				action: function(){
				}
			},
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