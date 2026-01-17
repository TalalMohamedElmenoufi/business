<?php
	
	//Must be same with server
	$host = "161.97.75.98";
	$port = 1978;

	// No Timeout 
	set_time_limit(0);

	//Create Socket
	$sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("Não foi possível criar socket\n");

	//Connect to the server
	$result = socket_connect($sock, $host, $port) or die("Não pode conectar ao servidor\n");

	$message = 'O que devo fazer?';
	//Write to server socket
	socket_write($sock, $message, strlen($message)) or die("Não foi possível enviar dados para o servidor\n");

	//Read server respond message
	$result = socket_read($sock, 1024) or die("Não foi possível ler a resposta do servidor\n");
	echo "Resposta do servidor  :".$result;

	//Close the socket
	socket_close($sock);

?>