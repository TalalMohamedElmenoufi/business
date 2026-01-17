<?php
include("../../includes/connect.php");

mysqli_query($conexao2,"update cadastro set tel_tipo = '' where categoria =  '".$_GET[Catg]."' ");
 
?>