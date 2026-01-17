<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');

$Conf[script] = 'tabelas/financeiro/financeiro';
$Script = md5($Conf[script]);

$dataHoje = date('Y-m');


$cobrancasExternas = "select * from usuarios ";
$RcobrancasExternas = mysqli_query($conexao, $cobrancasExternas);
while ($C = mysqli_fetch_object($RcobrancasExternas)) {

	$con = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_" . $C->id);

	list($emDia_pesquisa) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_pesquisa WHERE dueDate like '%$dataHoje%' and status!='OVERDUE' "));

	list($emDia_sms) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_sms WHERE dueDate like '%$dataHoje%' and status!='OVERDUE' "));

	list($emDia_whatsapp) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_whatsapp WHERE dueDate like '%$dataHoje%' and status!='OVERDUE' "));

	list($emDia_email) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_email WHERE dueDate like '%$dataHoje%' and status!='OVERDUE' "));

	$SomaCob_emDia = $emDia_sms + $emDia_pesquisa + $emDia_whatsapp + $emDia_email;
	$CobrancasEx += $SomaCob_emDia;
	/*---------------------------------------------------------------------------*/

	list($previstas_pesquisa) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_pesquisa WHERE dueDate like '%$dataHoje%' "));

	list($previstas_sms) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_sms WHERE dueDate like '%$dataHoje%' "));

	list($previstas_whatsapp) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_whatsapp WHERE dueDate like '%$dataHoje%' "));

	list($previstas_email) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_email WHERE dueDate like '%$dataHoje%' "));

	$SomaCob_previstas = $previstas_pesquisa + $previstas_sms + $previstas_whatsapp + $previstas_email;
	$PrevistasEx += $SomaCob_previstas;
	/*---------------------------------------------------------------------------*/


	list($recebidas_pesquisa, $recebidos_pesquisa) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg), sum(value) from asaas_cobranca_pesquisa WHERE dueDate like '%$dataHoje%' and status='RECEIVED' or dueDate like '%$dataHoje%' and status='RECEIVED_IN_CASH' "));

	list($recebidas_sms, $recebidos_sms) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg), sum(value) from asaas_cobranca_sms WHERE dueDate like '%$dataHoje%' and status='RECEIVED' or dueDate like '%$dataHoje%' and status='RECEIVED_IN_CASH' "));

	list($recebidas_whatsapp, $recebidos_whatsapp) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg), sum(value) from asaas_cobranca_whatsapp WHERE dueDate like '%$dataHoje%' and status='RECEIVED' or dueDate like '%$dataHoje%' and status='RECEIVED_IN_CASH' "));

	list($recebidas_email, $recebidos_email) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg), sum(value) from asaas_cobranca_email WHERE dueDate like '%$dataHoje%' and status='RECEIVED' or dueDate like '%$dataHoje%' and status='RECEIVED_IN_CASH' "));

	$SomaCob_recebidas_pesquisa = $recebidas_pesquisa + $recebidas_sms + $recebidas_whatsapp + $recebidas_email;
	$RecebidasEx += $SomaCob_recebidas_pesquisa;

	$SomaCob_recebidos_pesquisa = $recebidos_pesquisa + $recebidos_sms + $recebidos_whatsapp + $recebidos_email;
	$RecebidosEx += $SomaCob_recebidos_pesquisa;
	/*---------------------------------------------------------------------------*/


	list($cartaoRepasse_pesquisa) = mysqli_fetch_row(mysqli_query($con, "select sum(value) from asaas_cobranca_pesquisa WHERE dueDate like '%$dataHoje%' and status='CONFIRMED' or dueDate like '%$dataHoje%' and status='AWAITING_CHARGEBACK_REVERSAL' "));

	list($cartaoRepasse_sms) = mysqli_fetch_row(mysqli_query($con, "select sum(value) from asaas_cobranca_sms WHERE dueDate like '%$dataHoje%' and status='CONFIRMED' or dueDate like '%$dataHoje%' and status='AWAITING_CHARGEBACK_REVERSAL' "));

	list($cartaoRepasse_whatsapp) = mysqli_fetch_row(mysqli_query($con, "select sum(value) from asaas_cobranca_whatsapp WHERE dueDate like '%$dataHoje%' and status='CONFIRMED' or dueDate like '%$dataHoje%' and status='AWAITING_CHARGEBACK_REVERSAL' "));

	list($cartaoRepasse_email) = mysqli_fetch_row(mysqli_query($con, "select sum(value) from asaas_cobranca_email WHERE dueDate like '%$dataHoje%' and status='CONFIRMED' or dueDate like '%$dataHoje%' and status='AWAITING_CHARGEBACK_REVERSAL' "));

	$SomaCob_cartaoRepasse = $cartaoRepasse_pesquisa + $cartaoRepasse_sms + $cartaoRepasse_whatsapp + $cartaoRepasse_email;
	$CartaoRepasseEx += $SomaCob_cartaoRepasse;
	/*---------------------------------------------------------------------------*/

	list($previsto_pesquisa) = mysqli_fetch_row(mysqli_query($con, "select sum(value) from asaas_cobranca_pesquisa WHERE dueDate like '%$dataHoje%' "));

	list($previsto_sms) = mysqli_fetch_row(mysqli_query($con, "select sum(value) from asaas_cobranca_sms WHERE dueDate like '%$dataHoje%' "));

	list($previsto_whatsapp) = mysqli_fetch_row(mysqli_query($con, "select sum(value) from asaas_cobranca_whatsapp WHERE dueDate like '%$dataHoje%' "));

	list($previsto_email) = mysqli_fetch_row(mysqli_query($con, "select sum(value) from asaas_cobranca_email WHERE dueDate like '%$dataHoje%' "));

	$SomaCob_previsto = $previsto_pesquisa + $previsto_sms + $previsto_whatsapp + $previsto_email;
	$PrevistoEx += $SomaCob_previsto;
	/*---------------------------------------------------------------------------*/

	list($inadimplentes_pesquisa) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_pesquisa WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));

	list($inadimplentes_sms) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_sms WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));

	list($inadimplentes_whatsapp) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_whatsapp WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));

	list($inadimplentes_email) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_email WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));

	$SomaCob_inadimplentes = $inadimplentes_pesquisa + $inadimplentes_sms + $inadimplentes_whatsapp + $inadimplentes_email;
	$InadimplentesEx += $SomaCob_inadimplentes;
	/*---------------------------------------------------------------------------*/

	list($vencidas_pesquisa) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_pesquisa WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));

	list($vencidas_sms) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_sms WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));

	list($vencidas_whatsapp) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_whatsapp WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));

	list($vencidas_email) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_email WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));

	$SomaCob_vencidas = $vencidas_pesquisa + $vencidas_sms + $vencidas_whatsapp + $vencidas_email;
	$VencidasEx += $SomaCob_vencidas;
	/*---------------------------------------------------------------------------*/
}


