<?php	
include("../../includes/connect.php");	
include('../../includes/funcoes.php');	

$Conf[script] = 'tabelas/bot_config_whats/bot_config_whats';
$Script = md5($Conf[script]);
list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from whats_bot_menu  "));
$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/bot_config_whats/bot_config_whats';
$_SESSION[$Script][titulo] = 'Chat bot WhatsApp';


$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
$Md5 = $_SESSION[$Script][Script];
	

if($_POST[excluir]){

	//mysqli_query($conexao2,"delete from grupos_bot where id = '".$_POST[excluir]."'  ");

	echo "<script>parent.VoltarExclusao('$_POST[excluir]');</script>";
	exit;
}


	
$pergunta = "select * from  bot_whats_saldacao ";
$resultado = mysqli_query($conexao2,$pergunta);
$d = mysqli_fetch_object($resultado);
 
	//if(!$_SESSION[$Script][Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}
	//if($_GET[Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}

if($_POST[palavraChave] or $_POST[saldacao]){
	
mysqli_query($conexao2, " replace into bot_whats_saldacao set id='1', instancia='".$_SESSION[id_usuario]."', palavra_chave='".$_POST[palavraChave]."', saldacao='".$_POST[saldacao]."' ");
		
exit();	
}

if($_POST[conteudo]){

if($_POST[id_reg]){
	
	mysqli_query($conexao2, " update bot_whats_menu set opcao='".$_POST[opcao]."', conteudo='".$_POST[conteudo]."' where id='".$_POST[id_reg]."' ");

	exit();
	
}else{
	
	list($MaxIdSal) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id) from bot_whats_saldacao  "));	

	list($MaxId) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id) from bot_whats_menu  "));	
	list($opcaoDb) = mysqli_fetch_row(mysqli_query($conexao2, "select opcao from bot_whats_menu where id = '".$MaxId."' "));	

	$opcao = $opcaoDb + 1;

	//mysqli_query($conexao2, " insert into bot_whats_menu set instancia='".$_SESSION[id_usuario]."', id_saldacao='".$MaxIdSal."',  opcao='".$opcao."', conteudo='".$_POST[conteudo]."' ");
	mysqli_query($conexao2, " replace into bot_whats_menu set  id='".$opcao."',  instancia='".$_SESSION[id_usuario]."', id_saldacao='".$MaxIdSal."',  opcao='".$opcao."', conteudo='".$_POST[conteudo]."' ");
	
	
	$id = mysqli_insert_id($conexao2);	
	
	echo "<script>parent.ConteudoSalvo('$MaxId','$id','$opcao','$_POST[conteudo]','$MaxIdSal');</script>";	

	exit();		
	
}
	
	

}


if($_POST[id_excluir]){
	
	$excluir = mysqli_query($conexao2,"delete from bot_whats_menu where id='".$_POST[id_excluir]."' ");
	
	if($excluir){
		list($MaxId) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id) from bot_whats_menu  "));
		
		echo "<script>parent.ConteudoExcluido('$MaxId','$_POST[id_excluir]');</script>";	
	}
	
	exit();
}



