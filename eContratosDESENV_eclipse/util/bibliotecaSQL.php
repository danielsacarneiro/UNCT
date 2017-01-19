<?php
include_once("constantes.class.php");

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
?>