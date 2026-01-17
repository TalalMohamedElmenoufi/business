<?php
include("../includes/connect.php");

list($emailUso) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from t_".$_GET[MesAno]."_emailStatuses where date LIKE '%".$_GET[Mes_Ano]."%' "));


?>

<script>parent.VoltarChequeQ('<?=$emailUso?>','<?=$_GET[DiaMes1]?>');</script>