<?php
// CONFIGURAÇÕES INICIAIS
$db_host = "localhost";
$db_user = "root";
$db_pass = "Pr0v!s@2024S!st3m@";
$db_name = "elmenoufi_bot";
$ip = "http://84.247.173.106";
$porta = "1979";
$token_ia = "gsk_...";

// Conexão e charset UTF-8
$conexao = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conexao) die("Erro na conexão com o banco de dados.\n");
mysqli_set_charset($conexao, 'utf8mb4');

// Função de log
function log_info($msg) {
    echo "[" . date("Y-m-d H:i:s") . "] $msg\n";
}

// Limpa texto com problemas de codificação
function clean_utf8($texto) {
    $texto = iconv('UTF-8', 'UTF-8//IGNORE', $texto);
    return preg_replace('/[\x00-\x1F\x7F]/u', '', $texto);
}

// Envia WhatsApp
function EnviarWhatsapp($token, $whatsapp, $mensagem, $ip, $porta) {
    $authorization = "Bearer $token";
    $fields = ['mensagem' => $mensagem, 'numbers' => $whatsapp];
    $headers = ['Content-Type: application/json', "Authorization: $authorization"];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$ip:$porta/send/text");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_exec($ch);
    curl_close($ch);
}

// Quebra texto longo
function split_utf8($text, $maxLength) {
    $result = [];
    while (mb_strlen($text, 'UTF-8') > 0) {
        $chunk = mb_substr($text, 0, $maxLength, 'UTF-8');
        $result[] = $chunk;
        $text = mb_substr($text, $maxLength, null, 'UTF-8');
    }
    return $result;
}

// Envia à IA com contexto e validação
function sendIa($mensagem, $token_ia, $instancia, $conexao, $id_movimentacao = null) {
    $instancia_safe = mysqli_real_escape_string($conexao, $instancia);
    $sql = "SELECT system1, contexto_propostas, contexto_integrantes, contexto_extra FROM treinamento WHERE instancia = '$instancia_safe' AND status = 1 LIMIT 1";
    $queryTreino = mysqli_query($conexao, $sql);
    if (!$queryTreino) return null;

    $treino = mysqli_fetch_assoc($queryTreino);
    if (!$treino) return null;

    $system1 = "IMPORTANTE: Responda apenas com base no conteúdo abaixo. Caso a resposta não esteja contida nos dados, diga que não há informação suficiente.\n\n" . clean_utf8($treino['system1'] ?? '');
    $propostas   = clean_utf8($treino['contexto_propostas'] ?? '');
    $integrantes = clean_utf8($treino['contexto_integrantes'] ?? '');
    $extra       = clean_utf8($treino['contexto_extra'] ?? '');
    $msg         = clean_utf8($mensagem);

    // Redução de caracteres, se necessário
    $total_chars = mb_strlen($system1, '8bit') + mb_strlen($propostas, '8bit') + mb_strlen($integrantes, '8bit') + mb_strlen($extra, '8bit') + mb_strlen($msg, '8bit');
    $limite_chars = 12000;
    if ($total_chars > $limite_chars) {
        $excedente = $total_chars - $limite_chars;
        foreach (['extra', 'integrantes', 'propostas', 'system1'] as $campo) {
            if ($excedente <= 0) break;
            $len = mb_strlen($$campo, 'UTF-8');
            $reduzir = min($excedente, max($len - 1000, 0));
            $$campo = mb_substr($$campo, 0, $len - $reduzir, 'UTF-8');
            $excedente -= $reduzir;
        }
        if ($excedente > 0) {
            $len = mb_strlen($msg, 'UTF-8');
            $reduzir = min($excedente, max($len - 1000, 0));
            $msg = mb_substr($msg, 0, $len - $reduzir, 'UTF-8');
        }
    }

    // Histórico anterior
    $historico = [];
    $sqlHist = "SELECT mensagem, retorno_log FROM movimentacao WHERE instancia = '$instancia_safe' AND mensagem != '" . mysqli_real_escape_string($conexao, $mensagem) . "' AND retorno_log IS NOT NULL ORDER BY id DESC LIMIT 10";
    $resHist = mysqli_query($conexao, $sqlHist);
    if ($resHist) {
        while ($row = mysqli_fetch_assoc($resHist)) {
            $historico[] = ['role' => 'user', 'content' => clean_utf8($row['mensagem'])];
            $historico[] = ['role' => 'assistant', 'content' => clean_utf8($row['retorno_log'])];
        }
    }

    $messages = [['role' => 'system', 'content' => $system1]];
    $messages = array_merge($messages, $historico);

    foreach (split_utf8($propostas, 2500) as $i => $chunk)
        $messages[] = ['role' => 'system', 'content' => "(parte " . ($i + 1) . "):\n" . $chunk];
    foreach (split_utf8($integrantes, 2500) as $i => $chunk)
        $messages[] = ['role' => 'system', 'content' => "(parte " . ($i + 1) . "):\n" . $chunk];
    foreach (split_utf8($extra, 2500) as $i => $chunk)
        $messages[] = ['role' => 'system', 'content' => "(parte " . ($i + 1) . "):\n" . $chunk];

    $messages[] = ['role' => 'system', 'content' => 'Atenção: responda exclusivamente com base nos dados acima. Se não houver dados suficientes, informe isso claramente.'];
    $messages[] = ['role' => 'user', 'content' => $msg];

    $data = ['model' => 'llama3-8b-8192', 'messages' => $messages, 'temperature' => 0.0];
    $jsonPayload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | (defined('JSON_INVALID_UTF8_IGNORE') ? JSON_INVALID_UTF8_IGNORE : 0));
    $headers = ['Content-Type: application/json', "Authorization: Bearer $token_ia"];

    $ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $jsonPayload,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response, true);
    if (!isset($json['choices'][0]['message']['content'])) return null;

    return $json['choices'][0]['message']['content'];
}

