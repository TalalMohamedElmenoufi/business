<?php
	
	$host = "161.97.75.98";
	$port = 1978;
	
	// No Timeout 
	set_time_limit(0);

	//Create Socket
	$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Não foi possível criar socket\n");

	//Ligue o soquete à porta e ao host
	$result = socket_bind($sock, $host, $port) or die("Não foi possível ligar a socket\n");

	while(true) {
		//Comece a ouvir a porta
		$result = socket_listen($sock, 5) or die("Não foi possível configurar o ouvinte de socket\n");

		if(($spawn = socket_accept($socket)) !== false){

		echo "Client $spawn has connected\n";

		//Faça-o para aceitar a conexão de entrada
		$spawn = socket_accept($sock) or die("Não foi possível aceitar conexão de entrada\n");

				//Leia a mensagem do soquete do cliente
				$input = socket_read($spawn, 2048) or die("Não foi possível ler a entrada\n");

				$output = 'Eu recebi sua mensagem. \nAgora faça o seu trabalho e \ninscreva-se no canal do YouTube do Mossymoo! ';

				//Enviar mensagem de volta ao soquete do cliente
				socket_write($spawn, $output, strlen ($output)) or die("Não foi possível gravar a saída\n");			

				$retorno = socket_read($sock, 1024) or die("Não foi possível ler a resposta do cliente\n");
				echo "Resposta do cliente  :".$retorno;
		 
			
		}
		

	}

	socket_close($spawn);
	socket_close($socket);

?>