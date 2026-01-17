<?php
include('../../../includes/connect.php');


list($status_whats_desc,$qr_code,$attempt) = mysqli_fetch_row(mysqli_query($conexao, "select status_whats_desc, qr_code, attempt from usuarios where id = '".$_SESSION[id_usuario]."' "));


echo $dados = $status_whats_desc.",".$qr_code.",".(($attempt)?$attempt:1);