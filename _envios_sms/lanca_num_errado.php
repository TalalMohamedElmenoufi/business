<?php
$conexao2 = mysqli_connect("localhost", "elmenoufi_root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_12");

//082019 092019 102019 112019 122019

//012020 022020 032020 042020 052020 062020 072020 082020

$mes_ano = '082019';

$pergunta = "select destination, carrierName from t_".$mes_ano."_smsStatuses where carrierName='UNKNOWN' ";
$resultado = mysqli_query($conexao2,$pergunta);

while($d = mysqli_fetch_object($resultado) ){
	
	$TiraDdarea = substr($d->destination, 4);
	
	echo $TiraDdarea." - ";
	
	mysqli_query($conexao2, "update cadastro set
	tel_tipo='Celular Erro'
	where telefone='".$TiraDdarea."'
	");
	
	
}