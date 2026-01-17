<?php
include("../includes/connect.php");

list($celular) = mysqli_fetch_row(mysqli_query($conexao, "select celular from usuarios where celular = '".$_POST[celular]."' "));

if($celular==$_POST[celular]){
	echo 'false';
}else{
	echo 'true';
}

?>