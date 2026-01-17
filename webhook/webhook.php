<?php
$conexao = mysqli_connect("localhost", "root", "Pr0v!s@2024S!st3m@", "elmenoufi_bot") or die("<center>Autenticação principal invalida</center>");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))

$retorno = file_get_contents("php://input");
$data = json_decode($retorno, true);
 

$base = $data['dados']['base'] ?? null;

$session = trim($data['dados']['session']) ?? null;

$statusCell = $data['dados']['statusCell'] ?? null;
$base64Qr = $data['dados']['base64Qr'] ?? null;
$attempts = $data['dados']['attempts'] ?? null;
$state = $data['dados']['state'] ?? null;
$id_whats = $data['dados']['id_whats'] ?? null;
$de_quem = $data['dados']['de_quem'] ?? null;
$para_quem = $data['dados']['para_quem'] ?? null;
$mensagem = $data['dados']['mensagem'] ?? null;
$retorno_log = $data['dados']['retorno_log'] ?? null;
$ackRes = $data['dados']['ackRes'] ?? null;

$log = array (
    "base" => $base,
    "session" => $session,
    "statusCell" => $statusCell,
    "base64Qr" => $base64Qr,
    "attempts" => $attempts,
    "state" => $state,
    "id_whats" => $id_whats,
    "de_quem" => $de_quem,
    "para_quem" => $para_quem,
    "mensagem" => $mensagem,
    "retorno_log" => $retorno_log,
    "ackRes" => $ackRes
);
$dadosLog = json_encode($log);

if($base == 'usuarios_qr'){
    mysqli_query($conexao, " UPDATE usuarios SET 
    status_whats_desc = '$statusCell', 
    qr_code = '$base64Qr',
    attempt = '$attempt'
    WHERE id = '$session' 
    ");
}

if($base == 'usuarios_st'){
    mysqli_query($conexao, " UPDATE usuarios SET 
    status_whats_desc = '$state'
    WHERE id = '$session' 
    ");
}


if($base == 'onMessage'){
    mysqli_query($conexao, " INSERT INTO movimentacao SET 
    instancia = '$session',
    id_whats = '$id_whats',
    de_quem = '$de_quem',
    para_quem = '$para_quem',
    mensagem = '$mensagem',
    retorno_log = '$retorno_log'
    ");
}


if($base == 'ack'){
    mysqli_query($conexao, " INSERT INTO movimentacao_ack SET 
    instancia = '$session',
    id_whats = '$id_whats',
    de_quem = '$de_quem',
    para_quem = '$para_quem',
    mensagem = '$mensagem',
    ackRes = '$ackRes',
    retorno_log = '$retorno_log'
    ");
}


//file_put_contents("logs/tme-webhook-" . date("YmdHis") . ".txt", $dadosLog );
 

?>