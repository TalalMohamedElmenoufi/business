
<form action="sendArg.php" method="post" name="enviar" enctype="multipart/form-data">

<input type="file" name="arquivo">

<button type="submit">ENVIAR</button>

</form>

<?php

if(isset($_FILES['arquivo'])){

$instancia = 1;
$email = 'tamer@mohatron.com.br'; 
$senha = 'mf6t1y76'; 
$numeros = '559291725319,559284194660'; 
$mensagem = 'imagem teste data '.date('d/m/Y H:i:s'); 
$arquivo = $_FILES['arquivo']['name'];
$tipo = 'img'; 

$diretorio_temporario = 'diretorio_temporario/';
$caminho_temporario = $diretorio_temporario . $_FILES['arquivo']['name'];

if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_temporario)) {

$arquivo_curl = new CURLFile($caminho_temporario, $_FILES['arquivo']['type'], $_FILES['arquivo']['name']);

$fields = array
(
    'instancia' => $instancia,
    'email' => $email,
    'senha' => $senha,
    'numeros' => $numeros,
    'mensagem' => $mensagem,
    'tipo' => $tipo
);

$headers = array
(
'Content-Type: multipart/form-data'
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'http://207.180.219.129/integracao_mohatron/update/' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields + array('arquivo' => $arquivo_curl)) ;
curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, true);	
$result = curl_exec($ch );
curl_close( $ch );

echo 'result: '.$result;

unlink($caminho_temporario);

}else{
    echo 'Erro ao salvar o arquivo temporariamente!';
}

}





?>