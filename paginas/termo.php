<?php
include("../includes/connect.php");	

$pergunta = "select * from termo";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);

echo $d->termo;
?>