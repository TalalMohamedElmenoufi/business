<?php
include("../includes/connect.php");

list($email) = mysqli_fetch_row(mysqli_query($conexao, "select email from usuarios where email = '".$_POST[email]."' "));

if($email==$_POST[email]){
	echo 'false';
}else{
	echo 'true';
}

?>