<?php
include_once(caminho_lib. "dbprocesso.obj.php");

Class dbTramitacao extends dbprocesso{

	function consultarPorChave($vo, $isHistorico){
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);

		$query = "SELECT * FROM ".$nmTabela;
		$query.= " WHERE ";
		$query.= $vo->getValoresWhereSQLChave($isHistorico);

		//echo $query;
		return $this->consultarEntidade($query, true);
	}

	function incluirSQL($vo){
		if($vo->sq == null || $vo->sq == ""){
			$vo->sq = $this->getProximoSequencial(voTramitacao::$nmAtrSq, $vo);
		}
		return $this->incluirQueryVO($vo);
	}

	function getSQLValuesInsert($voTramitacao){
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($voTramitacao->sq) . ",";
		$retorno.= $this-> getVarComoString($voTramitacao->obs) . ",";
		$retorno.= $this-> getVarComoData($voTramitacao->dtReferencia) . ",";

		$retorno.= $this-> getVarComoNumero($voTramitacao->voDoc->cdSetor) . ",";
		$retorno.= $this-> getVarComoNumero($voTramitacao->voDoc->ano) . ",";
		$retorno.= $this-> getVarComoString($voTramitacao->voDoc->tp) . ",";
		$retorno.= $this-> getVarComoNumero($voTramitacao->voDoc->sq);		

		$retorno.= $voTramitacao->getSQLValuesInsertEntidade();

		return $retorno;
	}
	 
}
?>