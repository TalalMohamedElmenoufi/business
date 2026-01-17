<?php	
include("../../includes/connect.php");	
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/minhas_transferencias/minhas_transferencias';
$Script = md5($Conf[script]);
list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from minhas_transferencias  "));
$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/minhas_transferencias/minhas_transferencias';
$_SESSION[$Script][titulo] = ('Minhas transferências');


$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
$Md5 = $_SESSION[$Script][Script];


list($cpf_cnpj) = mysqli_fetch_row(mysqli_query($conexao, "select cpf_cnpj from usuarios where id = '".$_SESSION[id_usuario]."' "));

list($api_token,$bank,$razao_social,$agencia,$conta, $digito, $tipo_conta) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao, bank, razao_social, agency, account, accountDigit, bankAccountType from asaas where id_usuario = '".$_SESSION[id_usuario]."' and status = 'Liberado' "));

list($conta_nome) = mysqli_fetch_row(mysqli_query($conexao, "select banco_nome from bancos_clientes where cod_bn = '".$bank."' "));	

/*Solicitar transfeencias*/


if($_POST){

	$cpfCnpj1 = str_replace(".","",$cpf_cnpj);
	$cpfCnpj2 = str_replace("/","",$cpfCnpj1);
	$cpfCnpj3 = str_replace("-","",$cpfCnpj2);
	
	$value = $_POST[valor];
	$code = $bank;
	$accountName = ($conta_nome);
	$ownerName = ($razao_social);
	$ownerBirthDate = $_POST[data_transferencia];
	$cpfCnpj = $cpfCnpj3;
	$agency = $agencia;
	$account = $conta;
	$accountDigit = $digito;
	$bankAccountType = $tipo_conta;	

	Transferencia($conexao2,$api_token,$value,$code,$accountName,$ownerName,$ownerBirthDate,$cpfCnpj,$agency,$account,$accountDigit,$bankAccountType);	
	
	//echo "<script>alert(' $api_token, $value, $code, $accountName, $ownerName, $ownerBirthDate, $cpfCnpj, $agency, $account, $accountDigit, $bankAccountType  ');</script>";
	
	
	exit();
}


function Transferencia($conexao2,$api_token,$value,$code,$accountName,$ownerName,$ownerBirthDate,$cpfCnpj,$agency,$account,$accountDigit,$bankAccountType){

	$fields = array(
		'value' => $value,
		'bankAccount' =>
			array(
				'bank' =>
				array(
					'code' => $code
				),
			'accountName' => $accountName,
			'ownerName' => $ownerName,
			'ownerBirthDate' => $ownerBirthDate,
			'cpfCnpj' => $cpfCnpj,
			'agency' => $agency,
			'account' => $account,
			'accountDigit' => $accountDigit,
			'bankAccountType' => $bankAccountType
			)
	);	
	
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: ' . $api_token

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://api.asaas.com/v3/transfers' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
	$result = curl_exec($ch );
	curl_close( $ch );
	
	//echo $result."<br>";

	
	
$json_str = '{"Dados": '.'['.$result.']}';
$jsonObjTransfer = json_decode($json_str);
$ListaTransfer = $jsonObjTransfer->Dados;	
	
	foreach ( $ListaTransfer as $e ){
    
		$code = json_encode($e->bankAccount->bank->code) ;		 
		$name = json_encode($e->bankAccount->bank->name) ;		 
		$accountName = json_encode($e->bankAccount->accountName) ;
		$ownerName = json_encode($e->bankAccount->ownerName) ;
		$cpfCnpj = json_encode($e->bankAccount->cpfCnpj) ;
		$agency = json_encode($e->bankAccount->agency) ;
		$agencyDigit = json_encode($e->bankAccount->agencyDigit) ;
		$account = json_encode($e->bankAccount->account) ;
		$accountDigit = json_encode($e->bankAccount->accountDigit) ;

 
			mysqli_query($conexao2,
			"
			insert into minhas_transferencias set
			id = '".$e->id."',
			dateCreated = '".$e->dateCreated."',
			status = '".$e->status."',
			effectiveDate = '".$e->effectiveDate."',
			type = '".$e->type."',
			value = '".$e->value."',
			netValue = '".$e->netValue."',
			transferFee = '".$e->transferFee."',
			scheduleDate = '".$e->scheduleDate."',
			authorized = '".$e->authorized."',
			code = '".$code."',
			name = '".($name)."',
			accountName = '".($accountName)."',
			ownerName = '".($ownerName)."',
			cpfCnpj = '".$cpfCnpj."',
			agency = '".$agency."',
			agencyDigit = '".$agencyDigit."',
			account = '".$account."',
			accountDigit = '".$accountDigit."',
			transactionReceiptUrl = '".$e->transactionReceiptUrl."'
		   "   
		   );			
			
		
		}	
	    
		echo "<script>parent.AtualizarPagina('$json_str');</script>";
		
	
	
}


