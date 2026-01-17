<?php	
include("../../includes/connect.php");	

$Conf[script] = 'tabelas/chat_bot/chat_bot';
$Script = md5($Conf[script]);

if($_POST[CodP]){
	
	$tipo = "Editar";
	
	$query = " update resposta_bot set
				  resposta='".($_POST[resposta])."',
				  vin_grupo='".$_POST[vin_grupo]."',
				  com_text='".$_POST[com_text]."'
				  where id='".$_POST[CodP]."'
	";
	$result = mysqli_query($conexao2, $query);
	$id = $_POST[CodP];
	
}else{
	
	$tipo = "Novo";
	
	$query = " insert into resposta_bot set
	  			  id_pesquisa='".$_SESSION[$Script][Cod]."',
				  id_grupo='".$_POST[id_grupo]."',
				  resposta='".($_POST[resposta])."',
				  vin_grupo='".$_POST[vin_grupo]."',
				  com_text='".$_POST[com_text]."'
	";
	$result = mysqli_query($conexao2, $query);
	$id = mysqli_insert_id($conexao2);	
	
}

list($grupo) = mysqli_fetch_row(mysqli_query($conexao2, "select nome from grupos_bot where id='".$_POST[vin_grupo]."'  "));

 
if($id)
?>
<script language="javascript">
	parent.VoltarRespostas("<?=$tipo?>","<?=$id?>","<?=$_POST[id_grupo]?>","<?=$_POST[resposta]?>","<?=$_POST[vin_grupo]?>","<?=($grupo)?>","<?=$_POST[com_text]?>");
</script>