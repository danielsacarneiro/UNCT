<?php
include_once ("constantes.class.php");
include_once ("bibliotecaFuncoesPrincipal.php");

// bibliotecaSQL

/**
 * traz tabela atual + historico para ser usado no join
 * @param unknown $vo
 * @return string
 */
function getSQLTabelaTrazendoHistorico($vo){
	$nmTabelaOriginal = $vo->getNmTabelaEntidade(false);
	$nmTabelaHist = $vo->getNmTabelaEntidade(true);
	$atributosHist = $atributos = $vo->getAtributosFilho();
	//o sqhist vem com valor apenas na tabela de historico
	$atributosHist[]=voentidade::$nmAtrSqHist;
	$atributos[] = "0";
	$atributos = getSQLStringFormatadaColecaoIN($atributos);
	$atributosHist = getSQLStringFormatadaColecaoIN($atributosHist);
	$nmTabelaGeral = $vo::getNmTabelaGeralComHistorico();
	
	return "(SELECT $atributos FROM $nmTabelaOriginal UNION SELECT $atributosHist FROM $nmTabelaHist) $nmTabelaGeral ";	
}

function getSQLNmContratada($comAliasNoAtributo = true) {
	$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
	$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
	
	$colecaoAtributoCoalesceNmPessoa = array (
			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
			$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato 
	);
	$alias = vopessoa::$nmAtrNome;
	if(!$comAliasNoAtributo){
		$alias = null;		
	}
	return getSQLCOALESCE ( $colecaoAtributoCoalesceNmPessoa,  $alias);
}
function getSQLCOALESCE($arrayAtributos, $nmAtributoRetornoClausulaAS = null) {
	$retorno = "COALESCE (";
	foreach ( $arrayAtributos as $item ) {
		$retorno .= $item . ",";
	}
	
	// retira a ultima virgula
	$retorno = substr ( $retorno, 0, strlen ( $retorno ) - 1 );
	
	$retorno .= ") ";
	if ($nmAtributoRetornoClausulaAS != null) {
		$retorno .= " AS " . $nmAtributoRetornoClausulaAS;
	}
	return $retorno;
}
function getSQLCASE($atributo, $valorCondicao, $valorTHEN, $valorELSE) {
	return "CASE $atributo WHEN $valorCondicao THEN $valorTHEN ELSE $valorELSE END ";
}
function getDataSQL($dataWeb) {
	$retorno = "";
	if ($dataWeb != null) {
		// $retorno = date("Y-m-d", strtotime($dataSQL));
		$retorno = implode ( "-", array_reverse ( explode ( "/", $dataWeb ) ) );
	}
	return $retorno;
}
function getSQLDataVigenteSqSimples($pNmTableEntidade, $pNmColDtInicioVigencia, $pNmColDtFimVigencia) {
	$pDataComparacao = dtHoje;
	
	return getSQLDataVigente ( $pNmTableEntidade, null, null, null, $pDataComparacao, $pNmColDtInicioVigencia, $pNmColDtFimVigencia );
}
function getSQLDataNaoVigenteSqSimples($pNmTableEntidade, $pNmColDtInicioVigencia, $pNmColDtFimVigencia) {
	$pDataComparacao = dtHoje;
	
	return getSQLDataNaoVigente ( $pNmTableEntidade, null, null, null, $pDataComparacao, $pNmColDtInicioVigencia, $pNmColDtFimVigencia );
}
function getSQLDataNaoVigente($pNmTableEntidade, $pNmColSequencial, $pChaveTuplaComparacaoSemSequencial, $pChaveGroupBy, $pDataComparacao, $pNmColDtInicioVigencia, $pNmColDtFimVigencia) {
	if ($pNmTableEntidade != null) {
		$pNmTableEntidade = "$pNmTableEntidade.";
	}
	$nmColDtInicioVigencia = $pNmTableEntidade . $pNmColDtInicioVigencia;
	$nmColDtFimVigencia = $pNmTableEntidade . $pNmColDtFimVigencia;
	
	$sqlFinal = $sqlClausulaVigenciaAtual = "( " . $pNmColDtFimVigencia . " < " . getVarComoDataSQL ( $pDataComparacao ) . ")";
	
	return $sqlFinal;
}
function getSQLDataVigenteSimplesPorData($pNmTableEntidade, $pDataComparacao, $pNmColDtInicioVigencia, $pNmColDtFimVigencia) {
	return getSQLDataVigente ( $pNmTableEntidade, null, null, null, $pDataComparacao, $pNmColDtInicioVigencia, $pNmColDtFimVigencia );
}

