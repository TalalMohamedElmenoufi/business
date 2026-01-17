<?php
include('../../includes/connect.php');
include('../../includes/funcoes.php');


list($id_usuario) = mysqli_fetch_row(mysqli_query($conexao, "select id_usuario from server "));

$pergunta = "select a.* from usuarios a
where id='".$_SESSION[id_usuario]."' ";
$resultado = mysqli_query($conexao, $pergunta);	
$d = mysqli_fetch_object($resultado); 




?>
<style>

.toggle {
    margin-bottom: 20px;
}

.toggle > input {
    display: none;
}

.toggle > label {
    position: relative;
    display: block;
    height: 28px;
    width: 52px;
    background-color: #E5E2E2;
    border: 1px #F80303 solid;
    border-radius: 100px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.toggle > label:after {
    position: absolute;
    left: 1px;
    top: 1px;
    display: block;
    width: 26px;
    height: 26px;
    border-radius: 100px;
    background: #fff;
    box-shadow: 0px 3px 3px rgba(0,0,0,0.05);
    content: '';
    transition: all 0.3s ease;
}
.toggle > label:active:after {
    transform: scale(1.15, 0.85);
}
.toggle > input:checked ~ label {
    background-color: #005acc;
    border-color: #005acc;
}
.toggle > input:checked ~ label:after {
    left: 25px;
}
.toggle > input:disabled ~ label {
    background-color: #d5d5d5;
    pointer-events: none;
}
.toggle > input:disabled ~ label:after {
    background-color: rgba(255, 255, 255, 0.3);
}
	
	

.danger {
  background-color: #ffdddd;
  border-left: 6px solid #f44336;
}

.success {
  background-color: #ddffdd;
  border-left: 6px solid #4CAF50;
}

.info {
  background-color: #e7f3fe;
  border-left: 6px solid #2196F3;
}


.warning {
  background-color: #ffffcc;
  border-left: 6px solid #ffeb3b;
}	
	

	
	.IniciaQr{
		cursor: pointer;
		border: #FFFFFF solid 1px;
	}	
	.IniciaQr:hover{
		border: #04DD08 solid 1px;
	}
	
	.attempt{
		font-family:arbutus ;
		float: left;
		padding:1px;
		margin-top:-7px;
		font-size:20px;
		font-weight: bold;
		color: #FF0004;
		margin-left: 10px;
		animation: animate 1.5s linear infinite;
		
	}
	 @keyframes animate{
	   0%{
		 opacity: 0;
	   }
	   50%{
		 opacity: 1;
	   }
	   100%{
		 opacity: 0;
	   }
	 }	
	
	
	#VerQr{
		padding:25px;
	}
	
</style>  

<div class="card" >
<div class="card-body">
				

	<div class="panel-heading text-center">
		<h3>Conectar ao whatsapp</h3>
	</div>	
	
	
	<div class="row">
		
		
		<div class="col-lg-5 col-md-5 text-center" style="padding:10px;">
			
			
			<div id="StAPI"></div>
			
			<div id="QrCod" style="display:none"><img id="VerQr" ></div>
			
			<div class="StartInicial" style="display:none">
				<img src="./img/inicializando.gif" width="220">
			</div>
			
			<div class="refresh" style="display:none">
				<img src="./img/refresh.png" width="220" class="IniciaQr">
			</div>			
			
			
			<div class="success" id="Conectado" style="display:none">
				<img src="./img/conectado.jpg" width="220">
			</div>	
		
			<!--<div class="success" id="Manutencao" style="height:40px;" >
				<center>EM MANUTENÇÃO</center>	
			</div>-->	
			
		</div>	
			
		
		<div class="col-lg-7 col-md-7 text-center" style="padding:10px;">
			
			<h2><i class="fas fa-info-circle"></i> Info whatsapp</h2>


			<div class="danger" id="danger" style="display:none">
			  <p><strong>BOT desconectado! </strong> Dispositivo OFF ...</p>
			</div>

			<div class="secondary" id="secondary" style="display:none">
			  <p><strong>BOT status! </strong> Dispositivo EMPARELHAMENTO ...</p>
			</div>

			<div class="success" id="success" style="display:none">
			  <p><strong>BOT conectado! </strong> Dispositivo ON ...</p>
			</div>

			<div class="info" id="info" style="display:none">
			  <p><strong>Dispositivo!</strong> TIMEOUT OFF line ...</p>
			</div>

			<div class="info" id="pendente" style="display:none">
			  <p><span class="attempt"  ></span> <strong>Dispositivo!</strong> AGUARDE QR PENDENTE ...</p>
			</div>

			<div class="warning" id="Inicializando" style="display:none">
			  <p><strong>BOT </strong> Inicializando ...</p>
			</div>

			<div class="warning" id="warning" style="display:none">
			  <p> <strong>Dispositivo!</strong> OFF line ...</p>
			</div>
			
		</div>		
		
		
	</div>	
		
	

