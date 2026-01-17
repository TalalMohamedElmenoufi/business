<?php	
include("../../includes/connect.php");	


if($_POST){
	
	$perfil = implode('|', $_POST['perfil']);

	$query = "UPDATE login_acesso SET 
			 perfil='".$perfil."'
			 WHERE id='$_POST[id]' ";	
	$result = mysqli_query($conexao2,$query);
	
}

$pergunta = "select * from login_acesso where id='".$_GET[id]."' ";
$resultado = mysqli_query($conexao2,$pergunta);
$d = mysqli_fetch_object($resultado);

$Vperfil = explode('|',$d->perfil);



list($listaPerfilDono) = mysqli_fetch_row(mysqli_query($conexao, "select perfil from usuarios where id = '".$_SESSION[id_usuario]."' "));
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
	
	label{
		padding: 0 !important ;
		margin-left:1px !important;
	}	
</style>




<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:0px; padding: 8px; background:#f9f9f9" >

	
<fieldset class="fieldset-border">
  <legend class="legend-border">PERFIL</legend>
	
	<div class="row">	
	<?php
	$partes = explode("|",$listaPerfilDono);

	foreach ($partes as $valores) {

	  $nome = strtoupper($valores);	
	  $nome = str_replace("_"," ",$nome);
	?>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 checkbox checkbox-primary">
			<input type="checkbox" name="perfil[]" id="<?=$valores?>" value="<?=$valores?>" <?=((in_array($valores,$Vperfil))?'checked':false)?> >
			<label for="<?=$valores?>"><?=$nome?></label> 
		</div>

	<?php
	}
	?>
	</div>
</fieldset>

			
	
	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left" style="padding: 0;">
	<button id="Salvar" class="btn-bs-file btn btn-xs btn-success"> SALVAR </button>
</div>	

	
</div>

<div id="ChecarPerfil" style="display:none"></div>


<script>

$('#Salvar').click(function(){

	 var Perfils = new Array();

	 $(':checkbox:checked').each(function(i){
		Perfils.push($(this).val());
	 });

	
	$.ajax({
	type: "POST",
	url: "./tabelas/login_acesso/perfil.php",
	data: {id:'<?=$_GET[id]?>',perfil:Perfils},
		success: function (data){
			$("#ChecarPerfil").html(data);
			Perfil.close();
		}
	});

});
	
</script>


