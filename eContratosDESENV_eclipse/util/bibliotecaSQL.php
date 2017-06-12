<?php
include_once("constantes.class.php");
include_once ("bibliotecaFuncoesPrincipal.php");

  //Class bibliotecaSQL {
  
	function getDataSQL($dataSQL) {
		$retorno = "";		
		if ($dataSQL != null){						
			//$retorno = date("Y-m-d", strtotime($dataSQL));
			$retorno = implode("-",array_reverse(explode("/",$dataSQL)));		
		}			
		return $retorno;
	}  
	
	function getSQLDataVigenteSqSimples(
			$pNmTableEntidade,
			$pNmColDtInicioVigencia,
			$pNmColDtFimVigencia) {
		
			$pDataComparacao = dtHoje;
		
			return getSQLDataVigente(
					$pNmTableEntidade,
					null,
					null,
					null,
					$pDataComparacao,
					$pNmColDtInicioVigencia,
					$pNmColDtFimVigencia);	
	}
	
	function getSQLDataNaoVigenteSqSimples(
			$pNmTableEntidade,
			$pNmColDtInicioVigencia,
			$pNmColDtFimVigencia) {
	
				$pDataComparacao = dtHoje;
	
				return getSQLDataNaoVigente(
						$pNmTableEntidade,
						null,
						null,
						null,
						$pDataComparacao,
						$pNmColDtInicioVigencia,
						$pNmColDtFimVigencia);
	}
	
	function getSQLDataNaoVigente(
			$pNmTableEntidade,
			$pNmColSequencial,
			$pChaveTuplaComparacaoSemSequencial,
			$pChaveGroupBy,
			$pDataComparacao,
			$pNmColDtInicioVigencia,
			$pNmColDtFimVigencia) {
	
			$nmColDtInicioVigencia = "$pNmTableEntidade.$pNmColDtInicioVigencia";
			$nmColDtFimVigencia = "$pNmTableEntidade.$pNmColDtFimVigencia";
					
			$sqlFinal = $sqlClausulaVigenciaAtual =
					"( "					
					. $pNmColDtFimVigencia
					. " < "
					. getVarComoDataSQL($pDataComparacao)
					. ")";
	
		return $sqlFinal;
	}
	
	function getSQLDataVigenteSimplesPorData(
			$pNmTableEntidade,
			$pDataComparacao,
			$pNmColDtInicioVigencia,
			$pNmColDtFimVigencia) {
		
				return getSQLDataVigente(
						$pNmTableEntidade,
						null,
						null,
						null,
						$pDataComparacao,
						$pNmColDtInicioVigencia,
						$pNmColDtFimVigencia);		
	}
	
	
	function getSQLDataVigente(
		$pNmTableEntidade,
		$pNmColSequencial,
		$pChaveTuplaComparacaoSemSequencial,
		$pChaveGroupBy,
		$pDataComparacao,
		$pNmColDtInicioVigencia,
		$pNmColDtFimVigencia) {
		
		$nmColDtInicioVigencia = "$pNmTableEntidade.$pNmColDtInicioVigencia";
		$nmColDtFimVigencia = "$pNmTableEntidade.$pNmColDtFimVigencia";
		
		//$pDataComparacao = "'" . $pDataComparacao . "'";
		
		$sqlFinal = $sqlClausulaVigenciaAtual =
			"( ( "
				. getVarComoDataSQL($pDataComparacao)
				. " BETWEEN "
				. $nmColDtInicioVigencia
				. "\n AND "
				. $nmColDtFimVigencia
				. "\n ) OR ( "
				. $nmColDtInicioVigencia
				. " <= "
				. getVarComoDataSQL($pDataComparacao)
				. " "
				. "\n AND "
				. $nmColDtFimVigencia
				. " IS NULL"
				. ") )";

		//a query abaixo serah usada quando a consulta utilizar o MAIOR sq
		/*$sqlFinal =
			"( ("
				. $pChaveTuplaComparacaoSemSequencial
				. ", "
				. $pNmTableEntidade
				. "."
				. $pNmColSequencial
				. ")\n IN \n( SELECT "
				. $pChaveTuplaComparacaoSemSequencial
				. ", MAX("
				. $pNmTableEntidade
				. "."
				. $pNmColSequencial
				. ")"
				. "\n FROM "
				. $pNmTableEntidade
				. "\n WHERE "
				. $sqlClausulaVigenciaAtual
				. "\n GROUP BY "
				. $pChaveGroupBy
				. ")\n OR " 
				. $nmColDtInicioVigencia
				. " IS NULL)";*/

		return $sqlFinal;
	}
	
	function getSQLIntervaloDatas($tableEntidade, $pNmColDataAComparar, $data1, $data2){
		
		if($data1 == null)
			$data1 = constantes::$DATA_INICIO;

		if($data2 == null)
			$data2 = constantes::$DATA_FIM;
		
		$coluna = $tableEntidade. "." . $pNmColDataAComparar;		
		$retorno =
				"("
				. $coluna
				. " BETWEEN '"
				. getDataSQL($data1)
				. "'\n AND '"
				. getDataSQL($data2)
				. "'\n )";
				
		/*$retorno =
			"( ( "
				. $coluna
				. " BETWEEN '"
				. $data1
				. "'\n AND '"
				. $data2
				. "'\n ) OR ( "
				. nmColDtInicioVigencia
				. " <= "
				. pDataComparacao
				. " "
				. "\n AND "
				. nmColDtFimVigencia
				. " IS NULL"
				. ") )";*/
				
		return $retorno;
	}	
			
	function getSQLStringFormatadaColecaoIN($colecaoValores, $isString){
		$separador = ",";	
		
		return getColecaoEntreSeparadorAspas($colecaoValores, $separador, $isString);
	}
	
	function getSQLStringArgumentosFormatadoColecao($colecaoValores, $nmAtributo, $operadorSQL, $operardorValor, $isString) {
		$retorno = "";
		$aspas = "'";
		
		$isOperadorSQLLIKE = (mb_stripos($operardorValor, "LIKE") !== false);
		//echo $isOperadorSQLLIKE;
		
		if($colecaoValores != null){
			$tamanho = count($colecaoValores);
			//echo "<br> qtd registros: " . $tamanho;
			 
			for ($i=0; $i<$tamanho; $i++) {
				$atrib = $colecaoValores[$i];
	
				if($atrib != null){
					 
					if($isString){
						
						if ($isOperadorSQLLIKE)
							$atrib = "%" . $atrib . "%";
						
						$atrib = $aspas . $atrib . $aspas;
					}						
						 
					$retorno .= "\n". $nmAtributo . $operardorValor . $atrib . $operadorSQL;
				}
				//echo "$retorno<br>";
			}
			//tamanho da string retirada do fim do retorno
			$qtdCharFim = strlen($retorno) - strlen($operadorSQL);
			//echo $qtdCharFim;
			$retorno = substr($retorno, 0, $qtdCharFim);
		}
		//echo $retorno;
		return $retorno;
	}
	
	/**
	 *FUNCOES MANIPULACAO
	 */
	function substituirCaracterSQLLike($param){
		return substituirCaracterEspecial("*", "%", $param);
	}
	
	function substituirCaracterEspecial($strOrigem, $strDestino, $param){
		$retorno = null;
		if($param != null){			
			$retorno = str_replace($strOrigem, $strDestino, utf8_encode($param));			
			//$retorno = str_replace($strOrigem, $strDestino, $param);
		}	
		return $retorno;
	}
	
	function getVarComoString($param){
		//return "'" . utf8_encode($param) . "'";
		$retorno = "null";
		if($param != null){			
			//corrige a existencia de aspas simples pq dah pau no banco
			$valor = str_replace("'", '"', $param);
			$retorno =  "'" . trim($valor) . "'";
		}		
	
		return $retorno;
	}
	
	function getVarComoNumero($param){
		$retorno = "null";
		$isNum = isNumero($param);
		if($isNum){
			$retorno =  trim($param);
			// echo "EH NUMERO";
		}
		return $retorno;
	}
	
	function getVarComoDecimal($param){
		return getDecimalSQL($param);
	}
	
	function getVarComoData($param){
		return getVarComoDataSQL($param);
	}
	
	function getVarComoDataSQL($param){
		$retorno = "null";
		//echo "<br> parametro conversao data sql:".$param;
		if($param != null)
			$retorno = "'" . (substr($param,6,4)) . "-" . substr($param,3,2) . "-" . substr($param,0,2) . "'";
		
		return $retorno;
	}
	
	function getDecimalSQL($param){
		$retorno = "null";
		$valor = str_replace(" ", "", "$param");
		$valor = str_replace(".", "", "$valor");
		$valor = str_replace(",", ".", "$valor");
	
		//echo $valor;
		if(isNumero($valor)){
			$retorno = $valor;
			//echo "É NÚMERO! <BR>";
		}
		//else
		//echo "NÃO É NÚMERO! <BR>";
	
		return $retorno;
	}
	
?>