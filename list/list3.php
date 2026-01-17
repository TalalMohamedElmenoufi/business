<?php

   // $_SESSION[$Script][valorPag] = $_GET[valorPag] ; 

	

	if($_GET[valorPag]){$_SESSION[$Script][valorPag] = $_GET[valorPag];}

	if(!$_SESSION[$Script][valorPag]){$_SESSION[$Script][valorPag] = $_GET[valorPag];}

	

    if($_SESSION[$Script][valorPag]){ $_SESSION[$Script][valorPag] ; }else{ $_SESSION[$Script][valorPag] = 10; }

	

	if($_GET[acao]=='1' or $_GET[acao]=='2'){

	$_SESSION[$Script][avansar_mais]  = ($_SESSION[$Script][ponteiro]+$_SESSION[$Script][valorPag]) ;

	$_SESSION[$Script][avansar_menos]  = ($_SESSION[$Script][ponteiro]-$_SESSION[$Script][valorPag]) ;

	}

	

	

	$_SESSION[$Script][nrs] = $_SESSION[$Script][nr];

	

	if(!$_SESSION[$Script][acao]){$_SESSION[$Script][acao] = $_GET[acao];}

	if($_GET[acao]){$_SESSION[$Script][acao] = $_GET[acao];}

	

	if($_SESSION[$Script][acao]=='1'){$_SESSION[$Script][ponteiro] = $_SESSION[$Script][avansar_menos];}

	

	if($_SESSION[$Script][acao]=='2'){$_SESSION[$Script][ponteiro] = $_SESSION[$Script][avansar_mais];}

	

	if($_SESSION[$Script][acao]=='3'){$_SESSION[$Script][ponteiro] = '0';}

	

	if($_SESSION[$Script][acao]=='4'){$_SESSION[$Script][ponteiro] = $_SESSION[$Script][nr];}

	

	if($_SESSION[$Script][ponteiro] < '0'){$_SESSION[$Script][ponteiro] = '0';}



    if(!$_SESSION[$Script][busca]){$_SESSION[$Script][busca] = $_GET[busca];}

	if($_GET[busca]){$_SESSION[$Script][busca] = $_GET[busca];}



    if($_GET[b2]=='naocontem'){$_SESSION[$Script][busca] = false;}





	$img[0] = "<img class='Ordem' src='./img/list/asc.png' ord='asc' >" ;

	$img[1] .= "<img class='Ordem' src='./img/list/desc.png' ord='desc'  >";

	$img[2] .= "<img class='Ordem' src='./img/list/neutro.png' ord=''  >";	

	

	

	for($i =0; $i < 2; $i++){ $_SESSION[$Script][ordem] = $img; } 







	echo $_SESSION[$Script][$Script][url];

	

	if(!$_SESSION[$Script][acao]){

	$_SESSION[$Script][ponteiro] = '0';

    $_SESSION[$Script][valorPag]  = '10';

	}

	$pn = $_SESSION[$Script][ponteiro];

	$pg = $_SESSION[$Script][valorPag];

	$busca = $_SESSION[$Script][busca];



    if(!$_SESSION[$Script][campo]){$_SESSION[$Script][campo] = $_GET[ordem];}

	if($_GET[ordem]){$_SESSION[$Script][campo] = "order by ".$_GET[ordem];}



	$_SESSION[$Script][opi] = $_GET[opi] ;



	if($_SESSION[$Script][opi]==''){ $opi=2  ; }else{ $opi=$_SESSION[$Script][opi] ; }

	if($opi==0){ $ordenar='asc' ; }

	if($opi==1){ $ordenar='desc' ; }



    if(!$_SESSION[$Script][ordenar]){$_SESSION[$Script][ordenar] = $ordenar;}

	if($ordenar){$_SESSION[$Script][ordenar] = $ordenar;}



	$_SESSION[$Script][checkbox] = "<input type='checkbox' class='MT".$_SESSION[$Script][MT]." TitlePadrao' id='MT".$_SESSION[$Script][MT]."' onclick='MT_".$_SESSION[$Script][MT]."();' data-toggle='tooltip' data-placement='top' title='Marcar todos'  />" ;

?>











<style>

.NovoRegistro{
	font-size:34px;
	cursor:pointer;
	 
}
.NovoRegistro:hover{
	color:#428bca;
}	
	
