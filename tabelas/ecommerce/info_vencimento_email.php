<div id="AcessarMeusPlanosEmail">
<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');
	
$Conf[script] = 'tabelas/ecommerce/info_vencimento_email';
$Script = md5($Conf[script]);
	
if(!$_SESSION[$Script][Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}
if($_GET[Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}
	
if(!$_SESSION[$Script][ValorPlano]){$_SESSION[$Script][ValorPlano] = $_GET[ValorPlano];}
if($_GET[ValorPlano]){$_SESSION[$Script][ValorPlano] = $_GET[ValorPlano];}	
	
if(!$_SESSION[$Script][ValorPlanoSend]){$_SESSION[$Script][ValorPlanoSend] = $_GET[ValorPlanoSend];}
if($_GET[ValorPlanoSend]){$_SESSION[$Script][ValorPlanoSend] = $_GET[ValorPlanoSend];}
	
if(!$_SESSION[$Script][ValorEmail]){$_SESSION[$Script][ValorEmail] = $_GET[ValorEmail];}
if($_GET[ValorEmail]){$_SESSION[$Script][ValorEmail] = $_GET[ValorEmail];}
	
if(!$_SESSION[$Script][QuantidadePlano]){$_SESSION[$Script][QuantidadePlano] = $_GET[QuantidadePlano];}
if($_GET[QuantidadePlano]){$_SESSION[$Script][QuantidadePlano] = $_GET[QuantidadePlano];}	
	
	
//forma pagamento  BOLETO CREDIT_CARD	
	
list($api_token) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id = '1' "));
	
$peguntaCartao = "select * from cartao_credito where id_usuario = '".$_SESSION[id_usuario]."' ";
$resultadoCartao = mysqli_query($conexao,$peguntaCartao);	
while($c = mysqli_fetch_object($resultadoCartao)){	
	$numberCartao[] = $c->number;	
}	
	
list($id_asaas, $nome, $email, $cpf_cnpj, $cep, $numero, $celular, $endereco ) = mysqli_fetch_row(mysqli_query($conexao, "select id_asaas,nome,email,cpf_cnpj,cep,numero,celular,endereco from usuarios where id = '".$_SESSION[id_usuario]."' "));	
	
if($_POST){

$codPlano = $_SESSION[$Script][Cod];	
$billingType = $_POST[pagamento];
$dueDate = dataMysql($_POST[data_vencimento]); //Data de vencimento da cobrança
$value = $_POST[ValorPlano]; //Valor da cobrança
$description = 'Credito E-mail'; //Descrição da cobrança
$externalReference = 'x'; //Campo livre para busca
$discountValue = 0; //Valor percentual ou fixo de desconto a ser aplicado sobre o valor da cobrança
$dueDateLimitDays = 0; //Dias antes do vencimento para aplicar desconto. Ex: 0 = até o vencimento, 1 = até um dia antes, 2 = até dois dias antes, e assim por diante
$fineValue = 0; //Percentual de multa sobre o valor da cobrança para pagamento após o vencimento
$interestValue = 0; //Percentual de juros ao mês sobre o valor da cobrança para pagamento após o vencimento	

if($billingType=='BOLETO'){
	GerarFatura($conexao2,$api_token,$codPlano,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue);
}	
	
if($billingType=='CREDIT_CARD'){

	
	$holderName = $_POST[holderName];
	$number = $_POST[number]; 
	$expiryMonth = $_POST[expiryMonth]; 
	$expiryYear = $_POST[expiryYear]; 
	$ccv = $_POST[ccv];		
	
	
	$cepTrat = str_replace("-","",$cep);
	
	$cpfCnpj1 = str_replace(".","",$cpf_cnpj);
	$cpfCnpj2 = str_replace("/","",$cpfCnpj1);
	$cpfCnpj = str_replace("-","",$cpfCnpj2);
	
	$celular1 = str_replace(" ","",$celular);
	$celTrat = str_replace("-","",$celular1);

	
	if(!in_array($number,$numberCartao)){
		mysqli_query($conexao, " insert into cartao_credito set
		id_usuario='".$_SESSION[id_usuario]."',
		holderName='".$holderName."',
		number='".$number."',
		expiryMonth='".$expiryMonth."',
		expiryYear='".$expiryYear."',
		ccv='".$ccv."'
		");
	}
	
	//echo "<script> alert(' codPlano:$codPlano e billingType:$billingType e dueDate:$dueDate e value:$value description:$description e holderName:$holderName e number:$number e expiryMonth:$expiryMonth e expiryYear:$expiryYear e ccv:$ccv e nome:$nome e email:$email e cpf_cnpj:$cpfCnpj e cep:$cepTrat e  numero:$numero e celular:$celTrat  '); </script>";
	GerarCartao($conexao,$conexao2,$api_token,$billingType,$dueDate,$value,$description,$holderName,$number,$expiryMonth,$expiryYear,$ccv,$nome,$email,$cpfCnpj,$cepTrat,$numero,$celTrat,$_SESSION[id_usuario]);
}


}	


	
function GerarCartao($conexao,$conexao2,$api_token,$billingType,$dueDate,$value,$description,$holderName,$number,$expiryMonth,$expiryYear,$ccv,$nome,$email,$cpfCnpj,$cepTrat,$numero,$celTrat,$id_usuario){

	
	$fields = array
	(
		'customer' => $id_asaas,
		'billingType' => $billingType,
		'dueDate' => $dueDate,
		'value' => $value,
		'description' => $description,
		'externalReference' => $externalReference,
		'creditCard' =>
			array
			(
			'holderName' => $holderName,
			'number' => $number,
			'expiryMonth' => $expiryMonth,
			'expiryYear' => $expiryYear,
			'ccv' => $ccv,	
			),
		'creditCardHolderInfo' =>
			array
			(
			'name' => $nome,
			'email' => $email,
			'cpfCnpj' => $cpfCnpj,
			'postalCode' => $cepTrat,
			'addressNumber' => $numero,
			'addressComplement' => $endereco,	
			'phone' => $celTrat,	
			'mobilePhone' => $celTrat,	
			),

	);	

$ch = curl_init();

$obj = json_decode($response);	
	
curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: ".$api_token
));

$response = curl_exec($ch);
curl_close($ch);

//var_dump($response);	
	$insertc = " insert into log_cartao set
				id_usuario = '".$id_usuario."',
				log = '".$response."'
	";
	mysqli_query($conexao,$insertc);	
	
	
	$confirmedDate = $obj->confirmedDate ;
	$postalService = $obj->postalService ;
	foreach ( $obj->creditCard as $c ){
		$creditCardNumber = $c->creditCardNumber ;
		$creditCardBrand = $c->creditCardBrand;
		$creditCardToken = $c->creditCardToken;
	}	
	
	$insert = " insert into asaas_cobranca_email set
				object = '".$obj->object."',
				id = '".$obj->id."',
				dateCreated = '".$obj->dateCreated."',
				customer = '".$obj->customer."',
				value = '".$obj->value."',
				netValue = '".$obj->netValue."',
				originalValue = '".$obj->originalValue."',
				interestValue = '".$obj->interestValue."',
				quantidade_plano = '".$_POST[QuantidadePlano]."',
				description = '".$obj->description."',
				billingType = '".$obj->billingType."',
				status = '".$obj->status."',
				dueDate = '".$obj->dueDate."',
				originalDueDate = '".$obj->originalDueDate."',
				paymentDate = '".$obj->paymentDate."',
				clientPaymentDate = '".$obj->clientPaymentDate."',
				invoiceUrl = '".$obj->invoiceUrl."',
				invoiceNumber = '".$obj->invoiceNumber."',
				externalReference = '".$obj->externalReference."',
				deleted = '".$obj->deleted."',
				anticipated = '".$obj->anticipated."',
				creditDate = '".$obj->creditDate."',
				estimatedCreditDate = '".$obj->estimatedCreditDate."',
				bankSlipUrl = '".$obj->bankSlipUrl."',
				lastInvoiceViewedDate = '".$obj->lastInvoiceViewedDate."',
				confirmedDate = '".$confirmedDate."',
				postalService = '".$postalService."',
				creditCardNumber = '".$creditCardNumber."',
				creditCardBrand = '".$creditCardBrand."',
				creditCardToken = '".$creditCardToken."'

				
	";
	mysqli_query($conexao2,$insert);	

	

	
	echo "<script>parent.IrMeusPlanos();</script>";
	
}	
	
	
	
	
	
	

$DataVencimento = date('d/m/Y', strtotime("+3 days"));	

	
function GerarFatura($conexao2,$api_token,$codPlano,$id_asaas,$billingType,$dueDate,$value,$description,$externalReference,$discountValue,$dueDateLimitDays,$fineValue,$interestValue){
	
	
	$fields = array
	(
		'customer' => $id_asaas,
		'billingType' => $billingType,
		'dueDate' => $dueDate,
		'value' => $value,
		'description' => $description,
		'externalReference' => $externalReference,
		'discount' =>
			array
			(
			'value' => $discountValue,
			'dueDateLimitDays' => $dueDateLimitDays,	
			),
		'fine' =>
			array
			(
			'value' => $fineValue,	
			),
		'interest' =>
			array
			(
			'value' => $interestValue,	
			),
		'postalService' => false,
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);	
	
	//var_dump($response);
	$obj = json_decode($response);
	
	$insert = " insert into asaas_cobranca_email set
				object = '".$obj->object."',
				id = '".$obj->id."',
				dateCreated = '".$obj->dateCreated."',
				customer = '".$obj->customer."',
				value = '".$obj->value."',
				netValue = '".$obj->netValue."',
				originalValue = '".$obj->originalValue."',
				interestValue = '".$obj->interestValue."',
				quantidade_plano = '".$_POST[QuantidadePlano]."',
				description = '".$obj->description."',
				billingType = '".$obj->billingType."',
				status = '".$obj->status."',
				dueDate = '".$obj->dueDate."',
				originalDueDate = '".$obj->originalDueDate."',
				paymentDate = '".$obj->paymentDate."',
				clientPaymentDate = '".$obj->clientPaymentDate."',
				invoiceUrl = '".$obj->invoiceUrl."',
				invoiceNumber = '".$obj->invoiceNumber."',
				externalReference = '".$obj->externalReference."',
				deleted = '".$obj->deleted."',
				anticipated = '".$obj->anticipated."',
				creditDate = '".$obj->creditDate."',
				estimatedCreditDate = '".$obj->estimatedCreditDate."',
				bankSlipUrl = '".$obj->bankSlipUrl."',
				lastInvoiceViewedDate = '".$obj->lastInvoiceViewedDate."',
				lastBankSlipViewedDate = '".$obj->lastBankSlipViewedDate."'
	";
	mysqli_query($conexao2,$insert);
	
	
	echo "<script>parent.IrMeusPlanos('".$codPlano."','".$dueDate."');</script>";
}
	
$porPlano = ($_SESSION[$Script][ValorEmail]) / $_SESSION[$Script][QuantidadePlano] ;		
?>

<style>
	.GeraCobranca{
		margin-top:6px;
		padding:6px;
		width:100%;
	}

	.DadosCard{
		font-size:11px !important;
	}
	.DDCARD{
		margin-top:10px !important;
	}
</style>

<form class="pt-3" id="SolicitandoCredt">
<label>Você acaba de solicitar o pacote <b>Personalizado:</b></label><br>
<label>No valor de R$ <b><?=$_SESSION[$Script][ValorPlano]?></b></label><br>
<label>Pagando R$ <b><?=number_format($porPlano,5,",",".")?>/e-mail</b></label>

<input type="hidden" name="ValorPlano" value="<?=$_SESSION[$Script][ValorPlanoSend]?>" >	
<input type="hidden" name="QuantidadePlano" value="<?=$_SESSION[$Script][QuantidadePlano]?>" >	
	
	<div class="form-check form-check-success">
	<label class="form-check-label">
	<input type="radio" class="form-check-input" name="pagamento" id="BOLETO" value="BOLETO" checked> Boleto <i class="input-helper"></i></label>
	</div>

	
	<div class="form-check form-check-info">
	<label class="form-check-label">
	<input type="radio" class="form-check-input" name="pagamento" id="CREDIT_CARD" value="CREDIT_CARD" > Cartão de credito <i class="input-helper"></i></label>
	</div>	

	
	<div class="form-group InfoBoleto">
	<label class="DadosCard">Data para vencimento:</label>
	<input type="text" id="DataVencimento" name="data_vencimento" placeholder="Data para vencimento" class="form-control" value="<?=$DataVencimento?>" />
	</div>	
	
	
	<div class="form-group InfoCartao" style="display:none">
	<label for="holderName" class="DadosCard">Nome do cartão</label>
	<input type="text" class="form-control" name="holderName" id="holderName" placeholder="Nome do cartão">

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 DDCARD">
		<label for="number" class="DadosCard">Número do cartão</label>	
		<input type="number" class="form-control" name="number" id="number" placeholder="Número">	
		</div>

		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 DDCARD">
		<label for="expiryMonth" class="DadosCard">Mês</label>		
		<input type="number" class="form-control" name="expiryMonth" id="expiryMonth" placeholder="MES">	
		</div>

		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 DDCARD">
		<label for="expiryYear" class="DadosCard">Ano</label>		
		<input type="number" class="form-control" name="expiryYear" id="expiryYear" placeholder="ANO">	
		</div>

		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 DDCARD">
		<label for="ccv" class="DadosCard">CCV</label>	
		<input type="number" class="form-control" name="ccv" id="ccv" placeholder="CCV">
		</div>
	</div>
	</div>




	
	
<div class="form-group">	
<button type="submit" name="SolicitarCredito" class="btn btn-success GeraCobranca">Gerar cobrança</button>  	
</div>	
	
</div>

</form>
	
<script>

$("input[name=pagamento]").click(function(){
	
	let pagamento = $(this).val();
	if(pagamento=="BOLETO"){
	   $(".InfoCartao").css("display","none");
	   $(".InfoBoleto").css("display","block");
	}
	else if(pagamento=="CREDIT_CARD"){
	   $(".InfoBoleto").css("display","none");
	   $(".InfoCartao").css("display","block");			
	}
	
});	
	
	
$('#SolicitandoCredt').validate({
	rules : {
		data_vencimento : {
			required : true,
			minlength: 10
		},
		holderName : {
			required : true
		},
		number : {
			required : true
		},
		expiryMonth : {
			required : true,
			minlength: 2
		},
		expiryYear : {
			required : true,
			minlength: 4
		},
		ccv : {
			required : true,
			minlength: 3
		}
	},
	messages : {
		data_vencimento : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a data vencimento.</div>',
			minlength: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Esta faltando número na data.</div>'
		},
		holderName : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Nome do cartão.</div>',
		},
		number : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Número do cartão.</div>',
		},
		expiryMonth : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o mês.</div>',
			minlength: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Mês invalido.</div>'
		},
		expiryYear : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o ano.</div>',
			minlength: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Ano invalido.</div>'
		},
		ccv : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o ccv.</div>',
			minlength: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> ccv invalido.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

	$.ajax({
		type: "POST",
		url: "./tabelas/ecommerce/info_vencimento_email.php",
		data: dados,
		success: function( data )
		{
			$("#AcessarMeusPlanosEmail").html(data);

		}
	});

	return false;
}

});	

	
function IrMeusPlanos(c,d){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
	
	$.ajax({
		type: "GET",
		url: "./tabelas/minhas_faturas/faturas_email.php",
		data: {
			Cod:c,
			data_vencimento:d
		},
		success: function( data )
		{
		  $('#CONTEUDOS').html(data);
		  $("#CARREGANDO").html('');
		  SolicitandoCredir.close(); 
		}
	});	
	
	
}	
	

$("#DataVencimento").mask("99/99/9999");
$("#expiryMonth").mask("99");	
$("#expiryYear").mask("9999");		
$("#ccv").mask("999");	
	
</script>

</div>