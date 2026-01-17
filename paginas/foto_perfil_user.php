<div id="RecarregarPerfil">
<?php
include("../includes/connect.php");	
include("../includes/funcoes.php");	

$perguntaUser = "select id, nome, foto_perfil from login_acesso where id = '".$_SESSION[id_user]."' ";
$resultadoUser = mysqli_query($conexao2, $perguntaUser);

$d = mysqli_fetch_object($resultadoUser);

$nome = explode(" ",$d->nome);
$nome = ($nome[0]) ;

$pasta = acentos($nome) ;
$pasta = $d->id."_".strtolower($pasta) ;



if(isset($_POST["alterarFoto"])){

mkdir('../img/sistema/user/'.$pasta.'/', 0777, true);
mkdir('../img/sistema/user/'.$pasta.'/perfil/', 0777, true);

$trat1 = RetiraEspaco($_FILES['foto_perfil']['name']) ;
$foto_perfil = acentos($trat1) ;	
 
	

	if($_FILES['foto_perfil']['name']){

		$uploaddir = '../img/sistema/user/'.$pasta.'/perfil/';
		
		$_POST[foto_perfil] = $foto_perfil;
		move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $uploaddir.$_POST[foto_perfil]);                                              
		//if($_POST[foto_antiga]){ unlink($uploaddir.$_POST[foto_antiga]); } //para remover foto antiga
		
		$query = "update login_acesso set  
		foto_perfil = '".$foto_perfil."'
		where id='".$_SESSION[id_user]."'
		";
		$result = mysqli_query($conexao2,$query);

		if($result){
		 $dirPerfil = 'img/sistema/user/'.$pasta.'/perfil/'.$foto_perfil;
		 echo "<script>parent.AtualizarPerfil('".$dirPerfil."');</script>";				
		}


	}else{
		
		$query = "update login_acesso set  
		foto_perfil = '".$_POST[foto_antiga]."'
		where id='".$_SESSION[id_user]."'
		";
		$result = mysqli_query($conexao2,$query);		
		
		$uploaddir = './img/sistema/user/'.$pasta.'/perfil/';
		$atUrl = $uploaddir.$_POST[foto_antiga] ;
		echo "<script>parent.AtualizarPerfil('".$atUrl."');</script>";
		
	}	
	
	
	
}


if($_GET[ExcluirFoto]){
	unlink("../".$_GET[ExcluirFoto]);
	echo "<script>parent.ExcluirFoto('".$_GET[ExcluirFoto]."');</script>";	
}


?>

<style>
	
	.imagemPerfil{
		cursor:pointer;
	}	
	
	.imagemPerfil:hover .ExcluirFperfil{
		display: block;
	}
	
	.ExcluirFperfil{
		display:none;
		font-size:27px;
		position:absolute;
		margin-top:-20px;
		cursor:pointer;
	}
	.ExcluirFperfil:hover{
		color:#FF0004;
	}	
	

</style>

		
<form action="./paginas/foto_perfil_user.php" method="post" target="pagina" enctype="multipart/form-data" >


		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" s>
			
			<div class="fileupload fileupload-new" data-provides="fileupload">
				<div class="fileupload-new thumbnail" style="left:0; width: 200px; height: 150px;">
					<img MinhaFoto src="<?=(($d->foto_perfil)?'img/sistema/user/'.$pasta.'/perfil/'.$d->foto_perfil:'assets/img/demoUpload.jpg')?>" alt="<?=$d->foto_perfil?>" />
				</div>
				<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
				<div>
					<span class="btn btn-file btn-primary btn-sm">
						<span class="fileupload-new">Selecione</span>
						<span class="fileupload-exists">Mudar</span>
						<input type="file" name="foto_perfil" />
					</span>
					<a href="#" class="btn btn-danger fileupload-exists btn-sm" data-dismiss="fileupload">Remover </a>
				</div>
			</div>

			
		<input type="hidden" name="foto_antiga" id="foto_antiga" value="<?=(($d->foto_perfil)?$d->foto_perfil:false)?>">
			
		</div>
	
	
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<hr style="border: #939393 solid 1px;">
	    </div>

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<!--<button type="button" id="FecharPerfil" class="btn btn-default">Fechar</button>-->
			<button type="submit" class="btn btn-success" name="alterarFoto">Salvar</button>
	    </div>
	
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<hr style="border: #939393 solid 1px;">
	    </div>	
	
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<br>
	    </div>	

	    
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="row">
			<?php
			$dir0 = dirname(dirname(__FILE__));

			$dir = $dir0."/img/sistema/user/".$pasta."/perfil/";

			if (is_dir($dir)){
			  if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
				  if( $file!='.' and $file!='..' ){	
				  ?>
				  <div class="col-md-3 col-lg-3 imagemPerfil" style="text-align:center">
					<span FotoPerfil caminho="img/sistema/user/<?=$pasta?>/perfil/<?=$file?>" >
						<i class="fas fa-user-times ExcluirFperfil"></i>
					</span>  
					<img EditarPerfil caminho="img/sistema/user/<?=$pasta?>/perfil/<?=$file?>" NomeImg="<?=$file?>" class="FotoVez" src="img/sistema/user/<?=$pasta?>/perfil/<?=$file?>" alt="<?=$file?>" style="width:70%;" />
				  </div>
				  <?php
				  }
				}
				closedir($dh);
			  }
			}
			?>
			</div>
		</div>

	
</form>

	
<script language="javascript">

	
$("img[EditarPerfil]").click(function(){
	
	var caminho = $(this).attr("caminho");
	var NomeImg = $(this).attr("NomeImg");

	$(".fileupload-new .thumbnail img[MinhaFoto]")
		.attr('src', caminho);
	
	$("#foto_antiga").val(NomeImg);

});
	
$("span[FotoPerfil]").click(function(){
	
	var caminho = $(this).attr("caminho");

	
	$.confirm({
		title: '<span style="color:red">EXCLUIR!</span>',
		content: "Tem certeza excluir esta imagem?",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Sim: {
				btnClass: "btn-danger btn-sm",
				action: function(){
					$.ajax({
						url: "./paginas/foto_perfil_user.php?ExcluirFoto="+caminho,
						success: function(data) {
						$('#RecarregarPerfil').html(data);		  
						}
					});						
				}
			},
			'Não': {
				btnClass: "btn-success btn-sm",
				action: function(){
				}
			},
		}				
	  });	


	
});	
	
	
function AtualizarPerfil(urlPerfil){

	$(".RetornaPerfil")
		.attr('src', urlPerfil)
	
	Perfil.close();

}

function ExcluirFoto(c){
	
	$.ajax({
		url: "./paginas/foto_perfil_user.php",
		success: function(data) {
		$('#RecarregarPerfil').html(data);		  
		}
	});	
	

}	
	
</script>
	
</div>