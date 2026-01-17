<?php
include("../../includes/connect.php");
include("../../includes/funcoes.php");

$Conf[script] = 'tabelas/contatos_agenda/contatos_agenda';
$Script = md5($Conf[script]);


$pergunta = "select * from contatos_agenda where id='".$_GET[cod]."' ";
$resultado = mysqli_query($conexao2, $pergunta);
$d = mysqli_fetch_object($resultado);



if(isset($_POST["alterar_".$Script])){
	
	mysqli_query($conexao2,"delete from cadastro_erro where destination='".$_POST[destination]."' ");		
	
$celular = explode(' ',$_POST[celular]);	
	
	$cells = $celular[0].$celular[1].''.$celular[2];
	 
		$query = "update contatos_agenda set  
		nome='".($_POST[nome])."',
		email='".$_POST[email]."',
		cod_pais='".$celular[0]."',
		cod_estado='".$celular[1]."',
		telefone='".$celular[2]."',
		data_nascimento='".($_POST[data_nascimento])."',
		destination= '".$cells."',
		situacao='".$_POST[situacao]."'
		where id='".$_POST[cod]."'
		";
		$result = mysqli_query($conexao2, $query);

		echo "<script>parent.retornar_$Script('".$_POST[cod]."')</script>";
		exit();
}
	
	

if(isset($_POST["salvar_".$Script])){	
	
$celular = explode(' ',$_POST[celular]);	
	
	$cells = $celular[0].$celular[1].''.$celular[2];
	
		$query = "insert into contatos_agenda set 
		nome='".($_POST[nome])."',
		email='".$_POST[email]."',
		cod_pais='".$celular[0]."',
		cod_estado='".$celular[1]."',
		telefone='".$celular[2]."',
		data_nascimento='".($_POST[data_nascimento])."',
		destination= '".$cells."',
		situacao='".$_POST[situacao]."'
		";
		$result = mysqli_query($conexao2, $query);												
		
		echo "<script>parent.retornar_$Script('".$_POST[cod]."')</script>";
		exit();
}


?>



<style>
	.day{
		color:#FFF !important;
	}
	.day:hover{
		color:#000 !important;
		background: rgba(123,180,37, 1.00) !important; /* Old browsers */
		background: -moz-linear-gradient(top,  rgba(123,180,37, 1.00), rgba(123,180,37, 1.00) 50%, rgba(123,180,37, 1.00)) !important; /* FF3.6-15 */
		background: -webkit-linear-gradient(top,  rgba(123,180,37, 1.00), rgba(123,180,37, 1.00) 50%,rgba(123,180,37, 1.00)) !important; /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom,  rgba(123,180,37, 1.00), rgba(123,180,37, 1.00) 50%,rgba(123,180,37, 1.00)) !important; /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='rgba(123,180,37, 1.00)', endColorstr='rgba(123,180,37, 1.00)',GradientType=0 ) !important; /* IE6-9 */		
	}
	.switch{
		color:#FFF !important;
	}
	.switch:hover{
		color:#000 !important;
	}
	.prev{
		color:#FFF !important;
	}
	.prev:hover{
		color:#000 !important;
	}
	.next{
		color:#FFF !important;
	}
	.next:hover{
		color:#000 !important;
	}
	.dow{
		color:#FFF !important;
	}
	.old{
		color:#908E8E !important;
	}
	.new{
		color:#908E8E !important;
	}	
	.month{
		color:#FFF !important;
	}
	.month:hover{
		color:#000 !important;
	}
	
	.year{
		color:#FFF !important;
	}
	.year:hover{
		color:#000 !important;
	}
	
	.old{
		color:#908E8E !important;
	}
	.old:hover{
		color:#000 !important;
	}
	
	
	.datepicker {
		background: rgba(0, 0, 0, 2.60) !important; /* Old browsers */
		background: -moz-linear-gradient(top,  rgba(0, 0, 0, 2.60), rgba(0, 0, 0, 2.60) 50%, rgba(0, 0, 0, 2.60)) !important; /* FF3.6-15 */
		background: -webkit-linear-gradient(top,  rgba(0, 0, 0, 2.60), rgba(0, 0, 0, 2.60) 50%,rgba(0, 0, 0, 2.60)) !important; /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom,  rgba(0, 0, 0, 2.60), rgba(0, 0, 0, 2.60) 50%,rgba(0, 0, 0, 2.60)) !important; /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='rgba(0, 0, 0, 2.60)', endColorstr='rgba(0, 0, 0, 2.60)',GradientType=0 ) !important; /* IE6-9 */				
	}
	
