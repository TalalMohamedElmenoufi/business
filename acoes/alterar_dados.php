<div id="AlterarDados">
<?php
include("../includes/connect.php");

	
list($api_token) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id = '1' "));	
	
	
$pergunta = "select * from usuarios where id = '".$_SESSION[id_usuario]."' ";	
$resultado = mysqli_query($conexao,$pergunta);	
$d = mysqli_fetch_object($resultado);	
	
	
if($_POST){

	
mysqli_query($conexao, " update usuarios set
		nome = '".($_POST[nome])."',
		email = '".$_POST[email]."',
		emails = '".$_POST[emails]."',
		celular = '".$_POST[celular]."',
		celulares = '".$_POST[celulares]."',
		tipo_pessoa = '".$_POST[tipo_pessoa]."',
		cpf_cnpj = '".$_POST[cpf_cnpj]."',
		data_nascimento = '".$_POST[data_nascimento]."',
		estado = '".$_POST[estado]."',
		cidade = '".$_POST[cidade]."',
		cep = '".$_POST[cep]."',
		endereco = '".($_POST[endereco])."',
		numero = '".$_POST[numero]."',
		bairro = '".($_POST[bairro])."',
		complemento = '".($_POST[complemento])."'
where id = '".$_SESSION[id_usuario]."' " );
	
list($cidade) = mysqli_fetch_row(mysqli_query($conexao, "select nome from cidades where cod_cidades= '".$_POST[cidade]."'  "));
list($estado) = mysqli_fetch_row(mysqli_query($conexao, "select nome from estados where cod_estados= '".$_POST[estado]."'  "));	
	
$fone = $_POST[celular];
$fone0 = explode(" ",$fone);

$celular = $fone0[1]."".$fone0[2]."".$fone0[3];
$celular0 = str_replace('-','',$celular);	
	
$cpfcnpj0 = $_POST['cpf_cnpj'];
$cpfcnpj1 = str_replace('.','',$cpfcnpj0);
$cpfcnpj2 = str_replace('-','',$cpfcnpj1);
$cpfcnpj3 = str_replace('/','',$cpfcnpj2);	
	
$cep0 = $_POST['cep'];
$cep1 = str_replace('-','',$cep0);	
	
alterarCadastro($d->id_asaas,$api_token ,$_POST[email],$_POST[nome],$_POST[notes],$celular0,$fone0[1],$cpfcnpj3,$_POST[emails],$cep1,$_POST[numero],$_POST[endereco],$cidade,$estado,$_POST[bairro],$_POST[complemento]);	
	
echo "<script> parent.VoltarDados(); </script>";
	

	
}
	

	
/*Inicio asaas*/
function alterarCadastro($id_asaas,$api_token ,$email,$name,$notes,$phone,$phone_prefix,$cpf_cnpj,$cc_emails,$zip_code,$number,$street,$city,$state,$district,$complement){
	
	
	$fields = array
	(
		'name' => ($name),
		'email' => $email,
		'phone' => $phone,
		'mobilePhone' => '',
		'cpfCnpj' => $cpf_cnpj,
		'postalCode' => $zip_code,
		'address' => ($street),
		'addressNumber' => $number,
		'complement' => ($complement),
		'province' => ($district),
		'externalReference' => '',
		'notificationDisabled' => false,
		'additionalEmails' => $cc_emails,
		'municipalInscription' => '',
		'stateInscription' => '',
		'observations' => $notes		
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/customers/$id_asaas");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);

//var_dump($response);	
}
/*Fim asaas*/	
	
?>	
<style>
	.InfoDesc{
		font-size:12px;
	}	
</style>


	<form class="pt-3" id="FormCadastrarAlterar">
		
	 <div class="row">	

		  <div class="form-group col-lg-6 col-md-6">
			<span class="InfoDesc">Nome completo:</span>  
			<input type="text" class="form-control form-control-lg" placeholder="Nome completo" name="nome" value="<?=($d->nome)?>">
		  </div>
		 
		  <div class="form-group col-lg-6 col-md-6">
			<span class="InfoDesc">Seu celular:</span>   
			<input class="form-control form-control-lg" tel type="tel" placeholder="Celular" name="celular" id="celular" value="<?=($d->celular)?>">
		  </div>

		  <div class="form-group col-lg-6 col-md-6">
			<span class="InfoDesc">Celulares:</span>   
			<input class="form-control form-control-lg" tel type="tel" placeholder="5592999999999,5592999999999" name="celulares" id="celulares" value="<?=($d->celulares)?>">
		  </div>		  
		  
		  <div class="form-group col-lg-6 col-md-6">
			<span class="InfoDesc">Seu e-mail:</span>   
			<input type="email" class="form-control form-control-lg" placeholder="Seu E-mail" name="email" value="<?=($d->email)?>">
		  </div>

		 <div class="form-group col-lg-6 col-md-6">
			<span class="InfoDesc">Seu e-mails:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="E-mails separados por virgula" name="emails" value="<?=($d->emails)?>">
		  </div>
		 
			<div class="form-group SelectBarra col-lg-4 col-md-4">
				<span class="InfoDesc">Tipo pessoa:</span> 
				<select InputForm class="form-control input-text-select" id="tipo_pessoa" name="tipo_pessoa">
				<option InputForm value="">Tipo pessoa</option>
				<option InputForm value="F" <?=(($d->tipo_pessoa=='F')?'selected':false)?> >F&iacute;sica</option>
				<option InputForm value="J" <?=(($d->tipo_pessoa=='J')?'selected':false)?>>Jur&iacute;dica</option>	
				</select> 
			</div>	
		 
		<div class="form-group col-lg-6 col-md-6">	
		<span id="MostraCpfCnpj" style="display:<?=(($d->tipo_pessoa)?'block':'none')?>" >
		
			<span class="InfoDesc">CPF/CNPJ:</span>
			<input type="text" class="form-control input-text" id="cpf_cnpj" name="cpf_cnpj" placeholder="CPF/CNPJ" value="<?=($d->cpf_cnpj)?>">
		</span>
		</div>						

		<div class="form-group col-lg-6 col-md-6">
		<span class="InfoDesc">Data de aniversario:</span>   
		<input type="date" class="form-control form-control-lg" placeholder="Data de aniversario" name="data_nascimento" value="<?=($d->data_nascimento)?>">
		</div>
		 
		  <div class="form-group col-lg-6 col-md-6">
			<span class="InfoDesc">Seu CEP:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="Seu CEP" name="cep" id="cep" value="<?=($d->cep)?>">
		  </div>

			<div class="form-group col-lg-6 col-md-6">
				<span class="InfoDesc">Estado:</span>
				<select InputForm class="form-control input-text-select" id="estado" name="estado">
				<option InputForm value="">Procurar por estado</option>	
				<?php
				$query = "select * from estados order by nome";
				$result = mysqli_query($conexao, $query);
				while($dc = mysqli_fetch_object($result)){
				?>
				<option InputForm value="<?=$dc->cod_estados?>" <?=(($dc->cod_estados== $d->estado)?'selected':false)?> ><?=($dc->nome)?></option>
				<?php
				}
				?>
				</select> 
			</div>      


			<div class="form-group col-lg-6 col-md-6">
				<span class="InfoDesc">Cidade:</span> 
				<select class="form-control input-text-select" id="cidade" name="cidade">
				<option value="">Cidades</option>
				<?php
				$query = "select * from cidades where estados_cod_estados='".$d->estado."' order by nome";
				$result = mysqli_query($conexao, $query);
				while($dc = mysqli_fetch_object($result)){
				?>
				<option InputForm value="<?=$dc->cod_cidades?>" <?=(($dc->cod_cidades== $d->cidade)?'selected':false)?> ><?=($dc->nome)?></option>
				<?php
				}
				?>
				</select> 
			</div> 					

		  <div class="form-group col-lg-6 col-md-6">
			<span class="InfoDesc">Endere&ccedil;o:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="Endere&ccedil;o" name="endereco" id="endereco" value="<?=($d->endereco)?>">
		  </div>
		  <div class="form-group col-lg-6 col-md-6">
			<span class="InfoDesc">N&uacute;mero:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="N&uacute;mero" name="numero" value="<?=($d->numero)?>">
		  </div>


		  <div class="form-group">
			<span class="InfoDesc">Bairro:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="Bairro" name="bairro" id="bairro" value="<?=($d->bairro)?>">
		  </div>			
			
		  <div class="form-group col-lg-6 col-md-6">
			<span class="InfoDesc">Complemento:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="Complemento" name="complemento" value="<?=($d->complemento)?>">
		  </div>	

		 
<div class="col-lg-12 col-md-12">	
  <button type="submit" class="btn btn-sm btn-gradient-success ">Atualizar</button>
</div>		 
		  
		 
	</div>
		
</form>
	
<script>
	
let tipoPessoa = "<?=($d->tipo_pessoa)?>";	
	
if(tipoPessoa=='F'){
	$('#cpf_cnpj').mask('000.000.000-00');
	$('#MostraCpfCnpj').css('display','block');
}else if(tipoPessoa=='J'){
	$('#cpf_cnpj').mask('00.000.000/0000-00');
	$('#MostraCpfCnpj').css('display','block');
}
	
	
$('#FormCadastrarAlterar').validate({
	rules : {
		nome : {
			required : true
		},		
		celular : {
			required : true,
			minlength: 17
		},
		data_nascimento : {
			required : true
		},
		estado : {
			required : true
		},
		cidade : {
			required : true
		},
		email: {
			required: true,
			email: true
		},
		
		tipo_pessoa : {
			required : true
		},
		cpf_cnpj : {
			required : true
		},		
		
		cep : {
			required : true
		},
		endereco : {
			required : true
		},
		numero : {
			required : true
		},
		bairro : {
			required : true
		},
		complemento : {
			required : true
		}

	},
	messages : {
		nome : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu nome.</div>'
		},
		celular : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu celular.</div>',
			minlength: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Esta faltando número.</div>',
		},
		data_nascimento : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a data de aniversario.</div>'
		},
		estado : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu estado.</div>'
		},
		cidade : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a sua cidade.</div>'
		},		
		email : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu e-mail.</div>',
			email: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Digite um endereço de e-mail valido.</div>',	
		},
		
		tipo_pessoa : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Pessoa juridica ou fisica?</div>'
		},
		cpf_cnpj : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o cpf/cnpj.</div>'
		},		
		
		cep : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o CEP.</div>'
		},
		endereco : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o endere&ccedil;o.</div>'
		},
		numero : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o numero.</div>'
		},
		bairro : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o bairro.</div>'
		},
		complemento : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o complemento.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();
            
	    $("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
		$.ajax({
			type: "POST",
			url: "./acoes/alterar_dados.php",
			data: dados,
			success: function( data )
			{
				$("#AlterarDados").html(data);

			}
		});		


	return false;
}

});	
	
	
$("#estado").change(function(){
	estado = $(this).val();
	//alert(estado);
	$("#cidade").html('<option>Carregando cidades...</option>');
	$.ajax({
	url:"./acoes/select_cidades.php?estado="+estado,
	success:function(dados){
	$("#cidade").html(dados);
	}
	});
});		
	
