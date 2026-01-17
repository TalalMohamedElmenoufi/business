<?php	
include("../../includes/connect.php");



$pergnta = "select id, categoria, nome from cadastro a

			limit $_GET[p],500 ";
$resultado = mysqli_query($conexao2, $pergnta);

while($d = mysqli_fetch_object($resultado)){
	
	echo " $d->id ->  $d->categoria - $d->nome --- categria:$_GET[c] <br>";
	
	//mysqli_query($conexao2, " update cadastro set categoria = '".$_GET[c]."' where id = '".$d->id."' ");
	
}
?>