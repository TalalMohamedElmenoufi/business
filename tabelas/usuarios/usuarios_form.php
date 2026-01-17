<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/usuarios/usuarios';
$Script = md5($Conf[script]);

list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));	
	
list($api_token_cliente) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '".$_SESSION[id_usuario]."' and status = 'Liberado' "));	
	
if($api_token_dono==$api_token_cliente){
	$api_token = $api_token_dono ;
}else{
	$api_token = $api_token_cliente ;	
}

list($usuarios) = mysqli_fetch_row(mysqli_query($conexao, "select nome from usuarios where id = '".$_SESSION[id_usuario]."' "));

$pergunta = "select * from ".$_SESSION[$Script][tabela]." where id='".$_GET[cod]."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);

if($_POST){
	
$notes = "Business - $usuarios";
	
if(isset($_POST["alterar_".$Script])){

	$query = "update ".$_SESSION[$Script][tabela]." set  
	nome='".($_POST[nome])."',
	email='".$_POST[email]."',
	emails='".$_POST[emails]."',
	tipo_pessoa='".$_POST[tipo_pessoa]."',
	cpf_cnpj='".$_POST[cpf_cnpj]."',
	celular='".$_POST[celular]."',
	estado='".$_POST[estado]."',
	cidade='".$_POST[cidade]."',
	cep='".$_POST[cep]."',
	endereco='".($_POST[endereco])."',
	numero='".$_POST[numero]."',
	bairro='".($_POST[bairro])."',
	complemento='".($_POST[complemento])."',
	data_nascimento='".$_POST[data_nascimento]."',
	sexo='".$_POST[sexo]."',
	ativo='".$_POST[ativo]."'
	where id='".$_POST[cod]."'
	";
	$result = mysqli_query($conexao, $query);

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
	
	alterarCadastro($_POST[id_asaas],$api_token ,$_POST[email],$_POST[nome],$notes,$celular0,$fone0[1],$cpfcnpj3,$_POST[emails],$cep1,$_POST[numero],$_POST[endereco],$cidade,$estado,$_POST[bairro],$_POST[complemento]);	

	echo "<script>parent.retornar_$Script()</script>";
	exit();
	
}
	
	

if(isset($_POST["salvar_".$Script])){	

	$query = "insert into ".$_SESSION[$Script][tabela]." set 
	nome='".($_POST[nome])."',
	email='".$_POST[email]."',
	emails='".$_POST[emails]."',
	tipo_pessoa='".$_POST[tipo_pessoa]."',
	cpf_cnpj='".$_POST[cpf_cnpj]."',
	celular='".$_POST[celular]."',
	estado='".$_POST[estado]."',
	cidade='".$_POST[cidade]."',
	cep='".$_POST[cep]."',
	endereco='".($_POST[endereco])."',
	numero='".$_POST[numero]."',
	bairro='".($_POST[bairro])."',
	complemento='".($_POST[complemento])."',
	data_nascimento='".$_POST[data_nascimento]."',
	sexo='".$_POST[sexo]."',
	ativo='".$_POST[ativo]."'
	";
	$result = mysqli_query($conexao, $query);
	$codRegister = mysqli_insert_id($conexao);


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
	
    sendCadastroAsaas($conexao,$api_token ,$_POST[email],$_POST[nome],$notes,$celular0,$fone0[1],$cpfcnpj3 ,$_POST[emails],$cep1,$_POST[numero],$_POST[endereco],$cidade,$estado,$_POST[bairro],$_POST[complemento],$codRegister);		
	
	
	echo "<script>parent.retornar_$Script()</script>";
	exit();
}	

	
}