?>

<style>
td[Push]:hover{
	color:#F7030B;
	cursor:pointer;
}
td[Editar]:hover{
	cursor:pointer;
}
	
th[campo]:hover{
	color: #0A9835;
}

	.UrlBoleto{
		cursor:pointer;
		font-size:28px;
	}
	.UrlBoleto:hover{
		color: #00220F;
	}
	.InfoBoleto{
		cursor:pointer;
		font-size:28px;
	}
	.InfoBoleto:hover{
		color: #00220F;
	}	
	
	.NovaTransferenvia{
		padding:8px !important;
		float:right;
	}
</style>


<div class="card" >
<div class="card-body">

		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>

			
		<?php
	    include("../../list/list2.php");	
			
		$pergnta = "select a.* from minhas_transferencias a
					where a.dateCreated like '%$busca%' 

					  ".$_SESSION[$Script][campo]."
					  ".$_SESSION[$Script][ordenar]."

					limit $pn,$pg ";
		$resultado = mysqli_query($conexao2, $pergnta);
		//echo $pergnta;			
			
		?>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:1px;" >

		<button type="button" class="btn btn-success NovaTransferenvia">Solicitar transferencia</button>

		</div>		
	
	
		<div class="table-responsive" style="overflow-x:auto;">
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
          <table class="table table-striped">
            <thead >
              <tr >
				  
                <th campo ordem='a.status'><?=$_SESSION[$Script][ordem][$opi]?> Status</th> 
                <th >Comprovante</th>
				<th campo ordem='a.dateCreated'><?=$_SESSION[$Script][ordem][$opi]?> Data criada </th>   
                <th campo ordem='a.effectiveDate'><?=$_SESSION[$Script][ordem][$opi]?> Data efetiva</th>
				<th campo ordem='a.value'><?=$_SESSION[$Script][ordem][$opi]?> Valor tranferido</th>  
				<th campo ordem='a.netValue'><?=$_SESSION[$Script][ordem][$opi]?> Valor Liquido </th> 
				<th campo ordem='a.transferFee'><?=$_SESSION[$Script][ordem][$opi]?> Taxa </th>
				<th campo ordem='a.scheduleDate'><?=$_SESSION[$Script][ordem][$opi]?> Data agendada </th>   
				

              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
			
			
		$status = ( (($d->status=='PENDING')?'PENDENTE':  
				   
				    ( ($d->status=='BANK_PROCESSING')?'PROCESSAMENTO DE BANCO':     
				   
				    ( ($d->status=='DONE')?'FEITA': 

				    $d->status 
		            ))));	
			
			
			$cor = ( (($d->status=='DONE')?'#184E0B':
					 (($d->status=='PENDING')?'#BC1D1F':
					 '#13577C'
			       )));
			
        ?> 
              <tr class="AoclicarList" style="color:<?=$cor?>">
				  
                <td Editar cod="<?=$d->id_transferencia?>" > <span style="font-size:10px;"> <?=$status?> </td> 

				<td >
				 <i class="fas fa-atlas UrlBoleto" url_boleto="<?=$d->transactionReceiptUrl?>" ></i>			  
				</td> 
				  
				<td Editar cod="<?=$d->id_transferencia?>" ><?=dataBr($d->dateCreated)?> </td>   
				<td Editar cod="<?=$d->id_transferencia?>" ><?=data_br_completo($d->effectiveDate)?> </td>  
				<td Editar cod="<?=$d->id_transferencia?>" >R$ <?=number_format($d->value,2,",",".")?> </td>  
				<td Editar cod="<?=$d->id_transferencia?>" >R$ <?=number_format($d->netValue,2,",",".")?> </td>  
				<td Editar cod="<?=$d->id_transferencia?>" >R$ <?=number_format($d->transferFee,2,",",".")?> </td>  
				<td Editar cod="<?=$d->id_transferencia?>" ><?=dataBr($d->scheduleDate)?> </td>   
				
			  
				  
              </tr>
        
        <?php
        }
        ?>
            </tbody>
          </table>
		  </div>
			
        </form>

	
	<div id="DadosTransferencias" style="display:none"></div>
	