</style>


<div class="card" >
<div class="card-body">
		
		<div class="panel-heading">
			<?=($_SESSION[$Script][titulo])?>
		</div>

		
		
        <form id="ValidarContatos" action="<?=$Conf[script]?>_form.php" method="post" target="pagina" enctype="multipart/form-data" >
        
         
			    <div class="row">   
			
					<div class="col-lg-6" style="padding:1px">
						 <span class="TituloForms">Nome</span><br>
						  <i class="far fa-copyright Icons"></i>
						  <p><input InputForm id="nome" type="text" name="nome" class="form-control" placeholder="Nome"  value="<?=($d->nome)?>" /></p>
					</div>


					<?php $celular = $d->cod_pais.' '.$d->cod_estado.' '.$d->telefone; ?>
					<div class="col-lg-6" style="padding:1px">
						 <span class="TituloForms">Celular</span><br>
						  <i class="far fa-copyright Icons"></i>
						  <p><input InputForm id="celular" type="text" name="celular" class="form-control" placeholder="Celular"  value="<?=(($celular>0)?$celular:false)?>" /></p>
					</div>
					
                </div> 
			
			    <div class="row">  
				<script>   
				/*$('#data_nascimento').datepicker({
					format: 'dd/mm/yyyy',                
					language: 'pt-BR',
					dayNames: [ "Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado" ],
					dayNamesMin: [ "Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab" ],
					monthNames: [ "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" ],
					monthNamesShort: [ "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez" ]		
				});*/                
				</script> 
				 <div class="col-lg-4" style="padding:2; border: #FF0004 solid 0px;">
					 <span class="TituloForms">Data nascimento</span><br>
					  <i class="far fa-copyright Icons"></i>
					  <p><input InputForm id="data_nascimento" type="date" name="data_nascimento" class="form-control" placeholder="Data nascimento"  value="<?=($d->data_nascimento)?>" /></p>
				 </div>	
                       
                
                <div class="col-lg-4" style="padding:1px">
                     <span class="TituloForms">E-mail</span><br>
				   	  <i class="far fa-copyright Icons"></i>
                      <p><input InputForm id="email" type="text" name="email" class="form-control" placeholder="E-mail"  value="<?=$d->email?>" /></p>
                </div>
        
			
                <div class="col-lg-4" style="padding:1px">
                <span class="TituloForms">Situação</span><br>	   
                     <i class="far fa-copyright Icons"></i> 
                      <select InputForm id="situacao" name="situacao" class="form-control"> 
						<option InputForm value="0" <?=(($d->situacao == 0)?'selected':false)?>>Liberado</option>
  						<option InputForm value="1" <?=(($d->situacao == 1)?'selected':false)?>>Bloqueado</option>
                      </select>
            	</div>

                </div> 
					
	 <div class="row"> 

	  <button type="submit" id="salvar" name="<?=(($_GET[op]=='novo') ? 'salvar_'.$Script : 'alterar_'.$Script)?>" class="btn btn-success"><?=(($_GET[op]=='novo') ? 'Salvar' : 'Alterar')?></button>                  

	  <button type="button" id="cancelar_<?=$Script?>" name="cancelar" class="btn btn-danger">Cancelar</button>                  

	 </div>
	 <input type="hidden" name="destination" value="<?=$d->destination?>" />

	  <input type="hidden" id="cod_<?=$Script?>" name="cod" value="<?=$_GET[cod]?>" />
        
        
     </form>	


</div>
</div>

<script type="text/javascript" charset="iso-8859-1">
	
$('#ValidarContatos').validate({
	rules : {
		nome : {
			required : true
		},
		celular : {
			required : true,
			minlength: 15
		}

	},
	messages : {
		nome : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o nome.'
		},
		celular : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o celular.</div>',
			minlength: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Esta faltando número.</div>'
		}
	}

});		
	

		//opção de cancelar
		$("#cancelar_<?=$Script?>").click(function(){

		       $("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');
				$.ajax({
					url: './<?=$Conf[script]?>.php',
					success: function(data) {
					$('#<?=$_SESSION[$Script][dialog]?>').html(data);
						$("#CARREGANDO").html('');
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
								}
							}); 					

						}
					},
				}
			  }); 
 
		}	 

	
	
	
	
//$('#data_nascimento').mask('00/00/00');	
$('#celular').mask('55 00 000000000');
	
</script>