function getSQLDataVigente($pNmTableEntidade, $pNmColSequencial, $pChaveTuplaComparacaoSemSequencial, $pChaveGroupBy, $pDataComparacao, $pNmColDtInicioVigencia, $pNmColDtFimVigencia, $isTrazerMaiorSqVigente=false) {
	$pArrayParam = array(
			$pNmTableEntidade,
			$pNmColSequencial,
			$pChaveTuplaComparacaoSemSequencial,
			$pChaveGroupBy,
			$pDataComparacao,
			$pNmColDtInicioVigencia,
			$pNmColDtFimVigencia,
			$isTrazerMaiorSqVigente);
	
	return getSQLDataVigenteArrayParam($pArrayParam);	
}

function getSQLDataVigenteArrayParam($pArrayParam) {	
	$pNmTableEntidade = $pArrayParam[0];
	$pNmColSequencial = $pArrayParam[1];
	$pChaveTuplaComparacaoSemSequencial = $pArrayParam[2];
	$pChaveGroupBy = $pArrayParam[3];
	$pDataComparacao = $pArrayParam[4];
	$pNmColDtInicioVigencia = $pArrayParam[5];
	$pNmColDtFimVigencia = $pArrayParam[6];
	$isTrazerMaiorSqVigente = $pArrayParam[7];	
	$sqlFiltroInternoMaiorSq = $pArrayParam[8];

	/*if ($pNmTableEntidade != null) {
		$pNmTableEntidade = "$pNmTableEntidade.";
	}*/
	
	if(is_array($pChaveTuplaComparacaoSemSequencial)){
		for ($i=0; $i< sizeof($pChaveTuplaComparacaoSemSequencial); $i++){
			$atributo = $pChaveTuplaComparacaoSemSequencial[$i];
			if(strpos($atributo, ".") === false){
				$atributo = "$pNmTableEntidade." . $atributo;
				$pChaveTuplaComparacaoSemSequencial[$i]=$atributo;
			}
			
		}
		$pChaveTuplaComparacaoSemSequencial = getColecaoEntreSeparador($pChaveTuplaComparacaoSemSequencial, ",");
	}
	
	
	$nmColDtInicioVigencia = "$pNmTableEntidade." . $pNmColDtInicioVigencia;
	$nmColDtFimVigencia = "$pNmTableEntidade." . $pNmColDtFimVigencia;
	
	// $pDataComparacao = "'" . $pDataComparacao . "'";	
	$sqlComparacaoDatas = "(( " . getVarComoDataSQL ( $pDataComparacao ) . " BETWEEN " . $nmColDtInicioVigencia . "\n AND " . $nmColDtFimVigencia . "\n ) OR ( " . $nmColDtInicioVigencia . " <= " . getVarComoDataSQL ( $pDataComparacao ) . " " . "\n AND " . $nmColDtFimVigencia . " IS NULL" . "))";
	
	$sqlFinal = "($sqlComparacaoDatas";
	
	if($isTrazerMaiorSqVigente){
		//$sqlFinal .= " AND (EXISTS SELECT MAX($pNmColSequencial) FROM $pNmTableEntidade WHERE )";
		
		if($sqlFiltroInternoMaiorSq != null){
			$sqlFiltroInternoMaiorSq = " AND " . $sqlFiltroInternoMaiorSq;
		}
		$sqlFinal .=
		" AND (("
				. $pChaveTuplaComparacaoSemSequencial
				. ", "
				. $pNmTableEntidade
				. "."
				. $pNmColSequencial
				. ")\n IN \n( SELECT "
				. $pChaveTuplaComparacaoSemSequencial
				. ", MAX($pNmTableEntidade.$pNmColSequencial)"
				. "\n FROM "
				. $pNmTableEntidade
				. " WHERE $sqlComparacaoDatas"
				. $sqlFiltroInternoMaiorSq
				. "\n GROUP BY "
				. $pChaveTuplaComparacaoSemSequencial
				. "))";
		
	}	
	
	$sqlFinal .= ") ";
	// a query abaixo serah usada quando a consulta utilizar o MAIOR sq	
	 
	
	return $sqlFinal;
}
function getSQLIntervaloDatas($tableEntidade, $pNmColDataAComparar, $data1, $data2) {
	if ($data1 == null)
		$data1 = constantes::$DATA_INICIO;
	
	if ($data2 == null)
		$data2 = constantes::$DATA_FIM;
	
	$coluna = $tableEntidade . "." . $pNmColDataAComparar;
	$retorno = "(" . $coluna . " BETWEEN '" . getDataSQL ( $data1 ) . "'\n AND '" . getDataSQL ( $data2 ) . "'\n )";
	
	/*
	 * $retorno =
	 * "( ( "
	 * . $coluna
	 * . " BETWEEN '"
	 * . $data1
	 * . "'\n AND '"
	 * . $data2
	 * . "'\n ) OR ( "
	 * . nmColDtInicioVigencia
	 * . " <= "
	 * . pDataComparacao
	 * . " "
	 * . "\n AND "
	 * . nmColDtFimVigencia
	 * . " IS NULL"
	 * . ") )";
	 */
	
	return $retorno;
}
function getSQLStringFormatadaColecaoIN($colecaoValores, $isString=false) {
	$separador = ",";
	
	return getColecaoEntreSeparadorAspas ( $colecaoValores, $separador, $isString );
}

