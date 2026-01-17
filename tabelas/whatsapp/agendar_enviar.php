<?php
include('../../includes/connect.php');

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_msg from usuarios where id = '".$_SESSION[id_usuario]."' "));

?>

<style>

	#AreaGrupo{
		border:#cccccc solid 1px !important;
		background: rgba(255,255,255,0.90) !important; 
	}
	#AreaGrupo:hover{
		border:#e2e6ea solid 1px !important;
		background: rgba(226,230,234,0.90) !important; 
	}
	
	.multiselect-container{
		width: 100% !important;
	}
	
	.datepicker{
		padding:0 !important;
	}
	.datepicker-days{
		padding:0 !important;
		font-size:15px !important;
		font-family: Verdana,sans-serif !important;
		font-weight: 100 !important;
	}

	.timepicker{
		padding:0 !important;
	}
	.timepicker-picker{
		padding:0 !important;
		margin-left:-15px !important;
	}
	
	.table-condensed{
		width:84% !important; 
		margin-left:6% !important; 
		margin-right:10% !important; 
	}
	

	
	
	.DataSms{
		border:#ffffff solid 5px;
		padding:10px;
		border-radius: 14px;
		
		background: rgba(36,208,183,0.50); /* Old browsers */
		background: -moz-linear-gradient(top,  rgba(36,208,183,0.50), rgba(36,208,183,0.50) 50%, rgba(36,208,183,0.50)); /* FF3.6-15 */
		background: -webkit-linear-gradient(top,  rgba(36,208,183,0.50), rgba(36,208,183,0.50) 50%,rgba(36,208,183,0.50)); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom,  rgba(36,208,183,0.50), rgba(36,208,183,0.50) 50%,rgba(36,208,183,0.50)); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='rgba(36,208,183,0.50)', endColorstr='rgba(36,208,183,0.50)',GradientType=0 ); /* IE6-9 */			
		
	}

	.DescCarac{
		font-size:12px;
		padding:0;
		margin-top:-10px;
		color:#000000;
	}
	.Alerta{
		font-size:11px;
		color: #E10F13;
		padding:0;
		margin-top:-10px;		
	}	

	
	
	#myInput{
		
		width:166px;
		 
		
		background: rgba(36,208,183,0.50); /* Old browsers */
		background: -moz-linear-gradient(top,  rgba(36,208,183,0.50), rgba(36,208,183,0.50) 50%, rgba(36,208,183,0.50)); /* FF3.6-15 */
		background: -webkit-linear-gradient(top,  rgba(36,208,183,0.50), rgba(36,208,183,0.50) 50%,rgba(36,208,183,0.50)); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom,  rgba(36,208,183,0.50), rgba(36,208,183,0.50) 50%,rgba(36,208,183,0.50)); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='rgba(36,208,183,0.50)', endColorstr='rgba(36,208,183,0.50)',GradientType=0 ); /* IE6-9 */			
		
	}	

	.TituloEnvio{
		font-size:16px;
	}  
	
	.emojiPickerIcon{
		float:left !important;
		margin-top:-25px !important;
		width:38px !important;
		height:38px !important;
	}
	
	
	
.btn-bs-file{
    position:relative;
	width:100%;
}
.btn-bs-file input[type="file"]{
    position: absolute;
    top: -9999999;
    filter: alpha(opacity=0);
    opacity: 0;
    width:0;
    height:0;
    outline: none;
    cursor: inherit;
}	

	#NomeImg{
		font-size:11px;
	}	
	
	#AguardePrecesso{
		font-size:30px;
		text-align:center;
		color: #FC0004;
	}
	
	
	#suaMensagem{
		padding-left:35px;	
	}	
</style>



<div class="card" >
<div class="card-body">


<div class="col-md-12">
	<h2><i class="far fa-paper-plane"></i> <span class="TituloEnvio">Envios de Mensagens WhatsApp</span></h2>
	
	<div id="AguardePrecesso"></div>
	
</div>	
				

<form id="EnviarAgenda" action="./tabelas/whatsapp/agendar.php" method="post" target="pagina" enctype="multipart/form-data" >				

<div id="#TOP" class="container Paginas">

