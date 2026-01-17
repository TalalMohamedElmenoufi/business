<?php
if($_SESSION[id_status]=="ADM"){
	$perguntaUser = "select id, nome, sexo, foto_perfil, perfil, status_whats_desc from usuarios where id = '".$_SESSION[id_usuario]."' ";
	$resultadoUser = mysqli_query($conexao, $perguntaUser);	
	$pastaInt = "sistema";
}
elseif($_SESSION[id_status]=="USER"){
	$perguntaUser = "select id, nome, sexo, foto_perfil, perfil from login_acesso where id = '".$_SESSION[id_user]."' ";
	$resultadoUser = mysqli_query($conexao2, $perguntaUser);
	$pastaInt = "sistema/user";
}


$d = mysqli_fetch_object($resultadoUser);

$nomeDb = explode(" ",$d->nome);
$nome = $nomeDb[0] ;
$SobNome = $nomeDb[1] ;

$pasta = acentos($nome) ;
$pasta = $d->id."_".strtolower($pasta) ;

$Vperfil = explode('|',$d->perfil);

$stWhats = (($d->status_whats_desc=='CONNECTED')?'00D004': (($d->status_whats_desc=='OFFLINE')?'FF0004':'B1C517') );
	
$dataHoje = date('m-d',time());	
list($niver) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where data_nascimento like '%$dataHoje%' and tel_tipo = 'Invalido' or data_nascimento like '%$dataHoje%' and tel_tipo = 'WhatsApp' "));	

list($ContaBanco) = mysqli_fetch_row(mysqli_query($conexao, "select stDoc from asaas where id_usuario = '".$_SESSION[id_usuario]."' "));

list($Transferencias) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_transferencia) from minhas_transferencias where status = 'PENDING' "));

$AnoMes = date('Y-m',time());
list($Cobrancas) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id_rg) from cobrancas where dueDate like '%$AnoMes%' and status = 'PENDING' or dueDate like '%$AnoMes%' and status = 'OVERDUE' "));


$cobrancasExternas = "select * from usuarios where id !='1' ";
$RcobrancasExternas = mysqli_query($conexao,$cobrancasExternas);
while($C = mysqli_fetch_object($RcobrancasExternas)){

$con = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$C->id);	
	
list($cobrancaSms) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_sms where dueDate like '%$AnoMes%' and status = 'PENDING' or dueDate like '%$AnoMes%' and status = 'OVERDUE' "));	
	
list($cobrancaWhatsapp) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_whatsapp where dueDate like '%$AnoMes%' and status = 'PENDING' or dueDate like '%$AnoMes%' and status = 'OVERDUE' "));
	
list($cobrancaMeil) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_email where dueDate like '%$AnoMes%' and status = 'PENDING' or dueDate like '%$AnoMes%' and status = 'OVERDUE' "));		
	
list($cobrancaPesquisa) = mysqli_fetch_row(mysqli_query($con, "select count(id_rg) from asaas_cobranca_pesquisa where dueDate like '%$AnoMes%' and status = 'PENDING' or dueDate like '%$AnoMes%' and status = 'OVERDUE' "));
	
	
$CobrancasEx  += $cobrancaSms ;
$CobrancasEx2 += $cobrancaWhatsapp ;
$CobrancasEx3 += $cobrancaMeil ;
$CobrancasEx4 += $cobrancaPesquisa ;
	
}

