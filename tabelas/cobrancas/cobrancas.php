<?php	
include("../../includes/connect.php");	
include("../../includes/funcoes.php");

	list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));	

	list($api_token_cliente) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '".$_SESSION[id_usuario]."' and status = 'Liberado' "));	

	if($api_token_dono==$api_token_cliente){
		$api_token = $api_token_dono ;
	}else{
		$api_token = $api_token_cliente ;	
	}

	$Conf[script] = 'tabelas/cobrancas/cobrancas';
	$Script = md5($Conf[script]);
    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cobrancas  "));
	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/cobrancas/cobrancas';
	$_SESSION[$Script][titulo] = ('Emissão de cobranças');
	
	$_SESSION[$Script][FiltroSim] = 'tabelas/cobrancas/filtro.php'; //informar o caminho da url;	

	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){
		
		foreach ($_POST[CheckOpc] as $value) {
			$parte = explode("|",$value);
			//echo "<script>alert(' $parte[0] e $parte[1] e $parte[2] ');</script>";
			mysqli_query($conexao2,"delete from cobrancas where id_rg = '".$parte[0]."' ");
			
			
			RemoverCobranca($parte[2],$parte[1] );			
			
		}		
 
		exit;
	}


function RemoverCobranca($api_token,$payId){
	
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments/$payId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: $api_token"
));

$response = curl_exec($ch);
curl_close($ch);

//var_dump($response);	
	
}



if($_GET[idCobranca]){
	
	$Vr1 = str_replace(".","",$_GET[Vp]);
	$value = str_replace(",",".",$Vr1);
	$idCobranca = $_GET[idCobranca] ;
	$paymentDate = $_GET[Dp];
	
	//echo "<script>alert(' $api_token $idCobranca  $paymentDate  $value ');</script>";
	PagoEmDinheio($api_token,$idCobranca,$paymentDate,$value);
}

function PagoEmDinheio($api_token,$idCobranca,$paymentDate,$value){
	
	$fields = array
	(
		'paymentDate' => $paymentDate,
		'value' => $value,
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments/$idCobranca/receiveInCash");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, "TRUE");
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json",
	  "access_token: $api_token"
	));

	$response = curl_exec($ch);
	curl_close($ch);	
	
}



function AtualizarCobranca($api_token,$idCobranca){
	
	$ch = curl_init();

	$fields = array
	(
		'billingType' => 'BOLETO',
		'value' => $value,
		'description' => $description,
		'externalReference' => $externalReference,
		'discount' => array(
			'value' => $discountvalue,
			'dueDateLimitDays' => $dueDateLimitDays
		),
		'fine' => array(
			'value' => $finevalue
		),
		'interest' => array(
			'value' => $interestvalue
		),
		'postalService' => $postalService
	);	
		
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/payments/$idCobranca");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_POST, TRUE);

	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );


	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json",
	  "access_token: $api_token"
	));

	$response = curl_exec($ch);
	curl_close($ch);	
	
}


?>

<style>
td[Editar]:hover{
	cursor:pointer;
}
	
th[campo]:hover{
	color: #0A9835;
}

	.SendTo{
		cursor:pointer;
		font-size:22px;
	}
	.SendTo:hover{
		color: #22C86B;
	}	
	
	.LembreteVencido{
		cursor:pointer;
		font-size:28px;
	}
	.LembreteVencido:hover{
		color: #22C86B;
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
	.Dinheiro{
		cursor:pointer;
		font-size:28px;
	}
	.Dinheiro:hover{
		color: #00220F;
	}	
	
	.ConfigNF{
		width:30px !important;
		cursor:pointer;
			
	}	
	
	
	#ResumoFinanceiro{
		border: #000000 solid 1px;
		padding:10px;
		left:0;
		height: auto;
		width:100%;
		position: fixed;
		bottom: 0;
		color:#fff;
		z-index:999;
		text-align:right;
		border-radius:12px;
		
		background: rgba(0,0,0, 0.45); /* Old browsers */
		background: -moz-linear-gradient(top,  rgba(0,0,0, 0.45), rgba(0,0,0, 0.45) 10%, rgba(0,0,0, 0.45)); /* FF3.6-15 */
		background: -webkit-linear-gradient(top,  rgba(0,0,0, 0.45), rgba(0,0,0, 0.45) 10%,rgba(0,0,0, 0.45)); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom,  rgba(0,0,0, 0.45), rgba(0,0,0, 0.45) 10%,rgba(0,0,0, 0.45)); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='rgba(0,0,0, 0.45)', endColorstr='rgba(0,0,0, 0.45)',GradientType=0 ); /* IE6-9 */		

	}
	.NovaCobranca:hover{
		cursor:pointer;
		color: #021EC3;
	}
	
	.Moedas{
		font-size:28px;
	}
	.Moedas2{
		font-size:20px;
	}
	.Moedas3{
		font-size:16px;
	}	