/*Uso da respasta*/
if($_POST[Resposta]){
	

if($_POST[id_reg]){

$trat = RetiraEspaco($_FILES['Arquivo'.$_POST[id_reg]]['name']) ;
$arquivo = date('YmdHis')."_".acentos($trat) ;

$ultimos4 = substr($arquivo , -4);

if($ultimos4 == '.png' or $ultimos4 == '.PNG' or $ultimos4 == '.jpg' or  $ultimos4 == '.JPG' or  $ultimos4 == '.pdf' or  $ultimos4 == '.PDF' ){
	$ext_file = substr($ultimos4, 1);
}elseif($ultimos4 == 'JPEG' or $ultimos4 == 'jpeg'){
	$ext_file = $ultimos4;
}	

$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/business/tabelas/atendimento/upload/';	
	
move_uploaded_file($_FILES['Arquivo'.$_POST[id_reg]]['tmp_name'], $uploaddir.$arquivo);
if(($_FILES['Arquivo'.$_POST[id_reg]]['name'])){
$Arq = $arquivo;
$Arq_ext = $ext_file;

unlink($uploaddir."".$_POST[ArqName]);	
	
}else{
$Arq = $_POST[ArqName];
$Arq_ext = $_POST[ArqExt] ;			
}	
	
	
	mysqli_query($conexao2, " update bot_whats_menu_resposta set id_menu='".$_POST[Opcao]."', conteudo='".$_POST[Resposta]."', arquivo='".$Arq."', ext_arquivo='".$Arq_ext."' where id='".$_POST[id_reg]."' ");

	//echo "<script>alert(' $_POST[Resposta] ');</script>";
	
	exit();
	
}else{

$trat = RetiraEspaco($_FILES['ArquivoNovo']['name']) ;
$arquivo = date('YmdHis')."_".acentos($trat) ;

$ultimos4 = substr($arquivo , -4);

if($ultimos4 == '.png' or $ultimos4 == '.PNG' or $ultimos4 == '.jpg' or  $ultimos4 == '.JPG' or  $ultimos4 == '.pdf' or  $ultimos4 == '.PDF' ){
	$ext_file = substr($ultimos4, 1);
}elseif($ultimos4 == 'JPEG' or $ultimos4 == 'jpeg'){
	$ext_file = $ultimos4;
}

$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/business/tabelas/atendimento/upload/';	
	
move_uploaded_file($_FILES['ArquivoNovo']['tmp_name'], $uploaddir.$arquivo);
if(($_FILES['ArquivoNovo']['name'])){
$Arq = $arquivo;
$Arq_ext = $ext_file;
	
}else{
$Arq = '';
$Arq_ext = '' ;			
}	
	
	mysqli_query($conexao2, " insert into bot_whats_menu_resposta set instancia='".$_SESSION[id_usuario]."', id_menu='".$_POST[Opcao]."', conteudo='".$_POST[Resposta]."', arquivo='".$Arq."', ext_arquivo='".$Arq_ext."' ");
	$id = mysqli_insert_id($conexao2);	
	
	list($Opcao) = mysqli_fetch_row(mysqli_query($conexao2, "select opcao from bot_whats_menu where id = '".$_POST[Opcao]."' "));	
	
	$ultimos10 = substr($Arq, -10);

	$respostaTrat = str_replace("\n"," ",$_POST[Resposta]);
	$RespostaEnv = substr($respostaTrat, 0, 50);
	
	//echo "<script>alert(' $RespostaEnv ');</script>";
	
	echo "<script>parent.RespostaSalvo('$id','$Opcao','$_POST[Opcao]','$RespostaEnv','$Arq','$ultimos10','$Arq_ext');</script>";	

	exit();
	
}	
	
	
}


if($_POST[id_excluirR]){
	
	$excluir = mysqli_query($conexao2,"delete from bot_whats_menu_resposta where id='".$_POST[id_excluirR]."' ");
	
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/business/tabelas/atendimento/upload/';	
    unlink($uploaddir."".$_POST[arquivo]);
	
	if($excluir){
		
		echo "<script>parent.RespostaExcluido('$_POST[id_excluirR]');</script>";	
	}
	
	exit();
}
/*-----------------------*/


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

?>


<style>
.fieldset-border {
  border: 1px groove #ddd !important;
  padding: 0 0.4em 0.4em 0.4em !important;
  margin: 0 0 1.5em 0 !important;
  -webkit-box-shadow: 0px 0px 0px 0px #000;
  box-shadow: 0px 0px 0px 0px #000;

}

.fieldset-border .legend-border {
  font-size: 1.2em !important;
  text-align: left !important;
  width: auto;
  padding: 0 3px;
  border-bottom: none;
}
	
	
.NovaMenu{
	font-size:28px;
	cursor:pointer;
}	
	
.NovaResposta{
	font-size:28px;
	cursor:pointer;
}
	
	
.Editar{
	font-size:18px;
	cursor:pointer;
}

.Excluir{
	font-size:18px;
	cursor:pointer;
}	

.Editar:hover{
	color:#1E701A;
}

.Excluir:hover{
	color: #D50003;
}
	
textarea[InputForm]{
	padding-left:38px !important;
	height:120px !important;
}	
	
	
	
.btn-bs-file{
    position:relative;
	width:100%;
	cursor:pointer;
	padding:2px !important;
	
}
.btn-bs-file input[type="file"]{
    position: absolute;
    top: -9999999;
    filter: alpha(opacity=0);
    opacity: 0;
    width:0;
    height:0;
    outline: none;
    cursor: inherit;
	margin-left:-60px;
	margin-top:-20px;
}	
</style>

<div class="card" >
<div class="card-body">

	<div class="panel-heading">
		CONFIGURAÇÃO BOT WHATSAPP  
 
		<?php
		
		//$teste = "Teste â¶ blz agora sim â¶ ok";
		
		//echo ($teste);
		
		?>
		
	</div>


<style>

