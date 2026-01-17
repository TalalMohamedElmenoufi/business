<div id="Salvando" style="overflow-y:auto; overflow-x:hidden; height:700px;">
<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");
	
	
$Conf['script'] = 'tabelas/agenda/agendar';
$Script = md5($Conf['script']);

if(!$_SESSION[$Script]['data']){$_SESSION[$Script]['data'] = $_GET['data'];}
if($_GET['data']){$_SESSION[$Script]['data'] = $_GET['data'];}	

list($_SESSION[$Script]['nr']) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from agenda where data = '".$_SESSION[$Script]['data']."'  "));
$_SESSION[$Script]['tabela'] = 'agenda';
$_SESSION[$Script]['dialog'] = 'CONTEUDOS';
$_SESSION[$Script]['url'] = 'tabelas/agenda/agenda';
$_SESSION[$Script]['titulo'] = 'Tabela de agenda';

$_SESSION[$Script]['Script'] = md5($_SESSION[$Script]['url']);
$Md5 = $_SESSION[$Script]['Script'];

if($_POST){
	
	$compartilhar = implode(",",$_POST['compartilhar']);
	
	mysqli_query($conexao2, " 
	insert into agenda set
	data='".$_POST['data']."',
	hora='".$_POST['hora']."',
	lembrete='".$_POST['lembrete']."',
	compartilhar='".$compartilhar."'
	");
	
	echo "<script>parent.VoltarAgenda();</script>";
	
}

	
	
$pergunta = "select * from agenda where data = '".$_SESSION[$Script]['data']."' order by data desc, hora desc ";
$resultado = mysqli_query($conexao2, $pergunta);	
	
	
if($_GET['excluir']){
	mysqli_query($conexao2,"delete from agenda where id = '".$_GET['excluir']."' ");
	echo "<script>parent.VoltarAgendamento();</script>";
	exit();
}
?>

<style>
	
	.Tem{
		display:<?=(($_SESSION[$Script]['nr'])?'block':'none')?>;
	}
	
	#Lembretes{
		padding:5px;
	}
	

	.TituloLembrete{
		color:#000000;
	}

	.multiselect{
		border:#000000 solid 1px;
		border-radius:10px;
	}
	

	.timepicker-hour{
		color:#000000 !important;
	}
	.timepicker-minute{
		color:#000000 !important;
	}
	.hour{
		color:#000000 !important;
	}
	.minute{
		color:#000000 !important;
	}
	.timepicker-second{
		color:#000000 !important;
	}
	.second{
		color:#000000 !important;
	}	

	.RemoveAgenda:hover{
		cursor:pointer;
		color:#DF1D20;
	}
	
	#lembrete{
		padding-left:35px;	
	}
	
</style>

	
<div id="Lembretes" >


	
	<div class="form-group">
		
	<label class="TituloLembrete" for="lembrete">Lembrete:</label>	
		
	<label class="trigger col-md-12" id="lembrete_env" onclick="EmojiClick( this.id )">
	  <i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />		
	  <div class="msg EmojiVer"></div>
	</label>		
		
	
	<textarea class="form-control" id="lembrete" rows="4" placeholder="Informe seu lembrete aqui..."></textarea>
	</div>	


	<div class="row">
	
		<div class="col-lg-5 col-md-5">
			
			<div class="form-group">
			  <label class="TituloLembrete">hora:</label>

				<div id="Horas">
					<input type="hidden" id="HoraSelect">
				</div>
				
			</div>	

		</div>

		<div class="col-lg-7 col-md-7">
			<div class="form-group">

			  <label class="TituloLembrete">Compartilhar com:</label>
					 <select id="descricao" name="descricao[]" multiple="multiple">
						<optgroup label="Todos">
						<?php
							$Tipos = explode(',',$d->descricao);
							$queryc = "select * from contatos_agenda group by telefone order by nome ";
							$resultc = mysqli_query($conexao2,$queryc);
							while($c = mysqli_fetch_object($resultc)){
							$descricao = ($c->nome);	
						?>
						<option value="<?=$c->id?>" <?=((in_array($c->id,$Tipos))?'selected':false)?> ><?=$descricao?></option>
						<?php
							}
						?> 
						</optgroup> 
						<option data-role="divider" ></option>
					</select>  
			</div>	
		</div>
	
		
		
		
		
	</div>
	
	<div class="form-group">
	<br><button id="SalvarAgenda" type="button" class="btn btn-gradient-success btn-sm">Salvar</button>
	</div>
		

	<input type="hidden" id="data" value="<?=$_SESSION[$Script]['data']?>">
	
	
<div class="Tem">
	
	<div class="table-responsive" style="overflow-x:auto;">	
	<table class="table table-striped">
	  <thead>
		<tr>
		  <th> Data </th>
		  <th> Lembrete </th>
		  <th> Ação </th>
		</tr>
	  </thead>

	  <tbody>

		<?php 
		while($d = mysqli_fetch_object($resultado)){
		?>	
			<tr>
				<td> <?=dataBr($d->data)?> <?=$d->hora?> </td>
				<td> <?=$d->lembrete?> </td>
				<td><i class="far fa-trash-alt RemoveAgenda" Cod="<?=$d->id?>"></i> </td>
			</tr>		
		<?php	
		}
		?>	  


	  </tbody>

	</table>
	</div>
	