</div>
</div>	

<script>
$(".StartInicial").css("display","block");
$("#Inicializando").css("display","block");		

	
$(".IniciaQr").click(function(){
	
	$("#warning").css("display","none");
	$(".refresh").css("display","none");
	$(".StartInicial").css("display","block");
	$("#Inicializando").css("display","block");	
	$.ajax({
	url: "./tabelas/whatsapp/acoes/onzap.php",
	success: function(data) {
		$("#StAPI").html(data);
	}
	});	
	
});	
	
function LerStatus() {
  LerSt = 
  setInterval(
	function(){  
		$.ajax({
		url: "./tabelas/whatsapp/acoes/ler_status_qr.php",
		success: function(data) {

			var res = data.split(",");
			var st = res[0];
			var qrPart1 = res[1];
			var qrPart2 = res[2];
			let qr = qrPart1+','+qrPart2;
			
			const Cor = 
					((res[3]==1)
					? '18a805' : 
					((res[3]==2)
					? 'd9f616' :
					((res[3]==3)
					? '5746fb' :
					((res[3]==4)
					? '18a805' :
					((res[3]==5)
					? 'd9f616' :
					((res[3]==6)
					? '5746fb' : 
					'18a805'
					))))));
			 
			$("#VerQr").css("border" ,"#"+Cor+" solid 8px");
			
            $(".attempt").text( '0'+res[3] );
			
			if(st=="CONNECTED"){
				$("#secondary").css("display","none");
				$("#danger").css("display","none");
				$("#success").css("display","block");
				$("#info").css("display","none");
				$("#warning").css("display","none");
				$("#pendente").css("display","none");
				$("#Inicializando").css("display","none");
				$("#QrCod").css("display","none");
				$("#Conectado").css("display","block");
				
				$(".OnZapp").css({'background-color': '#00D004'});
				$(".OnZapp").css("border", "#00D004 solid 1px"); 
				$(".StartInicial").css("display","none");	
				$(".refresh").css("display","none");
			}
			else if(st=="CONFLICT"){

				$("#secondary").css("display","none");
				$("#danger").css("display","none");
				$("#success").css("display","block");
				$("#info").css("display","none");
				$("#warning").css("display","none");
				$("#pendente").css("display","none");
				$("#Inicializando").css("display","none");
				$("#QrCod").css("display","none");
				$("#Conectado").css("display","block");
				
				$(".OnZapp").css({'background-color': '#00D004'});
				$(".OnZapp").css("border", "#00D004 solid 1px"); 
				$(".StartInicial").css("display","none");	
				$(".refresh").css("display","none");

			}
			else if(st=="TIMEOUT"){

				$("#secondary").css("display","none");
				$("#danger").css("display","none");
				$("#success").css("display","none");
				$("#info").css("display","block");
				$("#warning").css("display","none");
				$("#pendente").css("display","none");
				$("#Inicializando").css("display","none");
				$("#QrCod").css("display","none");
				$("#Conectado").css("display","none")
				
				$(".OnZapp").css({'background-color': '#B1C517'});
				$(".OnZapp").css("border", "#B1C517 solid 1px"); 
				$(".StartInicial").css("display","none");		
				$(".refresh").css("display","none");

			}
			else if(st=="PAIRING"){
				
				$("#VerQr").attr("src",qr);
				$("#QrCod").css("display","block");
				$("#secondary").css("display","block");
 				$("#pendente").css("display","block");
				$("#danger").css("display","none");
				$("#success").css("display","none");
				$("#warning").css("display","none");
				$("#Inicializando").css("display","none");
				$("#info").css("display","none");
				$("#Conectado").css("display","none")
				
				$(".OnZapp").css({'background-color': '#B1C517'});
				$(".OnZapp").css("border", "#B1C517 solid 1px"); 
				$(".StartInicial").css("display","none");
				$(".refresh").css("display","none");
				
	
			}
			else if(st=="UNPAIRED"){
				$("#secondary").css("display","none");
				$("#danger").css("display","none");
				$("#success").css("display","none");
				$("#info").css("display","none");
				$("#warning").css("display","block");
				$("#pendente").css("display","none");
				$("#Inicializando").css("display","none");
				$("#QrCod").css("display","none");
				$("#Conectado").css("display","none")
				
				$(".OnZapp").css({'background-color': '#FF0004'});
				$(".OnZapp").css("border", "#FF0004 solid 1px");				
				$(".StartInicial").css("display","none");	
				$(".refresh").css("display","block");

			}
			else if(st=="UNPAIRED_IDLE"){
				$("#secondary").css("display","none");
				$("#danger").css("display","none");
				$("#success").css("display","none");
				$("#info").css("display","none");
				$("#warning").css("display","block");
				$("#pendente").css("display","none");
				$("#Inicializando").css("display","none");
				$("#QrCod").css("display","none");
				$("#Conectado").css("display","none")
				
				$(".OnZapp").css({'background-color': '#FF0004'});
				$(".OnZapp").css("border", "#FF0004 solid 1px");				
				$(".StartInicial").css("display","none");	
				$(".refresh").css("display","block");

			}
			else if(st=="OFFLINE"){
				$("#secondary").css("display","none");
				$("#danger").css("display","none");
				$("#success").css("display","none");
				$("#info").css("display","none");
				$("#warning").css("display","block");
				$("#pendente").css("display","none");
				$("#Inicializando").css("display","none");
				$("#QrCod").css("display","none");
				$("#Conectado").css("display","none")
				
				$(".OnZapp").css({'background-color': '#FF0004'});
				$(".OnZapp").css("border", "#FF0004 solid 1px");				
				$(".StartInicial").css("display","none");	
				$(".refresh").css("display","block");
				
			}
			else if(st=="DISCONNECTED"){
				$("#secondary").css("display","none");
				$("#danger").css("display","none");
				$("#success").css("display","none");
				$("#info").css("display","none");
				$("#warning").css("display","block");
				$("#pendente").css("display","none");
				$("#Inicializando").css("display","none");
				$("#QrCod").css("display","none");
				$("#Conectado").css("display","none")
				
				$(".OnZapp").css({'background-color': '#FF0004'});
				$(".OnZapp").css("border", "#FF0004 solid 1px");				
				$(".StartInicial").css("display","none");	
				$(".refresh").css("display","block");
				
			}
			else if(st=="PEND QRCODE"){
				$("#secondary").css("display","none");
				$("#danger").css("display","none");
				$("#success").css("display","none");
				$("#info").css("display","none");
				$("#warning").css("display","none");
				$("#pendente").css("display","block");
				$("#Inicializando").css("display","none");
				$("#QrCod").css("display","none");
				$("#Conectado").css("display","none")
				
				$(".OnZapp").css({'background-color': '#B1C517'});
				$(".OnZapp").css("border", "#B1C517 solid 1px");	
				$(".StartInicial").css("display","none");	
				$(".refresh").css("display","none");

			}
			else if(st=="START"){
				$("#secondary").css("display","none");
				$("#danger").css("display","none");
				$("#success").css("display","none");
				$("#info").css("display","none");
				$("#warning").css("display","none");
				$("#pendente").css("display","none");
				$("#Inicializando").css("display","block");
				$("#QrCod").css("display","none");
				$("#Conectado").css("display","none")
				
				$(".OnZapp").css({'background-color': '#FF0004'});
				$(".OnZapp").css("border", "#FF0004 solid 1px");				
				$(".StartInicial").css("display","block");	
				$(".refresh").css("display","none");
				 
			}
			
			
			
			
				}
				})
			}
		  , 5000)

		}	
	
		LerStatus();	
</script>