</style>	
	

	
	<div class="dropdown-divider"></div>


	<fieldset class="fieldset-border" id="ListaGrupo<?=$d->id?>"  >

	<legend class="legend-border">palavras chaves & Saudação</legend>
	
		
		<div class="col-md-12" style="border: #F40004 solid 0px; margin:0; padding:0" >	

		<input type="text" id="palavraChave" class="form-control" placeholder="Suas Palavras chaves separados por virgula" value="<?=($d->palavra_chave)?>" >			
			
		</div>
		
		
		<div class="col-md-12" style="border: #F40004 solid 0px; margin:0; padding:0" >	

		<label class="trigger col-md-12" id="Saudacao_env" onclick="EmojiClick( this.id )">
		  <i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />		
		  <div class="msg EmojiVer"></div>
		</label>			

        <textarea InputForm id="Saudacao" class="form-control" placeholder="Sua Saudação Bot" ><?=($d->saldacao)?></textarea>
		
		</div>	
			
	</fieldset>
	
	
	<fieldset class="fieldset-border" id="ListaGrupo<?=$d->id?>"  >

	<legend class="legend-border">Menu de opções Bot</legend>
	
		
	<div class="col-md-12" style="border: #F40004 solid 0px; margin:0; padding:0" >	
		<i class="fas fa-plus-square NovaMenu" Cod="<?=$d->id?>" data-toggle="tooltip" data-placement="right" title="Adicionar menu de opções"></i>

	</div>		
		
		
	<div class="NovoMenu" style="display:none; border: #FC0105 solid 0px; height:55px; padding:0; margin:0; margin-bottom:18px;">

		<div class="row">
			<div class="col-xs-9 col-sm-9 col-md-10 col-lg-10"  >

			<label class="trigger col-md-12" id="NovoMenu_env" onclick="EmojiClick( this.id )">
			  <i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />	
			  <div class="msg EmojiVer"></div>
			</label>				

			<input InputForm type="text" id="NovoMenu" class="form-control" placeholder="Novo menu de opções">
							

			</div>
			<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2" >
			   <button type="button" id="SalvarMenu" class="btn btn-success btn-sm SalvarPerg" cod="<?=$d->id?>" style="margin:0; float: right">Salvar</button>	
			</div>							
		</div>

	</div>		
		
		
		<?php
		list($tem,$id_max) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id), MAX(id) from bot_whats_menu where id_saldacao ='".$d->id."'  "));
  
		$perguntaMenu = "select * from bot_whats_menu where id_saldacao ='".$d->id."' order by id ";
		$resultadoMenu = mysqli_query($conexao2, $perguntaMenu);	
		?>		  

		<div class="table-responsive MostrarTabela" style="display:<?=(($tem)?'block':'none')?>" >
        

		  <table class="table table-bordered" >
			<thead>
			  <tr>
				<th>Opção</th>
				<th>Pergunta</th>
				<th>Ação</th>

			  </tr>
			</thead>
			<tbody ListaPerg >	

		<?php		  
		while($d2 = mysqli_fetch_object($resultadoMenu)){	
		?>	
		  <tr id="LinhaPerg<?=$d2->id?>"  >
			  
			<td width="120">
				
				<span ContList<?=$d2->id?> id="OpcaoVer<?=$d2->id?>" ><?= $d2->opcao ?></span>
				
			    <span ContForm<?=$d2->id?> style="display:none"><input id="Opcao<?=$d2->id?>"type="text" class="form-control" value="<?= $d2->opcao ?>"></span>
				
			</td>  
			<td ContList<?=$d2->id?> >
				
				<span ContList<?=$d2->id?> id="ConteudoVer<?=$d2->id?>"><?= ($d2->conteudo) ?></span>
				
			    <span ContForm<?=$d2->id?> style="display:none">

					<label class="trigger col-md-12" id="Conteudo<?=$d2->id?>_env" onclick="EmojiClick( this.id )">
					  <i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />		
					  <div class="msg EmojiVer"></div>
					</label>					

					<input InputForm id="Conteudo<?=$d2->id?>"type="text" class="form-control" value="<?= ($d2->conteudo) ?>" >
				
				</span>
				
			</td>
			<td ContList<?=$d2->id?> width="80">
				
				<span ContList<?=$d2->id?>><i class="far fa-edit Editar" id="<?=$d2->id?>|<?=($d2->conteudo)?>" onclick="EditarPergunta( this.id )" ></i>
				<i class="far fa-times-circle Excluir" id="<?=$d2->id?>" onclick="ExcluirPergunta( this.id )" style=" float:right; margin-left:4px; display:<?=(($id_max==$d2->id)?'block':'none')?>"></i></span>

				<span ContForm<?=$d2->id?> style="display:none"><button type="button" id="<?=$d2->id?>" onclick="AlterarPergunta( this.id )" class="btn btn-success btn-sm SalvarPerg" cod="<?=$d->id?>" style="margin:0; float: right" >Alterar</button></span>	
				
			</td>

			  
		  </tr>
		<?php
		}
		?>
		</tbody>
	  </table>
	  	
	</div>		
			
	</fieldset>
	
	
	
	
	<fieldset id="RespostaMenu" class="fieldset-border" id="ListaGrupo<?=$d->id?>" style="display:<?=(($tem)?'block':'none')?>" >

	<legend class="legend-border">Resposta do menu de opções</legend>

	<div class="col-md-12" style="border: #F40004 solid 0px; margin:0; padding:0" >	
		<i class="fas fa-plus-square NovaResposta" Cod="<?=$d->id?>" data-toggle="tooltip" data-placement="right" title="Adicionar resposta menu de opções"></i>

	</div>		
		
		
	<div class="NovoResposta" style="display:none; border: #FC0105 solid 0px; height:55px; padding:0; margin:0; margin-bottom:18px;">		

		
		<div class="row">
			<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"  >

			<label class="trigger col-md-12" id="NovoResposta_env" onclick="EmojiClick( this.id )">
			  <i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />	
			  <div class="msg EmojiVer"></div>
			</label>				
				
			<textarea InputForm id="NovoResposta" class="form-control" placeholder="Nova resposta do menu de opções"></textarea>
	
			</div>
			
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"  >

				
				
				<br>
				<select class="form-control" id="Opcao" >
					
				  <?php
					$PerguntaOpcoes = "select id,opcao from bot_whats_menu where id in (".implode(",",$ArrayOP).") ";
					$RespostaOpcoes = mysqli_query($conexao2,$PerguntaOpcoes);
					while($o = mysqli_fetch_object($RespostaOpcoes)){
				  ?>
				  <option RemovOP<?=$o->id?> value="<?=$o->id?>">Opção <?=$o->opcao?></option>
				  <?php
					}
				  ?>
				</select>	
				
			</div>
			
			
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"  >
				<br>
				<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Carregar arquivo PDF,PNG,JPG"> 
					<i class="far fa-file-pdf"></i> &nbsp; 
					<i class="fas fa-images" aria-hidden="true"></i> 
					<input type="file" id="ArquivoNovo" name="ArquivoNovo"  /> 
				</label>
			</div>
			
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" >
			   <br>	
			   <button type="button" id="SalvarResposta" class="btn btn-success btn-sm SalvarResp" cod="<?=$d->id?>" style="margin:0; float: right">Salvar</button>	
			</div>							
		</div>		
		
	</div>	
		
		
		
		<?php
		list($temR,$id_maxR) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id), MAX(id) from bot_whats_menu_resposta  "));
  
		$perguntaMenuR = "select a.*, b.id as idOpcao, b.opcao from bot_whats_menu_resposta a
						 left join bot_whats_menu b on b.id=a.id_menu
		order by a.id ";
		$resultadoMenuR = mysqli_query($conexao2, $perguntaMenuR);	
		?>		  

		
		<div class="table-responsive MostrarTabela2" style="display:<?=(($temR)?'block':'none')?>" >
        

		  <table class="table table-bordered" >
			<thead>
			  <tr>
				<th>Resposta</th>
				<th>Opção</th>
				<th>Arquivo</th>
				<th>Ação</th>

			  </tr>
			</thead>
			<tbody ListaResp >	

		<?php		  
		while($d2 = mysqli_fetch_object($resultadoMenuR)){

			
		$ultimos10 = substr($d2->arquivo, -10);	
				
		?>	
				
		  <tr id="LinhaResp<?=$d2->id?>"  >
			  
 
			<td ContListR<?=$d2->id?> >
				
				<span ContListR<?=$d2->id?> id="ConteudoVerR<?=$d2->id?>"><?= substr($d2->conteudo, 0, 50)  ?> ... </span>
				
			    <span ContFormR<?=$d2->id?> style="display:none">

					<label class="trigger col-md-12" id="ConteudoR<?=$d2->id?>_env" onclick="EmojiClick( this.id )">
					  <i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />		
					  <div class="msg EmojiVer"></div>
					</label>					

					<textarea InputForm id="ConteudoR<?=$d2->id?>" class="form-control"><?= ($d2->conteudo) ?></textarea>
					
				
				</span>
				
			</td>
			  
			<td >
				
				<?php
			    $seOP = ((implode(",",$ArrayOP))?' or id in ('.implode(",",$ArrayOP).') ':'');
			    ?>
				
				<span ContListR<?=$d2->id?> id="OpcaoVerR<?=$d2->id?>" ><?=$d2->opcao?></span>
				
			    <span ContFormR<?=$d2->id?> style="display:none">
					
					<select class="form-control" id="OpcaoR<?=$d2->id?>" >

					  <?php
						$PerguntaOpcoes = "select id,opcao from bot_whats_menu where id = '$d2->idOpcao' ".$seOP."  ";
						$RespostaOpcoes = mysqli_query($conexao2,$PerguntaOpcoes);
						while($o = mysqli_fetch_object($RespostaOpcoes)){
					  ?>
					  <option Opcao<?=$o->id?> value="<?=$o->id?>|<?=$o->opcao?>">Opção <?=$o->opcao?></option>
					  <?php
						}
					  ?>
					</select>
				
				
				</span>
				
			</td> 
			  
			<td >
				<span ContListR<?=$d2->id?> id="ArquivoVer<?=$d2->id?>"  data-toggle="tooltip" data-placement="top" title="<?=$d2->arquivo?>"><?=$ultimos10?></span>
				
				<span ContFormR<?=$d2->id?> style="display:none">
					
				<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Carregar arquivo PDF,PNG,JPG"> 
					<i class="far fa-file-pdf"></i> &nbsp; 
					<i class="fas fa-images" aria-hidden="true"></i> 
					<input type="file" id="Arquivo<?=$d2->id?>" name="Arquivo<?=$d2->id?>"  /> 
				</label>
					
				<input type="hidden" id="ArqName<?=$d2->id?>" value="<?=$d2->arquivo?>" />		
				<input type="hidden" id="ArqExt<?=$d2->id?>" value="<?=$d2->ext_arquivo?>" />	
					
				</span>
			</td>
			  
			  
			<td ContListR<?=$d2->id?> width="80">

				<span ContListR<?=$d2->id?>><i class="far fa-edit Editar" id="<?=$d2->id?>|<?=($d2->conteudo)?>|<?=$d2->idOpcao?>" onclick="EditarResposta( this.id )" ></i>
				<i class="far fa-times-circle Excluir" id="<?=$d2->id?>|<?=$d2->arquivo?>" onclick="ExcluirResposta( this.id )" style=" float:right; margin-left:4px; "></i></span>

				<span ContFormR<?=$d2->id?> style="display:none"><button type="button" id="<?=$d2->id?>" onclick="AlterarResposta( this.id )" class="btn btn-success btn-sm SalvarResp" cod="<?=$d->id?>" style="margin:0; float: right" >Alterar</button></span>	
				
			</td>

			  
		  </tr>
		<?php
		}
		?>
		</tbody>
	  </table>
	  	
	</div>		

		
	</fieldset>	
	
	
	
	</div>
