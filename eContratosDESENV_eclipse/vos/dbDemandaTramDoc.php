<?php
include_once(caminho_lib. "dbprocesso.obj.php");

Class dbDemandaTramDoc extends dbprocesso{
	
	function incluirSQL($vo){
		return $this->incluirQueryVO($vo);
	}	
	
	function getSQLValuesInsert($vo){				
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($vo->anoDemanda) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdDemanda) . ",";
		$retorno.= $this-> getVarComoNumero($vo->sqDemandaTram) . ",";

		$retorno.= $this-> getVarComoNumero($vo->anoDoc) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdSetorDoc) . ",";
		$retorno.= $this-> getVarComoString($vo->tpDoc) . ",";
		$retorno.= $this-> getVarComoNumero($vo->sqDoc);

		$retorno.= $vo->getSQLValuesInsertEntidade();
		 
		return $retorno;
	}
	 
}
?>