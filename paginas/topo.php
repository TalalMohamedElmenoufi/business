<?php
if($_SESSION[id_status]=="ADM"){
	$perguntaUser = "select id, nome, sexo, foto_perfil, perfil from usuarios where id = '".$_SESSION[id_usuario]."' ";
	$resultadoUser = mysqli_query($conexao, $perguntaUser);
	$d = mysqli_fetch_object($resultadoUser);
	$pastaInt = "sistema";
}
elseif($_SESSION[id_status]=="USER"){
	$perguntaUser = "select id, nome, sexo, foto_perfil, perfil from login_acesso where id = '".$_SESSION[id_user]."' ";
	$resultadoUser = mysqli_query($conexao2, $perguntaUser);
	$d = mysqli_fetch_object($resultadoUser);
	$pastaInt = "sistema/user";
}


$nomeDb = explode(" ",$d->nome);
$nome = $nomeDb[0] ;
$SobNome = $nomeDb[1] ;

$pasta = acentos($nome) ;
$pasta = $d->id."_".strtolower($pasta) ;


$alertas = "select * from alertas where lido = 'S'order by id desc  limit 0,4 ";
$ralertas = mysqli_query($conexao2, $alertas);


?>





<style>
	.Logo_empresa{
		height:51px !important;
		width:200px !important;
	}.Logo_mini{
		height:53px !important;
		width:55px !important;
	}

	.SairSistema:hover{
		color:#FF0004 !important;
	}
	
	#fullscreen-button{
		cursor:pointer;
	}
	
	.TodasAlertas:hover{
		cursor:pointer;
		color:#108812;
	}
</style>

      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="?u=sistema"><img src="assets/images/logo.png" class="Logo_empresa" alt="logo"  /></a>
          <a class="navbar-brand brand-logo-mini" href="?u=sistema"><img src="assets/images/logo-mini.png" alt="logo" class="Logo_mini" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
			
          <div class="search-field d-none d-md-block">
            <!--<form class="d-flex align-items-center h-100" action="#">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
              </div>
            </form>-->
          </div>
			
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img alt="<?=$nome?>" src="<?=(($d->foto_perfil)?'img/'.$pastaInt.'/'.$pasta.'/perfil/'.$d->foto_perfil:'assets/img/'.(($d->sexo=='M')?'user':'user_f').'.gif')?>" class="RetornaPerfil">
                  <span class="availability-status online"></span> 
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black"><?=($d->nome)?></p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
				  
				  
				  
                <a class="dropdown-item MenusAoPassar AlterarDados" TipoLog="<?=$_SESSION[id_status]?>" >
                  <i class="far fa-user mr-2 text-info "></i> Alterar seus dados 
				</a>
                <div class="dropdown-divider"></div>				  
				  
                <a class="dropdown-item MenusAoPassar AlterarSenha" TipoLog="<?=$_SESSION[id_status]?>" >
                  <i class="fas fa-key mr-2 text-primary "></i> Alterar senha 
				</a>
                <div class="dropdown-divider"></div>				  
				  
                <a class="dropdown-item MenusAoPassar" Config  caminho="./tabelas/bot_config/bot_config.php" >
                  <i class="fas fa-cog mr-2 text-success "></i> Configurações BOT SIS
				</a>
                <div class="dropdown-divider"></div>				  
				  
                <a class="dropdown-item MenusAoPassar" Config  caminho="./tabelas/bot_config_whats/bot_config_whats.php" >
                  <i class="fas fa-cog mr-2 text-success "></i> Configurações BOT WHATS
				</a>
                <div class="dropdown-divider"></div>				  
				  
                <a class="dropdown-item MenusAoPassar" Config  caminho="./tabelas/bot_config/whats_config.php" >
                  <i class="mdi mdi-whatsapp mr-2 text-success "></i> Config WhatsApp 
				</a>
                <div class="dropdown-divider"></div>
				  
				<span style="display:<?=(($_SESSION[id_status]=="ADM")?'block':'none')?>"><a class="dropdown-item MenusAoPassar" Config  caminho="./tabelas/log_acesso/log_acesso.php" >
				<i class="mdi mdi-cached mr-2 text-warning "></i> Log de acessos 
				</a></span>
                <div class="dropdown-divider" ></div>
 
				   
				  
                <a class="dropdown-item MenusAoPassar" href="?u=sistema&sair=1">
                  <i class="mdi mdi-logout mr-2 text-danger "></i> Sair 
				</a>
              </div>
            </li>
            <li class="nav-item d-none d-lg-block full-screen-link">
              <a class="nav-link">
                <i class="mdi mdi-fullscreen" id="fullscreen-button" data-toggle="tooltip" data-placement="left" title="Tela cheia"></i>
              </a>
            </li>
			  
			<!--E-mail-->  
            <li class="nav-item dropdown" style="display:<?=(($_SESSION[id_usuario]==1)?'block':'none')?>">
              <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-users"></i>
                <span class="count-symbol bg-warning"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <h6 class="p-3 mb-0">Cientes</h6>
                <div class="dropdown-divider"></div>
				  
				<?php
				$clientes = "select * from usuarios where id != '1' and status_whats_desc='CONNECTED' limit 0,6 ";
				$Rclientes = mysqli_query($conexao,$clientes);
				while ($cl = mysqli_fetch_object($Rclientes)){
					
					
				$nomeDbC = explode(" ",$cl->nome);
				$nomeC = $nomeDbC[0] ;
				$SobNome = $nomeDbC[1] ;

				$pastaC = acentos($nomeC) ;
				$pastaC = $cl->id."_".strtolower($pastaC) ;					
				?>		
				  
                <a class="dropdown-item preview-item MenusAoPassar">
                  <div class="preview-thumbnail">
					  
				   <img alt="<?=$nomeC?>" src="<?=(($cl->foto_perfil)?'img/sistema/'.$pastaC.'/perfil/'.$cl->foto_perfil:'assets/img/'.(($cl->sexo=='M')?'user':'user_f').'.gif')?>" class="profile-pic">		
					  
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal"><?=($nomeC)?> <?=$SobNome?></h6>
                    <p class="text-gray mb-0"> ... </p>
                  </div>
                </a>
				  
                <div class="dropdown-divider"></div>				  
				  
			   <?php
				}	
				?>

				  
                
                <!--<h6 class="p-3 mb-0 text-center">4 new messages</h6>-->
              </div>
            </li>
			<!------------------------------------------------> 
			  
			  
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline" data-toggle="tooltip" data-placement="left" title="Alertas"></i>
                <span class="count-symbol bg-danger"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <h6 class="p-3 mb-0">Notificações</h6>
                <div class="dropdown-divider"></div>
				 
				<?php 
				while ($a = mysqli_fetch_object($ralertas)){
				?>  
                <a class="dropdown-item preview-item MenusAoPassar">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-success">
                      <i class="fas fa-bullhorn"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1"><?=dataBr($a->data)?></h6>
                    <p class="text-gray ellipsis mb-0"> <?=($a->alerta)?> </p>
                  </div>
                </a>
				  
                <div class="dropdown-divider"></div>
				<?php
				} 
				?> 				  

				  
                <h6 class="p-3 mb-0 text-center TodasAlertas">Ver todas as notificações</h6>
				  
              </div>
            </li>
			  
			  
            <li class="nav-item nav-logout d-none d-lg-block">
              <a class="nav-link" href="?u=sistema&sair=1">
                <i class="mdi mdi-power SairSistema" data-toggle="tooltip" data-placement="left" title="Sair do sistema"></i>
              </a>
            </li>
            <li class="nav-item nav-settings d-none d-lg-block">
              <a class="nav-link" href="#">
                <i class="mdi mdi-format-line-spacing"></i>
              </a>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
		  