</div>

<div id="ConteudoMenu" style="display:none"></div>

<script language="javascript">
	
$("[data-toggle='tooltip']").tooltip();	
	
	
/*Ações Emojis*/
function EmojiClick(dados){

	var str = dados;
	var Campo = str.split("_");	
	
	$.ajax({
		url: "./_emoji/emoji.php?Campo="+Campo[0],
		success: function( data ){
			$(".EmojiVer").html(data);
			$('body>.tooltip').remove();
		}
	});		
}

function MenuEmoji(dados){

	var str = dados;
	var res = str.split("-");
	
	$.ajax({
		url: "./_emoji/emoji.php?Cod="+res[0],
		success: function( data ){
			$(".EmojiVer").html(data);
			$('body>.tooltip').remove();
		}
	});
	
}
function InsertEmoji(dados){
	var str = dados;
	var res = str.split("|");	
	
	let ConteudoAt = $("#"+res[0]).val();
	
	let Conteudo = ConteudoAt + ' '+res[1];
	
	$("#"+res[0]).val(Conteudo);	
	
	if(res[0]=="Saudacao"){
	   SalvarEmoji(Conteudo);
	}	
	
}

function SalvarEmoji(dados){
	
	let palavraChave = $("#palavraChave").val();
	
	$.ajax({
		type: "POST",
		url: "./tabelas/bot_config_whats/bot_config_whats.php",
		data: {
			saldacao:dados,
			palavraChave:palavraChave
		},
		success: function( data ){
			//$("#RetornaSaldacao").html(data);
			$('body>.tooltip').remove();
		}
	});		
}	
	
