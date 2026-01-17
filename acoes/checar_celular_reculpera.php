<?php
include("../includes/connect.php");

list($celular) = mysqli_fetch_row(mysqli_query($conexao, "select celular from usuarios where celular = '".$_POST[Reculperacelular]."' "));

if($celular==$_POST[Reculperacelular]){
	echo 'true';
}else{
	echo 'false';
}

?>