list($emDia_in) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from cobrancas WHERE dueDate like '%$dataHoje%' and status!='OVERDUE' "));
$emDia = $CobrancasEx + $emDia_in;
/*---------------------------------------------------------------------------*/

list($previstas_in) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from cobrancas WHERE dueDate like '%$dataHoje%' "));
$previstas = $PrevistasEx + $previstas_in;
/*---------------------------------------------------------------------------*/

list($recebidas_in, $recebidos_in) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg), sum(value) from cobrancas WHERE dueDate like '%$dataHoje%' and status='RECEIVED' or dueDate like '%$dataHoje%' and status='RECEIVED_IN_CASH' "));
$recebidas = $RecebidasEx + $recebidas_in;
$recebidos = $RecebidosEx + $recebidos_in;
/*---------------------------------------------------------------------------*/

list($cartaoRepasse_in) = mysqli_fetch_row(mysqli_query($conexao2, "select sum(value) from cobrancas WHERE dueDate like '%$dataHoje%' and status='CONFIRMED' or dueDate like '%$dataHoje%' and status='AWAITING_CHARGEBACK_REVERSAL' "));
$cartaoRepasse = $CartaoRepasseEx + $cartaoRepasse_in;
/*---------------------------------------------------------------------------*/

list($previsto_in) = mysqli_fetch_row(mysqli_query($conexao2, "select sum(value) from cobrancas WHERE dueDate like '%$dataHoje%' "));
$previsto = $PrevistoEx + $previsto_in;
/*---------------------------------------------------------------------------*/

list($inadimplentes_in) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from cobrancas WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));
$inadimplentes = $InadimplentesEx + $inadimplentes_in;
/*---------------------------------------------------------------------------*/

list($vencidas_in) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from cobrancas WHERE dueDate like '%$dataHoje%'  and status='OVERDUE' "));
$vencidas = $VencidasEx + $vencidas_in;
/*---------------------------------------------------------------------------*/
?>