/*Fim Ações Emojis*/	
	
	
/*$("span[Emoji]").click(function(){
	
	var Emoji = $(this).attr("emojiVez");
	$("#Saudacao").val(" Teste aqui com emoji "+Emoji);
	
});*/
	
$("#palavraChave").blur(function(){

	let Saldacao = $("#Saudacao").val();
	let palavraChave = $(this).val();
 
	$.ajax({
		type: "POST",
		url: "./tabelas/bot_config_whats/bot_config_whats.php",
		data: {
			saldacao:Saldacao,
			palavraChave:palavraChave
		},
		success: function( data ){
			//$("#RetornaSaldacao").html(data);
			$('body>.tooltip').remove();
		}
	});	
	
	
});
	
$("#Saudacao").blur(function(){

	let Saldacao = $(this).val();
 	let palavraChave = $("#palavraChave").val();
	
	$.ajax({
		type: "POST",
		url: "./tabelas/bot_config_whats/bot_config_whats.php",
		data: {
			saldacao:Saldacao,
			palavraChave:palavraChave
		},
		success: function( data ){
			//$("#RetornaSaldacao").html(data);
			$('body>.tooltip').remove();
		}
	});	
	
	
});
function RetornaSaldacao(teste){
	//alert(teste);
}
	
	
$(".NovaMenu").click(function(){

	$(".NovoMenu").css("display","block");
	$("#NovoMenu").focus();
	
});	
	

	
	
	
$("#SalvarMenu").click(function(){

	let Cod = $(this).attr('cod');
	let Conteudo = $("#NovoMenu").val();
	
	$(".NovoMenu").css("display","none");
	$("#NovoMenu").val("");

    $(".MostrarTabela").css("display","block"); 	
	$(".MostraResp").css("display","block");
	
	
	$.ajax({
		type: "POST",
		url: "./tabelas/bot_config_whats/bot_config_whats.php",
		data: {
			Cod:Cod,
			conteudo:Conteudo
		},
		success: function( data ){
			$("#ConteudoMenu").html(data);
			
			$("#Opcao").html('<option>Carregando opções...</option>');
			$.ajax({
			url:"./tabelas/bot_config_whats/option.php",
			success:function(dados){
			$("#Opcao").html(dados);
				$('body>.tooltip').remove();
			}
			});			
			
		}
	});		
	

	
});	

