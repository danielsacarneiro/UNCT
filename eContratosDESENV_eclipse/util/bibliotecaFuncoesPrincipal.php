<?php

/*
 * implementa a busca insensitive de uma palavra em outra
 * diferente de strpos que eh case sensitive
 */
function getMultiPos($haystack, $needles, $sensitive=true, $offset=0){
	foreach($needles as $needle) {
		$result[$needle] = ($sensitive) ? strpos($haystack, $needle, $offset) : stripos($haystack, $needle, $offset);
	}
	return $result;
}
function isColecaoVazia($recordset) {
	return $recordset == null || $recordset == "";
}
function getColunaEmLinha($recordset, $nmColuna, $pSeparador) {
	$retorno = null;
	
	if (! isColecaoVazia ( $recordset )) {
		$tamanho = count ( $recordset );
		
		for($i = 0; $i < $tamanho; $i ++) {
			$atrib = $recordset [$i] [$nmColuna];
			$retorno .= $atrib . $pSeparador;
		}
		
		$qtdCharFim = strlen ( $retorno ) - strlen ( $pSeparador );
		// echo $qtdCharFim;
		$retorno = substr ( $retorno, 0, $qtdCharFim );
	}
	
	return $retorno;
}
function isArrayMultiDimensional($colecao) {
	// funcao marreta que verifica se o array eh muldimensional
	// se retornar zero, eh array simples
	$isArrayMultidimensional = 0;
	if ($colecao != null) {
		$isArrayMultidimensional = array_sum ( array_map ( 'is_array', $colecao ) );
	}
	
	$retorno = true;
	if ($isArrayMultidimensional == 0)
		$retorno = false;
	
	return $retorno;
}
function existeItemNoArray($item, $array) {
	$retorno = false;
	$tam = count ( $array );
	
	/*
	 * echo "ITEM: " . $item . "<br>";
	 * echo "ARRAY: " ;
	 * var_dump($array);
	 */
	
	for($i = 0; $i < $tam; $i ++) {
		$findme = $array [$i];
		$pos = strpos ( $item, $findme );
		
		if ($pos !== false) {
			$retorno = true;
			break;
		}
	}
	
	return $retorno;
}
function existeStr1NaStr2ComSeparador($str2, $str1comseparador) {
	$array = explode ( CAMPO_SEPARADOR, $str1comseparador );
	$tamanho = count ( $array );
	$retorno = false;
	
	// echo "<br> IMPRIMINDO OPCOES-----";
	// var_dump($array) . "<br>";
	
	for($i = 0; $i < $tamanho; $i ++) {
		$especie = $array [$i];
		// verifica se eh o tipo da especie em questao
		if($especie != null && $especie != ""){
			$existe = mb_stripos ( $str2, $especie );
			// echo "<br>$str2 x $especie";
			
			if ($existe !== false) {
				// if($existe){
				// echo "<br>EXISTE<br>";
				$retorno = true;
				break;
			}
		}
	}
	return $retorno;
}
function getIndicePosteriorAoUltimoNumeroAPartirDoComeco($param) {
	$tamanho = strlen ( $param );
	$retorno = null;
	
	// echo $tamanho;
	for($i = 0; $i < $tamanho; $i ++) {
		$char = substr ( $param, $i, 1 );
		// echo "<br>$char<br>";
		
		if (! isNumero ( $char )) {
			$retorno = $i;
			break;
		}
	}
	
	return $retorno;
}
function getIndiceBarraOuPonto($param) {
	$tamanho = strlen ( $param );
	$retorno = null;
	
	for($i = $tamanho - 1; $i >= 0; $i --) {
		$char = substr ( $param, $i, 1 );
		
		if ($char == "." || $char == "/") {
			$retorno = $i;
			break;
		}
	}
	
	return $retorno;
}
function getDocLinkMascaraImportacao($param) {
	$linkDoc = str_replace ( "\\", "\\\\", $param );
	return $linkDoc;
}
function getMoedaMascaraImportacao($param) {
	$tamanho = strlen ( "$param" );
	// echo "tamanho da string: " . $tamanho. "<br>";
	
	$referencia = "0123456789";
	
	$qtCasaDecimais = 0;
	for($i = $tamanho - 1; $i >= 0; $i --) {
		$char = substr ( "$param", $i, 1 );
		// echo ".$i.";
		
		if (strpos ( $referencia, "$char" ) === false) {
			// echo "char $char, tem casa decimal:".$i;
			$qtCasaDecimais = $tamanho - $i - 1;
			break;
		}
	}
	
	// echo "<br>". "casas decimais: " . $qtCasaDecimais. "<br>";
	
	$valor = str_replace ( ".", "", "$param" );
	$valor = str_replace ( ",", "", "$valor" );
	$valor = str_replace ( " ", "", "$valor" );
	
	$tamanho = strlen ( $valor );
	$valor = substr ( $valor, 0, $tamanho - $qtCasaDecimais ) . "." . substr ( $valor, $tamanho - $qtCasaDecimais, $qtCasaDecimais );
	
	return number_format ( $valor, $qtCasaDecimais, ',', '.' );
}
function isNumero($param) {
	return isNumeroComDecimal ( $param, true );
}
function isNumeroComDecimal($param, $isDecimal) {
	$referencia = "0123456789";
	
	if ($isDecimal)
		$referencia = $referencia . ".";
	
	$retorno = false;
	
	$tam = strlen ( $param . "" );
	// echo "tamanho da string do numero $param :" . $tam . "<br>";
	
	for($i = 0; $i < $tam; $i ++) {
		$digito = substr ( $param, $i, 1 );
		$val = "$digito";
		
		// if(strpos($referencia, "$val") === true){
		// eh o mesmo que, pois a posicao zero eh zero, e isso eh falso qd transformado pra boolean
		if (strpos ( $referencia, "$val" ) || $val == "0") {
			// echo $val. " é numero <br>";
			$retorno = true;
		} else {
			// echo $val. " nao é numero <br>";
			$retorno = false;
			break;
		}
	}
	
	return $retorno;
}
function removeColecaoAtributos($colecaoAtributos, $arrayAtribRemover) {
	$retorno = $colecaoAtributos;
	
	if ($arrayAtribRemover != null) {
		$totalResultado = count ( $arrayAtribRemover );
		// echo "<br> qtd elementos a remover: " . $totalResultado;
		
		for($i = 0; $i < $totalResultado; $i ++) {
			$atrib = $arrayAtribRemover [$i];
			$retorno = removeElementoArray ( $retorno, $atrib );
			// echo "<br>" . $i. $atrib;
		}
	}
	// echo "<br>";
	// var_dump($retorno);
	return $retorno;
}
function removeElementoArray($input, $elem) {
	$key = array_search ( $elem, $input );
	if ($key !== false) {
		// echo "<br> removendo elemento: " . $input[$key];
		// unset($input[$key]);
		$input [$key] = null;
	}
	
	return $input;
}
/*
 * faz o merge de array com as chaves
 */
