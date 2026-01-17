<?php
include("../includes/connect.php");

list($senha) = mysqli_fetch_row(mysqli_query($conexao, "select senha_ver from usuarios where senha_ver = '".$_POST[senha_antiga]."' "));

if($senha==$_POST[senha_antiga]){
	echo 'true';
}else{
	echo 'false';
}

?>