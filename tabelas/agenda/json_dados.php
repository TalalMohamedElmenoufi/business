<?php
include("../../includes/connect.php");

$pergunta = "SELECT a.data, count(a.data) as number FROM agenda a group by a.data ";
$resultado = mysqli_query($conexao2, $pergunta);

$strInfo = '{';
while($d = mysqli_fetch_object($resultado)){
	
	$strInfo .= '"'.$d->data.'": {';
	$strInfo .= '"number":'.$d->number.' ';
	$strInfo .= "},";

}
$strInfo .= "}";
echo $strInfo;
?>