function array_merge_keys() {
	$args = func_get_args ();
	$result = array ();
	foreach ( $args as &$array ) {
		foreach ( $array as $key => &$value ) {
			$result [$key] = $value;
		}
	}
	return $result;
}

function putElementoArrayComChaves($input, $elem) {
	return array_merge_keys ( $input, $elem);	
}
function getColecaoEntreSeparador($colecaoAtributos, $separador) {
	return getColecaoEntreSeparadorAspas ( $colecaoAtributos, $separador, false );
}
function getColecaoEntreSeparadorAspas($colecaoAtributos, $separador, $comAspas) {
	$retorno = "";
	$aspas = "'";
	if ($colecaoAtributos != null) {
		$tamanho = count ( $colecaoAtributos );
		// echo "<br> qtd registros: " . $tamanho;
		
		for($i = 0; $i <= $tamanho; $i ++) {
			$atrib = $colecaoAtributos [$i];
			
			if ($atrib != null) {
				
				if ($comAspas)
					$atrib = $aspas . $atrib . $aspas;
				
				$retorno .= $atrib . $separador;
			}
			// echo "$retorno<br>";
		}
		$retorno = substr ( $retorno, 0, count ( $retorno ) - 2 );
	}
	// echo $retorno;
	return $retorno;
}
function getChaveReferenciaGroupBy($registro, $colecaoColunasAgrupar) {
	$retorno = "";
	$tamanhoColecao = count ( $colecaoColunasAgrupar );
	for($i = 0; $i < $tamanhoColecao; $i ++) {
		$nmColuna = $colecaoColunasAgrupar [$i];
		$retorno .= CAMPO_SEPARADOR + $registro [$nmColuna];
	}
	return $retorno;
}
function getRecordSetGroupBy($recordset, $colecaoColunasAgrupar) {
	// a colecao deve estar ordenada de acordo com a regra de negocio
	$retorno = "";
	$indice = 0;
	
	if ($colecaoColunasAgrupar != null && $recordset != "") {
		$tamanho = count ( $colecaoColunasAgrupar );
		$tamanhoColecao = count ( $recordset );
		// echo "<br> qtd registros: " . $tamanho;
		
		$registroAtual = $recordset [0];
		// inclui sempre o primeiro
		$retorno [$indice] = $registroAtual;
		$indice ++;
		
		$strReferencia = getChaveReferenciaGroupBy ( $registroAtual, $colecaoColunasAgrupar );
		
		for($i = 1; $i < $tamanhoColecao; $i ++) {
			$registroAtual = $recordset [$i];
			$strComparacao = getChaveReferenciaGroupBy ( $registroAtual, $colecaoColunasAgrupar );
			
			if ($strComparacao != $strReferencia) {
				$retorno [$indice] = $registroAtual;
				$indice ++;
				// echo "<br>" . $strComparacao . "<br>";
				
				$strReferencia = getChaveReferenciaGroupBy ( $registroAtual, $colecaoColunasAgrupar );
			}
			// echo "$retorno<br>";
		}
	}
	// echo $retorno;
	return $retorno;
}
function getNomeArquivoPHP() {
	$link = getLinkChamadaPHP ();
	return basename ( $link, '.php' );
}
function isPastaRaiz() {
	$retorno = false;
	
	$pastaRaiz = "/wordpress/UNCT";
	$nmPasta = getNomePastaArquivoPHP ();
	
	// echo "$nmPasta<br>";
	$indice = getIndiceBarraOuPonto ( $nmPasta );
	$nmPasta = substr ( $nmPasta, 0, $indice );
	// echo "|$nmPasta<br>";
	// echo "|$pastaRaiz<br>";
	
	if (strtoupper ( $pastaRaiz ) == strtoupper ( $nmPasta ))
		$retorno = true;
	
	return $retorno;
}
function subirNivelPasta($pasta, $qtdNiveis) {
	$retorno = $pasta;
	$strARemover = "../"; // 3 digitos
	$fator = strlen ( $strARemover ); // 3 digitos
	if ($qtdNiveis != null) {
		// posicao inicial = $fator*$qtdNiveis
		// se sao 2 niveis, sao 6 digitos a apagar, por ex
		$indice = $fator * $qtdNiveis;
		$retorno = substr ( $retorno, $indice );
		
		/*
		 * for ($i=1; $i<=$qtdNiveis; $i++) {
		 * $retorno .= "../";
		 * }
		 */
	}
	return $retorno;
}
function getNomePastaArquivoPHP() {
	$link = getLinkChamadaPHP ();
	return dirname ( $link );
}
function getLinkChamadaPHP() {
	return $_SERVER ['PHP_SELF'];
}
function getAtributoComoBooleano($param) {
	$retorno = false;
	if ($param != null && $param == constantes::$CD_SIM) {
		$retorno = true;
	}
	return $retorno;
}

?>