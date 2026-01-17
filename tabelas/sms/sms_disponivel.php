<?php
include('../../includes/connect.php');

list($creditoSms) = mysqli_fetch_row(mysqli_query($conexao, "select creditos_sms from usuarios where id = '".$_SESSION[id_usuario]."' "));

list($smsEnvio) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from cadastro where categoria in (".implode(',',$_POST[grupos]).") and situacao = '0' and tel_tipo = 'Invalido' "));

$smsSaldo = $creditoSms - $smsEnvio;


?>
<script>parent.VoltarSmsDisponivel2('<?=$smsEnvio?>','<?=$smsSaldo?>');</script>