<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/integracao/integracao';
$Script = md5($Conf[script]);

$pergunta = "select * from usuarios where id = '".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);

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
	
	.BaixarModeloSend{
		font-size:20px;
		cursor:pointer;
		float:right;
	}
	.BaixarModeloSend:hover{
		color:#2C9A00;
	}	
	
	.TesteSend{
		font-size:20px;
		cursor:pointer;
		float:right;
		margin-left:10px;
	}
	.TesteSend:hover{
		color:#2C9A00;
	}	
	
	.BaixarModeloSendImg{
		font-size:20px;
		cursor:pointer;
		float:right;
	}
	.BaixarModeloSendImg:hover{
		color:#2C9A00;
	}	
	
	.TesteSendImg{
		font-size:20px;
		cursor:pointer;
		float:right;
		margin-left:10px;
	}
	.TesteSendImg:hover{
		color:#2C9A00;
	}	
	
	.BaixarModeloSendPdf{
		font-size:20px;
		cursor:pointer;
		float:right;
	}
	.BaixarModeloSendPdf:hover{
		color:#2C9A00;
	}	
	
	.TesteSendPdf{
		font-size:20px;
		cursor:pointer;
		float:right;
		margin-left:10px;
	}
	.TesteSendPdf:hover{
		color:#2C9A00;
	}	
	
	
</style>


<div class="card" >
<div class="card-body">
		
		
<div class="panel-heading">
	<h3>API de integração</h3>
</div>

	
<fieldset class="fieldset-border">
  <legend class="legend-border">Send Texto de notificação WhatsApp</legend>	
	
<div class="row">		
	
	<div class="col-lg-6 col-md-6">	
		<blockquote class="blockquote">
			  <h4 class="card-title text-dark">Dados de utilização para API de integração:</h4>
			  <p class="mb-0"><b>E-mail:</b> <?=$d->email?></p>
			  <p class="mb-0"><b>Senha:</b> <?=$d->senha_ver?></p>
			  <p class="mb-0"><b>Url:</b> http://207.180.219.129/integracao/send/</p>
		</blockquote>
	</div>
	
	<div class="col-lg-6 col-md-6">	
	<blockquote class="blockquote">
	<h3 class="card-title text-dark">Requisição <b>body</b>: <i class="fab fa-mixcloud TesteSend" data-toggle="tooltip" data-placement="top" title="Teste o script em tempo real"></i> <i class="fas fa-download BaixarModeloSend" data-toggle="tooltip" data-placement="top" title="Baixar modelo curl PHP"></i></h3>
		
	<div class="highlight">

	<pre><code class="language-html" data-lang="html">      
	{
		"email": "<?=$d->email?>",
		"senha": "<?=$d->senha_ver?>",
		"numeros": "559291725319,559291969064",
		"mensagem": "HELO WORD"
	}
	</code></pre>

	</div>		
	</blockquote>
	</div>
	
</div>
	
</fieldset>
	
			
	
<fieldset class="fieldset-border">
  <legend class="legend-border">Send Arquivos de notificação WhatsApp</legend>	
	
<div class="row">		
	
	<div class="col-lg-6 col-md-6">	
		<blockquote class="blockquote">
			  <h4 class="card-title text-dark">Dados de utilização para API de integração:</h4>
			  <p class="mb-0"><b>E-mail:</b> <?=$d->email?></p>
			  <p class="mb-0"><b>Senha:</b> <?=$d->senha_ver?></p>
			  <p class="mb-0"><b>Url:</b> http://207.180.219.129/integracao/update/</p>
			   
			  <br>
			  <p class="mb-0"><b>Extensões validos:</b> array("jpeg","jpg","png","pdf","ogg","mp3","xls","xlsx","doc","docx") </p>
			  <p class="mb-0"><b>Tipo:</b> - "tipo": "<b>img</b>" ou "tipo": "<b>arq</b>"</p>
		</blockquote>
	</div>
	
	<div class="col-lg-6 col-md-6">	
	<blockquote class="blockquote">
	<h3 class="card-title text-dark">Requisição <b>POST form-data</b>:  <i class="fab fa-mixcloud TesteSendPdf" data-toggle="tooltip" data-placement="top" title="Teste o script em tempo real"></i> <i class="fas fa-download BaixarModeloSendPdf" data-toggle="tooltip" data-placement="top" title="Baixar modelo curl PHP"></i></h3>
	<div class="highlight">

	<pre><code class="language-html" data-lang="html">      
	{
		"email": "<?=$d->email?>",
		"senha": "<?=$d->senha_ver?>",
		"numeros": "559291725319,558587521623",
		"mensagem": "HELO WORD",
		"arquivo": "type-file",
		"tipo": "img"
	}
	</code></pre>

	</div>		
	</blockquote>
	</div>
	
</div>
	
</fieldset>			
	
	
	
	
		

	
</div>	
</div>	

<script>
/*$('[data-toggle="tooltip"]').tooltip();
	
	$('.BaixarModeloSend').click(function(){
		window.open('./tabelas/integracao/modelo_send.zip', '_blank');
	});		
	$('.TesteSend').click(function(){
		window.open('./tabelas/integracao/modelo_send.php', '_blank');
	});	
	
	$('.BaixarModeloSendImg').click(function(){
		window.open('./tabelas/integracao/modelo_send_img.zip', '_blank');
	});		
	$('.TesteSendImg').click(function(){
		window.open('./tabelas/integracao/modelo_send_img.php', '_blank');
	});	
	
	$('.BaixarModeloSendPdf').click(function(){
		window.open('./tabelas/integracao/modelo_send_pdf.zip', '_blank');
	});		
	$('.TesteSendPdf').click(function(){
		window.open('./tabelas/integracao/modelo_send_pdf.php', '_blank');
	});*/	
	
</script>
