<?php	
include("../../includes/connect.php");	
include("../../includes/funcoes.php");

	$Conf[script] = 'tabelas/clientes/clientes';
	$Script = md5($Conf[script]);
    list($_SESSION[$Script][nr]) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from clientes  "));
	$_SESSION[$Script][dialog] = 'CONTEUDOS';
    $_SESSION[$Script][url] = 'tabelas/clientes/clientes';
	$_SESSION[$Script][titulo] = 'Cadastro clientes';
	
	
	$_SESSION[$Script][Script] = md5($_SESSION[$Script][url]);
	$Md5 = $_SESSION[$Script][Script];
	

	list($api_token_dono) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '1' "));	
	
	list($api_token_cliente) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id_usuario = '".$_SESSION[id_usuario]."' and status = 'Liberado' "));	
		
	if($api_token_dono==$api_token_cliente){
		$api_token = $api_token_dono ;
	}else{
		$api_token = $api_token_cliente ;	
	}

	
	if($_POST){
		
		
		foreach ($_POST[CheckOpc] as $value) {

			$parte = explode("|",$value);
			
			//echo "<script>alert('$parte[0] e $parte[1] ');</script>";

			mysqli_query($conexao2,"delete from clientes where id = '".$parte[0]."' ");
			mysqli_query($conexao2,"delete from servicos where id_cliente = '".$parte[0]."' ");
			mysqli_query($conexao2,"delete from customer where customer = '".$parte[1]."' ");
			
			deleteCliente($api_token,$parte[1]);
		}	
		
		if($_POST[id]){
			mysqli_query($conexao2,"update clientes set gerar='".$_POST[gerar]."' where id='".$_POST[id]."' ");
		}
		
		exit;
	}	


function deleteCliente($api_token,$idCliente){

	$curl = curl_init();
		
	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://api.asaas.com/v3/customers/$idCliente",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "DELETE",
	  CURLOPT_HTTPHEADER => [
		"accept: application/json",
		"access_token: $api_token"
	  ],
	]);
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	
	curl_close($curl);
	
	if ($err) {
	  //echo "cURL Error #:" . $err;
	} else {
	  //echo $response;
	}

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

	.BaixarExcel{
		font-size:22px;
		cursor:pointer;
	}	
	.BaixarExcel:hover{
		color: #1B4B0C; 
	}
	
	.IdCategoria{
		padding:1px !important;
		cursor:pointer !important;
	}
	
	.Bordas{
		padding:2px !important;
	}
	
	.Servicos:hover{
		cursor:pointer;
		opacity:0.8;
	}
	
	
	.ServicosList{
		border: rgba(0, 0, 0, 0.40) solid 1px;
		margin-top:-8px;
		padding:2px;
		font-size:11px;
		color:#DF0003;
		width:auto;
		height:auto;
		position:absolute;
		border-radius:30%;
	}	
	
	.SestiloEnviar{
		font-size:28px;
		cursor:pointer;
	}
	.SestiloEnviar:hover{
		color:#08D300;
	}
	
	.ConfigNF{
		width:30px !important;
		cursor:pointer;
			
	}
</style>