function getSQLStringArgumentosFormatadoColecao($colecaoValores, $nmAtributo, $operadorSQL, $operadorValor, $isString) {
	$retorno = "";
	$aspas = "'";
	
	$isOperadorSQLLIKE = (mb_stripos ( $operadorValor, "LIKE" ) !== false);
	// echo $isOperadorSQLLIKE;
	
	if ($colecaoValores != null) {
		$tamanho = count ( $colecaoValores );
		// echo "<br> qtd registros: " . $tamanho;
		
		for($i = 0; $i < $tamanho; $i ++) {
			$atrib = $colecaoValores [$i];
			
			if ($atrib != null) {
				
				if ($isString) {
					
					if ($isOperadorSQLLIKE)
						$atrib = "%" . $atrib . "%";
					
					$atrib = $aspas . $atrib . $aspas;
				}
				
				$retorno .= "\n" . $nmAtributo . $operadorValor . $atrib . $operadorSQL;
			}
			// echo "$retorno<br>";
		}
		// tamanho da string retirada do fim do retorno
		$qtdCharFim = strlen ( $retorno ) - strlen ( $operadorSQL );
		// echo $qtdCharFim;
		$retorno = substr ( $retorno, 0, $qtdCharFim );
	}
	// echo $retorno;
	return $retorno;
}

/**
 * Se $nmAtributo for uma colecao, sera uma colecao de codigo (do dominio trabalhado) relacionado ao atributo a ser comparado no banco
 * @param unknown $colecaoAtributos
 * @param unknown $nmAtributo
 * @param string $operador
 * @return string
 */
