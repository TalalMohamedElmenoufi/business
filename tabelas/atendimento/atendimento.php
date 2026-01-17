<?php
include("../../includes/connect.php");

$Conf[script] = 'tabelas/atendimento/atendimento';
$Script = md5($Conf[script]);
list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao, "select count(id) from atendimento  "));
$_SESSION[$Script][tabela] = 'atendimento';
$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/atendimento/atendimento';
$_SESSION[$Script][titulo] = 'CHAT BOT';


$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
$Md5 = $_SESSION[$Script][Script];


if($_POST[nome_agenda]){
	mysqli_query($conexao2," insert into contatos_bot set nome='".($_POST[nome_agenda])."',  whatsapp='".$_POST[celular]."'  ");
	echo "<script>parent.VoltarAddClient('$_POST[nome_agenda]');</script>";
}

list($whatsapp_conectado) = mysqli_fetch_row(mysqli_query($conexao, "select whatsapp_conectado from usuarios  where id = '".$_SESSION[id_usuario]."' "));
$wConect = explode("@",$whatsapp_conectado);


date_default_timezone_set('America/Manaus'); 
setlocale(LC_ALL, 'pt_BR.utf-8');
?>


<style>
	#messages{
		overflow:scroll;
		height:360px;
	}
	#messages_client {
		
		overflow:scroll;
		height:410px;		
		
		flex: 1!important;
		background: hsl(249, 0%, 95%);
		overflow: auto;
	}	
	
	#ClientesEspera{
		padding:3px;
	}
	
	.ClienteBot{
		height:65px;
		color:#FFFFFF !important;
	}
	.IconBot{
		font-size:30px;
	}
	.NomeCliente{
		margin-left:6px;
	}
	
	.ListVez{
		border-bottom:#1E1E1E solid 1px;
	}
	.ListVez:hover{
		border-bottom:#E00003 solid 1px;
		color:#FFF;
		background: rgba(0, 0, 0, 0.49); 
	}

	.ClienteClick{
		cursor:pointer;
	}
	
	.AddBot{
		font-size:18px;
		margin-left:10px;
		margin-top:4px;
		cursor:pointer;
	}
	.AddBot:hover{
		color:#DFFF30;
	}
	
	.ClienteSelecionado{
		text-align: right;
		color:#FFFFFF;
		border: #FF0004 solid 0px;
		width:90%;
		position: absolute;
	}
	
	.BtClient{
		padding:6px !important;
	}
</style>


