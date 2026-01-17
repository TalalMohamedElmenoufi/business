<div id="Salvando" style="overflow-y:auto; overflow-x:hidden; height:700px;">
	<?php
	include("../../includes/connect.php");
	include("../../includes/funcoes.php");

	$Conf['script'] = 'tabelas/agenda/agendar';
	$Script = md5($Conf['script']);

	if (!$_SESSION[$Script]['data']) {
		$_SESSION[$Script]['data'] = $_GET['data'];
	}
	if ($_GET['data']) {
		$_SESSION[$Script]['data'] = $_GET['data'];
	}

	list($_SESSION[$Script]['nr']) = mysqli_fetch_row(mysqli_query($conexao2, "
    SELECT COUNT(id)
    FROM agenda
    WHERE data = '" . $_SESSION[$Script]['data'] . "'
"));
	$_SESSION[$Script]['tabela']   = 'agenda';
	$_SESSION[$Script]['dialog']   = 'CONTEUDOS';
	$_SESSION[$Script]['url']      = 'tabelas/agenda/agenda';
	$_SESSION[$Script]['titulo']   = 'Tabela de agenda';

	$_SESSION[$Script]['Script'] = md5($_SESSION[$Script]['url']);
	$Md5 = $_SESSION[$Script]['Script'];

	if ($_POST) {
		$compartilhar    = implode(",", $_POST['compartilhar']);

		$tipo = isset($_POST['tipo'])
			? mysqli_real_escape_string($conexao2, $_POST['tipo'])
			: '';

		$x_horas         = intval($_POST['x_horas']);

		$repetir_dia    = ($tipo === 'repetir_dia')   ? 1 : 0;
		$repetir_horas  = ($tipo === 'repetir_horas') ? 1 : 0;
		$repetir_semana = ($tipo === 'repetir_semana') ? 1 : 0;
		$repetir_mes    = ($tipo === 'repetir_mes')   ? 1 : 0;
		$result_hora    = ($tipo === 'repetir_horas') ? intval($_POST['x_horas']) : 0;



		mysqli_query($conexao2, "
        INSERT INTO agenda SET
            data            = '" . $_POST['data'] . "',
            hora            = '" . $_POST['hora'] . "',
            lembrete        = '" . mysqli_real_escape_string($conexao2, $_POST['lembrete']) . "',
            compartilhar    = '" . mysqli_real_escape_string($conexao2, $compartilhar) . "',
            repetir_dia     = '{$repetir_dia}',
            repetir_horas   = '{$repetir_horas}',
            repetir_semana  = '{$repetir_semana}',
            repetir_mes     = '{$repetir_mes}',
            x_horas         = '{$result_hora}'
        ");

		echo "<script>parent.VoltarAgenda();</script>";
	}

	$pergunta   = "SELECT * FROM agenda WHERE data = '" . $_SESSION[$Script]['data'] . "' ORDER BY data DESC, hora DESC";
	$resultado  = mysqli_query($conexao2, $pergunta);

	if ($_GET['excluir']) {
		mysqli_query($conexao2, "DELETE FROM agenda WHERE id = '" . $_GET['excluir'] . "'");
		echo "<script>parent.VoltarAgendamento();</script>";
		exit();
	}
	?>

	<style>
		.Tem {
			display: <?= (($_SESSION[$Script]['nr']) ? 'block' : 'none') ?>;
		}

		#Lembretes {
			padding: 5px;
		}


		.TituloLembrete {
			color: #000000;
		}

		.multiselect {
			border: #000000 solid 1px;
			border-radius: 10px;
		}


		.timepicker-hour {
			color: #000000 !important;
		}

		.timepicker-minute {
			color: #000000 !important;
		}

		.hour {
			color: #000000 !important;
		}

		.minute {
			color: #000000 !important;
		}

		.timepicker-second {
			color: #000000 !important;
		}

		.second {
			color: #000000 !important;
		}

		.RemoveAgenda:hover {
			cursor: pointer;
			color: #DF1D20;
		}

		#lembrete {
			padding-left: 35px;
		}


		#campo_x_horas {
			display: none;
			margin-top: 10px;
		}
	</style>

	<div id="Lembretes">
		<div class="form-group">
			<label class="TituloLembrete" for="lembrete">Lembrete:</label>
			<label class="trigger col-md-12" id="lembrete_env" onclick="EmojiClick( this.id )">
				<i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />
				<div class="msg EmojiVer"></div>
			</label>
			<textarea class="form-control" id="lembrete" rows="4" name="lembrete" placeholder="Informe seu lembrete aqui..."></textarea>
		</div>

		<div class="row">
			<div class="col-lg-5 col-md-5">
				<div class="form-group">
					<label class="TituloLembrete">Hora:</label>
					<div id="Horas">
						<input type="hidden" id="HoraSelect" name="hora">
					</div>
				</div>
			</div>
			<div class="col-lg-7 col-md-7">
				<div class="form-group">
					<label class="TituloLembrete">Compartilhar com:</label>
					<select id="descricao" name="compartilhar[]" multiple="multiple">
						<optgroup label="Todos">
							<?php
							$queryc = "SELECT * FROM contatos_agenda GROUP BY telefone ORDER BY nome";
							$resultc = mysqli_query($conexao2, $queryc);
							while ($c = mysqli_fetch_object($resultc)) {
							?>
								<option value="<?= $c->id ?>"><?= $c->nome ?></option>
							<?php } ?>
						</optgroup>
					</select>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="TituloLembrete" for="repetir">Repetir:</label>
			<select class="form-control" id="repetir" name="repetir">
				<option value="">Não repetir</option>
				<option value="repetir_dia">Diariamente</option>
				<option value="repetir_semana">Semanalmente</option>
				<option value="repetir_mes">Mensalmente</option>
				<option value="repetir_horas">A cada horas</option>
			</select>
		</div>

		<div id="campo_x_horas">
			<div class="form-group">
				<label class="TituloLembrete" for="x_horas">Intervalo (horas):</label>
				<input type="number" class="form-control" id="x_horas" name="x_horas" min="1" placeholder="Digite o número de horas">
			</div>
		</div>

		<div class="form-group">
			<button id="SalvarAgenda" type="button" class="btn btn-gradient-success btn-sm">Salvar</button>
		</div>

		<input type="hidden" id="data" name="data" value="<?= $_SESSION[$Script]['data'] ?>">

		<div class="Tem">
			<div class="table-responsive" style="overflow-x:auto;">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Data</th>
							<th>Lembrete</th>
							<th>Reterir</th>
							<th>Ação</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($d = mysqli_fetch_object($resultado)) { ?>
							<tr>
								<td><?= dataBr($d->data) ?> <?= $d->hora ?></td>
								<td><?= $d->lembrete ?></td>
								<td>
								<?php
								$retornoRep = (
									(($d->repetir_dia==1)
										? 'Diariamente' :
										(($d->repetir_semana==1)
											? 'Semanalmente' :
											(($d->repetir_mes==1)
												? 'Mensalmente' :
												(($d->repetir_horas==1)
												? `A cada $d->x_horas horas` : 'Sem repetição!'
												)
											)
										)
									)
								);
								echo $retornoRep;
								?>
								</td>
								<td><i class="far fa-trash-alt RemoveAgenda" Cod="<?= $d->id ?>"></i></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script>
		/*Ações Emojis*/
		function EmojiClick(dados) {

			var str = dados;
			var Campo = str.split("_");

			$.ajax({
				url: "./_emoji/emoji.php?Campo=" + Campo[0],
				success: function(data) {
					$(".EmojiVer").html(data);
					$('body>.tooltip').remove();
				}
			});
		}

		function MenuEmoji(dados) {

			var str = dados;
			var res = str.split("-");

			$.ajax({
				url: "./_emoji/emoji.php?Cod=" + res[0],
				success: function(data) {
					$(".EmojiVer").html(data);
					$('body>.tooltip').remove();
				}
			});

		}

		function InsertEmoji(dados) {
			var str = dados;
			var res = str.split("|");

			let ConteudoAt = $("#" + res[0]).val();

			let Conteudo = ConteudoAt + ' ' + res[1];

			$("#" + res[0]).val(Conteudo);

		}
		/*Fim Ações Emojis*/


		$('#descricao').multiselect({
			maxHeight: 330,
			enableFiltering: true,
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			//includeSelectAllOption: true,
			buttonText: function(options, select) {
				if (options.length === 0) {

					return 'Seus contatos';

				} else if (options.length > 2) {

					return options.length + ' selecionados!';

				} else {
					var labels = [];
					options.each(function() {
						if ($(this).attr('label') !== undefined) {
							labels.push($(this).attr('label'));

						} else {
							labels.push($(this).html());

						}
					});
					return labels.join(', ');

				}

			}
		});

		$('#lembrete').focus();


		$('#Horas').datetimepicker({
			format: 'LT',
			inline: true,
			sideBySide: true,
			useCurrent: true,
		});

		$('#repetir').on('change', function() {
			if (this.value === 'repetir_horas') {
				$('#campo_x_horas').show();
			} else {
				$('#campo_x_horas').hide();
				$('#x_horas').val('');
			}
		});

		$("#SalvarAgenda").click(function() {
			let data = $("#data").val();
			let lembrete = $("#lembrete").val();
			let Horas = $("#HoraSelect").val(); // formato “HH:mm AM/PM”
			let descricao = $("#descricao").val();

			// processa hora
			let parts = Horas.split(" ");
			let hm = parts[0].split(":");
			let H = (parts[1] === "PM" && hm[0] !== "12") ? (parseInt(hm[0]) + 12) : (parts[1] === "AM" && hm[0] === "12" ? 0 : parseInt(hm[0]));
			let HoraCerta = (H < 10 ? '0' + H : H) + ":" + hm[1];

			let tipo = $("#repetir").val();
			let x_horas = $("#x_horas").val();

			if (!lembrete) {
				alert("Informe seu lembrete.");
				return;
			}
			if (!descricao) {
				alert("Selecione com quem compartilhar.");
				return;
			}

			$.ajax({
				type: "POST",
				url: "./tabelas/agenda/agendar.php",
				data: {
					data: data,
					hora: HoraCerta,
					lembrete: lembrete,
					compartilhar: descricao,
					tipo: tipo,
					x_horas: x_horas
				},
				beforeSend: function() {
					$("#Salvando").html("<div id='loader'><img src='./img/loader.gif' width='120'></div>");
				},
				success: function(data) {
					$("#Salvando").html(data);
				}
			});
		});

		function VoltarAgenda() {
			$("#CARREGANDO").html("<div id='loader'><img src='./img/loader.gif' width='120'></div>");
			$.ajax({
				url: "./tabelas/agenda/agenda.php",
				success: function(data) {
					$('#CONTEUDOS').html(data);
					$.ajax({
						url: "./tabelas/agenda/agendar.php",
						success: function(data) {
							$('#Salvando').html(data);
							$("#CARREGANDO").html('');
						}
					});
				}
			});
		}

		$(".RemoveAgenda").click(function() {
			let Cod = $(this).attr("Cod");
			$("#CARREGANDO").html("<div id='loader'><img src='./img/loader.gif' width='120'></div>");
			$.ajax({
				url: "./tabelas/agenda/agendar.php?excluir=" + Cod,
				success: function(data) {
					$('#Salvando').html(data);
					$.ajax({
						url: "./tabelas/agenda/agenda.php",
						success: function(data) {
							$('#CONTEUDOS').html(data);
							$("#CARREGANDO").html('');
						}
					});
				}
			});
		});

		function VoltarAgendamento() {
			$.ajax({
				url: "./tabelas/agenda/agendar.php",
				success: function(data) {
					$('#Salvando').html(data);
				}
			});
		}
	</script>