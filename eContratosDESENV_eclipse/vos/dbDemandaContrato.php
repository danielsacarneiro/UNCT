<?php
include_once(caminho_lib. "dbprocesso.obj.php");
require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioEspeciesContrato.php");

Class dbDemandaContrato extends dbprocesso{
	
	function incluirSQL($vo){
		return $this->incluirQueryVO($vo);
	}	
	
	function getSQLValuesInsert($vo){		
		if($vo->voContrato->cdEspecie == dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER){
			$vo->voContrato->sqEspecie = 1;
		}
		
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($vo->anoDemanda) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdDemanda) . ",";
		
		$retorno.= $this-> getVarComoNumero($vo->voContrato->anoContrato) . ",";
		$retorno.= $this-> getVarComoString($vo->voContrato->tipo) . ",";
		$retorno.= $this-> getVarComoString($vo->voContrato->cdEspecie) . ",";
		$retorno.= $this-> getVarComoNumero($vo->voContrato->cdContrato) . ",";		
		$retorno.= $this-> getVarComoNumero($vo->voContrato->sqEspecie);
				
		$retorno.= $vo->getSQLValuesInsertEntidade();

		return $retorno;
	}
	 
}
?>