<div class="card" >
<div class="card-body">
		
		
	<div class="panel-heading" style="margin-bottom:10px;">
		<?=($_SESSION[$Script][titulo])?> FROM <?=$whatsapp_conectado?>
	</div>
	
	
	
	<div class="row">
	
		<div class="form-group col-lg-9 col-md-9">

				<!--INICIO-->

				<div class="row d-flex flex-row align-items-center p-2 m-0 w-100" id="navbar">

					<a href="#"><img src="../business/img/logos/20200810164617_logo-tme-smol.png" alt="Profile Photo" class="img-fluid rounded-circle mr-2" style="height:50px;" id="pic"></a>
					<div class="d-flex flex-column">
					<div class="text-white font-weight-bold" id="name">T M Elmenoufi Ltda</div>
					<div class="text-white small" id="details"><?=strftime('%A, %d de %B de %Y', strtotime('today'))?></div>	
					</div>
					<div class="ClienteSelecionado"></div>

				</div>


				<!-- Messages -->
				<div class="d-flex flex-column" id="messages">

				</div>


				<!-- Input -->
				<div class="justify-self-end align-items-center flex-row d-flex" id="input-area">
				<a href="#"><i class="far fa-smile text-muted px-3" style="font-size:1.5rem;"></i></a>

				<input type="text" id="ListMensagem" name="message" id="input" placeholder="Enviar mensagem" class="form-control" >

				<i class="fas fa-paper-plane text-muted px-3" id="SendMensagem" style="cursor:pointer;" ></i>

				</div>						
				<!--FIM-->
			
			
		</div>
	
		<div class="form-group col-lg-3 col-md-3">

				<div class="row d-flex flex-row align-items-center p-2 m-0 w-100 ClienteBot" id="navbar">

					<i class="fas fa-headset IconBot"></i> <span class="NomeCliente">CLIENTES <span>

				</div>
			    <div class="d-flex flex-column" id="messages_client">
					
					
					<div id="ClientesEspera">
						<?php
						$clientes = " select MAX(a.id) as IdM, a.de_quem, b.nome from mov_bot_".date('mY')." a
						left join contatos_bot b on b.whatsapp=a.de_quem
						where a.de_quem != '".$wConect[0]."' group by a.de_quem order by MAX(a.id) desc limit 0,100 ";
						$resultadoClient = mysqli_query($conexao2,$clientes); 
						while( $c = mysqli_fetch_object($resultadoClient) ){
						$de_quem = explode("@",$c->de_quem);
						$nome = explode(" ",$c->nome);
						$nome = ($nome[0]);	

						$de_quem_vez = substr($de_quem[0], 0, 16);	
							
						list($status) = mysqli_fetch_row(mysqli_query($conexao2, "select status from mov_bot_".date('mY')." where id = '".$c->IdM."' "));	
						?>
						
						<div class="form-check form-check-success ListVez" id="<?=$de_quem_vez."|".$nome?>" onclick="ConversaClient(this.id)" >
						  <label class="form-check-label ClienteClick">
							<input type="radio" class="form-check-input" name="Clintes" id="Clintes" > 
							  <i class="input-helper"></i>
							  <?=(($nome)?$nome:$de_quem_vez)?>
							   
							  <img Cell<?=$de_quem_vez?> src="./img/what_msg.gif" style="width:22px;margin-top:4px;float:right; display:<?=(($status==0)?'block':'none')?> " > 
							  
							  <i class="far fa-user AddBot" data-toggle="tooltipBot" data-placement="top" title="Adicionar a agenda BOT" id="<?=$de_quem_vez?>" onclick="SelectClient(this.id)" style="float:right; margin-right:7px; display:<?=(($nome)?'none':'block')?>"></i>
							  
							  
						  </label>
						</div>						


						<?php
						}  
						?>
					</div>

				</div>	
					

		</div>	
	
	
	</div>
	
	
</div>	
</div>
	
<input type="hidden" id="whatsVez" >		
<input type="hidden" id="MaxId" >	
	
<div id="addCell" style="display:none"></div>	
	
<script>
	$('[data-toggle="tooltipBot"]').tooltip();
	$(".AddBot").mouseout(function(){
		$('body>.tooltip').remove();
	});
	
	function SelectClient(cell){

	 $.confirm({
		title: "",
		content: "<input type='text' id='AddNome' class='form-control' placeholder='Informe o Nome' >",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Salvar: {
				btnClass: "btn-success BtClient",
				action: function(){
					
					var nome = $("#AddNome").val();
					
					$.ajax({
						type: "POST",
						url: "./tabelas/atendimento/atendimento.php",
						data: { celular:cell, nome_agenda:nome },
						success: function( data ){ 
							$("#addCell").html(data);
						}
					});					
					
				}
			},
		}				
	  });

		
	}
	function VoltarAddClient(n){
		$(".ClienteSelecionado").text('Cliente: '+n);
	}
	
	
$("#ListMensagem").focus();	
	
setInterval(function(){
	$.ajax({
	url: './tabelas/atendimento/acoes/max_id.php',
	success: function(data) {
		 $('#MaxId').val(data);
	}
	});
}, 2000);
	