.Excluir{
	font-size:34px;
	cursor:pointer;
	margin-right:6px;
}
.Excluir:hover{
	color:#E50003;
}

.Filtro{
	font-size:28px;
	cursor:pointer;
	margin-right:6px;
}
.Filtro:hover{
	color:#026404;
}
	
.ParaTodos{
	font-size:28px;
	cursor:pointer;
	margin-right:6px;
}
.ParaTodos:hover{
	color:#026404;
}	
	
	
.Clickponteiro{
	font-size:28px;
	border-bottom:#000000 solid 1px; 
	cursor:pointer;
}
.Clickponteiro:hover{
	color:#428bca;
	border-bottom:#428bca solid 1px; 
}	
	
	
	
.MT<?=$_SESSION[$Script][MT]?>{
	cursor:pointer;
	width:17px;
	height:17px;
}





.Titulo{

	background:#DCD2D2;

	color:#000000;

	border:#7C7A7A solid 1px;

}


	.btnBusca{
		cursor: pointer;
	}


</style>

<div class="row"> 

	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">	
		A consulta retornou <b><?=$_SESSION[$Script][nrs]?></b> registros divididos em p&aacute;ginas de <b><?=$_SESSION[$Script][valorPag]?></b> registros.
		</div> 
	</div>

</div>


<?php
list($creditos) = mysqli_fetch_row(mysqli_query($conexao, "select creditos from usuarios where id = '".$_SESSION[id_usuario]."'  "));
?>

<div class="row"> 	

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

		<div class="row"> 

			<!--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
				<i class="fas fa-plus-circle NovoRegistro TitlePadrao" id="Novo<?=$_SESSION[$Script][Novo]?>" data-toggle="tooltip" data-placement="top" title="Novo registro"></i>
			</div>-->

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<i class="fas fa-search Icons btnBusca"></i>
				<input InputForm type="text" id="Busca" value="<?=$_SESSION[$Script][busca]?>" nrs="<?=$_SESSION[$Script][nrs]?>" class="form-control" placeholder="Pesquisar!" aria-describedby="basic-addon1" data-toggle="tooltip" data-placement="top" title="Realize sua busca e em seguida pressione ENTER">	
			</div>

		 </div>

	</div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="row"> 

			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">	
				<select class=" show-tick form-control" id="Selectponteiro<?=$_SESSION[$Script][Script]?>" ponteiro="<?=$_SESSION[$Script][avansar_menos]?>" tot=<?=$_SESSION[$Script][nrs]?> valorPag<?=$_SESSION[$Script][valorPag]?> >
				  <option value="10"  <?=(($_SESSION[$Script][valorPag] == 10)?'selected':false)?>>10</option> 
				  <option value="20"   <?=(($_SESSION[$Script][valorPag] == 20)?'selected':false)?>>20</option> 
				  <option value="30" <?=(($_SESSION[$Script][valorPag] == 30)?'selected':false)?>>30</option> 
				  <option value="50" <?=(($_SESSION[$Script][valorPag] == 50)?'selected':false)?>>50</option>
				  <option value="100" <?=(($_SESSION[$Script][valorPag] == 100)?'selected':false)?>>100</option>
				</select>					
			</div>

			<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
				<i id="Filtro_<?=$_SESSION[$Script][Filtro]?>" caminho="<?=$_SESSION[$Script][FiltroSim]?>" style="display:<?=(($_SESSION[$Script][FiltroSim])?'block':'none')?>"  class="fa fa-filter Filtro TitlePadrao" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filtro avançado"></i>
			</div>
			<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">	
				<i id="ParaTodos_<?=$_SESSION[$Script][ParaTodos]?>" caminho="<?=$_SESSION[$Script][ParaTodosSim]?>" style="display:<?=(($_SESSION[$Script][ParaTodosSim])?'block':'none')?>"  class="fas fa-layer-group ParaTodos TitlePadrao" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Ações múltiplas"></i>
			</div>	

			<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
				<i class="far fa-trash-alt Excluir TitlePadrao" id="excluir_registro_<?=$_SESSION[$Script][excluir]?>"  data-toggle="tooltip" data-placement="top" title="Excluir registro" ></i>
			</div>


			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
				<i class="fas fa-fast-backward Clickponteiro TitlePadrao" id="ponteiroInicio<?=$_SESSION[$Script][Script]?>" v="<?=$_SESSION[$Script][valorPag]?>" data-toggle="tooltip" data-placement="top" title="Inicio da tabela"></i>	

				<i class="fas fa-step-backward Clickponteiro TitlePadrao" id="Voltar<?=$_SESSION[$Script][Script]?>" ponteiro="<?=$_SESSION[$Script][avansar_menos]?>" tot="<?=$_SESSION[$Script][nrs]?>" v="<?=$_SESSION[$Script][valorPag]?>" data-toggle="tooltip" data-placement="top" title="Anterior"></i>


				<i class="fas fa-step-forward Clickponteiro TitlePadrao" id="Avancar<?=$_SESSION[$Script][Script]?>"  ponteiro="<?=$_SESSION[$Script][avansar_mais]?>" tot="<?=$_SESSION[$Script][nrs]?>" v="<?=$_SESSION[$Script][valorPag]?>" data-toggle="tooltip" data-placement="top" title="Pr&oacute;ximo"></i>		

				<i class="fas fa-fast-forward Clickponteiro TitlePadrao" id="ponteiroFim<?=$_SESSION[$Script][Script]?>" v="<?=$_SESSION[$Script][valorPag]?>" data-toggle="tooltip" data-placement="top" title="Fim da tabela"></i>		
			</div>




		</div>
	</div>
