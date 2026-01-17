<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/integracao/gpswox';
$Script = md5($Conf[script]);

$pergunta = "select * from usuarios where id = '".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);

?>

<div class="card" >
<div class="card-body">
		
		
<div class="panel-heading">
	<h3>API de integração <img src="./img/gpswox.png"  ></h3>
</div>


<h4 class="card-title text-dark">Dados de utilização para API de integração:</h4>

<blockquote class="blockquote">
	
<div class="highlight"><pre><code class="language-html" data-lang="html">
<span class="nt">https://elmenoufi.com.br/business/integracao/send_wox.php?email=<?=$d->email?>&senha=<?=$d->senha_ver?>&numeros=%NUMBER%&mensagem=%MESSAGE%</span></code>
</pre></div>

</blockquote>			

	
	
	
	
</div>	
</div>	