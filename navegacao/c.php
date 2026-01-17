<?php
$conexao = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_$_GET[db]") or die("SEM CONEXAO! ");

$pergunta = "select * from mailmarketing where id='".$_GET[id]."' ";
$resultado = mysqli_query($conexao,$pergunta);

$d = mysqli_fetch_object($resultado);


?>

<link href="/_libes_tme/bootstrap/3/bootstrap.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/_libes_tme/bootstrap/3/bootstrap.min.js" charset="utf-8"></script>

<div class="container" style="margin-top:10px;">
<div class="row"> 

<?=$d->mensagem?>
	
</div>
</div>