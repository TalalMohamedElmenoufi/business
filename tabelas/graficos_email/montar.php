<?php
include("../../includes/connect.php");

$Conf[script] = 'tabelas/graficos_email/graficos_email';
$Script = md5($Conf[script]);

if(!$_SESSION[$Script][MesAno]){$_SESSION[$Script][MesAno] = $_GET[MesAno];}
if($_GET[MesAno]){$_SESSION[$Script][MesAno] = $_GET[MesAno];}

if(!$_SESSION[$Script][Mes_Ano]){$_SESSION[$Script][Mes_Ano] = $_GET[Mes_Ano];}
if($_GET[Mes_Ano]){$_SESSION[$Script][Mes_Ano] = $_GET[Mes_Ano];}


if($_SESSION[$Script][MesAno]){
	$dataTabela = $_SESSION[$Script][MesAno];
}else{
	$dataTabela = date('mY');
}

if($_SESSION[$Script][Mes_Ano]){
	$dataSel = $_SESSION[$Script][Mes_Ano];
}else{
	$dataSel = date('Y-m');
}

$pergunta = "SELECT * FROM t_".$dataTabela."_emailStatuses where date like '%$dataSel%'  ";
$resultado = mysqli_query($conexao2, $pergunta);

$strInfo = '[';
while($d = mysqli_fetch_object($resultado)){
	
	$data = $d->date;
	$data = explode('-',$data);

	$mes = $data[1].'/'.$data[0];
	$dia = $data[2];
	$ano = $data[0];	
	
	$tratDia = explode(' ',$dia);
	$diaD = $data[1].'-'.$tratDia[0];
	
	if($d->enviado=='2'){
		$status = 'Não Enviado';
	}else{
		$status = 'Enviado';
	}
	
	$envio = 1;

	$strInfo .= '{';
	$strInfo .= '"Name":"'.$status.'",';
	$strInfo .= '"mes":"'.$mes.'",';
	$strInfo .= '"dia":"'.$diaD.'",';
	$strInfo .= '"Envios":"'.$envio.'",';
	$strInfo .= '"Year":"'.$ano.'" ';

	
	$strInfo .= "},";

}
	$strInfo .= "]";
	$strInfo = str_replace(',]', ']', $strInfo);	
	echo $strInfo;

?>