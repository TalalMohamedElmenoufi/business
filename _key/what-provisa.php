<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação invalida</center>");

$pergunta = "select token, status_whats_desc from usuarios where id = '48' ";
$resultado = mysqli_query($conexao,$pergunta);
$d = mysqli_fetch_object($resultado);

$pergunta2 = "select * from server  ";
$resultado2 = mysqli_query($conexao,$pergunta2);
$d2 = mysqli_fetch_object($resultado2);


echo $d->token."|".$d2->ip."|".$d2->porta."|".$d->status_whats_desc;
?>