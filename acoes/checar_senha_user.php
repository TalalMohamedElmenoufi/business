<?php
include("../includes/connect.php");

list($senha) = mysqli_fetch_row(mysqli_query($conexao2, "select senha_ver from login_acesso where senha_ver = '".$_POST[senha_antiga]."' "));

if($senha==$_POST[senha_antiga]){
	echo 'true';
}else{
	echo 'false';
}

?>