/*Inicio asaas*/
function sendCadastroAsaas($conexao,$api_token ,$email,$name,$notes,$phone,$phone_prefix,$cpf_cnpj,$cc_emails,$zip_code,$number,$street,$city,$state,$district,$complement,$codRegister){
	
	
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
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/customers");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);

	$obj = json_decode($response);
	mysqli_query($conexao," update ".$_SESSION[$Script][tabela]." set id_asaas = '".$obj->id."' where id = '".$codRegister."' ");
	
	 
//var_dump($response);	
}
/*Fim asaas*/	


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



 
<div class="card" >
<div class="card-body">
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>
		
		<form id="Usuarios" action="<?=$Conf[script]?>_form.php" method="post" target="pagina" enctype="multipart/form-data" >

		
		<div class="row">
			
		<div class="form-group col-lg-4 col-md-4">
			 <span class="TituloForms">Nome</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm id="nome_in" type="text" name="nome" class="form-control" placeholder="Nome"  value="<?=($d->nome)?>" /></p>
		</div>		
			
			
		<div class="form-group col-lg-4 col-md-4">
			 <span class="TituloForms">E-mail</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm id="email_in" type="text" name="email" class="form-control" placeholder="E-mail"  value="<?=($d->email)?>" /></p>
		</div>	
			
		<div class="form-group col-lg-4 col-md-4">
			 <span class="TituloForms">E-mails</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm id="emails_in" type="text" name="emails" class="form-control" placeholder="E-mail separados por ,"  value="<?=($d->emails)?>" /></p>
		</div>	
			
		<div class="form-group col-lg-4 col-md-4">
			<span class="TituloForms">Tipo pessoa:</span> 
			<select InputForm class="form-control input-text-select" id="tipo_pessoa_in" name="tipo_pessoa">
			<option InputForm value="">Tipo pessoa</option>
			<option InputForm value="F" <?=(($d->tipo_pessoa=='F')?'selected':false)?> >F&iacute;sica</option>
			<option InputForm value="J" <?=(($d->tipo_pessoa=='J')?'selected':false)?>>Jur&iacute;dica</option>	
			</select>
		</div>
			
		<div class="form-group col-lg-4 col-md-4">
			 <span class="TituloForms">CPF/CNPJ</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm id="cpf_cnpj_in" type="text" name="cpf_cnpj" class="form-control" placeholder="CPF/CNPJ"  value="<?=($d->cpf_cnpj)?>" /></p>
		</div>				

		<div class="form-group col-lg-4 col-md-4">
			 <span class="TituloForms">Celular</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm type="text" name="celular"  id="celular_in" class="form-control" placeholder="Celular"  value="<?=($d->celular)?>" /></p>
		</div>	
			
		<div class="form-group col-lg-4 col-md-4">
			<span class="TituloForms">Estado:</span>
			<select InputForm class="form-control input-text-select" id="estado_in" name="estado">
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


		<div class="form-group col-lg-4 col-md-4">
			<span class="TituloForms">Cidade:</span> 
			<select class="form-control input-text-select" id="cidade_in" name="cidade">
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
			
			
			
		  <div class="form-group  col-lg-4 col-md-4">
			<span class="TituloForms">Seu CEP:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="Seu CEP" name="cep" id="cep_in" value="<?=($d->cep)?>">
		  </div>

		  <div class="form-group  col-lg-4 col-md-4">
			<span class="TituloForms">Endere&ccedil;o:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="Endere&ccedil;o" name="endereco" id="endereco_in" value="<?=($d->endereco)?>">
		  </div>

		  <div class="form-group col-lg-4 col-md-4">
			<span class="TituloForms">N&uacute;mero:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="N&uacute;mero" name="numero" value="<?=($d->numero)?>">
		  </div>

		  <div class="form-group  col-lg-4 col-md-4">
			<span class="TituloForms">Bairro:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="Bairro" name="bairro" id="bairro_in" value="<?=($d->bairro)?>">
		  </div>
		 
		  <div class="form-group col-lg-4 col-md-4">
			<span class="TituloForms">Complemento:</span>   
			<input type="text" class="form-control form-control-lg" placeholder="Complemento" name="complemento" value="<?=($d->complemento)?>">
		  </div>	

		<div class="form-group col-lg-4 col-md-4">
			 <span class="TituloForms">Data nascimento</span><br>
			  <i class="far fa-copyright Icons"></i>
			  <p><input InputForm type="date" name="data_nascimento"  id="data_nascimento" class="form-control" placeholder="Data nascimento"  value="<?=($d->data_nascimento)?>" /></p>
		</div>
			
		<div class="form-group col-lg-4 col-md-4">
			<span class="TituloForms">Sexo</span><br>			
			<div class="form-check form-check-success">

			<label class="form-check-label">
			<input type="radio" class="form-check-input" name="sexo" id="Masculino_in"  value="M" <?=(($d->sexo=='M')?'checked':false)?> > Masculino <i class="input-helper"></i></label>
			</div>				
			<div class="form-check form-check-primary">
			<label class="form-check-label">
			<input type="radio" class="form-check-input" name="sexo" id="Feminino_in" value="F" <?=(($d->sexo=='F')?'checked':false)?> > Feminino <i class="input-helper"></i></label>
			</div>			
		</div>			
              
		<div class="form-group col-lg-4 col-md-4">
			<span class="TituloForms">Ativo</span><br>			
			<div class="form-check form-check-info">
			<label class="form-check-label">
			<input type="radio" class="form-check-input" name="ativo" id="Sim_in"  value="0" <?=(($d->ativo=='0')?'checked':false)?> > SIM <i class="input-helper"></i></label>
			</div>				
			<div class="form-check form-check-danger">
			<label class="form-check-label">
			<input type="radio" class="form-check-input" name="ativo" id="Nao_in" value="1" <?=(($d->ativo=='1')?'checked':false)?> > NÃO <i class="input-helper"></i></label>
			</div>			
		</div>		
			
	    </div>	
			
			
	 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:1px;margin-top:8px;">

			<button type="submit" id="salvar" name="<?=(($_GET[op]=='novo') ? 'salvar_'.$Script : 'alterar_'.$Script)?>" class="btn btn-success"><?=(($_GET[op]=='novo') ? 'Salvar' : 'Alterar')?></button>                  

			<button type="button" id="cancelar_<?=$Script?>" name="cancelar" class="btn btn-danger">Cancelar</button>                   

	 </div>

      <input type="hidden" id="id_asaas<?=$Script?>" name="id_asaas" value="<?=$d->id_asaas?>" />
	  <input type="hidden" id="cod_<?=$Script?>" name="cod" value="<?=$_GET[cod]?>" />
        
        
     </form>	


		
		
