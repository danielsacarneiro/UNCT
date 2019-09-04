<?php
include_once(caminho_lib. "dbprocesso.obj.php");

Class dbDemandaPL extends dbprocesso{
	
	function incluirSQL($vo){
		return $this->incluirQueryVO($vo);
	}	
	
	function getSQLValuesInsert($vo){		
		
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($vo->anoDemanda) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdDemanda) . ",";
		
		$retorno.= $this-> getVarComoNumero($vo->anoProcLic) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdProcLic) . ",";
		$retorno.= $this-> getVarComoString($vo->cdModProcLic);
				
		$retorno.= $vo->getSQLValuesInsertEntidade();

		return $retorno;
	}
	 
}
?>