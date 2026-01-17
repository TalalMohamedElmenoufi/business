<?php
include('../../includes/connect.php');

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select credito_email from usuarios where id = '".$_SESSION[id_usuario]."' "));

list($smsEnvio) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria in (".implode(',',$_POST[grupos]).") and email != '' and situacao = '0'  "));

$smsSaldo = $creditoSms - $smsEnvio;


?>
<script>parent.VoltarSmsDisponivel('<?=$smsEnvio?>','<?=$smsSaldo?>');</script>