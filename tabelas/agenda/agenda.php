<?php
include("../../includes/connect.php");

$Conf[script] = 'tabelas/agenda/agenda';
$Script = md5($Conf[script]);
list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao, "select count(id) from agenda  "));
$_SESSION[$Script][tabela] = 'agenda';
$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/agenda/agenda';
$_SESSION[$Script][titulo] = 'Tabela de agenda';


$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
$Md5 = $_SESSION[$Script][Script];


?>


<style>
	
	.card-body{
		padding:4px !important;
	}
	
	.pull-left{
		float:left !important;
	}	
	.pull-right{
		float:right !important;
	}
	
	.badge{
		margin-top:-5px !important;
		margin-right:-1px !important; 
		padding:5px !important;
		border-radius: 15px !important;
		background: rgba(0, 0, 0, 0.50) !important;
		color:#DCDADA !important;
		border: #F90004 solid 0px !important;
		font-size:9px !important;
	}


	
.PesonalMenuDireita {
  position: fixed;
  z-index:9991 !important;	
  right:0;
  top:0 !important;	
  width:100%;	
  color: #fff !important;
  background: rgba(255, 255, 255, 0.95); /* Old browsers */
  background: -moz-linear-gradient(top,  rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95) 50%, rgba(255, 255, 255, 0.95)); /* FF3.6-15 */
  background: -webkit-linear-gradient(top,  rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95) 50%,rgba(255, 255, 255, 0.95)); /* Chrome10-25,Safari5.1-6 */
  background: linear-gradient(to bottom,  rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95) 50%,rgba(255, 255, 255, 0.95)); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='rgba(255, 255, 255, 0.95)', endColorstr='rgba(255, 255, 255, 0.95)',GradientType=0 ); /* IE6-9 */	
	
}	
	
	.FecharAgenda{
		font-size:30px; 
		padding:0; 
		float:right; 
		margin-top:-37px !important;
		margin-right:5px !important;
		cursor:pointer; 
		color:#000000
	}
	
.responsive-calendar .day {
    border: #d1d1d1 solid 1px;
}
	
</style>

	
<nav class="w3-sidebar w3-card PesonalMenuDireita w3-animate-zoom" id="Agendamento" style="display:none">

<div class="w3-container ">

  <i id="FechaAgenda" class="far fa-times-circle FecharAgenda" style=""></i>

  <div id="ConteudoAgenda"></div>	
	
</div>	
	
</nav>



<div class="card" >
<div class="card-body">

	
	
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>

	
	
		<div class="dropdown-divider"></div>

			<div class="container">
			  <!-- Responsive calendar - START -->
				<div class="responsive-calendar">
				<div class="controls">
					<a class="pull-left" data-go="prev"><div class="btn btn-gradient-light btn-sm">Anterior</div></a>
					<h4><span data-head-year></span> <span data-head-month></span></h4>
					<a class="pull-right" data-go="next"><div class="btn btn-gradient-light btn-sm">Próximo</div></a>
				</div><hr/>
				<div class="day-headers">
				  <div class="day header">Seg</div>
				  <div class="day header">Ter</div>
				  <div class="day header">Qua</div>
				  <div class="day header">Qui</div>
				  <div class="day header">Sex</div>
				  <div class="day header">Sab</div>
				  <div class="day header">Dom</div>
				</div>
				<div class="days" data-group="days">

				</div>
			  </div>
			  <!-- Responsive calendar - END -->
			</div>	
	

	</div>
</div>


 


<script language="javascript">


	
$('#FechaAgenda').click(function(){
	$('#Agendamento').css('display','none'); 
});	
	
	
	$.ajax({
	  url: "./tabelas/agenda/json_dados.php",
	  success: function(Retorno) {

		  var RetornoDados = eval('('+Retorno+')');
		  
			/*alet Dados = {  
				"2020-07-10": {"number":2, "url":"#" },
			}*/
			
			
			$(".responsive-calendar").responsiveCalendar({
			  time: '<?=date('Y-m')?>',
			  events: RetornoDados,

				onDayClick: function(events) {

					let Ano = $(this).data('year');
					let Mes = $(this).data('month');
					let Dia = $(this).data('day');

					Mes = ((Mes<9)?'0'+Mes:Mes);
					Dia = ((Dia<9)?'0'+Dia:Dia);	   

					let Data  = Ano+'-'+ Mes+'-'+Dia;				
					 
					 $.confirm({
						title: "",
						content: "url:./tabelas/agenda/agendar.php?data="+Data,
						columnClass:"col-md-10 col-md-offset-1",
						theme: "light",
						buttons: {
							Fechar: {
								btnClass: "btn-warning",
								action: function(){
								}
							},
						}				
					  });					
					
					

				}	

			});				
			
	  }
	});

</script>