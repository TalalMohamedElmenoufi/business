<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/conta_bancaria/conta_bancaria';
$Script = md5($Conf[script]);

list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao, "select count(id) from usuarios  "));
$_SESSION[$Script][tabela] = 'usuarios';
$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/conta_bancaria/conta_bancaria';
$_SESSION[$Script][titulo] = 'Sua conta bancaria';

list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id='1'  "));
//ListaContas($api_token_dono);

list($proprietario) = mysqli_fetch_row(mysqli_query($conexao, "select nome from usuarios where id='".$_SESSION[id_usuario]."'  "));


list($api_token) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario='".$_SESSION[id_usuario]."'  "));

list($id_alerta) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id) from alertas ")); 
list($alerta) = mysqli_fetch_row(mysqli_query($conexao2, "select alerta from alertas where id = '".$id_alerta."' ")); 


$pergunta = "select * from asaas where id_usuario='".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao, $pergunta);
$d = mysqli_fetch_object($resultado);



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
	
	
.btn-bs-file{
    position:relative;
	width:100%;
}
.btn-bs-file input[type="file"]{
    position: absolute;
    top: -9999999;
    filter: alpha(opacity=0);
    opacity: 0;
    width:0;
    height:0;
    outline: none;
    cursor: inherit;
}	
	
	.IconContrac{
		font-size:20px !important;
	}
	.TextContrac{
		font-size:15px !important;
	}	
	
	.IconCpfCnh{
		font-size:20px !important;
	}
	.TextCpfCnh{
		font-size:15px !important;
	}
	
	.Iconind{
		font-size:20px !important;
	}
	.Textind{
		font-size:15px !important;
	}
	.IconCpfCnhindi{
		font-size:20px !important;
	}
	.TextCpfCnhindi{
		font-size:15px !important;
	}	
	
	
	.Aprovado{
		font-size:27px;
		color:#0D5100;
	}
	.AprovadoText{
		font-size:16px;
		font-family: Constantia, "Lucida Bright", "DejaVu Serif", Georgia, "serif";
		font-weight: 800;
	}
	
	.ObsST{
		font-size:11px;
		color: #E00003;
	}
	
</style>



