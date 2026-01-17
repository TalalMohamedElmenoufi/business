<?php
include("../../../includes/connect.php");

list($MaxId) = mysqli_fetch_row(mysqli_query($conexao2, "select MAX(id) from mov_bot_".date('mY')." where status = '1' "));

echo $MaxId;
?>