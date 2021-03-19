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
function getSQLCASE($atributo, $valorAComparar , $valorSeIgual , $valorELSE, $nmAtributoAS=null) {
	if($nmAtributoAS != null){
		$nmAtributoAS = " AS $nmAtributoAS ";		
	}
	return "CASE $atributo WHEN $valorAComparar THEN $valorSeIgual ELSE $valorELSE END $nmAtributoAS";
}
//sintaxe do CASE para comparar atributo nulo eh um pouco diferente
function getSQLCASEIsNULL($atributo, $valorSeNulo, $valorSeNaoNulo, $nmAtributoAS=null) {
	return getSQLCASE("", " $atributo IS NULL ", $valorSeNulo, $valorSeNaoNulo, $nmAtributoAS);
}

function getSQLCASEBooleano($expressaoBooleana, $resultadoSeTrue, $resultadoSeFalse, $nmAtributoAS=null) {
	return getSQLCASE("", $expressaoBooleana, $resultadoSeTrue, $resultadoSeFalse, $nmAtributoAS);
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
function getSQLDataVigenciaFutura($pNmTableEntidade, $pNmColDtInicioVigencia, $pDataComparacao=null) {
	if($pDataComparacao == null){
		$pDataComparacao = getDataHoje();
	}
	if ($pNmTableEntidade != null) {
		$pNmTableEntidade = "$pNmTableEntidade.";
	}
	$nmColDtInicioVigencia = $pNmTableEntidade . $pNmColDtInicioVigencia;

	$sqlFinal = "( " . $nmColDtInicioVigencia . " > " . getVarComoDataSQL ( $pDataComparacao ) . ")";

	return $sqlFinal;
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

function formataArrayChaveTuplaComparacaoSequencial($pChaveTuplaComparacaoSemSequencial, $pNmTableEntidade){
	if(is_array($pChaveTuplaComparacaoSemSequencial)){
		for ($i=0; $i< sizeof($pChaveTuplaComparacaoSemSequencial); $i++){
			$atributo = $pChaveTuplaComparacaoSemSequencial[$i];
			if(strpos($atributo, ".") === false){
				if(isAtributoValido($pNmTableEntidade)){
					$pNmTableEntidadeTemp = "$pNmTableEntidade.";
				}
				$atributo = $pNmTableEntidadeTemp . $atributo;
				$pChaveTuplaComparacaoSemSequencial[$i]=$atributo;
			}
				
		}
		$pChaveTuplaComparacaoSemSequencial = getColecaoEntreSeparador($pChaveTuplaComparacaoSemSequencial, ",");
	}
	
	return $pChaveTuplaComparacaoSemSequencial;
}

function getSQLDataVigenteArrayParam($pArrayParam) {	
	$pNmTableEntidade = $pArrayParam[0];
	$pNmColSequencial = $pArrayParam[1];
	$pChaveTuplaComparacaoSemSequencial = $pArrayParam[2];
	//$pChaveGroupBy = $pArrayParam[3];
	$pDataComparacao = $pArrayParam[4];
	$pNmColDtInicioVigencia = $pArrayParam[5];
	$pNmColDtFimVigencia = $pArrayParam[6];
	$isTrazerMaiorSqVigente = $pArrayParam[7];	
	$sqlFiltroInternoMaiorSq = $pArrayParam[8];
	$isTrazerVigenciaFutura = $pArrayParam[9];
	$isPermiteDataFimNula = $pArrayParam[10];
	
	if($isPermiteDataFimNula  === null){
		$isPermiteDataFimNula = true;
	}
	
	$pChaveTuplaComparacaoSemSequencial = formataArrayChaveTuplaComparacaoSequencial($pChaveTuplaComparacaoSemSequencial,$pNmTableEntidade);
	
	$nmColDtInicioVigencia = $pNmColDtInicioVigencia;
	$nmColDtFimVigencia = $pNmColDtFimVigencia;
	
	if(isAtributoValido($pNmTableEntidade)){
		$nmColDtInicioVigencia = "$pNmTableEntidade." . $pNmColDtInicioVigencia;
		$nmColDtFimVigencia = "$pNmTableEntidade." . $pNmColDtFimVigencia;
	}
	
	if($isPermiteDataFimNula){
		$sqlPermiteDataFimNula = " OR ( $nmColDtInicioVigencia  <= " . getVarComoDataSQL ( $pDataComparacao ) . "\n AND $nmColDtFimVigencia IS NULL" . ")";
		//echo "NULO";
	}else{
		$sqlNAOPermiteDataFimNula = " $nmColDtFimVigencia IS NOT NULL AND $nmColDtFimVigencia <> '0000-00-00' AND ";
		//echo "NAO NULO";
	}
	//$sqlComparacaoDatas = "(( " . getVarComoDataSQL ( $pDataComparacao ) . " BETWEEN " . $nmColDtInicioVigencia . "\n AND " . $nmColDtFimVigencia . "\n ) OR ( " . $nmColDtInicioVigencia . " <= " . getVarComoDataSQL ( $pDataComparacao ) . "\n AND " . $nmColDtFimVigencia . " IS NULL" . ")";
	$sqlComparacaoDatas = "(( $sqlNAOPermiteDataFimNula " . getVarComoDataSQL ( $pDataComparacao ) . " BETWEEN " . $nmColDtInicioVigencia . "\n AND " . $nmColDtFimVigencia . "\n ) " . $sqlPermiteDataFimNula;
	
	if($isTrazerVigenciaFutura){
		$sqlComparacaoDatas .= "\n OR ( " . $nmColDtInicioVigencia . " > " . getVarComoDataSQL ( $pDataComparacao ) . ")";
	}
	
	$sqlComparacaoDatas .= ")";
	
	$sqlFinal = "($sqlComparacaoDatas";
	
	if($isTrazerMaiorSqVigente){
		//$sqlFinal .= " AND (EXISTS SELECT MAX($pNmColSequencial) FROM $pNmTableEntidade WHERE )";
		//echo "entrou";
		
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
	$retorno = "( $coluna BETWEEN '" . getDataSQL ( $data1 ) . "'\n AND '" . getDataSQL ( $data2 ) . "'\n )";
	
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
 * Busca dentro de um atributo string com campo separador no banco de dados
 * @param unknown $colecaoAtributos
 * @param unknown $nmAtributo
 * @param string $operador
 * @return string
 */
function getSQLBuscarStringCampoSeparador($colecaoAtributos, $nmAtributo, $operador = "OR") {
	//$strFormato = " LOCATE('$tpDemandaContrato',".voDemanda::$nmAtrTpDemandaContrato.") ";
	//ECHO (" OPERADOR $operador ");
	$retorno = "";
	$separador = "";
	if ($colecaoAtributos != null) {

		if(!is_array($colecaoAtributos)){
			$colecaoAtributos = array($colecaoAtributos);
		}

		$tamanho = count ( $colecaoAtributos );
		// echo "<br> qtd registros: " . $tamanho;

		for($i = 0; $i <= $tamanho; $i ++) {
			$atrib = $colecaoAtributos [$i];

			if ($atrib != null) {
				if(constantes::$CD_OPCAO_NENHUM == $atrib){
					$retorno .= " $separador $nmAtributo  IS NULL ";
				}else{
					$retorno .= " $separador LOCATE('$atrib',$nmAtributo) ";
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
 * busca se o atributo passado como parametro eh nulo ou nao , ou eh do tipo sim ou nao: NADA MAIS QUE ISSO
 * @param unknown $colecaoAtributos
 * @param unknown $nmAtributoColecao
 * @param string $operador
 * @return string
 */
function getSQLBuscarAtributoSimOuNaoOuSeNulo($colecaoAtributos, $nmAtributoColecao, $operador = "OR", $isTpOperacaoSeNulo = true) {
	$retorno = "";
	$separador = "";
	if ($colecaoAtributos != null) {
		
		if (! is_array ( $colecaoAtributos )) {
			$colecaoAtributos = array (
					$colecaoAtributos 
			);
		}
		
		$tamanho = count ( $colecaoAtributos );
		// echo "<br> qtd registros: " . $tamanho;
		
		for($i = 0; $i <= $tamanho; $i ++) {
			$atrib = $colecaoAtributos [$i];
			
			if ($atrib != null) {
				$arrayAtrib = explode ( CAMPO_SEPARADOR, $atrib );
				$chave = $arrayAtrib [0];
				$opcaoSelecionada = $arrayAtrib [1];
				// echoo($atrib);
				if (isAtributoValido ( $opcaoSelecionada )) {
					if ($isTpOperacaoSeNulo) {
						//se a consulta eh pra verificar se eh nulo
						if (constantes::$CD_NAO == $opcaoSelecionada) {
							$comparacaoTemp = "IS NULL";
						} else {
							$comparacaoTemp = "IS NOT NULL";
						}
					} else {
						//ou se a consulta eh pra verificar se eh sim ou nao
						$comparacaoTemp = " = " . getVarComoString ( $opcaoSelecionada );
					}
					$retorno .= " $separador " . dominio::getDescricaoStatic ( $chave, $nmAtributoColecao ) . "  $comparacaoTemp ";
				}
				$separador = " $operador ";
			}
		}
		
		$retorno = "($retorno)";
	}
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

/**
 * funcao que serve para indicar o atributo que sera comparado e se sera operacao de igual ou de diferenca
 * via de regra, recebera um array bidirecional: posicao 0 eh a operacao, posicao 1, os dados
 * @param unknown $param
 * @param unknown $nmTabelaAtributo
 * @param unknown $nmTabela
 * @return string
 */
 function getSQLFiltroAtributoArrayComparacao($param, $nmAtributo, $nmTabelaParam=null, $isString = true){
	$operador = " = ";
	$operadorIN = " IN ";
	$dados = $param;
	if(is_array($param)){
		if($param[0] == constantes::$CD_OPCAO_CONSULTA_DIFERENTE
				|| $param[0] == constantes::$CD_OPCAO_CONSULTA_IGUAL){
					$dados = $param[1];

					if($param[0] == constantes::$CD_OPCAO_CONSULTA_DIFERENTE){
						$isDiferente  = true;
						$strTemp = "";
						$operador = " <> ";
						$operadorIN = " NOT IN ";
					}
		}
	}
		
	$comparar = " $operador '$dados' ";
	if(is_array($dados)){
		$comparar = " $operadorIN (" . getSQLStringFormatadaColecaoIN($dados, $isString) . ") ";
	}
	
	if($nmTabelaParam != null){
		$nmTabelaParam = "$nmTabelaParam.";
	}
	
	if($isDiferente){
		$strTemp = " $nmTabelaParam" . "$nmAtributo IS NULL OR "  ;
		
	}
	
	$retorno = " ($strTemp $nmTabelaParam" . $nmAtributo . "$comparar) ";
	
	return $retorno;
}

function removeNomeTabelaDoAtributo ($atributo){
	$retorno = $atributo;
	$arraytemp = explode(".", $retorno);
	if(is_array($arraytemp) && sizeof($arraytemp) > 1){
		$nmTabelaARemover = $arraytemp[0];
		//$nmTabelaARemover = str_replace(",", "", $nmTabelaARemover);
		//echoo($atributo );
	
		$retorno = str_replace("$nmTabelaARemover.", "", $retorno);
	
	}
	
	return $retorno;
}

?>