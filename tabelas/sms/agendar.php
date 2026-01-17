<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');

$tratPerg = explode(" ",$_POST[mensagem]) ;
foreach ($tratPerg as $key => $value ) {  
    $tratPerg1 = explode("{",$value) ;
	
	$Emoji = str_replace("}","",$tratPerg1[1]);
	$returnPerg[] = $tratPerg1[0]."". (($Emoji)?'#'.json_encode($Emoji) :'') ;
}
$pergunta_tratada = implode(" ",$returnPerg);
$mensagem = str_replace('"','',$pergunta_tratada); //mensagem='".($_POST[mensagem])."'

$query = "insert into t_".date('Y')."_smsAgendamento set 
			cod_cliente='".$_SESSION[id_usuario]."',
			data='".dataMysql($_POST[dataSms])."',
			grupos='".implode(',',$_POST[grupos])."',
			mensagem='".($mensagem)."'
			";
$result = mysqli_query($conexao2, $query);	

$grupos = implode(",",$_POST[grupos]);

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_sms from usuarios where id = '".$_SESSION[id_usuario]."' "));

list($QtEnvios) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria in (".$grupos.") and situacao = '0' and tel_tipo = 'Invalido' "));

$credito_atual = ($creditoSms - $QtEnvios) ;

mysqli_query($conexao, "update usuarios set creditos_sms='".$credito_atual."' where id = '".$_SESSION[id_usuario]."' ");

?>
<script language="javascript">parent.RetornoEnvio();</script>