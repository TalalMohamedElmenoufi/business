<?php
include("../../../includes/connect.php");	
include("../../../includes/funcoes.php");

$Conf[script] = 'tabelas/contatos_agenda/contatos_agenda';
$Script = md5($Conf[script]);


if (isset($_FILES['arquivo'])) { 
	
	// Extensões permitidas
    $extensoes = array(".xls", ".xlsx");
    
    // Caminho onde ficarão os arquivos
    $caminho = "uploads/";
    
    // Recuperando informações do arquivo
    $nome = $_FILES['arquivo']['name'];
    $temp = $_FILES['arquivo']['tmp_name'];
    
    // Verifica se a extensão é permitida
    if (!in_array(strtolower(strrchr($nome, ".")), $extensoes)) {
		echo "<script>alert('Load 0 !')</script>";
		$erro = true;
     }
     
     // Se não houver erro
     if (!isset($erro)) {
		 
     	// Gerando um nome aleatório para o arquivo
        $nomeAleatorio = md5(uniqid(time())) . strrchr($nome, ".");
        
        // Movendo arquivo para servidor
        if (!move_uploaded_file($temp, $caminho . $nomeAleatorio)){
			echo "<script>alert('ERRO ao mover o arquivo !' );</script>";
			echo "<script>parent.excel();</script>";
		}else{
			
			if(strtolower(strrchr($nome, ".")) == '.xls'){
				include("xls.php");
				//echo "<script>alert('xls !');</script>";
				echo "<script>parent.excel();</script>";
			}else if(strtolower(strrchr($nome, ".")) == '.xlsx'){
				include("xlsx.php");
				//echo "<script>alert('xlsx !');</script>";
				echo "<script>parent.excel();</script>";
			}
			unlink($caminho . $nomeAleatorio);
			
				//echo "<script>alert('unlink !');</script>";
			
		}
     }
	
}

?>