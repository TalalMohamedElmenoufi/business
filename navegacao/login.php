<div id="AcessarSistema">
<?php
if($_POST){

include("../includes/connect.php");
	
list($api_token) = mysqli_fetch_row(mysqli_query($conexao, "select token_producao from asaas where id = '1' "));
	
if($_POST[repetir_senha]){
	
	$endereco = str_replace("'","`",$_POST[endereco]);
    
	$senha = password_hash($_POST[senha], PASSWORD_DEFAULT);
	 
    $query = " insert into usuarios set
			   nome = '".($_POST[nome])."',
			   email = '".$_POST[email]."',
			   celular = '".$_POST[celular]."',
			   senha = '".$senha."',
			   senha_ver = '".$_POST[senha]."',
			   data_nascimento = '".$_POST[data_nascimento]."',
			   tipo_pessoa = '".$_POST[tipo_pessoa]."',
			   cpf_cnpj = '".$_POST[cpf_cnpj]."',			   
			   estado = '".$_POST[estado]."',
			   cidade = '".$_POST[cidade]."',
			   cep = '".$_POST[cep]."',
			   endereco = '".($endereco)."',
			   numero = '".$_POST[numero]."',
			   bairro = '".($_POST[bairro])."',
			   complemento = '".($_POST[complemento])."',
			   
			   perfil = 'agenda|conta_bancaria|grfico_financeiro|clientes|cobrancas|Transferencias|mailmarketing|envioMarketing|aniversariantes|contatos_agenda|seus_contatos|perguntas_bot|graficos|solicitar_credito|whatsapp_conectar|sms|integracao|atendimento',
			   creditos = '1',
			   creditos_sms = '0',
			   creditos_msg = '500',
			   creditos_pesquisa = '1',
			   credito_email = '1000',
			   termo='1',
			   status_whats_desc='OFFLINE'
			   
			 ";
	$result = mysqli_query($conexao, $query);
	$id = mysqli_insert_id($conexao);

	if($result){

		$codigo = $id;

		//CRIA BASE DE DADOS BOT__________________________________________
		$link = mysqli_connect('localhost', 'root', 'Pr0v!s@2024S!st3m@');
		if (!$link) {
			die('N�o foi possivel conectar: ' . mysqli_error());
		}

		$sql = "CREATE DATABASE elmenoufi_bot_".$codigo ;
		if (mysqli_query($link,$sql)) {

			//echo "Database elmenoufi_sms_".$codigo." criado com sucesso\n";

				$cnx = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$codigo) ;

				if (!$cnx) {
					die('N�o foi possivel conectar: ' . mysqli_error() .'\n' );
				}

			
					$login_acesso = "CREATE TABLE `login_acesso` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `foto_perfil` varchar(60) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `celular` char(20) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `sexo` enum('M', 'F') NOT NULL,
					  `senha` text NOT NULL,
					  `senha_ver` varchar(25) NOT NULL,
					  `perfil` text NOT NULL,
					  `data_cadastro` datetime NOT NULL,
					  PRIMARY KEY (id),
					  UNIQUE KEY (email),
					  UNIQUE KEY (celular)
					)";
					mysqli_query($cnx,$login_acesso);			
			
			
					$agenda = "CREATE TABLE `agenda` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `data` date NOT NULL,
					  `hora` time NOT NULL,
					  `lembrete` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					  `compartilhar` varchar(100) NOT NULL,
					  `status` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$ragenda = mysqli_query($cnx,$agenda);			

					$alertas = "CREATE TABLE `alertas` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `alerta` varchar(255) NOT NULL,
					  `url` varchar(120) NOT NULL,
					  `data` datetime NOT NULL,
					  `entrege` int(2) NOT NULL,
					  `lido` enum('N','S') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$ralertas = mysqli_query($cnx,$alertas);			
			
			
					$bot_config = "CREATE TABLE `bot_config` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `empresa` varchar(160) NOT NULL,
					  `titulo_login` varchar(110) NOT NULL,
					  `descricao_login` text NOT NULL,
					  `logo` varchar(140) NOT NULL,
					  `logo_login` varchar(140) NOT NULL,
					  `cor_menu` varchar(25) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rbot_config = mysqli_query($cnx,$bot_config);				
			
			
					$whats_config = "CREATE TABLE `whats_config` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`titulo` varchar(100) NOT NULL,
					`descricao` varchar(200) NOT NULL,
					`titulo_vencida` varchar(100) NOT NULL,
					`descricao_vencida` varchar(200) NOT NULL,
					`img` varchar(140) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rwhats_config = mysqli_query($cnx,$whats_config);			

			
					$contatos_agenda = "CREATE TABLE `contatos_agenda` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `cod_pais` bigint(20) NOT NULL,
					  `cod_estado` bigint(20) NOT NULL,
					  `telefone` varchar(20) NOT NULL,
					  `destination` varchar(15) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `erro` enum('0', '1') NOT NULL,
					  `situacao` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcontatos_agenda = mysqli_query($cnx,$contatos_agenda);				
			
					$contatos_bot = "CREATE TABLE `contatos_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `whatsapp` varchar(20) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `situacao` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					mysqli_query($cnx,$contatos_bot);			
			
			
					$cadastro = "CREATE TABLE `cadastro` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `usuario` varchar(255) NOT NULL,
					  `categoria` bigint(20) NOT NULL,
					  `nome` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `cod_pais` bigint(20) NOT NULL,
					  `cod_estado` bigint(20) NOT NULL,
					  `telefone` varchar(20) NOT NULL,
					  `tel_tipo` varchar(15) NOT NULL,
					  `destination` varchar(15) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `erro` enum('0', '1') NOT NULL,
					  `situacao` enum('0', '1') NOT NULL,
					  `aceito` enum('SIM', 'NAO') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcadastro = mysqli_query($cnx,$cadastro);				
			
					$cadastro_erro = "CREATE TABLE `cadastro_erro` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `destination` varchar(15) NOT NULL,
					  `active` int(11) NOT NULL,
					  `operadora` varchar(30) NOT NULL,
					  `pais` varchar(30) NOT NULL,
					  `date` datetime NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcadastro_erro = mysqli_query($cnx,$cadastro_erro);				
			
			
					$categoria_cadastro = "CREATE TABLE `categoria_cadastro` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `usuario` varchar(255) NOT NULL,
					  `descricao` varchar(255) NOT NULL,
					  `campos` text NOT NULL,
					  `sms` bigint(20) NOT NULL,
					  `emails` bigint(20) NOT NULL,
					  `registros` bigint(20) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcategoria_cadastro = mysqli_query($cnx,$categoria_cadastro);			
			
			
					$grupos_bot = "CREATE TABLE `grupos_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `nome` varchar(100) NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rgrupos_bot = mysqli_query($cnx,$grupos_bot);				
			
			
				   /*Ação do Log login*/
			       $resposta_logAcesso = "CREATE TABLE `log_acesso_".date('Y')."` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `ip` varchar(20) NOT NULL,
					  `city` varchar(120) NOT NULL,
					  `log` text NOT NULL,
					  `data` datetime NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rresposta_logAcesso = mysqli_query($cnx,$resposta_logAcesso);

					$ip = $_SERVER['REMOTE_ADDR'];
					$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
					//echo $details->ip; // -> "Retorna IP"
					//echo $details->city; // -> "Retorna cidade"	  		

					$log = "ip:".$details->ip." | hostname:".$details->hostname." | city:".$details->city." | region:".$details->region." | country:".$details->country." | loc:".$details->loc." | org:".$details->org." | postal:".$details->postal." | timezone:".$details->timezone." | readme:".$details->readme;
			
					$insert_logAcesso = "INSERT INTO `log_acesso_".date('Y')."` (
					`ip`,
					`city`,
					`log`, 
					`data`
					) VALUES
					( '".$ip."', '".$details->city."', '".$log."', NOW() );
					";
					$rinsert_logAcesso = mysqli_query($cnx,$insert_logAcesso);			
					/*-------------------------------------------*/				
			
			
					$perguntas_bot = "CREATE TABLE `perguntas_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `id_grupo` int(11) NOT NULL,
					  `pergunta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rperguntas_bot = mysqli_query($cnx,$perguntas_bot);			
			
			
					$pesquisa_bot = "CREATE TABLE `pesquisa_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_logado` int(11) NOT NULL,
					  `pesquisa` varchar(120) NOT NULL,
					  `id_config` int(11) NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rpesquisa_bot = mysqli_query($cnx,$pesquisa_bot);

					$pesquisa_bot_participante = "CREATE TABLE `pesquisa_bot_participante` (
						`id` int(20) NOT NULL AUTO_INCREMENT,
						`fone` varchar(20) NOT NULL,
						`nome` varchar(120) NOT NULL,
						`email` varchar(130) NOT NULL,
						`idade` int(11) NOT NULL,
						PRIMARY KEY (id)	
					  )";
					  $rpesquisa_bot_participante= mysqli_query($cnx,$pesquisa_bot_participante);


			        $resposta_bot = "CREATE TABLE `resposta_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `id_grupo` int(11) NOT NULL,
					  `resposta` varchar(120) CHARACTER SET utf8mb4 NOT NULL,
					  `com_text` int(1) NOT NULL,
					  `vin_grupo` int(11) NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rresposta_bot = mysqli_query($cnx,$resposta_bot);
			
			
			       $resposta_user_bot = "CREATE TABLE `resposta_user_bot_".date('Y')."` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `id_pergunta` int(11) NOT NULL,
					  `id_resposta` int(11) NOT NULL,
					  `participante` varchar(20) NOT NULL,
					  `ano_mes` varchar(10) NOT NULL,
					  `ano` varchar(6) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rresposta_user_bot = mysqli_query($cnx,$resposta_user_bot);
			
			       $resposta_user_bot = "CREATE TABLE `t_".date('Y')."_smgAgendamento` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `cod_cliente` int(11) NOT NULL,
					  `data` datetime NOT NULL,
					  `grupos` varchar(150) NOT NULL,
					  `mensagem` text NOT NULL,
					  `status` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rresposta_user_bot = mysqli_query($cnx,$resposta_user_bot);			
			

			
			
					$planosSms = "CREATE TABLE `sms_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,3) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosSms = mysqli_query($cnx,$planosSms);		

					$planosSmsI = "INSERT INTO `sms_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1.000 SMSs', 1000, '0.10', 1),
					(2, '5.000 SMSs', 5000, '0.09', 2),
					(3, '10.000 SMSs', 10000, '0.08', 3),
					(4, '50.000 SMSs', 50000, '0.07', 4),
					(5, '100.000 SMSs', 100000, '0.06', 5);
					";
					$rplanosSmsI = mysqli_query($cnx,$planosSmsI);			
			
					$planosWhats = "CREATE TABLE `whatsapp_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,6) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosWhats = mysqli_query($cnx,$planosWhats);		

					$planosWhatsI = "INSERT INTO `whatsapp_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1.000 Envios', 1000, '0.1', 1),
					(2, '5.000 Envios', 5000, '0.09', 2),
					(3, '10.000 Envios', 10000, '0.08', 3),
					(4, '20.000 Envios', 20000, '0.07', 4),
					(5, '30.000 Envios', 30000, '0.06', 5),
					(6, '50.000 Envios', 50000, '0.05', 6);
					";
					$rplanosWhatsI = mysqli_query($cnx,$planosWhatsI);				
			
					$planosPesquisa = "CREATE TABLE `pesquisas_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,2) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosPesquisa = mysqli_query($cnx,$planosPesquisa);		

					$planosPesquisaI = "INSERT INTO `pesquisas_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1 Pesquisa', 1, '788.89', 1),
					(2, '5 Pesquisas', 5, '398.88', 2),
					(3, '8 Pesquisas', 8, '279.99', 3);
					";
					$rplanosPesquisaI = mysqli_query($cnx,$planosPesquisaI);			
			
			
					$Ecommerce = "CREATE TABLE `t_".date('Y')."_ecommerce` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_cliente` int(11) NOT NULL,
					`data` datetime NOT NULL  DEFAULT CURRENT_TIMESTAMP,
					`data_vencimento` date NULL,
					`data_pagamento` date NULL,
					`quantidade` int(20) NOT NULL,
					`valor` decimal(20,2) NOT NULL,
					`status` enum('0','1') NOT NULL,



					  PRIMARY KEY (id)
					) ";
					$rEcommerce = mysqli_query($cnx,$Ecommerce);		

					$EcommercePg = "CREATE TABLE `t_".date('Y')."_ecommerce_pg` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_fatura` int(11) NOT NULL,
					`id_log` varchar(70) NOT NULL,
					`description` varchar(70) NOT NULL,
					`notes` varchar(70) NOT NULL,
					`created_at` varchar(70) NOT NULL,
					`creditado` enum('0','1') NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rEcommercePg = mysqli_query($cnx,$EcommercePg);			
			

			
			
					/*SMS E E-mails*/			
					$smsEnvio = "CREATE TABLE `t_".date('Y')."_smsAgendamento` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_cliente` int(11) NOT NULL,
					`data` datetime NOT NULL,
					`grupos` varchar(150) NOT NULL,
					`mensagem` text NOT NULL,
					`status` enum('0','1') NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rsmsEnvio = mysqli_query($cnx,$smsEnvio);		

					$smsErros = "CREATE TABLE `t_".date('mY')."_smsErros` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`errorCode` int(11) NOT NULL,
					`errorMessage` text NOT NULL,
					`mes_ano` varchar(10) NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rsmsErros = mysqli_query($cnx,$smsErros);

					$smsStatuses = "CREATE TABLE `t_".date('mY')."_smsStatuses` (
					`codigo` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`id` varchar(40) NOT NULL,
					`id_lote` varchar(40) NOT NULL,
					`correlationId` varchar(25) NOT NULL,
					`carrierId` int(11) NOT NULL,
					`carrierName` varchar(30) NOT NULL,
					`destination` varchar(15) NOT NULL,
					`sentStatusCode` int(11) NOT NULL,
					`sentStatus` varchar(30) NOT NULL,
					`sentDate` datetime NOT NULL,
					`sentAt` int(11) NOT NULL,
					`deliveredStatusCode` varchar(20) NOT NULL,
					`deliveredStatus` varchar(30) NOT NULL,
					`deliveredDate` datetime NOT NULL,
					`deliveredAt` varchar(30) NOT NULL,
					`updatedDate` datetime NOT NULL,
					`updatedAt` varchar(30) NOT NULL,
					`mes_ano` varchar(10) NOT NULL,
					PRIMARY KEY (codigo)
					)  ";
					$rsmsStatuses = mysqli_query($cnx,$smsStatuses);


					$eventosSms = "CREATE TABLE `eventos_sms` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`evento` text NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`id_grupo` int(11) NOT NULL,
					`status` enum('0','1') NOT NULL,
					PRIMARY KEY (id)
					) ";
					$reventosSms = mysqli_query($cnx,$eventosSms);		

			
					$smgStatuses = "CREATE TABLE `t_".date('mY')."_smgStatuses` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`mensagem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`destination` varchar(15) NOT NULL,
					`id_pesquisa` int(11) NOT NULL,
					`img` varchar(255) NOT NULL,
					`img_ext` varchar(4) NOT NULL,
					`pdf` varchar(255) NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`enviado` enum('0','1') NOT NULL,
					`aniversario` enum('enviado','num_valido','num_invalido') NOT NULL,
					`campanha` enum('0','1','2') NOT NULL,
					PRIMARY KEY (id)
					)  ";
					$rsmgStatuses = mysqli_query($cnx,$smgStatuses);	
			
			
					$aniversariantes = "CREATE TABLE `t_".date('mY')."_aniversariantes` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_cadastro` int(11) NOT NULL,
					`mensagem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`destination` varchar(15) NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`enviado` enum('0','1') NOT NULL,
					`aniversario` enum('enviado','num_valido','num_invalido') NOT NULL,
					PRIMARY KEY (id)
					)  ";
					$raniversariantes = mysqli_query($cnx,$aniversariantes);	
			
			
					$emailStatuses = "CREATE TABLE `t_".date('mY')."_emailStatuses` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`campanha` int(11) NOT NULL,
					`nome` varchar(210) NOT NULL,
					`email` varchar(210) NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`enviado` enum('0','1','2') NOT NULL,
					`log_erro` text NOT NULL,
					PRIMARY KEY (id)
					)  ";
					mysqli_query($cnx,$emailStatuses);			
			
			
			
					$numeros_invalidos = "CREATE TABLE `numeros_invalidos` (
					`codigo` int(20) NOT NULL AUTO_INCREMENT,
					`id` varchar(40) NOT NULL,
					`carrierName` varchar(30) NOT NULL,
					`correlationId` varchar(25) NOT NULL,
					`destination` varchar(15) NOT NULL,
					PRIMARY KEY (codigo),
					UNIQUE KEY (destination)
					) ";
					$rnumeros_invalidos = mysqli_query($cnx,$numeros_invalidos);	

					$MensagemNiver = "CREATE TABLE `mensagem_niver` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`mensagem` text NOT NULL,
					`data_hoje` varchar(6) NOT NULL,
					`data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
					) ";
					$rMensagemNiver = mysqli_query($cnx,$MensagemNiver);			


					$Campanhas = "CREATE TABLE `campanhas` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`nome` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`mensagem1` text NOT NULL,
					`mensagem2` text NOT NULL,
					`mensagem3` text NOT NULL,
					`mensagem4` text NOT NULL,
					`mensagem5` text NOT NULL,
					`mensagem6` text NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rCampanhas = mysqli_query($cnx,$Campanhas);		


					$Campanhas = "CREATE TABLE `campanhas` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`nome` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`mensagem1` text NOT NULL,
					`mensagem2` text NOT NULL,
					`mensagem3` text NOT NULL,
					`mensagem4` text NOT NULL,
					`mensagem5` text NOT NULL,
					`mensagem6` text NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rCampanhas = mysqli_query($cnx,$Campanhas);		
	

					$mailmarketing = "CREATE TABLE `mailmarketing` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`nome` varchar(255) NOT NULL,
					`email` varchar(255) NOT NULL,
					`assinatura` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`categorias` text NOT NULL,
					`mensagem` text NOT NULL,
					`situacao` enum('0','1') NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rmailmarketing = mysqli_query($cnx,$mailmarketing);

					$emailAgendamento = "CREATE TABLE `t_".date('Y')."_emailAgendamento` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_cliente` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`grupos` varchar(150) NOT NULL,
					`campanha` int(11) NOT NULL,
					`lote` varchar(255) NOT NULL,
					`processados` int(20) NOT NULL,
					`status` int(11) NOT NULL,
					PRIMARY KEY (id)
					) ";
					$remailAgendamento = mysqli_query($cnx,$emailAgendamento);		

					$SendEmails = "CREATE TABLE `t_email_".$codigo."_".date('Ym')."` (
					`codigo` int(20) NOT NULL AUTO_INCREMENT,
					`lote` varchar(255) NOT NULL,
					`nome` varchar(255) NOT NULL,
					`email` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`mensagem` text NOT NULL,
					`enviada` enum('0','1') NOT NULL, 
					`lida` enum('0','1') NOT NULL,
					`clicada` enum('0','1') NOT NULL,
					`bloqueada` enum('0','1') NOT NULL,
					`retornada` enum('0','1') NOT NULL,
					PRIMARY KEY (codigo)
					) ";
					$rSendEmails = mysqli_query($cnx,$SendEmails);
			
			
					$planosEmail = "CREATE TABLE `email_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,3) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosEmail = mysqli_query($cnx,$planosEmail);		

					$planosEmailI = "INSERT INTO `email_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1.000 E-mails', 1000, '15.00', 1),
					(2, '5.000 E-mails', 5000, '30.00', 2),
					(3, '10.000 E-mails', 10000, '40.00', 3),
					(4, '50.000 E-mails', 50000, '150.00', 4),
					(5, '100.000 E-mails', 100000, '250.00', 5);
					";
					$rplanosEmailI = mysqli_query($cnx,$planosEmailI);				
					/*--------------------------------*/			
			
			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca = "CREATE TABLE `asaas_cobranca_sms` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,					  
					  
					  PRIMARY KEY (id_rg)
					) ";
					$rAsaasCobranca = mysqli_query($cnx,$AsaasCobranca);			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca2 = "CREATE TABLE `asaas_cobranca_whatsapp` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,					  
					  
					  PRIMARY KEY (id_rg)
					) ";
					$rAsaasCobranca2 = mysqli_query($cnx,$AsaasCobranca2);			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca3 = "CREATE TABLE `asaas_cobranca_pesquisa` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,						  
					  
					  PRIMARY KEY (id_rg)
					) ";
					$rAsaasCobranca3 = mysqli_query($cnx,$AsaasCobranca3);
					/*--------------------------------*/			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca4 = "CREATE TABLE `asaas_cobranca_email` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,
					  
					  PRIMARY KEY (id_rg)
					) ";
					mysqli_query($cnx,$AsaasCobranca4);
					/*--------------------------------*/	

			       /*Pagamento Asaas-------------------*/
					$AsaasCobrancaClientes = "CREATE TABLE `cobrancas` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  `mes_ano` varchar(10) NOT NULL,
					  PRIMARY KEY (id_rg)
					) ";
					mysqli_query($cnx,$AsaasCobrancaClientes);


					/*--------------------------------*/			
			
			
					$Clientes = "CREATE TABLE `clientes` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_asaas` varchar(50) NOT NULL,
					`billingType` varchar(50) NOT NULL,
					`nome` varchar(255) NOT NULL,
					`email` varchar(255) NOT NULL,
					`emails` text NOT NULL,
					`tipo_pessoa` varchar(3) NOT NULL,
					`cpf_cnpj` varchar(30) NOT NULL,
					`celular` char(20) NOT NULL,
					`celulares` varchar(160) NOT NULL,
					`estado` bigint(20) NOT NULL,
					`cidade` bigint(20) NOT NULL,
					`cep` varchar(20) NOT NULL,
					`endereco` varchar(255) NOT NULL,
					`numero` varchar(10) NOT NULL,
					`bairro` varchar(80) NOT NULL,
					`complemento` varchar(150) NOT NULL,
					`data_nascimento` date NOT NULL,
					`sexo` enum('M','F') NOT NULL,
					`ativo` enum('0','1') NOT NULL,
					`dia_vencimento` int(10) NOT NULL,
					`envio` int(2) NOT NULL,
					`gerar` int(2) NOT NULL,
					`ano_mes` varchar(10) NOT NULL,
					`frequencia` varchar(30) NOT NULL,
					`data_cadastro` datetime NOT NULL,
					 PRIMARY KEY (id),
					 UNIQUE KEY (email),
					 UNIQUE KEY (celular)					
					)";
					mysqli_query($cnx,$Clientes);

			
			
					$Servicos = "CREATE TABLE `servicos` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_cliente` int(20) NOT NULL,
					`id_tipo_servico` int(20) NOT NULL,
					`descricao` varchar(255) NOT NULL,
					`valor` decimal(20,2) NOT NULL,
					`valor_desconto` decimal(20,2) NOT NULL,
					`data_adesao` date NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx,$Servicos);	
			
					$ServicosTipo = "CREATE TABLE `servicos_tipo` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`descricao` varchar(255) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx,$ServicosTipo);				


					$Transferencias = "CREATE TABLE `minhas_transferencias` (			
					`id_transferencia` int(20) NOT NULL AUTO_INCREMENT,
					`id` varchar(150) NOT NULL,
					`dateCreated` date NOT NULL,
					`status` varchar(30) NOT NULL,
					`effectiveDate` datetime NOT NULL,
					`type` varchar(40) NOT NULL,
					`value` decimal(20,2) NOT NULL,
					`netValue` decimal(20,2) NOT NULL,
					`transferFee` decimal(20,2) NOT NULL,
					`scheduleDate` date NOT NULL,
					`authorized` varchar(3) NOT NULL,
					`code` varchar(10) NOT NULL,
					`name` varchar(200) NOT NULL,
					`accountName` varchar(100) NOT NULL,
					`ownerName` varchar(200) NOT NULL,
					`cpfCnpj` varchar(25) NOT NULL,
					`agency` varchar(25) NOT NULL,
					`agencyDigit` varchar(3) NOT NULL,
					`account` varchar(28) NOT NULL,
					`accountDigit` varchar(3) NOT NULL,
					`transactionReceiptUrl` varchar(255) NOT NULL,
					 PRIMARY KEY (id_transferencia)				
					)";
					mysqli_query($cnx,$Transferencias);			
			
			
					$ConfigNF = "CREATE TABLE `config_nf` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_cliente` int(20) NOT NULL,
					`installment` varchar(130) NOT NULL,
					`serviceDescription` varchar(255) NOT NULL,
					`observations` varchar(255) NOT NULL,
					`deductions` decimal(20,2) NOT NULL,
					`retainIss` varchar(5) NOT NULL,
					`iss` decimal(20,2) NOT NULL,
					`cofins` decimal(20,2) NOT NULL,
					`csll` decimal(20,2) NOT NULL,
					`inss` decimal(20,2) NOT NULL,
					`ir` decimal(20,2) NOT NULL,
					`pis` decimal(20,2) NOT NULL,
					`municipalServiceCode` float NOT NULL,
					`municipalServiceName` varchar(255) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx,$ConfigNF);			
			

					$NotaFiscal = "CREATE TABLE `nota_fiacal_".date('Y')."` (			
					`id_nota` int(20) NOT NULL AUTO_INCREMENT,
					`object` varchar(50) NOT NULL,
					`id` varchar(150) NOT NULL,
					`status` varchar(100) NOT NULL,
					`customer` varchar(50) NOT NULL,
					`type` varchar(20) NOT NULL,
					`statusDescription` varchar(50) NOT NULL,
					`serviceDescription` varchar(255) NOT NULL,
					`pdfUrl` varchar(200) NOT NULL,
					`xmlUrl` varchar(200) NOT NULL,
					`rpsSerie` varchar(200) NOT NULL,
					`rpsNumber` varchar(200) NOT NULL,
					`number` varchar(100) NOT NULL,
					`validationCode` varchar(100) NOT NULL,
					`value` decimal(20,2) NOT NULL,
					`deductions` decimal(20,2) NOT NULL,
					`effectiveDate` date NOT NULL,
					`observations` varchar(255) NOT NULL,
					`estimatedTaxesDescription` varchar(255) NOT NULL,
					`payment` varchar(30) NOT NULL,
					`installment` varchar(130) NOT NULL,
					`retainIss` varchar(5) NOT NULL,
					`iss` decimal(20,2) NOT NULL,
					`cofins` decimal(20,2) NOT NULL,
					`csll` decimal(20,2) NOT NULL,
					`inss` decimal(20,2) NOT NULL,
					`ir` decimal(20,2) NOT NULL,
					`pis` decimal(20,2) NOT NULL,
					`municipalServiceId` varchar(100) NOT NULL,
					`municipalServiceCode` float NOT NULL,
					`municipalServiceName` varchar(255) NOT NULL,
					 PRIMARY KEY (id_nota)				
					)";
					mysqli_query($cnx,$NotaFiscal);				

					$MovBot = "CREATE TABLE `mov_bot_".date('mY')."` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(11) NOT NULL,
					`id_whats` varchar(100) NOT NULL,
					`de_quem` varchar(100) NOT NULL,
					`para_quem` varchar(100) NOT NULL,
					`mensagem` text NOT NULL,
					`ackRes` int(2) NOT NULL,
					`retorno_log` text NOT NULL,
					`data_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`entregue` int(1) NOT NULL,
					`status` int(1) NOT NULL,
					`status_bot` int(1) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx,$MovBot);
			
					$MovBot2 = "CREATE TABLE `mov_bot_cliente_".date('mY')."` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`de_quem` varchar(100) NOT NULL,
					`id_user` int(20) NOT NULL,
					`data` date NOT NULL,
					`data_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx,$MovBot2);			
			 
			

					$saldacao = "CREATE TABLE `bot_whats_saldacao` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(20) NOT NULL,
					`saldacao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`palavra_chave` varchar(255) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx,$saldacao);
			
			
					$bot_whats_menu = "CREATE TABLE `bot_whats_menu` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(11) NOT NULL,
					`id_saldacao` int(11) NOT NULL,		
					`opcao` int(11) NOT NULL,
					`conteudo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx,$bot_whats_menu);	
			
					$bot_whats_menu_resposta = "CREATE TABLE `bot_whats_menu_resposta` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(11) NOT NULL,
					`id_menu` int(11) NOT NULL,		
					`conteudo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`arquivo` varchar(200) NOT NULL, 
					`ext_arquivo` varchar(6) NOT NULL, 
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx,$bot_whats_menu_resposta);				
			
			
			

			
					//Dados para 
			
					list($cidade) = mysqli_fetch_row(mysqli_query($conexao, "select nome from cidades where cod_cidades= '".$_POST['cidade']."'  "));
					list($estado) = mysqli_fetch_row(mysqli_query($conexao, "select nome from estados where cod_estados= '".$_POST['estado']."'  "));

					$fone = $_POST['celular'];
					$fone0 = explode(" ",$fone);

					$celular = $fone0[1]."".$fone0[2]."".$fone0[3];
					$celular0 = str_replace('-','',$celular);

					$cpfcnpj0 = $_POST['cpf_cnpj'];
					$cpfcnpj1 = str_replace('.','',$cpfcnpj0);
					$cpfcnpj2 = str_replace('-','',$cpfcnpj1);
					$cpfcnpj3 = str_replace('/','',$cpfcnpj2);

					$cep0 = $_POST['cep'];
					$cep1 = str_replace('-','',$cep0);

					$email = $_POST['email'];
					$name = ($_POST['nome']);
					$notes = "Business Corporativo";
					$phone = $celular0;
					$phone_prefix = $fone0[1];
					$cpf_cnpj = $cpfcnpj3;
					$cc_emails = $_POST['emails'];
					$zip_code = $cep1;
					$number = $_POST['numero'];
					$street = ($_POST['endereco']);
					$city = ($cidade);
					$state = ($estado);
					$district = ($_POST['bairro']);
					$complement = ($_POST['complemento']);	


					//----------------------------------			
			
					sendCadastroAsaas($conexao,$api_token ,$email,$name,$notes,$phone,$phone_prefix,$cpf_cnpj,$cc_emails,$zip_code,$number,$street,$city,$state,$district,$complement,$id,'Novo');			
			
			
			
			
		} else {
			//echo 'Erro ao criar o banco de dados: ' . mysqli_error() . "\n";
		}
		//FIM CRIA BASE DE DADOS__________________________________________	
		

	}
	

	
}
	
	
	else{
	
    $email = $_POST['email'];
    $senha = $_POST['password'];

	/*LONIN MARTER*/
	$getHash = mysqli_query($conexao,"SELECT id, senha, id_asaas FROM usuarios WHERE email = '$email'");
	$dados = mysqli_fetch_assoc($getHash);
	$hash = $dados['senha'];
    /*------------------------------------------*/
	
	/*LONIN SLAVER*/
	$cnxIn = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$_POST[ChaveInt]);	
	$getHash2 = mysqli_query($cnxIn,"SELECT id, senha FROM login_acesso WHERE email = '$email'");
	$dados2 = mysqli_fetch_assoc($getHash2);
	$hash2 = $dados2['senha'];		
	/*------------------------------------------*/	

		
	if( (password_verify($senha, $hash)) or (password_verify($senha, $hash2))  ){
		session_start();

		if($hash){
			$codigo = $dados[id];
			$status = 'ADM';
		}elseif($hash2){
			$codigo = $_POST[ChaveInt];
			$CodUser = $dados2[id];
			$status = 'USER';
		}


		
		
		//CRIA BASE DE DADOS BOT__________________________________________
		$link2 = mysqli_connect('localhost', 'root', 'Pr0v!s@2024S!st3m@');
		if (!$link2) {
			die('N�o foi possivel conectar: ' . mysqli_error());
		}
		
		$sql2 = "CREATE DATABASE elmenoufi_bot_".$codigo ;
		if (mysqli_query($link2,$sql2)) {
			//echo "Database elmenoufi_sms_".$codigo." criado com sucesso\n";

					$cnx2 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$codigo) ;

				if (!$cnx2) {
					die('N�o foi possivel conectar: ' . mysqli_error() .'\n' );
				}
				    
					$login_acesso = "CREATE TABLE `login_acesso` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `foto_perfil` varchar(60) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `celular` char(20) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `sexo` enum('M', 'F') NOT NULL,
					  `senha` text NOT NULL,
					  `senha_ver` varchar(25) NOT NULL,
					  `perfil` text NOT NULL,
					  `data_cadastro` datetime NOT NULL,
					  PRIMARY KEY (id),
					  UNIQUE KEY (email),
					  UNIQUE KEY (celular)
					)";
					mysqli_query($cnx2,$login_acesso);			
			
					$agenda = "CREATE TABLE `agenda` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `data` date NOT NULL,
					  `hora` time NOT NULL,
					  `lembrete` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					  `compartilhar` varchar(100) NOT NULL,
					  `status` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$ragenda = mysqli_query($cnx2,$agenda);			

					$alertas = "CREATE TABLE `alertas` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `alerta` varchar(255) NOT NULL,
					  `url` varchar(120) NOT NULL,
					  `data` datetime NOT NULL,
					  `entrege` int(2) NOT NULL,
					  `lido` enum('N','S') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$ralertas = mysqli_query($cnx2,$alertas);			
			
			
					$bot_config = "CREATE TABLE `bot_config` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `empresa` varchar(160) NOT NULL,
					  `titulo_login` varchar(110) NOT NULL,
					  `descricao_login` text NOT NULL,
					  `logo` varchar(140) NOT NULL,
					  `logo_login` varchar(140) NOT NULL,
					  `cor_menu` varchar(25) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rbot_config = mysqli_query($cnx2,$bot_config);			

				   $whats_config = "CREATE TABLE `whats_config` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`titulo` varchar(100) NOT NULL,
					`descricao` varchar(200) NOT NULL,
					`titulo_vencida` varchar(100) NOT NULL,
					`descricao_vencida` varchar(200) NOT NULL,
					`img` varchar(140) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rwhats_config = mysqli_query($cnx2,$whats_config);			
			
					$contatos_agenda = "CREATE TABLE `contatos_agenda` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `cod_pais` bigint(20) NOT NULL,
					  `cod_estado` bigint(20) NOT NULL,
					  `telefone` varchar(20) NOT NULL,
					  `destination` varchar(15) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `erro` enum('0', '1') NOT NULL,
					  `situacao` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcontatos_agenda = mysqli_query($cnx2,$contatos_agenda);	
			
					$contatos_bot = "CREATE TABLE `contatos_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `whatsapp` varchar(20) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `situacao` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					mysqli_query($cnx2,$contatos_bot);			
			
					$cadastro = "CREATE TABLE `cadastro` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `usuario` varchar(255) NOT NULL,
					  `categoria` bigint(20) NOT NULL,
					  `nome` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `cod_pais` bigint(20) NOT NULL,
					  `cod_estado` bigint(20) NOT NULL,
					  `telefone` varchar(20) NOT NULL,
					  `tel_tipo` varchar(15) NOT NULL,
					  `destination` varchar(15) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `erro` enum('0', '1') NOT NULL,
					  `situacao` enum('0', '1') NOT NULL,
					  `aceito` enum('SIM', 'NAO') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcadastro = mysqli_query($cnx2,$cadastro);				
			
					$cadastro_erro = "CREATE TABLE `cadastro_erro` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `destination` varchar(15) NOT NULL,
					  `active` int(11) NOT NULL,
					  `operadora` varchar(30) NOT NULL,
					  `pais` varchar(30) NOT NULL,
					  `date` datetime NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcadastro_erro = mysqli_query($cnx2,$cadastro_erro);				
			
			
					$categoria_cadastro = "CREATE TABLE `categoria_cadastro` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `usuario` varchar(255) NOT NULL,
					  `descricao` varchar(255) NOT NULL,
					  `campos` text NOT NULL,
					  `sms` bigint(20) NOT NULL,
					  `emails` bigint(20) NOT NULL,
					  `registros` bigint(20) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcategoria_cadastro = mysqli_query($cnx2,$categoria_cadastro);			
			
			
					$grupos_bot = "CREATE TABLE `grupos_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `nome` varchar(100) NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rgrupos_bot = mysqli_query($cnx2,$grupos_bot);				
			
			
				   /*Ação do Log login*/
			       $resposta_logAcesso = "CREATE TABLE `log_acesso_".date('Y')."` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `ip` varchar(20) NOT NULL,
					  `city` varchar(120) NOT NULL,
					  `log` text NOT NULL,
					  `data` datetime NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rresposta_logAcesso = mysqli_query($cnx2,$resposta_logAcesso);

					$ip = $_SERVER['REMOTE_ADDR'];
					$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
					//echo $details->ip; // -> "Retorna IP"
					//echo $details->city; // -> "Retorna cidade"	  		

					$log = "ip:".$details->ip." | hostname:".$details->hostname." | city:".$details->city." | region:".$details->region." | country:".$details->country." | loc:".$details->loc." | org:".$details->org." | postal:".$details->postal." | timezone:".$details->timezone." | readme:".$details->readme;
			
					$insert_logAcesso = "INSERT INTO `log_acesso_".date('Y')."` (
					`ip`,
					`city`,
					`log`, 
					`data`
					) VALUES
					( '".$ip."', '".$details->city."', '".$log."', NOW() );
					";
					$rinsert_logAcesso = mysqli_query($cnx2,$insert_logAcesso);			
					/*-------------------------------------------*/				
			
			
					$perguntas_bot = "CREATE TABLE `perguntas_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `id_grupo` int(11) NOT NULL,
					  `pergunta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rperguntas_bot = mysqli_query($cnx2,$perguntas_bot);			
			
			
					$pesquisa_bot = "CREATE TABLE `pesquisa_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_logado` int(11) NOT NULL,
					  `pesquisa` varchar(120) NOT NULL,
					  `id_config` int(11) NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rpesquisa_bot = mysqli_query($cnx2,$pesquisa_bot);

					$pesquisa_bot_participante = "CREATE TABLE `pesquisa_bot_participante` (
						`id` int(20) NOT NULL AUTO_INCREMENT,
						`fone` varchar(20) NOT NULL,
						`nome` varchar(120) NOT NULL,
						`email` varchar(130) NOT NULL,
						`idade` int(11) NOT NULL,
						PRIMARY KEY (id)	
					  )";
					  $rpesquisa_bot_participante= mysqli_query($cnx,$pesquisa_bot_participante);

			        $resposta_bot = "CREATE TABLE `resposta_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `id_grupo` int(11) NOT NULL,
					  `resposta` varchar(120) CHARACTER SET utf8mb4 NOT NULL,
					  `com_text` int(1) NOT NULL,
					  `vin_grupo` int(11) NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rresposta_bot = mysqli_query($cnx2,$resposta_bot);
			
			
			       $resposta_user_bot = "CREATE TABLE `resposta_user_bot_".date('Y')."` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `id_pergunta` int(11) NOT NULL,
					  `id_resposta` int(11) NOT NULL,
					  `participante` varchar(20) NOT NULL,
					  `ano_mes` varchar(10) NOT NULL,
					  `ano` varchar(6) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rresposta_user_bot = mysqli_query($cnx2,$resposta_user_bot);
			
			       $resposta_user_bot = "CREATE TABLE `t_".date('Y')."_smgAgendamento` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `cod_cliente` int(11) NOT NULL,
					  `data` datetime NOT NULL,
					  `grupos` varchar(150) NOT NULL,
					  `mensagem` text NOT NULL,
					  `status` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rresposta_user_bot = mysqli_query($cnx2,$resposta_user_bot);	
			
			
					$planosSms = "CREATE TABLE `sms_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,3) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosSms = mysqli_query($cnx2,$planosSms);		

					$planosSmsI = "INSERT INTO `sms_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1.000 SMSs', 1000, '0.10', 1),
					(2, '5.000 SMSs', 5000, '0.09', 2),
					(3, '10.000 SMSs', 10000, '0.08', 3),
					(4, '50.000 SMSs', 50000, '0.07', 4),
					(5, '100.000 SMSs', 100000, '0.06', 5);
					";
					$rplanosSmsI = mysqli_query($cnx2,$planosSmsI);			
			
					$planosWhats = "CREATE TABLE `whatsapp_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,6) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosWhats = mysqli_query($cnx2,$planosWhats);		

					$planosWhatsI = "INSERT INTO `whatsapp_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1.000 Envios', 1000, '0.1', 1),
					(2, '5.000 Envios', 5000, '0.09', 2),
					(3, '10.000 Envios', 10000, '0.08', 3),
					(4, '20.000 Envios', 20000, '0.07', 4),
					(5, '30.000 Envios', 30000, '0.06', 5),
					(6, '50.000 Envios', 50000, '0.05', 6);
					";
					$rplanosWhatsI = mysqli_query($cnx2,$planosWhatsI);			
			
			
					$planosPesquisa = "CREATE TABLE `pesquisas_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,2) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosPesquisa = mysqli_query($cnx2,$planosPesquisa);		

					$planosPesquisaI = "INSERT INTO `pesquisas_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1 Pesquisa', 1, '788.89', 1),
					(2, '5 Pesquisas', 5, '398.88', 2),
					(3, '8 Pesquisas', 8, '279.99', 3);
					";
					$rplanosPesquisaI = mysqli_query($cnx2,$planosPesquisaI);			
			
					$Ecommerce = "CREATE TABLE `t_".date('Y')."_ecommerce` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_cliente` int(11) NOT NULL,
					`data` datetime NOT NULL  DEFAULT CURRENT_TIMESTAMP,
					`data_vencimento` date NULL,
					`data_pagamento` date NULL,
					`quantidade` int(20) NOT NULL,
					`valor` decimal(20,2) NOT NULL,
					`status` enum('0','1') NOT NULL,



					  PRIMARY KEY (id)
					) ";
					$rEcommerce = mysqli_query($cnx2,$Ecommerce);		

					$EcommercePg = "CREATE TABLE `t_".date('Y')."_ecommerce_pg` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_fatura` int(11) NOT NULL,
					`id_log` varchar(70) NOT NULL,
					`description` varchar(70) NOT NULL,
					`notes` varchar(70) NOT NULL,
					`created_at` varchar(70) NOT NULL,
					`creditado` enum('0','1') NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rEcommercePg = mysqli_query($cnx2,$EcommercePg);			
			
					/*SMS E E-mails*/			
					$smsEnvio = "CREATE TABLE `t_".date('Y')."_smsAgendamento` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_cliente` int(11) NOT NULL,
					`data` datetime NOT NULL,
					`grupos` varchar(150) NOT NULL,
					`mensagem` text NOT NULL,
					`status` enum('0','1') NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rsmsEnvio = mysqli_query($cnx2,$smsEnvio);		

					$smsErros = "CREATE TABLE `t_".date('mY')."_smsErros` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`errorCode` int(11) NOT NULL,
					`errorMessage` text NOT NULL,
					`mes_ano` varchar(10) NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rsmsErros = mysqli_query($cnx2,$smsErros);

					$smsStatuses = "CREATE TABLE `t_".date('mY')."_smsStatuses` (
					`codigo` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`id` varchar(40) NOT NULL,
					`id_lote` varchar(40) NOT NULL,
					`correlationId` varchar(25) NOT NULL,
					`carrierId` int(11) NOT NULL,
					`carrierName` varchar(30) NOT NULL,
					`destination` varchar(15) NOT NULL,
					`sentStatusCode` int(11) NOT NULL,
					`sentStatus` varchar(30) NOT NULL,
					`sentDate` datetime NOT NULL,
					`sentAt` int(11) NOT NULL,
					`deliveredStatusCode` varchar(20) NOT NULL,
					`deliveredStatus` varchar(30) NOT NULL,
					`deliveredDate` datetime NOT NULL,
					`deliveredAt` varchar(30) NOT NULL,
					`updatedDate` datetime NOT NULL,
					`updatedAt` varchar(30) NOT NULL,
					`mes_ano` varchar(10) NOT NULL,
					PRIMARY KEY (codigo)
					)  ";
					$rsmsStatuses = mysqli_query($cnx2,$smsStatuses);


					$eventosSms = "CREATE TABLE `eventos_sms` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`evento` text NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`id_grupo` int(11) NOT NULL,
					`status` enum('0','1') NOT NULL,
					PRIMARY KEY (id)
					) ";
					$reventosSms = mysqli_query($cnx2,$eventosSms);		

			        $smgStatuses = "CREATE TABLE `t_".date('mY')."_smgStatuses` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`mensagem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`destination` varchar(15) NOT NULL,
					`id_pesquisa` int(11) NOT NULL,
					`img` varchar(255) NOT NULL,
					`img_ext` varchar(4) NOT NULL,
					`pdf` varchar(255) NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`enviado` enum('0','1') NOT NULL,
					`aniversario` enum('enviado','num_valido','num_invalido') NOT NULL,
					`campanha` enum('0','1','2') NOT NULL,
					PRIMARY KEY (id)
					)  ";
					$rsmgStatuses = mysqli_query($cnx2,$smgStatuses);
			
					$aniversariantes = "CREATE TABLE `t_".date('mY')."_aniversariantes` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_cadastro` int(11) NOT NULL,
					`mensagem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`destination` varchar(15) NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`enviado` enum('0','1') NOT NULL,
					`aniversario` enum('enviado','num_valido','num_invalido') NOT NULL,
					PRIMARY KEY (id)
					)  ";
					$raniversariantes = mysqli_query($cnx2,$aniversariantes);			
			
					$emailStatuses = "CREATE TABLE `t_".date('mY')."_emailStatuses` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`campanha` int(11) NOT NULL,
					`nome` varchar(210) NOT NULL,
					`email` varchar(210) NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`enviado` enum('0','1','2') NOT NULL,
					`log_erro` text NOT NULL,
					PRIMARY KEY (id)
					)  ";
					mysqli_query($cnx2,$emailStatuses);				
			
					$numeros_invalidos = "CREATE TABLE `numeros_invalidos` (
					`codigo` int(20) NOT NULL AUTO_INCREMENT,
					`id` varchar(40) NOT NULL,
					`carrierName` varchar(30) NOT NULL,
					`correlationId` varchar(25) NOT NULL,
					`destination` varchar(15) NOT NULL,
					PRIMARY KEY (codigo),
					UNIQUE KEY (destination)
					) ";
					$rnumeros_invalidos = mysqli_query($cnx2,$numeros_invalidos);			
			

					$MensagemNiver = "CREATE TABLE `mensagem_niver` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`mensagem` text NOT NULL,
					`data_hoje` varchar(6) NOT NULL,
					`data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
					) ";
					$rMensagemNiver = mysqli_query($cnx2,$MensagemNiver);			


					$Campanhas = "CREATE TABLE `campanhas` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`nome` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`mensagem1` text NOT NULL,
					`mensagem2` text NOT NULL,
					`mensagem3` text NOT NULL,
					`mensagem4` text NOT NULL,
					`mensagem5` text NOT NULL,
					`mensagem6` text NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rCampanhas = mysqli_query($cnx2,$Campanhas);		


					$Campanhas = "CREATE TABLE `campanhas` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`nome` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`mensagem1` text NOT NULL,
					`mensagem2` text NOT NULL,
					`mensagem3` text NOT NULL,
					`mensagem4` text NOT NULL,
					`mensagem5` text NOT NULL,
					`mensagem6` text NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rCampanhas = mysqli_query($cnx2,$Campanhas);		
	

					$mailmarketing = "CREATE TABLE `mailmarketing` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`nome` varchar(255) NOT NULL,
					`email` varchar(255) NOT NULL,
					`assinatura` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`categorias` text NOT NULL,
					`mensagem` text NOT NULL,
					`situacao` enum('0','1') NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rmailmarketing = mysqli_query($cnx2,$mailmarketing);

					$emailAgendamento = "CREATE TABLE `t_".date('Y')."_emailAgendamento` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_cliente` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`grupos` varchar(150) NOT NULL,
					`campanha` int(11) NOT NULL,
					`lote` varchar(255) NOT NULL,
					`processados` int(20) NOT NULL,
					`status` int(11) NOT NULL,
					PRIMARY KEY (id)
					) ";
					$remailAgendamento = mysqli_query($cnx2,$emailAgendamento);		

					$SendEmails = "CREATE TABLE `t_email_".$codigo."_".date('Ym')."` (
					`codigo` int(20) NOT NULL AUTO_INCREMENT,
					`lote` varchar(255) NOT NULL,
					`nome` varchar(255) NOT NULL,
					`email` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`mensagem` text NOT NULL,
					`enviada` enum('0','1') NOT NULL, 
					`lida` enum('0','1') NOT NULL,
					`clicada` enum('0','1') NOT NULL,
					`bloqueada` enum('0','1') NOT NULL,
					`retornada` enum('0','1') NOT NULL,
					PRIMARY KEY (codigo)
					) ";
					$rSendEmails = mysqli_query($cnx2,$SendEmails);
			
			
					$planosEmail = "CREATE TABLE `email_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,3) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosEmail = mysqli_query($cnx2,$planosEmail);		

					$planosEmailI = "INSERT INTO `email_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1.000 E-mails', 1000, '15.00', 1),
					(2, '5.000 E-mails', 5000, '30.00', 2),
					(3, '10.000 E-mails', 10000, '40.00', 3),
					(4, '50.000 E-mails', 50000, '150.00', 4),
					(5, '100.000 E-mails', 100000, '250.00', 5);
					";
					$rplanosEmailI = mysqli_query($cnx2,$planosEmailI);		
					/*--------------------------------*/			
			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca = "CREATE TABLE `asaas_cobranca_sms` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,					  
					  
					  PRIMARY KEY (id_rg)
					) ";
					$rAsaasCobranca = mysqli_query($cnx2,$AsaasCobranca);			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca2 = "CREATE TABLE `asaas_cobranca_whatsapp` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,					  
					  
					  PRIMARY KEY (id_rg)
					) ";
					$rAsaasCobranca2 = mysqli_query($cnx2,$AsaasCobranca2);			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca3 = "CREATE TABLE `asaas_cobranca_pesquisa` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,						  
					  
					  PRIMARY KEY (id_rg)
					) ";
					$rAsaasCobranca3 = mysqli_query($cnx2,$AsaasCobranca3);
					/*--------------------------------*/			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca4 = "CREATE TABLE `asaas_cobranca_email` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,					  
					  
					  PRIMARY KEY (id_rg)
					) ";
					mysqli_query($cnx2,$AsaasCobranca4);
					/*--------------------------------*/			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobrancaClientes = "CREATE TABLE `cobrancas` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  `mes_ano` varchar(10) NOT NULL,
					  PRIMARY KEY (id_rg)
					) ";
					mysqli_query($cnx2,$AsaasCobrancaClientes);


					/*--------------------------------*/			
			
			
					$Clientes = "CREATE TABLE `clientes` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_asaas` varchar(50) NOT NULL,
					`billingType` varchar(50) NOT NULL,
					`nome` varchar(255) NOT NULL,
					`email` varchar(255) NOT NULL,
					`emails` text NOT NULL,
					`tipo_pessoa` varchar(3) NOT NULL,
					`cpf_cnpj` varchar(30) NOT NULL,
					`celular` char(20) NOT NULL,
					`celulares` varchar(160) NOT NULL,
					`estado` bigint(20) NOT NULL,
					`cidade` bigint(20) NOT NULL,
					`cep` varchar(20) NOT NULL,
					`endereco` varchar(255) NOT NULL,
					`numero` varchar(10) NOT NULL,
					`bairro` varchar(80) NOT NULL,
					`complemento` varchar(150) NOT NULL,
					`data_nascimento` date NOT NULL,
					`sexo` enum('M','F') NOT NULL,
					`ativo` enum('0','1') NOT NULL,
					`dia_vencimento` int(10) NOT NULL,
					`envio` int(2) NOT NULL,
					`gerar` int(2) NOT NULL,
					`ano_mes` varchar(10) NOT NULL,
					`frequencia` varchar(30) NOT NULL,
					`data_cadastro` datetime NOT NULL,
					 PRIMARY KEY (id),
					 UNIQUE KEY (email),
					 UNIQUE KEY (celular)					
					)";
					mysqli_query($cnx2,$Clientes);			
			
					$Servicos = "CREATE TABLE `servicos` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_cliente` int(20) NOT NULL,
					`id_tipo_servico` int(20) NOT NULL,
					`descricao` varchar(255) NOT NULL,
					`valor` decimal(20,2) NOT NULL,
					`valor_desconto` decimal(20,2) NOT NULL,
					`data_adesao` date NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx2,$Servicos);	
			
					$ServicosTipo = "CREATE TABLE `servicos_tipo` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`descricao` varchar(255) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx2,$ServicosTipo);				

					$Transferencias = "CREATE TABLE `minhas_transferencias` (			
					`id_transferencia` int(20) NOT NULL AUTO_INCREMENT,
					`id` varchar(150) NOT NULL,
					`dateCreated` date NOT NULL,
					`status` varchar(30) NOT NULL,
					`effectiveDate` datetime NOT NULL,
					`type` varchar(40) NOT NULL,
					`value` decimal(20,2) NOT NULL,
					`netValue` decimal(20,2) NOT NULL,
					`transferFee` decimal(20,2) NOT NULL,
					`scheduleDate` date NOT NULL,
					`authorized` varchar(3) NOT NULL,
					`code` varchar(10) NOT NULL,
					`name` varchar(200) NOT NULL,
					`accountName` varchar(100) NOT NULL,
					`ownerName` varchar(200) NOT NULL,
					`cpfCnpj` varchar(25) NOT NULL,
					`agency` varchar(25) NOT NULL,
					`agencyDigit` varchar(3) NOT NULL,
					`account` varchar(28) NOT NULL,
					`accountDigit` varchar(3) NOT NULL,
					`transactionReceiptUrl` varchar(255) NOT NULL,
					 PRIMARY KEY (id_transferencia)				
					)";
					mysqli_query($cnx2,$Transferencias);	
						
			
					$ConfigNF = "CREATE TABLE `config_nf` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_cliente` int(20) NOT NULL,
					`installment` varchar(130) NOT NULL,
					`serviceDescription` varchar(255) NOT NULL,
					`observations` varchar(255) NOT NULL,
					`deductions` decimal(20,2) NOT NULL,
					`retainIss` varchar(5) NOT NULL,
					`iss` decimal(20,2) NOT NULL,
					`cofins` decimal(20,2) NOT NULL,
					`csll` decimal(20,2) NOT NULL,
					`inss` decimal(20,2) NOT NULL,
					`ir` decimal(20,2) NOT NULL,
					`pis` decimal(20,2) NOT NULL,
					`municipalServiceCode` float NOT NULL,
					`municipalServiceName` varchar(255) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx2,$ConfigNF);			
			
					$NotaFiscal = "CREATE TABLE `nota_fiacal_".date('Y')."` (			
					`id_nota` int(20) NOT NULL AUTO_INCREMENT,
					`object` varchar(50) NOT NULL,
					`id` varchar(150) NOT NULL,
					`status` varchar(100) NOT NULL,
					`customer` varchar(50) NOT NULL,
					`type` varchar(20) NOT NULL,
					`statusDescription` varchar(50) NOT NULL,
					`serviceDescription` varchar(255) NOT NULL,
					`pdfUrl` varchar(200) NOT NULL,
					`xmlUrl` varchar(200) NOT NULL,
					`rpsSerie` varchar(200) NOT NULL,
					`rpsNumber` varchar(200) NOT NULL,
					`number` varchar(100) NOT NULL,
					`validationCode` varchar(100) NOT NULL,
					`value` decimal(20,2) NOT NULL,
					`deductions` decimal(20,2) NOT NULL,
					`effectiveDate` date NOT NULL,
					`observations` varchar(255) NOT NULL,
					`estimatedTaxesDescription` varchar(255) NOT NULL,
					`payment` varchar(30) NOT NULL,
					`installment` varchar(130) NOT NULL,
					`retainIss` varchar(5) NOT NULL,
					`iss` decimal(20,2) NOT NULL,
					`cofins` decimal(20,2) NOT NULL,
					`csll` decimal(20,2) NOT NULL,
					`inss` decimal(20,2) NOT NULL,
					`ir` decimal(20,2) NOT NULL,
					`pis` decimal(20,2) NOT NULL,
					`municipalServiceId` varchar(100) NOT NULL,
					`municipalServiceCode` float NOT NULL,
					`municipalServiceName` varchar(255) NOT NULL,
					 PRIMARY KEY (id_nota)				
					)";
					mysqli_query($cnx2,$NotaFiscal);				
			
					$MovBot = "CREATE TABLE `mov_bot_".date('mY')."` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(11) NOT NULL,
					`id_whats` varchar(100) NOT NULL,
					`de_quem` varchar(100) NOT NULL,
					`para_quem` varchar(100) NOT NULL,
					`mensagem` text NOT NULL,
					`ackRes` int(2) NOT NULL,
					`retorno_log` text NOT NULL,
					`data_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`entregue` int(1) NOT NULL,
					`status` int(1) NOT NULL,
					`status_bot` int(1) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx2,$MovBot);			
			
					$MovBot2 = "CREATE TABLE `mov_bot_cliente_".date('mY')."` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`de_quem` varchar(100) NOT NULL,
					`id_user` int(20) NOT NULL,
					`data` date NOT NULL,
					`data_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx2,$MovBot2);				
			
			
					$saldacao = "CREATE TABLE `bot_whats_saldacao` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(20) NOT NULL,
					`saldacao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`palavra_chave` varchar(255) NOT NULL,
					 PRIMARY KEY (id)					
					)";
					mysqli_query($cnx2,$saldacao);			
			
			
					$bot_whats_menu = "CREATE TABLE `bot_whats_menu` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,	
					`instancia` int(11) NOT NULL,
					`id_saldacao` int(11) NOT NULL,		
					`opcao` int(11) NOT NULL,
					`conteudo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx2,$bot_whats_menu);	
			
					$bot_whats_menu_resposta = "CREATE TABLE `bot_whats_menu_resposta` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,	
					`instancia` int(11) NOT NULL,
					`id_menu` int(11) NOT NULL,		
					`conteudo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`arquivo` varchar(200) NOT NULL, 
					`ext_arquivo` varchar(6) NOT NULL, 
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx2,$bot_whats_menu_resposta);	
			
			
			
		} else {
			//echo 'Erro ao criar o banco de dados: ' . mysqli_error() . "\n";
			
				   $cnx3 = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot_".$codigo) ;
			
					$login_acesso = "CREATE TABLE `login_acesso` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `foto_perfil` varchar(60) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `celular` char(20) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `sexo` enum('M', 'F') NOT NULL,
					  `senha` text NOT NULL,
					  `senha_ver` varchar(25) NOT NULL,
					  `perfil` text NOT NULL,
					  `data_cadastro` datetime NOT NULL,
					  PRIMARY KEY (id),
					  UNIQUE KEY (email),
					  UNIQUE KEY (celular)
					)";
					mysqli_query($cnx3,$login_acesso);			
			
					$agenda = "CREATE TABLE `agenda` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `data` date NOT NULL,
					  `hora` time NOT NULL,
					  `lembrete` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					  `compartilhar` varchar(100) NOT NULL,
					  `status` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$ragenda = mysqli_query($cnx3,$agenda);			

					$alertas = "CREATE TABLE `alertas` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `alerta` varchar(255) NOT NULL,
					  `url` varchar(120) NOT NULL,
					  `data` datetime NOT NULL,
					  `entrege` int(2) NOT NULL,
					  `lido` enum('N','S') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$ralertas = mysqli_query($cnx3,$alertas);			
			
			
					$bot_config = "CREATE TABLE `bot_config` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `empresa` varchar(160) NOT NULL,
					  `titulo_login` varchar(110) NOT NULL,
					  `descricao_login` text NOT NULL,
					  `logo` varchar(140) NOT NULL,
					  `logo_login` varchar(140) NOT NULL,
					  `cor_menu` varchar(25) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rbot_config = mysqli_query($cnx3,$bot_config);			

				   $whats_config = "CREATE TABLE `whats_config` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`titulo` varchar(100) NOT NULL,
					`descricao` varchar(200) NOT NULL,
					`titulo_vencida` varchar(100) NOT NULL,
					`descricao_vencida` varchar(200) NOT NULL,
					`img` varchar(140) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rwhats_config = mysqli_query($cnx3,$whats_config);				
			
					$contatos_agenda = "CREATE TABLE `contatos_agenda` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `cod_pais` bigint(20) NOT NULL,
					  `cod_estado` bigint(20) NOT NULL,
					  `telefone` varchar(20) NOT NULL,
					  `destination` varchar(15) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `erro` enum('0', '1') NOT NULL,
					  `situacao` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcontatos_agenda = mysqli_query($cnx3,$contatos_agenda);				
			
					$contatos_bot = "CREATE TABLE `contatos_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `whatsapp` varchar(20) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `situacao` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					mysqli_query($cnx3,$contatos_bot);			
			
					$cadastro = "CREATE TABLE `cadastro` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `usuario` varchar(255) NOT NULL,
					  `categoria` bigint(20) NOT NULL,
					  `nome` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `cod_pais` bigint(20) NOT NULL,
					  `cod_estado` bigint(20) NOT NULL,
					  `telefone` varchar(20) NOT NULL,
					  `tel_tipo` varchar(15) NOT NULL,
					  `destination` varchar(15) NOT NULL,
					  `data_nascimento` date NOT NULL,
					  `erro` enum('0', '1') NOT NULL,
					  `situacao` enum('0', '1') NOT NULL,
					  `aceito` enum('SIM', 'NAO') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcadastro = mysqli_query($cnx3,$cadastro);				
			
					$cadastro_erro = "CREATE TABLE `cadastro_erro` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `destination` varchar(15) NOT NULL,
					  `active` int(11) NOT NULL,
					  `operadora` varchar(30) NOT NULL,
					  `pais` varchar(30) NOT NULL,
					  `date` datetime NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcadastro_erro = mysqli_query($cnx3,$cadastro_erro);				
			
			
					$categoria_cadastro = "CREATE TABLE `categoria_cadastro` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `usuario` varchar(255) NOT NULL,
					  `descricao` varchar(255) NOT NULL,
					  `campos` text NOT NULL,
					  `sms` bigint(20) NOT NULL,
					  `emails` bigint(20) NOT NULL,
					  `registros` bigint(20) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rcategoria_cadastro = mysqli_query($cnx3,$categoria_cadastro);			
			
			
					$grupos_bot = "CREATE TABLE `grupos_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `nome` varchar(100) NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rgrupos_bot = mysqli_query($cnx3,$grupos_bot);				
			
			
				   /*Ação do Log login*/
			       $resposta_logAcesso = "CREATE TABLE `log_acesso_".date('Y')."` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `ip` varchar(20) NOT NULL,
					  `city` varchar(120) NOT NULL,
					  `log` text NOT NULL,
					  `data` datetime NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rresposta_logAcesso = mysqli_query($cnx3,$resposta_logAcesso);

					$ip = $_SERVER['REMOTE_ADDR'];
					$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
					//echo $details->ip; // -> "Retorna IP"
					//echo $details->city; // -> "Retorna cidade"	  		

					$log = "ip:".$details->ip." | hostname:".$details->hostname." | city:".$details->city." | region:".$details->region." | country:".$details->country." | loc:".$details->loc." | org:".$details->org." | postal:".$details->postal." | timezone:".$details->timezone." | readme:".$details->readme;
			
					$insert_logAcesso = "INSERT INTO `log_acesso_".date('Y')."` (
					`ip`,
					`city`,
					`log`, 
					`data`
					) VALUES
					( '".$ip."', '".$details->city."', '".$log."', NOW() );
					";
					$rinsert_logAcesso = mysqli_query($cnx3,$insert_logAcesso);			
					/*-------------------------------------------*/				
			
			
					$perguntas_bot = "CREATE TABLE `perguntas_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `id_grupo` int(11) NOT NULL,
					  `pergunta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rperguntas_bot = mysqli_query($cnx3,$perguntas_bot);			
			
			
					$pesquisa_bot = "CREATE TABLE `pesquisa_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_logado` int(11) NOT NULL,
					  `pesquisa` varchar(120) NOT NULL,
					  `id_config` int(11) NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rpesquisa_bot = mysqli_query($cnx3,$pesquisa_bot);

					$pesquisa_bot_participante = "CREATE TABLE `pesquisa_bot_participante` (
						`id` int(20) NOT NULL AUTO_INCREMENT,
						`fone` varchar(20) NOT NULL,
						`nome` varchar(120) NOT NULL,
						`email` varchar(130) NOT NULL,
						`idade` int(11) NOT NULL,
						PRIMARY KEY (id)	
					  )";
					$rpesquisa_bot_participante= mysqli_query($cnx,$pesquisa_bot_participante);

			        $resposta_bot = "CREATE TABLE `resposta_bot` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `id_grupo` int(11) NOT NULL,
					  `resposta` varchar(120) CHARACTER SET utf8mb4 NOT NULL,
					  `com_text` int(1) NOT NULL,
					  `vin_grupo` int(11) NOT NULL,
					  PRIMARY KEY (id)	
					)";
					$rresposta_bot = mysqli_query($cnx3,$resposta_bot);
			
			
			       $resposta_user_bot = "CREATE TABLE `resposta_user_bot_".date('Y')."` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `id_pesquisa` int(11) NOT NULL,
					  `id_pergunta` int(11) NOT NULL,
					  `id_resposta` int(11) NOT NULL,
					  `participante` varchar(20) NOT NULL,
					  `ano_mes` varchar(10) NOT NULL,
					  `ano` varchar(6) NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rresposta_user_bot = mysqli_query($cnx3,$resposta_user_bot);
			
			       $resposta_user_bot = "CREATE TABLE `t_".date('Y')."_smgAgendamento` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `cod_cliente` int(11) NOT NULL,
					  `data` datetime NOT NULL,
					  `grupos` varchar(150) NOT NULL,
					  `mensagem` text NOT NULL,
					  `status` enum('0', '1') NOT NULL,
					  PRIMARY KEY (id)
					)";
					$rresposta_user_bot = mysqli_query($cnx3,$resposta_user_bot);			
			
					$planosSms = "CREATE TABLE `sms_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,3) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosSms = mysqli_query($cnx3,$planosSms);		

					$planosSmsI = "INSERT INTO `sms_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1.000 SMSs', 1000, '0.10', 1),
					(2, '5.000 SMSs', 5000, '0.09', 2),
					(3, '10.000 SMSs', 10000, '0.08', 3),
					(4, '50.000 SMSs', 50000, '0.07', 4),
					(5, '100.000 SMSs', 100000, '0.06', 5);
					";
					$rplanosSmsI = mysqli_query($cnx3,$planosSmsI);			
			
					$planosWhats = "CREATE TABLE `whatsapp_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,6) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosWhats = mysqli_query($cnx3,$planosWhats);		

					$planosWhatsI = "INSERT INTO `whatsapp_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1.000 Envios', 1000, '0.1', 1),
					(2, '5.000 Envios', 5000, '0.09', 2),
					(3, '10.000 Envios', 10000, '0.08', 3),
					(4, '20.000 Envios', 20000, '0.07', 4),
					(5, '30.000 Envios', 30000, '0.06', 5),
					(6, '50.000 Envios', 50000, '0.05', 6);
					";
					$rplanosWhatsI = mysqli_query($cnx3,$planosWhatsI);		
			
			
			
					$planosPesquisa = "CREATE TABLE `pesquisas_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,2) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosPesquisa = mysqli_query($cnx3,$planosPesquisa);		

					$planosPesquisaI = "INSERT INTO `pesquisas_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1 Pesquisa', 1, '788.89', 1),
					(2, '5 Pesquisas', 5, '398.88', 2),
					(3, '8 Pesquisas', 8, '279.99', 3);
					";
					$rplanosPesquisaI = mysqli_query($cnx3,$planosPesquisaI);				
			
			
					$Ecommerce = "CREATE TABLE `t_".date('Y')."_ecommerce` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_cliente` int(11) NOT NULL,
					`data` datetime NOT NULL  DEFAULT CURRENT_TIMESTAMP,
					`data_vencimento` date NULL,
					`data_pagamento` date NULL,
					`quantidade` int(20) NOT NULL,
					`valor` decimal(20,2) NOT NULL,
					`status` enum('0','1') NOT NULL,



					  PRIMARY KEY (id)
					) ";
					$rEcommerce = mysqli_query($cnx3,$Ecommerce);		

					$EcommercePg = "CREATE TABLE `t_".date('Y')."_ecommerce_pg` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_fatura` int(11) NOT NULL,
					`id_log` varchar(70) NOT NULL,
					`description` varchar(70) NOT NULL,
					`notes` varchar(70) NOT NULL,
					`created_at` varchar(70) NOT NULL,
					`creditado` enum('0','1') NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rEcommercePg = mysqli_query($cnx3,$EcommercePg);			
			
					/*SMS E E-mails*/			
					$smsEnvio = "CREATE TABLE `t_".date('Y')."_smsAgendamento` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_cliente` int(11) NOT NULL,
					`data` datetime NOT NULL,
					`grupos` varchar(150) NOT NULL,
					`mensagem` text NOT NULL,
					`status` enum('0','1') NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rsmsEnvio = mysqli_query($cnx3,$smsEnvio);		

					$smsErros = "CREATE TABLE `t_".date('mY')."_smsErros` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`errorCode` int(11) NOT NULL,
					`errorMessage` text NOT NULL,
					`mes_ano` varchar(10) NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rsmsErros = mysqli_query($cnx3,$smsErros);

					$smsStatuses = "CREATE TABLE `t_".date('mY')."_smsStatuses` (
					`codigo` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`id` varchar(40) NOT NULL,
					`id_lote` varchar(40) NOT NULL,
					`correlationId` varchar(25) NOT NULL,
					`carrierId` int(11) NOT NULL,
					`carrierName` varchar(30) NOT NULL,
					`destination` varchar(15) NOT NULL,
					`sentStatusCode` int(11) NOT NULL,
					`sentStatus` varchar(30) NOT NULL,
					`sentDate` datetime NOT NULL,
					`sentAt` int(11) NOT NULL,
					`deliveredStatusCode` varchar(20) NOT NULL,
					`deliveredStatus` varchar(30) NOT NULL,
					`deliveredDate` datetime NOT NULL,
					`deliveredAt` varchar(30) NOT NULL,
					`updatedDate` datetime NOT NULL,
					`updatedAt` varchar(30) NOT NULL,
					`mes_ano` varchar(10) NOT NULL,
					PRIMARY KEY (codigo)
					)  ";
					$rsmsStatuses = mysqli_query($cnx3,$smsStatuses);


					$eventosSms = "CREATE TABLE `eventos_sms` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`evento` text NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`id_grupo` int(11) NOT NULL,
					`status` enum('0','1') NOT NULL,
					PRIMARY KEY (id)
					) ";
					$reventosSms = mysqli_query($cnx3,$eventosSms);		

					$smgStatuses = "CREATE TABLE `t_".date('mY')."_smgStatuses` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`mensagem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`destination` varchar(15) NOT NULL,
					`id_pesquisa` int(11) NOT NULL,
					`img` varchar(255) NOT NULL,
					`img_ext` varchar(4) NOT NULL,
					`pdf` varchar(255) NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`enviado` enum('0','1') NOT NULL,
					`aniversario` enum('enviado','num_valido','num_invalido') NOT NULL,
					`campanha` enum('0','1','2') NOT NULL,
					PRIMARY KEY (id)
					)  ";
					$rsmgStatuses = mysqli_query($cnx3,$smgStatuses);
			
					$aniversariantes = "CREATE TABLE `t_".date('mY')."_aniversariantes` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_cadastro` int(11) NOT NULL,
					`mensagem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`destination` varchar(15) NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`enviado` enum('0','1') NOT NULL,
					`aniversario` enum('enviado','num_valido','num_invalido') NOT NULL,
					PRIMARY KEY (id)
					)  ";
					$raniversariantes = mysqli_query($cnx3,$aniversariantes);			
			
					$emailStatuses = "CREATE TABLE `t_".date('mY')."_emailStatuses` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_grupo` int(11) NOT NULL,
					`campanha` int(11) NOT NULL,
					`nome` varchar(210) NOT NULL,
					`email` varchar(210) NOT NULL,
					`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`enviado` enum('0','1','2') NOT NULL,
					`log_erro` text NOT NULL,
					PRIMARY KEY (id)
					)  ";
					mysqli_query($cnx3,$emailStatuses);				
			
					$numeros_invalidos = "CREATE TABLE `numeros_invalidos` (
					`codigo` int(20) NOT NULL AUTO_INCREMENT,
					`id` varchar(40) NOT NULL,
					`carrierName` varchar(30) NOT NULL,
					`correlationId` varchar(25) NOT NULL,
					`destination` varchar(15) NOT NULL,
					PRIMARY KEY (codigo),
					UNIQUE KEY (destination)
					) ";
					$rnumeros_invalidos = mysqli_query($cnx3,$numeros_invalidos);
					

					$MensagemNiver = "CREATE TABLE `mensagem_niver` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`mensagem` text NOT NULL,
					`data_hoje` varchar(6) NOT NULL,
					`data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
					) ";
					$rMensagemNiver = mysqli_query($cnx3,$MensagemNiver);			


					$Campanhas = "CREATE TABLE `campanhas` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`nome` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`mensagem1` text NOT NULL,
					`mensagem2` text NOT NULL,
					`mensagem3` text NOT NULL,
					`mensagem4` text NOT NULL,
					`mensagem5` text NOT NULL,
					`mensagem6` text NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rCampanhas = mysqli_query($cnx3,$Campanhas);		


					$Campanhas = "CREATE TABLE `campanhas` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`nome` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`mensagem1` text NOT NULL,
					`mensagem2` text NOT NULL,
					`mensagem3` text NOT NULL,
					`mensagem4` text NOT NULL,
					`mensagem5` text NOT NULL,
					`mensagem6` text NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rCampanhas = mysqli_query($cnx3,$Campanhas);		
	

					$mailmarketing = "CREATE TABLE `mailmarketing` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`nome` varchar(255) NOT NULL,
					`email` varchar(255) NOT NULL,
					`assinatura` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`categorias` text NOT NULL,
					`mensagem` text NOT NULL,
					`situacao` enum('0','1') NOT NULL,
					PRIMARY KEY (id)
					) ";
					$rmailmarketing = mysqli_query($cnx3,$mailmarketing);

					$emailAgendamento = "CREATE TABLE `t_".date('Y')."_emailAgendamento` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`cod_cliente` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`grupos` varchar(150) NOT NULL,
					`campanha` int(11) NOT NULL,
					`lote` varchar(255) NOT NULL,
					`processados` int(20) NOT NULL,
					`status` int(11) NOT NULL,
					PRIMARY KEY (id)
					) ";
					$remailAgendamento = mysqli_query($cnx3,$emailAgendamento);		

					$SendEmails = "CREATE TABLE `t_email_".$codigo."_".date('Ym')."` (
					`codigo` int(20) NOT NULL AUTO_INCREMENT,
					`lote` varchar(255) NOT NULL,
					`nome` varchar(255) NOT NULL,
					`email` varchar(255) NOT NULL,
					`data` datetime NOT NULL,
					`mensagem` text NOT NULL,
					`enviada` enum('0','1') NOT NULL, 
					`lida` enum('0','1') NOT NULL,
					`clicada` enum('0','1') NOT NULL,
					`bloqueada` enum('0','1') NOT NULL,
					`retornada` enum('0','1') NOT NULL,
					PRIMARY KEY (codigo)
					) ";
					$rSendEmails = mysqli_query($cnx3,$SendEmails);
			
			
					$planosEmail = "CREATE TABLE `email_planos` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `nome` varchar(255) NOT NULL,
					  `quantidade` bigint(20) NOT NULL,
					  `valor_unitario` decimal(20,3) NOT NULL,
					  `ordem` int(2) NOT NULL,
					  PRIMARY KEY (id)
					) ";
					$rplanosEmail = mysqli_query($cnx3,$planosEmail);		

					$planosEmailI = "INSERT INTO `email_planos` (`id`, `nome`, `quantidade`, `valor_unitario`, `ordem`) VALUES
					(1, '1.000 E-mails', 1000, '15.00', 1),
					(2, '5.000 E-mails', 5000, '30.00', 2),
					(3, '10.000 E-mails', 10000, '40.00', 3),
					(4, '50.000 E-mails', 50000, '150.00', 4),
					(5, '100.000 E-mails', 100000, '250.00', 5);
					";
					$rplanosEmailI = mysqli_query($cnx3,$planosEmailI);		
					/*--------------------------------*/				
			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca = "CREATE TABLE `asaas_cobranca_sms` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,					  
					  
					  PRIMARY KEY (id_rg)
					) ";
					$rAsaasCobranca = mysqli_query($cnx3,$AsaasCobranca);			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca2 = "CREATE TABLE `asaas_cobranca_whatsapp` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,					  
					  
					  PRIMARY KEY (id_rg)
					) ";
					$rAsaasCobranca2 = mysqli_query($cnx3,$AsaasCobranca2);			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca3 = "CREATE TABLE `asaas_cobranca_pesquisa` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,						  
					  
					  PRIMARY KEY (id_rg)
					) ";
					$rAsaasCobranca3 = mysqli_query($cnx3,$AsaasCobranca3);
					/*--------------------------------*/				
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobranca4 = "CREATE TABLE `asaas_cobranca_email` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `quantidade_plano` int(10) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  
					  `confirmedDate` date NOT NULL,
					  `postalService` varchar(100) NOT NULL,
					  `creditCardNumber` varchar(30) NOT NULL,
					  `creditCardBrand` varchar(130) NOT NULL,
					  `creditCardToken` varchar(160) NOT NULL,					  
					  
					  PRIMARY KEY (id_rg)
					) ";
					mysqli_query($cnx3,$AsaasCobranca4);
					/*--------------------------------*/			
			
			       /*Pagamento Asaas-------------------*/
					$AsaasCobrancaClientes = "CREATE TABLE `cobrancas` (
					  `id_rg` int(20) NOT NULL AUTO_INCREMENT,
					  `object` varchar(30) NOT NULL,
					  `id` varchar(30) NOT NULL,
					  `dateCreated` date NOT NULL,
					  `customer` varchar(30) NOT NULL,
					  `value` decimal(20,2) NOT NULL,
					  `netValue` decimal(20,2) NOT NULL,
					  `originalValue` decimal(20,2) NOT NULL,
					  `interestValue` decimal(20,2) NOT NULL,
					  `description` varchar(200) NOT NULL,
					  `billingType` varchar(20) NOT NULL,
					  `status` varchar(30) NOT NULL,
					  `dueDate` date NOT NULL,
					  `originalDueDate` date NOT NULL,
					  `paymentDate` date NOT NULL,
					  `clientPaymentDate` date NOT NULL,
					  `invoiceUrl` varchar(255) NOT NULL,
					  `invoiceNumber` varchar(100) NOT NULL,
					  `externalReference` varchar(100) NOT NULL,
					  `deleted` varchar(100) NOT NULL,
					  `anticipated` varchar(100) NOT NULL,
					  `creditDate` date NOT NULL,
					  `estimatedCreditDate` date NOT NULL,
					  `bankSlipUrl` varchar(255) NOT NULL,
					  `lastInvoiceViewedDate` datetime NOT NULL,
					  `lastBankSlipViewedDate` date NOT NULL,
					  `mes_ano` varchar(10) NOT NULL,
					  PRIMARY KEY (id_rg)
					) ";
					mysqli_query($cnx3,$AsaasCobrancaClientes);

					/*--------------------------------*/			
			
					$Clientes = "CREATE TABLE `clientes` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_asaas` varchar(50) NOT NULL,
					`billingType` varchar(50) NOT NULL,
					`nome` varchar(255) NOT NULL,
					`email` varchar(255) NOT NULL,
					`emails` text NOT NULL,
					`tipo_pessoa` varchar(3) NOT NULL,
					`cpf_cnpj` varchar(30) NOT NULL,
					`celular` char(20) NOT NULL,
					`celulares` varchar(160) NOT NULL,
					`estado` bigint(20) NOT NULL,
					`cidade` bigint(20) NOT NULL,
					`cep` varchar(20) NOT NULL,
					`endereco` varchar(255) NOT NULL,
					`numero` varchar(10) NOT NULL,
					`bairro` varchar(80) NOT NULL,
					`complemento` varchar(150) NOT NULL,
					`data_nascimento` date NOT NULL,
					`sexo` enum('M','F') NOT NULL,
					`ativo` enum('0','1') NOT NULL,
					`dia_vencimento` int(10) NOT NULL,
					`data_cadastro` datetime NOT NULL,
					`envio` int(2) NOT NULL,
					`gerar` int(2) NOT NULL,
					`ano_mes` varchar(10) NOT NULL,
					`frequencia` varchar(30) NOT NULL,
					 PRIMARY KEY (id),
					 UNIQUE KEY (email),
					 UNIQUE KEY (celular)					
					)";
					mysqli_query($cnx3,$Clientes);			
			 
			
					$Servicos = "CREATE TABLE `servicos` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_cliente` int(20) NOT NULL,
					`id_tipo_servico` int(20) NOT NULL,
					`descricao` varchar(255) NOT NULL,
					`valor` decimal(20,2) NOT NULL,
					`valor_desconto` decimal(20,2) NOT NULL,
					`data_adesao` date NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx3,$Servicos);	
			
					$ServicosTipo = "CREATE TABLE `servicos_tipo` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`descricao` varchar(255) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx3,$ServicosTipo);				

					$Transferencias = "CREATE TABLE `minhas_transferencias` (			
					`id_transferencia` int(20) NOT NULL AUTO_INCREMENT,
					`id` varchar(150) NOT NULL,
					`dateCreated` date NOT NULL,
					`status` varchar(30) NOT NULL,
					`effectiveDate` datetime NOT NULL,
					`type` varchar(40) NOT NULL,
					`value` decimal(20,2) NOT NULL,
					`netValue` decimal(20,2) NOT NULL,
					`transferFee` decimal(20,2) NOT NULL,
					`scheduleDate` date NOT NULL,
					`authorized` varchar(3) NOT NULL,
					`code` varchar(10) NOT NULL,
					`name` varchar(200) NOT NULL,
					`accountName` varchar(100) NOT NULL,
					`ownerName` varchar(200) NOT NULL,
					`cpfCnpj` varchar(25) NOT NULL,
					`agency` varchar(25) NOT NULL,
					`agencyDigit` varchar(3) NOT NULL,
					`account` varchar(28) NOT NULL,
					`accountDigit` varchar(3) NOT NULL,
					`transactionReceiptUrl` varchar(255) NOT NULL,
					 PRIMARY KEY (id_transferencia)				
					)";
					mysqli_query($cnx3,$Transferencias);	
	
			
					$ConfigNF = "CREATE TABLE `config_nf` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`id_cliente` int(20) NOT NULL,
					`installment` varchar(130) NOT NULL,
					`serviceDescription` varchar(255) NOT NULL,
					`observations` varchar(255) NOT NULL,
					`deductions` decimal(20,2) NOT NULL,
					`retainIss` varchar(5) NOT NULL,
					`iss` decimal(20,2) NOT NULL,
					`cofins` decimal(20,2) NOT NULL,
					`csll` decimal(20,2) NOT NULL,
					`inss` decimal(20,2) NOT NULL,
					`ir` decimal(20,2) NOT NULL,
					`pis` decimal(20,2) NOT NULL,
					`municipalServiceCode` float NOT NULL,
					`municipalServiceName` varchar(255) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx3,$ConfigNF);			
					
					$NotaFiscal = "CREATE TABLE `nota_fiacal_".date('Y')."` (			
					`id_nota` int(20) NOT NULL AUTO_INCREMENT,
					`object` varchar(50) NOT NULL,
					`id` varchar(150) NOT NULL,
					`status` varchar(100) NOT NULL,
					`customer` varchar(50) NOT NULL,
					`type` varchar(20) NOT NULL,
					`statusDescription` varchar(50) NOT NULL,
					`serviceDescription` varchar(255) NOT NULL,
					`pdfUrl` varchar(200) NOT NULL,
					`xmlUrl` varchar(200) NOT NULL,
					`rpsSerie` varchar(200) NOT NULL,
					`rpsNumber` varchar(200) NOT NULL,
					`number` varchar(100) NOT NULL,
					`validationCode` varchar(100) NOT NULL,
					`value` decimal(20,2) NOT NULL,
					`deductions` decimal(20,2) NOT NULL,
					`effectiveDate` date NOT NULL,
					`observations` varchar(255) NOT NULL,
					`estimatedTaxesDescription` varchar(255) NOT NULL,
					`payment` varchar(30) NOT NULL,
					`installment` varchar(130) NOT NULL,
					`retainIss` varchar(5) NOT NULL,
					`iss` decimal(20,2) NOT NULL,
					`cofins` decimal(20,2) NOT NULL,
					`csll` decimal(20,2) NOT NULL,
					`inss` decimal(20,2) NOT NULL,
					`ir` decimal(20,2) NOT NULL,
					`pis` decimal(20,2) NOT NULL,
					`municipalServiceId` varchar(100) NOT NULL,
					`municipalServiceCode` float NOT NULL,
					`municipalServiceName` varchar(255) NOT NULL,
					 PRIMARY KEY (id_nota)				
					)";
					mysqli_query($cnx3,$NotaFiscal);				
					
					$MovBot = "CREATE TABLE `mov_bot_".date('mY')."` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(11) NOT NULL,
					`id_whats` varchar(100) NOT NULL,
					`de_quem` varchar(100) NOT NULL,
					`para_quem` varchar(100) NOT NULL,
					`mensagem` text NOT NULL,
					`ackRes` int(2) NOT NULL,
					`retorno_log` text NOT NULL,
					`data_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`entregue` int(1) NOT NULL,
					`status` int(1) NOT NULL,
					`status_bot` int(1) NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx3,$MovBot);				
			
					$MovBot3 = "CREATE TABLE `mov_bot_cliente_".date('mY')."` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`de_quem` varchar(100) NOT NULL,
					`id_user` int(20) NOT NULL,
					`data` date NOT NULL,
					`data_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx3,$MovBot3);				
			
					$saldacao = "CREATE TABLE `bot_whats_saldacao` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(11) NOT NULL,
					`saldacao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`palavra_chave` varchar(255) NOT NULL,
					 PRIMARY KEY (id)						
					)";
					mysqli_query($cnx3,$saldacao);			
			
			
					$bot_whats_menu = "CREATE TABLE `bot_whats_menu` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,	
					`instancia` int(11) NOT NULL,
					`id_saldacao` int(11) NOT NULL,		
					`opcao` int(11) NOT NULL,
					`conteudo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					 PRIMARY KEY (id)				
					)";
					mysqli_query($cnx3,$bot_whats_menu);	
			
					$bot_whats_menu_resposta = "CREATE TABLE `bot_whats_menu_resposta` (			
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`instancia` int(11) NOT NULL,
					`id_menu` int(11) NOT NULL,		
					`conteudo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
					`arquivo` varchar(200) NOT NULL, 
					`ext_arquivo` varchar(6) NOT NULL, 
					 PRIMARY KEY (id)					
					)";
					mysqli_query($cnx3,$bot_whats_menu_resposta);	
			
				
			
			
		}
		//FIM CRIA BASE DE DADOS__________________________________________	
		
		    $_SESSION[id_usuario] =  $codigo;
		    $_SESSION[id_user] =  $CodUser;
		    $_SESSION[id_status] =  $status;
			GerarToken($conexao,$codigo);	
			//echo "<script>AcessarSistema('S');</script>";	
		
			//Dados para asaas
			list($id_asaas,$cidadeDb,$estadoDb,$celularDb,$cpf_cnpjDb,$cepDb,$emailDb,$nomeDb,$emailsDb,$numeroDb,$enderecoDb,$bairroDb,$complementoDb) = mysqli_fetch_row(mysqli_query($conexao, "select id_asaas, cidade, estado, celular, cpf_cnpj, cep, email, nome, emails, numero, endereco, bairro, complemento from usuarios where id= '".$codigo."'  "));

		
			list($cidade) = mysqli_fetch_row(mysqli_query($conexao, "select nome from cidades where cod_cidades= '".$cidadeDb."'  "));
			list($estado) = mysqli_fetch_row(mysqli_query($conexao, "select nome from estados where cod_estados= '".$estadoDb."'  "));

			$fone = $celularDb;
			$fone0 = explode(" ",$fone);

			$celular = $fone0[1]."".$fone0[2]."".$fone0[3];
			$celular0 = str_replace('-','',$celular);

			$cpfcnpj0 = $cpf_cnpjDb;
			$cpfcnpj1 = str_replace('.','',$cpfcnpj0);
			$cpfcnpj2 = str_replace('-','',$cpfcnpj1);
			$cpfcnpj3 = str_replace('/','',$cpfcnpj2);

			$cep0 = $cepDb;
			$cep1 = str_replace('-','',$cep0);

			$email = $emailDb;
			$name = ($nomeDb);
			$notes = "Business Corporativo";
			$phone = $celular0;
			$phone_prefix = $fone0[1];
			$cpf_cnpj = $cpfcnpj3;
			$cc_emails = $emailsDb;
			$zip_code = $cep1;
			$number = $numeroDb;
			$street = ($enderecoDb);
			$city = ($cidade);
			$state = ($estado);
			$district = ($bairroDb);
			$complement = ($complementoDb);
		
		
			if(!$id_asaas){
				sendCadastroAsaas($conexao,$api_token ,$email,$name,$notes,$phone,$phone_prefix,$cpf_cnpj,$cc_emails,$zip_code,$number,$street,$city,$state,$district,$complement,$codigo,'Logar');				
			}
		
		    //----------------------------------------------------------

		}

	else{
		echo "<script>AcessarSistema('N','');</script>";
	}		

		
}	
	

}
	
	
	
/*Inserir dados API whatsapp*/
function GerarToken($conexao,$id_usuario){
	
	list($ip,$porta) = mysqli_fetch_row(mysqli_query($conexao, "select ip, porta from server  "));

	$pergunta = "select id, nome, senha_ver, token, status_whats_desc from usuarios where id = '".$id_usuario."' ";
	$resultado = mysqli_query($conexao,$pergunta);
	$d = mysqli_fetch_object($resultado);

	$nome0 = explode(" ",$d->nome);	
	$nome = ($nome0[0]);
	
	$fields = array
	(
		'username' => $nome,
		'password' => $d->senha_ver,
		'instance' => $d->id,
	);

	$headers = array
	(
	'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/getToken' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$resultApi = curl_exec($ch );
	curl_close( $ch );	

	$jsonObjlista = json_decode($resultApi);
	$token = $jsonObjlista->accessToken;	

	
	if($d->token==""){
		mysqli_query($conexao," update usuarios set token = '".$token."' where id = '".$d->id."' ");
		
		$tokenChek = $token;
	}else{
		$tokenChek = $d->token;
	}
	

	if($st=='Novo'){
		
		echo "<script>AcessarSistema('S','$id_usuario');</script>";
		
		/*Para salda��o*/
		list($tokenAdm,$nomeAdm,$celularAdm) = mysqli_fetch_row(mysqli_query($conexao, "select token, nome, celular from usuarios where id = '1' "));	
		Saldacao($ip,$porta,$tokenAdm,$nomeAdm,$celularAdm);
		/*------------------------------*/					
		
		
	}else{
		
		echo "<script>AcessarSistema('S','$id_usuario');</script>";
	
	}

	
	//onzap($ip,$porta,$token);
}
	
	
function onzap($ip,$porta,$token){
	
	$authorization = "Bearer $token";

	$headers = array
	(
	'Content-Type: application/json',
	'Authorization: ' . $authorization	
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/onzap');
	curl_setopt( $ch,CURLOPT_GET, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	$result = curl_exec($ch );
	curl_close( $ch );	
	
	
}
	
	
	

/*----------------------------------------*/	
	
function Saldacao($ip,$porta,$token,$nome,$celular){
	
	$celularDb = explode(" ",$celular);
	
	if($celularDb[1]=='92' or $celularDb[1]=='85' or $celularDb[1]=='82'){
		$tira = str_replace("-","",$celularDb[3]); ;
		$cells = $celularDb[0].''.$celularDb[1].''.$tira;
	}else{
		$cells = $celularDb[0].''.$celularDb[1].''.$tira;
		//$cells = $celularDb[0].''.$celularDb[1].''.$celularDb[2].''.$tira; //caso acrecente o 9
	}	
	
	$whatsapp = $cells  ;

	$mensagemEnviar = ("Ol� $nome tudo bem?\nObrigado por fazer parte da nossa rede!\nSeja bem vindo e aproveite as nossas aplica��es.");
	
	$numeros[] = $whatsapp;	

	$authorization = "Bearer $token";

	$fields = array
	(
		'mensagem' => $mensagemEnviar ,
		'numbers' => $numeros
	);

	$headers = array
	(
	'Content-Type: application/json',	
	'Authorization: ' . $authorization

	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $ip.':'.$porta.'/send/text' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );
	
	
	
	echo "<script>AcessarSistema('S','');</script>";
}	
	

	
	
/*Inicio asaas*/
function sendCadastroAsaas($conexao,$api_token ,$email,$name,$notes,$phone,$phone_prefix,$cpf_cnpj,$cc_emails,$zip_code,$number,$street,$city,$state,$district,$complement,$id,$st){
	
	session_start();
	$_SESSION[id_usuario] = $id;
	$_SESSION[id_status] = "ADM";
	GerarToken($conexao,$id,$st);
	//echo "<script>AcessarSistema('S');</script>";
	
	
	
	
	$fields = array
	(
		'name' => ($name),
		'email' => $email,
		'phone' => $phone,
		'mobilePhone' => '',
		'cpfCnpj' => $cpf_cnpj,
		'postalCode' => $zip_code,
		'address' => ($street),
		'addressNumber' => $number,
		'complement' => ($complement),
		'province' => ($district),
		'externalReference' => '',
		'notificationDisabled' => false,
		'additionalEmails' => $cc_emails,
		'municipalInscription' => '',
		'stateInscription' => '',
		'observations' => $notes		
	);
	$headers = array
	(
	'Content-Type: application/json',
	'access_token: '.$api_token,
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.asaas.com/v3/customers");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
	$response = curl_exec($ch);
	curl_close($ch);

//var_dump($response);	
	
	//echo "<script>alert('$response');</script>";
	
}
/*Fim asaas*/	
	
?>
	
<style>
	body{
		background: #f2edf3;
	}

	.CriarConta{
		cursor:pointer;
	}
	
	.Acessar{
		cursor:pointer;
	}

	.InfoDesc{
		font-size:12px;
	}
	
	.ChaveAcesso{
		font-size:16px;
		cursor:pointer;
	}
</style>


<div id="TelaLogin" class="w3-animate-top">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
				  
				<form class="pt-3" id="formulario_login" >  
                <div class="brand-logo">
                  <img src="./assets/images/logo.png"> &nbsp;&nbsp; <i class="fas fa-key ChaveAcesso"></i> <input class="form-control" name="ChaveInt" id="ChaveInt" style="display:none" placeholder="Codigo recebido pelo ADM" >
                </div>
                <h4>Olá! vamos começar</h4>
                <h6 class="font-weight-light">Faça login para continuar.</h6>
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" name="email" placeholder="E-mail valido" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Sua senha" autocomplete="off">
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-gradient-success btn-lg font-weight-medium auth-form-btn " >ACESSAR</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check form-check-success">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Mantenha-me conectado </label>
                    </div>
                    <a href="#" class="auth-link text-black" id="ReculperarSenha">Esqueceu a senha?</a>
                  </div>
                  <!--<div class="mb-2">
                    <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="mdi mdi-facebook mr-2"></i>Conecte-se usando o facebook </button>
                  </div>-->
                  <div class="text-center mt-4 font-weight-light"> Não possui uma conta? <a  class="text-info CriarConta" >Criar conta</a>
                  </div>
                </form>
              </div>
				
            </div>
          </div>
        </div>
</div>



<div id="TelaCadastrat" class="w3-animate-top" style="display:none" >

        <div class="content-wrapper d-flex align-items-center auth" >
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="./assets/images/logo.png">
                </div>
                <h4>Novo aqui?</h4>
                <h6 class="font-weight-light">A inscrição é fácil. Leva apenas alguns passos</h6>
                <form class="pt-3" id="FormCadastrar">
                  <div class="form-group">
					<span class="InfoDesc">Nome completo:</span>  
                    <input type="text" class="form-control form-control-lg" placeholder="Nome completo" name="nome">
                  </div>
				  <div class="form-group">
					<span class="InfoDesc">Seu celular:</span>   
                    <input class="form-control form-control-lg" tel type="tel" placeholder="Celular" name="celular" id="celular">
                  </div>	
					
                  <div class="form-group">
					<span class="InfoDesc">Seu e-mail:</span>   
                    <input type="email" class="form-control form-control-lg" placeholder="Seu E-mail" name="email">
                  </div>
					
					<div class="form-group SelectBarra">
						<span class="InfoDesc">Tipo pessoa:</span> 
						<select InputForm class="form-control input-text-select" id="tipo_pessoa" name="tipo_pessoa">
						<option InputForm value="">Tipo pessoa</option>
						<option InputForm value="F">F&iacute;sica</option>
						<option InputForm value="J">Jur&iacute;dica</option>	
						</select> 
					</div> 
					
					<div class="form-group SelectBarra">
						<span class="InfoDesc">Sexo:</span> 
						<select InputForm class="form-control input-text-select" id="sexo" name="sexo">
						<option InputForm value="">::Selecione::</option>
						<option InputForm value="M">Masculino</option>
						<option InputForm value="F">Feminino</option>	
						</select> 
					</div> 
					
					
					
					
						
					<span id="MostraCpfCnpj" style="display:none" >
					<div class="form-group">
						<span class="InfoDesc">CPF/CNPJ:</span> 
						<input type="text" class="form-control input-text" id="cpf_cnpj" name="cpf_cnpj" placeholder="CPF/CNPJ">
					</div>	
					</span>						
					
					
				  <div class="form-group">
					<span class="InfoDesc">Data de aniversario:</span>   
                    <input type="date" class="form-control form-control-lg" placeholder="Data de aniversario" name="data_nascimento">
                  </div>	
					
					
					<div class="form-group">
						<span class="InfoDesc">Estado:</span>
						<select InputForm class="form-control input-text-select" id="estado" name="estado">
						<option InputForm value="">Procurar por estado</option>	
						<?php
						$query = "select * from estados order by nome";
						$result = mysqli_query($conexao, $query);
						while($dc = mysqli_fetch_object($result)){
						?>
						<option InputForm value="<?=$dc->cod_estados?>" ><?=($dc->nome)?></option>
						<?php
						}
						?>
						</select> 
					</div>      
       
       
					<div class="form-group">
						<span class="InfoDesc">Cidade:</span> 
						<select class="form-control input-text-select" id="cidade" name="cidade">
						<option value="">Cidades</option>	
						</select> 
					</div> 					
					
					
                  <div class="form-group">
					<span class="InfoDesc">Seu CEP:</span>   
                    <input type="text" class="form-control form-control-lg" placeholder="Seu CEP" name="cep" id="cep">
                  </div>
					
                  <div class="form-group">
					<span class="InfoDesc">Endere&ccedil;o:</span>   
                    <input type="text" class="form-control form-control-lg" placeholder="Endere&ccedil;o" name="endereco" id="endereco">
                  </div>
					
                  <div class="form-group">
					<span class="InfoDesc">N&uacute;mero:</span>   
                    <input type="text" class="form-control form-control-lg" placeholder="N&uacute;mero" name="numero">
                  </div>
					
                  <div class="form-group">
					<span class="InfoDesc">Bairro:</span>   
                    <input type="text" class="form-control form-control-lg" placeholder="Bairro" name="bairro" id="bairro">
                  </div>
					
                  <div class="form-group">
					<span class="InfoDesc">Complemento:</span>   
                    <input type="text" class="form-control form-control-lg" placeholder="Complemento" name="complemento">
                  </div>					
					
					
					
					
                  <div class="form-group">
					<span class="InfoDesc">Crei uma senha:</span>   
                    <input type="password" class="form-control form-control-lg" placeholder="Senha" name="senha" id="senha">
                  </div>
                  <div class="form-group">
					<span class="InfoDesc">Repetir a senha:</span>   
                    <input type="password" class="form-control form-control-lg" placeholder="Repetir a senha" name="repetir_senha">
                  </div>
					
					
                  <div class="mb-4">
					  
                    <div class="form-check form-check-success">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" id="Termo" class="form-check-input" name="termo"> 
						  Eu concordo com todos os Termos & Condições
					  </label>
                    </div>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-gradient-success btn-lg font-weight-medium auth-form-btn" >ACESSAR</button>
                  </div>
                  <div class="text-center mt-4 font-weight-light"> já tem uma conta? <a  class="text-primary Acessar">Login</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
</div>

	
	
	
<script>

id_user = localStorage.getItem('id_user');	
$("#ChaveInt").val(id_user);//Resgata ID usuario vinculado
	
$(".ChaveAcesso").click(function(){
	$("#ChaveInt").css("display","block");
	$("#ChaveInt").focus();
});
$("#ChaveInt").blur(function(){
	$("#ChaveInt").css("display","none");
	
	let us = $("#ChaveInt").val();
	window.localStorage.setItem('id_user', us);//Salva ID usuario vinculado
});	
	
	
$("#estado").change(function(){
	estado = $(this).val();
	//alert(estado);
	$("#cidade").html('<option>Carregando cidades...</option>');
	$.ajax({
	url:"./acoes/select_cidades.php?estado="+estado,
	success:function(dados){
	$("#cidade").html(dados);
	}
	});
});		
	
	
$("#ReculperarSenha").click(function(){

	Rsenha = $.confirm({
		title: "",
		content: "url:./acoes/recuperar_senha.php",
		columnClass:"col-md-4 col-md-offset-4",
		theme: "light",
		buttons: {
			fechar: {
				btnClass: "btn-success",
				action: function(){
				}
			},
		}				
	  });
		
	
});	
	
	$("#Termo").click(function(){
		
		var ckbox = $('#Termo');
        if (ckbox.is(':checked')) {
			$.confirm({
				title: "",
				content: "url:./paginas/termo.php",
				columnClass:"col-md-6 col-md-offset-3",
				theme: "light",
				buttons: {
					Concordo: {
						btnClass: "btn-success",
						action: function(){
						}
					},
				}				
			  });
		}else{

		}		
		
	})
	    

	
	
$(".CriarConta").click(function(){
	
	$("#TelaCadastrat").css("display","block");
	$("#TelaLogin").css("display","none");
});
	
$(".Acessar").click(function(){
	
	$("#TelaCadastrat").css("display","none");
	$("#TelaLogin").css("display","block");
	
});

	
$('#FormCadastrar').validate({
	rules : {
		nome : {
			required : true
		},		
		celular : {
			required : true,
			remote: {
				url: "./acoes/checar_celular.php",
				type: "post"
			},
			minlength: 17
		},
		data_nascimento : {
			required : true
		},
		estado : {
			required : true
		},
		cidade : {
			required : true
		},
		email: {
			required: true,
			email: true,
			remote: {
				url: "./acoes/checar_email.php",
				type: "post"
			 }
		},
		tipo_pessoa : {
			required : true
		},
		sexo : {
			required : true
		},
		cpf_cnpj : {
			required : true,
			remote: {
				url: "./acoes/checar_cpf_cnpj.php",
				type: "post"
			 }
		},		
		
		cep : {
			required : true
		},
		endereco : {
			required : true
		},
		numero : {
			required : true
		},
		bairro : {
			required : true
		},		
		senha : {
			required : true
		},
		repetir_senha : {
			equalTo:"#senha"
		}

	},
	messages : {
		nome : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu nome.</div>'
		},
		celular : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu celular.</div>',
			remote: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Este celular já existe.</div>',
			minlength: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Esta faltando número.</div>',
		},
		data_nascimento : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a data de aniversario.</div>'
		},
		estado : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu estado.</div>'
		},
		cidade : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a sua cidade.</div>'
		},		
		email : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu e-mail.</div>',
			remote: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Este e-mail j� existe.</div>',
			email: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Digite um endere�o de e-mail valido.</div>',	
		},
		
		tipo_pessoa : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Pessoa juridica ou fisica?</div>'
		},
		sexo : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o sexo</div>'
		},
		cpf_cnpj : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o cpf/cnpj.</div>',
			remote: '<div class="AlertCampo"><i class="fa fa-info-circle"></i> CPF/CNPJ invalido.</div>'
		},		
		
		cep : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o CEP.</div>'
		},
		endereco : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o endere&ccedil;o.</div>'
		},
		numero : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o numero.</div>'
		},
		bairro : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o bairro.</div>'
		},
		senha : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a senha.</div>'
		},
		repetir_senha : {
			equalTo : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> A senha n�o confere.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

		var ckbox = $('#Termo');
        if (ckbox.is(':checked')) {
            
		$.ajax({
			type: "POST",
			url: "./navegacao/login.php",
			data: dados,
			success: function( data )
			{
				$("#AcessarSistema").html(data);

			}
		});		

        } else {
			 $.confirm({
				title: "<span style='color:red'>Aten&ccedil;&atilde;o!</span>",
				content: "<b>Favor concordar com os termos do sistema.</b>",
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
	


	return false;
}

});	
	

	
TipoPessoa = $("#tipo_pessoa").val();
if(TipoPessoa=="F"){
   $('#cpf_cnpj').mask('000.000.000-00');
}else if(TipoPessoa=="J"){
   $('#cpf_cnpj').mask('00.000.000/0000-00');	 
}
	
	
$("#tipo_pessoa").change(function(){
	
	var tp = $(this).val();
	
	if(tp=='F'){

		$('#cpf_cnpj').mask('000.000.000-00');
		$('#MostraCpfCnpj').css('display','block');
	}else if(tp=='J'){

		$('#cpf_cnpj').mask('00.000.000/0000-00');
		$('#MostraCpfCnpj').css('display','block');
	}else{
		$('#MostraCpfCnpj').css('display','none');
	}
	
});		
	
	
$("#cep").blur(function(){
	// Remove tudo o que n�o � n�mero para fazer a pesquisa
	var cep = this.value.replace(/[^0-9]/, "");

	// Valida��o do CEP; caso o CEP n�o possua 8 n�meros, ent�o cancela
	// a consulta
	if(cep.length != 8){
		return false;
	}

	// A url de pesquisa consiste no endere�o do webservice + o cep que
	// o usu�rio informou + o tipo de retorno desejado (entre "json",
	// "jsonp", "xml", "piped" ou "querty")
	var url = "https://viacep.com.br/ws/"+cep+"/json/";

	// Faz a pesquisa do CEP, tratando o retorno com try/catch para que
	// caso ocorra algum erro (o cep pode n�o existir, por exemplo) a
	// usabilidade n�o seja afetada, assim o usu�rio pode continuar//
	// preenchendo os campos normalmente
	$.getJSON(url, function(dadosRetorno){
		try{
			// Preenche os campos de acordo com o retorno da pesquisa
			$("#endereco").val(dadosRetorno.logradouro);
			$("#bairro").val(dadosRetorno.bairro);
			//$("#cidade").val(dadosRetorno.localidade);
			//$("#uf").val(dadosRetorno.uf);
		}catch(ex){}
	});
});		
	
	
	
$('#formulario_login').validate({
	rules : {
		email : {
			required : true
		},
		password : {
			required : true
		}

	},
	messages : {
		email : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe o seu e-mail.</div>'
		},
		password : {
			required : '<div class="AlertCampo"><i class="fa fa-info-circle"></i> Informe a senha.</div>'
		}
	},
submitHandler: function( form ){
	var dados = $( form ).serialize();

	$.ajax({
		type: "POST",
		url: "./navegacao/login.php",
		data: dados,
		success: function( data )
		{
			$("#AcessarSistema").html(data);

		}
	});

	return false;
}

});	

	
function AcessarSistema(st,us){

		
	if(st=='S'){
		
	   //window.localStorage.setItem('id_user', us);//Salva ID usuario vinculado
	   window.location.href = "?u=sistema";
		
		
	}else{ 
	

	 $.confirm({
		title: "<span style='color:red'>Aten&ccedil;&atilde;o!<span>",
		content: "<b>Informa&ccedil;&otilde;es de acesso incorretas</b>",
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
	
$("#celular").mask("55 99 9 9999-9999");	
$('#cep').mask('00000-000');	
	
</script>
</div>