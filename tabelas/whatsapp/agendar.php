<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');

$trat = RetiraEspaco($_FILES['arquivo_img']['name']) ;
$arquivo_img = date('YmdHis')."_".acentos($trat) ;

$ultimos4 = substr($arquivo_img , -4);

if($ultimos4 == '.png' or $ultimos4 == '.PNG' or $ultimos4 == '.jpg' or  $ultimos4 == '.JPG' ){
	$img_ext_file = substr($ultimos4, 1);
}elseif($ultimos4 == 'JPEG' or $ultimos4 == 'jpeg'){
	$img_ext_file = $ultimos4;
}
	
	$uploaddir = './upload/';
	move_uploaded_file($_FILES['arquivo_img']['tmp_name'], $uploaddir.$arquivo_img);
	if(($_FILES['arquivo_img']['name'])){
	$img = $arquivo_img;
	$img_ext = $img_ext_file;
		
	}else{
	$img = '';
	$img_ext = '' ;			
	}


$trat2 = RetiraEspaco($_FILES['arquivo_pdf']['name']) ;
$arquivo_pdf = date('YmdHis')."_".acentos($trat2) ;

$ultimosPdf3 = substr($arquivo_pdf , -3);

if($ultimosPdf3 == 'pdf' or $ultimosPdf3 == 'PDF'){
	$uploaddir2 = './upload/';
	move_uploaded_file($_FILES['arquivo_pdf']['tmp_name'], $uploaddir2.$arquivo_pdf);
	
	if(($_FILES['arquivo_pdf']['name'])){
	$pdf = $arquivo_pdf;	
	}else{	
	$pdf = '';	
	}
	
}

 

/*$tratPerg = explode(" ",$_POST[mensagem]) ;
foreach ($tratPerg as $key => $value ) {  
    $tratPerg1 = explode("{",$value) ;
	
	$Emoji = str_replace("}","",$tratPerg1[1]);
	$returnPerg[] = $tratPerg1[0]."". (($Emoji)?'#'.json_encode($Emoji) :'') ;
}
$pergunta_tratada = implode(" ",$returnPerg);
$mensagem = str_replace('"','',$pergunta_tratada);*/

$query = "insert into t_".date('Y')."_smgAgendamento set 
			cod_cliente='".$_SESSION[id_usuario]."',
			data='".dataMysql($_POST[dataSms])."',
			grupos='".implode(',',$_POST[descricao])."',
			mensagem='".$_POST[mensagem]."'
			";
$result = mysqli_query($conexao2, $query);
$idGrup = mysqli_insert_id($conexao2);

$grupos = implode(",",$_POST[descricao]);

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_msg from usuarios where id = '".$_SESSION[id_usuario]."' "));

list($QtEnvios) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria in (".$grupos.") and situacao = '0' and tel_tipo = 'WhatsApp' "));

$credito_atual = ($creditoSms - $QtEnvios) ;

mysqli_query($conexao, "update usuarios set creditos_msg='".$credito_atual."' where id = '".$_SESSION[id_usuario]."' ");


//Resgistra todos os contatos
$categoria = implode(',',$_POST[descricao]);
$pergunta2 = "select * from cadastro where categoria in (".$categoria.") and situacao='0' and tel_tipo = 'WhatsApp' ";
$resultado2 = mysqli_query($conexao2, $pergunta2);
while($d = mysqli_fetch_object($resultado2)){

	$Tnome = ($d->nome);

	$nome = explode(' ',$Tnome);
	$celular = $d->cod_pais.' ('.$d->cod_estado.') '.$d->telefone;
	$mensagem_tratada = str_replace('[nome]',$nome[0],$_POST[mensagem]);
	$mensagem_enviar  = str_replace('[celular]',$celular,$mensagem_tratada);

	$correlationId = "tme_".date( "YmdHis", time() )  ;

	if($d->cod_estado=='92' or $d->cod_estado=='85' or $d->cod_estado=='82'){
		$tira9 = substr($d->telefone, 1);
		$cells = $d->cod_pais.''.$d->cod_estado.''.$tira9;
	}else{
		$cells = $d->cod_pais.''.$d->cod_estado.''.$d->telefone;
	}
	
	mysqli_query($conexao2,"insert into t_".date('mY')."_smgStatuses set id_grupo='".$idGrup."', mensagem='".$mensagem_enviar."', destination='".$cells."', id_pesquisa='".$_POST[pesquisa]."', img='".$img."', img_ext='".$img_ext."', pdf='".$pdf."' ");

}
/*----------------------*/

?>
<script language="javascript">parent.RetornoEnvio('<?=$_FILES['arquivo_pdf']['tmp_name']?>','<?=$_FILES['arquivo_img']['tmp_name']?>');</script>