function ConteudoSalvo(MaxId,id,opcao,conteudo,MaxIdSal){

	
	
	$("tbody[ListaPerg]").append(
		
		  '<tr id="LinhaPerg'+id+'"  >'+
			  
			'<td width="120">'+
				
				'<span ContList'+id+' id="OpcaoVer'+id+'" >'+opcao+'</span>'+
				
			    '<span ContForm'+id+' style="display:none"><input id="Opcao'+id+'" type="text" class="form-control" value="'+opcao+'"></span>'+
				
			'</td>'+  
			'<td ContList'+id+' >'+
				
				'<span ContList'+id+' id="ConteudoVer'+id+'">'+conteudo+'</span>'+
				
			    '<span ContForm'+id+' style="display:none">'+
		
				'<label class="trigger col-md-12" id="Conteudo'+id+'_env" onclick="EmojiClick( this.id )">'+
				  '<i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />'+	
				  '<div class="msg EmojiVer"></div>'+
				'</label>'+		
		
				'<input InputForm id="Conteudo'+id+'" type="text" class="form-control" value="'+conteudo+'" >'+
	
				'</span>'+
				
			'</td>'+

		
			'<td ContList'+id+' width="80">'+
				
				'<span ContList'+id+'>'+
		
		        '<i class="far fa-edit Editar" id="'+id+'|'+conteudo+'" onclick="EditarPergunta( this.id )" ></i>'+ 
		
				'<i class="far fa-times-circle Excluir" id="'+id+'" onclick="ExcluirPergunta( this.id )" style="float:right; margin-left:4px;" ></i>'+
	
				'</span>'+

				'<span ContForm'+id+' style="display:none"><button type="button" id="'+id+'" onclick="AlterarPergunta( this.id )" class="btn btn-success btn-sm SalvarPerg" cod="'+MaxIdSal+'" style="margin:0; float: right" >Alterar</button></span>'+	
				
			'</td>'+

			  
		 '</tr>'		
		
		
	);
	
	$("#"+MaxId).css("display","none");
	$("#"+id).css("display","block");
	
	$("#RespostaMenu").css("display","block");	
	
}
	
	
	
function EditarPergunta(dados){
	
	var str = dados;
	var res = str.split("|");
	
	$("span[ContList"+res[0]+"]").css("display","none");
	$("span[ContForm"+res[0]+"]").css("display","block");
	
	//alert(res[0]+" e "+res[1]);
	
}

