<?php
include("../includes/connect.php");

$id_device = $_POST[id]."_".$_POST[device];

list($device) = mysqli_fetch_row(mysqli_query($conexao, "select id_device from rh_posntos where id_device = '".$id_device."'  "));

if(!$device){
	
mysqli_query($conexao," insert into rh_posntos set
	id_device='".$id_device."',
	device='".$_POST[device]."',
	data='".$_POST[data]."',
	empresa='".$_POST[empresa]."',
	latitude='".$_POST[latitude]."',
	longitude='".$_POST[longitude]."',
	obs='".($_POST[obs])."'
");
	
}
?>