</div>
</div>

<div id="SolicitacaoTransferida" style="display:none"></div>


<script language="javascript">

$('.UrlBoleto').click(function(){

	var url_boleto = $(this).attr("url_boleto");

	window.open(url_boleto, '_blank');		


});


$("th[campo]").click( function(){
 
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	var ordem = $(this).attr('ordem');
			for ( var i = 0; i < 2; i++ ) {
					if(i == <?=$opi?>){ var opi = 1 ;  break; }
					if(i == <?=$opi?>){ var opi = 2 ;  break;  }
					if(i == i){ var opi = i ;  break;  }
				}

		$.ajax({
		  url: './<?=$_SESSION[$Script][url]?>.php?ordem='+ordem+'&opi='+opi,
		  success: function(data) {
		  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
			  $("#CARREGANDO").html('');
			  $('body>.tooltip').remove();
		  }
		});

});

	
$(".NovaTransferenvia").click( function(){	


	
     $.confirm({
		title: "<span style='color:blue'>ATENÇÂO!</span>",
		content: "Transferência<br><input type='date' id='DataTransferencia' class='form-control' placeholder='Data Transferência' value='<?=date('Y-m-d')?>' /> <br> <input type='number' id='ValorTransferencia' class='form-control' placeholder='Valor Transferência' /> ",

		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Transferir: {
				btnClass: "btn-success",
				action: function(){
					
					$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
					
					var data_transferencia = $("#DataTransferencia").val();
					var valor = $("#ValorTransferencia").val();
					
					if(data_transferencia && valor){

						$.ajax({
							type: "POST",
							url: "./tabelas/minhas_transferencias/minhas_transferencias.php",
							data: {

								data_transferencia:data_transferencia,
								valor:valor

							},
							success: function( data ){
								$("#SolicitacaoTransferida").html(data);

							}
						});					   
					   
					}else{
						
						 $.confirm({
							title: "<span style='color:red'>Atenção!<span>",
							content: "<b>Informações incorretas</b>",
							columnClass:"col-md-4 col-md-offset-4",
							theme: "light",
							buttons: {
								ok: {
									btnClass: "btn-success",
									action: function(){
									}
								},
							}				
						  });
						
					}

					
				}
			},
			Cancelar: {
				btnClass: "btn-danger",
				action: function(){
				}
			},
		}				
	  });	
	
	
});	
	
function AtualizarPagina(dados){
	
	$.ajax({
	  url: './<?=$_SESSION[$Script][url]?>.php',
	  success: function(data) {
	  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
		  $("#CARREGANDO").html('');
		  $('body>.tooltip').remove();
		  
		  $("#DadosTransferencias").html(dados);
	  }
	});	
	
	
	
}	
</script>