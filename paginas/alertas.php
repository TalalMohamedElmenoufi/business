<?php
include("../includes/connect.php");	

list($nome) = mysqli_fetch_row(mysqli_query($conexao, "select nome from usuarios where id = '".$_SESSION[id_usuario]."'  "));
list($checar) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from alertas "));
list($maxAlerta) = mysqli_fetch_row(mysqli_query($conexao2, "select max(id) from alertas where lido = 'N' "));
list($Alerta,$Url) = mysqli_fetch_row(mysqli_query($conexao2, "select alerta, url from alertas where id = '".$maxAlerta."'  "));


$alerta = "Seja bem vindo ".($nome);
?>

<style>
	.FecharAlerta{
		float:right !important; 
		cursor:pointer;
		border:#FF0004 solid 0px;
		position:relative;
		margin-left:-10px;
		margin-top:-10px;
		font-size:16px;
	}
	.FecharAlerta:hover{
		color:#F30004;
	}	
	
	.Checar{
		margin-left:8px;
	}	
	
	.Lido{
		margin-left:8px;
	}

</style>


	   <?php
	   if($checar==0){
	   ?>
	   <div id="ParaFechar">

		   <div class="FecharAlerta"> 
			   <i class="far fa-times-circle" id="bannerClose" ></i>
		   </div>
			<span class="d-flex align-items-center purchase-popup">
				   <p>
					   <img src="./img/alert.png" width="30"> Seja bem vindo <b><?=($nome)?></b>!  
					   
						<button type="button" class="btn btn-inverse-success btn-sm Lido">Marcar como lido</button>
				  </p>	
			</span>

	   </div> 

	   <?php
		}elseif($Alerta){
		?>
		
		<div id="ParaFechar">
			
		   <div class="FecharAlerta"> 
			   <i class="far fa-times-circle" id="bannerClose" ></i>
		   </div>
			<span class="d-flex align-items-center purchase-popup">
				   <p>
					   <img src="./img/alert.png" width="30"> <?=($Alerta)?>  
					   
						<button type="button" class="btn btn-inverse-info btn-sm Checar" caminho="<?=$Url?>">Checar</button> 
					   
						<button type="button" class="btn btn-inverse-success btn-sm Lido">Marcar como lido</button>
				   </p>		
			</span>
			
		</div>	
		<?php
		}	
		?>



<script>
$("#bannerClose").click(function(){

	$.ajax({
	type: "POST",
	url: "./acoes/checar_alerta.php",
	data: {cod:'<?=$maxAlerta?>', alerta:'<?=$alerta?>' } ,
	success: function(data) {
	}
	});
	
});
$(".Lido").click(function(){

	$.ajax({
	type: "POST",
	url: "./acoes/checar_alerta.php",
	data: {cod:'<?=$maxAlerta?>', alerta:'<?=$alerta?>' } ,
	success: function(data) {
	}
	});
	$("#ParaFechar").animate({ width:0, height:0, opacity:0 }, 2500);
});
	
$(".Checar").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
	
	var caminho = $(this).attr("caminho");

		$.ajax({
		  url: caminho,
		  success: function(data) {
			  $('#CONTEUDOS').html(data);
			  $("#CARREGANDO").html('');
		  }
		});		
	
});	
</script>
