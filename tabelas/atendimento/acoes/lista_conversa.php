<?php
include("../../../includes/connect.php");

list($totalRegs) = mysqli_fetch_row(mysqli_query($conexao2, "select count(id) from mov_bot_".date('mY')." where de_quem like '%".$_GET[celular]."%' and ackRes = '2' or para_quem like '%".$_GET[celular]."%' and ackRes = '2' "));

$total = $totalRegs - 50;

$sezero = (($totalRegs < 50 )?'0':$total);

$clientes = " select * from mov_bot_".date('mY')." where de_quem like '%".$_GET[celular]."%' and ackRes = '2' or para_quem like '%".$_GET[celular]."%' and ackRes = '2' order by id asc limit $sezero,50";
$resultadoClient = mysqli_query($conexao2,$clientes); 

//echo $clientes;

		$montar .= '<div class="mx-auto my-2 bg-primary text-white small py-1 px-2 rounded">'.date('d/m/Y').'</div>';

while( $c = mysqli_fetch_object($resultadoClient) ){
$de_quem = explode("@",$c->de_quem);
$para_quem = explode("@",$c->para_quem);	

mysqli_query($conexao2, "update mov_bot_".date('mY')." set status = '1' where de_quem like '%".$_GET[celular]."%'  or para_quem like '%".$_GET[celular]."%' ");	
		
	    if($_GET[celular]==$de_quem[0]){
			$montar .= '<div class="align-self-end self p-1 my-1 mx-3 rounded bg-white shadow-sm message-item">';
				$montar .= '<div class="options"><a><i class="fas fa-angle-down text-muted px-2"></i></a></div>';
				$montar .= '<div class="d-flex flex-row">';
					$montar .= '<div class="body m-1 mr-2">'.($c->mensagem).'<br>'; 
					$montar .= '</div>';
				$montar .= '<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted" style="width:auto;font-size:10px;">'.$c->data_registro.' <i class="fas fa-check-circle"></i>';
				$montar .= '</div>';
				$montar .= '</div>';
			$montar .= '</div>';			
		}else{
			
		  $montar .= '<div class="align-self-start p-1 my-1 mx-3 rounded bg-white shadow-sm message-item">';
			$montar .= '<div class="options">';
			$montar .= '<a><i class="fas fa-angle-down text-muted px-2"></i></a>';
			$montar .= '</div>';
			$montar .= '<div class="d-flex flex-row">';
			$montar .= '<div class="body m-1 mr-2">'.($c->mensagem).'<br>';
				$montar .= '</div>';
			$montar .= '<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted" style="width:auto;font-size:10px;">'.$c->data_registro.'</div>';
			$montar .= '</div>';
		  $montar .= '</div>';
			
		}

	
}
echo $montar;
?>