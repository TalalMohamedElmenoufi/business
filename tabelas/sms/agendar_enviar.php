<?php
include('../../includes/connect.php');

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_sms from usuarios where id = '".$_SESSION[id_usuario]."' "));

?>

<style>

	#Area2Grupo{
		border:#cccccc solid 1px !important;
		background: rgba(255,255,255,0.90) !important; 
	}
	#Area2Grupo:hover{
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
		
		background: rgba(14,131,225,0.50); /* Old browsers */
		background: -moz-linear-gradient(top,  rgba(14,131,225,0.50), rgba(14,131,225,0.50) 50%, rgba(14,131,225,0.50)); /* FF3.6-15 */
		background: -webkit-linear-gradient(top,  rgba(14,131,225,0.50), rgba(14,131,225,0.50) 50%,rgba(14,131,225,0.50)); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom,  rgba(14,131,225,0.50), rgba(14,131,225,0.50) 50%,rgba(14,131,225,0.50)); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='rgba(14,131,225,0.50)', endColorstr='rgba(14,131,225,0.50)',GradientType=0 ); /* IE6-9 */			
		
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

	.TituloEnvio{
		font-size:16px;
	}
	
	#myInput{
		
		width:166px;
		 
		
		background: rgba(14,131,225,0.50); /* Old browsers */
		background: -moz-linear-gradient(top,  rgba(14,131,225,0.50), rgba(14,131,225,0.50) 50%, rgba(14,131,225,0.50)); /* FF3.6-15 */
		background: -webkit-linear-gradient(top,  rgba(14,131,225,0.50), rgba(14,131,225,0.50) 50%,rgba(14,131,225,0.50)); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom,  rgba(14,131,225,0.50), rgba(14,131,225,0.50) 50%,rgba(14,131,225,0.50)); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='rgba(14,131,225,0.50)', endColorstr='rgba(14,131,225,0.50)',GradientType=0 ); /* IE6-9 */			
		
	}	


</style>



<div class="card" >
<div class="card-body">


<div class="col-md-12">
	<h2><i class="far fa-paper-plane"></i> <span class="TituloEnvio">Envios de Mensagens SMS</span></h2>
</div>	
				

				

