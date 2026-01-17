<?php
include("../../includes/connect.php");	
include("../../includes/funcoes.php");	

list($NomeCategoria) = mysqli_fetch_row(mysqli_query($conexao2, "select descricao from categoria_cadastro where id = '".$_GET[Cat]."' "));	

	$pergnta = "select a.* from cadastro a 
				where a.categoria = '".$_GET[Cat]."'
				";
	$resultado = mysqli_query($conexao2, $pergnta);


    //declaramos uma variavel para monstarmos a tabela
    $dadosXls  = "";
    $dadosXls .= "  <table border='1' >";
    $dadosXls .= "          <tr>";
    $dadosXls .= "          <th>Nome</th>";
    $dadosXls .= "          <th>Celular</th>";
    $dadosXls .= "          <th>E-mail</th>";
	$dadosXls .= "          <th>Data nascimento</th>";
    $dadosXls .= "      </tr>";



    while($res = mysqli_fetch_assoc($resultado)){
		
		$celular = $res['cod_estado'].''.$res['telefone'] ;
		
		$DN = explode('-',$res['data_nascimento']);
		$data_nascimento = $DN[2].'/'.$DN[1].'/'.$DN[0];

        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['nome']."</td>";
        $dadosXls .= "          <td>".$celular."</td>";
        $dadosXls .= "          <td>".$res['email']."</td>";
		$dadosXls .= "          <td>".$data_nascimento."</td>";
		
        $dadosXls .= "      </tr>";
    }
    $dadosXls .= "  </table>";

 
    // Definimos o nome do arquivo que será exportado  
    $arquivo = "Relatorio_".$NomeCategoria.".xls";
    // Configurações header para forçar o download  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    header('Cache-Control: max-age=0');
    // Se for o IE9, isso talvez seja necessário
    header('Cache-Control: max-age=1');
       
    // Envia o conteúdo do arquivo  
    echo $dadosXls;

    //exit;
?>
