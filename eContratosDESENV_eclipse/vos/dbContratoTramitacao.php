<?php
include_once("voTramitacao.php");

Class dbContratoTramitacao extends dbTramitacao{

	function consultar($vo, $filtro){
		$isHistorico = ("S" == $filtro->cdHistorico);	
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
		$nmTabelaTramitacao = voTramitacao::getNmTabela();

		$query = "SELECT * FROM ".$nmTabela;
		$query.= "\n INNER JOIN ". $nmTabelaTramitacao;
		$query.= "\n ON ";
		$query.= $nmTabela. ".".voContratoTramitacao::$nmAtrSq. "=".$nmTabelaTramitacao . "." . voTramitacao::$nmAtrSq;
		
		//echo $query;
		return $this->consultarEntidade($query, false);
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
		$retorno.= $this-> getVarComoNumero($vo->sq);

		$retorno.= $vo->getSQLValuesInsertEntidade();

		return $retorno;
	}
	 
}
?>