?>
<style>
	.SairSis{
		cursor:pointer;
	}
	.SairSis:hover{
		color:#F80004;
	}

	.OnZapp{
		border:#<?=$stWhats?> solid 1px;
		background:#<?=$stWhats?>;
		width:6px;
		height:6px;
		position:absolute;
		border-radius:20px;
	}
	
	.QtNiver{
		border: rgba(0, 0, 0, 0.40) solid 1px;
		margin-top:-15px;
		padding:2px;
		font-size:11px;
		color:#DF0003;
		width:auto;
		height:auto;
		position:absolute;
		border-radius:30%;
	}
	
	.QtAtendimento{
		float:right;
		margin-top:-28px;
		margin-left:15px;
		width:20px;
		position:absolute;
	}
	span[QTANT]{
		display:none;
	}
	
	.ContaBanco{
		border: rgba(0, 0, 0, 0.40) solid 0px;
		margin-top:-6px;
		padding:2px;
		font-size:11px;
		color:#016F03;
		width:auto;
		height:auto;
		position:absolute;
		border-radius:30%;
	}
	
	.Cobrancas{
		border: rgba(0, 0, 0, 0.40) solid 1px;
		margin-top:-15px;
		padding:2px;
		font-size:11px;
		color:#DF0003;
		width:auto;
		height:auto;
		position:absolute;
		border-radius:30%;
	}
	
	.Transferencias{
		border: rgba(0, 0, 0, 0.40) solid 1px;
		margin-top:-15px;
		padding:2px;
		font-size:11px;
		color:#010072;
		width:auto;
		height:auto;
		position:absolute;
		border-radius:30%;
	}	
	
	
