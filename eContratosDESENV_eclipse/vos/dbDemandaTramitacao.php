<?php
include_once(caminho_lib. "dbprocesso.obj.php");

Class dbDemandaTramitacao extends dbprocesso{

	function consultarPorChave($vo, $isHistorico){
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);

		$query = "SELECT * FROM ".$nmTabela;
		$query.= " WHERE ";
		$query.= $vo->getValoresWhereSQLChave($isHistorico);

		//echo $query;
		return $this->consultarEntidade($query, true);
	}

	function consultarTelaConsulta($vo, $filtro){
		$isHistorico = $filtro->isHistorico;
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
		/*$nmTabelaTramitacao = voTramitacao::getNmTabela();
		$nmTabelaContrato = vocontrato::getNmTabela();
		$nmTabelaPessoa = vopessoa::getNmTabela();*/
		$nmTabelaUsuario = vousuario::getNmTabela();
	
		$querySelect = "SELECT ";
		$querySelect .= $nmTabela.".*";
		//$querySelect .= "," . $nmTabelaTramitacao.".*";
		$querySelect .= "," . $nmTabelaUsuario . "." . vousuario::$nmAtrName;
		$querySelect .= "  AS " . voDemanda::$nmAtrNmUsuarioInclusao;
		$queryFrom = " FROM ".$nmTabela;
	
		/*$queryFrom.= "\n INNER JOIN ". $nmTabelaTramitacao;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabela. ".".voContratoTramitacao::$nmAtrSq. "=".$nmTabelaTramitacao . "." . voTramitacao::$nmAtrSq;
		$queryFrom.= "\n INNER JOIN ". $nmTabelaContrato;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabela. ".".voContratoTramitacao::$nmAtrAnoContrato. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryFrom.= "\n AND ";
		$queryFrom.= $nmTabela. ".".voContratoTramitacao::$nmAtrTipoContrato. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryFrom.= "\n AND ";
		$queryFrom.= $nmTabela. ".".voContratoTramitacao::$nmAtrCdContrato. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		$queryFrom.= "\n INNER JOIN ". $nmTabelaPessoa;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabelaPessoa. ".".vopessoa::$nmAtrCd. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;*/
		$queryFrom.= "\n INNER JOIN ". $nmTabelaUsuario;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabelaUsuario. ".".vousuario::$nmAtrID. "=".$nmTabela . "." . voDemanda::$nmAtrCdUsuarioInclusao;	
		
		//echo $query;
		return parent::consultarTelaConsulta($filtro, $querySelect, $queryFrom);
	}
	
	function incluirSQL($vo){
		if($vo->sq == null || $vo->sq == ""){
			$vo->sq = $this->getProximoSequencialChaveComposta(voDemandaTramitacao::$nmAtrSq, $vo);
		}
		return $this->incluirQueryVO($vo);
	}
	
	//o incluir eh implementado para nao usar da voentidade
	//por ser mais complexo
	function incluir($vo){
		
		$this->validarInclusao($vo);
		
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
	
			$voDemanda = new voDemanda();
			$voDemanda = $vo->getVOPai();
			$voDemanda->dbprocesso->cDb = $this->cDb;
			$voDemanda->dbprocesso->incluir($voDemanda);	
			$vo->cd = $voDemanda->cd;
			
			if($voDemanda->temContratoParaIncluir()){
				$voDemContrato = $voDemanda->getVODemandaContrato();
				$voDemContrato->dbprocesso->cDb = $this->cDb;
				$voDemContrato->dbprocesso->incluir($voDemContrato);
			}
			
			//a transacao ja eh controlada acima
			$this->incluirDemandaTramitacaoSEMControleTransacao($vo);						
			
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}
	
		return $voDemanda;
	}
	
	function validarInclusao($vo){
		$voDemanda = new voDemanda();		
		
		if($vo->tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO && !$voDemanda->temContratoParaIncluir()){
			$msg = "Selecione o contrato.";
			throw new Exception($msg);
		}
	
	}
	
	function validarEncaminhamento($vo){
			
		if($vo->situacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA){
			$msg = "Encaminhamento não permitido para demanda FECHADA.";
			throw new Exception($msg);
		}
			
		return true;
	}
		
	function encaminharDemanda($vo){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
			$this->incluirDemandaTramitacaoSEMControleTransacao($vo);
			
			/*$voDemanda = new voDemanda();
			$voDemanda = $vo->getVOPaiChave();
			$registro = $voDemanda->dbprocesso->consultarPorChave($voDemanda, false);
			$voDemanda->getDadosBanco($registro);
			$voDemanda->dbprocesso->cDb = $this->cDb;
			$voDemanda->situacao = dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA;
			$voDemanda->dbprocesso->alterar($voDemanda);*/
				
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}		
	}
	
	function incluirDemandaTramitacaoSEMControleTransacao($vo){
			//echo "codigo demandatramitacao: " . $vo->cd;
			if($vo->temTramitacaoParaIncluir()){
				if($vo->sq == null || $vo->sq == ""){
					$vo->sq= $this->getProximoSequencialChaveComposta(voDemandaTramitacao::$nmAtrSq, $vo);
				}
				
				parent::incluir($vo);
				//verifica se tem voDoc pra incluir
				if($vo->temDocParaIncluir()){
					$voDemandaTramDoc = new voDemandaTramDoc();
					$voDemandaTramDoc = $vo->getVODemandaTramDoc();
					$voDemandaTramDoc->dbprocesso->cDb = $this->cDb;
					$voDemandaTramDoc->dbprocesso->incluir($voDemandaTramDoc);
				}
	
			}		
	}
	
	function alterar($vo){
		//o alterar eh chamado na pagina generica confirmar.php
		//para chamar o alterarVO, basta chamar o parent::alterar
		//este metodo, por ser chamado da pagina manter.php, apenas incluira uma nova tramitacao
		//ele NAO altera o estado da demanda, apenas inclui uma nova tramitacao
		$isAlteracaoPermitida = $this->validarEncaminhamento($vo);
		if($isAlteracaoPermitida){		
			$this->encaminharDemanda($vo);
		}
		
		return $vo;
	}
	
	function excluir($vo){
		throw new Exception("Operacao nao permitida.");
	}

	function getSQLValuesInsert($vo){
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($vo->ano) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cd) . ",";
		$retorno.= $this-> getVarComoNumero($vo->sq) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdSetorOrigem) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdSetorDestino) . ",";
		$retorno.= $this-> getVarComoString($vo->textoTram) . ",";
		$retorno.= $this-> getVarComoString($vo->prt) . ",";
		$retorno.= $this-> getVarComoData($vo->dtReferencia);

		$retorno.= $vo->getSQLValuesInsertEntidade();

		return $retorno;
	}
	 
}
?>