<div class="card" >
<div class="card-body">
	
		<div class="panel-heading">
		<?=$d->id?> - <?=($_SESSION[$Script][titulo])?>
		</div>

		<form id="conta_bancaria" action="<?=$Conf[script]?>.php" method="post" target="pagina" enctype="multipart/form-data" >	
			 
		<div class="row">
	
			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Nome ou Razão social</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="name" class="form-control" placeholder="Nome ou Razão social" value="<?=($d->razao_social)?>" /></p>
			</div>	




			<div class="form-group col-lg-4 col-md-4">
				
			

				<?php
				if($d2->tipo_pessoa=='F'){
				?>
				 <span class="TituloForms">Tipo pessoa</span><br>
				  <i class="far fa-copyright Icons"></i>				
				  <p><input InputForm type="text" name="companyType" class="form-control"   value="pessoa_fisica" readonly /></p>
				<?php
				}else{
				?>
				<span class="TituloForms">Tipo da empresa:</span> 	
				<select InputForm class="form-control input-text-select" id="companyType" name="companyType" style="display:<?=($d2->tipo_pessoa=='J')?'block':'none'?>">
				<option InputForm value="">::Selecione::</option>
				<option InputForm value="mei" <?=(($d->companyType=='mei')?'selected':false)?> >Micro Empreendedor individual (mei)</option>
				<option InputForm value="limited" <?=(($d->companyType=='limited')?'selected':false)?>>Empresa limitada</option>	
				<option InputForm value="individual" <?=(($d->companyType=='individual')?'selected':false)?>>Empresa individual</option>
				<option InputForm value="association" <?=(($d->companyType=='association')?'selected':false)?>>Associação</option>
				</select>				
				<?php
				}
	            ?>
				

			</div>				 
		

			
			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Nº Conta</span>
				  <p><input type="number" name="account" class="form-control" placeholder="Nº Conta" value="<?=($d->account)?>" /></p>
			</div>	
			
			<div class="form-group col-lg-2 col-md-2">
				 <span class="TituloForms">Digito</span>
				  <p><input type="number" name="accountDigit" class="form-control" placeholder="Digito" value="<?=($d->accountDigit)?>" /></p>
				
				
				<input type="hidden" name="accountName" class="form-control" value="Referencia <?=($d->razao_social)?>" />
			</div>	
			
			<div class="form-group col-lg-2 col-md-2">
				 <span class="TituloForms">Agencia</span>
				  <p><input type="number" name="agency" class="form-control" placeholder="Agencia" value="<?=($d->agency)?>" /></p>
			</div>			


			<div class="form-group col-lg-4 col-md-4">
				<span class="TituloForms">Banco:</span> 

				<select Meubanco class="selectpicker" data-width="100%" title="Informe seu banco" data-live-search="true" name="bank" >
				 <option class="MenusAoPassar" data-icon="fas fa-university" data-subtext="" value="">Selecione  o seu banco</option>
				  <?php
					$query = "select * from  bancos_clientes order by id desc";
					$result = mysqli_query($conexao, $query);
					while($b = mysqli_fetch_object($result)){
				  ?>
				  <option class="MenusAoPassar" data-icon="fas fa-university" data-subtext="<?=$b->cod_bn?>" value="<?=$b->cod_bn?>" <?=(($d->bank==$b->cod_bn)?'selected':false)?> ><?=($b->banco_nome)?></option>
				  <?php
					}
				  ?>
				</select>				
				
			</div>			

			
			<div class="form-group col-lg-4 col-md-4">
				<span class="TituloForms">Tipo da conta:</span> 
				<select InputForm class="form-control input-text-select" id="bankAccountType" name="bankAccountType">
				<option InputForm value="">::Selecione::</option>
				<option InputForm value="CONTA_POUPANCA" <?=(($d->bankAccountType=='CONTA_POUPANCA')?'selected':false)?> >Poupança</option>
				<option InputForm value="CONTA_CORRENTE" <?=(($d->bankAccountType=='CONTA_CORRENTE')?'selected':false)?>>Corrente</option>	
				</select>
			</div>
			
			

<?php
	//$mostrar = (($d->companyType)?'block': (($d2->tipo_pessoa=='F')?'block': false)  ) ;
	$mostrar = (($d->token_producao)?'block': 'none') ;
?>
			
<div ANOXOS class="form-group col-lg-12 col-md-12" style="display:<?=$mostrar?>" >	
	
