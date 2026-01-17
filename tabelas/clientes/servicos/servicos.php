<div id="ServicosClientes">
<?php	
include("../../../includes/connect.php");	
include("../../../includes/funcoes.php");

$Conf[script] = 'tabelas/clientes/clientes';
$Script = md5($Conf[script]);	

if($_GET[idCliente]){$_SESSION[$Script][idCliente] = $_GET[idCliente];}
if(!$_SESSION[$Script][idCliente]){$_SESSION[$Script][idCliente] = $_GET[idCliente];}	
	
if(isset($_POST["SalvarServico"])){
	$Tid = explode("|",$_POST[descricao]);
	$Tvalor = str_replace(",",".",$_POST[valor]);
	$TvalorDesc = str_replace(",",".",$_POST[valor_desconto]);
	
	mysqli_query( $conexao2, " insert into servicos set id_cliente='".$_SESSION[$Script][idCliente]."', id_tipo_servico='".$Tid[0]."' , descricao='".($_POST[descricaoServico])."', valor='".$Tvalor."' , valor_desconto='".$TvalorDesc."' , data_adesao='".$_POST[data_adesao]."' " );	
//echo "<script>alert(' $Tid[0] e $Tvalor e $_POST[data_adesao] ');</script>";
	
}
	
if(isset($_POST["AlterarServico"])){
	$Tid = explode("|",$_POST[descricao]);
	$Tvalor = str_replace(",",".",$_POST[valor]);
	$TvalorDesc = str_replace(",",".",$_POST[valor_desconto]);
	
	mysqli_query( $conexao2, " update servicos set id_cliente='".$_SESSION[$Script][idCliente]."', id_tipo_servico='".$Tid[0]."' , descricao='".($_POST[descricaoServico])."', valor='".$Tvalor."' , valor_desconto='".$TvalorDesc."' , data_adesao='".$_POST[data_adesao]."' where id = '".$_POST[CodEd]."' " );	
//echo "<script>alert(' $Tid[0] e $Tvalor e $_POST[data_adesao] ');</script>";
	
}	
	
if($_POST[Servico]){

	if($_POST[Cod]){
		mysqli_query( $conexao2, " update servicos_tipo set descricao='".($_POST[Servico])."' where id = '".$_POST[Cod]."'  " );	
	}else{
		mysqli_query( $conexao2, " insert into servicos_tipo set descricao='".($_POST[Servico])."'  " );			
	}

}	
	
	
$perguntaS = "select * from servicos where id='".$_GET[CodEd]."' ";
$resultadoS = mysqli_query($conexao2, $perguntaS);
$s = mysqli_fetch_object($resultadoS);	
	
if($_GET[idRemov]){
	mysqli_query($conexao2,"delete from servicos where id='".$_GET[idRemov]."' ");
	//echo "<script>parent.VoltarServicos();</script>";
}	
	
?>
	
<style>
	.TituloServico{
		font-size:12px;
	}	
	.AddServico{
		font-size:25px;
		cursor:pointer;
		width:30px;
		float:left;
		border:#9C3A1A solid 0px;
	}
	.AddServico:hover{
		color:#0A4518;
	}	
	.EditServico{
		display:none;
		font-size:25px;
		cursor:pointer;
		width:30px;
		float:left;		
		border:#050C8F solid 0px;
	}	
	.EditServico:hover{
		color:#0A4518;
	}	
	.CancelarS{
		display:none;
		font-size:28px;
		width:30px;
		float:left;		
		cursor:pointer;
		border:#9C3A1A solid 0px;
	}
	.CancelarS:hover{
		color:#CD0003;
	}
	.SalvarST{
		display:none;
		font-size:40px;
		width:40px;
		float:left;			
		cursor:pointer;
		border:#14731C solid 0px;
	}
	.SalvarST:hover{
		color:#0A4518;
	}

	#TSedit{
		display:none;
	}
	
	.LinhaSt{
		margin-top:6px;
	}
	
	.Editar{
		font-size:18px;
		cursor:pointer;
	}
	.Editar:hover{
		color: #23980C;
	}
	
	.Remover{
		font-size:18px;
		cursor:pointer;
	}
	.Remover:hover{
		color: #F40004;
	}
	
</style>
	 
