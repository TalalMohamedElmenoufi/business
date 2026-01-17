<?php
include('../includes/connect.php');

$Conf[script] = 'tabelas/acoes/grafico';
$Script = md5($Conf[script]);

if(!$_SESSION[$Script][AnoSelect]){$_SESSION[$Script][AnoSelect] = $_GET[AnoSelect];}
if($_GET[AnoSelect]){$_SESSION[$Script][AnoSelect] = $_GET[AnoSelect];}





?>
<option InputForm value="">Mês Ano</option>
<?php
for ($x = 1; $x <= 12; $x++) {

if($x < 10){
	$x = '0'.$x;
}else{
	$x;
}	

$mesAno = $x.'-'.$_GET[AnoSelect];	

$mAno = $x.''.$_GET[AnoSelect];

$dataSel = $_GET[AnoSelect].'-'.$x;	

$MY = $x.''.$_GET[AnoSelect];	

?>
<option InputForm value="<?=$mAno?>|<?=$dataSel?>|<?=$MY?>" ><?=$x.'/'.$_GET[AnoSelect]?> </option>
<?php
}
?>