</div>	


<div class="dropdown-divider"></div>


<script>

$('[data-toggle="tooltip"]').tooltip();
	
	
		$("#Novo<?=$_SESSION[$Script][Novo]?>").click( function(){ 

		   $("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

				$.ajax({
				  url: './<?=$_SESSION[$Script][url]?>_form.php?op=novo',
				  success: function(data) {
				  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
				  $("#CARREGANDO").html('');
					  $('body>.tooltip').remove();
				  }
				});

		});


	$("#ponteiroInicio<?=$_SESSION[$Script][Script]?>").click( function(){ 

		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

				var v = $(this).attr('v');
		
				$.ajax({
				  url: "./<?=$_SESSION[$Script][url]?>.php?acao=3&valorPag="+v,
				  success: function(data) {
				  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
				  $("#CARREGANDO").html('');
					  $('body>.tooltip').remove();
				  }
				});
	
	});


	$("#Voltar<?=$_SESSION[$Script][Script]?>").click( function(){ 

		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

				var p = $(this).attr('ponteiro');
				var v = $(this).attr('v');
					
				$.ajax({
				  url: "./<?=$_SESSION[$Script][url]?>.php?acao=1&ponteiro="+p+"&valorPag="+v,
				  success: function(data) {
				  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
				  $("#CARREGANDO").html('');	  
					  $('body>.tooltip').remove();
				  }
				});

	
	});
	
	$("#Avancar<?=$_SESSION[$Script][Script]?>").click( function(){ 

		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

				var p = $(this).attr('ponteiro');
				var v = $(this).attr('v');
				
				$.ajax({
				  url: "./<?=$_SESSION[$Script][url]?>.php?acao=2&ponteiro="+p+"&valorPag="+v,
				  success: function(data) {
				  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
				  $("#CARREGANDO").html('');
					  $('body>.tooltip').remove();
				  }
				});
	 
	 });


	$("#ponteiroFim<?=$_SESSION[$Script][Script]?>").click( function(){ 

		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

				var v = $(this).attr('v');
					
				$.ajax({
				  url: "./<?=$_SESSION[$Script][url]?>.php?acao=4&valorPag="+v,
				  success: function(data) {
				  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
				  $("#CARREGANDO").html('');	
					  $('body>.tooltip').remove();
				  }
				});

	
	});


$("#Selectponteiro<?=$_SESSION[$Script][Script]?>").change(function(){
        
		 var v = $(this).val();
         var p = $(this).attr('ponteiro');

	     $("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
				
				$.ajax({
				  url: "./<?=$_SESSION[$Script][url]?>.php?acao=1&ponteiro="+p+"&valorPag="+v,
				  success: function(data) {
				  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
				  $("#CARREGANDO").html('');	
					  $('body>.tooltip').remove();
				  }
				});
	
});	
	 

	    $('#Busca').keyup( function(e){
			
			if(e.which == 13){

				    $("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
				 
					var b = $(this).val();
					
					if(b == ''){ var b2 = 'naocontem'; }else{ var b2 = 'contem';   }
					
					$.get('./<?=$_SESSION[$Script][url]?>.php?b2='+b2+'&busca='+b,
					function(data) {
					$('#<?=$_SESSION[$Script][dialog]?>').html(data);
				    $("#CARREGANDO").html('');	
						$('body>.tooltip').remove();
					});
					
			
			}	
			   
		});
		$('.btnBusca').click( function(e){


				$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

				var b = $('#Busca').val();

				if(b == ''){ var b2 = 'naocontem'; }else{ var b2 = 'contem';   }

				$.get('./<?=$_SESSION[$Script][url]?>.php?b2='+b2+'&busca='+b,
				function(data) {
				$('#<?=$_SESSION[$Script][dialog]?>').html(data);
				$("#CARREGANDO").html('');	
					$('body>.tooltip').remove();
				});
					
	
			   
		});


			
		$("#MT<?=$_SESSION[$Script][MT]?>").click(function(){

			$(".MT<?=$_SESSION[$Script][MT]?>").prop('checked', $(this).prop('checked'));	

		});



$("#Filtro_<?=$_SESSION[$Script][Filtro]?>").click(function(){

	var caminho = $(this).attr('caminho');

	 Filtro_<?=$_SESSION[$Script][Filtro]?> = $.confirm({
		title: "",
		content: "url:./"+caminho,
		columnClass:"col-md-8 col-md-offset-2",
		theme: "light",
		buttons: {
			fechar: {
				btnClass: "btn-success",
				action: function(){
				}
			},
		}				
	  });
	


});
	

$("#ParaTodos_<?=$_SESSION[$Script][ParaTodos]?>").click(function(){

	var caminho = $(this).attr('caminho');

    var val = [];
	$(".MT<?=$_SESSION[$Script][MT]?>").each( function() {
		if( $(this).prop("checked") ){	
			if( $(this).val() != 'on' ){
				val.push($(this).val());
			}			
		}
	});	
	
	if(val!=''){
		

	 ParaTodos_<?=$_SESSION[$Script][ParaTodos]?> = $.confirm({
		title: "",
		content: "url:./"+caminho+"?sel="+val,
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			fechar: {
				btnClass: "btn-success",
				action: function(){
				}
			},
		}				
	  });		
		
		
		
		
	}else{
		
	 $.confirm({
		title: "<span style='color:blue'>SEM SELEÇÃO!</span>",
		content: "<b>Para <span style='color:green'>ação especial</span> é preciso selecionar registo(s).</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){
				}
			},
		}				
	  });		
		
	
	}	
	
	
});	
	
	


