<?php
include_once "config.obj.php";
include_once "db.obj.php";
include_once (caminho_util . "paginacao.php");
include_once (caminho_util . "bibliotecaHTML.php");
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
// include_once (caminho_excecoes . "ExcecaoMaisDeUmRegistroRetornado.php");
class dbprocesso {
	var $cDb;
	var $cConfig;
	static $nmCampoCount = "nmCampoCount";
	static $nmTabelaUsuarioInclusao = "TAB_USU_INCLUSAO";
	static $nmTabelaUsuarioUltAlteracao = "TAB_USU_ULT_ALTERACAO";
	static $nmTabelaUsuarioOperacao = "TAB_USU_OPERACAO";
	
	// ...............................................................
	// construtor
	function __construct() {
		$this->cConfig = new config ();
		$this->cDb = new db ();
		$this->cDb->abrirConexao ( $this->cConfig->db, $this->cConfig->login, $this->cConfig->senha, $this->cConfig->odbc, $this->cConfig->driver, $this->cConfig->servidor );
	}
	function incluirHistorico($voEntidade) {
		// par ao historico funcionar, a tabela de historico deve estar adequada a estrutura da query abaixo
		/*
		 * deve possuir sempre:
		 * 1 - o hist como seguencial
		 * 2 - o dhoperacao (CURRENT TIMESTAMP) que diz a hora em q o registro foi historiado
		 * 3 - o usuario da operacao id_user logado que fez a operacao
		 */
		$tabelaHistorico = $voEntidade->getNmTabelaEntidade ( true );
		
		$novoSeq = " SELECT MAX(" . voentidade::$nmAtrSqHist . ")+1 FROM " . $tabelaHistorico;
		
		$query = "INSERT INTO " . $tabelaHistorico;
		$query .= " SELECT ($novoSeq),";
		$query .= $voEntidade->getNmTabela () . ".*,";
		$query .= " CURRENT_TIMESTAMP, ";
		$query .= id_user;
		$query .= " FROM " . $voEntidade->getNmTabela ();
		$query .= " WHERE ";
		$query .= $voEntidade->getValoresWhereSQLChave ( false );
		
		// echo $query;
		
		$retorno = $this->cDb->atualizar ( $query );
		return $retorno;
	}
	function validaAlteracao($voEntidade) {
		$query = "SELECT " . voentidade::$nmAtrDhUltAlteracao . " FROM " . $voEntidade->getNmTabela ();
		$query .= " WHERE ";
		$query .= $voEntidade->getValoresWhereSQLChave ( false );
		
		// echo $query;
		$registro = $this->consultarEntidade ( $query, true );
		$dhValidacao = $registro [0] [voentidade::$nmAtrDhUltAlteracao];
		
		if ($dhValidacao != $voEntidade->dhUltAlteracao) {
			$msg = "Registro desatualizado.";
			$msg .= "<br>data banco: " . $dhValidacao;
			$msg .= "<br>data registro: " . $voEntidade->dhUltAlteracao;
			
			throw new Exception ( $msg );
		}
		
		return $registro [0];
	}
	