setInterval(function(){

	let MaxId = $("#MaxId").val();
	
	$.ajax({
	url: './tabelas/atendimento/acoes/clientes_espera.php?MaxId='+MaxId,
	success: function(data2) {
		if(data2){
		   $('#ClientesEspera').html(data2);
		}
	}
	});

}, 3000);	
	
	
function ConversaClient(dados){
	
	var res = dados.split('|');
	
	//alert(res[0]+" e "+res[1]);
	
	$("#whatsVez").val(res[0]);
	$("img[Cell"+res[0]+"]").css("display","none");

	if(res[1]){
	   $(".ClienteSelecionado").text('Cliente: '+res[1]);
	}else{
	   $(".ClienteSelecionado").text('Cliente: '+res[0]);
	}

	$("#messages").html('Aguarde...');
	
	$.ajax({
		type: "GET",
		url: "./tabelas/atendimento/acoes/lista_conversa.php",
		data: { celular:res[0], nome:res[1] },
		success: function( data ){ 
			$("#messages").html(data);
			
			var objDiv = document.getElementById("messages");
			objDiv.scrollTop = objDiv.scrollHeight;				
			
		}
	});	
	
}
	
	
$("#ListMensagem").keyup(function(e){

	if(e.which == 13){
		
	let whatsVez = $("#whatsVez").val();
	let mensagem = $(this).val();	
	//alert(whatsVez +" e "+mensagem);
	 	
		if(whatsVez){

			$("#messages").append(

				  '<div class="align-self-start p-1 my-1 mx-3 rounded bg-white shadow-sm message-item">'+
					'<div class="options">'+
					'<a><i class="fas fa-angle-down text-muted px-2"></i></a>'+
					'</div>'+
					'<div class="d-flex flex-row">'+
					'<div class="body m-1 mr-2">'+
						mensagem+'<br>'+
						'</div>'+
					'<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted" style="width:auto;font-size:10px;"><?=date('d/m/Y H:i:s')?></div>'+
					'</div>'+
				  '</div>'

			);	

			

			$.ajax({
				type: "POST",
				url: "./tabelas/atendimento/acoes/enviar_cliente.php",
				data: { celular:whatsVez, mensagem:mensagem },
				success: function( data ){ 
					
					var objDiv = document.getElementById("messages");
					objDiv.scrollTop = objDiv.scrollHeight;	
					
				}
			});
			
			$(this).val('');
			
		}else{

			$.confirm({
			title: "Informe o cliente",
			content: "",
			columnClass:"col-md-4 col-md-offset-4",
			theme: "light",
			buttons: {
				ok: {
					btnClass: "btn-success",
					action: function(){
					}
				},
			}				
			});		

		}

	
	}
	
	
});	
	
$("#SendMensagem").click(function(){
	
	let whatsVez = $("#whatsVez").val();
	let mensagem = $("#ListMensagem").val();

	
	if(whatsVez){
	
		$("#messages").append(

			  '<div class="align-self-start p-1 my-1 mx-3 rounded bg-white shadow-sm message-item">'+
				'<div class="options">'+
				'<a><i class="fas fa-angle-down text-muted px-2"></i></a>'+
				'</div>'+
				'<div class="d-flex flex-row">'+
				'<div class="body m-1 mr-2">'+
					mensagem+'<br>'+
					'</div>'+
				'<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted" style="width:auto;font-size:10px;"><?=date('d/m/Y H:i:s')?></div>'+
				'</div>'+
			  '</div>'

		);	

		$("#ListMensagem").val('');

		$.ajax({
			type: "POST",
			url: "./tabelas/atendimento/acoes/enviar_cliente.php",
			data: { celular:whatsVez, mensagem:mensagem },
			success: function( data ){ 
					
				var objDiv = document.getElementById("messages");
				objDiv.scrollTop = objDiv.scrollHeight;	
									
			}
		});
		
		
	}else{
		
			$.confirm({
			title: "Informe o cliente",
			content: "",
			columnClass:"col-md-4 col-md-offset-4",
			theme: "light",
			buttons: {
				ok: {
					btnClass: "btn-success",
					action: function(){
					}
				},
			}				
			});		
		
	}
	
});

</script>	