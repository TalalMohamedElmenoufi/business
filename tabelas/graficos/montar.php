<?php
include("../../includes/connect.php");

$Conf[script] = 'tabelas/graficos/graficos';
$Script = md5($Conf[script]);

if(!$_SESSION[$Script][Tabela]){$_SESSION[$Script][Tabela] = $_GET[Tabela];}
if($_GET[Tabela]){$_SESSION[$Script][Tabela] = $_GET[Tabela];}

if(!$_SESSION[$Script][AnoMes]){$_SESSION[$Script][AnoMes] = $_GET[AnoMes];}
if($_GET[AnoMes]){$_SESSION[$Script][AnoMes] = $_GET[AnoMes];}

if($_SESSION[$Script][AnoMes]){
	$AnoMes = $_SESSION[$Script][AnoMes];
}else{
	$AnoMes = date('Y-m');
}

if($_SESSION[$Script][Tabela]){
	$Tabela = $_SESSION[$Script][Tabela];
}else{
	$Tabela = "resposta_user_bot_".date('Y');
}

$pergunta = "SELECT a.*, b.pesquisa, c.pergunta, d.resposta FROM ".$Tabela." a
			 left join pesquisa_bot b on b.id=a.id_pesquisa
			 left join perguntas_bot c on c.id=a.id_pergunta
			 left join resposta_bot d on d.id=a.id_resposta
/*where a.ano_mes like '%$AnoMes%' */ ";
$resultado = mysqli_query($conexao2, $pergunta);



$pergunta2 = "SELECT participante from ".$Tabela."
group by participante 
";
$resultado2 = mysqli_query($conexao2, $pergunta2);
while($d = mysqli_fetch_object($resultado2)){
	$QtParticipante += count($d->participante);
}

$pergunta3 = "SELECT id_resposta from resposta_user_bot_".date('Y')."
group by id_resposta 
";
$resultado3 = mysqli_query($conexao2, $pergunta3);
while($d = mysqli_fetch_object($resultado3)){
	$QtResposta += count($d->id_resposta);
}


$strInfo = '[';
while($d = mysqli_fetch_object($resultado)){
	 
	$data = $d->ano_mes;
	$data = explode('-',$data);

	$ano = $data[0];
	$mes = $data[1];
	
	$strInfo .= '{';

	$strInfo .= '"QtParticipante":"'.$QtParticipante.'", ';
	
    $strInfo .= '"Year":"'.$ano.'", ';
	$strInfo .= '"mes":"'.$mes.'", ';
	$strInfo .= '"participante":"'.$d->participante.'", ';
	$strInfo .= '"pergunta":"'.($d->pergunta).' | '.($d->resposta).'  ", ';
	$strInfo .= '"pesquisa":"'.($d->pesquisa).'" ';
	
	$strInfo .= "},";

}
	$strInfo .= "]";
	$strInfo = str_replace(',]', ']', $strInfo);	
	echo $strInfo;
?>