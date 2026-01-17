<?php
$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_$_GET[db]") or die("SEM CONEXAO! ");

$pergunta = "select * from campanhas where id='".$_GET[id]."' ";
$resultado = mysqli_query($conexao,$pergunta);

$d = mysqli_fetch_object($resultado);

?>

<link href="/_libes_tme/bootstrap/3/bootstrap.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/_libes_tme/bootstrap/3/bootstrap.min.js" charset="utf-8"></script>

<div class="container" style="margin-top:10px;">
<div class="row"> 


<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="border:#F80206 solid 1px;">	
	
	<?=$d->mensagem1?>
	
</div>	
	
<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="border:#F80206 solid 1px;">	
	
	<?=$d->mensagem2?>
	
</div>		
	
	
	
</div>
</div>