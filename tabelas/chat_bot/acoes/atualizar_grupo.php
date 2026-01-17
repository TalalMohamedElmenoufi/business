<?php	
include("../../../includes/connect.php");	

mysqli_query($conexao2, " update grupos_bot set nome = '".$_POST[nome]."' where id = '".$_POST[id]."' " );

echo $_POST[nome];
?>