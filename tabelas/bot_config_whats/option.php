<?php	
include("../../includes/connect.php");	


$PergOP = "select a.id, b.id_menu from bot_whats_menu a
		  left join bot_whats_menu_resposta b on b.id_menu=a.id
		  order by a.id asc
";
$RespOP = mysqli_query($conexao2,$PergOP);
while($op = mysqli_fetch_object($RespOP)){

	if(!$op->id_menu){
		$ArrayOP[] = $op->id ;
	}

}


$PerguntaOpcoes = "select id,opcao from bot_whats_menu where id in (".implode(",",$ArrayOP).") ";
$RespostaOpcoes = mysqli_query($conexao2,$PerguntaOpcoes);
while($o = mysqli_fetch_object($RespostaOpcoes)){
echo "<option RemovOP$o->id value='$o->id'>Opção $o->opcao</option>";
}
?>