<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbDemanda extends dbprocesso {
	function consultarPorChave($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato,
				$nmTabelaPessoa . "." . vopessoa::$nmAtrDoc,
				$nmTabelaPessoa . "." . vopessoa::$nmAtrNome 
		);
		
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
		
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
	}
	function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico;
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela ();
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabela ();
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		$colunaUsuHistorico = "";
		
		if ($isHistorico) {
			$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
		}
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				static::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioInclusao,
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
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		/*
		 * $arrayGroupby = array($nmTabela . "." . voDemanda::$nmAtrAno,
		 * $nmTabela . "." . voDemanda::$nmAtrCd
		 * );
		 *
		 * $filtro->groupby = $arrayGroupby;
		 */
		
		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
	}
	function consultarDemandaTramitacao($vo) {
		$isHistorico = $filtro->isHistorico;
		$nmTabela = voDemandaTramitacao::getNmTabelaStatic ( false );
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( $isHistorico );
		$nmTabelaDemandaTramDoc = voDemandaTramDoc::getNmTabelaStatic ( false );
		$nmTabelaDocumento = voDocumento::getNmTabelaStatic ( false );
		
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
		
		$filtro = new filtroManterDemanda ();
		$filtro->vodemanda = $vo;
		$filtro->TemPaginacao = false;
		
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
			$this->excluirDemandaTramitacao ( $vo );
			$this->excluirDemandaContrato ( $vo );
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
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
}
?>