<fieldset class="fieldset-border">
  <legend class="legend-border">ANEXO DE DOCUMENTOS</legend>	

	<?php
	
		$StDos = trim($d->stDoc);
		
		$stDocs = ( (($StDos=='APPROVED')?'APROVADO':  
				   
				    ( ($StDos=='REJECTED')?'REJEITADO':   
				   
				    ( ($StDos=='Y')?'Y': 

				    '' 
		            ))));	
		
		$StDos2 = trim($d->stDoc_contrat);
		
		$stDocs2 = ( (($StDos2=='APPROVED')?'APROVADO':  
				   
				    ( ($StDos2=='REJECTED')?'REJEITADO':   
				   
				    ( ($StDos2=='Y')?'Y': 

				    '' 
		            ))));
		
		
		
		if($StDos=='APPROVED'){
			$iconD1 = 'fas fa-id-card-alt';
		}else{
			$iconD1 = 'fas fa-exclamation-triangle';
		}
		
		if($StDos2=='APPROVED'){
			$iconD2 = 'fas fa-id-card-alt';
		}else{
			$iconD2 = 'fas fa-exclamation-triangle';
		}	
	
	
	if($d2->tipo_pessoa=='F'){
	?>
	<div class="row">

			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs?></span> <i class="fas fa-check Aprovado"></i>
			</div>		
		
			<input type="hidden" name="documentType_fisica" value="IDENTIFICATION" style="display:<?=(($d2->tipo_pessoa=='F')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_fisica" value="<?=($proprietario)?>" style="display:<?=(($d2->tipo_pessoa=='F')?'block':'none')?>" />		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'none':'block')?>">
			<span class="TituloForms" style="color:#000000">Anexar CPF ou CNH:</span><br>
				<span class="ObsST"><?=$d->stObs?></span>	
			<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Anexar CPF ou CNH">
				<i class="fas fa-id-card IconCpfCnhmei" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextCpfCnhmei"><strong><?=(($d->stDoc)?$stDocs:'CPF ou CNH')?></strong></span></b>
				<input type="file" name="documentFile_fisica" id="documentFile_fisica" style="display:<?=(($d2->tipo_pessoa=='F')?'block':'none')?>" >
			</label>
			</div>	

	</div>	

	<?php
	}else{
		

	?>

	
	<div limited style="display:<?=(($d->companyType=='limited')?'block':'none')?>">
		
		<div class="row">

			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs?></span> <i class="fas fa-check Aprovado"></i>
			</div>		

			<input type="hidden" name="documentType_limited_doc" value="IDENTIFICATION" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_limited_doc" value="PARTNER" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" />		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'none':'block')?>">
			<span class="TituloForms" style="color:#000000">Anexar CPF ou CNH:</span><br>
				<span class="ObsST"><?=$d->stObs?></span>	
			<label class="btn-bs-file btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Anexar CPF ou CNH">
				<i class="<?=$iconD1?> IconCpfCnh" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextCpfCnh"><strong><?=(($d->stDoc)?$stDocs:'CPF ou CNH')?></strong></span></b>
				<input type="file" name="documentFile_limited_doc" id="documentFile_limited_doc" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" >
			</label>
			</div>	

			
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc_contrat=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs2?></span> <i class="fas fa-check Aprovado"></i>
			</div>				
			
			<input type="hidden" name="documentType_limited_contrato" value="SOCIAL_CONTRACT" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_limited_contrato" value="LIMITED_COMPANY" style="display:<?=(($d->companyType=='limited')?'block':'none')?>"/>
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc_contrat=='APPROVED')?'none':'block')?>"> 
			<span class="TituloForms" style="color:#000000">Anexar Contrato Social:</span><br>
			<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Anexar Contrato Social">
				<i class="<?=$iconD2?> IconContrac" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextContrac"><?=(($d->stDoc_contrat)?$stDocs2:'Contrato Social')?></span></b>
				<input type="file" name="documentFile_limited_contrato" id="documentFile_limited_contrato" style="display:<?=(($d->companyType=='limited')?'block':'none')?>" >
			</label>
			</div>

			
		</div>
		
	</div>
	
	<div individual style="display:<?=(($d->companyType=='individual')?'block':'none')?>">
		
		<div class="row">

			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs?></span> <i class="fas fa-check Aprovado"></i>
			</div>		

			<input type="hidden" name="documentType_individual_doc" value="IDENTIFICATION" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_individual_doc" value="INDIVIDUAL_COMPANY" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" />		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'none':'block')?>">
			<span class="TituloForms" style="color:#000000">Anexar CPF ou CNH:</span><br>
				<span class="ObsST"><?=$d->stObs?></span>
			<label class="btn-bs-file btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Anexar CPF ou CNH">
				<i class="<?=$iconD1?> IconCpfCnh" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextCpfCnh"><strong><?=(($d->stDoc)?$stDocs:'CPF ou CNH')?></strong></span></b>
				<input type="file" name="documentFile_individual_doc" id="documentFile_individual_doc" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" >
			</label>
			</div>	

			
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc_contrat=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs2?></span> <i class="fas fa-check Aprovado"></i>
			</div>				
			
			<input type="hidden" name="documentType_individual_contrato" value="ENTREPRENEUR_REQUIREMENT" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_individual_contrato" value="INDIVIDUAL_COMPANY" style="display:<?=(($d->companyType=='individual')?'block':'none')?>"/>
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc_contrat=='APPROVED')?'none':'block')?>"> 
			<span class="TituloForms" style="color:#000000">Anexar Contrato Social:</span><br>
				
			<label class="btn-bs-file btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Anexar Contrato Social">
				<i class="<?=$iconD2?> IconContrac" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextContrac"><?=(($d->stDoc_contrat)?$stDocs2:'Contrato Social')?></span></b>
				<input type="file" name="documentFile_individual_contrato" id="documentFile_individual_contrato" style="display:<?=(($d->companyType=='individual')?'block':'none')?>" >
			</label>
			</div>

	
			
		</div>		
		
	</div>
	
	<div mei style="display:<?=(($d->companyType=='mei')?'block':'none')?>">
		
		<div class="row">
		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'block':'none')?>">
				<span class="TituloForms" style="color:#000000">CPF/CNH:</span><br>
				<span class="AprovadoText"><?=$stDocs?></span> <i class="fas fa-check Aprovado"></i>
			</div>		

			<input type="hidden" name="documentType_mei_doc" value="IDENTIFICATION" style="display:<?=(($d->companyType=='mei')?'block':'none')?>" />
			<input type="hidden" name="documentGroupType_mei_doc" value="MEI" style="display:<?=(($d->companyType=='mei')?'block':'none')?>" />		
			<div class="col-lg-4 col-md-4" style="display:<?=(($d->stDoc=='APPROVED')?'none':'block')?>">
			<span class="TituloForms" style="color:#000000">Anexar CPF ou CNH:</span><br>
				<span class="ObsST"><?=$d->stObs?></span>	
			<label class="btn-bs-file btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Anexar CPF ou CNH">
				<i class="<?=$iconD1?> IconCpfCnh" aria-hidden="true"></i> 
				&nbsp; <b><span id="TextCpfCnh"><strong><?=(($d->stDoc)?$stDocs:'CPF ou CNH')?></strong></span></b>
				<input type="file" name="documentFile_mei_doc" id="documentFile_mei_doc" style="display:<?=(($d->companyType=='mei')?'block':'none')?>" >
			</label>
			</div>	
			
		</div>			
		
	</div>
	
	<div association style="display:<?=(($d->companyType=='association')?'block':'none')?>">
		<center>Não disponivel no momento!</center>
	</div>	
	
	
	

	<?php
	}
	?>	
	
	
	
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

	</div>
	
	
	