<style>
	.TituloF {
		margin-bottom: 10px;
		font-size: 24px;
		font-weight: 600;
		color: rgba(0, 0, 0, 0.54);
	}

	.Sombra {
		border: rgba(0, 0, 0, 0.30) 1px solid;
		;
		padding: 10px;
		box-shadow: 2px 4px;
	}

	.IconDest {
		font-size: 26px;
		color: #858585;
	}

	.bordaFinc {
		margin-top: 3px;
		border: #dadada solid 1px;
		border-radius: 5px;
		padding: 3px;
	}

	.TituloFinc {
		margin-top: 8px;
		color: #858585;
	}

	.NumbFinc {
		font-size: 70px;
		color: #228B09;
		text-align: center;
		font-weight: bold;
	}

	.NumbFinc2 {
		font-size: 70px;
		color: #c75d5d;
		text-align: center;
		font-weight: bold;
	}

	.NumbCobranca1 {
		font-size: 30px;
		color: #6e97aa;
		text-align: center;
		font-weight: bold;
		padding: 3px;
	}

	.NumbCobranca2 {
		font-size: 30px;
		color: #c75d5d;
		text-align: center;
		font-weight: bold;
		padding: 3px;
	}

	.NumbCobranca3 {
		font-size: 30px;
		color: #228b09;
		text-align: center;
		font-weight: bold;
		padding: 3px;
	}

	.NumbConfirmado {
		font-size: 30px;
		color: #ff9f01;
		text-align: center;
		font-weight: bold;
		padding: 3px;
	}

	.Sig {
		font-size: 15px !important;
	}

	.INFOREC {
		position: relative;
		float: right;
		cursor: pointer;
	}

	#vlConta {
		color: #2D6600;
		font-size: 18px;
	}
</style>



<div class="card">
	<div class="card-body">

		<div class="row">
			<div class="TituloF">
				RESUMO FINANCEIRO
			</div>
			<div style="width:100%; height:100%; right:0; text-align:right">
				Saldo em conta<br>
				<span id="vlConta"></span>
			</div>
		</div>

		<div class="row">


			<div class="card col-lg-4 col-sm-6 Sombra">
				<div class="card-header"><i class="fas fa-users IconDest"></i> Clientes</div>

				<div class="bordaFinc">
					<div class="TituloFinc">EM DIA</div>
					<div class="NumbFinc"><?= $emDia ?></div>
				</div>

				<div class="bordaFinc">
					<div class="TituloFinc">INADIMPLENTES</div>
					<div class="NumbFinc2"><?= $inadimplentes ?></div>
				</div>


			</div>


			<div class="card col-lg-4 col-sm-6 Sombra">
				<div class="card-header"><i class="far fa-money-bill-alt IconDest"></i> Cobranças</div>

				<div class="bordaFinc">
					<div class="TituloFinc">PREVISTAS</div>
					<div class="NumbCobranca1"><?= $previstas ?></div>
				</div>
				<div class="bordaFinc">
					<div class="TituloFinc">VENCIDAS</div>
					<div class="NumbCobranca2"><?= $vencidas ?></div>
				</div>
				<div class="bordaFinc">
					<div class="TituloFinc">RECEBIDAS</div>
					<div class="NumbCobranca3"><?= $recebidas ?></div>
				</div>

			</div>


			<div class="card col-lg-4 col-sm-6 Sombra">
				<div class="card-header"><i class="far fa-chart-bar IconDest"></i> Faturamento</div>

				<div class="bordaFinc">
					<div class="TituloFinc">PREVISTO</div>
					<div class="NumbCobranca1"><span class="Sig">R$</span> <?= number_format($previsto, 2, ",", ".") ?></div>
				</div>
				<div class="bordaFinc">
					<i class="far fa-question-circle INFOREC" data-toggle="tooltip" data-placement="top" title="Cobranças recebidas via cartão de crédito e boleto que estão aguardando o repasse."></i>
					<div class="TituloFinc">CONFIRMADO</div>
					<div class="NumbConfirmado"><span class="Sig">R$</span> <?= number_format($cartaoRepasse, 2, ",", ".") ?></div>
				</div>
				<div class="bordaFinc">
					<div class="TituloFinc">RECEBIDO</div>
					<div class="NumbCobranca3"><span class="Sig">R$</span> <?= number_format($recebidos, 2, ",", ".") ?></div>
				</div>

			</div>


		</div>

	</div>
</div>

<script>
	$('[data-toggle="tooltip"]').tooltip();

	$('#vlConta').html('<div class="spinner-border" style="width:1.2rem;height:1.2rem;border:0.12em solid currentColor;border-right-color:transparent;color:#228b09"></div>');
	$.ajax({
		url: './acoes/asaas_saldo.php',
		success: function(data) {
			$('#vlConta').html(data);
		}
	});
</script>