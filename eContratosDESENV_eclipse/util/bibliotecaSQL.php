<?php
include_once("constantes.class.php");
include_once ("bibliotecaFuncoesPrincipal.php");

  //Class bibliotecaSQL {
  
	function getDataSQL($dataSQL) {
		$retorno = "";
		if ($dataSQL != null)
			//$retorno = date("Y-m-d", strtotime($dataSQL));
			$retorno = implode("-",array_reverse(explode("/",$dataSQL)));
			
		return $retorno;
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
		
		$pDataComparacao = "'" . $pDataComparacao . "'";
		
		$sqlFinal = $sqlClausulaVigenciaAtual =
			"( ( "
				. $pDataComparacao
				. " BETWEEN "
				. $nmColDtInicioVigencia
				. "\n AND "
				. $nmColDtFimVigencia
				. "\n ) OR ( "
				. $nmColDtInicioVigencia
				. " <= "
				. $pDataComparacao
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
	
?>