</fieldset>	
	
</div>			
			
			
			<hr>
			

			<?php $tipoPessoa = ( ($d2->tipo_pessoa=='F')?'FISICA' : (($d2->tipo_pessoa=='J')? 'JURIDICA':false) ) ?>
			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Tipo pessoa</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="personType" class="form-control" placeholder="Complemento"  value="<?=$tipoPessoa?>" readonly /></p>
			</div>
			
			
			
			<div class="form-group col-lg-6 col-md-6">
				 <span class="TituloForms">Endereço</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="address" class="form-control" placeholder="Endereço"  value="<?=($d2->endereco)?>" readonly /></p>
			</div>		

			<div class="form-group col-lg-2 col-md-2">
				 <span class="TituloForms">Número</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="addressNumber" class="form-control" placeholder="Número"  value="<?=($d2->numero)?>" readonly /></p>
			</div>		

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Complemento</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="complement" class="form-control" placeholder="Complemento"  value="<?= (($d2->complemento)?($d2->complemento):'Não informado') ?>" readonly /></p>
			</div>			

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">CPF/CNPJ</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="cpfCnpj" class="form-control" placeholder="CPF/CNPJ"  value="<?=($d2->cpf_cnpj)?>" readonly /></p>
			</div>				

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">E-mail</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="email" class="form-control" placeholder="E-mail" value="<?=($d2->email)?>" readonly /></p>
			</div>				

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Celular</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="mobilePhone" class="form-control" placeholder="Celular" value="<?=($d2->celular)?>" readonly /></p>
			</div>				
					

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">CEP</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="postalCode" class="form-control" placeholder="CEP" value="<?=($d2->cep)?>" readonly /></p>
			</div>					

			<div class="form-group col-lg-4 col-md-4">
				 <span class="TituloForms">Estado</span><br>
				  <i class="far fa-copyright Icons"></i>
				  <p><input InputForm type="text" name="province" class="form-control" placeholder="Estado" value="<?=($estado)?>" readonly /></p>
			</div>				

			
			
			<input type="hidden" name="token_producao" value="<?=$d->token_producao?>" />
			<input type="hidden" name="CodR" value="<?=$d->id?>" />
			<input type="hidden" name="CodCliente" value="<?=$_SESSION[id_usuario]?>" />
			
		 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<button type="submit" name="salvar_<?=$Script?>" class="btn btn-success">Salvar</button>                  
                

		 </div>			
			
	    </div>
	
		</form>	
			
