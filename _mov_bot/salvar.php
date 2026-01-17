<?php
error_reporting(0);
$retorno = trim(file_get_contents("php://input"));
$dadosOut = json_decode($retorno, true);

date_default_timezone_set('America/Manaus');

$id = $dadosOut[id];
$instancia = $dadosOut[instancia];
$id_whats = $dadosOut[id_whats];
$deQuem = explode("@",$dadosOut[de_quem]) ;
$paraQuem = explode("@",$dadosOut[para_quem]) ;
$mensagem = $dadosOut[mensagem];
$retorno_log = $dadosOut[retorno_log];
$data_registro = $dadosOut[data_registro];

$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_$instancia") ;

$MovBot = "CREATE TABLE `mov_bot_".date('mY')."` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(11) NOT NULL,
					`id_whats` varchar(100) NOT NULL,
					`de_quem` varchar(100) NOT NULL,
					`para_quem` varchar(100) NOT NULL,
					`mensagem` text NOT NULL,
					`ackRes` int(2) NOT NULL,
					`retorno_log` text NOT NULL,
					`data_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`entregue` int(1) NOT NULL,
					`status` int(1) NOT NULL,
					`status_bot` int(1) NOT NULL,
					 PRIMARY KEY (id)				
)";
mysqli_query($conexao2,$MovBot);


$meg_trat = str_replace("'","`",$mensagem);
$log_env = str_replace("'","`",$retorno_log);

//$meg_trat = str_replace("'"," ",$mensagem);
//$log_env = str_replace("'"," ",$retorno_log);

$savo = mysqli_query($conexao2," insert into mov_bot_".date('mY')." set
						 instancia = '".$instancia."',
						 id_whats = '".$id_whats."',
						 de_quem = '".$deQuem[0]."',
						 para_quem = '".$paraQuem[0]."',
						 mensagem = '".($meg_trat)."',
						 ackRes = '2',
						 retorno_log = '".$log_env."',
						 data_registro = '".$data_registro."'
");
if($savo){
	echo $id;
}else{
	echo " => Erro:".$id." insert into mov_bot_".date('mY')." set
						 instancia = '".$instancia."',
						 id_whats = '".$id_whats."',
						 de_quem = '".$deQuem[0]."',
						 para_quem = '".$paraQuem[0]."',
						 mensagem = '".($meg_trat)."',
						 ackRes = '2',
						 retorno_log = '".$log_env."',
						 data_registro = '".$data_registro."' " ;
}
?>