</div>
</div>


<script type="text/javascript">

//opção de cancelar
$("#cancelar_<?=$Script?>").click(function(){

	   $("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
		$.ajax({
			url: './<?=$Conf[script]?>.php',
			success: function(data) {
			$('#<?=$_SESSION[$Script][dialog]?>').html(data);
				$("#CARREGANDO").html('');
				$('body>.tooltip').remove();
			}
		});
});

//opção de salvar
function retornar_<?=$Script?>(cod){

	 $.confirm({
		title: "",
		content: "<b>Dados salvo com sucesso!</b>",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "",
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
 
	
TipoPessoa = $("#tipo_pessoa_in").val();
if(TipoPessoa=="F"){
   $('#cpf_cnpj_in').mask('000.000.000-00');
}else if(TipoPessoa=="J"){
   $('#cpf_cnpj_in').mask('00.000.000/0000-00');	 
}else{
   $('#cpf_cnpj_in').mask('000.000.000-00');
}
	
	
$("#tipo_pessoa_in").change(function(){
	
	var tp = $(this).val();
	
	if(tp=='F'){

		$('#cpf_cnpj_in').mask('000.000.000-00');
		$('#MostraCpfCnpj').css('display','block');
	}else if(tp=='J'){

		$('#cpf_cnpj_in').mask('00.000.000/0000-00');
		$('#MostraCpfCnpj').css('display','block');
	}else{
		$('#MostraCpfCnpj').css('display','none');
	}
	
});	

$("#estado_in").change(function(){
	estado = $(this).val();
	//alert(estado);
	$("#cidade_in").html('<option>Carregando cidades...</option>');
	$.ajax({
	url:"./acoes/select_cidades.php?estado="+estado,
	success:function(dados){
	$("#cidade_in").html(dados);
	}
	});
});		
	
	
$('#Usuarios').validate({
	rules : {
		nome : {
			required : true
		},
		email: {
			required: true,
			email: true
		},
		tipo_pessoa: {
			required: true
		},
		cpf_cnpj: {
			required: true
		},
		celular : {
			required : true,
			minlength: 17
		},
		estado : {
			required : true
		},
		cidade : {
			required : true
		},
		cep : {
			required : true
		},
		numero : {
			required : true
		},
		data_nascimento : {
			required : true
		}	

	},
	messages : {
		nome : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o nome.</div>'
		},
		email : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu e-mail.</div>',
			email: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Digite um endereço de e-mail valido.</div>'
		},
		tipo_pessoa : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe tipo pessoa.</div>'
		},
		cpf_cnpj : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe Cpf/Cnpj.</div>'
		},
		celular : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu celular.</div>',
			minlength: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Esta faltando número.</div>',
		},
		estado : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o estado.</div>'
		},
		cidade : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a cidade.</div>'
		},
		cep : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o cep.</div>'
		},
		numero : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o número.</div>'
		},
		data_nascimento : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a data nascimento.</div>'
		}		
	}

});		
	
	
$("#cep_in").blur(function(){
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
			$("#endereco_in").val(dadosRetorno.logradouro);
			$("#bairro_in").val(dadosRetorno.bairro);
			//$("#cidade").val(dadosRetorno.localidade);
			//$("#uf").val(dadosRetorno.uf);
		}catch(ex){}
	});
});		
	
	
$("#celular_in").mask('55 99 9 9999-9999');	
</script>