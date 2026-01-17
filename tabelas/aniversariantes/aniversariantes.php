<?php	
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$dataHoje = date('m-d',time());

$Conf[script] = 'tabelas/aniversariantes/aniversariantes';
$Script = md5($Conf[script]);

list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where data_nascimento like '%$dataHoje%'  "));

$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/aniversariantes/aniversariantes';
$_SESSION[$Script][titulo] = 'cadastro';

$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
$Md5 = $_SESSION[$Script][Script];
	

if($_GET[AtivarNive]){

	mysqli_query($conexao,"update usuarios set aniversariantes = '".$_GET[Niver]."' where id = '".$_GET[AtivarNive]."' ");
	
	exit();
	
}

if($_GET[AtivarNiveWhats]){

	mysqli_query($conexao,"update usuarios set aniversariantes_whats = '".$_GET[Niver2]."' where id = '".$_GET[AtivarNiveWhats]."' ");
	
	exit();
	
}



if($_POST[mensagem]){
	
	
$MesAno = intval(date('my'));	 
	
list($data_hoje) = mysqli_fetch_row(mysqli_query($conexao2, "select data_hoje from mensagem_niver where id = '".$MesAno."'  "));	

if($data_hoje){
	mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', data_hoje='".$data_hoje."', mensagem = '".($_POST[mensagem])."' ");	
}else{
	
	$Hoje = date('d-m-Y',time());

	$dia = date("d",time()); // dia desejado
	$mes = date("m",time()); // MÃªs desejado
	$ano = date("Y",time()); // Ano atual
	$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); 

	if( $ultimo_dia == $dia ){

	$MesAnoAnterior = intval(date('my',time()));	

	$MesAno = intval( date('my', strtotime('+1 month', strtotime( $Hoje ))) );

	$HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));		

	mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAnoAnterior."', mensagem='".$mensagemAt."', data_hoje='".$HojeAlt."' ");		

		mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', data_hoje='".$HojeAlt."', mensagem = '".($_POST[mensagem])."' ");

	}else{
	$MesAno = intval(date('my',time()));

	$HojeAlt = date('md', strtotime('+1 days', strtotime( $Hoje )));

		mysqli_query($conexao2,"replace into mensagem_niver set id='".$MesAno."', data_hoje='".$HojeAlt."', mensagem = '".($_POST[mensagem])."' ");
	}	
	
}

	
	
	
	
	
	
	

	
	exit();
	
}

$MesAno = intval(date('my'));

list($mensagem) = mysqli_fetch_row(mysqli_query($conexao2, "select mensagem from mensagem_niver where id = '".$MesAno."'  "));

?>

<style>
.Icons{
	margin-top:17px; !important;
}

td[Editar]:hover{
cursor:pointer;
}

th[campo]:hover{
	color: #0A9835;
}


.AoclicarList:hover{
	color: #0A9835;
}	
	
	
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
</style>


<div id="RetornoNiver" style="display:none"></div>


<div class="card" >
<div class="card-body">

<div class="row">	
	<div class="col-lg-12 col-md-12" style="border: #FF0004 solid 0px; padding:15px;"> 
		<div class="panel-heading">
			Aniversariantes do dia
		</div>

		<button type="button" class="btn btn-gradient-primary btn-icon-text btn-sm Home" style="float:right">HOME</button> 
	</div>