</div>	
</div>	




<script>
$('select[Meubanco]').selectpicker();
	
//opção de salvar
function retornar_<?=$Script?>(cod){

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

	
$('#conta_bancaria').validate({
	rules : {
		name : {
			required : true
		},		
		companyType : {
			required : true
		},
		account : {
			required : true
		},
		accountDigit : {
			required : true
		},
		agency : {
			required : true
		},
		bank : {
			required : true
		},
		bankAccountType : {
			required : true
		}

	},
	messages : {
		name : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Nome ou Razão social.</div>'
		},
		companyType : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Tipo da empresa.</div>'
		},
		account : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe Nº Conta.</div>'
		},
		accountDigit : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Digito.</div>'
		},
		agency : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a Agencia.</div>'
		},
		bank : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Banco.</div>'
		},
		bankAccountType : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o Tipo da conta.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
	    $("#conta_bancaria").submit();

	return false;
}

});	

	
	
	$("#companyType").change(function(e){

		let TipoEmpresa = $(this).val();
 
		if(TipoEmpresa=="mei"){
		   $("div[ANOXOS]").css('display','block');	
		   $("div[mei]").css('display','block');
		   $("div[limited]").css('display','none');
		   $("div[individual]").css('display','none');
		   $("div[association]").css('display','none');	
		}
		else if(TipoEmpresa=="limited"){
		   $("div[ANOXOS]").css('display','block');		
		   $("div[mei]").css('display','none');
		   $("div[limited]").css('display','block');
		   $("div[individual]").css('display','none');
		   $("div[association]").css('display','none');	  
		}
		else if(TipoEmpresa=="individual"){
		   $("div[ANOXOS]").css('display','block');		
		   $("div[mei]").css('display','none');
		   $("div[limited]").css('display','none');
		   $("div[individual]").css('display','block');
		   $("div[association]").css('display','none');	 
		}
		else if(TipoEmpresa=="association"){
		   $("div[ANOXOS]").css('display','block');		
		   $("div[mei]").css('display','none');
		   $("div[limited]").css('display','none');
		   $("div[individual]").css('display','none');
		   $("div[association]").css('display','block');	
		}else{
		   $("div[ANOXOS]").css('display','none');
		   $("div[mei]").css('display','none');
		   $("div[limited]").css('display','none');
		   $("div[individual]").css('display','none');
		   $("div[association]").css('display','none');				
		}

	});		
	
	
	
	$("#documentFile_limited_contrato").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#TextContrac').text(NomeArq);
		}else{
			$('#TextContrac').text('Arquivo invalido!');
		}

	});	

	
	$("#documentFile_limited_doc").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#TextCpfCnh').text(NomeArq);
		}else{
			$('#TextCpfCnh').text('Arquivo invalido!');
		}

	});		
	
	$("#documentFile_individual_socio").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#Textind').text(NomeArq);
		}else{
			$('#Textind').text('Arquivo invalido!');
		}

	});	

	
	$("#documentFile_individual_doc").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#TextCpfCnhindi').text(NomeArq);
		}else{
			$('#TextCpfCnhindi').text('Arquivo invalido!');
		}

	});			
	

	
	$("#documentFile_mei_doc").change(function(e){

		var files = e.target.files;
		let NomeArq = e.target.files[0].name;

		var Com3 = NomeArq.substr(-3);
		var Com4 = NomeArq.substr(-4);
		
		if(Com3 == 'pdf' || Com3 == 'PDF' || Com3 == 'png' || Com3 == 'PNG' || Com3 == 'jpg' ||  Com3 == 'JPG' || Com4 == 'JPEG' || Com4 == 'jpeg'){
			$('#TextCpfCnhmei').text(NomeArq);
		}else{
			$('#TextCpfCnhmei').text('Arquivo invalido!');
		}

	});		
	
	
$("#phone").mask("999999999999");
</script>