// LOOP PRINCIPAL
while (true) {
    if (!mysqli_ping($conexao)) {
        $conexao = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        mysqli_set_charset($conexao, 'utf8mb4');
    }

    $res = mysqli_query($conexao, "SELECT * FROM movimentacao WHERE lido = 0 ORDER BY id DESC LIMIT 1");
    if ($row = mysqli_fetch_assoc($res)) {
        $id        = $row['id'];
        $mensagem  = $row['mensagem'];
        $para      = preg_replace('/@.*$/', '', $row['de_quem']);
        $instancia = $row['instancia'];

        $resposta = sendIa($mensagem, $token_ia, $instancia, $conexao, $id);

        // FILTRO RIGOROSO
        $frases_proibidas = [
            'não tenho certeza', 'como um modelo de linguagem',
            'segundo minha base de conhecimento', 'posso te ajudar com isso',
            'a IA pode', 'não fui treinado com'
        ];
        foreach ($frases_proibidas as $frase) {
            if (stripos($resposta, $frase) !== false) {
                $resposta = "Desculpe, não há informação suficiente nos dados disponíveis para responder a essa pergunta.";
                break;
            }
        }

        if ($resposta) {
            $respDB = mysqli_real_escape_string($conexao, $resposta);
            mysqli_query($conexao, "UPDATE movimentacao SET retorno_log = '$respDB' WHERE id = $id");

            $u = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT token FROM usuarios WHERE status_whats_desc = 'CONNECTED' AND id = '$instancia' LIMIT 1"));
            if (!empty($u['token'])) {
                EnviarWhatsapp($u['token'], $para, $resposta, $ip, $porta);
                mysqli_query($conexao, "UPDATE movimentacao SET lido = 1 WHERE id = $id");
                log_info("Mensagem enviada (ID $id) para $para");
            }
        }
    }
    sleep(2);
}
