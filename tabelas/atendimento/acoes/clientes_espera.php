<?php
include("../../../includes/connect.php");

list($whatsapp_conectado) = mysqli_fetch_row(mysqli_query($conexao, "select whatsapp_conectado from usuarios  where id = '".$_SESSION[id_usuario]."' "));
$wConect = explode("@",$whatsapp_conectado);

list($MaxId) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id) from mov_bot_".date('mY')."  "));



$clientes = "
select MAX(a.id) as IdM, a.de_quem, b.nome from mov_bot_".date('mY')." a
left join contatos_bot b on b.whatsapp=a.de_quem
where a.de_quem != '".$wConect[0]."' group by a.de_quem order by MAX(a.id) desc limit 0,100
";
$resultadoClient = mysqli_query($conexao2,$clientes); 
while( $c = mysqli_fetch_object($resultadoClient) ){
$de_quem = explode("@",$c->de_quem);	
$nome = explode(" ",$c->nome);
$nome = ($nome[0]);	
	
$de_quem_vez = substr($de_quem[0], 0, 16);	
	
	list($status) = mysqli_fetch_row(mysqli_query($conexao2, "select status from mov_bot_".date('mY')." where id = '".$c->IdM."' "));	
	
	$montar .= '<div class="form-check form-check-success ListVez" id="'.$de_quem_vez.'|'.$nome.'" onclick="ConversaClient(this.id)" > <label class="form-check-label ClienteClick"> <input type="radio" class="form-check-input" name="Clintes" id="Clintes" > <i class="input-helper"></i> '.(($nome)?$nome:$de_quem_vez).' <img Cell'.$de_quem_vez.' src="./img/what_msg.gif" style="width:22px;margin-top:4px;float:right; display:'.(($status==0)?'block':'none').' " >  <i class="far fa-user AddBot TituloBotCli" data-placement="top" title="Adicionar a agenda BOT" id="'.$de_quem_vez.'" onclick="SelectClient(this.id)" style="float:right; margin-right:7px; display:'.(($nome)?'none':'block').'"></i></label> </div>';

}

if($MaxId!=$_GET[MaxId]){
	echo $montar;
	echo "<script>$('.TituloBotCli').tooltip();$('.AddBot').mouseout(function(){	$('body>.tooltip').remove();});</script>";
}
?>