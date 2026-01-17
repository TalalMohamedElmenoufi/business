<?php
include("../../../includes/connect.php");

list($whatsapp_conectado) = mysqli_fetch_row(mysqli_query($conexao, "select whatsapp_conectado from usuarios  where id = '".$_SESSION[id_usuario]."' "));
$wConect = explode("@",$whatsapp_conectado);
list($QtAtendimento) = mysqli_fetch_row(mysqli_query($conexao2, "select count(de_quem) from mov_bot_".date('mY')." where status='0' and de_quem != '".$wConect[0]."' group by de_quem  "));

echo $QtAtendimento;
?>