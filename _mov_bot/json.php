<?php

$instance = $_GET[instance];
$mes_ano  = $_GET[mY];
$data  = $_GET[data];
$limit = $_GET[limit];

$MesAno = (($mes_ano)?$mes_ano:date('mY'));
$limitGet = (($limit)?'limit '.$limit:'');

$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_$instance") ;

$pergunta = "select * from mov_bot_".$MesAno."
			 where data_registro like '%$data%'
			 $limitGet
";
$resultado = mysqli_query($conexao2,$pergunta);

$strInfo = '[';
while($d = mysqli_fetch_object($resultado)){

	$de_quem = explode("@",$d->de_quem);
	$para_quem = explode("@",$d->para_quem);
	
	$strInfo .= '{';
	
    $strInfo .= '"de_quem":"'.$de_quem[0].'", ';
	$strInfo .= '"para_quem":"'.$para_quem[0].'", ';
	$strInfo .= '"mensagem":"'.($d->mensagem).'", ';
	$strInfo .= '"pesquisa":"'.$d->data_registro.'" ';
	
	$strInfo .= "},";

}
	$strInfo .= "]";
	$strInfo = str_replace(',]', ']', $strInfo);	
	echo $strInfo;

?>