</div>	

	       <!--Botão Aniversariantes-->
	       <?php
			list($statusNiver) = mysqli_fetch_row(mysqli_query($conexao, "select aniversariantes from usuarios where id = '".$_SESSION[id_usuario]."'  "));
	
			list($statusNiver2) = mysqli_fetch_row(mysqli_query($conexao, "select aniversariantes_whats from usuarios where id = '".$_SESSION[id_usuario]."'  "));
		   ?>
		   <style>
				#radioBtn .notActive{
				color: #3276b1;
				background-color: #fff;
				}
			   
			   #radioBtn2 .notActive{
				color: #3276b1;
				background-color: #fff;
				}
			   
		   </style>
	  
	
			<div class="row">
			
			<div class="col-md-6 col-lg-6">	
			<fieldset class="fieldset-border">
			  <legend class="legend-border">ATIVAR SERVIÇO SMS</legend>	

				<div class="input-group" style="margin-top:4px;">
					<div id="radioBtn" class="btn-group">
						<a class="btn btn-success btn-sm <?=(($statusNiver=='S')?'active':'notActive')?> " data-toggle="happy" data-title="S">Ativado</a>
						<a class="btn btn-danger btn-sm <?=(($statusNiver=='N')?'active':'notActive')?>" data-toggle="happy" data-title="N">Inativo</a>
					</div>
					<input type="hidden" name="happy" id="happy">
				</div>


			</fieldset>				
			</div>	

			
			<div class="col-md-6 col-lg-6">	
			<fieldset class="fieldset-border">
			  <legend class="legend-border">ATIVAR SERVIÇO WHATSAPP</legend>	

				<div class="input-group" style="margin-top:4px;">
					<div id="radioBtn2" class="btn-group">
						<a class="btn btn-success btn-sm <?=(($statusNiver2=='S')?'active':'notActive')?> " data-toggle="happy2" data-title="S">Ativado</a>
						<a class="btn btn-danger btn-sm <?=(($statusNiver2=='N')?'active':'notActive')?>" data-toggle="happy2" data-title="N">Inativo</a>
					</div>
					<input type="hidden" name="happy2" id="happy2">
				</div>


			</fieldset>				
			</div>
				
	        </div>
	
	
		    <!--Fim Botão Aniversariantes-->
	    <p>
		<i class="far fa-comment-dots IconsNiver"></i><br>
		<input InputForm id="mensagem" type="text" name="mensagem" class="form-control" placeholder="Mensagem para aniversariantes"  value="<?=($mensagem)?>" />
		</p>
		<?php
			include("../../list/list2.php");
			$pergunta = "select a.* from cadastro a
			where a.nome like '%$busca%' and a.data_nascimento  like '%$dataHoje%'
			".$_SESSION[$Script][campo]."
			".$_SESSION[$Script][ordenar]."
			limit $pn,$pg ";
			$resultado = mysqli_query($conexao2, $pergunta);		
			//echo $pergunta;
		?>
		<div class="table-responsive" style="overflow-x:auto;">     
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
          <table class="table table-striped">
            <thead >
              <tr >
                <th campo ordem='a.nome'><?=$_SESSION[$Script][ordem][$opi]?> Nome</th>
                <th campo ordem='a.telefone'><?=$_SESSION[$Script][ordem][$opi]?> Celular</th>
                <th campo ordem='a.data_nascimento'><?=$_SESSION[$Script][ordem][$opi]?> Nascimento</th>
              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
			
		$CellOk = '<i class="fas fa-check" style="color:#7FE387"></i>';	

        ?> 
              <tr class="AoclicarList">
                <td Editar cod="<?=$d->id?>" > <?=($d->nome)?> </td>
				<td Editar cod="<?=$d->id?>" ><?= '('.$d->cod_estado.') '. $d->telefone ?> </td>
                <td Editar cod="<?=$d->id?>" ><?= dataBr($d->data_nascimento) ?>  </td>
              </tr>
        
        <?php
        }
        ?>
            </tbody>
          </table>

        </form>
		</div>

</div>
</div>

<script>
	
$("th[campo]").click( function(){ 


	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	var ordem = $(this).attr('ordem');
			for ( var i = 0; i < 2; i++ ) {
					if(i == <?=$opi?>){ var opi = 1 ;  break; }
					if(i == <?=$opi?>){ var opi = 2 ;  break;  }
					if(i == i){ var opi = i ;  break;  }
				}

		$.ajax({
		  url: './<?=$_SESSION[$Script][url]?>.php?ordem='+ordem+'&opi='+opi,
		  success: function(data) {
		  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
			  $("#CARREGANDO").html('');
		  }
		});

});	


	
/*$('#mensagem').keyup(function(){
	
	var mensagem = $(this).val();
	
	$.ajax({
	type: "POST",
	url: "./tabelas/aniversariantes/aniversariantes.php",
	data: {mensagem: mensagem},
		success: function (dados){
			$('#RetornoNiver').html(dados);
		}
	});		
	
	
});*/
$('#mensagem').blur(function(){
	
	var mensagem = $(this).val();
	
	$.ajax({
	type: "POST",
	url: "./tabelas/aniversariantes/aniversariantes.php",
	data: {mensagem: mensagem},
		success: function (dados){
			$('#RetornoNiver').html(dados);
		}
	});		
	
	
});	
	

	
$('#radioBtn a').on('click', function(){
	var sel = $(this).data('title');
	var tog = $(this).data('toggle');

	var CodU = "<?=$_SESSION[id_usuario]?>";

	$.ajax({
	url:"./tabelas/aniversariantes/aniversariantes.php?AtivarNive="+CodU+"&Niver="+sel,
	success: function(dados){
		$('#RetornoNiver').html(dados);
	}
	});				

	$('#'+tog).prop('value', sel);

	$('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
	$('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
});	
	

	
$('#radioBtn2 a').on('click', function(){
	var sel = $(this).data('title');
	var tog = $(this).data('toggle');

	var CodU = "<?=$_SESSION[id_usuario]?>";

	$.ajax({
	url:"./tabelas/aniversariantes/aniversariantes.php?AtivarNiveWhats="+CodU+"&Niver2="+sel,
	success: function(dados){
		$('#RetornoNiver').html(dados);
	}
	});			

	$('#'+tog).prop('value', sel);

	$('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
	$('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
});		
	
	
	
	
$(".Home").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

	$.ajax({
	  url: "./paginas/pagina_inicial.php",
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
	  $("#CARREGANDO").html('');
	  }
	});
	
});	
	
</script>