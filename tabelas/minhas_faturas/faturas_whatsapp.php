<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');

$Conf[script] = 'tabelas/minhas_faturas/faturas_whatsapp';
	$Script = md5($Conf[script]);
    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from asaas_cobranca_whatsapp  "));
	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/minhas_faturas/faturas_whatsapp';
	$_SESSION[$Script][titulo] = 'Faturas WhatsApp';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	
	
	if($_POST){
		//mysqli_query($conexao2,"delete from asaas_cobranca_whatsapp where id_rg in (".@implode(",",$_POST[CheckOpc]).")");
		
		//echo "<script>alert('".implode(",",$_POST[CheckOpc])."');</script>";
		
		exit;
	}


if(!$_SESSION[$Script][Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}
if($_GET[Cod]){$_SESSION[$Script][Cod] = $_GET[Cod];}

if(!$_SESSION[$Script][data_vencimento]){$_SESSION[$Script][data_vencimento] = $_GET[data_vencimento];}
if($_GET[data_vencimento]){$_SESSION[$Script][data_vencimento] = $_GET[data_vencimento];}

?>

<style>
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

<div class="row">	
	<div class="col-lg-12 col-md-12" style="border: #FF0004 solid 0px; padding:0;"> 
		<div class="panel-heading">
			
		<i class="fas fa-shopping-cart mdi-24px "></i> <?=($_SESSION[$Script][titulo])?>
		</div>

		<button type="button" class="btn btn-gradient-primary btn-icon-text btn-sm Home" style="float:right">HOME</button> 
	</div>
</div>	
	
<div class="row">

<button type="button" id="MeusPlanos" class="btn btn-success">Meus planos</button> 	

	
<div class="col-lg-12 col-md-12" style="margin-top:10px;">

	
		<?php	
	    include("../../list/list3.php");	
			
		$pergnta = "select a.* from asaas_cobranca_whatsapp a
					where a.description like '%$busca%'

					  ".$_SESSION[$Script][campo]."
					  ".$_SESSION[$Script][ordenar]."

					limit $pn,$pg ";
		$resultado = mysqli_query($conexao2, $pergnta);
		//echo $pergnta;			
			
		?>

		<div class="table-responsive" style="overflow-x:auto; ">
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
          <table class="table table-striped">
            <thead >
              <tr >
                <th  ><?=$_SESSION[$Script][checkbox]?></th>
                <th >Boleto</th>
				<th >info</th>				  
                <th campo ordem='a.description'><?=$_SESSION[$Script][ordem][$opi]?> Descricao</th>
				<th campo ordem='a.quantidade_plano'><?=$_SESSION[$Script][ordem][$opi]?> Qnt plano</th> 
				<th campo ordem='a.value'><?=$_SESSION[$Script][ordem][$opi]?> Valor a cobrar</th>  
				<th campo ordem='a.status'><?=$_SESSION[$Script][ordem][$opi]?> Status</th>   
				<th campo ordem='a.dateCreated'><?=$_SESSION[$Script][ordem][$opi]?> Data criação </th>   
				<th campo ordem='a.dueDate'><?=$_SESSION[$Script][ordem][$opi]?> Data vencimento </th>   
				<th campo ordem='a.paymentDate'><?=$_SESSION[$Script][ordem][$opi]?> Data pagamento </th> 
                

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
			
        ?> 
              <tr style="color:<?=$cor?>">
                <td ><input pos<?=$d->id_rg?> type="checkbox" name="CheckOpc[]" <?=(($d->status=='RECEIVED')?'disabled':false)?> class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id_rg?>" >  </td>
				  
				<td >
				 <i class="fas fa-barcode UrlBoleto" url_boleto="<?=$d->bankSlipUrl?>" ></i>			  
				</td> 
				<td >
					<i class="fas fa-info-circle InfoBoleto" info_boleto="<?=$d->invoiceUrl?>" ></i>
				</td>				  
				  
                <td Editar cod="<?=$d->id_rg?>" ><?=($d->description)?> </td> 
				<td Editar cod="<?=$d->id_rg?>" ><?=($d->quantidade_plano)?> </td>  
				<td Editar cod="<?=$d->id_rg?>" >R$ <?=number_format($d->value,2,",",".")?> </td> 
				<td Editar cod="<?=$d->id_rg?>" ><?=$status?> </td>   
				<td Editar cod="<?=$d->id_rg?>" ><?=data_br_completo($d->dateCreated)?> </td>   
				<td Editar cod="<?=$d->id_rg?>" ><?=dataBr($d->dueDate)?> </td>    
				<td Editar cod="<?=$d->id_rg?>" ><?=dataBr($d->paymentDate)?> </td> 
                
				  


              </tr>
        
        <?php
        }
        ?>
            </tbody>
          </table>
		  </div>
        
        </form>

	
	
	
	
	
</div>	
	
	
</div>
	
</div>	
</div>

<script>
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
	
	
	
	
$("#MeusPlanos").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

	$.ajax({
	  url: "./tabelas/ecommerce/whatsapp.php",
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
	  $("#CARREGANDO").html('');
	  }
	});
	
});
	
	
	
$(".Home").click(function(){
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

	$.ajax({
	  url: "./paginas/pagina_inicial.php",
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
	  $("#CARREGANDO").html('');
	  }
	});
	
});	
	

</script>