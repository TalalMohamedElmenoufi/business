<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');

$Conf[script] = 'tabelas/financeiro/financeiro';
$Script = md5($Conf[script]);

$dataHoje = date('Y-m');

list($emDia) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from cobrancas WHERE dueDate like '%$dataHoje%' and status!='OVERDUE' "));

list($previstas) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from cobrancas WHERE dueDate like '%$dataHoje%' "));

list($recebidas,$recebidos) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg), sum(value) from cobrancas WHERE dueDate like '%$dataHoje%' and status='RECEIVED' or dueDate like '%$dataHoje%' and status='RECEIVED_IN_CASH' "));

list($cartaoRepasse) = mysqli_fetch_row(mysqli_query($conexao2, "select sum(value) from cobrancas WHERE dueDate like '%$dataHoje%' and status='CONFIRMED' or dueDate like '%$dataHoje%' and status='AWAITING_CHARGEBACK_REVERSAL' "));

list($previsto) = mysqli_fetch_row(mysqli_query($conexao2, "select sum(value) from cobrancas WHERE dueDate like '%$dataHoje%' "));

list($inadimplentes) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from cobrancas WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));

list($vencidas) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from cobrancas WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));
?>



<style>

	.TituloF{
		margin-bottom:10px;
		font-size: 24px;
		font-weight: 600;
		color: rgba(0, 0, 0, 0.54);		
	}	
	
.Sombra{
	border:rgba(0, 0, 0, 0.30) 1px solid;; 
	padding: 10px;
	box-shadow: 2px 4px;
}

	.IconDest{
		font-size:26px;
		color:#858585;
	}
	
	.bordaFinc{
		margin-top:3px;
		border:#dadada solid 1px;
		border-radius:5px;
		padding:3px;
	}
	
	.TituloFinc{
		margin-top:8px;
		color:#858585;
	}
	
	.NumbFinc{
		font-size: 70px;
		color:#228B09;
		text-align:center;
		font-weight: bold;	
	}
	.NumbFinc2{
		font-size: 70px;
		color:#c75d5d;
		text-align:center;
		font-weight: bold;	
	}
	
	.NumbCobranca1{
		font-size: 30px;
		color:#6e97aa;
		text-align:center;
		font-weight: bold;
		padding:3px;
	}
	.NumbCobranca2{
		font-size: 30px;
		color:#c75d5d;
		text-align:center;
		font-weight: bold;	
		padding:3px;
	}
	.NumbCobranca3{
		font-size: 30px;
		color:#228b09;
		text-align:center;
		font-weight: bold;
		padding:3px;
	}
	.NumbConfirmado{
		font-size: 30px;
		color:#ff9f01;
		text-align:center;
		font-weight: bold;
		padding:3px;
	}
	
	.Sig{
		font-size:15px !important;
	}
	
	.INFOREC{
		position:relative;
		float:right;
		cursor:pointer;
	}
</style>



<div class="card" >
<div class="card-body">

<div class="row">
	<div class="TituloF">
		RESUMO FINANCEIRO
	</div>
</div>	

<div class="row">	

	
	<div class="card col-lg-4 col-sm-6 Sombra">	
	  <div class="card-header"><i class="fas fa-users IconDest"></i> Clientes</div>
		
	   <div class="bordaFinc">
		   <div class="TituloFinc">EM DIA</div>
		   <div class="NumbFinc"><?=$emDia?></div>		
	   </div>
		
	   <div class="bordaFinc">
		   <div class="TituloFinc">INADIMPLENTES</div>
		   <div class="NumbFinc2"><?=$inadimplentes?></div>		
	   </div>	

		
	</div>
	
	
	<div class="card col-lg-4 col-sm-6 Sombra">
	  <div class="card-header"><i class="far fa-money-bill-alt IconDest"></i> Cobranças</div>

	   <div class="bordaFinc">
		   <div class="TituloFinc">PREVISTAS</div>
		   <div class="NumbCobranca1"><?=$previstas?></div>		
	   </div>
	   <div class="bordaFinc">
		   <div class="TituloFinc">VENCIDAS</div>
		   <div class="NumbCobranca2"><?=$vencidas?></div>		
	   </div>
	   <div class="bordaFinc">
		   <div class="TituloFinc">RECEBIDAS</div>
		   <div class="NumbCobranca3"><?=$recebidas?></div>		
	   </div>		
		
	</div>	
	
	
	<div class="card col-lg-4 col-sm-6 Sombra">
	  <div class="card-header"><i class="far fa-chart-bar IconDest"></i> Faturamento</div>

	   <div class="bordaFinc">
		   <div class="TituloFinc">PREVISTO</div>
		   <div class="NumbCobranca1"><span class="Sig">R$</span> <?=number_format($previsto,2,",",".")?></div>		
	   </div>
	   <div class="bordaFinc">
		   <i class="far fa-question-circle INFOREC" data-toggle="tooltip" data-placement="top" title="Cobranças recebidas via cartão de crédito e boleto que estão aguardando o repasse."></i>
		   <div class="TituloFinc">CONFIRMADO</div>
		   <div class="NumbConfirmado"><span class="Sig">R$</span> <?=number_format($cartaoRepasse,2,",",".")?></div>		
	   </div>
	   <div class="bordaFinc">
		   <div class="TituloFinc">RECEBIDO</div>
		   <div class="NumbCobranca3"><span class="Sig">R$</span> <?=number_format($recebidos,2,",",".")?></div>		
	   </div>		
		
	</div>	
	

</div>	
	
</div>
</div>
	
<script>
$('[data-toggle="tooltip"]').tooltip();
</script>	
	