<form id="SalvandoST" action="#" method="post" target="pagina" enctype="multipart/form-data" >		
<div class="row">


	
	<div class="col-md-4 col-lg-4 ">


			<i class="fas fa-plus-circle AddServico"></i>
			<i class="far fa-edit EditServico"></i>		
			<i class="far fa-save SalvarST"></i>
			<i class="far fa-window-close CancelarS"></i>		
		
			<span id="TS">
			<label class="TituloServico">Tipo Serviço: </label>
			<select id="descricao" name="descricao" class="form-control" >
			<option value="" >::Selecione o serviço::</option>
			<?php
			$Tipos = explode(',',$d->descricao);
			$queryc = "select * from servicos_tipo order by descricao ";
			$resultc = mysqli_query($conexao2,$queryc);
			while($c = mysqli_fetch_object($resultc)){	
			?>
			<option value="<?=$c->id.'|'.($c->descricao)?>" <?=(($s->id_tipo_servico==$c->id)?'selected':false)?>  ><?=($c->descricao)?></option>
			<?php
			}
			?> 

			</select>
			</span>
			<span id="TSedit">
			<label class="TituloServico">Novo tipo serviço:</label>
			<input type="text" class="form-control" id="novoTipo" placeholder="Novo tipo serviço" />
			</span>


	</div>
	
	<div class="col-md-4 col-lg-4 ">
		 <span class="TituloForms">Descrição</span><br>
		  <i class="far fa-copyright Icons"></i>
		  <p><input InputForm type="text" name="descricaoServico"  id="descricaoServico" class="form-control" placeholder="Descrição serviço"  value="<?=($s->descricao)?>" /></p>
	</div>
	
	
	<div class="col-md-4 col-lg-4 ">
		 <span class="TituloForms">Valor</span><br>
		  <i class="far fa-copyright Icons"></i>
		  <p><input InputForm type="number" name="valor"  id="valor" class="form-control" placeholder="Valor serviço"  value="<?=($s->valor)?>" /></p>
	</div>
	
	<div class="col-md-4 col-lg-4 ">
		 <span class="TituloForms">Valor desconto</span><br>
		  <i class="far fa-copyright Icons"></i>
		  <p><input InputForm type="number" name="valor_desconto"  id="valor_desconto" class="form-control" placeholder="Valor desconto"  value="<?=($s->valor_desconto)?>" /></p>
	</div>
	
	
	<div class="col-md-4 col-lg-4 ">
		 <span class="TituloForms">Data adicional</span><br>
		  <i class="far fa-copyright Icons"></i>
		  <p><input InputForm type="date" name="data_adesao"  id="data_adesao" class="form-control" placeholder="Data adicional"  value="<?=($s->data_adesao)?>" /></p>
	</div>	
	
	
	
	
	
	<div class="col-md-12 col-lg-12 col-xs-12 col-ms-12 ">	
			<button type="submit"  name="<?=(($_GET[CodEd])?'AlterarServico':'SalvarServico')?>" class="btn btn-success" style="float:left; margin-right:2px;"><?=(($_GET[CodEd])?'Alterar':'Salvar')?></button>

			<button type="button" id="Cancelar" class="btn btn-danger" style=" display:<?=(($_GET[CodEd])?'block':'none')?>">Cancelar</button> 
	</div>
	
	
	
</div>
	
	<input InputForm type="hidden" name="CodEd" value="<?=$_GET[CodEd]?>"  class="form-control" />
	
</form>	
	
<hr class="LinhaSt">

	<div class="table-responsive" style="overflow-x:auto;">
	<table class="table table-striped">
	  <thead>
		<tr>
		  <th scope="col">Id</th>
		  <th scope="col">Tipo serviço</th>
		  <th scope="col">Descrição</th>
		  <th scope="col">Valor</th>
		  <th scope="col">Valor desconto</th>
		  <th scope="col">Data programação</th>
		  <th scope="col">Ação</th>
		</tr>
	  </thead>
		
	  	
	  <tbody>
	  <?php
		$pergunta = "select a.id, a.descricao as DescServ, a.valor, a.valor_desconto, a.data_adesao, b.descricao as DescTp from servicos a
		left join servicos_tipo b on b.id=a.id_tipo_servico
		where a.id_cliente = '".$_SESSION[$Script][idCliente]."' ";
		$resultado = mysqli_query($conexao2,$pergunta);
		while ($d = mysqli_fetch_object($resultado)){
		?>	
		<tr>
		  <th scope="row"><?=($d->id)?></th>
		  <th scope="row"><?=($d->DescTp)?></th>
		  <td><?=($d->DescServ)?></td>
		  <td><?=number_format($d->valor,2,",",".")?></td>
		  <td><?=number_format($d->valor_desconto,2,",",".")?></td>
		  <td><?= (($d->data_adesao!='0000-00-00')?dataBr($d->data_adesao):'Não informado')  ?></td>
		  <td>
			<i class="far fa-edit Editar" Cod="<?=$d->id?>"></i>
			  
			<i class="far fa-window-close Remover" Cod="<?=$d->id?>"></i>  
		  </td>	
		</tr>			
		<?php	
		}			
	   ?>

	  </tbody>
	</table>	
	</div>

	
	
