<div id="SalvandoNf" >
<?php
include("../../includes/connect.php");	
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/clientes/clientes';
$Script = md5($Conf[script]);

if(!$_SESSION[$Script][CodNf]){$_SESSION[$Script][CodNf] = $_GET[CodNf];}
if($_GET[CodNf]){$_SESSION[$Script][CodNf] = $_GET[CodNf];}

list($cliente) = mysqli_fetch_row(mysqli_query($conexao2, "select nome from clientes where id = '".$_SESSION[$Script][CodNf]."'  "));

list($descricaoServico,$Valor) = mysqli_fetch_row(mysqli_query($conexao2, "select descricao,valor from servicos where id_cliente = '".$_SESSION[$Script][CodNf]."'  "));


$pergunta = "select * from config_nf where id_cliente = '".$_SESSION[$Script][CodNf]."' " ;
$resultado = mysqli_query($conexao2, $pergunta);
$d = mysqli_fetch_object($resultado);	
	
	
if($_POST){
	
	$municipal = explode(" - ",$_POST[municipalServiceCode]) ;
	
	$id_cliente = $_SESSION[$Script][CodNf];
	$municipalServiceCode = trim($municipal[0]) ;
	$municipalServiceName = ($municipal[1]); 
	$installment = "null";
	$serviceDescription = ($_POST[serviceDescription]);
	$observations = ($_POST[serviceDescription]);
	$deductions = $_POST[deductions];
	$retainIss = $_POST[retainIss];
	$iss = $_POST[iss];
	$cofins = $_POST[cofins];
	$csll = $_POST[csll];
	$inss = $_POST[inss];
	$ir = $_POST[ir];
	$pis = $_POST[pis];
	
	if($_POST[idConfig]){
		
		mysqli_query($conexao2, "
		update config_nf set
		id_cliente = '".$id_cliente."',
		installment = '".$installment."',
		serviceDescription = '".$serviceDescription."',
		observations = '".$serviceDescription."',
		deductions = '".$deductions."',
		retainIss = '".$retainIss."',
		iss = '".$iss."',
		cofins = '".$cofins."',
		csll = '".$csll."',
		inss = '".$inss."',
		ir = '".$ir."',
		pis = '".$pis."',
		municipalServiceCode = '".$municipalServiceCode."',
		municipalServiceName = '".$municipalServiceName."'
		where id = '".$_POST[idConfig]."'
		");
		
	}else{
		
		mysqli_query($conexao2, "
		insert into config_nf set
		id_cliente = '".$id_cliente."',
		installment = '".$installment."',
		serviceDescription = '".$serviceDescription."',
		observations = '".$serviceDescription."',
		deductions = '".$deductions."',
		retainIss = '".$retainIss."',
		iss = '".$iss."',
		cofins = '".$cofins."',
		csll = '".$csll."',
		inss = '".$inss."',
		ir = '".$ir."',
		pis = '".$pis."',
		municipalServiceCode = '".$municipalServiceCode."',
		municipalServiceName = '".$municipalServiceName."'
		");	
		
	}
	
	//echo "<script>alert(' $municipal[0] e $municipal[1] ');</script>";
	
	
	echo "<script>parent.retornar_$Script()</script>";
	exit();	
	
}	
	
?>

<style>
	.btn-success{
		padding:6px !important;
		font-size:12px !important;
	}
	.btn-danger{
		padding:5px !important;
		font-size:11px !important;
	}
	.MenusAoPassar{
		color:#000000 !important;
	}
	.DescServico{
		color:#EC0D10;
		font-size:11px;
	}
	
	.Porcento{
		position: absolute; 
		margin-top:5px !important;
		margin-left:100px;
		font-size:17px;	
		color:#000;
	}
	.Moeda{
		position: absolute; 
		margin-top:5px !important;
		margin-left:3px;
		font-size:17px;	
		color:#000;
	}

	.InputNf{
		width:120px !important;
		height:30px !important;
		border:#3F3F3F solid 1px !important;
	}
	.InputNf2{
		width:120px !important;
		height:30px !important;
		padding-left:24px;
		border:#3F3F3F solid 1px !important;
	}
	
	
	
</style>



<div>Informações da nota fiscal de serviço </div>
<div>Informações da cobrança</div>
<div>Cliente: <b><?=($cliente)?></b></div>
<div>Descrição do serviço: <b><?=($descricao)?></b></div>
<div>Valor da cobrança: R$ <b><?=number_format($Valor,2,",",".")?></b></div>
<hr>

<div>Alíquota do Serviço</div>

	
<form class="pt-3" id="formulario_nf" > 
	
	
<div class="row">

	
	
<div class="col-lg-12 col-md-12">
	<span class="TituloForms">Serviço:</span> 

	<select MeuServico class="selectpicker" data-width="100%" title="Selecione um serviço" data-live-search="true" name="municipalServiceCode" id="MeuServico" >
	 <option class="MenusAoPassar" data-icon="fas fa-university" data-subtext="" value="">Selecione um serviço</option>
	  <?php
		$query = "select * from nota_fiacal_municipal order by numero desc";
		$result = mysqli_query($conexao, $query);
		while($b = mysqli_fetch_object($result)){
			
		$descricao = substr( acentos( ($b->descricao) ) , 0, 40);	
	  ?>
	  <option class="MenusAoPassar" data-icon="" data-subtext="<?=$b->numero?>" value="<?=$b->numero." - ".($b->descricao)?>" <?=(($d->municipalServiceCode==$b->numero)?'selected':false)?> ><?=$descricao?></option>
	  <?php
		}
	  ?>
	</select>				
	
</div>

<input type="hidden" name="serviceDescription" value="<?=($descricaoServico)?>" >	

<div class="col-lg-12 col-md-12 DescServico"><?=$d->municipalServiceCode?> - <?=($d->municipalServiceName)?></div>	
	

<div class="col-lg-3 col-md-3">
	
 <span class="TituloForms">Alíquota ISS:</span><br>
  <span class="Porcento">%</span>	
  <p><input type="text" name="iss" id="iss" class="InputNf" placeholder="ISS" value="<?=($d->iss)?>" /></p>
	
</div>	
	

<div Descontos class="col-xs-12 col-sm-12 col-md-12 col-md-12" style="margin-top:8px; display:none"> 	
<span>Valor líquido:</span>
R$ <b><span descontos></span></b> (impostos descontados)
</div>	

<hr>	
	
<div class="col-lg-12 col-md-12">Impostos retidos desta cobrança</div>		
<div class="col-lg-12 col-md-12">Valor da nota R$ <b><?=number_format($Valor,2,",",".")?></b></div><div class="col-lg-12 col-md-12" style="margin-top:5px;">Impostos retidos desta cobrança</div>		
	

	
<div class="col-xs-12 col-sm-12 col-md-12 col-md-12"> 
	<span class="TituloForms">Valor COFINS:</span><br>
	<span class="Porcento">%</span>	
	<input type="text" name="cofins" id="cofins" class="InputNf" placeholder="COFINS" value="<?=($d->cofins)?>" />
	<span class="Moeda">R$</span>	
	<input type="text" name="Valor_cofins" id="Valor_cofins" class="InputNf2" placeholder="00.00" value="<?=($d->Valor_cofins)?>" readonly />
</div> 
	
<div class="col-xs-12 col-sm-12 col-md-12 col-md-12"> 	
	<span class="TituloForms">Valor CSLL:</span><br>
	<span class="Porcento">%</span>	
	<input type="text" name="csll" id="csll" class="InputNf" placeholder="CSLL" value="<?=($d->csll)?>" />
	<span class="Moeda">R$</span>	
	<input type="text" name="Valor_csll" id="Valor_csll" class="InputNf2" placeholder="00.00" value="<?=($d->Valor_csll)?>" readonly />  
</div> 
	
<div class="col-xs-12 col-sm-12 col-md-12 col-md-12"> 	
	<span class="TituloForms">Valor INSS:</span><br>
	<span class="Porcento">%</span>	
	<input type="text" name="inss" id="inss" class="InputNf" placeholder="INSS" value="<?=($d->inss)?>" />
	<span class="Moeda">R$</span>	
	<input type="text" name="Valor_inss" id="Valor_inss" class="InputNf2" placeholder="00.00" value="<?=($d->Valor_inss)?>" readonly />  
</div>  
	
<div class="col-xs-12 col-sm-12 col-md-12 col-md-12" > 	
	<span class="TituloForms">Valor IR:</span><br>
	<span class="Porcento">%</span>	
	<input type="text" name="ir" id="ir" class="InputNf" placeholder="IR" value="<?=($d->ir)?>" />
	<span class="Moeda">R$</span>	
	<input type="text" name="Valor_ir" id="Valor_ir" class="InputNf2" placeholder="00.00" value="<?=($d->Valor_ir)?>" readonly />  
</div>  
	
<div class="col-xs-12 col-sm-12 col-md-12 col-md-12"> 	
	<span class="TituloForms">Valor PIS:</span><br>
	<span class="Porcento">%</span>	
	<input type="text" name="pis" id="pis" class="InputNf" placeholder="PIS" value="<?=($d->pis)?>" />
	<span class="Moeda">R$</span>	
	<input type="text" name="Valor_pis" id="Valor_pis" class="InputNf2" placeholder="00.00" value="<?=($d->Valor_pis)?>" readonly />  
</div> 

<div class="col-lg-12 col-md-12" style="margin-top:8px;">Outras Deduções</div>	
	
<div class="col-xs-12 col-sm-12 col-md-12 col-md-12"> 	
	<span class="TituloForms">Deduções:</span><br>
	<input type="text" name="deductions" id="deductions" class="InputNf" placeholder="Deduções" value="<?=($d->deductions)?>" />
</div>

<div class="col-lg-12 col-md-12" style="margin-top:8px;">Retenção do ISS</div>	
	
<div class="col-xs-12 col-sm-12 col-md-12 col-md-12"> 	
	<span class="TituloForms">Cliente deve reter ISS?</span><br>
	
	<select class="InputNf" name="retainIss" id="retainIss" >

	  <option value="false" <?=(($d->retainIss=='false')?'selected':false)?> >Não</option>
	  <option value="true" <?=(($d->retainIss=='true')?'selected':false)?> >Sim</option>

	</select>
	
</div> 	
	
	
<input type="hidden" name="ValoNota" id="ValoNota" value="<?=$Valor?>" />
	
	
<input type="hidden" name="idConfig" value="<?=$d->id?>" />
	
	
	
	
<div class="col-xs-12 col-sm-12 col-md-12 col-md-12" style="margin-top:8px;"> 	
	<button type="submit" class="btn btn-success">Salvar Configurações NF</button>
</div> 	

	
	
</div>
	
</form>	

		
	
<script>
$('select[MeuServico]').selectpicker();

$('#MeuServico').change(function(){
	
	let Descricao = $(this).val();

	$(".DescServico").text(Descricao);
	
});
	
	
$("#cofins").blur(function(){
	
	var valor  = $("#ValoNota").val();
	var cofins = $(this).val();

	var CofinsCal = ( parseFloat(valor) * parseFloat(cofins) / 100 )  ;
	var ValorCofins = ((CofinsCal)?$("#Valor_cofins").val(CofinsCal):$("#Valor_cofins").val(''));
	
	var Valor_cofins = $("#Valor_cofins").val();
	var Valor_csll   = $("#Valor_csll").val();	
	var Valor_inss   = $("#Valor_inss").val();	
	var Valor_ir     = $("#Valor_ir").val();	
	var Valor_pis    = $("#Valor_pis").val();

	var SomaVal = ((Valor_cofins)?parseFloat(Valor_cofins):0)  + ((Valor_csll)?parseFloat(Valor_csll):0) + ((Valor_inss)?parseFloat(Valor_inss):0) + ((Valor_ir)?parseFloat(Valor_ir):0) + ((Valor_pis)?parseFloat(Valor_pis):0) ;
	
	//alert( "valor:"+parseFloat(valor) +"  CofinsCal:"+ CofinsCal + " SomaVal :"+SomaVal + " Valor_csll  :"+Valor_csll  );

	var Descontos = ( parseFloat(valor) - SomaVal  );
	
	$("span[descontos]").text(Descontos);

	if(SomaVal){
		$("div[Descontos]").css("display","block");
		$("span[descontos]").text(Descontos);	   
	}else{
	   $("div[Descontos]").css("display","none");
	}

});
	
	
	
$("#csll").keyup(function(){
	
	var valor  = $("#ValoNota").val();
	var csll   = $(this).val();	

	var Csll = ( parseFloat(valor) * parseFloat(csll) / 100 )  ;
	var ValorCofins = ((Csll)?$("#Valor_csll").val(Csll):$("#Valor_csll").val(''));

	var Valor_cofins = $("#Valor_cofins").val();
	var Valor_csll   = $("#Valor_csll").val();	
	var Valor_inss   = $("#Valor_inss").val();	
	var Valor_ir     = $("#Valor_ir").val();	
	var Valor_pis    = $("#Valor_pis").val();

	var SomaVal = ((Valor_cofins)?parseFloat(Valor_cofins):0)  + ((Valor_csll)?parseFloat(Valor_csll):0) + ((Valor_inss)?parseFloat(Valor_inss):0) + ((Valor_ir)?parseFloat(Valor_ir):0) + ((Valor_pis)?parseFloat(Valor_pis):0) ;
	
	var Descontos = ( parseFloat(valor) - SomaVal  );
	
	$("span[descontos]").text(Descontos);
	
	if(SomaVal){
		$("div[Descontos]").css("display","block");
		$("span[descontos]").text(Descontos);	   
	}else{
	   $("div[Descontos]").css("display","none");
	}

});	
	
$("#inss").keyup(function(){
	
	var valor  = $("#ValoNota").val();
	var inss   = $(this).val();	

	var Inss = ( parseFloat(valor) * parseFloat(inss) / 100 )  ;
	var ValorCofins = ((Inss)?$("#Valor_inss").val(Inss):$("#Valor_inss").val(''));

	var Valor_cofins = $("#Valor_cofins").val();
	var Valor_csll   = $("#Valor_csll").val();	
	var Valor_inss   = $("#Valor_inss").val();	
	var Valor_ir     = $("#Valor_ir").val();	
	var Valor_pis    = $("#Valor_pis").val();

	var SomaVal = ((Valor_cofins)?parseFloat(Valor_cofins):0)  + ((Valor_csll)?parseFloat(Valor_csll):0) + ((Valor_inss)?parseFloat(Valor_inss):0) + ((Valor_ir)?parseFloat(Valor_ir):0) + ((Valor_pis)?parseFloat(Valor_pis):0) ;
	
	var Descontos = ( parseFloat(valor) - SomaVal  );
	
	$("span[descontos]").text(Descontos);
	
	if(SomaVal){
		$("div[Descontos]").css("display","block");
		$("span[descontos]").text(Descontos);	   
	}else{
	   $("div[Descontos]").css("display","none");
	}

});	

	
$("#ir").keyup(function(){
	
	var valor  = $("#ValoNota").val();
	var ir   = $(this).val();	

	var Ir = ( parseFloat(valor) * parseFloat(ir) / 100 )  ;
	var ValorCofins = ((Ir)?$("#Valor_ir").val(Ir):$("#Valor_ir").val(''));

	var Valor_cofins = $("#Valor_cofins").val();
	var Valor_csll   = $("#Valor_csll").val();	
	var Valor_inss   = $("#Valor_inss").val();	
	var Valor_ir     = $("#Valor_ir").val();	
	var Valor_pis    = $("#Valor_pis").val();

	var SomaVal = ((Valor_cofins)?parseFloat(Valor_cofins):0)  + ((Valor_csll)?parseFloat(Valor_csll):0) + ((Valor_inss)?parseFloat(Valor_inss):0) + ((Valor_ir)?parseFloat(Valor_ir):0) + ((Valor_pis)?parseFloat(Valor_pis):0) ;
	
	var Descontos = ( parseFloat(valor) - SomaVal  );
	
	$("span[descontos]").text(Descontos);
	
	if(SomaVal){
		$("div[Descontos]").css("display","block");
		$("span[descontos]").text(Descontos);	   
	}else{
	   $("div[Descontos]").css("display","none");
	}

});		

	
$("#pis").keyup(function(){
	
	var valor  = $("#ValoNota").val();
	var pis   = $(this).val();	

	var Pis = ( parseFloat(valor) * parseFloat(pis) / 100 )  ;
	var ValorCofins = ((Pis)?$("#Valor_pis").val(Pis):$("#Valor_pis").val(''));

	var Valor_cofins = $("#Valor_cofins").val();
	var Valor_csll   = $("#Valor_csll").val();	
	var Valor_inss   = $("#Valor_inss").val();	
	var Valor_ir     = $("#Valor_ir").val();	
	var Valor_pis    = $("#Valor_pis").val();

	var SomaVal = ((Valor_cofins)?parseFloat(Valor_cofins):0)  + ((Valor_csll)?parseFloat(Valor_csll):0) + ((Valor_inss)?parseFloat(Valor_inss):0) + ((Valor_ir)?parseFloat(Valor_ir):0) + ((Valor_pis)?parseFloat(Valor_pis):0) ;
	
	var Descontos = ( parseFloat(valor) - SomaVal  );
	
	$("span[descontos]").text(Descontos);
	
	if(SomaVal){
		$("div[Descontos]").css("display","block");
		$("span[descontos]").text(Descontos);	   
	}else{
	   $("div[Descontos]").css("display","none");
	}

});	
	
	
$("#iss").mask("99.99");
	
$("#cofins").mask("99.99");	
$("#csll").mask("99.99");	
$("#inss").mask("99.99");	
$("#ir").mask("99.99");	
$("#pis").mask("99.99");
	
	
	
$('#formulario_nf').validate({
	rules : {
		municipalServiceCode : {
			required : true
		},
		iss : {
			required : true
		}

	},
	messages : {
		municipalServiceCode : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o serviço.</div>'
		},
		iss : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o ISS.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

	$.ajax({
		type: "POST",
		url: "./tabelas/clientes/config_nota_fiscal.php",
		data: dados,
		success: function( data )
		{
			$("#SalvandoNf").html(data);

		}
	});

	return false;
}

});	
	

//opção de salvar
function retornar_<?=$Script?>(cod){

	 ConfigNF.close();
	
	 $.confirm({
		title: "",
		content: "<b>Dados salvo com sucesso!</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){

					$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
						$.ajax({
						url: './<?=$Conf[script]?>.php',
						success: function(data) {
						$('#<?=$_SESSION[$Script][dialog]?>').html(data);
							$("#CARREGANDO").html('');
							$('body>.tooltip').remove();
						}
					}); 					

				}
			},
		}
	  });

}	
	
</script>




</div>