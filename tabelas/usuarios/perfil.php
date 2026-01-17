<?php	
include("../../includes/connect.php");	


if($_POST){
	
	$perfil = implode('|', $_POST['perfil']);

	$query = "UPDATE usuarios SET 
			 perfil='".$perfil."'
			 WHERE id='$_POST[id]' ";	
	$result = mysqli_query($conexao,$query);
	
}

$pergunta = "select * from usuarios where id='".$_GET[id]."' ";
$resultado = mysqli_query($conexao,$pergunta);
$d = mysqli_fetch_object($resultado);

$Vperfil = explode('|',$d->perfil);
?>



<style>
.fieldset-border {
  border: 1px groove #ddd !important;
  padding: 0 0.4em 0.4em 0.4em !important;
  margin: 0 0 1.5em 0 !important;
  -webkit-box-shadow: 0px 0px 0px 0px #000;
  box-shadow: 0px 0px 0px 0px #000;
}

.fieldset-border .legend-border {
  font-size: 1.2em !important;
  text-align: left !important;
  width: auto;
  padding: 0 3px;
  border-bottom: none;
}
	
	label{
		padding: 0 !important ;
		margin-left:1px !important;
	}	
</style>












<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:0px; padding: 8px; background:#f9f9f9" >

	
<fieldset class="fieldset-border">
  <legend class="legend-border">APICAÇÕES</legend>	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="agenda" value="agenda" <?=((in_array('agenda',$Vperfil))?'checked':false)?> >
		<label for="agenda">Agenda</label> 
	</div>
	
	
	
</fieldset>
	
	
	
<fieldset class="fieldset-border">
  <legend class="legend-border">FINANCEIRO ASAAS</legend>	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="asaas" value="asaas" <?=((in_array('asaas',$Vperfil))?'checked':false)?> >
		<label for="asaas">API Asaas token</label> 
	</div>		
		
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="conta_bancaria" value="conta_bancaria" <?=((in_array('conta_bancaria',$Vperfil))?'checked':false)?> >
		<label for="conta_bancaria">Conta bancaria</label> 
	</div>		
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="financeiro_geral" value="financeiro_geral" <?=((in_array('financeiro_geral',$Vperfil))?'checked':false)?> >
		<label for="financeiro_geral">Graficos Geral Dono</label> 
	</div>		
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="grfico_financeiro" value="grfico_financeiro" <?=((in_array('grfico_financeiro',$Vperfil))?'checked':false)?> >
		<label for="grfico_financeiro">Graficos</label> 
	</div>
	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="clientes" value="clientes" <?=((in_array('clientes',$Vperfil))?'checked':false)?> >
		<label for="clientes">Clientes</label> 
	</div>		
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="cobrancas" value="cobrancas" <?=((in_array('cobrancas',$Vperfil))?'checked':false)?> >
		<label for="cobrancas">Emissão de cobranças</label> 
	</div>		
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="cobrancas_externas" value="cobrancas_externas" <?=((in_array('cobrancas_externas',$Vperfil))?'checked':false)?> >
		<label for="cobrancas_externas">Cobranças SMS</label> 
	</div>		
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="Transferencias" value="Transferencias" <?=((in_array('Transferencias',$Vperfil))?'checked':false)?> >
		<label for="Transferencias">Minhas transferências</label> 
	</div>	
	
	
	
	
</fieldset>		
	
	
	
<fieldset class="fieldset-border">
  <legend class="legend-border">E-MAIL MARKETING</legend>	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="mailmarketing" value="mailmarketing" <?=((in_array('mailmarketing',$Vperfil))?'checked':false)?> >
		<label for="mailmarketing">E-mail Marketing</label> 
	</div>		
 
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="envioMarketing" value="envioMarketing" <?=((in_array('envioMarketing',$Vperfil))?'checked':false)?> >
		<label for="envioMarketing">Envio e-mail Marketing</label> 
	</div>	
	
	