function AlterarPergunta(id){
	
	$("span[ContList"+id+"]").css("display","block");
	$("span[ContForm"+id+"]").css("display","none");
	
	let Opcao = $("#Opcao"+id).val();
	let Conteudo = $("#Conteudo"+id).val();
	
	$("#OpcaoVer"+id).text(Opcao);
	$("#ConteudoVer"+id).text(Conteudo);

	
	$.ajax({
		type: "POST",
		url: "./tabelas/bot_config_whats/bot_config_whats.php",
		data: {
			id_reg:id,
			opcao:Opcao,
			conteudo:Conteudo
		},
		success: function( data ){
			$("#ConteudoMenu").html(data);
			$('body>.tooltip').remove();
		}
	});	
	
	
	
}	

	
function ExcluirPergunta(id){
	

     $.confirm({
		title: "<span style='color:green'>Atenção!</span>",
		content: "Deseja realmente excluir o registro? "+id,
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Sim: {
				btnClass: "btn-success",
				action: function(){

					$.ajax({
						type: "POST",
						url: "./tabelas/bot_config_whats/bot_config_whats.php",
						data: {
							id_excluir:id
						},
						success: function( data ){
							$("#ConteudoMenu").html(data);
							$('body>.tooltip').remove();
						}
					});					
					
				}
			},
			'Não': {
				btnClass: "btn-danger",
				action: function(){
				}
			},
		}				
	  });	

}	

