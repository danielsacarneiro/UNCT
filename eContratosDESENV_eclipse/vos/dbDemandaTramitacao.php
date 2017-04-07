<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbDemandaTramitacao extends dbprocesso {
	
	static $NM_FUNCAO_ENCAMINHAR = "encaminhar";
	
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				$nmTabelaDemanda . "." . voDemanda::$nmAtrTipo,
				$nmTabelaDemanda . "." . voDemanda::$nmAtrCdSetor,
				$nmTabelaDemanda . "." . voDemanda::$nmAtrTexto,
				$nmTabelaDemanda . "." . voDemanda::$nmAtrPrioridade,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato,
				$nmTabelaPessoa . "." . vopessoa::$nmAtrDoc,
				$nmTabelaPessoa . "." . vopessoa::$nmAtrNome 
		);
				
		$queryJoin .= "\n INNER JOIN " . $nmTabelaDemanda;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno. "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
		
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
	
	/*
	 * function consultarTelaConsulta($vo, $filtro){
	 * $isHistorico = $filtro->isHistorico;
	 * $nmTabela = $vo->getNmTabelaEntidade($isHistorico);
	 * $nmTabelaUsuario = vousuario::getNmTabela();
	 *
	 * $querySelect = "SELECT ";
	 * $querySelect .= $nmTabela.".*";
	 * $querySelect .= "," . $nmTabelaUsuario . "." . vousuario::$nmAtrName;
	 * $querySelect .= " AS " . voDemanda::$nmAtrNmUsuarioInclusao;
	 * $queryFrom = " FROM ".$nmTabela;
	 * $queryFrom.= "\n INNER JOIN ". $nmTabelaUsuario;
	 * $queryFrom.= "\n ON ";
	 * $queryFrom.= $nmTabelaUsuario. ".".vousuario::$nmAtrID. "=".$nmTabela . "." . voDemanda::$nmAtrCdUsuarioInclusao;
	 *
	 * //echo $query;
	 * return parent::consultarTelaConsulta($filtro, $querySelect, $queryFrom);
	 * }
	 */
	function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico;
		$nmTabelaTramitacao = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaDemanda = voDemanda::getNmTabela ();
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabela ();
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		$colunaUsuHistorico = "";
		
		if ($isHistorico) {
			$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
		}
		$arrayColunasRetornadas = array (
				$nmTabelaTramitacao . ".*",
				static::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioInclusao,
				$colunaUsuHistorico 
		);
		
		// agora pega dos dados da ultima tramitacao, se houver
		$queryJoin .= "\n INNER JOIN ";
		$queryJoin .= $nmTabelaDemanda;
		$queryJoin .= "\n ON " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . " = " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd . " = " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= $nmTabelaDemandaContrato;
		$queryJoin .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
		$queryJoin .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
		
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
		
		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
	}
	function incluirSQL($vo) {
		if ($vo->sq == null || $vo->sq == "") {
			$vo->sq = $this->getProximoSequencialChaveComposta ( voDemandaTramitacao::$nmAtrSq, $vo );
		}
		return $this->incluirQueryVO ( $vo );
	}
	
	// o incluir eh implementado para nao usar da voentidade
	// por ser mais complexo
	function incluir($vo) {
		$this->validarInclusao ( $vo );
		
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			
			$voDemanda = new voDemanda ();
			$voDemanda = $vo->getVOPai ();
			$voDemanda->dbprocesso->cDb = $this->cDb;
			$voDemanda->dbprocesso->incluir ( $voDemanda );
			$vo->cd = $voDemanda->cd;
			
			if ($voDemanda->temContratoParaIncluir ()) {
				$voDemContrato = $voDemanda->getVODemandaContrato ();
				$voDemContrato->dbprocesso->cDb = $this->cDb;
				$voDemContrato->dbprocesso->incluir ( $voDemContrato );
			}
			
			// a transacao ja eh controlada acima
			$this->incluirDemandaTramitacaoSEMControleTransacao ( $vo );
			
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $voDemanda;
	}
	function validarInclusao($vo) {
		/*
		 * echo "tipo da demanda:" . $vo->tipo . "<br>";
		 * echo "tem contrato:" . $vo->temContratoParaIncluir() . "<br>";
		 */
		if ($vo->tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO && ! $vo->temContratoParaIncluir ()) {
			$msg = "Selecione o contrato.";
			throw new Exception ( $msg );
		}
	}
	function validarEncaminhamento($vo) {
		if ($vo->situacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA) {
			$msg = "Encaminhamento n�o permitido para demanda FECHADA.";
			throw new Exception ( $msg );
		}
		
		return true;
	}
	function encaminharDemanda($vo) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$this->incluirDemandaTramitacaoSEMControleTransacao ( $vo );
			
			/*
			 * $voDemanda = new voDemanda();
			 * $voDemanda = $vo->getVOPaiChave();
			 * $registro = $voDemanda->dbprocesso->consultarPorChave($voDemanda, false);
			 * $voDemanda->getDadosBanco($registro);
			 * $voDemanda->dbprocesso->cDb = $this->cDb;
			 * $voDemanda->situacao = dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA;
			 * $voDemanda->dbprocesso->alterar($voDemanda);
			 */
			
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
	}
	function incluirDemandaTramitacaoSEMControleTransacao($vo) {
		// echo "codigo demandatramitacao: " . $vo->cd;
		if ($vo->temTramitacaoParaIncluir ()) {
			if ($vo->sq == null || $vo->sq == "") {
				$vo->sq = $this->getProximoSequencialChaveComposta ( voDemandaTramitacao::$nmAtrSq, $vo );
			}
			
			parent::incluir ( $vo );
			// verifica se tem voDoc pra incluir
			if ($vo->temDocParaIncluir ()) {
				$voDemandaTramDoc = new voDemandaTramDoc ();
				$voDemandaTramDoc = $vo->getVODemandaTramDoc ();
				$voDemandaTramDoc->dbprocesso->cDb = $this->cDb;
				$voDemandaTramDoc->dbprocesso->incluir ( $voDemandaTramDoc );
			}
		}
	}
	//usa a opcao EXCEPCIONAL do voentidade: $NM_METODO_RETORNO_CONFIRMAR
	function alterar($vo) {
		$vo->NM_METODO_RETORNO_CONFIRMAR = voDemandaTramitacao::getNmTabela();
		parent::alterar($vo);		
	}
	//usa a opcao EXCEPCIONAL do voentidade: $NM_METODO_RETORNO_CONFIRMAR
	function excluir($vo) {
		$vo->NM_METODO_RETORNO_CONFIRMAR = voDemandaTramitacao::getNmTabela();
		parent::excluir($vo);		
	}
	function encaminhar($vo) {
		// o alterar eh chamado na pagina generica confirmar.php
		// para chamar o alterarVO, basta chamar o parent::alterar
		// este metodo, por ser chamado da pagina manter.php, apenas incluira uma nova tramitacao
		// ele NAO altera o estado da demanda, apenas inclui uma nova tramitacao
		$isAlteracaoPermitida = $this->validarEncaminhamento ( $vo );
		if ($isAlteracaoPermitida) {
			$this->encaminharDemanda ( $vo );
		}
	
		return $vo;
	}

	function getSQLValuesInsert($vo) {
		$retorno = "";
		$retorno .= $this->getVarComoNumero ( $vo->ano ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cd ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->sq ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cdSetorOrigem ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cdSetorDestino ) . ",";
		$retorno .= $this->getVarComoString ( $vo->textoTram ) . ",";
		$retorno .= $this->getVarComoString ( $vo->prt ) . ",";
		$retorno .= $this->getVarComoData ( $vo->dtReferencia );
		
		$retorno .= $vo->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
}
?>