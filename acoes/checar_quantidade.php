<?php
include("../includes/connect.php");

list($smsUso) = mysqli_fetch_row(mysqli_query($conexao2, "select count(codigo) from t_".$_GET[MesAno]."_smsStatuses where mes_ano = '".$_GET[MesAno]."' "));

?>

<script>parent.VoltarChequeQ('<?=$smsUso?>','<?=$_GET[DiaMes1]?>');</script>