$("#tipo_pessoa").change(function(){
	
	var tp = $(this).val();
	
	if(tp=='F'){

		$('#cpf_cnpj').mask('000.000.000-00');
		$('#MostraCpfCnpj').css('display','block');
	}else if(tp=='J'){

		$('#cpf_cnpj').mask('00.000.000/0000-00');
		$('#MostraCpfCnpj').css('display','block');
	}else{
		$('#MostraCpfCnpj').css('display','none');
	}
	
});		
	
	
$("#cep").blur(function(){
	// Remove tudo o que não é número para fazer a pesquisa
	var cep = this.value.replace(/[^0-9]/, "");

	// Validação do CEP; caso o CEP não possua 8 números, então cancela
	// a consulta
	if(cep.length != 8){
		return false;
	}

	// A url de pesquisa consiste no endereço do webservice + o cep que
	// o usuário informou + o tipo de retorno desejado (entre "json",
	// "jsonp", "xml", "piped" ou "querty")
	var url = "https://viacep.com.br/ws/"+cep+"/json/";

	// Faz a pesquisa do CEP, tratando o retorno com try/catch para que
	// caso ocorra algum erro (o cep pode não existir, por exemplo) a
	// usabilidade não seja afetada, assim o usuário pode continuar//
	// preenchendo os campos normalmente
	$.getJSON(url, function(dadosRetorno){
		try{
			// Preenche os campos de acordo com o retorno da pesquisa
			$("#endereco").val(dadosRetorno.logradouro);
			$("#bairro").val(dadosRetorno.bairro);
			//$("#cidade").val(dadosRetorno.localidade);
			//$("#uf").val(dadosRetorno.uf);
		}catch(ex){}
	});
});		
	
	
	
function VoltarDados(id,senha){
	
	$.confirm({
		title: "",
		content: "Dados atualizados com sucesso!",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			ok: {
				btnClass: "btn-success",
				action: function(){
					$("#CARREGANDO").html('');
				}
			},
		}				
	});	
	AlterarDados.close();
	
}	
	
$("#celular").mask("55 99 9 9999-9999");	
$('#cep').mask('00000-000');		
</script>	
	
	
</div>	