function getSQLBuscarStringCampoSeparador($colecaoAtributos, $nmAtributoColecao, $operador = "OR") {	
	//$strFormato = " LOCATE('$tpDemandaContrato',".voDemanda::$nmAtrTpDemandaContrato.") ";
	//ECHO (" OPERADOR $operador ");
	$retorno = "";
	$separador = "";
	if ($colecaoAtributos != null) {
		$tamanho = count ( $colecaoAtributos );
		// echo "<br> qtd registros: " . $tamanho;
		
		if(!is_array($colecaoAtributos)){
			$colecaoAtributos = array($colecaoAtributos);
		}

		for($i = 0; $i <= $tamanho; $i ++) {
			$atrib = $colecaoAtributos [$i];
				
			if ($atrib != null) {	
 
				if(!is_array($nmAtributoColecao)){					
					//a estrutura da consulta dependera do tipo passado como parametro em $nmAtributoColecao
					if(constantes::$CD_OPCAO_NENHUM == $atrib){
						$retorno .= " $separador $nmAtributoColecao  IS NULL ";
					}else{				
						$retorno .= " $separador LOCATE('$atrib',$nmAtributoColecao) ";
					}					
				}else{
					//echo "aqui";
					// var_dump($colecaoAtributos);
					//formato eh "05*S"
					$arrayAtrib = explode ( CAMPO_SEPARADOR, $atrib );
					$chave = $arrayAtrib[0];					
					$opcaoSelecionada = $arrayAtrib[1];					
					//echoo($atrib);					
					if(isAtributoValido($opcaoSelecionada)){
						if(constantes::$CD_NAO == $opcaoSelecionada){
							$comparacaoTemp = "IS NULL";
						}else{
							$comparacaoTemp = "IS NOT NULL";
						}
						$retorno .= " $separador " . dominio::getDescricaoStatic($chave, $nmAtributoColecao) . "  $comparacaoTemp ";
					}
					//$retorno .= " $separador TRUE ";
					
					//echoo ($retorno);
				}
				
				$separador = " $operador ";
			}
			// echo "$retorno<br>";
		}
		
		$retorno = "($retorno)";
		//$retorno = substr ( $retorno, 0, count ( $retorno ) - 2 );
	}
	// echo $retorno;
	return $retorno;
}

/**
 * FUNCOES MANIPULACAO
 */
function substituirCaracterSQLLike($param) {
	return substituirCaracterEspecial ( "*", "%", $param );
}
function getSQLLike($param) {
	return " LIKE '%$param%'";
}
function substituirCaracterEspecial($strOrigem, $strDestino, $param) {
	$retorno = null;
	if ($param != null) {
		$retorno = str_replace ( $strOrigem, $strDestino, utf8_encode ( $param ) );
		// $retorno = str_replace($strOrigem, $strDestino, $param);
	}
	return $retorno;
}
function getVarComoString($param) {
	// return "'" . utf8_encode($param) . "'";
	$retorno = "null";
	if ($param != null) {
		// corrige a existencia de aspas simples pq dah pau no banco
		$valor = str_replace ( "'", '"', $param );
		$retorno = "'" . trim ( $valor ) . "'";
	}
	
	return $retorno;
}
function getVarComoNumero($param) {
	$retorno = "null";
	$isNum = isNumero ( $param );
	if ($isNum) {
		$retorno = trim ( $param );
		// echo "EH NUMERO";
	}else{
		if(trim($param) != "")
			throw new excecaoGenerica("Parâmetro incluído não é um número: $param .");
	}
	return $retorno;
}
function getVarComoDecimal($param) {
	return getDecimalSQL ( $param );
}
function getVarComoData($param) {
	return getVarComoDataSQL ( $param );
}
function getVarComoDataSQL($param) {
	$retorno = "null";
	// echo "<br> parametro conversao data sql:".$param;
	if ($param != null)
		$retorno = "'" . (substr ( $param, 6, 4 )) . "-" . substr ( $param, 3, 2 ) . "-" . substr ( $param, 0, 2 ) . "'";
	
	return $retorno;
}
function getDecimalSQL($param) {
	$retorno = "null";
	$valor = str_replace ( " ", "", "$param" );
	$valor = str_replace ( ".", "", "$valor" );
	$valor = str_replace ( ",", ".", "$valor" );
	
	// echo $valor;
	if (isNumero ( $valor )) {
		$retorno = $valor;
		// echo "$valor É NÚMERO! <BR>";
	}else{
		if(trim($param) != "")
			throw new excecaoGenerica("Parâmetro incluído não é um decimal: $param .");
	}
	
	/*else
	 echo " $valor NÃO É NÚMERO! <BR>";*/
	
	return $retorno;
}
function getDataSQLFormatada($ano, $mes, $dia) {
	return " CONCAT($ano,'-', RIGHT(CONCAT ('0',$mes),2), '-', RIGHT(CONCAT ('0',$dia),2)) ";
}
function getDataSQLDiferencaAnos($data1, $data2) {
	//return " TIMESTAMPDIFF(YEAR, $data1, $data2) ";
	return " ROUND(DATEDIFF($data2, $data1)/365) ";
}
function getDataSQLDiferencaDias($data1, $data2) {
	// echoo("data $data1");
	return " DATEDIFF($data2, $data1) ";
}

?>