</style>

        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image imgPerfil">
				  <div id="FotoPerfil" class="EditFperfil" TipoLog="<?=$_SESSION[id_status]?>" >Editar</div>	
                  <img alt="<?=$nome?>" src="<?=(($d->foto_perfil)?'img/'.$pastaInt.'/'.$pasta.'/perfil/'.$d->foto_perfil:'assets/img/'.(($d->sexo=='M')?'user':'user_f').'.gif')?>" alt="profile" class="RetornaPerfil">
                  <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2"><?=($nome)?></span>
                  <span class="text-secondary text-small"><?=($SobNome)?></span>
				  <span class="text-secondary text-small" id="Atializando"></span>	
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
			  
            <li class="nav-item">
              <a class="nav-link" href="#" Acao caminho="./paginas/pagina_inicial.php" perfil="graficos">
                <span class="menu-title">Home</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
			  
            <li class="nav-item" style="display:<?=((in_array('financeiro_geral',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/financeiro/financeiro_geral.php" perfil="financeiro_geral">
                <span class="menu-title">Financeiro Geral</span>
                <i class="far fa-money-bill-alt menu-icon"></i>
              </a>
            </li>			  
			  
			  
			  
            <li class="nav-item"  >
              <a class="nav-link" data-toggle="collapse" href="#GraficosSis" aria-expanded="false" aria-controls="GraficosSis" >
                <span class="menu-title">Dashboard</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-chart-pie menu-icon"></i>
              </a>
              <div class="collapse" id="GraficosSis">
                <ul class="nav flex-column sub-menu">
					
					 
					<li class="nav-item" style="display:<?=((in_array('grfico_financeiro',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/financeiro/financeiro.php" perfil="grfico_financeiro">Financeiro</a></li>
					
					
					<li class="nav-item" style="display:<?=((in_array('graficos',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/graficos/graficos.php" perfil="graficos">Dashboard Pesquisas</a></li>
					
					
                    <li class="nav-item" style="display:<?=((in_array('graficos',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/graficos_sms/graficos_sms.php" perfil="graficos">Dashboard SMS</a></li>
					
					
                    <li class="nav-item" style="display:<?=((in_array('graficos',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/graficos_email/graficos_email.php" perfil="graficos">Dashboard E-MAIL</a></li>

                </ul>
              </div>
            </li>				  
			  
			  
			  
			  
            <li class="nav-item"  >
              <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic" >
                <span class="menu-title">Cadastros</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
					
					<li class="nav-item" style="display:<?=((in_array('server_config',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./paginas/emoji.php" perfil="server_config">Emoji</a></li>
					
					
                  <li class="nav-item" style="display:<?=((in_array('asaas',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/asaas/asaas.php" perfil="asaas">Api asaas</a></li>
					
                  <li class="nav-item" style="display:<?=((in_array('server_config',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/server/server.php" perfil="server_config">Servidor de configuração</a></li>
					
				 <li class="nav-item" style="display:<?=((in_array('termo',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/termo/termo.php" perfil="termo">Termo</a></li>	
					
                  <li class="nav-item" style="display:<?=((in_array('usuarios',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/usuarios/usuarios.php" perfil="usuarios">Usuários - sis</a></li>	
					
                  <li class="nav-item" style="display:<?=((in_array('login_acesso',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/login_acesso/login_acesso.php" perfil="login_acesso">Login acesso</a></li>	
					 
                  <li class="nav-item" style="display:<?=((in_array('clientes',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/clientes/clientes.php" perfil="clientes">Clientes</a></li>
				  
                  <li class="nav-item" style="display:<?=((in_array('seus_contatos',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/categoria_cadastro/categoria_cadastro.php" perfil="seus_contatos">Contatos marketing</a></li>
					
				  <li class="nav-item" style="display:<?=((in_array('contatos_agenda',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/contatos_agenda/contatos_agenda.php" perfil="contatos_agenda">Contatos agenda</a></li>
					
				  <li class="nav-item" style="display:<?=((in_array('contatos_agenda',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/contatos_agenda_bot/contatos_agenda_bot.php" perfil="contatos_agenda">Contatos agenda bot</a></li>	
					
                </ul>
              </div>
            </li>				  
			  
			  
			  
            <li class="nav-item" style="display:<?=((in_array('atendimento',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/atendimento/atendimento.php" perfil="atendimento">
                <span class="menu-title">Atendimento</span>

				  <i class="fab fa-teamspeak menu-icon"><span QTANT><img class="QtAtendimento" src="./img/what_msg.gif" ></span> </i>
				  
              </a>
				
            </li>					  
			  
			  
			  
            <li class="nav-item" style="display:<?=((in_array('aniversariantes',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/aniversariantes/aniversariantes.php" perfil="aniversariantes">
                <span class="menu-title">Aniversariantes</span>
                <i class="fas fa-birthday-cake menu-icon">
				  <span class="QtNiver" ><?=$niver?></span>
				</i>
              </a>
            </li>		
			  
            <li class="nav-item" style=" display:<?=((in_array('conta_bancaria',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/conta_bancaria/conta_bancaria.php" perfil="conta_bancaria">
                <span class="menu-title">Conta bancaria</span>
                <i class="mdi mdi-cash-multiple menu-icon" data-toggle="tooltip" data-placement="top" title="Conta bancaria <?=(($ContaBanco=='APPROVED')?'Aprovada':'Pendente')?> ">
				  <span class="ContaBanco" ><?=(($ContaBanco=='APPROVED')?'<i class="fas fa-check"></i>':'<img src="./img/alerta_mini.gif" width="13" />')?></span>
				</i>				  
              </a>
            </li>
			  
			  
			  
			  
			  
			  
			  
			  
            <li class="nav-item" style="display:<?=((in_array('cobrancas',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/cobrancas/cobrancas.php" perfil="cobrancas">
                <span class="menu-title">Emissão de cobranças</span>
                <i class="fas fa-hand-holding-usd menu-icon">
				  <span class="Cobrancas" ><?=$Cobrancas?></span>
				</i>
              </a>
            </li>  
			  
            <li class="nav-item" style="display:<?=((in_array('Transferencias',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/minhas_transferencias/minhas_transferencias.php" perfil="Transferencias">
                <span class="menu-title">Minhas transferências</span>
                <i class="fas fa-hand-holding-usd menu-icon">
				  <span class="Transferencias" ><?=$Transferencias?></span>
				</i>
              </a>
            </li>			  
			  
			  
			  
            <li class="nav-item"  >
              <a class="nav-link" data-toggle="collapse" href="#Faturas" aria-expanded="false" aria-controls="Faturas" >
                <span class="menu-title">Minhas Faturas</span>
                <i class="menu-arrow"></i>
                <i class="fas fa-hand-holding-usd menu-icon"></i>
              </a>
              <div class="collapse" id="Faturas">
                <ul class="nav flex-column sub-menu">

					
                  <li class="nav-item" style="display:<?=((in_array('seus_contatos',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/minhas_faturas/faturas_whatsapp.php" perfil="seus_contatos">Faturas WhatsApp</a></li>	
					
                  <li class="nav-item" style="display:<?=((in_array('seus_contatos',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/minhas_faturas/faturas_sms.php" perfil="seus_contatos">Faturas SMS</a></li>	
					
                  <li class="nav-item" style="display:<?=((in_array('seus_contatos',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/minhas_faturas/faturas_email.php" perfil="seus_contatos">Faturas E-mail</a></li>	
					
                  <li class="nav-item" style="display:<?=((in_array('seus_contatos',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/minhas_faturas/faturas_pesquisas.php" perfil="seus_contatos">Faturas Pesquisas</a></li>

                </ul>
              </div>
            </li>			  
			  

			  
			  
			  
            <li class="nav-item" style="display:<?=((in_array('cobrancas_externas',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/cobrancas_externas/cobrancas_externas_sms.php" perfil="cobrancas_externas">
                <span class="menu-title">Cobranças SMS</span>
                <i class="fas fa-hand-holding-usd menu-icon">
				  <span class="Cobrancas" ><?=$CobrancasEx?></span>
				</i>
              </a>
            </li>
			  
            <li class="nav-item" style="display:<?=((in_array('cobrancas_externas',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/cobrancas_externas/cobrancas_externas_whats.php" perfil="cobrancas_externas">
                <span class="menu-title">Cobranças WHATs</span>
                <i class="fas fa-hand-holding-usd menu-icon">
				  <span class="Cobrancas" ><?=$CobrancasEx2?></span>
				</i>
              </a>
            </li>			  
			  
			  
            <li class="nav-item" style="display:<?=((in_array('cobrancas_externas',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/cobrancas_externas/cobrancas_externas_marketing.php" perfil="cobrancas_externas">
                <span class="menu-title">Cobranças Marketing</span>
                <i class="fas fa-hand-holding-usd menu-icon">
				  <span class="Cobrancas" ><?=$CobrancasEx3?></span>
				</i>
              </a>
            </li>			  
			  
			  
			   
            <li class="nav-item" style="display:<?=((in_array('agenda',$Vperfil))?'block':'none')?>"   >
              <a class="nav-link" href="#" Acao caminho="./tabelas/agenda/agenda.php" perfil="agenda">
                <span class="menu-title">Sua Agenda</span>
                <i class="far fa-clock menu-icon"></i>
              </a>
            </li>
			  
			  
            <li class="nav-item" style="display:<?=((in_array('mailmarketing',$Vperfil))?'block':'none')?>"   >
              <a class="nav-link" href="#" Acao caminho="./tabelas/mailmarketing/mailmarketing.php" perfil="mailmarketing">
                <span class="menu-title">Marketing</span>
                <i class="far fa-envelope menu-icon"></i>
              </a>
            </li>			  
			  

			  
			  
            <li class="nav-item" style="display:<?=((in_array('perguntas_bot',$Vperfil))?'block':'none')?>"  >
              <a class="nav-link" href="#" Acao caminho="./tabelas/pesquisa_bot/pesquisa_bot.php" perfil="perguntas_bot">
                <span class="menu-title">Pesquisas bot</span>
                <i class="mdi mdi-buffer menu-icon"></i>
              </a>
            </li>

			<li class="nav-item" style="display:<?=((in_array('whatsapp_conectar',$Vperfil))?'block':'none')?>">
              <a class="nav-link" href="#" Acao caminho="./tabelas/whatsapp/conectar.php" perfil="whatsapp_conectar">
                <span class="menu-title">Conectar ao whatsapp</span>

				  <i class="mdi mdi-whatsapp menu-icon">
				  	<span class="OnZapp"></span>
				  </i>
				   

              </a>
            </li>  
 
			  
            <li class="nav-item"  >
              <a class="nav-link" data-toggle="collapse" href="#GrupoEnvios" aria-expanded="false" aria-controls="GrupoEnvios" >
                <span class="menu-title">Envio de mensagens</span>
                <i class="menu-arrow"></i>
                <i class="far fa-paper-plane menu-icon"></i>
              </a>
              <div class="collapse" id="GrupoEnvios">
                <ul class="nav flex-column sub-menu">
					
					<li class="nav-item" style="display:<?=((in_array('whatsapp_conectar',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/whatsapp/agendar_enviar.php" perfil="whatsapp_conectar">Envio por whatsApp</a></li>
					
					
                  <li class="nav-item" style="display:<?=((in_array('sms',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/sms/agendar_enviar.php" perfil="sms">Envio por SMS</a></li>

					
				  <li class="nav-item" style="display:<?=((in_array('envioMarketing',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/envio_mailer/envio_mailer.php" perfil="envioMarketing">Envio e-mail Marketing</a></li>
					

					
                </ul>
              </div>
            </li>				  
			  
			  
			  
			<div class="dropdown-divider"></div>  
			  
			  
			  
  
			  		  
			  
			 
            <li class="nav-item"  >
              <a class="nav-link" data-toggle="collapse" href="#Integracao" aria-expanded="false" aria-controls="ui-basic" >
                <span class="menu-title">API de integração</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
              <div class="collapse" id="Integracao">
                <ul class="nav flex-column sub-menu">
					
					<li class="nav-item" style="display:<?=((in_array('integracao',$Vperfil))?'block':'none')?>" > <a class="nav-link" href="#" Acao caminho="./tabelas/integracao/integracao.php" perfil="integracao">Informações da API</a></li>

					
                </ul>
              </div>
            </li>			  
			  
			  
			  
			  
            <li class="nav-item sidebar-actions">
              <span class="nav-link">
                <div class="mt-4">
                  <ul class="gradient-bullet-list mt-4">
                    <li class="SairSis">
						<a href="?u=sistema&sair=1">Sair <i class="mdi mdi-logout"></i></a> 
					</li>
                  </ul>
                </div>
              </span>
            </li>
			  
          </ul>
        </nav>

