<?php	
include("../../includes/connect.php");	

if($_POST[status]==1){
	$tabela = "perguntas_bot";
}else{
	$tabela = "resposta_bot";	
}

mysqli_query($conexao2," delete from ".$tabela." where id='".$_POST[cod]."'  ");

if($_POST[status]==1){
	$linha = "LinhaPerg".$_POST[cod];
}else{
	$linha = "LinhaResp".$_POST[cod];
}

?>
<script language="javascript">parent.voltarEcluir("<?=$linha?>","<?=$_POST[cod]?>");</script>