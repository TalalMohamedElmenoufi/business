<?php
	
	if($_POST){
		file_put_contents($_POST[local], $_POST[valor]);
		exit();
	}

	$result = @file_get_contents($_GET[file]);
?>
<div class="col-md-12">
    <div class="input-group">
      <input type="text" id="NovoNomeDir" class="form-control" value="<?=$result?>">
      <input type="hidden" id="NomeDirOriginal" value="<?=$result?>">
      <span class="input-group-btn">
        <button id="SalvarNovoNomeDir" class="btn btn-success" type="button">Salvar</button>
      </span>
      <span class="input-group-btn">
        <button id="CanelarNovoNomeDir" class="btn btn-danger" type="button">Cancelar</button>
      </span>
    </div><!-- /input-group -->
</div><!-- /.col-lg-6 -->

<script>
//$(function(){
	
	$("#NovoNomeDir").keyup(function(){
		$('span[<?=md5($_GET[file])?>]').html($("#NovoNomeDir").val());
	});
	
	$("#CanelarNovoNomeDir").click(function(){
		$('span[<?=md5($_GET[file])?>]').html($("#NomeDirOriginal").val());
		$('#NovoNomeDir').val($("#NomeDirOriginal").val());
		EditNameDir.close();
	});
	


	$("#SalvarNovoNomeDir").click(function(){
		var local = '<?=$_GET[file]?>';
		var valor = $('#NovoNomeDir').val();
		$.ajax({
			url:'_galeria/dir.php',
			data:{local:local,valor:valor},
			type:"POST",
			success:function(){
				EditNameDir.close();
			}
		});
	});
//})

</script>