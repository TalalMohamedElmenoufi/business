<?php
error_reporting(0);
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("erro na conexao");

$pergunta = "select * from usuarios where aniversariantes = 'S' or aniversariantes_whats = 'S' and id != '38' ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){
	
	dispararSms($conexao,$d->id);
 
}

//echo $pergunta."<br>";	

function dispararSms($conexao,$cliente){
	
	$conexao2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("erro na conexao");		
	
	list($data) = mysqli_fetch_row(mysqli_query($conexao2, "select max(data) from mensagem_niver  "));
	
	list($idR,$mensagem) = mysqli_fetch_row(mysqli_query($conexao2, "select id, mensagem from mensagem_niver where data = '".$data."'  "));

	
echo $idR." ".$mensagem."<br>";	


$ID = intval(date('my'));
$DiaAgora = date('md');
	
//echo $idR . "<br>";

//echo $ID .' | '.  $mensagem."' <br><br>";	
	
//echo "replace into mensagem_niver set id='".$ID."', data_hoje='".$DiaAgora."', mensagem = '".$mensagem."' <br><br>";
	
if($ID!=$idR){
	
	mysqli_query($conexao2,"replace into mensagem_niver set id='".$ID."', data_hoje='".$DiaAgora."', mensagem = '".$mensagem."' ");
}	
	


	
}