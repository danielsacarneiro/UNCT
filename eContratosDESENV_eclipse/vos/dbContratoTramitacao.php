<?php
include_once("voTramitacao.php");
include_once(caminho_funcoes."contrato/dominioEspeciesContrato.php");

Class dbContratoTramitacao extends dbTramitacao{

	function consultar($vo, $filtro){
		$isHistorico = ("S" == $filtro->cdHistorico);	
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
		$nmTabelaTramitacao = voTramitacao::getNmTabela();
		$nmTabelaContrato = vocontrato::getNmTabela();
		$nmTabelaPessoa = vopessoa::getNmTabela();

		$querySelect = "SELECT *";
		$queryFrom = " FROM ".$nmTabela;
		
		$queryFrom.= "\n INNER JOIN ". $nmTabelaTramitacao;
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
		$queryFrom.= $nmTabelaPessoa. ".".vopessoa::$nmAtrCd. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;		
		
		$filtro->cdEspecieContrato = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		//echo $query;
		return $this->consultarComPaginacaoQuery($vo, $filtro, $querySelect, $queryFrom);
	}	

	function consultarPorChave($vo, $isHistorico){
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
		$nmTabelaTramitacao = voTramitacao::getNmTabela();
		
		$arrayColunasRetornadas = array($nmTabela . ".*", $nmTabelaTramitacao . ".*");
			
		$queryJoin.= "\n INNER JOIN ". $nmTabelaTramitacao;
		$queryJoin.= "\n ON ";
		$queryJoin.= $nmTabela. ".".voContratoTramitacao::$nmAtrSq. "=".$nmTabelaTramitacao . "." . voTramitacao::$nmAtrSq;
					
		return $this->consultarPorChaveMontandoQuery($vo, $arrayColunasRetornadas, $queryJoin, $isHistorico);
	}
	
	function consultarDetalhamento($vo, $isHistorico){
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
		$nmTabelaTramitacao = voTramitacao::getNmTabela();
		$nmTabelaPessoa = vopessoa::getNmTabela();
		$nmTabelaContrato = vocontrato::getNmTabela();
	
		$arrayColunasRetornadas = array($nmTabela . ".*", $nmTabelaTramitacao . ".*", $nmTabelaPessoa . ".*");
			
		$queryJoin.= "\n INNER JOIN ". $nmTabelaTramitacao;
		$queryJoin.= "\n ON ";
		$queryJoin.= $nmTabela. ".".voContratoTramitacao::$nmAtrSq. "=".$nmTabelaTramitacao . "." . voTramitacao::$nmAtrSq;
		$queryJoin.= "\n INNER JOIN ". $nmTabelaContrato;
		$queryJoin.= "\n ON ";
		$queryJoin.= $nmTabelaContrato. ".".vocontrato::$nmAtrAnoContrato. "=".$nmTabela . "." . voContratoTramitacao::$nmAtrAnoContrato;
		$queryJoin.= "\n AND ";
		$queryJoin.= $nmTabelaContrato. ".".vocontrato::$nmAtrTipoContrato. "=".$nmTabela . "." . voContratoTramitacao::$nmAtrTipoContrato;
		$queryJoin.= "\n AND ";
		$queryJoin.= $nmTabelaContrato. ".".vocontrato::$nmAtrCdContrato. "=".$nmTabela . "." . voContratoTramitacao::$nmAtrCdContrato;
		$queryJoin.= "\n INNER JOIN ". $nmTabelaPessoa;
		$queryJoin.= "\n ON ";
		$queryJoin.= $nmTabelaContrato. ".".vocontrato::$nmAtrCdPessoaContratada. "=".$nmTabelaPessoa . "." . vopessoa::$nmAtrCd;
				
		$queryWhere = " WHERE ";
		$queryWhere.= $nmTabelaContrato. ".".vocontrato::$nmAtrCdEspecieContrato. "='". dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER . "'";
		$queryWhere.= " AND ". $vo->getValoresWhereSQLChave($isHistorico);
		
		$nmTabelaUsuario = voTramitacao::getNmTabela();
		
		return $this->consultarMontandoQueryUsuario($vo, $nmTabelaUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico);
	}
	
	function incluirSQL($vo){		
		return $this->incluirQueryVO($vo);
	}
	
	//o incluir eh implementado para nao usar da voentidade
	//por ser mais complexo
	function incluir($vo){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
		
			$voTramitacao = $vo->getVOPai();
			$voTramitacao->dbprocesso->cDb = $this->cDb;
			$voTramitacao->dbprocesso->incluir($voTramitacao);				
			
			if($vo->sqIndice == null || $vo->sqIndice == ""){
				$arrayAtribRemover = array(voContratoTramitacao::$nmAtrSqIndice, voContratoTramitacao::$nmAtrSq);
				$vo->sqIndice = $this->getProximoSequencialChaveCompostaLogica(voContratoTramitacao::$nmAtrSqIndice, $vo, $arrayAtribRemover);
			}
			
			$vo->sq = $voTramitacao->sq;
			$vo = parent::incluir($vo);
		
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}
	
		return $vo;
	}
			
	//o incluir eh implementado para nao usar da voentidade
	//por ser mais complexo
	function excluir($vo){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
			$voTramitacao = $vo->getVOPai();						
			$voTramitacao->dbprocesso->cDb = $this->cDb;
			$voTramitacao->dbprocesso->excluir($voTramitacao);
				
			$vo = parent::excluir($vo);
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}
	
		return $vo;
	}
	
	function alterar($vo){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
			$this->excluirPATramitacao($vo);
			$vo = $this->incluirPATramitacao($vo);
	
			$vo = parent::alterar($vo);
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}
	
		return $vo;
	}	

	function getSQLValuesInsert($vo){
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($vo->cdContrato) . ",";
		$retorno.= $this-> getVarComoNumero($vo->anoContrato) . ",";
		$retorno.= $this-> getVarComoString($vo->tipoContrato) . ",";
		$retorno.= $this-> getVarComoNumero($vo->sq) . ",";
		$retorno.= $this-> getVarComoNumero($vo->sqIndice);

		$retorno.= $vo->getSQLValuesInsertEntidade();

		return $retorno;
	}
	 
}
?>