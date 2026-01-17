<?php
include('../includes/connect.php');

if($_GET[sair]==1){ 
	session_destroy();
}

if(!$_SESSION[id_usuario]){
	
	echo "<script>window.location.assign('?u=login');</script>";
	
}else{
	
	$hora = date('H:i:s', time() ) ;
	 
	echo " <span style='color:#000'> $hora </span>";
	
}



?>

