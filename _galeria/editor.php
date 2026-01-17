<?php
include("../includes/connect.php");	
include("../includes/funcoes.php");	


$idMsn = $_GET[idMsn];

$Conf[script] = '/_galeria/editor';
$Script = md5($Conf[script]);

	$tipo_arquivo = array(
					'xls' => 'fa-file-excel-o',
					'xlsx' => 'fa-file-excel-o',
					'doc' => 'fa-file-word-o',
					'docx' => 'fa-file-word-o',
					'pdf' => 'fa-file-pdf-o',
					'ppsx' => 'fa-file-powerpoint-o',
					'pptx' => 'fa-file-powerpoint-o',
					'pps' => 'fa-file-powerpoint-o',
					'ppsm' => 'fa-file-powerpoint-o',
					'ppt' => 'fa-file-powerpoint-o',
					'txt' => 'fa-file-text-o'
		);
	
	function ext($a){
		$q = explode(".",$a);
		$p = count($q)-1;
		return $q[$p];
	}

	$ext_autorizados = array(
					'xls',
					'xlsx',
					'doc',
					'docx',
					'pdf',
					'pps',
					'ppsx',
					'pptx',
					'ppsm',
					'ppt',
					'png',
					'jpg',
					'jpeg',
					'gif',
					'txt'
		);


	if($_GET[formato]){
		$_SESSION[galeria_opc_view] = $_GET[formato];
	}else if(!$_SESSION[galeria_opc_view]){
		$_SESSION[galeria_opc_view] = 'lista';
	}


	if($_GET[galeria_opc_dir]){
		$_SESSION[galeria_opc_dir] = $_GET[galeria_opc_dir];
	}
	//$_SESSION[galeria_opc_dir] = false;

	if(!is_dir('uploads/'.$dir_)) mkdir('uploads/'.$dir_.'/', 0777, true);

	if(!$_SESSION[galeria_opc_dir]){
		$_SESSION[galeria_opc_dir] = 'uploads/'.$dir_;
	}


	if($_GET[novo_dir]){

		$NomePasta = acentos($_GET[novo_dir]);
		$NomePasta = RetiraEspaco($NomePasta);
		
		mkdir($_SESSION[galeria_opc_dir].'/'.$NomePasta.'/', 0777, true);
		
	}

	if($_GET[excluir]){
		unlink($_SESSION[galeria_opc_dir].'/'.$_GET[excluir]);
	}


	if($_GET[dir_excluir]){

		function delTree($dir) { 
		  $files = array_diff(scandir($dir), array('.','..')); 
		  foreach ($files as $file) { 
		    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
		  } 
		  return rmdir($dir); 
		}

		delTree($_GET[dir_excluir]);
		 
	}


	if (isset($_FILES['imagem'])) {
		$nome = strtolower($_FILES['imagem']['name']);
		$nome = acentos($nome);
		$nome = str_replace(" ","_",$nome);
		$ext = ext($nome);
		if(in_array($ext, $ext_autorizados)){
			$temp = $_FILES['imagem']['tmp_name'];
			move_uploaded_file($temp, $_SESSION[galeria_opc_dir].'/'.$nome);
			echo "<script>parent.recarregar_galeria();</script>";
			exit();
		}else{
			echo "<script>parent.erro();</script>";
			exit();
		}
	}


	$dirs = explode("/",$_SESSION[galeria_opc_dir]);
	$lkAcumulado = false;
	for($i=0;$i<count($dirs);$i++){
		$lkAcumulado =  (($lkAcumulado)?$lkAcumulado.'/':false).$dirs[$i];
		if($lkAcumulado != 'uploads'){
			if(is_file("uploads/_rotulos/".md5($lkAcumulado))){
				$result = @file_get_contents("uploads/_rotulos/".md5($lkAcumulado));
			}else{
				$result = $dirs[$i];
			}
			$links[] = "<a voltar cam='".$lkAcumulado."' href='#' title='Voltar para ".$result."' >".$result."</a>";
		}
	}

	$result = false;

?>