	// acrescenta os dados dos usuarios guardados na tabela
	function getQueryNmUsuarioTabelaAComparar($vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		// $temUsuInclusao = false;
		$temUsuInclusao = existeItemNoArray ( voentidade::$nmAtrCdUsuarioInclusao, $vo->getTodosAtributos () );
		$temUsuUltAlteracao = existeItemNoArray ( voentidade::$nmAtrCdUsuarioUltAlteracao, $vo->getTodosAtributos () );
		$temUsuHistorico = $vo->temTabHistorico && $isHistorico;
		
		/*
		 * if($temUsuHistorico){
		 * echo "tem usu ";
		 * }else{
		 * echo "NAO tem usu ";
		 * }
		 */
		$query = "";
		if ($temUsuInclusao) {
			$query .= ", " . self::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrName . " AS " . voentidade::$nmAtrNmUsuarioInclusao;
		}
		if ($temUsuUltAlteracao) {
			$query .= ", " . self::$nmTabelaUsuarioUltAlteracao . "." . vousuario::$nmAtrName . " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
		}
		if ($temUsuHistorico) {
			$query .= ", " . self::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . " AS " . voentidade::$nmAtrNmUsuarioOperacao;
		}
		
		$query .= $this->getQueryFrom_NmUsuarioTabelaAComparar ( $vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico );
		
		return $query;
	}
	function getQueryFrom_NmUsuarioTabelaAComparar($vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		// $temUsuInclusao = false;
		$temUsuInclusao = existeItemNoArray ( voentidade::$nmAtrCdUsuarioInclusao, $vo->getTodosAtributos () );
		$temUsuUltAlteracao = existeItemNoArray ( voentidade::$nmAtrCdUsuarioUltAlteracao, $vo->getTodosAtributos () );
		$temUsuHistorico = $vo->temTabHistorico && $isHistorico;
		
		$queryFrom = "";
		$queryFrom .= "\n FROM " . $nmTabela;
		$queryFrom .= $queryJoin;
		
		if ($temUsuInclusao) {
			$queryFrom .= "\n LEFT JOIN " . vousuario::$nmEntidade;
			$queryFrom .= "\n " . self::$nmTabelaUsuarioInclusao . " ON ";
			$queryFrom .= self::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrID . "=" . $nmTabelaACompararCdUsuario . "." . voentidade::$nmAtrCdUsuarioInclusao;
		}
		
		if ($temUsuUltAlteracao) {
			$queryFrom .= "\n LEFT JOIN " . vousuario::$nmEntidade;
			$queryFrom .= "\n " . self::$nmTabelaUsuarioUltAlteracao . " ON ";
			$queryFrom .= self::$nmTabelaUsuarioUltAlteracao . "." . vousuario::$nmAtrID . "=" . $nmTabelaACompararCdUsuario . "." . voentidade::$nmAtrCdUsuarioUltAlteracao;
		}
		
		if ($temUsuHistorico) {
			$queryFrom .= "\n LEFT JOIN " . vousuario::$nmEntidade;
			$queryFrom .= "\n " . self::$nmTabelaUsuarioOperacao . " ON ";
			$queryFrom .= self::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrID . "=" . $nmTabelaACompararCdUsuario . "." . voentidade::$nmAtrCdUsuarioOperacao;
		}
		
		return $queryFrom;
	}
	function consultarPorChave($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$arrayColunasRetornadas = array (
				$nmTabela . ".*" 
		);
		
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, "", $isHistorico );
	}
	function consultarPorChaveMontandoQuery($vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, $isConsultaPorChave = true) {
		$queryWhere = " WHERE ";
		$queryWhere .= $vo->getValoresWhereSQLChave ( $isHistorico );
		return $this->consultarMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, $isConsultaPorChave );
	}
	function consultarMontandoQuery($vo, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, $isConsultaPorChave) {
		$nmTabelaACompararCdUsuario = $vo->getNmTabelaEntidade ( $isHistorico );
		return $this->consultarMontandoQueryUsuario ( $vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, $isConsultaPorChave );
	}
	function consultarMontandoQueryTelaConsulta($vo, $filtro, $arrayColunasRetornadas, $queryJoin) {
		$nmTabelaACompararCdUsuario = $vo->getNmTabelaEntidade ( $filtro->isHistorico );
		$retorno = $this->consultarMontandoQueryUsuarioFiltro ( $vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $filtro, false, $filtro->isValidarConsulta );
		
		/*
		 * removeObjetoSessao($filtro->nmFiltro);
		 * putObjetoSessao($filtro->nmFiltro, $filtro);
		 */
		return $retorno;
	}
	function consultarMontandoQueryUsuario($vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, $isConsultaPorChave) {
		$atributos = getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$query = "SELECT " . $atributos;
		$query .= $this->getQueryNmUsuarioTabelaAComparar ( $vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico );
		
		$query .= $queryWhere;
		
		// echo $query;
		$retorno = $this->consultarEntidade ( $query, $isConsultaPorChave );
		if ($retorno != "" && $isConsultaPorChave) {
			$retorno = $retorno [0];
		}
		return $retorno;
	}
	function consultarMontandoQueryUsuarioFiltro($vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $filtro, $isConsultaPorChave, $validaConsulta) {
		$isHistorico = $filtro->isHistorico;
		$atributos = getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$querySelect = "SELECT " . $atributos;
		
		$queryFrom = $this->getQueryFrom_NmUsuarioTabelaAComparar ( $vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico );
		
		$retorno = $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, $validaConsulta );
		if ($retorno != "" && $isConsultaPorChave) {
			$retorno = $retorno [0];
		}
		
		return $retorno;
	}
	function consultarEntidade($query, $isPorChavePrimaria) {
		return $this->consultarEntidadeComValidacao ( $query, $isPorChavePrimaria, false );
	}
	function consultarEntidadeComValidacao($query, $isPorChavePrimaria, $levantarExcecaoSeConsultaVazia) {
		// echo $query;
		$retorno = $this->cDb->consultar ( $query );
		
		if ($isPorChavePrimaria) {
			$tamanho = sizeof ( $retorno );
			
			if ($retorno == "")
				throw new excecaoChaveRegistroInexistente ();
			
			if ($tamanho > 1)
				throw new excecaoMaisDeUmRegistroRetornado ();
		}
		
		if ($levantarExcecaoSeConsultaVazia && $retorno == "") {
			throw new excecaoConsultaVazia ();
		}
		
		//var_dump($retorno);
		
		//throw new excecaoGenerica("QUALQUER COPISA");
		
		return $retorno;
	}
	function atualizarEntidade($query) {
		// echo $query;
		$retorno = $this->cDb->atualizar ( $query );
		
		return $retorno;
	}
	function getEntidadePorChavePrimariaComValoresDiversosEmColunas($recordset, $colecaoAtr) {
		$retorno = null;
		if (! isColecaoVazia ( $recordset ) && ! isColecaoVazia ( $colecaoAtr )) {
			
			$tamanho = count ( $colecaoAtr );
			$retorno = $recordset [0];
			
			for($i = 0; $i < $tamanho; $i ++) {
				$nmColuna = $colecaoAtr [$i];
				$retorno [$nmColuna] = getColunaEmLinha ( $recordset, $nmColuna, CAMPO_SEPARADOR );
			}
		}
		
		return $retorno;
	}
	function consultarComPaginacao($voentidade, $filtro) {
		$isHistorico = ("S" == $filtro->cdHistorico);
		$nmTabela = $voentidade->getNmTabelaEntidade ( $isHistorico );
		
		$querySelect = "SELECT * ";
		$queryFrom = " FROM " . $nmTabela;
		
		return $this->consultarComPaginacaoQuery ( $voentidade, $filtro, $querySelect, $queryFrom );
	}
	function consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom) {
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, true );
	}
	function consultarTelaConsulta($filtro, $querySelect, $queryFrom) {
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, true );
	}
	function consultarFiltro($filtro, $querySelect, $queryFrom, $validaConsulta) {
		
		$retorno = "";
		$isHistorico = ("S" == $filtro->cdHistorico);
		
		// flag que diz se pode consultar ou nao
		$consultar = @$_GET ["consultar"];		
		
		if ($consultar == "S" || ! $validaConsulta) {
			
			// removeObjetoSessao($filtro->nmFiltro);
			
			$filtroSQL = $filtro->getSQLWhere ( true );
			// echo $filtroSQL. "<br>";
			
			// verifica se tem paginacao
			$limite = "";
			if ($filtro->TemPaginacao) {
				// ECHO "TEM PAGINACAO";
				$pagina = $filtro->paginacao->getPaginaAtual ();
				
				$filtroSQLPaginacao = $filtro->getSQLWhere ( false );
				// echo $filtroSQLPaginacao. "<br>";
				$queryCount = "SELECT count(*) as " . dbprocesso::$nmCampoCount;
				$queryCount .= "\n FROM (SELECT 'X' " . $queryFrom . $filtroSQLPaginacao . ") ALIAS_COUNT";
				
				// guarda o numero total de registros para nao ter que executar a consulta TODOS novamente
				$numTotalRegistros = $filtro->numTotalRegistros = $this->getNumTotalRegistrosQuery ( $queryCount );
				
				$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
				
				// echo $qtdRegistrosPorPag;
				if ($qtdRegistrosPorPag != null && $qtdRegistrosPorPag != constantes::$CD_OPCAO_TODOS) {
					// calcula o número de páginas arredondando o resultado para cima
					$numPaginas = ceil ( $numTotalRegistros / $qtdRegistrosPorPag );
					$filtro->paginacao->setNumTotalPaginas ( $numPaginas );
					
					$inicio = ($qtdRegistrosPorPag * $pagina) - $qtdRegistrosPorPag;
					$limite = " LIMIT $inicio,$qtdRegistrosPorPag";
				}
			}
			
			// aqui eh onde faz realmente a consulta a retornar
			$query = $querySelect . $queryFrom . " $filtroSQL ";
			$query = $query . " $limite";
			
			// echo $filtroSQL;
			// echo "$queryCount<br>";
			// echo "$query<br>";
			
			// removeObjetoSessao($voentidade->getNmTabela());

			$retorno = $this->cDb->consultar ( $query );
		}
		
		// echo $filtro->toString();
		
		return $retorno;
	}
	function getNumTotalRegistrosQuery($query) {
		$queryCount = $query;
		// echo $queryCount;
		$retorno = $this->cDb->consultar ( $queryCount );
		$numTotalRegistros = 0;
		
		if ($retorno != "") {
			$numTotalRegistros = $retorno [0] [dbprocesso::$nmCampoCount];
		}
		return $numTotalRegistros;
	}
	function incluir($voEntidade) {
		// ta na classe filha
		$query = $this->incluirSQL ( $voEntidade );
		// echo "<br>".$query."<br>";
		$retorno = $this->cDb->atualizar ( $query );
		return $voEntidade;
	}
	function incluirQueryVO($voEntidade) {
		$arrayAtribRemover = $voEntidade->varAtributosARemover;
		return $this->incluirQuery ( $voEntidade, $arrayAtribRemover );
	}
	function incluirQuery($voEntidade, $arrayAtribRemover) {
		$atributosInsert = $voEntidade->getTodosAtributos ();
		// var_dump ($atributosInsert);
		// echo "<br>";
		// var_dump($arrayAtribRemover);
		
		$atributosInsert = removeColecaoAtributos ( $atributosInsert, $arrayAtribRemover );
		// var_dump ($atributosInsert);
		// echo "<br>";
		
		$atributosInsert = getColecaoEntreSeparador ( $atributosInsert, "," );
		
		// echo "<br>$atributosInsert";
		
		$query = " INSERT INTO " . $voEntidade->getNmTabela () . " \n";
		$query .= " (";
		$query .= $atributosInsert;
		$query .= ") ";
		$query .= " \nVALUES(";
		// o metodo abaixo eh implementado para cada classe filha
		$query .= $this->getSQLValuesInsert ( $voEntidade );
		$query .= ")";
		
		// echo $query;
		
		return $query;
	}
	function excluir($voEntidade) {
		// echo $voEntidade->sqHist;
		$isHistorico = $voEntidade->isHistorico();
		if ($isHistorico) {
			//echo "EH HISTORICO";
			$retorno = $this->excluirEmDefinitivo ( $voEntidade, true );
		} else {
			//echo "nao EH HISTORICO";
			if ($voEntidade->temTabHistorico)
				$retorno = $this->excluirHistoriando ( $voEntidade );
			else
				$retorno = $this->excluirEmDefinitivo ( $voEntidade, false );
		}
		
		return $retorno;
	}
	function excluirEmDefinitivo($voEntidade, $isHistorico) {
		if ($isHistorico && ! temPermissaoParamHistorico ( true ))
			throw new Exception ( "Usuário não tem permissão para exclusão de histórico." );
		
		$query = $this->excluirSQL ( $voEntidade, $isHistorico );
		// echo $query;
		$retorno = $this->cDb->atualizar ( $query );
		return $retorno;
	}
	function excluirHistoriando($voEntidade) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$this->validaAlteracao ( $voEntidade );
			$this->incluirHistorico ( $voEntidade );
			
			$this->excluirEmDefinitivo ( $voEntidade, false );
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $retorno;
	}
	function excluirSQL($voEntidade, $isHistorico) {
		$nmTabela = $voEntidade->getNmTabelaEntidade ( $isHistorico );
		
		$query = " DELETE FROM " . $nmTabela . " \n";
		$query .= "\n WHERE ";
		// chave primaria
		$query .= $voEntidade->getValoresWhereSQLChave ( $isHistorico );
		
		// echo $query;
		return $query;
	}
	function alterarSQL($voEntidade) {
		$query = " UPDATE " . $voEntidade->getNmTabela () . " \n";
		$query .= " SET ";
		$query .= $this->getSQLValuesUpdate ( $voEntidade );
		$query .= "\n WHERE ";
		// chave primaria
		// $query.= vogestorpessoa::$nmAtrSqContrato . " = " . $voContrato->sq;
		
		$query .= $voEntidade->getValoresWhereSQLChave ( false );
		//echo $query;
		
		return $query;
	}
	function alterar($voEntidade) {
		$temTabHistorico = $voEntidade->temTabHistorico;
		if (! $temTabHistorico)
			$retorno = $this->alterarPorCima ( $voEntidade );
		else
			$retorno = $this->alterarHistoriando ( $voEntidade );
		
		return $retorno;
	}
	function alterarPorCima($voEntidade) {
		$query = $this->alterarSQL ( $voEntidade );
		// echo $query;
		$retorno = $this->cDb->atualizar ( $query );
		return $retorno;
	}
	function alterarHistoriando($voEntidade) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$this->validaAlteracao ( $voEntidade );
			$this->incluirHistorico ( $voEntidade );
			
			// altera o registro sendo este o mais vigente
			$this->alterarPorCima ( $voEntidade );
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $retorno;
	}
	function getProximoSequencial($nmColuna, $voEntidade) {
		$query = " SELECT MAX(" . $nmColuna . ")+1 AS " . $nmColuna . " FROM " . $voEntidade->getNmTabela () . " ";
		// echo $query;
		$registro = $this->consultarEntidade ( $query, false );
		
		$retorno = $registro [0] [$nmColuna];
		
		if ($retorno == null) {
			$retorno = 1;
		}
		
		return $retorno;
	}
	function getProximoSequencialChaveComposta($nmColunaSq, $voEntidade) {
		$arrayAtribRemover = array (
				$nmColunaSq 
		);
		return $this->getProximoSequencialChaveCompostaLogica ( $nmColunaSq, $voEntidade, $arrayAtribRemover );
	}
	function getProximoSequencialChaveCompostaLogica($nmColunaSq, $voEntidade, $arrayAtribRemover) {
		$isHistorico = $voEntidade->sqHist != null;
		
		$arrayColunasChaveSemSq = removeColecaoAtributos ( $voEntidade->getAtributosChavePrimaria (), $arrayAtribRemover );
		$query = " SELECT MAX(" . $nmColunaSq . ")+1 AS " . $nmColunaSq . " FROM " . $voEntidade->getNmTabela () . " ";
		$query .= " WHERE ";
		$query .= $voEntidade->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		
		$query .= "\n GROUP BY " . getSQLStringFormatadaColecaoIN ( $arrayColunasChaveSemSq, false );
		
		// echo $query;
		$registro = $this->consultarEntidade ( $query, false );
		
		if ($registro != "")
			$retorno = $registro [0] [$nmColunaSq];
		else
			$retorno = 1;
			
			// echo $retorno;
		
		return $retorno;
	}
	function existeRegistroVigente($vo) {
		$retorno = true;
		// verifica se existe outro registro vigente (que nao seja historico - obvio) com a mesma chave
		try {
			$this->consultarPorChave ( $vo, false );
		} catch ( excecaoChaveRegistroInexistente $ex ) {
			$retorno = false;
		}
		return $retorno;
	}
	function existeOutroRegistroUnionHistorico($vo) {
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$nmTabelaHistorico = $vo->getNmTabelaEntidade ( true );
		$arrayColunasRetornadas = $vo->getAtributosChavePrimaria ();
		
		$querySelect .= "SELECT ";
		$querySelect .= getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$querySelect .= ", null  AS " . voentidade::$nmAtrSqHist;
		$querySelect .= " FROM " . $nmTabela;
		$querySelect .= " WHERE ";
		$querySelect .= $vo->getValoresWhereSQLChave ( false );
		
		if($vo->temTabHistorico){
			// acrescenta o atributo de historico
			$arrayColunasRetornadas [] = voentidade::$nmAtrSqHist;
			
			$querySelect .= "\n\n UNION \n\n SELECT ";
			$querySelect .= getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
			$querySelect .= " FROM " . $nmTabelaHistorico;
			$querySelect .= " WHERE ";
			$querySelect .= $vo->getValoresWhereSQLChaveSemNomeTabela ( false );
		}
		
		//echo $querySelect;
				
		$colecao = $this->consultarEntidadeComValidacao ( $querySelect, false, true );
		//se for vazia, levanta excecaoConsultaVazia
		//se a qtd de registro for igual a 1, eh o proprio registro consultado
		$retorno = (count($colecao) != 1);
		
		return $retorno;
	}
	// ---------------------------------
	function limpaResultado() {
		$this->cDb->limpaResultado ();
	}
	Function finalizar() {
		$this->cDb->fecharConexao ();
	}
	
	/**
	 * FUNCOES MANIPULACAO
	 * pega na bibliotecaSQL
	 */
	
	function getValorAtributo($param, $nmMetodo) {	
		$retorno = "";
		if($param == constantes::$CD_CAMPO_NULO){
			$retorno = "null";
		}else{
			//pega o metodo na bibliotecaSQL.php
			$retorno = $nmMetodo( $param );
		}
	
		return $retorno;
	}
	
	function getVarComoString($param) {
		//return getVarComoString ( $param );
		return $this->getValorAtributo ( $param , "getVarComoString");
	}
	
	function getVarComoNumero($param) {
		//return getVarComoNumero ( $param );
		return $this->getValorAtributo ( $param , "getVarComoNumero");
	}
	
	function getVarComoData($param) {
		//return getVarComoData ( $param );
		return $this->getValorAtributo ( $param , "getVarComoData");
	}
	
	function getVarComoDecimal($param) {
		//return getDecimalSQL ( $param );
		return $this->getValorAtributo ( $param , "getDecimalSQL");
	}
	
	/*function getVarComoString($param) {
		return getVarComoString ( $param );
	}
	
	function getVarComoNumero($param) {
		return getVarComoNumero ( $param );
	}
	
	function getVarComoData($param) {
		return getVarComoData ( $param );
	}
	
	function getVarComoDecimal($param) {
		return getDecimalSQL ( $param );
	}*/
	
	/**
	 * @deprecated
	 * @param unknown $param
	 * @return string
	 */
	function getDataSQL($param) {
		return getVarComoDataSQL ( $param );
	}
	
	/**
	 * @deprecated
	 * @param unknown $param
	 * @return string|mixed
	 */
	function getDecimalSQL($param) {
		return getDecimalSQL ( $param );
	}
}	