<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once(caminho_vos. "vopessoagestor.php");

Class dbpessoagestor extends dbprocesso{

	function consultarPorChave($vo, $isHistorico){
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);

		$query = "SELECT * FROM ".$nmTabela;
		$query.= " WHERE ";
		$query.= $vo->getValoresWhereSQLChave($isHistorico);

		//echo $query;
		return $this->consultarEntidade($query, true);
	}

	function consultarVinculoPessoa($voentidade, $filtro){
		$querySelect = "SELECT * ";
		$queryFrom = "\n FROM ". vopessoagestor::getNmTabela();

		return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
	}

	function incluirSQL($vopessoagestor){
		$arrayAtribRemover = null;
		return $this->incluirQuery($vopessoagestor, $arrayAtribRemover);
	}

	function getSQLValuesInsert($vopessoagestor){
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($vopessoagestor->cdPessoa) . ",";
		$retorno.= $this-> getVarComoNumero($vopessoagestor->cdGestor);

		$retorno.= $vopessoagestor->getSQLValuesInsertEntidade();

		return $retorno;
	}

	function getSQLValuesUpdate($vo){
		$retorno = "";
		$sqlConector = "";

		if($vo->cdPessoa != null){
			$retorno.= $sqlConector . vopessoagestor::$nmAtrCdPessoa . " = " . $this->getVarComoNumero($vo->cdPessoa);
			$sqlConector = ",";
		}
		
		if($vo->cdGestor != null){
			$retorno.= $sqlConector . vopessoagestor::$nmAtrCdGestor . " = " . $this->getVarComoNumero($vo->cdGestor);
			$sqlConector = ",";
		}

		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();

		return $retorno;
	}
	 
}
?>