<style>
	div[carregando_<?=$Script?>]{
		background-color:#eee;
		position:absolute;
		top:0px;
		left:0px;
		height:100%;
		width:100%;
		z-index:999999;
		opacity:0.5;
		display:none;
	}
	.UploadExcel input[type="file"]{
		opacity: 0.0;
		-moz-opacity: 0.0;
		filter: alpha(opacity=000);
		position: absolute;
		right: 0px;
		width: 120px;
		padding: 0px;

	}

	.UploadExcel span{
		right:10px;
		border:solid 0px;
		top:5px;
		position:absolute;
		padding:0px;
		cursor: pointer;
	}
	span[AcessoDir]{
		cursor: pointer;
	}
	span[excluir]{
		cursor: pointer;
		color: #E40408;
		float:right;
	}
	span[editar]{
		cursor: pointer;
		color: #105202;
		float:right;
		margin-right:4px;
	}
	span[adicionar_arquivo]{
		cursor: pointer;
	}
	span[formato]{
		cursor: pointer;
	}
	
	.ExcluirArq{
		cursor:pointer;
		color:#D30003;
		float:right;
	}
	.Incluir{
		cursor:pointer;
		color:#229806;
		float:right;
		margin-right:7px;
	}
	
</style>

	<div carregando_<?=$Script?>>
    	<div style="position:relative; top:50%; text-align:center;" >
        	<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw carregando"></i>
            <span class="sr-only">Carregando...</span>
        </div>
    </div>




