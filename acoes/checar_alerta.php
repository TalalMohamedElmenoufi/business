<?php
include("../includes/connect.php");

if($_POST[cod]){
	mysqli_query($conexao2, " update alertas set lido = 'S', data=NOW() where id = '".$_POST[cod]."' ");
}else{
	mysqli_query($conexao2, " insert into alertas set alerta = '".$_POST[alerta]."', lido = 'S', data=NOW()  ");
}

?>