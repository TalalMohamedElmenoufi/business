<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/termo/termo';
$Script = md5($Conf[script]);

$pergunta = "select * from termo ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);

if(isset($_POST["SalvarTermo"])){
	
		$query = "replace termo set  
		termo='".$_POST[termo]."',
		id='1'
		";
		$result = mysqli_query($conexao, $query);
	
		echo "<script>parent.retornarTermo()</script>";
		exit();
	
}


?>

<div class="card" >
<div class="card-body">
		
		
		<div class="panel-heading">
			<p>Termo de uso do sistema </p>
		</div>
	
		<div class="dropdown-divider"></div>
	
        <form  action="<?=$Conf[script]?>.php" method="post" target="pagina" enctype="multipart/form-data" >
			
			
		<div class="form-group">
		<label for="termo">Preencha o termo</label>
		<textarea name="termo" class="form-control" id="termo" rows="4"><?=$d->termo?></textarea>
		</div>			
	
	    <button type="submit" id="salvar" name="SalvarTermo" class="btn btn-success">Salvar</button>   	
	
	   </form>
	
</div>	
</div>

<script>
	
var campo = CKEDITOR.replace( 'termo', {
uiColor: '#eeeeee',
height:150,
toolbar:[

['Bold', 'Italic' , 'Underline'],
['JustifyLeft' , 'JustifyCenter' , 'JustifyRight' , 'JustifyBlock'],
[ 'FontSize', 'Font', 'TextColor' ],
[ 'NumberedList' , 'BulletedList'  ],	
[ 'tools', 'Maximize' ],	

	
] 

});	
	
	
//opção de salvar
function retornarTermo(){

	 $.confirm({
		title: "",
		content: "<b>Termo salvo com sucesso!</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){
 					

				}
			},
		}
	  }); 

}		
</script>