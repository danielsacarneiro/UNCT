<?php
include_once "config.obj.php";
include_once "db.obj.php";
include_once (caminho_util . "paginacao.php");
include_once (caminho_util . "bibliotecaHTML.php");
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
// include_once (caminho_excecoes . "ExcecaoMaisDeUmRegistroRetornado.php");
class dbprocesso {
	static $FLAG_PRINTAR_SQL = false;
	
	var $cDb;
	var $cConfig;
	static $nmCampoCount = "nmCampoCount";
	static $nmTabelaUsuarioInclusao = "TAB_USU_INCLUSAO";
	static $nmTabelaUsuarioUltAlteracao = "TAB_USU_ULT_ALTERACAO";
	static $nmTabelaUsuarioOperacao = "TAB_USU_OPERACAO";
	
	static $NM_FUNCAO_EXCLUIR_MULTIPLOS = "excluirMultiplos";
	
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
		
		//echo $query;
		//$registro = $this->consultarEntidade ( $query, true );
		$registro = $this->consultarEntidadeComValidacao ( $query, true, true);
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
		//var_dump($vo->getTodosAtributos ());
		$temUsuUltAlteracao = existeItemNoArray ( voentidade::$nmAtrCdUsuarioUltAlteracao, $vo->getTodosAtributos () );
		$temUsuHistorico = $vo->temTabHistorico && $isHistorico;
		
		// var_dump($vo->getTodosAtributos ());
		
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
	
	/**
	 * retorna os joins necessarios para recuperar os dados do usuario
	 * @param unknown $vo
	 * @param unknown $nmTabelaACompararCdUsuario
	 * @param unknown $isHistorico
	 * @return string
	 */
	function getQueryJoinUsuarioTabelaAComparar($vo, $nmTabelaACompararCdUsuario, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		//echo $nmTabela;
		// $temUsuInclusao = false;
		$temUsuInclusao = existeItemNoArray ( voentidade::$nmAtrCdUsuarioInclusao, $vo->getTodosAtributos () );
		$temUsuUltAlteracao = existeItemNoArray ( voentidade::$nmAtrCdUsuarioUltAlteracao, $vo->getTodosAtributos () );
		$temUsuHistorico = $vo->temTabHistorico && $isHistorico;
			
		$retorno = "";
	
		if ($temUsuInclusao) {
			$retorno .= "\n LEFT JOIN " . vousuario::$nmEntidade;
			$retorno .= "\n " . self::$nmTabelaUsuarioInclusao . " ON ";
			$retorno .= self::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrID . "=" . $nmTabelaACompararCdUsuario . "." . voentidade::$nmAtrCdUsuarioInclusao;
				
			//echoo("temusuinclusao");
		}
	
		if ($temUsuUltAlteracao) {
			$retorno .= "\n LEFT JOIN " . vousuario::$nmEntidade;
			$retorno .= "\n " . self::$nmTabelaUsuarioUltAlteracao . " ON ";
			$retorno .= self::$nmTabelaUsuarioUltAlteracao . "." . vousuario::$nmAtrID . "=" . $nmTabelaACompararCdUsuario . "." . voentidade::$nmAtrCdUsuarioUltAlteracao;
				
			//echoo("temusualteracao");
		}
	
		if ($temUsuHistorico) {
			$retorno .= "\n LEFT JOIN " . vousuario::$nmEntidade;
			$retorno .= "\n " . self::$nmTabelaUsuarioOperacao . " ON ";
			$retorno .= self::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrID . "=" . $nmTabelaACompararCdUsuario . "." . voentidade::$nmAtrCdUsuarioOperacao;
				
			//echoo("temusuHistorico");
		}
	
		return $retorno;
	}
	
	/**
	 * monta o select from para o metodo generico
	 * @param unknown $vo
	 * @param unknown $nmTabelaACompararCdUsuario
	 * @param unknown $queryJoin
	 * @param unknown $isHistorico
	 * @return string
	 */
	function getQueryFrom_NmUsuarioTabelaAComparar($vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );		
		$queryFrom = "";
		$queryFrom .= "\n FROM " . $nmTabela;
		$queryFrom .= $queryJoin;
		$queryFrom .= $this->getQueryJoinUsuarioTabelaAComparar($vo, $nmTabelaACompararCdUsuario, $isHistorico);
		
