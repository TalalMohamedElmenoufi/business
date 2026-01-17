<?php
include('../../includes/connect.php');

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select credito_sms from usuarios where codigo = '".$_SESSION[codigo_usuario]."' "));

list($smsEnvio) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria in (".implode(',',$_POST[grupos]).")  "));

$smsSaldo = $creditoSms - $smsEnvio;


?>
<script>parent.VoltarSmsDisponivel('<?=$smsEnvio?>','<?=$smsSaldo?>');</script>