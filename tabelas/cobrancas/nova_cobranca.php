<div id="NOVACOBRANCA">
<?php	
include("../../includes/connect.php");	
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/cobrancas/nova_cobranca';
$Script = md5($Conf[script]);	


	
?>	
	
<style>
	.SalvarCob{
		padding:6px;
	}	
	
</style>	
	
<form id="formulario_novaCob" >	
	
<div class="row">
	
 	

	<div class="col-md-12" style="">

		<span class="TituloForms">Clientes:</span>
		<select class="form-control input-text-select" id="cliente_id" name="cliente_id">
		<option value="">Procurar por cliente</option>	
		<?php
		$query = "select id, nome from clientes where ativo = '0' order by nome";
		$result = mysqli_query($conexao2, $query);
		while($dc = mysqli_fetch_object($result)){
		?>
		<option value="<?=$dc->id?>" ><?=($dc->nome)?></option>
		<?php
		}
		?>
		</select> 

	</div>


	<div class="col-md-12" style="">	
		<span class="TituloForms">Descrição de cobrança</span><br>
		<i class="far fa-copyright Icons"></i>
		<p><input InputForm id="desc_cobranca" type="text" name="desc_cobranca" class="form-control" placeholder="Descrição de cobrança" /></p>
	</div>

	<div class="col-md-6" style="">	
		<span class="TituloForms">Valor de cobrança</span><br>
		<i class="far fa-copyright Icons"></i>
		<p><input InputForm id="valor_cobranca" type="tel" name="valor_cobranca" class="form-control" placeholder="Valor de cobrança" /></p>
	</div>

	<div class="col-md-6" style="">	
		<span class="TituloForms">Valor de desconto</span><br>
		<i class="far fa-copyright Icons"></i>
		<p><input InputForm id="valor_desconto" type="tel" name="valor_desconto" class="form-control" placeholder="Valor de desconto" /></p>
	</div>

	<div class="col-md-6" style="">	
		<span class="TituloForms">Data de Vencimento</span><br>
		<i class="far fa-copyright Icons"></i>
		<p><input InputForm id="data_vencimento" type="tel" name="data_vencimento" class="form-control" placeholder="Data de Vencimento" /></p>
	</div>		

	
	<input type="hidden" name="idUsuario" value="<?=$_SESSION[id_usuario]?>">
	
	<div class="col-md-12" style="">
	
		<button type="submit" class="btn btn-success SalvarCob">Salvar cobrança</button> 
	
	</div>	
	
</div>	

</form>	

<div id="SalvoNewCob" style"display:block"></div>
	
	
<script>
	


$('#formulario_novaCob').validate({
	rules : {
		cliente_id : {
			required : true
		},
		valor_cobranca : {
			required : true
		},
		valor_desconto : {
			required : true
		},
		data_vencimento : {
			required : true
		}

	},
	messages : {
		cliente_id : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o cliente.</div>'
		},
		valor_cobranca : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o valor a cobrar.</div>'
		},
		valor_desconto : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o valor de desconto.</div>'
		},
		data_vencimento : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a data de vencimento.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

		$.confirm({
			title: "<span style='color:green'>ATENÇÃO!</span>",
			content: "Deseja realmente enviar esta cobrança?",
			columnClass:"col-md-4 col-md-offset-4",
			theme: "light",
			buttons: {
				SIM: {
					btnClass: "btn-success",
					action: function(){
						
						$.ajax({
							type: "POST",
							url: "./_mailer_6_1_whats/_nova_cobranca.php",
							data: dados,
							success: function( data )
							{
								$("#SalvoNewCob").html(data);

							}
						});						
												
					}
				},
				NÃO: {
				btnClass: "btn-danger ",
					action: function(){
					}
				}
			}
		});	
	
	 

	return false;
}

});		
	
	
$("#data_vencimento").mask("99/99/9999");
	
	
	
function EnviadonNewCob(d){
	
	$.confirm({
		title: "<span style='color:green'>Status!</span>",
		content: "Cobrança enviada com sucesso!\n"+d,
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){
					
					$.ajax({
					  url: './tabelas/cobrancas/cobrancas.php',
					  success: function(data) {
					  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
						  $("#CARREGANDO").html('');
						  $('body>.tooltip').remove();
					  }
					});			
					NovaCobranca.close();
				}
			}
		}
	});		
	
}
</script>

</div>