function ConteudoExcluido(id_max,id_excluido){
	
	$("#LinhaPerg"+id_excluido).css("display","none");
	$("#"+id_max).css("display","block");
	
}	
	
	
	
	
/*Uso das respostas do menu de opções*/	
$(".NovaResposta").click(function(){

	$(".NovoResposta").css("display","block");
	$("#NovoResposta").focus();
	
});	
$("#SalvarResposta").click(function(){

	let Cod = $(this).attr('cod');
	let Resposta = $("#NovoResposta").val();
	let OpcaoSel = $("#Opcao").val();
	var OP = OpcaoSel;
	var Opcao = OP.split("|");	
	
	
	$(".NovoResposta").css("display","none");
	$("#NovoResposta").val("");

	
    $(".MostrarTabela2").css("display","block"); 	
	//$(".MostraResp").css("display","block");
	
	
	var file_data = $('#ArquivoNovo').prop('files')[0];
	var form_data = new FormData();
	form_data.append('ArquivoNovo', file_data);	
	form_data.append('Cod', Cod);	
	form_data.append('Opcao', Opcao[0]);	
	form_data.append('Resposta', Resposta);	
	

	$.ajax({
		processData: false,
		contentType: false,			
		type: "POST",
		url: "./tabelas/bot_config_whats/bot_config_whats.php",
		data: form_data,
		success: function( data ){
			$("#ConteudoMenu").html(data);
			$('body>.tooltip').remove();
		}
	});		
	
});
	
	
function EditarResposta(dados){
	
	var str = dados;
	var res = str.split("|");

	$("span[ContListR"+res[0]+"]").css("display","none");
	$("span[ContFormR"+res[0]+"]").css("display","block");
	
	$("option[Opcao"+res[2]+"]").attr("selected","selected");
	
	//alert(res[0]+" e "+res[1]+" e "+res[2]);
	
}
	
	
function AlterarResposta(id){
	
	$("span[ContListR"+id+"]").css("display","block");
	$("span[ContFormR"+id+"]").css("display","none");
	
	let OpcaoSel = $("#OpcaoR"+id).val();
	let Conteudo = $("#ConteudoR"+id).val();
	
	var ArqName = $("#ArqName"+id).val();
	var ArqExt = $("#ArqExt"+id).val();
	
	var OP = OpcaoSel;
	var Opcao = OP.split("|");		
	
	$("#OpcaoVerR"+id).text(Opcao[1]);
	
	let LimitConteudo = Conteudo.substr(0,50)+" ...";
	$("#ConteudoVerR"+id).text(LimitConteudo);

	var file_data = $('#Arquivo'+id).prop('files')[0];
	
	var form_data = new FormData();
	form_data.append('Arquivo'+id, file_data);	
	form_data.append('id_reg', id);	
	form_data.append('Opcao', Opcao[0]);	
	form_data.append('Resposta', Conteudo);	
	form_data.append('ArqName', ArqName);	
	form_data.append('ArqExt', ArqExt);

    if(file_data){
	   $("#ArquivoVer"+id).text('Atualizado');   
	}
	
	$.ajax({
		processData: false,
		contentType: false,		
		type: "POST",	
		url: "./tabelas/bot_config_whats/bot_config_whats.php",
		
		data: form_data,

		success: function( data ){

			$("#ConteudoMenu").html(data);
			
			$("#Opcao").html('<option>Carregando opções...</option>');
			$.ajax({
			url:"./tabelas/bot_config_whats/option.php",
			success:function(dados){
			$("#Opcao").html(dados);
				$('body>.tooltip').remove();
			}
			});				
			
		}
	});	
	
	
	
}

	
function RespostaSalvo(id,opcao,idOpcao,conteudo,arquivo,ultimos10,Arq_ext){
	
	//alert(arquivo+" e "+ultimos10+" e "+Arq_ext);
	
	$("tbody[ListaResp]").append(
		
		
		  '<tr id="LinhaResp'+id+'"  >'+
			  
 
			'<td ContListR'+id+' >'+
				
				'<span ContListR'+id+' id="ConteudoVerR'+id+'">'+conteudo+'</span>'+
				
			    '<span ContFormR'+id+' style="display:none">'+

					'<label class="trigger col-md-12" id="ConteudoR'+id+'_env" onclick="EmojiClick( this.id )">'+
					  '<i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />'+	
					  '<div class="msg EmojiVer"></div>'+
					'</label>'+					

		
					'<textarea InputForm id="ConteudoR'+id+'" class="form-control">'+conteudo+'</textarea>'+
					
					
				
				'</span>'+
				
			'</td>'+
			  
			'<td >'+
				
				'<span ContListR'+id+' id="OpcaoVerR'+id+'" >'+opcao+'</span>'+
				
			    '<span ContFormR'+id+' style="display:none">'+
					
					'<select class="form-control" id="OpcaoR'+id+'" >'+

					  <?php
						$PerguntaOpcoes = "select id,opcao from bot_whats_menu where id in (".implode(",",$ArrayOP).") ";
						$RespostaOpcoes = mysqli_query($conexao2,$PerguntaOpcoes);
						while($o = mysqli_fetch_object($RespostaOpcoes)){
					  ?>
					  '<option Opcao<?=$o->id?> value="<?=$o->id?>|<?=$o->opcao?>">Opção <?=$o->opcao?></option>'+
					  <?php
						}
					  ?>
					'</select>'+
				
				
				'</span>'+
				
			'</td>'+ 			  
			  
		
			'<td >'+
				'<span ContListR'+id+' id="ArquivoVer'+id+'"  data-toggle="tooltip" data-placement="top" title="'+arquivo+'">'+ultimos10+'</span>'+
				
				'<span ContFormR'+id+' style="display:none">'+
					
				'<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Carregar arquivo PDF,PNG,JPG"> '+
					'<i class="far fa-file-pdf"></i> &nbsp;'+ 
					'<i class="fas fa-images" aria-hidden="true"></i>'+ 
					'<input type="file" id="Arquivo'+id+'" name="Arquivo'+id+'"  />'+ 
				'</label>'+
					
				'<input type="hidden" id="ArqName'+id+'" value="'+arquivo+'" />'+		
				'<input type="hidden" id="ArqExt'+id+'" value="'+Arq_ext+'" />'+	
					
				'</span>'+
			'</td>'+	
		
		
			'<td ContListR'+id+' width="80">'+
				
				'<span ContListR'+id+'><i class="far fa-edit Editar" id="'+id+'|'+conteudo+'|'+idOpcao+'" onclick="EditarResposta( this.id )" ></i>'+
				'<i class="far fa-times-circle Excluir" id="'+id+'" onclick="ExcluirResposta( this.id )" style=" float:right; margin-left:4px; "></i></span>'+

				'<span ContFormR'+id+' style="display:none"><button type="button" id="'+id+'" onclick="AlterarResposta( this.id )" class="btn btn-success btn-sm SalvarResp" cod="'+id+'" style="margin:0; float: right" >Alterar</button></span>'+	
				
			'</td>'+

			  
		  '</tr>'	
		
	);

	$("#"+id).css("display","block");	

	$("#Opcao").html('<option>Carregando opções...</option>');
	$.ajax({
	url:"./tabelas/bot_config_whats/option.php",
	success:function(dados){
	$("#Opcao").html(dados);
		$('body>.tooltip').remove();
	}
	});
	
}
	
function ExcluirResposta(dados){

	var str = dados;
	var res = str.split("|");	
	
     $.confirm({
		title: "<span style='color:green'>Atenção!</span>",
		content: "Deseja realmente excluir o registro? "+res[0],
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Sim: {
				btnClass: "btn-success",
				action: function(){

					$.ajax({
						type: "POST",
						url: "./tabelas/bot_config_whats/bot_config_whats.php",
						data: {
							id_excluirR:res[0],
							arquivo:res[1]
						},
						success: function( data ){
							$("#ConteudoMenu").html(data);
							$('body>.tooltip').remove();

						}
					});					
					
				}
			},
			'Não': {
				btnClass: "btn-danger",
				action: function(){
				}
			},
		}				
	  });	

}

function RespostaExcluido(id_excluido){
	
	$("#LinhaResp"+id_excluido).css("display","none");

	$("#Opcao").html('<option>Carregando opções...</option>');
	$.ajax({
	url:"./tabelas/bot_config_whats/option.php",
	success:function(dados){
	$("#Opcao").html(dados);
		$('body>.tooltip').remove();
	}
	});		
	
}
/*-------------------------------------------*/	
	
	
</script>
	  