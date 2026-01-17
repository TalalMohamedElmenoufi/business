<?php
$cpfCnpj = strlen($_POST[cpf_cnpj]);
if($cpfCnpj == 14 ){
	
	if( validaCPF($_POST[cpf_cnpj]) == 'true' ){
		echo 'true';
	}else{
		echo 'false';
	}
	
}

if($cpfCnpj > 16 ){
	
	if( validar_cnpj($_POST[cpf_cnpj]) == 'true' ){
		echo 'true';
	}else{
		echo 'false';
	}
	
}

function validaCPF($cpf) {
 
    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return 'false';
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return 'false';
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return 'false';
        }
    }
    return 'true';

}


// Validar numero de CNPJ
function validar_cnpj($cnpj) {

    // Verificar se foi informado
	if(empty($cnpj))
		return 'false';

	// Remover caracteres especias
	$cnpj = preg_replace('/[^0-9]/', '', $cnpj);

	// Verifica se o numero de digitos informados
	if (strlen($cnpj) != 14)
		return 'false';

  	// Verifica se todos os digitos são iguais
	if (preg_match('/(\d)\1{13}/', $cnpj))
		return 'false';

	$b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    for ($i = 0, $n = 0; $i < 12; $n += $cnpj[$i] * $b[++$i]);

    if ($cnpj[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
        return 'false';
    }

    for ($i = 0, $n = 0; $i <= 12; $n += $cnpj[$i] * $b[$i++]);

    if ($cnpj[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
        return 'false';
    }

	return 'true';
}
?>