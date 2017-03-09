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
		
		//$query.= " WHERE ";
		//$query.= $vo->getValoresWhereSQLChave($isHistorico);

		//echo $query;
		return $this->consultarEntidade($query, false);
	}

	function consultarPorChave($vo, $isHistorico){
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
	
		$query = "SELECT * FROM ".$nmTabela;
		$query.= " WHERE ";
		$query.= $vo->getValoresWhereSQLChave($isHistorico);
	
		//echo $query;
		return $this->consultarEntidade($query, true);
	}
	
	/*function incluirSQL($voTramitacao){
		$arrayAtribRemover = null;
		return $this->incluirQuery($voTramitacao, $arrayAtribRemover);
	}*/
	
	//o incluir eh implementado para nao usar da voentidade
	//por ser mais complexo
	function incluir($vo){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
			$votramitacao = $vo->getVOPai();			
			$this->incluirTramitacao($votramitacao);
			
			$vo->sq = $votramitacao->sq;
			$vo = $this->incluirContratoTramitacao($vo);	
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}
	
		return $vo;
	}
	
	function incluirContratoTramitacao($voCTTRam){
		//o voContratoTramitacao nao tem nenhum atributo de controle
		//por isso todos sao removidos
		//os atributos a remover ja foram removidos no construtor
		$query = $this->incluirQueryVO($voCTTRam);
		$retorno = $this->cDb->atualizar($query);	
		return $voCTTRam;
	}
	
	function incluirTramitacao($voTramitacao){
		//var_dump($voTramitacao);
		$voTramitacao->dbprocesso->cDb = $this->cDb;
		$voTramitacao->dbprocesso->incluir($voTramitacao);
	}
		
	function excluirPATramitacao($voPA){
		$vo = new voPATramitacao();
		$nmTabela = $vo->getNmTabelaEntidade(false);
		$query = "DELETE FROM ".$nmTabela;
		$query.= "\n WHERE ". voPATramitacao::$nmAtrCdPA. " = ". $voPA->cdPA;
		$query.= "\n AND ". voPATramitacao::$nmAtrAnoPA. " = ". $voPA->anoPA;
		//echo $query;
		return $this->atualizarEntidade($query);
	}
	
	//o incluir eh implementado para nao usar da voentidade
	//por ser mais complexo
	function excluir($vo){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
			$this->excluirPATramitacao($vo);
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