//Abre a tela para inclusão de registros
$("#excluir_registro_<?=$_SESSION[$Script][excluir]?>").click(function(){		

	
    var val = [];
	$(".MT<?=$_SESSION[$Script][MT]?>").each( function() {
		if( $(this).prop("checked") ){

			if( $(this).val() != 'on' ){
				val.push($(this).val());
			}
			
		}
	});	
	
	if(val!=''){
		
     $.confirm({
		title: "<span style='color:red'>ATENÇÃO!</span>",
		content: "<b>Deseja realmente <span style='color:red'>excluir</span> os iten(s) selecionado(s)?</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			sim: {
				btnClass: "btn-success",
				action: function(){
					$("#form_list_<?=$_SESSION[$Script][excluir]?>").submit();
					$.ajax({
					url: './<?=$_SESSION[$Script][url]?>.php',
					success: function(data) {
					$('#<?=$_SESSION[$Script][dialog]?>').html(data);
						LimparExclusao();
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
		
		
		
	}else{
		
     $.confirm({
		title: "<span style='color:blue'>SEM SELEÇÃO!</span>",
		content: "<b>Para <span style='color:red'>excluir</span> é preciso selecionar registo(s).</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){
				}
			},
		}				
	  });	
		
	
	}

});
	
function LimparExclusao(){
	
	$.ajax({
		url: './<?=$_SESSION[$Script][url]?>.php',
		success: function(data) {
		$('#<?=$_SESSION[$Script][dialog]?>').html(data);
		$("#CARREGANDO").html('');
			$('body>.tooltip').remove();
		}
	});	
	
}

</script>
