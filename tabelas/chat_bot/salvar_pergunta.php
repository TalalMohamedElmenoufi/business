<?php	
include("../../includes/connect.php");	

$Conf[script] = 'tabelas/chat_bot/chat_bot';
$Script = md5($Conf[script]);

$tratPerg = explode(" ",$_POST[pergunta]) ;

foreach ($tratPerg as $key => $value ) {  
    $tratPerg1 = explode("{",$value) ;
	
	$Emoji = str_replace("}","",$tratPerg1[1]);
	$returnPerg[] = $tratPerg1[0]."". (($Emoji)?'#'.json_encode($Emoji) :'') ;
}
$pergunta_tratada = implode(" ",$returnPerg);
$pergunta_tratada2 = str_replace('"','',$pergunta_tratada);


if($_POST[codp]){

	$tipo = "Editar";
	$query = " update perguntas_bot set
				  id_grupo='".$_POST[id_grupo]."',
				  pergunta='".$_POST[pergunta]."' 
				  where id='".$_POST[codp]."'
	";
	$result = mysqli_query($conexao2, $query);
	$id = $_POST[codp];	
	
}else{
	$tipo = "Novo";
	$query = " insert into perguntas_bot set
	              id_pesquisa='".$_SESSION[$Script][Cod]."',
				  id_grupo='".$_POST[id_grupo]."',
				  pergunta='".$_POST[pergunta]."'
	";
	$result = mysqli_query($conexao2, $query);
	$id = mysqli_insert_id($conexao2);	
	
}

if($id)
?>
<script language="javascript">
	parent.VoltarPerguntas("<?=$tipo?>","<?=$id?>","<?=$_POST[id_grupo]?>","<?=$_POST[pergunta]?>");
</script>