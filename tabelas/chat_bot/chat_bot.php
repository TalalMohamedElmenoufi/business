<?php	
include("../../includes/connect.php");	
	

	$Conf[script] = 'tabelas/chat_bot/chat_bot';
	$Script = md5($Conf[script]);
    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from grupos_bot  "));
	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/chat_bot/chat_bot';
	$_SESSION[$Script][titulo] = 'Chat bot';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST[Grupo]){
		
		//echo "<script>alert(' ".$_SESSION[$Script][Cod]." e $_POST[Grupo] ');</script>";
		
		mysqli_query($conexao2, " insert into grupos_bot set id_pesquisa='".$_SESSION[$Script][Cod]."', nome='".($_POST[Grupo])."' ");

		echo "<script>parent.VoltarBot();</script>";
		exit;
	}

	if($_POST[excluir]){

		mysqli_query($conexao2,"delete from grupos_bot where id = '".$_POST[excluir]."'  ");
		mysqli_query($conexao2,"delete from perguntas_bot where id_grupo = '".$_POST[excluir]."'  ");
		mysqli_query($conexao2,"delete from resposta_bot where id_grupo = '".$_POST[excluir]."'  ");
		
		echo "<script>parent.VoltarExclusao('$_POST[excluir]');</script>";
		exit;
	}


	
 
	if(!$_SESSION[$Script][Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}
	if($_GET[Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}

list($NomePesquisa) = mysqli_fetch_row(mysqli_query($conexao2, "select pesquisa from pesquisa_bot where id='".$_SESSION[$Script][Cod]."'  "));


/*function EmojiTrat($retorno){
	
$tratPerg = explode(" ",$retorno) ;

	foreach ($tratPerg as $key => $value ) {  
		$tratPerg1 = explode("#",$value) ;
		
		$parte1 = substr($tratPerg1[1],0, 5);
		$parte2 = substr($tratPerg1[1], -5);

		$Emoji = " '\ ".$parte1." \'".$parte2   ;
		$Emoji = str_replace("'","",$Emoji);
		$Emoji = str_replace(" ","",$Emoji);
		
		$Emoji = json_decode('"{'.$Emoji.'}"');		
		
		$returnPerg[] = ($tratPerg1[0]) .''. (($tratPerg1[1])?$Emoji:'')   ;
	}
	$pergunta_tratada = implode(' ',$returnPerg) ;		

	echo $pergunta_tratada ;
	
}*/


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
	
	.NovaPergunta{
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
	
	.Responder{
		font-size:28px;
		cursor:pointer;
		float:right;
		margin-right:20px;
	}	
	
	.VoltarPg{
		float:right !important;
		cursor:pointer;
	}

	.emojiPickerIconWrap{
		width:100% !important;
	}
	.emojiBot{
		width:100% !important;
	}
	
	.EditarGrupo{
		cursor:pointer;
		font-size:18px;
		margin-left:30px;
	}
	.EditarGrupo:hover{
		color:#1B8B00;
	}
	
	.DeletaGrupo{
		cursor:pointer;
		font-size:18px;
		margin-left:10px;
	}
	.DeletaGrupo:hover{
		color:#ED0404;
	}
</style>

<div class="card" >
<div class="card-body">
	

	
		
		<div class="panel-heading">
			<?=($NomePesquisa)?>
		</div>

			

			<div class="row">
				<div class="col-md-10" >
				<a class="VoltarPg VoltarPara" data-toggle="tooltip" data-placement="top" title="Voltar para as pesquisas" >Voltar</a>
				</div>
			</div>

			<div class="dropdown-divider"></div>


					


					<?php
					$pergunta = "select * from grupos_bot where id_pesquisa='".$_SESSION[$Script][Cod]."' order by id ";
					$resultado = mysqli_query($conexao2, $pergunta);	
					while($d = mysqli_fetch_object($resultado)){
					?>	
			
						<div class="table-responsive" style="overflow-x:auto;" >
							
						<fieldset class="fieldset-border" id="ListaGrupo<?=$d->id?>"  >
							
						<legend class="legend-border">
							
						<div  style="float:left" id="TextGrupo<?=$d->id?>"><?=($d->nome)?></div>
							
						<div style="float:left;">	
						<input type="text" class="form-control CampoGrupo" Cod="<?=$d->id?>" id="EditarGrupo<?=$d->id?>" style="display:none" />		
						</div>
							
						<div style="float:left; position:relative">		
						<i class="far fa-edit EditarGrupo" id="EditGrup<?=$d->id?>" Cod="<?=$d->id?>" Conteudo="<?=($d->nome)?>" data-toggle="tooltip" data-placement="right" title="Editar <?=($d->nome)?>" ></i>	
							
						<i class="far fa-trash-alt DeletaGrupo" Cod="<?=$d->id?>" Conteudo="<?=($d->nome)?>" data-toggle="tooltip" data-placement="right" title="Deletar <?=($d->nome)?>"></i>	
						</div>
							
						</legend>

						<div class="col-md-12" style="border: #F40004 solid 0px; margin:0; padding:0" >	
							<i class="fas fa-plus-square NovaPergunta" Cod="<?=$d->id?>" data-toggle="tooltip" data-placement="right" title="Criar nova pergunta"></i>
							
						</div>	
							
						<div class="NovaPerg<?=$d->id?>" style="display:none; border: #FC0105 solid 0px; height:55px; padding:0; margin:0">

							<div class="row">
								<div class="col-xs-9 col-sm-9 col-md-10 col-lg-10"  >
		
								<label class="trigger col-md-12" id="Pergunta<?=$d->id?>_env" onclick="EmojiClick( this.id )">
								  <i EMOJI class="far fa-grin"></i><input EMOJI type="checkbox" class="checkbox" />		
								  <div class="msg EmojiVer"></div>
								</label>
									
								  <input InputForm type="text" id="Pergunta<?=$d->id?>" class="form-control emojiBot" placeholder="Sua Pergunta">
									
								</div>
								<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2" >
								   <button type="button" id="SalvarPerg<?=$d->id?>" class="btn btn-success btn-sm SalvarPerg" cod="<?=$d->id?>" style="margin:0; float: right">Salvar</button>	
								</div>							
							</div>
							
						</div>	
							
						<?php
						$pergnta2 = "select a.* from perguntas_bot a where a.id_grupo ='".$d->id."' order by a.id ";
						$resultado2 = mysqli_query($conexao2, $pergnta2);						
						$pergntaR = "select a.*, b.nome as grupo from  resposta_bot a 
									 left join grupos_bot b on b.id=a.vin_grupo
						where a.id_grupo ='".$d->id."' order by a.id ";
						$resultadoR = mysqli_query($conexao2, $pergntaR);
						
						list($tem) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from perguntas_bot where id_grupo ='".$d->id."'  "));
						
						list($tem2) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from resposta_bot where id_grupo ='".$d->id."'  "));
						?>

						<div class="table-responsive MostrarTabela<?=$d->id?>" style="display:<?=(($tem)?'block':'none')?>" >
							
							
						  <table class="table table-bordered" >
							<thead>
							  <tr>
								<th>Pergunta</th>
								<th>Ação</th>
								  
							  </tr>
							</thead>
							<tbody ListaPerg<?=$d->id?> >
							<?php
							while($d2 = mysqli_fetch_object($resultado2)){
							?> 
							  <tr id="LinhaPerg<?=$d2->id?>"  >
								<td><?= $d2->pergunta ?></td>
								  
								<td width="80">
									<i class="far fa-edit Editar" id="<?=$d->id?>|<?=$d2->id?>|<?=$d2->pergunta?>" onclick="EditarPergunta( this.id )" ></i>
									<i class="far fa-times-circle Excluir" id="<?=$d->id?>|<?=$d2->id?>|1" onclick="ExcluirPergunta( this.id )"></i>
								</td>
							  </tr>
							<?php
							}
							?>
							</tbody>
						  </table>
						  </div>

							
							
							
							
						<div class="col-md-12 MostraResp<?=$d->id?>" style="border: #F70004 solid 0px; height:30px; display:<?=(($tem)?'block':'none')?>">	
							<i class="fas fa-reply-all Responder" Cod="<?=$d->id?>" data-toggle="tooltip" data-placement="left" title="Criar nova resposta" ></i>	
						</div>								
							
						<div class="NovaResp<?=$d->id?>" style=" margin-top:6px; display:none">

						<div class="row">
							
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5"  >
							  <i class="far fa-copyright Icons"></i>
							  <input InputForm id="Resposta<?=$d->id?>" type="text" class="form-control" placeholder="Sua Resposta"  />
							</div>
							
							<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" >
								<?php
								$pergnta3 = "select a.* from grupos_bot a where a.id_pesquisa='".$_SESSION[$Script][Cod]."' and a.id > '".$d->id."' order by a.id ";
								$resultado3 = mysqli_query($conexao2, $pergnta3);	
								?>
								<select class="form-control" id="VinGrupo<?=$d->id?>">
								<?php
								while($d3 = mysqli_fetch_object($resultado3)){
								?>
								  <option value="<?=$d3->id?>" ><?=($d3->nome)?></option>
								<?php
								}
								?>	
								</select>
							</div>
							
							<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"  >

							<div class=" form-check form-check-warning">
							<label class="form-check-label">
							<input type="checkbox" id="com_text<?=$d->id?>" class="GerarAuto form-check-input " >
							<i class="input-helper"></i> 
							</label>
							</div>							
								
							</div>
							
							<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" >
							   <button type="button" id="SalvarResp<?=$d->id?>" class="btn btn-success btn-sm SalvarResp" Cod="<?=$d->id?>">Salvar</button>	
							</div>
						</div>		
							
							
						</div>								

						<div class="table-responsive MostrarTabela2<?=$d->id?>" style="border:#0027A4 solid 0px; width:80%; margin-top:3px; float:right; display:<?=(($tem2)?'block':'none')?>" >
						  <table class="table table-bordered" >
							<thead>
							  <tr>
								<th>Resposta</th>
								<th>Grupo</th>  
								<th>Ação</th>
							  </tr>
							</thead>
							<tbody ListaResp<?=$d->id?> >
							<?php
							while($dr = mysqli_fetch_object($resultadoR)){
							?> 
							  <tr id="LinhaResp<?=$dr->id?>">
								<td><?=($dr->resposta)?></td>
								<td><?=($dr->grupo)?></td>  
								<td >
									<i class="far fa-edit Editar" id="<?=$d->id?>|<?=$dr->id?>|<?=$dr->vin_grupo?>|<?=($dr->resposta)?>|<?=($dr->grupo)?>|<?=$dr->com_text?>" onclick="EditarResposta( this.id )" ></i>
									<i class="far fa-times-circle Excluir" id="<?=$d->id?>|<?=$dr->id?>|2" onclick="ExcluirPergunta( this.id )" ></i>
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
							
					<?php	
					}	
					?>
	
	
			<div class="dropdown-divider"></div>

	
			<div class="row">
				<div class="col-md-2 ">
				<button type="button" class="btn btn-gradient-info btn-fw Novo" data-toggle="tooltip" data-placement="top" title="Criar novo grupo">
					<i class="far fa-plus-square"></i> Novo
				</button>
				</div>

				<div class="col-md-10" >
				<a  class="VoltarPg VoltarPara" data-toggle="tooltip" data-placement="top" title="Voltar para as pesquisas" >Voltar</a>
				</div>
			</div>

			<div class="dropdown-divider"></div>
			
			<div class="TypeNovo" style="display:none; ">

				<div class="row">
					<div class="col-xs-9 col-sm-10 col-md-10 col-lg-10"  >
					  <i class="far fa-copyright Icons"></i>
					  <input InputForm id="Grupo" type="text" class="form-control" placeholder="Nome do grupo"  />
					</div>
					<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2" >
					   <button type="button" id="Salvar" class="btn btn-gradient-success btn-sm" style="float:right">Salvar</button> 						
					</div>
				</div>
				
			</div>
	
	
	
	
	</div>
</div>

<div id="SlavarPergunta"></div>
<div id="SlavarResposta"></div>
<div id="Exclisao"></div>	

<div id="ReturGrupo"></div>
<div id="ExcluirGrupo"></div>

<script language="javascript">

		
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

}	
/*Fim Ações Emojis*/		
	
	
$("[data-toggle='tooltip']").tooltip();	
	
$(".Novo").click(function(){

 	$(".TypeNovo").css("display","block");
	
});
	
	
$(".EditarGrupo").click(function(){

	let Cod = $(this).attr("Cod");
	let Conteudo = $(this).attr("Conteudo");

 	$("#TextGrupo"+Cod).css("display","none");
	$("#EditarGrupo"+Cod).css("display","block");

	$("#EditarGrupo"+Cod).focus().val( Conteudo ) ;
	
});	
$(".CampoGrupo").blur(function(){
	let Cod = $(this).attr("Cod");
	let Texto = $(this).val();
	
	$("#EditGrup"+Cod).removeAttr("Conteudo");
	$.ajax({
		type: "POST",
		url: "./tabelas/chat_bot/acoes/atualizar_grupo.php",
		data: {
			id:Cod,
			nome:Texto
		},
		success: function( data ){
			$("#ReturGrupo").html(data);
			$("#EditGrup"+Cod).attr("Conteudo",data);
			$("#TextGrupo"+Cod).text(data);
			$("#TextGrupo"+Cod).css("display","block");
			$("#EditarGrupo"+Cod).css("display","none");				
		}
	});
	
});
	
$(".DeletaGrupo").click(function(){

	let Cod = $(this).attr("Cod");
    let Conteudo = $(this).attr("Conteudo");
	
     $.confirm({
		title: "<span style='color:red'>ATENÇÃO!</span>",
		content: "<b>Deseja realmente <span style='color:red'>excluir</span> o grupo "+Conteudo+"?</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			sim: {
				btnClass: "btn-success",
				action: function(){
				
					$.ajax({
						type: "POST",
						url: "./tabelas/chat_bot/chat_bot.php",
						data: {
							excluir:Cod
						},
						success: function( data ){
							$("#ExcluirGrupo").html(data);
						}
					});	
					
				}
			},
			não: {
				btnClass: "btn-danger",
				action: function(){
				}
			},
		}				
	  });
	
});
function VoltarExclusao(Cod){
	$("#ListaGrupo"+Cod).css("display","none");
}	
	
	
$("#Salvar").click(function(){

	let Grupo = $("#Grupo").val();
	
	let q = $("#FundoCarregando");
	q.animate({opacity:1}, 600);
	q.css("display", "flex");	

	$.ajax({
		type: "POST",
		url: "./tabelas/chat_bot/chat_bot.php",
		data: {
			Grupo:Grupo
		},
		success: function( data ){
			$("#<?=$_SESSION[$Script][dialog]?>").html(data);
		}
	});	
	
	
});	


$(".NovaPergunta").click(function(){

 	let Cod = $(this).attr("Cod");
	$(".NovaPerg"+Cod).css("display","block");
	$("#SalvarPerg"+Cod).attr("codp","");
	$("#Pergunta"+Cod).val("");
	
});
	
$(".SalvarPerg").click(function(){

 	let cod = $(this).attr("cod");
	let codp = $(this).attr("codp");
	let Pergunta = $("#Pergunta"+cod).val();
	
	$.ajax({
		type: "POST",
		url: "./tabelas/chat_bot/salvar_pergunta.php",
		data: {
			id_grupo:cod,
			codp:codp,
			pergunta:Pergunta
		},
		success: function( data ){
			$("#SlavarPergunta").html(data);
		}
	});	
	
});	
	
	
	
$(".Responder").click(function(){

 	let Cod = $(this).attr("Cod");
	$(".NovaResp"+Cod).css("display","block");
	$("#Resposta"+Cod).val("");
	$("#SalvarResp"+Cod).attr("codp","");
	
});
	
$(".SalvarResp").click(function(){

 	let Cod = $(this).attr("Cod");
	let CodP = $(this).attr("codp");
	let Resposta = $("#Resposta"+Cod).val();
	let VinGrupo = $("#VinGrupo"+Cod).val();
	 
	var ckbox = $("#com_text"+Cod);
	if (ckbox.is(':checked')) {		
		var com_text = 1;	
	}else{	
		var com_text = 0;
	}
	
	$.ajax({
		type: "POST",
		url: "./tabelas/chat_bot/salvar_resposta.php",
		data: {
			id_grupo:Cod,
			CodP:CodP,
			resposta:Resposta,
			vin_grupo:VinGrupo,
			com_text:com_text
		},
		success: function( data ){
			$("#SlavarResposta").html(data);
		}
	});
	
});		
	
	
	
function VoltarBot(){

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	
	
	$.ajax({
	url: "./tabelas/chat_bot/chat_bot.php",
	success: function(data) {	
	$("#<?=$_SESSION[$Script][dialog]?>").html(data);
		$("#CARREGANDO").html('');
		$('body>.tooltip').remove();
	} 
    }); 	
	
}	

	
	
	
	
function VoltarPerguntas(tipo,id,id_grupo,pergunta){
	//alert(' id='+id+' e id_grupo='+id_grupo+' e pergunta='+pergunta);
	
	if(tipo=="Editar"){
	   $("#LinhaPerg"+id).remove();
	}
	
	$(".MostrarTabela"+id_grupo).css("display","block"); 
	$(".MostraResp"+id_grupo).css("display","block");
	$("#Pergunta"+id_grupo).val('');
	$("tbody[ListaPerg"+id_grupo+"]").append('<tr id="LinhaPerg'+id+'"><td>'+pergunta+'</td><td width="80"><i class="far fa-edit Editar" id="'+id_grupo+'|'+id+'|'+pergunta+'" onclick="EditarPergunta( this.id )" ></i> <i class="far fa-times-circle Excluir" id="'+id_grupo+'|'+id+'|1" onclick="ExcluirPergunta( this.id )" ></i></td></tr>');
	$(".NovaPerg"+id_grupo).css("display","none");
}	
	
function VoltarRespostas(tipo,id,id_grupo,resposta,vin_grupo,grupo,com_text){
	
	if(tipo=="Editar"){
	   $("#LinhaResp"+id).remove();
	}	
	
	$(".MostrarTabela2"+id_grupo).css("display","block"); 
	$(".MostraResp"+id_grupo).css("display","block");
	$("#Resposta"+id_grupo).val('');
	$("tbody[ListaResp"+id_grupo+"]").append('<tr id="LinhaResp'+id+'" ><td>'+resposta+'</td><td>'+grupo+'</td><td><i class="far fa-edit Editar"id="'+id_grupo+'|'+id+'|'+vin_grupo+'|'+resposta+'|x|'+com_text+'" onclick="EditarResposta( this.id )" ></i> <i class="far fa-times-circle Excluir" id="'+id_grupo+'|'+id+'|2" onclick="ExcluirPergunta( this.id )" ></i></td></tr>');
	$(".NovaResp"+id_grupo).css("display","none");

}
	
	
	
function EditarPergunta(dados){
	
	//alert(dados);
	var str = dados;
	var res = str.split("|");
	
	$(".NovaPerg"+res[0]).css("display","block");
	$("#Pergunta"+res[0]).val(res[2]); 
	$("#SalvarPerg"+res[0]).attr("codp", res[1]);
	

}
	

function ExcluirPergunta(dados){
	
	var str = dados;
	var res = str.split("|");
	
	$.ajax({
		type: "POST",
		url: "./tabelas/chat_bot/pesquisa_excluir.php",
		data: {
			status:res[2],
			id_grupo:res[0],
			cod:res[1]
		},
		success: function( data ){
			$("#Exclisao").html(data);
		}
	});	
	
}	
	
	
function EditarResposta(dados){

	var str = dados;
	var res = str.split("|");

	$(".NovaResp"+res[0]).css("display","block");
	$("#Resposta"+res[0]).val(res[3]); 
	$("#SalvarResp"+res[0]).attr("codp", res[1]);
	
	if (res[5] == 1) {
		$("#com_text"+res[0]).prop("checked", true);
	}else{	
		$("#com_text"+res[0]).prop("checked", false);
	}
	
	
	LopGrupoVin(res[0],res[2]);

}
		
	
function LopGrupoVin(c1,c2){
	
	$("#VinGrupo"+c1+" > option").each(function() {
		//alert( this.value +" e "+c2);
		if(this.value==c2){
		  $("#VinGrupo"+c1+" option[value="+c2+"]").attr("selected",""); 
		}else{
		  $("#VinGrupo"+c1+" option[value="+this.value+"]").removeAttr("selected"); 	
		}

	});	
	
}	
	

function voltarEcluir(l,c){
	
	$("#"+l).remove();
	
}	
	
	
$(".VoltarPara").click(function(){

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	
	
	$.ajax({
	url: "./tabelas/pesquisa_bot/pesquisa_bot.php",
	success: function(data) {
	$("#<?=$_SESSION[$Script][dialog]?>").html(data);
	$("#CARREGANDO").html('');
		$('body>.tooltip').remove();
	} 
    }); 
	
});	
</script>


		  