<div class="card" >
<div class="card-body">

		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>

			
		<?php
	    include("../../list/list_cliente.php");	
			
		$pergnta = "select a.* from clientes a
					where a.nome like _utf8 '%$busca%' COLLATE utf8_unicode_ci or 
						  a.cpf_cnpj like '%$busca%' or
						  a.email like '%$busca%'

					  ".$_SESSION[$Script][campo]."
					  ".$_SESSION[$Script][ordenar]."

					limit $pn,$pg ";
		$resultado = mysqli_query($conexao2, $pergnta);
		//echo $pergnta;			
			
		?>

		<div class="table-responsive" style="overflow-x:auto;">
        <form action="<?=$_SERVER["PHP_SELF"]?>" method="post" target="pagina" id="form_list_<?=$_SESSION[$Script][excluir]?>"  class="ListModulos">
        
          <table class="table table-striped">
            <thead >
              <tr >
                <th><?=$_SESSION[$Script][checkbox]?></th>
				<th > Serviço</th>
				<th > NF</th>
				<th > Enviar</th>
				<th > Status</th>
                <th campo ordem='a.ativo'><?=$_SESSION[$Script][ordem][$opi]?> Clientes</th>
                <th campo ordem='a.nome'><?=$_SESSION[$Script][ordem][$opi]?> Nome/E-mail</th>
                <th campo ordem='a.dia_vencimento'><?=$_SESSION[$Script][ordem][$opi]?> Dia vencimento</th>
                
				  <th campo ordem='a.frequencia'><?=$_SESSION[$Script][ordem][$opi]?> Frequência da cobrança</th> 
				  
              </tr>
            </thead>
            
         <tbody>
        <?php
        while($d = mysqli_fetch_object($resultado)){
			
		$AnoMes = date('Y-m',time());	
		list($servicos) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from servicos where id_cliente = '".$d->id."' and data_adesao LIKE '0000-00-00' or id_cliente = '".$d->id."' and data_adesao like '%$AnoMes%' "));

		list($CobrancaMes,$statusBol) = mysqli_fetch_row(mysqli_query($conexao2, "select dueDate,status from cobrancas where customer = '".$d->id_asaas."' and dueDate like '%$AnoMes%' "));	

		$dataHoje = date( "Y-m-d", time() ) ;
		if($CobrancaMes){
			$CobrancaMes2Dias = date("Y-m-d",strtotime($CobrancaMes . "+2 days"));	
		}else{
			$CobrancaMes2Dias = $CobrancaMes;
		}
		
			
		// Comparando as Datas	
		if(strtotime($CobrancaMes2Dias) < strtotime($dataHoje) and $statusBol=="PENDING" ){
		$ST = "Enviar segunda Via!";	
		$process = "far fa-copy EnviarSegundaVia";	
		$tituloCob = "Enviar segunda Via!";				
		}	
		elseif(strtotime($CobrancaMes2Dias) > strtotime($dataHoje) and $statusBol=="RECEIVED" ){
		$ST = "Serviço pago!";	
		$process = "fas fa-hands-helping";	
		$tituloCob = "Serviço pago!";				
		}
		elseif(strtotime($CobrancaMes2Dias) > strtotime($dataHoje)){
		//echo 'A data 1 é maior que a data 2. não enviar';
		$ST = "Aguardando Pagamento";	
		$process = "far fa-clock";	
		$tituloCob = "Aguardando Pagamento!";	
		}
		elseif(!$d->id_asaas){
		$ST = "Aguardando Liberação KEY";	
		$process = "far fa-clock";	
		$tituloCob = "Aguardando Liberação KEY!";	
		}
		elseif($d->dia_vencimento < date('d') ){
		$ST = "Dia vencimento menor que dia de Hoje";	
		$process = "far fa-clock";	
		$tituloCob = "Dia vencimento menor que dia de Hoje!";	
		}
		elseif($d->ano_mes != date('Y-m') ){
		$ST = "Esta fatura já foi processada neste mês";	
		$process = "far fa-clock";	
		$tituloCob = "Esta fatura já foi processada neste mês!";	
		}	
		else{
		$ST = "Enviar cobrança agora!";	
		$process = "far fa-paper-plane EnviarAgora";
		$tituloCob = "Enviar cobrança agora!";	
		//echo 'A data 1 é menor que a data 2. reenviar';	
		}			
			
	
			
			
        ?> 
              <tr class="AoclicarList" style="color:<?=(($d->ativo=='1')?'#FF0004':false)?>" >
                <td ><input pos<?=$d->id?> type="checkbox" name="CheckOpc[]" class="MT<?=$_SESSION[$Script][MT]?>" value="<?=$d->id?>|<?=$d->id_asaas?>" ></td>
				  
				<td>
					<img src="./img/servicos.png" class="Servicos" Cod="<?=$d->id."|".$d->id_asaas?>"  data-toggle="tooltip" data-placement="top" title="Lista de serviços!" >
					<span class="ServicosList" ><?=$servicos?> </span> 
				</td>

				 <td> <img data-toggle="tooltip" data-placement="left" title="Config nota fiscal para <?=($d->nome)?>"  src="./img/nf.png" class="ConfigNF" Cod="<?=$d->id?>"></td> 	
				  
			    <td><i class="<?=$process?> SestiloEnviar" Cod="<?=$d->id?>" CodSess="<?=$_SESSION[id_usuario]?>" Nome="<?=($d->nome)?>" data-toggle="tooltip" data-placement="top" title="<?=$tituloCob ?>"></i></td>	  
 
				  
				<td>
				<div id="Gcor<?=$d->id?>" class=" form-check form-check-<?=(($d->gerar=='0')?'warning':'success')?>">
				<label class="form-check-label">
				<input type="checkbox" id="AcaoG<?=$d->id?>" Cod="<?=$d->id?>"  class="GerarAuto form-check-input "  value="<?=(($d->gerar=='0')?'0':'1')?>"  <?=(($d->gerar=='0')?false:'checked')?>  >
				
					<i class="input-helper">
						<span id="Gat<?=$d->id?>"><?=(($d->gerar=='0')?'Manual':'Automático')?></span>
					</i> 
				</label>
				</div>  
				</td>  
				  
				<td Editar cod="<?=$d->id?>" ><?=(($d->ativo==0)?'Ativo':'Inativo')?> </td>

				  
				<td Editar cod="<?=$d->id?>" >
					<?=($d->nome)?><br>
					<span style="font-size:11px;"><?=($d->celular)?></span> <br>
					<span style="font-size:11px;"><?=($d->email)?></span> <br>
					<span style="font-size:11px;"><?=($d->id_asaas)?></span> 
			
			
			   </td>


				<td Editar cod="<?=$d->id?>" ><?=$d->dia_vencimento?> </td>

				  <td Editar cod="<?=$d->id?>" ><?=$d->frequencia?> </td> 
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

<div id="EnvioManual" style="display:none"></div>
<div id="SalvarGeral" style="display:none"></div>

<script language="javascript">

   	
$(".GerarAuto").click( function(){ 	
	var Cod = $(this).attr("Cod");
	var ckbox = $("#AcaoG"+Cod);
	if (ckbox.is(':checked')) {		
		$("#Gcor"+Cod).removeClass("form-check-warning");
		$("#Gcor"+Cod).addClass("form-check-success");		
		$("#Gat"+Cod).text("Automático");
		//alert('Checado '+Cod);
		$.ajax({
			type: "POST",
			url: "./<?=$_SESSION[$Script][url]?>.php",
			data: {id:Cod,gerar:'1'},
			success: function(data){
				$("#SalvarGeral").html(data);
			}
		});			
		
	}
	else{	
		$("#Gcor"+Cod).removeClass("form-check-success");
		$("#Gcor"+Cod).addClass("form-check-warning");
		$("#Gat"+Cod).text("Manual");
		//alert('Não Checado '+Cod);
		$.ajax({
			type: "POST",
			url: "./<?=$_SESSION[$Script][url]?>.php",
			data: {id:Cod,gerar:'0'},
			success: function(data){
				$("#SalvarGeral").html(data);
			}
		});		
		
	}
});	
	
	
	
$(".ConfigNF").click( function(){ 

	 let Cod = $(this).attr("Cod");	

	 ConfigNF = $.confirm({

		title: "Configurar nota fiscal de serviço",
		content: "url:./tabelas/clientes/config_nota_fiscal.php?CodNf="+Cod,
		columnClass:"col-md-6 col-md-offset-3",
		theme: "light",
		buttons: {
			Fechar: {
				btnClass: "btn-danger",
				action: function(){
				}
			}
		}				

	 });	

});	
	
$(".EnviarAgora").click( function(){ 
	
let cSess = $(this).attr('CodSess');
let c = $(this).attr('cod');
let n = $(this).attr('Nome');

 $.confirm({

	title: "<span style='color:red'>Atenção!</span>",
	content: "Deseja realmente enviar a cobrança para o cliente <b>"+n+"</b> agora?",
	columnClass:"col-md-4 col-md-offset-4",
	theme: "light",
	buttons: {
		Sim: {
			btnClass: "btn-success",
			action: function(){
				
				$.ajax({
					type: "POST",
					url: "./_mailer_6_1_whats/_envio_manual.php",
					data: {
						idUsuario:cSess,
						idCliente:c
					},
					success: function( data )
					{
						$("#EnvioManual").html(data)
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
	
function Enviado(){
	
$.confirm({
	title: "<span style='color:green'>Status!</span>",
	content: "Cobrança enviada com sucesso!",
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
	

$("td[Editar]").click( function(){ 

var c = $(this).attr('cod');

	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

		$.ajax({
		  url: './<?=$_SESSION[$Script][url]?>_form.php?op=editar&cod='+c,
		  success: function(data) {
		  $('#<?=$_SESSION[$Script][dialog]?>').html(data);
			 $("#CARREGANDO").html('');
			  $('body>.tooltip').remove();
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

	
$(".Servicos").click(function(){

	var cod = $(this).attr('Cod');
	
	$.confirm({
		title: "",
		content: "url:./tabelas/clientes/servicos/servicos.php?idCliente="+cod,
		columnClass:"col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1 ",
		theme: "light",
		buttons: {
			Fechar: {
				btnClass: "btn-dark",
				action: function(){
				}
			},
		}				
	  });	

});	
	
</script>