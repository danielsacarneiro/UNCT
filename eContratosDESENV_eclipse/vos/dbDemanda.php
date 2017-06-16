<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbDemanda extends dbprocesso {
	function consultarPorChaveTelaColecaoContrato($vo, $isHistorico) {
		try {
			// para o caso de so haver um contrato, o consultarPorChaveTelaJoinContrato traz apenas um registro
			// como realmente deve ser
			$colecao = $this->consultarPorChaveTelaJoinContrato ( $vo, $isHistorico, true );
			$voContrato = new vocontrato ();
			$voContrato->getDadosBanco ( $colecao );
			
			$vo->getDadosBanco ( $colecao );
			$vo->colecaoContrato = array ($voContrato);
		} catch ( excecaoMaisDeUmRegistroRetornado $ex ) {
			// faz consulta a parte
			// nao valida a consulta por chave
			$colecao = $this->consultarPorChaveTelaJoinContrato ( $vo, $isHistorico, false );
			// pega os dados do vo que vao ser iguais para qualquer registro.
			$vo->getDadosBanco ( $colecao [0] );
			$colecaoContrato = $this->consultarDemandaContrato ( $vo );
			$vo->setColecaoContratoRegistroBanco ( $colecaoContrato );
		}
		
		return $vo;
	}
	function consultarPorChaveTelaJoinContrato($vo, $isHistorico, $isConsultaPorChave) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela ();
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato,
				$nmTabelaPessoa . "." . vopessoa::$nmAtrDoc,
				$nmTabelaPessoa . "." . vopessoa::$nmAtrNome,
				"COALESCE (" . " . $nmTabelaTramitacao." . voDemandaTramitacao::$nmAtrCdSetorDestino . "," . $nmTabela . "." . voDemanda::$nmAtrCdSetor . ") AS " . voDemanda::$nmAtrCdSetorAtual 
		);
		
		$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno;
		// o proximo join eh p pegar a ultima tramitacao apenas, se houver
		$queryJoin = "";
		$queryJoin .= "\n LEFT JOIN (";
		$queryJoin .= " SELECT MAX(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq . "," . $atributosGroup . " FROM " . $nmTabelaTramitacao . " GROUP BY " . $atributosGroup;
		$queryJoin .= ") TABELA_MAX";
		$queryJoin .= "\n ON " . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaTramitacao;
		$queryJoin .= "\n ON " . $nmTabela . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabela . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd;
		$queryJoin .= "\n AND " . "TABELA_MAX." . voDemandaTramitacao::$nmAtrSq . " = " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrSq;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoa;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoa . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		$colecao = $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, $isConsultaPorChave );
		return $colecao;
	}
	function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico;
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela ();
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabela ();
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		$colunaUsuHistorico = "";
		
		if ($isHistorico) {
			$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
		}
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				"COUNT(*)  AS " . filtroManterDemanda::$NmColQtdContratos,
				static::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioInclusao,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				
				// $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . " AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
				"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "," . $nmTabela . "." . voDemanda::$nmAtrCdSetor . ") AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
				"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrDhInclusao . "," . $nmTabela . "." . voDemanda::$nmAtrDhUltAlteracao . ") AS " . filtroManterDemanda::$NmColDhUltimaMovimentacao,
				$colunaUsuHistorico 
		);
		
		$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno;
		
		// o proximo join eh p pegar a ultima tramitacao apenas, se houver
		$queryJoin = "";
		$queryJoin .= "\n LEFT JOIN (";
		$queryJoin .= " SELECT MAX(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq . "," . $atributosGroup . " FROM " . $nmTabelaTramitacao . " GROUP BY " . $atributosGroup;
		$queryJoin .= ") TABELA_MAX";
		$queryJoin .= "\n ON " . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrCd;
		
		// agora pega dos dados da ultima tramitacao, se houver
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= $nmTabelaTramitacao;
		$queryJoin .= "\n ON " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrCd;
		$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrSq . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrSq;
		
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= $nmTabelaDemandaContrato;
		$queryJoin .= "\n ON " . $nmTabela . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
		$queryJoin .= "\n AND " . $nmTabela . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		$arrayGroupby = array (
				$nmTabela . "." . voDemanda::$nmAtrAno,
				$nmTabela . "." . voDemanda::$nmAtrCd 
		);
		
		if ($isHistorico) {
			$arrayGroupby [] = voentidade::$nmAtrSqHist;
		}
		
		$filtro->groupby = $arrayGroupby;
		
		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
	}
	function consultarDemandaTramitacao($vo) {
		$nmTabela = voDemandaTramitacao::getNmTabelaStatic ( false );
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabelaDemandaTramDoc = voDemandaTramDoc::getNmTabelaStatic ( false );
		$nmTabelaDocumento = voDocumento::getNmTabelaStatic ( false );
		
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		
		$nmTabelaUsuario = vousuario::getNmTabela ();
		
		$querySelect = "SELECT ";
		$querySelect .= $nmTabela . ".*,";
		$querySelect .= $nmTabelaDocumento . ".*";
		$querySelect .= "," . $nmTabelaUsuario . "." . vousuario::$nmAtrName;
		$querySelect .= "  AS " . voDemanda::$nmAtrNmUsuarioInclusao;
		$queryFrom = " FROM " . $nmTabela;
		
		$queryFrom .= "\n INNER JOIN " . $nmTabelaDemanda;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		
		$queryFrom .= "\n INNER JOIN " . $nmTabelaUsuario;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaUsuario . "." . vousuario::$nmAtrID . "=" . $nmTabela . "." . voDemanda::$nmAtrCdUsuarioInclusao;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaDemandaTramDoc;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrSqDemandaTram . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrSq;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaDocumento;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrAnoDoc . "=" . $nmTabelaDocumento . "." . voDocumento::$nmAtrAno;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrCdSetorDoc . "=" . $nmTabelaDocumento . "." . voDocumento::$nmAtrCdSetor;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrTpDoc . "=" . $nmTabelaDocumento . "." . voDocumento::$nmAtrTp;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrSqDoc . "=" . $nmTabelaDocumento . "." . voDocumento::$nmAtrSq;
		
		$filtro = new filtroManterDemanda ( false );
		// $vo = new voContratoInfo();
		// vo eh vodemanda
		$filtro->vodemanda->cd = $vo->cd;
		$filtro->vodemanda->ano = $vo->ano;
		
		$filtro->TemPaginacao = false;
		$filtro->cdAtrOrdenacao = voDemandaTramitacao::$nmAtrDhInclusao;
		$filtro->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;		
		// echo $vo->texto;
		
		return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	function consultarDemandaContrato($vo) {
		$isHistorico = $vo->isHistorico ();
		// $isHistorico = false;
		
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( $isHistorico );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		$querySelect = "SELECT ";
		$querySelect .= $nmTabelaDemanda . ".*,";
		$querySelect .= $nmTabelaDemandaContrato . ".*";
		$queryFrom = " FROM " . $nmTabelaDemanda;
		
		$queryFrom .= "\n INNER JOIN ";
		$queryFrom .= $nmTabelaDemandaContrato;
		$queryFrom .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
		$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
		/*
		 * $queryJoin .= "\n AND ";
		 * $queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
		 * $queryJoin .= "\n AND ";
		 * $queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
		 */
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		$filtro = new filtroManterDemanda ( false );
		// var_dump($vo);
		$filtro->vodemanda->cd = $vo->cd;
		$filtro->vodemanda->ano = $vo->ano;
		$filtro->vodemanda->sqHist = $vo->sqHist;
		$filtro->TemPaginacao = false;
		$filtro->isHistorico = $isHistorico;
		
		return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	function validarAlteracao($vo) {
		if ($vo->situacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA) {
			// verifica se o setor atual eh igual ao setor de origem
			$filtro = new filtroManterDemanda ( false );
			// $filtro->vodemanda = new voDemanda();
			$filtro->vodemanda = new voDemanda ();
			$filtro->vodemanda->cd = $vo->cd;
			$filtro->vodemanda->ano = $vo->ano;
			
			// echo "SITUACAO FECHADA";
			$colecao = $this->consultarTelaConsulta ( $vo, $filtro );
			if ($colecao != "") {
				$setorAtual = $colecao [0] [voDemandaTramitacao::$nmAtrCdSetorDestino];
				// echo "setor atual:" . $setorAtual;
				if ($setorAtual != null && $vo->cdSetor != $setorAtual) {
					$msg = "A demanda deve estar encaminhada ao setor responsável para fechamento.";
					throw new Exception ( $msg );
				}
			} // else echo "COLECAO VAZIA";
		}
		
		// throw new Exception("REMOVER!!:" . $msg);
		
		return true;
	}
	function alterar($vo) {
		$isAlteracaoPermitida = $this->validarAlteracao ( $vo );
		if ($isAlteracaoPermitida) {
			parent::alterar ( $vo );
		}
	}
	
	// o excluir eh implementado para nao usar da voentidade
	// por ser mais complexo
	function excluir($vo) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$permiteExcluirPrincipal = $this->permiteExclusaoPrincipal ( $vo );
			// so exclui os relacionamentos se a exclusao for de registro historico
			// e nao existir outro registro vigente que possa utilizar os relacionamentos
			// if($vo->validaExclusaoRelacionamentoHistorico()){
			if ($permiteExcluirPrincipal) {
				$this->excluirDemandaTramitacao ( $vo );
				$this->excluirDemandaContrato ( $vo );
			}
			$vo = parent::excluir ( $vo );
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $vo;
	}
	function excluirDemandaTramitacao($voDemanda) {
		// exclui os docs relacionadas a demanda
		$this->excluirDemandaTramDoc ( $voDemanda );
		
		$vo = new voDemandaTramitacao ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voDemandaTramitacao::$nmAtrAno . " = " . $voDemanda->ano;
		$query .= "\n AND " . voDemandaTramitacao::$nmAtrCd . " = " . $voDemanda->cd;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function excluirDemandaTramDoc($voDemanda) {
		$nmTabela = voDemandaTramDoc::getNmTabelaStatic ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voDemandaTramDoc::$nmAtrAnoDemanda . " = " . $voDemanda->ano;
		$query .= "\n AND " . voDemandaTramDoc::$nmAtrCdDemanda . " = " . $voDemanda->cd;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function excluirDemandaContrato($voDemanda) {
		$vo = new voDemandaContrato ();
		
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voDemandaContrato::$nmAtrAnoDemanda . " = " . $voDemanda->ano;
		$query .= "\n AND " . voDemandaContrato::$nmAtrCdDemanda . " = " . $voDemanda->cd;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function incluirSQL($vo) {
		if ($vo->cd == null || $vo->cd == "") {
			$vo->cd = $this->getProximoSequencialChaveComposta ( voDemanda::$nmAtrCd, $vo );
		}
		return $this->incluirQueryVO ( $vo );
	}
	function getSQLValuesInsert($vo) {
		$retorno = "";
		$retorno .= $this->getVarComoNumero ( $vo->ano ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cd ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->tipo ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cdSetor ) . ",";
		// $retorno.= $this-> getVarComoNumero($vo->situacao);
		$retorno .= $this->getVarComoNumero ( dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA ) . ",";
		$retorno .= $this->getVarComoString ( strtoupper ( $vo->texto ) ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->prioridade ) . ",";
		$retorno .= $this->getVarComoData ( $vo->dtReferencia );
		
		$retorno .= $vo->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		$retorno = "";
		$sqlConector = "";
		
		if ($vo->prioridade != null) {
			$retorno .= $sqlConector . voDemanda::$nmAtrPrioridade . " = " . $this->getVarComoNumero ( $vo->prioridade );
			$sqlConector = ",";
		}
		
		if ($vo->situacao != null) {
			$retorno .= $sqlConector . voDemanda::$nmAtrSituacao . " = " . $this->getVarComoNumero ( $vo->situacao );
			$sqlConector = ",";
		}
		
		if ($vo->texto != null) {
			$retorno .= $sqlConector . voDemanda::$nmAtrTexto . " = " . $this->getVarComoString ( strtoupper ( $vo->texto ) );
			$sqlConector = ",";
		}
		
		if ($vo->tipo != null) {
			$retorno .= $sqlConector . voDemanda::$nmAtrTipo . " = " . $this->getVarComoString ( $vo->tipo );
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
}
?>