<div id="#TOP2" class="container Paginas">
<div class="row" > 
 
	<div class="col-md-6 col-lg-6 DataSms">
	
		  <div class="col-lg-12" style="margin-bottom:5px; border: #002AEF solid 0px; color: #030303; padding:0 ">
		  		<span class="TituloForms" style="color:#000000">GRUPOS:</span><br>
			    
			     <div id="Area2Grupo">
				 <select id="descricao2" name="descricao[]" multiple="multiple">
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

				$('#descricao2').multiselect({
				maxHeight: 430,
				enableFiltering: true,

				enableClickableOptGroups: true,
				enableCollapsibleOptGroups: true,
				//includeSelectAllOption: true,
				buttonText: function(options, select) {
					if (options.length === 0) {
						
						var grupo = $('#descricao2').val();
						var mensagem = $('#sua_mensagem2').val();

						$.ajax({
						type: "POST",
						url: "./tabelas/sms/sms_disponivel.php",
						data: {grupos: grupo},
							success: function (dados){
								$("#smsDisponivel2").html(dados);
							}
						});							
						
						if(grupo && mensagem){
							$("#Enviar2").prop('disabled', false);
						}else{
							$("#Enviar2").prop('disabled', true);
						} 
						return 'Selecione pelo menos um grupo';

					}
					else if (options.length > 2) {
						
						var grupo = $('#descricao2').val();
						var mensagem = $('#sua_mensagem2').val();

						$.ajax({
						type: "POST",
						url: "./tabelas/sms/sms_disponivel.php",
						data: {grupos: grupo},
							success: function (dados){
								$("#smsDisponivel2").html(dados);
							}
						});						
						
						if(grupo && mensagem){
							$("#Enviar2").prop('disabled', false);
						}else{
							$("#Enviar2").prop('disabled', true);
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

		
			<div id="dataSms2">
				<input type="hidden" name="data_sms" id="data_sms2">
			</div>

		
	</div>



	<div class="col-md-6 col-lg-6 DataSms">
	
	   <div class="row">	
		   <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" >
			   <span class="TituloForms" style="color:#000000">Incluir Campos:</span><br>
				  <i class="far fa-copyright Icons" style="color:#000000"></i>
				<select InputForm class="form-control" id="campos2" name="campos" style="color:#000000;border:#333333 solid 1px;">
				<option InputForm value="">Incluir Campos</option>	
				<option InputForm value="[nome]"  >Nome</option>
				<option InputForm value="[celular]" >Celular</option>
				</select> 	   
		   </div>
		   <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 " >
				<span class="TituloForms" style="color:#000000">Adicionar</span><br>
			    <button type="button" class="btn btn-gradient-primary btn-sm" id="ADD2">ADD</button>
		   </div>
	   </div>	
		
		
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height:12px;"></div>

		<div class="form-group">
		  <label for="comment" style="color:#000000 ">Mensagem</label>
		  <textarea InputForm class="form-control" rows="6" id="sua_mensagem2" placeholder="Sua mensagem aqui" maxlength="1051"></textarea>	
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
			<input type="hidden" id="ChecarEnvios2">
	  </div>  

	<div class="row" >
		<div class="col-md-12 col-lg-12" style="padding:27px;margin-top:4px; ">
			<button id="Enviar2" type="button" class="btn btn-success btn-fw" style="background-color:#0e83e1" disabled >
			<i class="far fa-paper-plane"></i> &nbsp; Enviar em <span hora></span> 
			</button>
		</div>				
	</div>
	
	</div>

	
</div>


  
    
    

    <script type="text/javascript">

		
	$('#descricao2').change(function(){
		
		var grupo = $(this).val();
		var mensagem = $('#sua_mensagem2').val();

		$.ajax({
		type: "POST",
		url: "./tabelas/sms/sms_disponivel.php",
		data: {grupos: grupo},
			success: function (dados){
				$("#smsDisponivel2").html(dados);
			}
		});			
		
		if(grupo && mensagem){
			$("#Enviar2").prop('disabled', false);
		}else{
			$("#Enviar2").prop('disabled', true);
		}

	});	
	
		
	function VoltarSmsDisponivel2(smsEnvio,smsSaldo){
		
		$('#ChecarEnvios2').val(smsSaldo);
		
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
			$("#Enviar2").prop('disabled', true);
		}		
		
		$('span[smsUso]').text(smsEnvio+' envios');
		$('span[smsSaldo]').text(smsSaldo+' envios');

		
	}		
	
		
	$('#ADD2').click(function(){

		var Campo = $('#campos2').val();

		if(Campo){
			$('#sua_mensagem2').val($('#sua_mensagem2').val()+Campo+' ');  		
			$('#sua_mensagem2').focus();			
		}

	});			
		
		
		
		
		
	  $("#sua_mensagem2").keyup(function(){
		  
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
		  
		  
			var grupo = $('#descricao2').val();
			var mensagem = $('#sua_mensagem2').val();

			if(grupo && mensagem){
				$("#Enviar2").prop('disabled', false);
			}else{
				$("#Enviar2").prop('disabled', true);
			} 
		  
			var smsSaldo = $('#ChecarEnvios2').val();
		  
			if(smsSaldo <= 0){
				$("#Enviar2").prop('disabled', true);
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


		var textarea = document.getElementById("sua_mensagem2");
		textarea.addEventListener("input", function(){
		  var v = wordCount( this.value );
			
			$('span[qntPalavas]').text(v.words);

		}, false);		  
	  
	  	
		
		
		$('#dataSms2').datetimepicker({
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


	$('#dataSms2').mouseout(function(){	
		
		dataSms = $('#data_sms2').val();	
		$('span[hora]').text(dataSms);
		
		
	})
	
	dataSms = $('#data_sms2').val();	
	$('span[hora]').text(dataSms);

		


	
	
	$('#Enviar2').click(function(){
		
		var dataSms = $('#data_sms2').val();
		var grupos = $('#descricao2').val();
		var mensagem = $('#sua_mensagem2').val();

		//alert('Data='+dataSms+' Grupo='+grupos+' Mensagem='+mensagem);
		
		$.ajax({
		type: "POST",
		url: "./tabelas/sms/agendar.php",
		data: {dataSms: dataSms, grupos: grupos, mensagem: mensagem },
			success: function (dados){
				$("#smsEndiado2").html(dados);
			}
		});			
		
		
		
	});
		
		
	function RetornoEnvio(){

	 $.confirm({
		title: "<span style='color:red'>ATENÇÃO!</span>",
		content: "<b>SMS processado com sucesso!</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){
					
					$("#CARREGANDO").html('<div id="loader"></div>');

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
    
  <div id="smsDisponivel2" style="display:none"></div> 
  <div id="smsEndiado2" style="display:none"></div>
  				
				
				
	
</div>
</div>
