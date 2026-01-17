<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/integracao/traccar';
$Script = md5($Conf[script]);

$pergunta = "select * from usuarios where id = '".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);

?>

<div class="card" >
<div class="card-body">
		
		
<div class="panel-heading">
	<h3>API de integração <img src="./img/tracar.png"  ></h3>
</div>


<h4 class="card-title text-dark">Dados de utilização para API de integração:</h4>

<blockquote class="blockquote">
	
<div class="highlight"><pre><code class="language-html" data-lang="html"><span class="nt">&lt;entry</span> <span class="na">key=</span><span class="s">'notificator.sms.manager.class'></span><span class="na">org.traccar.sms.HttpSmsClient<span class="nt">&lt;/entry&gt;</span>
 <span class="nt">&lt;entry</span> <span class="na">key=</span><span class="s">'sms.http.url'></span><span class="na">https://elmenoufi.com.br/business/integracao/send_trakar.php?<span class="nt">&lt;/entry&gt;</span>
 <span class="nt">&lt;entry</span> <span class="na">key=</span><span class="s">'sms.http.template'></span><span class="na">{"email":"<?=$d->email?>","senha":"<?=$d->senha_ver?>","numeros":"{phone}","mensagem":"{message}"}<span class="nt">&lt;/entry&gt;</span></code>

</pre></div>
 
</blockquote>			

	
	
	
	
</div>	
</div>	