		return $queryFrom;
	}
	function consultarPorChaveVO($vo, $isHistorico=false) {
		$registrobanco = $this->consultarPorChave ( $vo, $isHistorico );
		$vo->getDadosBancoPorChave ( $registrobanco );
		
		return $vo;
	}
	function consultarPorChave($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$arrayColunasRetornadas = array (
				$nmTabela . ".*" 
		);
		
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, "", $isHistorico );
	}
	function consultarPorChaveMontandoQuery($vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, $isConsultaPorChave = true, $nmMetodoAtributosWhere = null) {
		$queryWhere = " WHERE ";
		
		$temp = $vo->getValoresWhereSQLChave ( $isHistorico );
		if($nmMetodoAtributosWhere != null){
			$temp = $vo->$nmMetodoAtributosWhere ( $isHistorico );
		}
		
		$queryWhere .= $temp;
		return $this->consultarMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, $isConsultaPorChave );
	}
	function consultarMontandoQuery($vo, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, $isConsultaPorChave, $sqlComplemento=null) {
		$nmTabelaACompararCdUsuario = $vo->getNmTabelaEntidade ( $isHistorico );
		return $this->consultarMontandoQueryUsuario ( $vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, $isConsultaPorChave, $sqlComplemento);
	}
	function consultarMontandoQueryTelaConsulta($vo, $filtro, $arrayColunasRetornadas, $queryJoin) {
		$nmTabelaACompararCdUsuario = $vo->getNmTabelaEntidade ( $filtro->isHistorico );
		// $retorno = $this->consultarMontandoQueryUsuarioFiltro ( $vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $filtro, false, $filtro->isValidarConsulta );
		$retorno = $this->consultarMontandoQueryUsuarioFiltro ( $vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $filtro, false );
		
		return $retorno;
	}
	function consultarMontandoQueryUsuario($vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, $isConsultaPorChave, $sqlComplemento=null) {
		$atributos = getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$query = "SELECT " . $atributos;
		$query .= $this->getQueryNmUsuarioTabelaAComparar ( $vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico );
		
		$query .= $queryWhere;
		$query .= $sqlComplemento;
		
		/*if(!$isHistorico){
			$conector = "\n AND ";			
			$query .= $conector . $vo->getNmTabela() . "." . voentidade::$nmAtrInDesativado . " = '" . constantes::$CD_NAO . "'";
		}	*/			
		// echo $query;
		$retorno = $this->consultarEntidade ( $query, $isConsultaPorChave );
		if ($retorno != "" && $isConsultaPorChave) {
			$retorno = $retorno [0];
		}
		return $retorno;
	}
		
	function consultarMontandoQueryUsuarioFiltro($vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $filtro, $isConsultaPorChave) {
		$isHistorico = $filtro->isHistorico;
		$atributos = getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$querySelect = "SELECT " . $atributos;
		
		$queryFrom = $this->getQueryFrom_NmUsuarioTabelaAComparar ( $vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico );
		
		//echoo("query join $queryJoin ");
		//echoo ("query from $queryFrom ");
				
		//echo $querySelect;
		//echo " testando $filtro->isValidarConsulta";
		$retorno = $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, $filtro->isValidarConsulta );
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
		$query = str_replace ( constantes::$CD_NOVA_LINHA, "", $query );
		
		//if(static::$FLAG_PRINTAR_SQL){
		if(static::isPrintarSQL()){
			echo "<br>$query<br>";
		}
		
		//var_dump($retorno);
		$retorno = $this->cDb->consultar ( $query );
		//var_dump($retorno);
		
		if ($isPorChavePrimaria) {
			$tamanho = sizeof ( $retorno );
			
			if ($retorno == "")
				throw new excecaoChaveRegistroInexistente (get_class($this) . "|DbProcesso. Consulta Chave Primária");
			
			if ($tamanho > 1)
				throw new excecaoMaisDeUmRegistroRetornado ();
		}
		
		if ($levantarExcecaoSeConsultaVazia && $retorno == "") {
			throw new excecaoConsultaVazia ();
		}
		
		// var_dump($retorno);
		
		// throw new excecaoGenerica("QUALQUER COPISA");
		
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
	
	/**
	 * serve para executar qualquer operacao antes de chamar a consulta na tela
	 * inclusive permite passagem com facilidade de novos parametros
	 * @param unknown $arrayParam
	 * @return string
	 */
	function consultarTelaConsultaParam($arrayParam) {
		$filtro=$arrayParam[0];
		//determina se deve consultar ou nao
		$isConsultar =filtroManter::isConsultarHTML();
		
		$retorno = "";		
		if($isConsultar){
			$retorno = $this->consultarTelaConsulta ($arrayParam);
		}else{
			//ou remover da sessao
			$filtro = null;
		}
		return $retorno;
	}
	
	function consultarFiltroManter($filtro, $validaConsulta) {
		return $this->consultarFiltro ( $filtro, $filtro->getQuerySelect (), $filtro->getQueryFromJoin (), $validaConsulta );
	}
	function incluirFiltroNaSessao($filtro){
		if($filtro->isIncluirFiltroNaSessao){
			//echoo ("incluir $filtro->nmFiltro dbprocesso na sessao");
			$filtro->setNomeFiltroControleSessao();
			putObjetoSessao ( $filtro->nmFiltro, $filtro );
			
			//echoo("DBPROCESSO pos na sessao " .  $filtro->nmFiltro);
		}
		
	}
	
	/**
	 * permite usar o substituir uma chave para incluir o filtro em qualquer lugar da query
	 * @param unknown $filtro
	 * @param unknown $querySelect
	 * @param unknown $queryFrom
	 * @param unknown $validaConsulta
	 * @return string
	 */
	function consultarFiltroPorSubstituicao(&$filtro, $query) {
		$retorno = "";
		$isHistorico = ("S" == $filtro->cdHistorico);
		$isConsultar = $filtro->isConsultarHTML();
		$validaConsulta = $filtro->isValidarConsulta;
		
		//aqui
		$queryfinal = $filtro->getSQLQueryComFiltroSubstituicao($query, $filtro->sqlFiltrosASubstituir);
	
		if ($isConsultar || ! $validaConsulta) {
			// verifica se tem paginacao
			$limite = "";
			if ($filtro->TemPaginacao) {
				// ECHO "TEM PAGINACAO";
				$pagina = $filtro->paginacao->getPaginaAtual ();
	
				// echo $filtroSQLPaginacao. "<br>";
				$queryCount = "SELECT count(*) as " . dbprocesso::$nmCampoCount;
				$queryCount .= "\n FROM ($queryfinal) ALIAS_COUNT";
	
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
			$queryfinal = $queryfinal . " $limite";
	
			if(static::isPrintarSQL()){
				echo "<br> ".$filtro->getNmFiltro()." $queryfinal<br>";
			}
	
			//echo $query;
			$retorno = $this->cDb->consultar ( $queryfinal );
			$this->incluirFiltroNaSessao($filtro);	
			$filtro->setConsultaRealizada();
		}else{
			static::exibeFlagValidacaoConsulta($validaConsulta);
		}
	
		return $retorno;
	}
	
	
	
	static function getQuerySQLTotalizadores(&$filtro, $queryFrom){		
		
		$sqlAtributosTotalizados = "";
		$sqlAtributosInternoTotalizados = "'X'";
		$filtroSQLPaginacao = $filtro->getSQLWhere ( false );
		
		if($filtro->temTotalizadoresFiltro()){
			$sqlTotalizados = $filtro->getStringSQLAtributosTotalizados();
			$sqlAtributosTotalizados = $sqlAtributosInternoTotalizados = $sqlTotalizados;
			
			//script abaixo serve para pegar o nome da tabela interna, se existir, para corrigir o sql
			//$sqlAtributosTotalizados = "," . removeNomeTabelaDoAtributo($sqlAtributosTotalizados);
			
			$sqlAtributosTotalizados = ",".$filtro->getStringSQLAtributosTotalizados($filtro->getAtributosTotalizadoresOperacionados());
							
			//echo "totalizados $sqlAtributosTotalizados";						
			//echo $queryFrom;				
		}		
		
		//echo $filtroSQLPaginacao. "<br>";
		$queryCount = "SELECT count(*) as " . dbprocesso::$nmCampoCount . $sqlAtributosTotalizados;
		$queryCount .= "\n FROM (SELECT $sqlAtributosInternoTotalizados " . $queryFrom . $filtroSQLPaginacao . ") ALIAS_COUNT";
		
		//echo $queryCount;
		
		return $queryCount;		
	}
	
	static function exibeFlagValidacaoConsulta($validaConsulta){
		if(!$validaConsulta){
			$msg = getTextoHTMLDestacado("ATENÇÃO: Flag validação da consulta desativado.");
			//echo $msg;
			throw new excecaoConsultaVazia($msg);
		}
	}
	
	function consultarFiltro(&$filtro, $querySelect, $queryFrom, $validaConsulta) {
		//$filtro = new filtroConsultar();
		$retorno = "";
		$isRetornarApenasQueryCompleta = getAtributoComoBooleano($filtro->isRetornarQueryCompleta);
		/*if($isRetornarApenasQueryCompleta){
			echo "true";
		}else{
			echo "false";
		}*/
		$isHistorico = ("S" == $filtro->cdHistorico);
	
		/*if(!isUsuarioAdmin()){
		 $limiteGenerico = " LIMIT 100 ";
			}*/
	
		// flag que diz se pode consultar ou nao
		$isConsultar = $filtro->isConsultarHTML();
	
		//echoo("valida consulta " . $filtro->nmFiltro);
		if ($isConsultar || ! $validaConsulta || $isRetornarApenasQueryCompleta) {
			//echoo("valida consulta " . $filtro->nmFiltro);
				
			// removeObjetoSessao($filtro->nmFiltro);
				
			$filtroSQL = $filtro->getSQLWhere ( true );
			// echo $filtroSQL. "<br>";
				
			// para os casos em que o filtro possa conter o sql de consulta internamente
			if ($filtro->temQueryPadrao ()) {
				$queryFrom = $filtro->getQueryFromJoin ();
			}
			
			//para o caso de haver filtro a substituir no join
			if($filtro->temSQLFiltrosASubstituir()){
				$queryFrom = $filtro->getSQLQueryComFiltroSubstituicao($queryFrom, $filtro->getSQLFiltrosASubstituir(), false);
				//echoo("teste");
			}
			
			// verifica se tem paginacao
			$limite = "";
			if ($filtro->TemPaginacao && !$isRetornarApenasQueryCompleta) {
				// ECHO "TEM PAGINACAO";
				$pagina = $filtro->paginacao->getPaginaAtual ();
	
				/*$filtroSQLPaginacao = $filtro->getSQLWhere ( false );
				// echo $filtroSQLPaginacao. "<br>";
				$queryCount = "SELECT count(*) as " . dbprocesso::$nmCampoCount;
				$queryCount .= "\n FROM (SELECT 'X' " . $queryFrom . $filtroSQLPaginacao . ") ALIAS_COUNT";*/
				$queryCount = static::getQuerySQLTotalizadores($filtro, $queryFrom);
				//echo $queryCount;
	
				// guarda o numero total de registros para nao ter que executar a consulta TODOS novamente
				$numTotalRegistros = $filtro->numTotalRegistros = $this->getNumTotalRegistrosQuery ( $queryCount, $filtro );
	
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
			
			//echo $queryFrom;
				
			// echo $filtroSQL;
			// echo "$queryCount<br>";
			//if(static::$FLAG_PRINTAR_SQL){
			if(static::isPrintarSQL()){
				echo "<br> ".$filtro->getNmFiltro()." $query<br>";
			}
			
			//para o caso de apenas retornar a query completa
			if($isRetornarApenasQueryCompleta){
				$filtro->setSQL_QUERY_COMPLETA($query);
				return;
			}
				
			//echo $query;
			$retorno = $this->cDb->consultar ( $query );
			$this->incluirFiltroNaSessao($filtro);
				
			/*if($filtro->isIncluirFiltroNaSessao){
				//echoo ("incluir $filtro->nmFiltro dbprocesso na sessao");
				$filtro->setNomeFiltroControleSessao();
				putObjetoSessao ( $filtro->nmFiltro, $filtro );
			}*/
				
			$filtro->setConsultaRealizada();
		}else{
			static::exibeFlagValidacaoConsulta($validaConsulta);
		}	
		// echo $filtro->toString();
	
		return $retorno;
	}
	
	function consultarFiltroSemPaginacao(&$filtro, $querySelect, $incluirWhereFiltro=true) {
		$retorno = "";
		$validaConsulta = $filtro->isValidarConsulta;
		$isHistorico = ("S" == $filtro->cdHistorico);
		// flag que diz se pode consultar ou nao
		$isConsultar = $filtro->isConsultarHTML();
		//echoo("valida consulta " . $filtro->nmFiltro);
		if ($isConsultar || ! $validaConsulta) {			
			$filtroSQL = $filtro->getSQLWhere ( true );
			// echo $filtroSQL. "<br>";			
			
			// aqui eh onde faz realmente a consulta a retornar
			//echoo("FILTRO SQL: $filtroSQL");
			$query = $querySelect . " $filtroSQL ";
			//$query = str_replace(constantes::$CD_CAMPO_SEPARADOR_FILTRO, " $filtroSQL ", $querySelect);
			
			if(static::isPrintarSQL()){
				echo "<br> ".$filtro->getNmFiltro()." $query<br>";
			}
			
			//echo $query;			
			$retorno = $this->cDb->consultar ( $query );	
			$this->incluirFiltroNaSessao($filtro);
			/*if($filtro->isIncluirFiltroNaSessao){
				 //echoo ("incluir $filtro->nmFiltro dbprocesso na sessao");
				$filtro->setNomeFiltroControleSessao();
				 putObjetoSessao ( $filtro->nmFiltro, $filtro );
			 }*/
			$filtro->setConsultaRealizada();			
		}else{
				static::exibeFlagValidacaoConsulta($validaConsulta);
		}
				
		return $retorno;
	}
	
	
	function getNumTotalRegistrosQuery($query, &$filtro = null) {
		$temTotalizadorValor=false;
		if($filtro != null){
			//$filtro = new filtroManter();
			$temTotalizadorValor = $filtro->temTotalizadoresFiltro();			
		}
		
		$queryCount = $query;
		//echo $queryCount;
		$retorno = $this->cDb->consultar ( $queryCount );
		$numTotalRegistros = 0;
		
		if ($retorno != "") {
			$registro = $retorno [0];
			$numTotalRegistros =  $registro[dbprocesso::$nmCampoCount];
			if($temTotalizadorValor){
				//echo $queryCount;
				//var_dump($registro);
				$filtro->setColecaoTotalizadores($registro);
			}
		}
		return $numTotalRegistros;
	}
	
	// metodo mais simples de inclusao
	// havendo complexidade, cada class implementa o seu
	function incluirSQL($vo) {
		return $this->incluirQueryVO ( $vo );
	}
	function incluir($voEntidade) {
		// ta na classe filha
		$query = $this->incluirSQL ( $voEntidade );
		// echo "<br>".$query."<br>";
		$retorno = $this->cDb->atualizar ( $query );
		return $voEntidade;
	}
	function incluirQueryVO($voEntidade) {
		//$arrayAtribRemover = $voEntidade->varAtributosARemover;
		$arrayAtribRemover = $voEntidade->varAtributosDBDefault;
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
		
		$atributosInsert = getColecaoEntreSeparador ( $atributosInsert, ", " );
		
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
	function validaExclusaoHistorico($voEntidade) {
		// se a operacao com historico foi selecionada da tela
		// quando o vo for incluido na sessao para executar a operacao desejada pelo usuario
		// teremos a identificacao se trata-se de uma operacao com historico ou nao
		// e ela precisa ser garantida aqui
		// $voSessao = new voentidade();
		$voSessao = getObjetoSessao ( $voEntidade->getNmTabela (), true );
		$isOperacaoHistoricoSelecionadaPeloUsuario = $voSessao->isHistorico ();
		$isOperacaoHistoricoARealizar = $voEntidade->isHistorico ();
		
		if ($isOperacaoHistoricoSelecionadaPeloUsuario && ! $isOperacaoHistoricoARealizar) {
			throw new excecaoGenerica ( "Operação com registro de histórico não pode ser realizada por ausência do SQ Histórico." );
		}
	}
	function excluir($voEntidade) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			// echo $voEntidade->sqHist;
			$this->validaExclusaoHistorico ( $voEntidade );
			
			$isExcluirHistorico = $voEntidade->isHistorico ();
			if ($isExcluirHistorico) {
				// echo "EH HISTORICO";
				// verifica se deve excluir historico direto ou com o desativado
				$retorno = $this->excluirHistoricoEDesativado ( $voEntidade );
			} else {
				// echo "nao EH HISTORICO";
				if ($voEntidade->temTabHistorico) {
					$retorno = $this->excluirHistoriando ( $voEntidade );
				} else {
					// exclui principal direto
					$retorno = $this->excluirPrincipal ( $voEntidade );
				}
			}
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $retorno;
	}
	function desativarOuExcluirPrincipal($voEntidade) {
		// exclui o principal em caso de nao ter historico
		// a desativacao soh eh necessaria quando ha tabelas de relacionamento que impedem a exclusao direta
		if (! $voEntidade->temTabHistorico ()) {
			// exclusao simples
			$query = $this->excluirSQL ( $voEntidade, $isHistorico );
		} else {
			$query = $this->excluirDesativandoSQL ( $voEntidade );
		}
		// echo $query;
		$retorno = $this->cDb->atualizar ( $query );
		return $retorno;
	}
	function excluirHistoricoEDesativado($voEntidade) {
		if (! temPermissaoParamHistorico ( true )) {
			throw new Exception ( "Usuário não tem permissão para exclusão de histórico." );
		}
		
		// sempre exclui o historico em questao
		$query = $this->excluirSQLRegistroHistorico ( $voEntidade );
		
		// se implementar historico, verifica se exclui o principal tambem
		// o principal pode nao ser excluido se existirem outros historicos
		
		// echo "entrou temhistorico";
		// se eh o registro de historico, deve verificar se pode excluir o registro desativado na tabela principal
		// so pode excluir o registro principal se eh o ultimo historico e nao ha outro registro ativo
		// (quando tem tabelas relacionadas que nao permitem exclusao direta)
		if ($this->permiteExclusaoPrincipal ( $voEntidade )) {
			// echo "entrou aqui permiteExclusaoPrincipal";
			// exclui o registro desativado na tabela principal
			$query = $this->excluirHistoricoEPrincipalSQL ( $voEntidade );
		}
		
		// echo $query;
		$retorno = $this->cDb->atualizar ( $query );
		return $retorno;
	}
	function excluirHistoriando($voEntidade) {
		$this->validaAlteracao ( $voEntidade );
		$this->incluirHistorico ( $voEntidade );
		$this->desativarOuExcluirPrincipal ( $voEntidade );
	}
	function excluirDesativandoSQL($voEntidade) {
		$nmTabela = $voEntidade->getNmTabelaEntidade ( false );
		
		$query = " UPDATE " . $nmTabela . " \n";
		$query .= " SET " . voentidade::$nmAtrInDesativado . " = 'S' ";
		$query .= ", " . voentidade::$nmAtrDhUltAlteracao . " = now() ";
		$query .= "\n WHERE ";
		// chave primaria
		$query .= $voEntidade->getValoresWhereSQLChave ( $isHistorico );
		
		// echo $query;
		return $query;
	}
	function excluirSQLRegistroHistorico($voEntidade) {
		return $this->excluirSQL ( $voEntidade, true );
	}
	function excluirSQLRegistroPrincipal($voEntidade) {
		return $this->excluirSQL ( $voEntidade, false );
	}
	function excluirPrincipal($voEntidade) {
		$query = $this->excluirSQLRegistroPrincipal ( $voEntidade );
		$retorno = $this->cDb->atualizar ( $query );
		return $retorno;
	}
	function excluirHistorico($voEntidade) {
		$query = $this->excluirSQLRegistroHistorico ( $voEntidade );
		$retorno = $this->cDb->atualizar ( $query );
		return $retorno;
	}
	function excluirSQL($voEntidade, $isHistorico) {
		$nmTabela = $voEntidade->getNmTabelaEntidade ( $isHistorico );

		$query = " SELECT * FROM " . $nmTabela . " \n";
		$query .= "\n WHERE ";
		// chave primaria
		$query .= $voEntidade->getValoresWhereSQLChave ( $isHistorico );
		
		$retorno = $this->cDb->consultar( $query );
		if(isColecaoVazia($retorno)){
			throw new excecaoChaveRegistroInexistente("Registro inexistente para exclusão.");
		}
		if(sizeof($retorno) > 1){
			throw new excecaoMaisDeUmRegistroRetornado("Mais de um registro a ser excluído. Contate o Suporte.");
		}
		
		$query = " DELETE FROM " . $nmTabela . " \n";
		$query .= "\n WHERE ";
		// chave primaria
		$query .= $voEntidade->getValoresWhereSQLChave ( $isHistorico );
		
		// echo $query . "<br>";
		return $query;
	}
	function excluirHistoricoEPrincipalSQL($voEntidade) {
		$nmTabela = $voEntidade->getNmTabelaEntidade ( false );
		$nmTabelaHist = $voEntidade->getNmTabelaEntidade ( true );
		
		$query = " DELETE FROM ";
		$query .= "$nmTabela,$nmTabelaHist";
		$query .= " USING ";
		$query .= " $nmTabela,$nmTabelaHist ";
		$query .= " WHERE ";
		$query .= $voEntidade->getValoresWhereSQLChave ( false );
		// so permite excluir o registro desativado
		$query .= " AND $nmTabela." . voentidade::$nmAtrInDesativado . " = '" . constantes::$CD_SIM . "'";
		$query .= " AND ";
		$query .= $voEntidade->getValoresWhereSQLChave ( true );
		
		// DELETE FROM `teste`, `teste2` USING `teste`, `teste2` WHERE `teste`.`id` = 10 AND `teste2`.`id` = 10
		
		// echo $query . "<br>";
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
		//so valida a alteracao (para evitar alteracoes simultaneas) se o voentidade permitir alteracao (tiverdhultimaalteracao)
		if($voEntidade->varAtributosARemover != null && !in_array(voentidade::$nmAtrDhUltAlteracao, $voEntidade->varAtributosARemover)){
			$this->validaAlteracao ( $voEntidade );
		}
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
			//echo "HISTORIOU";
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $retorno;
	}
	/**
	 * $pArrayColunasAlternativas traz as colunas desejadas chaveadas com seus valores no formato array (coluna => valor)
	 * somente quando desejado um sq que difere da chave primaria
	 * @param unknown $nmColunaSq
	 * @param unknown $voEntidade
	 * @param unknown $isHistorico
	 * @param unknown $pArrayColunasAlternativas
	 * @return string
	 */
	function getSQLSequencialPorTabela($nmColunaSq, $voEntidade, $isHistorico, $pArrayColunasAlternativas = null) {
		$nmTabela = $voEntidade->getNmTabelaEntidade ( $isHistorico );
		
		//$voEntidade = new voentidade();
		if(!$isHistorico && $voEntidade->temTabHistorico){
			$atributoDesativado = " AND " . voentidade::$nmAtrInDesativado . "= 'N'";
		}
		
		if($pArrayColunasAlternativas == null){
			//vai pelos valores normais do voentidade
			$arrayAtribRemover = array (
					$nmColunaSq 
			);
			$arrayColunasChaveSemSq = removeColecaoAtributos ( $voEntidade->getAtributosChavePrimaria (), $arrayAtribRemover );
			$valoresTemp = $voEntidade->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		}else{
			//vai pelas colunas alternativas, considerando apenas as colunas passadas como parametro
			$arrayColunasChaveSemSq = array_keys($pArrayColunasAlternativas);
			$conector = "AND ";
			foreach ($pArrayColunasAlternativas as $coluna => $valor){
				$valoresTemp .= "$coluna = $valor $conector"; 
			}
			$valoresTemp = removerUltimaString($conector, $valoresTemp);			 
		}
		
		$query = " SELECT MAX($nmColunaSq) AS $nmColunaSq FROM $nmTabela ";
		$query .= " WHERE ";
		$query .= $valoresTemp . $atributoDesativado;
		$query .= "\n GROUP BY " . getSQLStringFormatadaColecaoIN ( $arrayColunasChaveSemSq, false );
		
		return $query;
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
	
	/**
	 * $pArrayColunasAlternativas serve para trazer o prox sq de qualquer combinacao de colunas diferentes da chave primaria
	 * @param unknown $nmColunaSq
	 * @param unknown $voEntidade
	 * @param unknown $pArrayColunasAlternativas
	 * @return number|string
	 */
	function getProximoSequencialChaveComposta($nmColunaSq, $voEntidade, $pArrayColunasAlternativas=null) {
		// nao eh mais necessario consultar tambem na tabela historico
		// a consulta no historico existia para os casos de relacionamento, quando a exclusao do registro principal causava a perda dos relacionamentos
		// isto porque a implementacao do desativado obriga a existencia de um registro na tabela principal
		// se nao houver, eh porque o registro desativado tambem foi excluido, e o seu numero pode ser reutilizado
		
		// porem, nao ha problemas em deixar como esta, apenas por mais seguranca
		$query = " SELECT COALESCE(MAX(" . $nmColunaSq . "),0)+1 AS " . $nmColunaSq . " FROM ";
		$query .= "(";
		$query .= $this->getSQLSequencialPorTabela ( $nmColunaSq, $voEntidade, false, $pArrayColunasAlternativas);
		if ($voEntidade->temTabHistorico ()) {
			$query .= " UNION ";
			$query .= $this->getSQLSequencialPorTabela ( $nmColunaSq, $voEntidade, true, $pArrayColunasAlternativas );
		}
		$query .= ") TAB_SQ";
		
		// echo $query;
		$registro = $this->consultarEntidade ( $query, false );
		
		if ($registro != "")
			$retorno = $registro [0] [$nmColunaSq];
		else
			$retorno = 1;
			
			// echo $retorno;
		
		return $retorno;
	}
	
	/**
	 * retorna o maior SQ dentro de um universo de VOs semelhantes com os dados preenchidos no VO
	 * @param unknown $arrayConfiguracaoVOs
	 * @return number|string
	 * NUNCA TESTADA
	 */
	function getProximoSequencialMultiplosVOs($arrayConfiguracaoVOs) {
		$maiorSq = 1;
		foreach ($arrayConfiguracaoVOs as $arrayVO){
			$nmColunaSq = $arrayVO[0]; 
			$voEntidade = $arrayVO[1]; 
			$pArrayColunasAlternativas = $arrayVO[2];
			
			$sqTemp = $this->getProximoSequencialChaveComposta($nmColunaSq, $voEntidade, $pArrayColunasAlternativas);
			if($sqTemp > $maiorSq){
				$maiorSq = $sqTemp;
			}
		}
		
		return $maiorSq;
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
	/**
	 * retorna a situacao dos registros historicos
	 * traz a quantidade de registro historico existente pra uma chave
	 * se o vo implementar a desativacao, trara sempre a quantidade de historicos + 1 (+ o registro desativado na tabela principal)
	 * se nao implementar, traz apenas a quantidade de historicos pra certa chave
	 */
	function permiteExclusaoPrincipal($vo) {
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$nmTabelaHistorico = $vo->getNmTabelaEntidade ( true );
		$arrayColunasRetornadas = $vo->getAtributosChavePrimaria ();
		
		$querySelect .= "SELECT ";
		$querySelect .= getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$querySelect .= ", null  AS " . voentidade::$nmAtrSqHist;
		$querySelect .= ", 1  AS " . voentidade::$nmAtrTemDesativado;
		$querySelect .= " FROM " . $nmTabela;
		$querySelect .= " WHERE ";
		$querySelect .= $vo->getValoresWhereSQLChave ( false );
		$querySelect .= " AND $nmTabela." . voentidade::$nmAtrInDesativado . " = '" . constantes::$CD_SIM . "'";
		
		// acrescenta o atributo de historico pra trazer no union
		$arrayColunasRetornadas [] = voentidade::$nmAtrSqHist;
		
		$querySelect .= "\n\n UNION \n\n SELECT ";
		$querySelect .= getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$querySelect .= ", 0  AS " . voentidade::$nmAtrTemDesativado;
		$querySelect .= " FROM " . $nmTabelaHistorico;
		$querySelect .= " WHERE ";
		$querySelect .= $vo->getValoresWhereSQLChaveSemNomeTabela ( false );
		
		// echo "<br>" . $querySelect;
		$temDesativado = false;
		try {
			$colecao = $this->consultarEntidadeComValidacao ( $querySelect, false, true );
			
			foreach ( $colecao as $registro ) {
				if ($registro [voentidade::$nmAtrTemDesativado] == 1) {
					$temDesativado = true;
					break;
				}
			}
			$countColecao = count ( $colecao );
		} catch ( excecaoConsultaVazia $ex ) {
			// throw new excecaoGenerica ( "Erro ao excluir histórico. Inexiste histórico a ser excluído." );
			$countColecao = 0;
		}
		// se consulta vazia levanta excecao
		
		// se a qtd de registro for igual a 2, eh o proprio registro consultado, temos o registro de historico + o registro desativado
		// eh essa situacao que permite a exclusao do principal quando a desativacao eh implementada
		$retorno = ($vo->isHistorico () && $countColecao == 2 && $temDesativado);
		
		// echo "<br> valor retorno booleano:". $retorno;
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
		if ($param == constantes::$CD_CAMPO_NULO) {
			$retorno = "null";
		} else {
			// pega o metodo na bibliotecaSQL.php
			$retorno = $nmMetodo ( $param );
		}
		
		return $retorno;
	}
	function getVarComoString($param) {
		// return getVarComoString ( $param );
		return $this->getValorAtributo ( $param, "getVarComoString" );
	}
	function getVarComoNumero($param) {
		// return getVarComoNumero ( $param );
		return $this->getValorAtributo ( $param, "getVarComoNumero" );
	}
	function getVarComoData($param) {
		// return getVarComoData ( $param );
		return $this->getValorAtributo ( $param, "getVarComoData" );
	}
	function getVarComoDecimal($param) {
		// return getDecimalSQL ( $param );
		return $this->getValorAtributo ( $param, "getDecimalSQL" );
	}
	
	/*
	 * function getVarComoString($param) {
	 * return getVarComoString ( $param );
	 * }
	 *
	 * function getVarComoNumero($param) {
	 * return getVarComoNumero ( $param );
	 * }
	 *
	 * function getVarComoData($param) {
	 * return getVarComoData ( $param );
	 * }
	 *
	 * function getVarComoDecimal($param) {
	 * return getDecimalSQL ( $param );
	 * }
	 */
	
	/**
	 *
	 * @deprecated
	 *
	 * @param unknown $param        	
	 * @return string
	 */
	function getDataSQL($param) {
		return getVarComoDataSQL ( $param );
	}
	
	/**
	 *
	 * @deprecated
	 *
	 * @param unknown $param        	
	 * @return string|mixed
	 */
	function getDecimalSQL($param) {
		return $this->getVarComoDecimal ( $param );
	}
	
	static function isPrintarSQL(){
		$printarFilho = static::$FLAG_PRINTAR_SQL;
		$printarPAI = self::$FLAG_PRINTAR_SQL;
		
		$retorno = isUsuarioAdmin() && ($printarPAI || $printarFilho);
		return $retorno;
	}
	
	static function printarSQL($query, $nmFiltro=null){
		if(static::isPrintarSQL()){
			echo "<br> $nmFiltro $query<br>";
		}		
	}
	
	/**
	 * a entrada deve ter o formato $valor_atributo_entidade => descricao do atributo
	 * @param unknown $array
	 * @throws excecaoAtributoObrigatorio
	 * @throws excecaoGenerica
	 */
	static function validarDadosEntidadeArray($array) {
		//var_dump($array);
			foreach ($array as $descricao => $valor){
				if(!isAtributoValido($valor) || (isMoeda($valor) && $valor=="0,00")){
					$arrayRetorno[]=$descricao;
					//echoo($descricao);
				}
			}
			
			if(!isColecaoVazia($arrayRetorno)){
				$msg = getArrayComoStringCampoSeparador($arrayRetorno, ";<br>");
				throw new excecaoAtributoObrigatorio("Verifique o(s) seguinte(s) campo(s) do termo relacionado: <br>'$msg' .");
			}					
	}
	
}