</style>



<div class="card" >
<div class="card-body">

		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>

			
		<?php
	    include("../../list/list3.php");	
		include("./filtro_avancado.php");

		$_SESSION[$Script][buscaVez] = $buscaVez;			

	
		$pergnta = "select a.*, b.nome from cobrancas a
					left join clientes b on b.id_asaas=a.customer

					  ".$_SESSION[$Script][buscaVez]."
						
					  ".$_SESSION[$Script][campo]."
					  ".$_SESSION[$Script][ordenar]."

					limit $pn,$pg ";
		$resultado = mysqli_query($conexao2, $pergnta);
		//echo $pergnta;			
		
			
			
		list($_SESSION[$Script][nrB]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(a.id) from cobrancas a left join clientes b on b.id_asaas=a.customer ".$_SESSION[$Script][buscaVez]."  "));
		?>
	
	
	
	
	
	
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="padding:1px;margin-top:10px;">
		<span class="NovaCobranca"><i class="fas fa-file"></i> Nova Cobrança</span>
	</div>
	
	
	
	
	
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right"> De <b><?=dataBr($data_inicio)?></b> ate <b><?=dataBr($data_fim)?></b>	</div>
	
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:1px; font-family:Calibri Bold Italic; color:#5E5A5A; font-size:16px; display:<?=(($_SESSION[$Script][buscaVez])?'block':'none')?>" >	
		A consulta retornou <b><?=$_SESSION[$Script][nrB]?></b> registros no total da busca informada.
		</div>	
	
	
		<div class="table-responsive" style="overflow-x:auto;">
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
          <table class="table table-striped">
            <thead >
              <tr >
                <th><?=$_SESSION[$Script][checkbox]?></th>
				  
				<th>TO</th>  
				  
                <th campo ordem='a.status'><?=$_SESSION[$Script][ordem][$opi]?> Status</th> 
                <th >Boleto</th>
				<th >Info</th>	
				<th data-placement="top" aria-hidden="true" data-toggle="tooltip" title="Caso o cliente ter pagado em dinheiro">Espécie</th>	
				<th >NF</th>				   
				 
				<th campo ordem='a.value'><?=$_SESSION[$Script][ordem][$opi]?> Valor a cobrar</th>  
				<th campo ordem='a.dueDate'><?=$_SESSION[$Script][ordem][$opi]?> Data vencimento </th>  
                <th campo ordem='b.nome'><?=$_SESSION[$Script][ordem][$opi]?> Nome</th> 
                <th campo ordem='a.description'><?=$_SESSION[$Script][ordem][$opi]?> Descricao</th>
				<th campo ordem='a.dateCreated'><?=$_SESSION[$Script][ordem][$opi]?> Data criação </th> <th campo ordem='a.paymentDate'><?=$_SESSION[$Script][ordem][$opi]?> Data pagamento </th>   
				

              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
			
			
		$status = ( (($d->status=='PENDING')?'PENDENTE':  
				   
				    ( ($d->status=='CONFIRMED')?'CONFIRMADO': 
				   
				    ( ($d->status=='RECEIVED')?'RECEBIDO': 
				   
				    ( ($d->status=='RECEIVED_IN_CASH')?'RECEBIDO EM DINHEIRO': 
				   
				    ( ($d->status=='OVERDUE')?'VENCIDO': 
				   
				    ( ($d->status=='REFUND_REQUESTED')?'REEMBOLSO SOLICITADO': 
				   
				    ( ($d->status=='REFUNDED')?'DEVOLVEU': 
				   
				    ( ($d->status=='CHARGEBACK_REQUESTED')?'COBRANÇA SOLICITADA':
				   
				    ( ($d->status=='CHARGEBACK_DISPUTE')?'RECUPERAR A DISPUTA': 
				   
				    ( ($d->status=='AWAITING_CHARGEBACK_REVERSAL')?'AGUARDANDO REVERSÃO DE COBRANÇA': 
				   
				    ( ($d->status=='DUNNING_REQUESTED')?'DUNNING SOLICITADO': 
				   
				    ( ($d->status=='DUNNING_RECEIVED')?'DUNNING RECEBIDO': 
				   
				    ( ($d->status=='AWAITING_RISK_ANALYSIS')?'AGUARDANDO ANÁLISE DE RISCO':
				   
				    '' 
		            ))))))))))))));	
			
			
			$cor = ( (($d->status=='RECEIVED' or $d->status=='RECEIVED_IN_CASH' )?'#184E0B':
					 (($d->status=='PENDING')?'#BC1D1F':
					 '#13577C'
			       )));
			
			
			$taxa = ($d->value - $d->netValue);
        ?>
              <tr style="color:<?=$cor?>">
                <td ><input pos<?=$d->id_rg?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id_rg.'|'.$d->id."|".$api_token?>" > </td>
				  
				  <td > 
					  
				  	<i class="fas fa-file-import SendTo" data-toggle="tooltip" data-placement="top" title="Reenviar fatura para <?=($d->nome)?>" style="display:<?=(($d->status!='RECEIVED')?'block':'none')?>" Cod="<?=$d->id_rg?>" CodSess="<?=$_SESSION[id_usuario]?>" Nome="<?=($d->nome)?>"></i>
				  
				  </td> 
				  
				  
                <td Editar cod="<?=$d->id_rg?>" > 

					<i class="fas fa-paper-plane LembreteVencido" style="display:<?=(($d->status=='OVERDUE')?'block':'none')?>" Cod="<?=$d->id_rg?>" CodSess="<?=$_SESSION[id_usuario]?>" Nome="<?=($d->nome)?>"  data-toggle="tooltip" data-placement="top" title="Reenviar 2ª via para <?=($d->nome)?>"></i>
					
					
					<span style="font-size:10px;"><?=$d->id?></span> <br><span class="Atualizar" customer="<?=$d->customer?>" style="cursor:pointer">[<?=$d->id_rg?>]</span> <?=$status?></td> 

				<td >
				 <i class="fas fa-barcode UrlBoleto" url_boleto="<?=$d->bankSlipUrl?>" ></i>			  
				</td> 
				<td >
				 <i class="fas fa-info-circle InfoBoleto" info_boleto="<?=$d->invoiceUrl?>" ></i>			  
				</td> 
 
				<td >
				 <i class="far fa-money-bill-alt Dinheiro" idCobranca="<?=$d->id?>" Valor="<?=number_format($d->value,2,",",".")?>" status="<?=$d->status?>" Nome="<?=($d->nome)?>" title="Clique aqui caso o cliente <?=($d->nome)?> ter pagado em dinheiro"></i>		  
				</td>

				<td> <img EmitirNota src="./img/nf.png" data-toggle="tooltip" data-placement="left" title="Emitir nota fiscal <?=$d->id?>"class="ConfigNF" idUsuario="<?=$_SESSION[id_usuario]?>" Pay="<?=$d->id?>"> </td>
				  
				  
				<td Editar cod="<?=$d->id_rg?>" >
				 <span style="font-size:11px;">R$ <?=number_format($d->value,2,",",".")?></span><br>	 
				 <span style="font-size:11px;">R$ <?=number_format($d->netValue,2,",",".")?></span><br>	 
				 <span style="font-size:11px;">Taxa R$ <?=number_format($taxa,2,",",".")?></span>	 
				</td>  
				<td Editar cod="<?=$d->id_rg?>" ><?=dataBr($d->dueDate)?> </td>    
                <td Editar cod="<?=$d->id_rg?>" ><?=($d->nome)?> </td>
				
				  
                <td Editar cod="<?=$d->id_rg?>">
					<?=($d->description)?> 
				</td> 
				  
				<td Editar cod="<?=$d->id_rg?>" ><?=data_br_completo($d->dateCreated)?> </td>  
				<td Editar cod="<?=$d->id_rg?>" ><?=dataBr($d->paymentDate)?> </td>   
				
			  
				  
              </tr>
        
        <?php
        }
        ?>
            </tbody>
          </table>
		  </div>
			
        </form>