<script >
$('[data-toggle="tooltip"]').tooltip();
	
	$('a[Config]').click(function(){

    let largura = window.screen.availWidth ;		
		
	var caminho = $(this).attr('caminho');
	
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
		
    });	


$('.AlterarDados').click(function(){	
	
	let TipoLog = $(this).attr("TipoLog");
	
	if(TipoLog=="ADM"){
	   var Pasta = "alterar_dados";
	}
	else if(TipoLog="USER"){
	   var Pasta = "alterar_dados_user";
	}
	
	
	AlterarDados = $.confirm({
		title: "",
		content: "url:./acoes/"+Pasta+".php",
		columnClass:"col-md-8 col-md-offset-2",
		theme: "light",
		buttons: {
			Cancelar: {
				btnClass: "btn-danger btn-sm",
				action: function(){
				}
			},
		}				
	  });
	
});	


$('.AlterarSenha').click(function(){	
	
	let TipoLog = $(this).attr("TipoLog");
	 
	AlterarSenha = $.confirm({
		title: "",
		content: "url:./acoes/alterar_senha.php?TipoLog="+TipoLog,
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			Cancelar: {
				btnClass: "btn-danger btn-sm",
				action: function(){
				}
			},
		}				
	  });
	
});	
	
	
$('.TodasAlertas').click(function(){	
	
	$("#CARREGANDO").html('<div id="loader"><img src="./img/loader.gif" width="120" ></div>');

	$.ajax({
	  url: './tabelas/alertas/alertas.php',
	  success: function(data) {
	  $('#CONTEUDOS').html(data);
		  $("#CARREGANDO").html('');
		  $('body>.tooltip').remove();
	  }
	});
	
});	
	
</script>		  
		  
      </nav>

