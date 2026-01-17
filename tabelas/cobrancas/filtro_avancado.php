<?php

$data_inicio = $_SESSION[$Script][data_inicio];
$data_fim =	$_SESSION[$Script][data_fim];

if($busca and $data_inicio and  $data_fim){
	
	$buscaVez = "
				where 
				b.nome like _utf8 '%$busca%' COLLATE utf8_unicode_ci and a.dueDate BETWEEN '$data_inicio' and '$data_fim' or
				a.status like '%$busca%' and a.dueDate BETWEEN '$data_inicio' and '$data_fim'  
	";	
	
}

elseif($data_inicio and  $data_fim){
	
	$buscaVez = "
				where 
				a.dueDate BETWEEN '$data_inicio' and '$data_fim' 
	";	
	
}

elseif($busca){
	
	$buscaVez = "
				where 
				b.nome like _utf8 '%$busca%' COLLATE utf8_unicode_ci or
				a.status like '%$busca%'or
				a.description like _utf8 '%$busca%' COLLATE utf8_unicode_ci 
	";
 }

else{

	$mes = date("m",time());
	$ano = date("Y",time());
	$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); 	
	
	$data_inicio = "$ano-$mes-01";
	$data_fim = "$ano-$mes-$ultimo_dia";
	
	$buscaVez = "
				where 
				a.dueDate BETWEEN '$data_inicio' and '$data_fim'
	";		
	
}



?>