<script>
$(".Editar").click(function(){	
	let Cod = $(this).attr("Cod");
	$.ajax({
		type: "GET",
		url: "./tabelas/clientes/servicos/servicos.php",
		data: {
			CodEd:Cod
		},
		success: function( data )
		{
			$("#ServicosClientes").html(data);

		}
	});
});
$("#Cancelar").click(function(){	
	$.ajax({
		url: "./tabelas/clientes/servicos/servicos.php",
		success: function( data )
		{ $("#ServicosClientes").html(data); }
	});
});	
$(".Remover").click(function(){	
	
	let Cod = $(this).attr("Cod");
	
	 $.confirm({
		title: "<span style='color:red'>Atenção!<span>",
		content: "Deseja realmente <br>Excluir o registro <b>id "+Cod+"</b>?",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Sim: {
				btnClass: "btn-danger",
				action: function(){
					$.ajax({
						url: "./tabelas/clientes/servicos/servicos.php?idRemov="+Cod,
						success: function( data )
						{ $("#ServicosClientes").html(data); }
					});					
				}
			},
			'Não': {
				btnClass: "btn-success",
				action: function(){				
				}
			},
		}				
	  });
	

});	
/*function VoltarServicos(){
	$.ajax({
		url: "./tabelas/clientes/servicos/servicos.php",
		success: function( data )
		{ $("#ServicosClientes").html(data); }
	});		
}*/
		
	
	
$(".AddServico").click(function(){
	$("#TSedit").css("display","block");
	$("#TS").css("display","none");
	$(".AddServico").css("display","none");
	$(".EditServico").css("display","none");
	$(".SalvarST").css("display","block");
	$(".CancelarS").css("display","block");
	$("#novoTipo").focus();
});
	
$(".SalvarST").click(function(){
	let descricao = $("#descricao").val();
	var result=descricao.split('|');
	
	let Servico = $("#novoTipo").val();

	$.ajax({
		type: "POST",
		url: "./tabelas/clientes/servicos/servicos.php",
		data: {
			Cod:result[0],
			Servico:Servico
		},
		success: function( data )
		{
			$("#ServicosClientes").html(data);

		}
	});	
	
	$("#TSedit").css("display","none");
	$("#TS").css("display","block");
	$(".AddServico").css("display","block");
	$(".SalvarST").css("display","none");
	$(".CancelarS").css("display","none");	
	
});

$(".EditServico").click(function(){
	let descricao = $("#descricao").val();
	
	var result=descricao.split('|');
	
	$("#TSedit").css("display","block");
	$("#TS").css("display","none");
	$(".AddServico").css("display","none");
	$(".EditServico").css("display","none");
	$(".SalvarST").css("display","block");
	$(".CancelarS").css("display","block");
	$("#novoTipo").focus().val(result[1]);	
	
});
$(".CancelarS").click(function(){	
	$("#TSedit").css("display","none");
	$("#TS").css("display","block");
	$(".AddServico").css("display","block");
	$(".SalvarST").css("display","none");
	$(".CancelarS").css("display","none");		
});	
	
$("#descricao").change(function(){
	
	let ServicoDesc = $(this).val();

	
	if(ServicoDesc){
		$(".AddServico").css("display","none");
		$(".EditServico").css("display","block");

		$(".SalvarST").css("display","none");
		$(".CancelarS").css("display","none");
	}else{
		$(".AddServico").css("display","block");
		$(".EditServico").css("display","none");
		$(".SalvarST").css("display","none");	
		$(".CancelarS").css("display","none");	
	}
	
	
});
	

$('#SalvandoST').validate({
	rules : {
		descricao : {
			required : true
		},
		descricaoServico : {
			required : true
		},
		valor : {
			required : true
		},
		valor_desconto : {
			required : true
		}

	},
	messages : {
		descricao : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o serviço.</div>'
		},
		descricaoServico : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a descrição do serviço.</div>'
		},
		valor : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o valor.</div>'
		},
		valor_desconto : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o valor desconto.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

	$.ajax({
		type: "POST",
		url: "./tabelas/clientes/servicos/servicos.php",
		data: dados,
		success: function( data )
		{
			$("#ServicosClientes").html(data);

		}
	});

	return false;
}

});	
	
</script>

</div>