<?php
$pergunta2 = "select a.*, b.nome from cobrancas a
			left join clientes b on b.id_asaas=a.customer

			  ".$_SESSION[$Script][buscaVez]."

			  ".$_SESSION[$Script][campo]."
			  ".$_SESSION[$Script][ordenar]."

			  ";
$resultado2 = mysqli_query($conexao2, $pergunta2);	
while($d2 = mysqli_fetch_object($resultado2)){
	
	if($d2->status=='RECEIVED' or $d2->status=='RECEIVED_IN_CASH' ){
		$valorPagoPruto += $d2->value;
		$valorPago += $d2->netValue;
	}
	if($d2->status=='PENDING' or $d2->status=='OVERDUE'){
		$valorPendentePruto += $d2->value;
		$valorPendente += $d2->netValue;
	}

	
	$vlTaxas += ($d2->value - $d2->netValue) ;
}
	
?>

	<div id="ResumoFinanceiro"> 
		<div class="row">
			
			
			<div class="col-md-3 col-lg-3 "></div>
			<div class="col-md-3 col-lg-3 "> 
				<span>Valor recebido:</span><br> 
				<span class="Moedas">R$ <?=number_format($valorPago,2,",",".")?></span><br>
				<span class="Moedas2">R$ <?=number_format($valorPagoPruto,2,",",".")?></span>
				
			</div>
			<div class="col-md-3 col-lg-3 ">
				<span>Valor a receber:</span><br>
				<span class="Moedas">R$ <?=number_format($valorPendente,2,",",".")?></span><br>
				<span class="Moedas2">R$ <?=number_format($valorPendentePruto,2,",",".")?></span>
			</div>
			<div class="col-md-3 col-lg-3 ">
				<span>Total:</span><br>
				<?php
				$vlTotal = ($valorPago + $valorPendente);
				$vlTotalPruto = ($valorPagoPruto + $valorPendentePruto);
				?>
				<span class="Moedas">R$ <?=number_format($vlTotal,2,",",".")?></span><br>
				<span class="Moedas2">R$ <?=number_format($vlTotalPruto,2,",",".")?></span><br>
				<span class="Moedas3">Taxas R$ <?=number_format($vlTaxas,2,",",".")?></span>
			</div>
			
			
		</div>
		
		
	</div>
	
	
