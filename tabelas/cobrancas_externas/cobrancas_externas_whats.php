<?php	
include("../../includes/connect.php");	
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/cobrancas_externas/cobrancas_externas_whats';
$Script = md5($Conf[script]);

$cobrancasExternas = "select * from usuarios where id !='1' ";
$RcobrancasExternas = mysqli_query($conexao,$cobrancasExternas);
while($C = mysqli_fetch_object($RcobrancasExternas)){

$con = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$C->id);	
	
list($cobrancaWhats) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_whatsapp where dueDate like '%$AnoMes%' and status = 'PENDING' "));			
	
}
$_SESSION[$Script][nr] = $cobrancaWhats;


$_SESSION[$Script][dialog] = 'CONTEUDOS';
$_SESSION[$Script][url] = 'tabelas/cobrancas_externas/cobrancas_externas_whats';
$_SESSION[$Script][titulo] = ('Cobranças de clientes whatsapp');


$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
$Md5 = $_SESSION[$Script][Script];
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
	
</style>


<div class="card" >
<div class="card-body">

		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>

			
<?php
include("../../list/list3.php");	
			
			
?>

		<div class="table-responsive" style="overflow-x:auto; overflow-y:hidden;">
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
          <table class="table table-striped">
            <thead >
              <tr >
                <th><?=$_SESSION[$Script][checkbox]?></th>
				  
                <th campo ordem='a.status'><?=$_SESSION[$Script][ordem][$opi]?> Status</th> 
                <th >Boleto</th>
				<th >info</th>				  
				<th campo ordem='a.dueDate'><?=$_SESSION[$Script][ordem][$opi]?> Data vencimento </th>   
                <th campo ordem='b.nome'><?=$_SESSION[$Script][ordem][$opi]?> Nome</th>
                <th campo ordem='a.description'><?=$_SESSION[$Script][ordem][$opi]?> Descricao</th>
				<th campo ordem='a.value'><?=$_SESSION[$Script][ordem][$opi]?> Valor a cobrar</th>  
				<th campo ordem='a.dateCreated'><?=$_SESSION[$Script][ordem][$opi]?> Data criação </th>   
				</th> <th campo ordem='a.paymentDate'><?=$_SESSION[$Script][ordem][$opi]?> Data pagamento </th>  

              </tr>
            </thead>
            
         <tbody>
        <?php
	
	
$cobEx = "select * from usuarios where id !='1' ";
$RcobEx = mysqli_query($conexao,$cobEx);
while($c = mysqli_fetch_object($RcobEx)){

$con = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$c->id);	
	
		$pergunta = "select a.*, b.nome from elmenoufi_bot_".$c->id.".asaas_cobranca_whatsapp a
				left join elmenoufi_bot.usuarios b on b.id_asaas=a.customer
				where b.nome like '%$busca%' 
				  ".$_SESSION[$Script][campo]."
				  ".$_SESSION[$Script][ordenar]."

				limit $pn,$pg ";
		$resultado = mysqli_query($con, $pergunta);
		//echo $pergunta."<br>";
	
	
	
	
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
			
			
			$cor = ( (($d->status=='RECEIVED')?'#184E0B':
					 (($d->status=='PENDING')?'#BC1D1F':
					 '#13577C'
			       )));			
			
        ?>
              <tr class="AoclicarList" style="color:<?=$cor?>">
                <td ><input pos<?=$d->id_rg?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id_rg?>" > </td>
				  
                <td Editar cod="<?=$d->id_rg?>" ><?=$status?> </td> 

				<td >
				 <i class="fas fa-barcode UrlBoleto" url_boleto="<?=$d->bankSlipUrl?>" ></i>			  
				</td> 
				<td >
				 <i class="fas fa-info-circle InfoBoleto" info_boleto="<?=$d->invoiceUrl?>" ></i>			  
				</td>					  
				<td Editar cod="<?=$d->id_rg?>" ><?=dataBr($d->dueDate)?> </td>   
                <td Editar cod="<?=$d->id_rg?>" ><?=($d->nome)?> </td>
                <td Editar cod="<?=$d->id_rg?>" ><?=($d->description)?> </td> 
				<td Editar cod="<?=$d->id_rg?>" >R$ <?=number_format($d->value,2,",",".")?> </td>  
				<td Editar cod="<?=$d->id_rg?>" ><?=data_br_completo($d->dateCreated)?> </td>   
				<td Editar cod="<?=$d->id_rg?>" ><?=dataBr($d->paymentDate)?> </td> 
              </tr>
        

	
	
<?php	
		}
	
}
?>	
	
	


            </tbody>
          </table>
		  </div>
			
        </form>


</div>
</div>


<script language="javascript">

$('.UrlBoleto').click(function(){

	var url_boleto = $(this).attr("url_boleto");

	window.open(url_boleto, '_blank');		


});
$('.InfoBoleto').click(function(){

	var info_boleto = $(this).attr("info_boleto");

	window.open(info_boleto, '_blank');		


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
	
</script>