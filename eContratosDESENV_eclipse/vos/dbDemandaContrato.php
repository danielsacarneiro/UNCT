<?php
include_once(caminho_lib. "dbprocesso.obj.php");

Class dbDemandaContrato extends dbprocesso{
	
	function incluirSQL($vo){
		return $this->incluirQueryVO($vo);
	}	
	
	function getSQLValuesInsert($vo){
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($vo->anoDemanda) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdDemanda) . ",";
		$retorno.= $this-> getVarComoNumero($vo->sqContrato);

		$retorno.= $vo->getSQLValuesInsertEntidade();

		return $retorno;
	}
	 
}
?>