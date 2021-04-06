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
		$nmTabelaDoc = voDocumento::getNmTabelaStatic ( false );
		$nmTabelaDemandaDoc = voDemandaTramDoc::getNmTabelaStatic ( false );
	
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
				$nmTabelaPessoa . "." . vopessoa::$nmAtrNome,
				$nmTabelaDoc . "." . voDocumento::$nmAtrAno,
				$nmTabelaDoc . "." . voDocumento::$nmAtrCdSetor,
				$nmTabelaDoc . "." . voDocumento::$nmAtrTp,
				$nmTabelaDoc . "." . voDocumento::$nmAtrSq
		);
	
		$queryJoin .= "\n INNER JOIN " . $nmTabelaDemanda;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
	
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
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaDoc;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDoc;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrAnoDoc . "=" . $nmTabelaDoc . "." . voDocumento::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrCdSetorDoc . "=" . $nmTabelaDoc . "." . voDocumento::$nmAtrCdSetor;
		$queryJoin .= "\n AND " . $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrTpDoc . "=" . $nmTabelaDoc . "." . voDocumento::$nmAtrTp;
		$queryJoin .= "\n AND " . $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrSqDoc . "=" . $nmTabelaDoc . "." . voDocumento::$nmAtrSq;
	
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
	}
	
	function consultarPorChaveTramitacao($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaDoc = voDocumento::getNmTabelaStatic ( false );
		$nmTabelaDemandaDoc = voDemandaTramDoc::getNmTabelaStatic ( false );
		
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
				$nmTabelaPessoa . "." . vopessoa::$nmAtrNome,
				$nmTabelaDoc . "." . voDocumento::$nmAtrAno,
				$nmTabelaDoc . "." . voDocumento::$nmAtrCdSetor,
				$nmTabelaDoc . "." . voDocumento::$nmAtrTp,
				$nmTabelaDoc . "." . voDocumento::$nmAtrSq				
		);
		
		$queryJoin .= "\n INNER JOIN " . $nmTabelaDemanda;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		
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
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaDoc;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		$queryJoin .= "\n AND " . $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrSqDemandaTram . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrSq;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDoc;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrAnoDoc . "=" . $nmTabelaDoc . "." . voDocumento::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrCdSetorDoc . "=" . $nmTabelaDoc . "." . voDocumento::$nmAtrCdSetor;
		$queryJoin .= "\n AND " . $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrTpDoc . "=" . $nmTabelaDoc . "." . voDocumento::$nmAtrTp;
		$queryJoin .= "\n AND " . $nmTabelaDemandaDoc . "." . voDemandaTramDoc::$nmAtrSqDoc . "=" . $nmTabelaDoc . "." . voDocumento::$nmAtrSq;
		
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
	}
	
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
				"$nmTabelaDemanda." . voDemanda::$nmAtrTipo,
				"$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrAnoContrato,
				"$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrCdContrato,
				"$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrTipoContrato,
				"$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrCdEspecieContrato,
				"$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrSqEspecieContrato,
				"$nmTabelaPessoaContrato." . vopessoa::$nmAtrNome,
				
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
			
			$dbUsuarioInfo = new dbUsuarioInfo();
			$cdSetor = $vo->cdSetor;			
			if (!$dbUsuarioInfo->isUsuarioPertenceAoSetor($cdSetor)) {
				$msg = "Usuário não autorizado pelo Setor ". dominioSetor::getDescricaoStaticTeste($cdSetor)." para incluir demanda.";
				throw new Exception ( $msg );
			}				
			
			//apenas usuario avancado pode determinar prioridade alta
			if (!isUsuarioAdmin()) {
				//$vo = new voDemandaTramitacao();
				if ($vo->tipo != dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL && $vo->prioridade == dominioPrioridadeDemanda::$CD_PRIORI_ALTA) {
					$msg = "Usuário não autorizado para incluir demandas com prioridade ALTA.";
					throw new Exception ( $msg );
				}
			}
						
			$voDemanda->dbprocesso->incluir ( $voDemanda );			
			$vo->cd = $voDemanda->cd;
			
			if ($voDemanda->temContratoParaIncluir ()) {
				$voDemanda->dbprocesso->incluirColecaoDemandaContrato($voDemanda);				
			}

			//$voDemanda = new voDemandaTramitacao();
			if ($voDemanda->temProcLicitatorioParaIncluir()) {
				$voProcLic = $voDemanda->voProcLicitatorio;
				$voDemandaProcLic = $voDemanda->getVODemandaProcLicitatorio($voProcLic);
				$voDemanda->dbprocesso->incluirDemandaProcLicitatorio($voDemandaProcLic);
			}							

			//$voDemanda = new voDemandaTramitacao();
			if ($voDemanda->temSolicCompraParaIncluir()) {				
				$voSolicCompra = $voDemanda->voSolicCompra;				
				$voDemandaSolicCompra = $voDemanda->getVODemandaSolicCompra($voSolicCompra);
				$voDemanda->dbprocesso->incluirDemandaSolicCompra($voDemandaSolicCompra);				
			}
				
			// a transacao ja eh controlada acima
			$this->incluirDemandaTramitacaoSEMControleTransacao ( $vo );
			
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( get_class($this)." | ". $e->getMessage () );
		}
		
		return $voDemanda;
	}
	function validarInclusao($vo) {
		//para o caso do encaminhar.novo.php
		dbDemanda::validarGenerico($vo);
	}
	function validarEncaminhamento($vo) {
		//validacao de demanda FECHADA eh feita no javascript
		/*if (isSituacaoDemandaFechada($vo->situacao)) {
			$msg = "Encaminhamento não permitido para demanda ". dominioSituacaoDemanda::getDescricao($vo->situacao). ".";
			$msg .= "Demanda: " . $vo->getMensagemComplementarTelaSucesso();
			throw new Exception ( $msg );
		}*/
				
		$dbUsuarioInfo = new dbUsuarioInfo();
		$cdSetor = $vo->cdSetorOrigem;
		$cdSetorDestino = $vo->cdSetorDestino;
		//var_dump("Setor atual:" . $cdSetor);
				
		//o usuario NAO pode retirar a demanda do setor ao qual nao pertence, mas pode tramitar deixando no mesmo setor
		if (!$dbUsuarioInfo->isUsuarioPertenceAoSetor($cdSetor) && $cdSetor != $cdSetorDestino) {
			$msg = "Usuário não autorizado pelo Setor ". dominioSetor::getDescricaoStaticTeste($cdSetor)." para encaminhamento. ";
			$msg .= "Demanda: " . $vo->getMensagemComplementarTelaSucesso();
								
			throw new Exception ( $msg );
		}		
				
		return true;
	}
	
	/**
	 * verifica se os atributos cuja alteracao da DEMANDA eh permitida na tela de encaminhamento foram alterados
	 * se existir alteracao a fazer, ja faz no vo
	 * 
	 * RETORNA NULO se nao houver alteracao a fazer
	 * @param unknown $vo
	 * @return boolean
	 */
	function getVODemandaPaiAAlterarEncaminhamento($vo){
		$voDemanda = new voDemanda ();
		$voDemanda = $vo->getVOPai ();		
		$voDemanda = $voDemanda->dbprocesso->consultarPorChaveVO($voDemanda, false);
		
		//validacao anterior a presente alteracao: deixei como esta, alterar no futuro
		$isFaseAlterada = $this->isFaseTelaAlterada($vo);		
		if($isFaseAlterada){
			$voDemanda->fase = $vo->fase;			
		}
		
		$inMonitorarTela = $vo->inMonitorar;
		//$inMonitorarBanco = getAtributoTelaACompararBanco(voDemanda::$nmAtrInMonitorar);
		$inMonitorarBanco = $voDemanda->inMonitorar;
		$isInMonitorarAlterado = $inMonitorarTela != $inMonitorarBanco;
		if($isInMonitorarAlterado){
			$voDemanda->inMonitorar = $vo->inMonitorar;
		}
				
		$cdPessoaRespATJATela = $vo->cdPessoaRespATJA;
		$cdPessoaRespATJABanco = $voDemanda->cdPessoaRespATJA;
		$iscdPessoaRespATJAAlterado = $cdPessoaRespATJATela != $cdPessoaRespATJABanco;
		if($iscdPessoaRespATJAAlterado){
			$voDemanda->cdPessoaRespATJA = $vo->cdPessoaRespATJA;
		}
		
		//$voDemanda = new voDemanda();
		//alternativa para o trecho acima, implementado depois
		$isCdPessoaRespUNCTAlterado = $voDemanda->setAtributoTelaSeAlterado($vo, "cdPessoaRespUNCT");
		
		$isSituacaoAlterada = false;
		//com o encaminhamento, a situacao fica, no minimo, EM ANDAMENTO
		if(!isAtributoValido($vo->situacao) || $vo->situacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA){
			$vo->situacao = dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO;
		}		
		$isSituacaoAlterada = $voDemanda->setAtributoTelaSeAlterado($vo, "situacao");
				
		/*$isSituacaoAlterada = $voDemanda->situacao != dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO;
		if($isSituacaoAlterada){
			$voDemanda->situacao = dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO;
		}*/		
		
		//so retorna VO nao nulo se as condicoes abaixo forem satisfeitas, permitindo a alteracao do VODEMANDAPAI
		if($isSituacaoAlterada 
				|| $isFaseAlterada
				|| $isInMonitorarAlterado
				|| $iscdPessoaRespATJAAlterado
				|| $isCdPessoaRespUNCTAlterado){
			$retorno = $voDemanda;
		}
			
		return $retorno;
	}
	
	/**
	 * verifica se a fase da demanda foi alterada na tela
	 * usada para saber se eh necessario alterar a demanda na tela de encaminhamento
	 * @param unknown $vo
	 * @throws excecaoGenerica
	 * @return boolean
	 */
	function isFaseTelaAlterada($vo){
		$faseTela = $vo->fase;
		$faseBanco = $_POST[voDemandaTramitacao::$nmAtrFaseRegistroBanco];
		if(is_array($faseBanco)){
			$faseBanco = voDemanda::getArrayComoStringCampoSeparador($faseBanco);
		}
		if(is_array($faseTela)){
			$faseTela = voDemanda::getArrayComoStringCampoSeparador($faseTela);
		}
		
		$isFaseAlterada = $faseTela != $faseBanco;
		/*echo "banco $faseBanco - tela $faseTela";		
		if($isFaseAlterada){
		 	throw new excecaoGenerica("fases DIFERENTES da demanda");
		}else{
			throw new excecaoGenerica("fases IGUAIS da demanda");
		}*/
		
		return $isFaseAlterada;		
	}
	
	function encaminharDemanda($vo) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$this->incluirDemandaTramitacaoSEMControleTransacao ( $vo );
			//verifica se tem alteracao a fazer no voPai
			$voDemandaPaiAlterar = $this->getVODemandaPaiAAlterarEncaminhamento($vo);
			
			//para alterar o voDemandaPAI eh necessario atribuir os novos valores expressamente
			//considerando que a busca pela chave eh feita antes, e sobrescreve os valores recuperados na tela
			if($voDemandaPaiAlterar != null){
				$voDemandaPaiAlterar->dbprocesso->cDb = $this->cDb;
				$voDemandaPaiAlterar->dbprocesso->alterarApenasVODemanda($voDemandaPaiAlterar);
			}			
			
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
			
			$this->validarEncaminhamento ( $vo );
			
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
	// usa a opcao EXCEPCIONAL do voentidade: $NM_METODO_RETORNO_CONFIRMAR
	function alterar($vo) {
		// Start transaction
		$vo->NM_METODO_RETORNO_CONFIRMAR = voDemandaTramitacao::getNmTabela ();
		
		$this->cDb->retiraAutoCommit ();
		try {
			$this->excluirDemandaTramDoc($vo);
			
			if ($vo->temDocParaIncluir ()) {				
				//echo "tem doc p incluir";
				$voDemandaTramDoc = new voDemandaTramDoc ();
				$voDemandaTramDoc = $vo->getVODemandaTramDoc ();
				$voDemandaTramDoc->dbprocesso->cDb = $this->cDb;
				$voDemandaTramDoc->dbprocesso->incluir ( $voDemandaTramDoc );
			}
			
			//echo "passou doc p incluir";			
			parent::alterar ( $vo );
			// End transaction
			$this->cDb->commit ();
				
		} catch ( Exception $e ) {
			//echo "DEU ROLLBACK";
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
	}
	function excluirDemandaTramDoc($voDemandaTram) {
		$nmTabela = voDemandaTramDoc::getNmTabelaStatic ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voDemandaTramDoc::$nmAtrAnoDemanda . " = " . $voDemandaTram->ano;
		$query .= "\n AND " . voDemandaTramDoc::$nmAtrCdDemanda . " = " . $voDemandaTram->cd;
		$query .= "\n AND " . voDemandaTramDoc::$nmAtrSqDemandaTram . " = " . $voDemandaTram->sq;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	// usa a opcao EXCEPCIONAL do voentidade: $NM_METODO_RETORNO_CONFIRMAR
	function excluir($vo) {
		$vo->NM_METODO_RETORNO_CONFIRMAR = voDemandaTramitacao::getNmTabela ();
		$this->cDb->retiraAutoCommit ();
		try {
			$this->excluirDemandaTramDoc($vo);
			parent::excluir ( $vo );
			
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			//echo "DEU ROLLBACK";
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}		
	}
	function encaminhar($vo) {
		// o alterar eh chamado na pagina generica confirmar.php
		// para chamar o alterarVO, basta chamar o parent::alterar
		// este metodo, por ser chamado da pagina manter.php, apenas incluira uma nova tramitacao
		// ele NAO altera o estado da demanda, apenas inclui uma nova tramitacao
		
		/*$isAlteracaoPermitida = $this->validarEncaminhamento ( $vo );
		if ($isAlteracaoPermitida) {*/
			$this->encaminharDemanda ( $vo );
		//}
		
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
		$retorno .= $this->getVarComoString ( voDemandaTramitacao::getNumeroPRTSemMascara($vo->prt) ) . ",";
		$retorno .= $this->getVarComoData ( $vo->dtReferencia );
		
		$retorno .= $vo->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		$retorno = "";
		$sqlConector = "";
		
		if ($vo->cdSetorOrigem != null) {
			$retorno .= $sqlConector . voDemandaTramitacao::$nmAtrCdSetorOrigem . " = " . $this->getVarComoNumero($vo->cdSetorOrigem);
			$sqlConector = ",";
		}
		
		if ($vo->cdSetorDestino != null) {
			$retorno .= $sqlConector . voDemandaTramitacao::$nmAtrCdSetorDestino . " = " . $this->getVarComoNumero($vo->cdSetorDestino);
			$sqlConector = ",";
		}
		
		if ($vo->textoTram != null) {
			$retorno .= $sqlConector . voDemandaTramitacao::$nmAtrTexto . " = " . $this->getVarComoString ( $vo->textoTram );
			$sqlConector = ",";
		}
		
		if ($vo->prt != null) {
			$retorno .= $sqlConector . voDemandaTramitacao::$nmAtrProtocolo . " = " . $this->getVarComoString ( voDemandaTramitacao::getNumeroPRTSemMascara($vo->prt));
			$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voDemandaTramitacao::$nmAtrProtocolo . " = null ";
			$sqlConector = ",";
		}
		
		if ($vo->dtReferencia != null) {
			$retorno .= $sqlConector . voDemandaTramitacao::$nmAtrDtReferencia . " = " . $this->getVarComoData($vo->dtReferencia);
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $vo->getSQLValuesEntidadeUpdate ();
		
		return $retorno;
	}
}
?>