<div class="row" > 

	<div class="col-md-12 col-lg-12 DataSms">
	
		<div class="row" >
			<div class="col-md-4 col-lg-4">	
				<span class="TituloForms" style="color:#000000">Pesquisas:</span><br>
				<i class="fas fa-book-reader Icons" style="color:#000000"></i>
				<select InputForm class="form-control" id="pesquisa" name="pesquisa" style="color:#000000;border:#333333 solid 1px;">
				<option InputForm value="">::Selecione a pesquisa::</option>	
				<?php
				$pergunta = "select * from pesquisa_bot ";
				$resultado = mysqli_query($conexao2,$pergunta);	
				while($d = mysqli_fetch_object($resultado)){
				?>	
					<option InputForm value="<?=$d->id?>"><?=($d->pesquisa)?></option>
				<?php
				}	
				?>
				</select>	
			</div>

			<div class="col-md-4 col-lg-4">
				<span class="TituloForms" style="color:#000000">Carregar imagem:</span><br>
				<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Carregar imagem">
					<i class="fas fa-images" aria-hidden="true"></i> 
					&nbsp; <b><span id="NomeImg">Imagem</span></b>
					<input id="arquivo_img" type="file" name="arquivo_img" value="arquivo_img" id="arquivo_img" >
				</label>
				
				
				<script>
				$("#arquivo_img").change(function(e){

					var files = e.target.files;
					/*var nomes = [];
					for (var i = 0; i < files.length; i++) {
						var nome = e.target.files[i].name ;	
						nomes.push( nome );
					}*/
					//var nome = e.target.files[0].name ;
					
					let NomeArq = e.target.files[0].name;
					
					var Com3 = NomeArq.substr(-3);
					var Com4 = NomeArq.substr(-4);
					
					if(Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
						$('#NomeImg').text(NomeArq);
					}else{
						$('#NomeImg').text('Arquivo invalido!');
					}


				});				
				</script>				
				
			</div>
			
			<div class="col-md-4 col-lg-4">
				<span class="TituloForms" style="color:#000000">Carregar PDF:</span><br>
				<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Carregar imagem">
					<i class="fas fa-file-pdf" aria-hidden="true"></i> 
					&nbsp; <b><span id="NomePdf">PDF</span></b>
					<input id="arquivo_pdf" type="file" name="arquivo_pdf" value="arquivo_pdf" id="arquivo_pdf" >
				</label>
				
				
				<script>
				$("#arquivo_pdf").change(function(e){

					var files = e.target.files;
					/*var nomes = [];
					for (var i = 0; i < files.length; i++) {
						var nome = e.target.files[i].name ;	
						nomes.push( nome );
					}*/
					//var nome = e.target.files[0].name ;
					
					let NomeArq = e.target.files[0].name;
					
					var Com3 = NomeArq.substr(-3);
					var Com4 = NomeArq.substr(-4);
					
					if(Com3 == 'pdf' || Com3 == 'PDF'){
						$('#NomePdf').text(NomeArq);
					}else{
						$('#NomePdf').text('Arquivo invalido!');
					}


				});				
				</script>				
				
			</div>			
			
		</div>
		
	</div>
	
	<div class="col-md-6 col-lg-6 DataSms">
	
		  <div class="col-lg-12" style="margin-bottom:5px; border: #002AEF solid 0px; color: #030303; padding:0 ">
		  		<span class="TituloForms" style="color:#000000">GRUPOS:</span><br>
			    
			     <div id="AreaGrupo">
				 <select id="descricao" name="descricao[]" multiple="multiple">
						<optgroup label="Todos">
						<?php
							$Tipos = explode(',',$d->descricao);
							$queryc = "select * from categoria_cadastro order by descricao";
							$resultc = mysqli_query($conexao2,$queryc);
							while($c = mysqli_fetch_object($resultc)){
							$descricao = ($c->descricao);	
						?>
						<option value="<?=$c->id?>" <?=((in_array($c->id,$Tipos))?'selected':false)?> ><?=$descricao?></option>
						<?php
							}
						?> 
						</optgroup>
						<option data-role="divider" ></option>
				</select>  
				</div>
			<script language="javascript">

				$('#descricao').multiselect({
				maxHeight: 430,
				enableFiltering: true,

				enableClickableOptGroups: true,
				enableCollapsibleOptGroups: true,
				//includeSelectAllOption: true,
				buttonText: function(options, select) {
					if (options.length === 0) {
						
						var grupo = $('#descricao').val();
						var mensagem = $('#suaMensagem').val();

						$.ajax({
						type: "POST",
						url: "./tabelas/whatsapp/smg_disponivel.php",
						data: {grupos: grupo},
							success: function (dados){
								$("#smsDisponivel").html(dados);
							}
						});							
						
						if(grupo && mensagem){
							$("#Enviar").prop('disabled', false);
						}else{
							$("#Enviar").prop('disabled', true);
						} 
						return 'Selecione pelo menos um grupo';

					}
					else if (options.length > 2) {
						
						var grupo = $('#descricao').val();
						var mensagem = $('#suaMensagem').val();

						$.ajax({
						type: "POST",
						url: "./tabelas/whatsapp/smg_disponivel.php",
						data: {grupos: grupo},
							success: function (dados){
								$("#smsDisponivel").html(dados);
							}
						});						
						
						if(grupo && mensagem){
							$("#Enviar").prop('disabled', false);
						}else{
							$("#Enviar").prop('disabled', true);
						} 
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
			</script>
		</div>		

		
			<div id="dataSms">
				<input type="hidden" name="dataSms" id="data_sms">
			</div>

		
	</div>



	<div class="col-md-6 col-lg-6 DataSms">
	
		
	   <div class="row">	
		   <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" >
			   <span class="TituloForms" style="color:#000000">Incluir Campos:</span><br>
				  <i class="far fa-copyright Icons" style="color:#000000"></i>
				<select InputForm class="form-control" id="campos" name="campos" style="color:#000000;border:#333333 solid 1px;">
				<option InputForm value="">Incluir Campos</option>	
				<option InputForm value="[nome]"  >Nome</option>
				<option InputForm value="[celular]" >Celular</option>
				</select> 	   
		   </div>
		   <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 " >
				<span class="TituloForms" style="color:#000000">Adicionar</span><br>
			    <button type="button" class="btn btn-gradient-primary btn-sm" id="ADD">ADD</button>
		   </div>
	   </div>	
		
		
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height:12px;"></div>

		<div class="form-group">

		  <label for="comment" style="color:#000000 ">Mensagem</label>
			
		<label class="trigger col-md-12" id="suaMensagem_env" onclick="EmojiClick( this.id )">
		  <i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />		
		  <div class="msg EmojiVer"></div>
		</label>			
			
		  <textarea class="form-control" rows="6" id="suaMensagem" name="mensagem" placeholder="Sua mensagem aqui" maxlength="1051"></textarea>

		</div>	
		
		<div class="DescCarac"><p><b><span qntCart >0</span></b> de <b>150</b> caracter(es)</p></div>
		<div class="DescCarac"><p><b><span qntPalavas >0</span></b> palavra(s) | <b><span qntMsg >0</span></b> parte(s)</p></div>		
		<div class="Alerta"><span Alerta ></span></div>			
										
	</div>



				
	  <div class="col-md-7 col-lg-7 " style="border: #1D12A4 solid 0px;" >
	  
	  </div>	  				
				
      <div class="col-md-5 col-lg-5 " style="border: #1D12A4 solid 0px; " >
		
			<p>
			<ul class="list-group">
			  <li class="list-group-item" style="color:#000000">Saldo Total é disponível <span smsDisponivel class="badge"><?=$creditoSms?> envios</span></li>
			  <li class="list-group-item" style="color:#000000">Esta operação utilizará <span smsUso class="badge">0 envios</span></li> 
			  <li class="list-group-item" style="color:#000000">Seu saldo será de <span smsSaldo class="badge"><?=$creditoSms?> envios</span></li> 
			</ul>
			</p>
			<input type="hidden" id="ChecarEnvios">
	  </div>  

	<div class="row" >
		<div class="col-md-12 col-lg-12" style="padding:27px;margin-top:4px;">
			<button id="Enviar" type="button" class="btn btn-success btn-fw" disabled >
			<i class="far fa-paper-plane"></i> &nbsp; Enviar em <span hora></span> 
			</button>
		</div>				
	</div>
	
	
	
	
	
	</div>
 
	
</div>

	
</form>
 
    
    

    <script type="text/javascript">

		
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
		
		
		
	$('#descricao').change(function(){
		
		var grupo = $(this).val();
		var mensagem = $('#suaMensagem').val();

		$.ajax({
		type: "POST",
		url: "./tabelas/whatsapp/smg_disponivel.php",
		data: {grupos: grupo},
			success: function (dados){
				$("#smsDisponivel").html(dados);
			}
		});			
		
		if(grupo && mensagem){
			$("#Enviar").prop('disabled', false);
		}else{
			$("#Enviar").prop('disabled', true);
		}

	});	
	
		
	function VoltarSmsDisponivel(smsEnvio,smsSaldo){
		
		$('#ChecarEnvios').val(smsSaldo);
		
		smsEnvio = parseInt(smsEnvio);
		smsSaldo = parseInt(smsSaldo);
		
		if(smsEnvio){
			smsEnvio;
		}else{
			smsEnvio = 0;
		}
		
		if(smsSaldo){
			smsSaldo;
		}else{
			smsSaldo = 0;
		}		

		if(smsSaldo <= 0){
			$("#Enviar").prop('disabled', true);
		}		
		
		$('span[smsUso]').text(smsEnvio+' envios');
		$('span[smsSaldo]').text(smsSaldo+' envios');

		
	}		
	
		
	$('#ADD').click(function(){

		var Campo = $('#campos').val();

		if(Campo){
			$('#suaMensagem').val($('#suaMensagem').val()+Campo+' ');  		
			$('#suaMensagem').focus();			
		}

	});			
		
		
		
		
		
	  $("#suaMensagem").keyup(function(){
		  
		  var count = $(this).val().length;
		  
		  if(count < 1){
			  $('span[qntMsg]').text(0);
			  $('span[Alerta]').text('');
		  }
		  else if(count <= 150){
			  $('span[qntMsg]').text(1);
			  $('span[Alerta]').text('');
		  }
		  else if(count <= 300){
			  $('span[qntMsg]').text(2);
			  $('span[Alerta]').text('');
		  }
		  else if(count <= 450){
			  $('span[qntMsg]').text(3);
			  $('span[Alerta]').text('');
		  }
		  else if(count <= 600){
			  $('span[qntMsg]').text(4);
			  $('span[Alerta]').text('');
		  }
		  else if(count <= 750){
			  $('span[qntMsg]').text(5);
			  $('span[Alerta]').text('');
		  }
		  else if(count <= 900){
			  $('span[qntMsg]').text(6);
			  $('span[Alerta]').text('');
		  }
		  else if(count <= 1050){
			  $('span[qntMsg]').text(7);
			  $('span[Alerta]').text('');
		  }  
		  else{
			  $('span[Alerta]').text('*Não é mais permitido acima de 1050 caractes!');
		  }

		  $('span[qntCart]').text(count);
		  
		  
			var grupo = $('#descricao').val();
			var mensagem = $('#suaMensagem').val();

			if(grupo && mensagem){
				$("#Enviar").prop('disabled', false);
			}else{
				$("#Enviar").prop('disabled', true);
			} 
		  
			var smsSaldo = $('#ChecarEnvios').val();
		  
			if(smsSaldo <= 0){
				$("#Enviar").prop('disabled', true);
			}
		  
		  
		  
	  });
	  
	  
	  
		function wordCount( val ){
			var wom = val.match(/\S+/g);
			return {
				charactersNoSpaces : val.replace(/\s+/g, '').length,
				characters         : val.length,
				words              : wom ? wom.length : 0,
				lines              : val.split(/\r*\n/).length
			};
		}


		var textarea = document.getElementById("suaMensagem");
		textarea.addEventListener("input", function(){
		  var v = wordCount( this.value );
			
			$('span[qntPalavas]').text(v.words);

		}, false);		  
	  
	  	
		
		
		$('#dataSms').datetimepicker({
			inline: true,
			sideBySide: true, 
			useCurrent: true,
			locale: 'pt-br',
			minDate: new Date(),
			widgetPositioning: {
			horizontal: 'auto',
			vertical: 'bottom'
			},
			icons: {
			date: 'far fa-calendar-alt',
			up: 'far fa-arrow-alt-circle-up',
			down: 'far fa-arrow-alt-circle-down',
			previous: 'far fa-arrow-alt-circle-left',
			next: 'far fa-arrow-alt-circle-right',
			today: 'fas fa-calendar-week',
			clear: 'fas fa-recycle'
			}
			});


	$('#dataSms').mouseout(function(){	
		
		dataSms = $('#data_sms').val();	
		$('span[hora]').text(dataSms);
		
		
	})
	
	dataSms = $('#data_sms').val();	
	$('span[hora]').text(dataSms);

		
		
		
		



	
		
		
		

	
	
	$('#Enviar').click(function(){
		
		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
		$("#AguardePrecesso").html('Aguarde! em processamento...');
		
		var dataSms = $('#data_sms').val();
		var grupos = $('#descricao').val();
		var mensagem = $('#suaMensagem').val();
		
		let dados = $("#EnviarAgenda").submit();
		$("#smsEndiado").html(dados);
		
		/*$.ajax({
		type: "POST",
		url: "./tabelas/whatsapp/agendar.php",
		data: {dataSms: dataSms, grupos: grupos, mensagem: mensagem },
			success: function (dados){
				$("#smsEndiado").html(dados);
			}
		});*/			

	});
		
		
	function RetornoEnvio(tmp,tmp2){
		
	 $.confirm({
		title: "<span style='color:red'>ATENÇÃO!</span>",
		content: "<b>SMS processado com sucesso!</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){
					
					$("#CARREGANDO").html('<div id="loader"></div>');
					$("#AguardePrecesso").html('');
					
					$.ajax({
					url:"./paginas/pagina_inicial.php",
					success: function(dados){
						$("#CARREGANDO").html('');
						$('#CONTEUDOS').html(dados);

					}
					});					
					
				}
			},
		}				
	  });		


	}
		
		

	
</script>
    
  <div id="smsDisponivel" style="display:none"></div> 
  <div id="smsEndiado" style="display:none"></div>
  				
				
				
	
</div>
</div>