<script>

setInterval(function(){
	
	$.ajax({
	url: './tabelas/atendimento/acoes/qt_atendimento.php',
	success: function(data) {

		if(data>0){
		   $('span[QTANT]').css('display','block');
		}else{
		   $('span[QTANT]').css('display','none');
		}
		
	}
	});

}, 3000);	
	
	
$('a[Acao]').click(function(){

	let largura = window.screen.availWidth ;
	
	var caminho = $(this).attr('caminho');

	var perfil = $(this).attr('perfil');
	var Perfils =  "<?=$d->perfil?>" ;
	var resultP = Perfils.split("|");
	var tem = resultP.indexOf( perfil );
	
	if( tem != -1 ){

		$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');	

		$.ajax({
		  url: caminho,
		  success: function(data) {
		  $('#CONTEUDOS').html(data);
		  $("#CARREGANDO").html('');
			  if(largura < 500){
				 $("#sidebar").removeClass("active");
			  }
		  }
		});				

	}else{


	 $.confirm({

		title: "<span style='color:red'>Permissão negada!</span>",
		content: "Entre em contato com o <b>administrador</b> do sistema.",
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

});		
	
	
	
$("#FotoPerfil").click(function(){

	let TipoLog = $(this).attr("TipoLog");
	
	if(TipoLog=="ADM"){
	   var Pasta = "foto_perfil";
	}
	else if(TipoLog="USER"){
	   var Pasta = "foto_perfil_user";
	}   
	
	Perfil = $.confirm({

		title: "<span style='color:#000'>Atualizar foto do perfil!</span>",
		content: "url:./paginas/"+Pasta+".php",
		columnClass:"col-md-8 col-md-offset-2",
		theme: "light",
		buttons: {
			fechar: {
				btnClass: "btn-danger",
				action: function(){
				}
			},
		}				

	});	

});

</script>