</div>	
	

	
	
</div>




<script language="javascript">

	
/*Ações Emojis*/
function EmojiClick(dados){

	var str = dados;
	var Campo = str.split("_");	
	
	$.ajax({
		url: "./_emoji/emoji.php?Campo="+Campo[0],
		success: function( data ){
			$(".EmojiVer").html(data);
			$('body>.tooltip').remove();
		}
	});		
}

function MenuEmoji(dados){

	var str = dados;
	var res = str.split("-");
	
	$.ajax({
		url: "./_emoji/emoji.php?Cod="+res[0],
		success: function( data ){
			$(".EmojiVer").html(data);
			$('body>.tooltip').remove();
		}
	});
	
}
function InsertEmoji(dados){
	var str = dados;
	var res = str.split("|");	
	
	let ConteudoAt = $("#"+res[0]).val();
	
	let Conteudo = ConteudoAt + ' '+res[1];
	
	$("#"+res[0]).val(Conteudo);	

}	
/*Fim Ações Emojis*/
	
	
	
	
	
	
	
	
$('#Horas').datetimepicker({	
        format: 'LT',
        inline: true,
        sideBySide: true,
		useCurrent: true,	
});	
	
	$('#lembrete').focus();
	
	$('#descricao').multiselect({
	maxHeight: 330,
	enableFiltering: true,
	enableClickableOptGroups: true,
	enableCollapsibleOptGroups: true,
	//includeSelectAllOption: true,
	buttonText: function(options, select) {
		if (options.length === 0) {

			return 'Seus contatos';

		}
		else if (options.length > 2) {

			return options.length+' selecionados!';

		}
		 else {
			 var labels = [];
			 options.each(function() {
				 if ($(this).attr('label') !== undefined) {
					 labels.push($(this).attr('label'));

				 }
				 else {
					 labels.push($(this).html());

				 }
			 });
			 return labels.join(', ');

		 }

	}
	});
	
	
$("#SalvarAgenda").click(function(){
	
	let data = $("#data").val();
	let lembrete = $("#lembrete").val();
	let Horas = $("#HoraSelect").val();
    let TratTurno = Horas.split(" ");
	let TratHora = TratTurno[0].split(":");
	
	let Hora = TratHora[0];
	let Minuto = TratHora[1];
	
	if(TratTurno[1]=="PM"){
		var H = ( (Hora=='1')?'13': ((Hora=='2')?'14': ((Hora=='3')?'15': ((Hora=='4')?'16': ((Hora=='5')?'17': ((Hora=='6')?'18': ((Hora=='7')?'19': ((Hora=='8')?'20': ((Hora=='9')?'21': ((Hora=='10')?'22': ((Hora=='11')?'23': ((Hora=='12')?'24':'') ))))))))))) ;
	}else{
		var H = Hora;
	}
	
	let HoraCerta = H +":"+ Minuto;
	
	let descricao = $("#descricao").val();
	
	if(lembrete==""){
		 $.confirm({
			title: "<span style='color:red'>Atenção!</span>",
			content: "<b>Informe o seu <span style='color:green'>Lembrete</span>.</b>",
			columnClass:"col-md-4 col-md-offset-4",
			theme: "light",
			buttons: {
				Entendi: {
					btnClass: "btn-success",
					action: function(){
						$('#lembrete').focus();
					}
				},
			}				
		  });
	 }
	else if(!descricao){
		 $.confirm({
			title: "<span style='color:red'>Atenção!</span>",
			content: "<b>Compartilhar com <span style='color:green'>quem?</span>.</b>",
			columnClass:"col-md-4 col-md-offset-4",
			theme: "light",
			buttons: {
				Entendi: {
					btnClass: "btn-success",
					action: function(){
						$('#lembrete').focus();
					}
				},
			}				
		  });		
	}
	else{
		 //alert(  HoraCerta +" e "+lembrete+" e "+descricao );
		$("#CARREGANDO").html("<div id='loader'><img src='./img/loader.gif' width='120' ></div>");		 
		$.ajax({
			type: "POST",
			url: "./tabelas/agenda/agendar.php",
			data: {
				data:data,
				hora:HoraCerta,
				lembrete:lembrete,
				compartilhar:descricao
			},
			success: function( data )
			{
				$("#Salvando").html(data);
			}
		});		 
		 
	 }
	
	
	
});
	
function VoltarAgenda(){

	$("#CARREGANDO").html("<div id='loader'><img src='./img/loader.gif' width='120' ></div>");
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
	
$(".RemoveAgenda").click(function(){
	
	let Cod = $(this).attr("Cod");
	$("#CARREGANDO").html("<div id='loader'><img src='./img/loader.gif' width='120' ></div>");
	$.ajax({
	  url: "./tabelas/agenda/agendar.php?excluir="+Cod,
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
	
function VoltarAgendamento(){
	$.ajax({
	  url: "./tabelas/agenda/agendar.php",
	  success: function(data) {
	  $('#Salvando').html(data);	
	  }
	});		
}	
	
</script>
</div>