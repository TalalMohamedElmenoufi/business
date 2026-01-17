<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');

$lote = $_SESSION[id_usuario].'_'.date('ymdHis');

$query = "insert into t_".date('Y')."_emailAgendamento set 
			cod_cliente='".$_SESSION[id_usuario]."',
			data='".dataMysql($_POST[dataSms])."',
			grupos='".implode(',',$_POST[grupos])."',
			campanha='".($_POST[campanha])."',
			lote='".$lote."',
			processados='".($_POST[EmailsEnvio])."'
			";
$result = mysqli_query($conexao2, $query);
$idGrup = mysqli_insert_id($conexao2);

$grupos = implode(",",$_POST[grupos]);

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select credito_email from usuarios where id = '".$_SESSION[id_usuario]."' "));

list($QtEnvios) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria in (".$grupos.") "));

$credito_atual = ($creditoSms - $QtEnvios) ;

mysqli_query($conexao, "update usuarios set credito_email='".$credito_atual."' where id = '".$_SESSION[id_usuario]."' ");





//Resgistra todos os contatos
$categoria = implode(',',$_POST[grupos]);
$pergunta2 = "select * from cadastro where categoria in (".$categoria.") and situacao='0' and email!='' ";
$resultado2 = mysqli_query($conexao2, $pergunta2);
while($d = mysqli_fetch_object($resultado2)){

	mysqli_query($conexao2,"insert into t_".date('mY')."_emailStatuses set id_grupo='".$idGrup."', campanha='".$_POST[campanha]."', nome='".($d->nome)."', email='".$d->email."' ");

}
/*----------------------*/



?>
<script language="javascript">parent.RetornoEnvio();</script>