<div class="col-md-12">
	<h3>Você esta em: <?=@implode(" / ",$links)?></h3>
	<div class="col-md-12" style="text-align: right; margin-bottom: 20px;">

	<div class="row">	
		
	<div class="col-md-6">
	 <span formato opc='lista' class="badge danger">Formato em Lista</span>
	 <span formato opc='icone' class="badge danger">Formato em Icones</span>
	</div>

	<div class="col-md-6">
		<form action="./_galeria/editor.php" method="post" target="pagina" enctype="multipart/form-data" class="UploadExcel">
		    <span><i class="fa fa-folder" aria-hidden="true"></i> Anexar Arquivos</span>
		    <input class="arquivo_excel" type="file" name="imagem" value="Cadastrar registros do Excel">
		</form>
	</div>

	</div>
	
	</div>
	
	
	<div class="row">
	
	<div class="col-lg-3 col-md-3">
		<label>Criar um novo diretório</label>
		
	    <div class="input-group" style="margin-bottom: 20px;">
	      
	      <input NovoNomeDir type="text" class="form-control" placeholder="Nome do diretório">
			
		  <button CriarNovoDir type="button" class="btn btn-gradient-info btn-sm"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Criar</font></font></button>	
			
	    </div>


		<div class="list-group">
		<?php
			$dir = $_SESSION[galeria_opc_dir];
			$dh  = opendir($dir);
			while (false !== ($filename = readdir($dh))) {
			    
			    if($filename != '.' and $filename != '..' and is_dir($dir.'/'.$filename)){

			    	$result = @file_get_contents("uploads/_rotulos/".md5($dir.'/'.$filename));
			    	if($result){ 
			    		$rotulo = $result;
			    	}else{ 
			    		$rotulo = $filename;
			    		file_put_contents("uploads/_rotulos/".md5($dir.'/'.$filename), $rotulo);
			    	}

		?>
			<a class="list-group-item">
				<i class="fa fa-folder" aria-hidden="true"></i>
				<span AcessoDir cam="<?=$dir.'/'.$filename?>" <?=md5("uploads/_rotulos/".md5($dir.'/'.$filename))?>><?=$rotulo?></span>
				
				<span excluir cam="<?=$dir.'/'.$filename?>" ><i class="fa fa-trash" aria-hidden="true"></i></span>
				<span editar cam="<?="uploads/_rotulos/".md5($dir.'/'.$filename)?>" ><i class="fa fa-edit" aria-hidden="true"></i></span>
				
			</a>
		<?php
			    }
			    
			}
		?>
		</div>
	</div>
	<div class="col-lg-9 col-md-9">

	<div class="<?=(($_SESSION[galeria_opc_view]=='icone')?'row':false)?>">	
		
		<?php

			$dir = $_SESSION[galeria_opc_dir];
			$dh  = opendir($dir);
			while (false !== ($filename = readdir($dh))) {
			    if($filename != '.' and $filename != '..' and is_file($dir.'/'.$filename)){

			    	if($_SESSION[galeria_opc_view] == 'lista'){
		?>

		      <?php
		      	$Cam = array();
		      	$cam = explode("/",$_SERVER['PHP_SELF']);
		      	for($i=0;$i<count($cam)-1;$i++){
		      		$Cam[] = $cam[$i];
		      	}
		      	$http = substr($_SERVER["SERVER_NAME"],0,4);
		      	$cam = (($http == 'http')?false:'http://').$_SERVER["SERVER_NAME"].implode("/",$Cam);
		      ?>

			<a class="list-group-item">
				

		      <div style="width: 20px; height: 20px; background-image: url(_galeria/<?=$dir.'/'.$filename?>); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; float: left; margin-right: 10px;">
		      	  <?php
		      	  	if(ext($filename)){
		      	  		echo '<i title="'.$filename.'" class="fa '.$tipo_arquivo[ext($filename)].'" aria-hidden="true" style="font-size:20px; text-align:center; width:20px"></i>';
		      	  	}
		      	  ?>
		      </div>



				<span adicionar_arquivo cam="<?=$cam.'/'.$dir.'/'.$filename?>" tipo="<?=(($tipo_arquivo[ext($filename)])?'arq':'img')?>"><?=$filename?></span>

				
				<span class="ExcluirArq" excluir_arquivo cam="<?=$filename?>"role="button" title="Excluir" >
					<i class="fa fa-trash" aria-hidden="true"></i>
				</span>
				
				<span class="Incluir" adicionar_arquivo cam="<?=$cam.'/'.$dir.'/'.$filename?>" tipo="<?=(($tipo_arquivo[ext($filename)])?'arq':'img')?>" href="#" role="button" title="Incluir" >
					<i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
				</span>
				
			</a>


		<?php

			}else{

		?>

		    <div class="col-lg-4 col-md-4 col-sm-12 thumbnail">
		      <div style="width: auto; height: 130px; background-image: url(_galeria/<?=$dir.'/'.$filename?>); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;">
		      	  <?php
		      	  	if(ext($filename)){
		      	  		echo '<i title="'.$filename.'" class="fa '.$tipo_arquivo[ext($filename)].'" aria-hidden="true" style="font-size:130px; text-align:center; width:100%"></i>';
		      	  	}
		      	  ?>
		      </div>
		      <div class="col-sm-12 caption">
		      <p>
		      <?php
		      	$Cam = array();
		      	$cam = explode("/",$_SERVER['PHP_SELF']);
		      	for($i=0;$i<count($cam)-1;$i++){
		      		$Cam[] = $cam[$i];
		      	}
		      	$http = substr($_SERVER["SERVER_NAME"],0,4);
		      	$cam = (($http == 'http')?false:'http://').$_SERVER["SERVER_NAME"].implode("/",$Cam);
		      ?>
		      </p>
				<p>
					
				<a adicionar cam="<?=$cam.'/'.$dir.'/'.$filename?>" tipo="<?=(($tipo_arquivo[ext($filename)])?'arq':'img')?>" href="#" class="btn btn-primary btn-sm" role="button" title="Incluir"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
				</a> 
					
				<a excluir cam="<?=$filename?>" href="#" class="btn btn-danger btn-sm" role="button" title="Excluir"><i class="fa fa-trash" aria-hidden="true"></i>
				</a></p>
				  
		      </div>
		    </div>

		<?php
					}
			    }
			    
			}
		?>
	</div>
	
	</div>	
		
		
	</div>
	
</div>


<script>

