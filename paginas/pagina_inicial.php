<?php
include("../includes/connect.php");	

$pergunta = "select * from usuarios where id = '".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);

list($pesquisasCads) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from pesquisa_bot  "));

list($respondidasCads) = mysqli_fetch_row(mysqli_query($conexao2, "select count(participante) from resposta_user_bot_".date('Y')."  "));


list($MesSMS) = mysqli_fetch_row(mysqli_query($conexao2, "select count(codigo) from t_".date('mY')."_smsStatuses  "));


$res = mysqli_query( $conexao2, 'SHOW TABLES' );
while( $row = mysqli_fetch_array($res) ){

	$parte = explode("_",$row[0]);
	if($parte[2]=="smsStatuses"){

		list($GeralSMS) = mysqli_fetch_row(mysqli_query($conexao2, "select count(codigo) from ".$row[0]."  "));
		$TotalSMS += $GeralSMS;
	}
	
}

?>
<style>
	.TituloCred{
		font-size:22px !important;
	}

	.Creds{
		font-size:17px !important;
	}
	
</style>

<div class="card" >
<div class="card-body">


	<div class="page-header">
	  <h3 class="page-title">
		<span class="page-title-icon bg-gradient-primary text-white mr-2">
		  <i class="mdi mdi-home"></i>
		</span> Dashboard </h3>
	  <nav aria-label="breadcrumb">
		<ul class="breadcrumb">
		  <li class="breadcrumb-item active" aria-current="page">
			<span></span>Visão geral <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
		  </li>
		</ul>
	  </nav>
	</div>

	
	<div class="row">	

	  <div class="col-md-4 stretch-card grid-margin">
		<div class="card bg-gradient-dark card-img-holder text-white">
		  <div class="card-body">
			<h4 class="font-weight-normal mb-3"> Realizar compras <i class="fas fa-shopping-cart mdi-24px float-right"></i>
			</h4>

			  
			 <h6 class="card-text"> 
			  <button Whats type="button" class="btn btn-gradient-success btn-icon-text btn-sm ComprarWhatsApp" style="width:100%">
				  Whastsap <i class="fab fa-whatsapp mdi-24px float-right"></i>
			  </button> 
			 </h6>	
			  
			 <h6 class="card-text"> 
			  <button type="button" class="btn btn-gradient-info btn-icon-text btn-sm ComprarSms" style="width:100%">
				  SMS Corporativo <i class="far fa-comment-alt mdi-24px float-right"></i>
			  </button> 
			 </h6>	
			  
			 <h6 class="card-text"> 
			  <button type="button" class="btn btn-gradient-primary btn-icon-text btn-sm ComprarEmail" style="width:100%">
				  E-mail Marketing <i class="far fa-envelope mdi-24px float-right"></i>
			  </button> 
			 </h6>

			 <h6 class="card-text"> 
			  <button type="button" class="btn btn-gradient-warning btn-icon-text btn-sm ComprarPesquisa" style="width:100%">
				  Pesquisas <i class="mdi mdi-bookmark-outline mdi-18px float-right"></i>
			  </button> 
			 </h6>

			  
		  </div>
		</div>
	  </div>		
		
		
	  <div class="col-md-4 stretch-card grid-margin">
		<div class="card bg-gradient-danger card-img-holder text-white">
		  <div class="card-body">
			<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
			<h4 class="font-weight-normal mb-3">Seus créditos <i class="fas fa-hand-holding-usd mdi-24px float-right"></i>
			</h4>
			  
			<h2 class="mb-5"> 
				<span class="TituloCred" >De Pesquisa<?=(($d->creditos>1)?'s':'')?></span>  <span class="Creds" ><?=number_format($d->creditos,0,".",".")?></span> <br>
			  
				<span class="TituloCred" >De Whatsapp<?=(($d->creditos_msg>1)?'s':'')?></span>  <span class="Creds"><?=number_format($d->creditos_msg,0,".",".")?></span> <br>
			  
				<span class="TituloCred" >De SMS<?=(($d->creditos_sms>1)?'s':'')?></span>  <span class="Creds"><?=number_format($d->creditos_sms,0,".",".")?></span><br>
			  
				<span class="TituloCred" >De E-mail<?=(($d->credito_email>1)?'s':'')?></span>  <span class="Creds"><?=number_format($d->credito_email,0,".",".")?></span>
				
			</h2>

			  
			<h6 class="card-text">Desconto de 15%</h6>
		  </div>
		</div>
	  </div>

		
	  <div class="col-md-4 stretch-card grid-margin">
		<div class="card bg-gradient-success card-img-holder text-white">
		  <div class="card-body">
			<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
			<h4 class="font-weight-normal mb-3">Total whatsApp  <i class="fab fa-whatsapp mdi-24px float-right"></i>
			</h4>
			<h5 class="font-weight-normal mb-3">
				Geral enviados  0
			</h5> 
			<h3 class="mb-5">
				Enviados neste mês 0
			</h3>
		  </div>
		</div>
	  </div>		
		
	  <div class="col-md-4 stretch-card grid-margin">
		<div class="card bg-gradient-info card-img-holder text-white">

		  <div class="card-body">

			<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" style="z-index:1" />
			<h4 class="font-weight-normal mb-3">
				Total de SMSs <i class="far fa-comment-alt mdi-24px float-right"></i>
			</h4>
			<h5 class="font-weight-normal mb-3">
				Geral enviados <?=number_format($TotalSMS,0,".",".")?>
			</h5> 
			  
			<h3 class="mb-5">
				Enviados neste mês <?=number_format($MesSMS,0,".",".")?>
			</h3>
			  
		  </div>

				
			
			
		</div>
	  </div>


		

	  <div class="col-md-4 stretch-card grid-margin">
		<div class="card bg-gradient-warning card-img-holder text-white">
		  <div class="card-body">
			<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
			<h4 class="font-weight-normal mb-3">Total de pesquisas <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
			</h4>
			<h2 class="mb-5"><?=$pesquisasCads?></h2>
			<h6 class="card-text"> </h6>
		  </div>
		</div>
	  </div>
		
	  <div class="col-md-4 stretch-card grid-margin">
		<div class="card bg-gradient-primary card-img-holder text-white">
		  <div class="card-body">
			<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
			<h4 class="font-weight-normal mb-3">Pesquisas respondidas  <i class="mdi mdi-diamond mdi-24px float-right"></i>
			</h4>
			<h2 class="mb-5"><?=$respondidasCads?></h2>
			<h6 class="card-text"> </h6>
		  </div>
		</div>
	  </div>		
		 
		

	</div>
	
</div>
</div>

<script>
$('[data-toggle="tooltip"]').tooltip();	
	
$(".ComprarWhatsApp").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

	$.ajax({
	  url: "./tabelas/ecommerce/whatsapp.php",
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
	  $("#CARREGANDO").html(''); 
	  }
	});
	
});
	
$(".ComprarSms").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

	$.ajax({
	  url: "./tabelas/ecommerce/sms.php",
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
	  $("#CARREGANDO").html('');
	  }
	});
	
});
	
$(".ComprarEmail").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

	$.ajax({
	  url: "./tabelas/ecommerce/email.php",
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
	  $("#CARREGANDO").html('');
	  }
	});
	
});	
	
	
$(".ComprarPesquisa").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

	$.ajax({
	  url: "./tabelas/ecommerce/pesquisa.php",
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
	  $("#CARREGANDO").html('');
	  }
	});
	
});

</script>