</fieldset>	
	
	
<fieldset class="fieldset-border">
  <legend class="legend-border">CADASTROS</legend>
	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="padding:2px" >
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="aniversariantes" value="aniversariantes" <?=((in_array('aniversariantes',$Vperfil))?'checked':false)?> >
		<label for="aniversariantes">Aniversariantes</label> 
	</div>	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="usuarios" value="usuarios" <?=((in_array('usuarios',$Vperfil))?'checked':false)?> >
		<label for="usuarios">Usuários - sis</label> 
	</div>

	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="server_config" value="server_config" <?=((in_array('server_config',$Vperfil))?'checked':false)?> >
		<label for="server_config">Config server</label> 
	</div>	



	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="termo" value="termo" <?=((in_array('termo',$Vperfil))?'checked':false)?> >
		<label for="termo">Termo</label> 
	</div>	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="login_acesso" value="login_acesso" <?=((in_array('login_acesso',$Vperfil))?'checked':false)?> >
		<label for="login_acesso">Login acesso</label> 
	</div>		

	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="contatos_agenda" value="contatos_agenda" <?=((in_array('contatos_agenda',$Vperfil))?'checked':false)?> >
		<label for="contatos_agenda">Contatos marketing</label> 
	</div>
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="seus_contatos" value="seus_contatos" <?=((in_array('seus_contatos',$Vperfil))?'checked':false)?> >
		<label for="seus_contatos">Contatos agenda</label> 
	</div>	
	
	
</div>		
	
</fieldset>	

	
	
<fieldset class="fieldset-border">
  <legend class="legend-border">CONFIG ATENDIMENTO</legend>	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="atendimento" value="atendimento" <?=((in_array('atendimento',$Vperfil))?'checked':false)?> >
		<label for="atendimento">Atendimento</label> 
	</div>		

	
	
</fieldset>		
	
	
<fieldset class="fieldset-border">
  <legend class="legend-border">CONFIG BOT</legend>
	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="padding:2px" >
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="perguntas_bot" value="perguntas_bot" <?=((in_array('perguntas_bot',$Vperfil))?'checked':false)?> >
		<label for="perguntas_bot">Perguntas bot</label> 
	</div>

	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="graficos" value="graficos" <?=((in_array('graficos',$Vperfil))?'checked':false)?> >
		<label for="graficos">Graficos</label> 
	</div>
	
</div>		
	
</fieldset>	
	
<fieldset class="fieldset-border">
  <legend class="legend-border">CONFIG SIS</legend>
	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="padding:2px" >
	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="solicitar_credito" value="solicitar_credito" <?=((in_array('solicitar_credito',$Vperfil))?'checked':false)?> >
		<label for="solicitar_credito">Solicitação de créditos</label> 
	</div>	

	


</div>		
	
</fieldset>		
	
<fieldset class="fieldset-border">
  <legend class="legend-border">WHATSAPP</legend>
	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="padding:2px" >
	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="whatsapp_conectar" value="whatsapp_conectar" <?=((in_array('whatsapp_conectar',$Vperfil))?'checked':false)?> >
		<label for="whatsapp_conectar">Solicitação de conexão</label> 
	</div>	


</div>		
	
</fieldset>			
	
<fieldset class="fieldset-border">
  <legend class="legend-border">SMS</legend>
	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="padding:2px" >
	 
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="sms" value="sms" <?=((in_array('sms',$Vperfil))?'checked':false)?> >
		<label for="sms">SMS Corporativo</label> 
	</div>	


</div>		
	
</fieldset>	
	
	
<fieldset class="fieldset-border">
  <legend class="legend-border">INTEGRAÇÃO</legend>
	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="padding:2px" >
	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 checkbox checkbox-primary">
		<input type="checkbox" name="perfil[]" id="integracao" value="integracao" <?=((in_array('integracao',$Vperfil))?'checked':false)?> >
		<label for="integracao">API de integração</label> 
	</div>	


</div>		
	
</fieldset>		
	
	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left" style="padding: 0;">
	<button id="Salvar" class="btn-bs-file btn btn-xs btn-success"> SALVAR </button>
</div>	

	
</div>

<div id="ChecarPerfil" style="display:none"></div>


<script>

$('#Salvar').click(function(){

	 var Perfils = new Array();

	 $(':checkbox:checked').each(function(i){
		Perfils.push($(this).val());
	 });

	
	$.ajax({
	type: "POST",
	url: "./tabelas/usuarios/perfil.php",
	data: {id:'<?=$_GET[id]?>',perfil:Perfils},
		success: function (data){
			$("#ChecarPerfil").html(data);
			Perfil.close();
		}
	});

});
	
</script>


