<?php
include("../includes/connect.php");
?>
  <option InputForm value="">::Selecione a Cidade::</option>
  <?php
    $query = "select * from cidades where estados_cod_estados = '".$_GET[estado]."' order by nome";
    $result = mysqli_query($conexao,$query);
    while($s = mysqli_fetch_object($result)){
  ?>
    <option InputForm value="<?=$s->cod_cidades?>"><?=($s->nome)?></option>
  <?php
  }
  ?>  	