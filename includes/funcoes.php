<?php
function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}





function mysqli_field_type( $result , $field_offset ) {
    static $types;

    $type_id = mysqli_fetch_field_direct($result,$field_offset)->type;

    if (!isset($types))
    {
        $types = array();
        $constants = get_defined_constants(true);
        foreach ($constants['mysqli'] as $c => $n) if (preg_match('/^MYSQLI_TYPE_(.*)/', $c, $m)) $types[$n] = $m[1];
    }

    return array_key_exists($type_id, $types)? $types[$type_id] : NULL;
}



function Idade($datadonascimento){

    // Separa em dia, mês e ano
    list($dia, $mes, $ano) = explode('/', $datadonascimento);

    // Descobre que dia é hoje e retorna a unix timestamp
    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    // Descobre a unix timestamp da data de nascimento do fulano
    $diadonascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

    // Depois apenas fazemos o cálculo já citado :)
    $idade = floor((((($hoje - $diadonascimento) / 60) / 60) / 24) / 365.25);
	return $idade;
}



function formatar_data($d){
	$dia = substr($d, 0,2);
	$mes = substr($d,3,2);
	$ano = substr($d,6,4);
	$data = $ano."-".$mes."-".$dia;
	if(strpos($d, ":")){
		$hora = substr($d, -8);
		return ($data." ".$hora);
	};
	return $data;
}





function dataMysql($d){
	$l = explode(" ",$d);
	$dt = explode("/",$l[0]);
	if($dt[2] and $dt[1] and $dt[0]){
		return $dt[2]."-".$dt[1]."-".$dt[0].(($l[1]) ? " ".$l[1] : false);
	}else{
		return false;		
	}
}


function foto_novo_nome($foto){
	   $nome = explode(".",$foto);
	   $n_nome = md5($nome[0].date("ymdhms")).'.'.$nome[1];
	   return $n_nome;
}








function dataBr($d){

	$l = explode(" ",$d);

	$dt = explode("-",$l[0]);

	if($dt[2] and $dt[1] and $dt[0]){

		return $dt[2]."/".$dt[1]."/".$dt[0].(($l[1]) ? " ".$l[1] : false);

	}else{

		return false;

	}

}

	







function data_br($dt){

    if ($dt){

    $aux1 = explode(" ",$dt); 

	$aux = explode("-",$aux1[0]); 

    $data = $aux[2]."/".$aux[1]."/".$aux[0];

	if($data != '00/00/0000'){

	   return $data;

	}else{ return false; }

		        }

		else { return false;}

}





function data_br_completo($dt){

    if ($dt){

    $aux1 = explode(" ",$dt); 

	$aux = explode("-",$aux1[0]); 

    $data = $aux[2]."/".$aux[1]."/".$aux[0];

	if($data != '00/00/0000'){

	   return $data.' '.$aux1[1];

	}else{ return false; }

		        }

		else { return false;}

}





function acentos($palavra){

	$acentos = array(
		 'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C');
	$acao = strtr($palavra, $acentos);

	return $acao ;

}

function RetiraEspaco($espaco){

 $acao = str_replace(' ', '_', $espaco);

 return $acao ;

}


function soNumero($str) {
	return preg_replace("/[^0-9]/", "", $str); 
}


function CalcularMeses($data1,$data2) {
	$arr = explode('/',$data1);
	$arr2 = explode('/',$data2);
	$dia1 = $arr[0];
	$mes1 = $arr[1];
	$ano1 = $arr[2];
	$dia2 = $arr2[0];
	$mes2 = $arr2[1];
	$ano2 = $arr2[2];
	$a1 = ($ano2 - $ano1)*12;
	$m1 = ($mes2 - $mes1)+1;
	$m3 = ($m1 + $a1);
	return $m3 ;
}




function Emojis($retorno){
	
$tratPerg = explode(" ",$retorno) ;

	foreach ($tratPerg as $key => $value ) {  
		$tratPerg1 = explode("#",$value) ;
		
		$parte1 = substr($tratPerg1[1],0, 5);
		$parte2 = substr($tratPerg1[1], -5);

		$Emoji = " '\ ".$parte1." \'".$parte2   ;
		$Emoji = str_replace("'","",$Emoji);
		$Emoji = str_replace(" ","",$Emoji);
		
		$Emoji = json_decode('"'.$Emoji.'"');		
		
		$returnPerg[] = ($tratPerg1[0]) .''. (($tratPerg1[1])?$Emoji:'')   ;
	}
	$texto = implode(' ',$returnPerg) ;		

	echo $texto ;
	
}
 
?>