</div>
</div>

<div id="LEMBRETE"></div>


<script language="javascript">


$('.Atualizar').click(function(){ //em desenvolvimento

	let customer = $(this).attr("customer");
	alert(customer);
	
});	
	
	
	
$('.UrlBoleto').click(function(){

	var url_boleto = $(this).attr("url_boleto");

	window.open(url_boleto, '_blank');		


});
$('.InfoBoleto').click(function(){

	var info_boleto = $(this).attr("info_boleto");

	window.open(info_boleto, '_blank');		


});	
$('.Dinheiro').click(function(){

	var idCobranca = $(this).attr("idCobranca");
    var status = $(this).attr("status");
    var Nome = $(this).attr("Nome");
    var Valor = $(this).attr("Valor");
	
	if(status=='RECEIVED'){
		
     $.confirm({
		title: "<span style='color:blue'>ATENÇÂO!</span>",
		content: "O boleto já foi pago!",
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
		
	}else{
		
     $.confirm({
		title: "<span style='color:blue'>ATENÇÂO!</span>",
		content: "O cliente <b>"+Nome+"</b> pagou em dinheiro? <br> Confirma que foi <b>pago</b> em dinheiro? <br> <input type='date' id='DataPagamento' class='form-control' placeholder='Data pagamento' value='<?=date('Y-m-d')?>' /> <br> <input type='text' id='ValorPagamento' class='form-control' placeholder='Valor a cobrar' value='"+Valor+"' /> ",

		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Sim: {
				btnClass: "btn-success",
				action: function(){
					
					
					if(status=='OVERDUE'){

						let Dp = $("#DataPagamento").val();
						let Vp = $("#ValorPagamento").val();

						$.ajax({
						  url: './<?=$_SESSION[$Script][url]?>.php?idCobranca='+idCobranca+'&Dp='+Dp+'&Vp='+Vp,
						  success: function(data) {
						  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
							  $("#CARREGANDO").html('');
							  $('body>.tooltip').remove();
						  }
						});							
						
					}else{
						
						$.confirm({
							title: "<span style='color:blue'>ATENÇÂO!</span>",
							content: "Não é possível receber a cobrança<br>Pois ela não está pendente.",
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
			'Não': {
				btnClass: "btn-danger",
				action: function(){
				}
			},
		}				
	  });		
		
	}

	
	

});	
	
$("img[EmitirNota]").click( function(){ 	
	
	 var idUsuario = $(this).attr("idUsuario");
	 var Pay = $(this).attr("Pay");
	
     $.confirm({
		title: "<span style='color:blue'>ATENÇÂO!</span>",
		content: "Deseja realmente emitir a nota fiscal <b>"+Pay+"</b>?",

		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Sim: {
				btnClass: "btn-success",
				action: function(){

					
				}
			},
			'Não': {
				btnClass: "btn-danger",
				action: function(){
				}
			},
		}				
	  });	
	
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

	
$(".SendTo").click( function(){ 
	
let cSess = $(this).attr('CodSess');
let c = $(this).attr('cod');
let n = $(this).attr('Nome');


 $.confirm({

	title: "<span style='color:red'>Atenção!</span>",
	content: "Deseja realmente Reenviar para o cliente <b>"+n+"</b> de nº:<b>"+c+"</b> agora?",
	columnClass:"col-md-4 col-md-offset-4",
	theme: "light",
	buttons: {
		Sim: {
			btnClass: "btn-success",
			action: function(){
				
				$.ajax({
					type: "POST",
					url: "./_mailer_6_1_whats/_envio_what_novamente.php",
					data: {
						idUsuario:cSess,
						idCobranca:c
					},
					success: function( data )
					{
						$("#LEMBRETE").html(data)
						$('body>.tooltip').remove();

					}
				});				
				
			}

		},
		'Não': {
			btnClass: "btn-danger",
			action: function(){

			}

		},
	}				

  });	
	


});	
	
$(".LembreteVencido").click( function(){ 
	
let cSess = $(this).attr('CodSess');
let c = $(this).attr('cod');
let n = $(this).attr('Nome');


 $.confirm({

	title: "<span style='color:red'>Atenção!</span>",
	content: "Deseja realmente enviar o lembrete para o cliente <b>"+n+"</b> de nº:<b>"+c+"</b> agora?",
	columnClass:"col-md-4 col-md-offset-4",
	theme: "light",
	buttons: {
		Sim: {
			btnClass: "btn-success",
			action: function(){
				
				$.ajax({
					type: "POST",
					url: "./_mailer_6_1_whats/_envio_vencido.php",
					data: {
						idUsuario:cSess,
						idCobranca:c
					},
					success: function( data )
					{
						$("#LEMBRETE").html(data)
						$('body>.tooltip').remove();

					}
				});				
				
			}

		},
		'Não': {
			btnClass: "btn-danger",
			action: function(){

			}

		},
	}				

  });	
	


});
function LembreteEnviado(a){
	
$.confirm({
	title: "<span style='color:green'>Status!</span>",
	content: "Lembrete enviada com sucesso! "+a,
	columnClass:"col-md-4 col-md-offset-4",
	theme: "light",
	buttons: {
		ok: {
			btnClass: "btn-success",
			action: function(){
			}
		}
	}
});	
	
}	
	
	
$(".NovaCobranca").click(function(){
	
	NovaCobranca = $.confirm({
		title: "<span style='color:green'>Nova Cobrança!</span>",
		content: "url:./tabelas/cobrancas/nova_cobranca.php",
		columnClass:"col-md-8 col-md-offset-2",
		theme: "light",
		buttons: {
			Cancelar: {
				btnClass: "btn-danger ",
				action: function(){
				}
			}
		}
	});	
	
})	
	
	
</script>