<?php
//INFO
// campanha 0 => rocessada
// campanha 1 => não aceito
// campanha 2 => resposta automatica de aceite


$cliente = 12;

$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$cliente) or die("<center>Autenticação invalida!</center>");


date_default_timezone_set($timezone);

$bosco = "nao";
	
$pergunta = "select * from mov_bot_".date('mY')." where mensagem like '$bosco' and status_bot = '0' ";
$resultado = mysqli_query($conexao, $pergunta);

while($d = mysqli_fetch_object($resultado)){

	$celular  = "9".substr($d->de_quem, 4);
	$celular2 = substr($d->de_quem, 4);
	
	//echo "ID:".$d->id_whats."<br>";
	//echo "Celular:".$celular."<br>";
	//echo "Mensagem:".$d->mensagem."<br> <hr>";
	
	$result = mysqli_query($conexao," update cadastro set situacao = '1', aceito='NAO' where telefone = '".$celular."' or telefone = '".$celular2."' ");
	if($result){
		mysqli_query($conexao," update mov_bot_".date('mY')." set status_bot = '1' where de_quem = '".$d->de_quem."' ");
		
		mysqli_query($conexao," update t_".date('mY')."_smgStatuses set campanha = '1' where destination = '".$d->de_quem."' ");
		
	}

}




?>