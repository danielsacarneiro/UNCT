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
	
	/**
	 * removido ultimo parametro
	 * */
	// function consultarMontandoQueryUsuarioFiltro($vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $filtro, $isConsultaPorChave, $validaConsulta) {
	function consultarMontandoQueryUsuarioFiltro($vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $filtro, $isConsultaPorChave) {
		$isHistorico = $filtro->isHistorico;
		$atributos = getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$querySelect = "SELECT " . $atributos;
		
		$queryFrom = $this->getQueryFrom_NmUsuarioTabelaAComparar ( $vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico );
		
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
	function consultarFiltroManter($filtro, $validaConsulta) {		
		return $this->consultarFiltro($filtro, $filtro->getQuerySelect(), $filtro->getQueryFromJoin(), $validaConsulta);
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
			
			//para os casos em que o filtro passa conter o sql de consulta internamente
			if($filtro->temQueryPadrao()){
				$queryFrom = $filtro->getQueryFromJoin();
			}				
			
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
		//exclui o principal em caso de nao ter historico ou, ainda que tenha, nao implementa a desativacao
		//a desativacao soh eh necessaria quando ha tabelas de relacionamento que impedem a exclusao direta
		if (! $voEntidade->temTabHistorico () || !$voEntidade->temTabsRelacionamentoQueImpedemExclusaoDireta()) {
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
		// a validacao so eh feita se o voentidade implementa a desativacao (quando tem tabelas relacionadas que nao permitem exclusao direta)
		if ($voEntidade->temTabsRelacionamentoQueImpedemExclusaoDireta() && $this->permiteExclusaoPrincipal ( $voEntidade )) {
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
		//so permite excluir o registro desativado
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
		// echo $query;
		
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
	function getSQLSequencialPorTabela($nmColunaSq, $voEntidade, $isHistorico) {
		$nmTabela = $voEntidade->getNmTabelaEntidade ( $isHistorico );
		
		$arrayAtribRemover = array (
				$nmColunaSq 
		);
		$arrayColunasChaveSemSq = removeColecaoAtributos ( $voEntidade->getAtributosChavePrimaria (), $arrayAtribRemover );
		
		$query = " SELECT MAX($nmColunaSq) AS $nmColunaSq FROM $nmTabela ";
		$query .= " WHERE ";
		$query .= $voEntidade->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
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
	function getProximoSequencialChaveComposta($nmColunaSq, $voEntidade) {
		// nao eh mais necessario consultar tambem na tabela historico
		// a consulta no historico existia para os casos de relacionamento, quando a exclusao do registro principal causava a perda dos relacionamentos
		// isto porque a implementacao do desativado obriga a existencia de um registro na tabela principal
		// se nao houver, eh porque o registro desativado tambem foi excluido, e o seu numero pode ser reutilizado
		
		// porem, nao ha problemas em deixar como esta, apenas por mais seguranca
		$query = " SELECT COALESCE(MAX(" . $nmColunaSq . "),0)+1 AS " . $nmColunaSq . " FROM ";
		$query .= "(";
		$query .= $this->getSQLSequencialPorTabela ( $nmColunaSq, $voEntidade, false );
		if ($voEntidade->temTabHistorico ()) {
			$query .= " UNION ";
			$query .= $this->getSQLSequencialPorTabela ( $nmColunaSq, $voEntidade, true );
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
	function consultarSituacaoHistorico($vo) {
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$nmTabelaHistorico = $vo->getNmTabelaEntidade ( true );
		$arrayColunasRetornadas = $vo->getAtributosChavePrimaria ();
		
		$querySelect .= "SELECT ";
		$querySelect .= getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$querySelect .= ", null  AS " . voentidade::$nmAtrSqHist;
		$querySelect .= " FROM " . $nmTabela;
		$querySelect .= " WHERE ";
		$querySelect .= $vo->getValoresWhereSQLChave ( false );
		
		// acrescenta o atributo de historico pra trazer no union
		$arrayColunasRetornadas [] = voentidade::$nmAtrSqHist;
		
		$querySelect .= "\n\n UNION \n\n SELECT ";
		$querySelect .= getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadas, false );
		$querySelect .= " FROM " . $nmTabelaHistorico;
		$querySelect .= " WHERE ";
		$querySelect .= $vo->getValoresWhereSQLChaveSemNomeTabela ( false );
		
		// echo $querySelect;
		try {
			$colecao = $this->consultarEntidadeComValidacao ( $querySelect, false, true );
		} catch ( excecaoConsultaVazia $ex ) {
			throw new excecaoGenerica ( "Erro ao excluir histórico. Inexiste histórico a ser excluído." );
		}
		// se consulta vazia levanta excecao
		$retorno = count ( $colecao );
		// echo "valor do parametro de permissao de exclusao principal:".$retorno;
		return $retorno;
	}
	function permiteExclusaoPrincipal($vo) {
		$countColecao = $this->consultarSituacaoHistorico ( $vo );
		
		// se a qtd de registro for igual a 2, eh o proprio registro consultado, temos o registro de historico + o registro desativado
		// eh essa situacao que permite a exclusao do principal quando a desativacao eh implementada
		$retorno = ($vo->isHistorico() && $countColecao == 2);
		
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
		return getDecimalSQL ( $param );
	}
}	