//$(function(){
	
	$("span[editar]").click(function(){

		var arq = $(this).attr("cam");

		
		EditNameDir = $.confirm({
			title: "",
			content: "url:_galeria/dir.php?file="+arq,
			columnClass:"col-md-6 col-md-offset-3",
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

	

	
	
	$("a[adicionar], span[adicionar_arquivo]").click(function(){
		
		tipo = $(this).attr('tipo');
		if(tipo == 'img'){
			campo<?=$idMsn?>.insertHtml( "<img src='" + $(this).attr('cam') + "' >" );
		}else if(tipo == 'arq'){
			campo<?=$idMsn?>.insertHtml( '<a href="'+$(this).attr('cam')+'">' + campo<?=$idMsn?>.getSelection().getNative() + '</a>');
		}
		Galeria.close();
	});


	$("a[excluir], span[excluir_arquivo]").click(function(){

		cam = $(this).attr("cam");

		$.confirm({
			title: "",
			content: "Deseja realmente excluir o arquivo?",
			columnClass:"col-md-4 col-md-offset-4",
			theme: "light",
			buttons: {
				Sim: {
					btnClass: "btn-success",
					action: function(){
						
						$("div[carregando_<?=$Script?>]").css("display","block");

						$.ajax({
							url:"_galeria/editor.php?excluir="+cam,
							success: function(dados){
								Galeria.setContent(dados);
								$("div[carregando_<?=$Script?>]").css("display","none");
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
		
		

	
	});


	$("span[excluir]").click(function(){

		cam = $(this).attr("cam");

		$.confirm({
			title: "",
			content: "Deseja realmente excluir o diretório?",
			columnClass:"col-md-4 col-md-offset-4",
			theme: "light",
			buttons: {
				Sim: {
					btnClass: "btn-success",
					action: function(){
						
						$("div[carregando_<?=$Script?>]").css("display","block");

						$.ajax({
							url:"_galeria/editor.php?dir_excluir="+cam,
							success: function(dados){
								Galeria.setContent(dados);
								$("div[carregando_<?=$Script?>]").css("display","none");
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
		
		
	
	});

	$("span[AcessoDir]").click(function(){
		$("div[carregando_<?=$Script?>]").css("display","block");
		cam = $(this).attr("cam");
		$.ajax({
			url:"_galeria/editor.php?galeria_opc_dir="+cam,
			success: function(dados){
				Galeria.setContent(dados);
				$("div[carregando_<?=$Script?>]").css("display","none");
			}
		});		
	});

	$("a[voltar]").click(function(){
		$("div[carregando_<?=$Script?>]").css("display","block");
		cam = $(this).attr("cam");

		$.ajax({
			url:"_galeria/editor.php?galeria_opc_dir="+cam,
			success: function(dados){
				Galeria.setContent(dados);
				$("div[carregando_<?=$Script?>]").css("display","none");
			}
		});	
	});


	$("button[CriarNovoDir]").click(function(){
		$("div[carregando_<?=$Script?>]").css("display","block");
		novo = $("input[NovoNomeDir]").val();
		if(novo){
			$.ajax({
				url:"_galeria/editor.php?novo_dir="+novo,
				success: function(dados){
					Galeria.setContent(dados);
					
					$("div[carregando_<?=$Script?>]").css("display","none");
				}
			});	
		}else{
			alert('insira um nome para o novo diretório!');
		}

	});	


	$(".arquivo_excel").change(function(){
		if($(".arquivo_excel").val()){
			$("div[carregando_<?=$Script?>]").css("display","block");			
			$(".UploadExcel").submit();	
		}

	});	

	$("span[formato]").click(function(){
		opc = $(this).attr("opc");
		$("div[carregando_<?=$Script?>]").css("display","block");
		$.ajax({
			url:"_galeria/editor.php?formato="+opc,
			success: function(dados){
				Galeria.setContent(dados);
				$("div[carregando_<?=$Script?>]").css("display","none");
			}
		});	 


	});


//});


function recarregar_galeria(){
	$("div[carregando_<?=$Script?>]").css("display","block");
	$.ajax({
		url:"_galeria/editor.php",
		success: function(dados){
			Galeria.setContent(dados);
			$("div[carregando_<?=$Script?>]").css("display","none");
		}
	});		

}


function erro(){
	$("div[carregando_<?=$Script?>]").css("display","block");
	$.alert({
		title: false,
		content: 'Ocorreu um erro, este arquivo não pode ser enviado!',
		animation: 'top',
		columnClass:'col-md-6 col-md-offset-3',
		closeAnimation: 'top',
		confirmButton: 'OK',   
		confirmButtonClass: 'btn-success',
		confirm: function(){
			$("div[carregando_<?=$Script?>]").css("display","none");
			this.close();
		}	
								
	});	
}

</script> 