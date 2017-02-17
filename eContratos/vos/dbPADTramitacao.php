<?php
include_once(caminho_lib. "dbprocesso.obj.php");

  Class dbPADTramitacao extends dbprocesso{
    
	function consultarPorChave($vo, $isHistorico){
        $nmTabela = $vo->getNmTabelaEntidade($isHistorico);
        
		$query = "SELECT * FROM ".$nmTabela;
        $query.= " WHERE ";
        $query.= $vo->getValoresWhereSQLChave($isHistorico);        		
        
		//echo $query;
        return $this->consultarEntidade($query, true);
	}
            
    function incluirSQL($voPADTramitacao){
        $arrayAtribRemover = null;
        return $this->incluirQuery($voPADTramitacao, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($voPADTramitacao){
		$retorno = "";        
        $retorno.= $this-> getVarComoNumero($voPADTramitacao->sq) . ",";
        $retorno.= $this-> getVarComoNumero($voPADTramitacao->cdPA) . ",";
        $retorno.= $this-> getVarComoNumero($voPADTramitacao->anoPA) . ",";
        $retorno.= $this-> getVarComoString($voPADTramitacao->obs);
        
        $retorno.= $voPADTramitacao->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->obs != null){
            $retorno.= $sqlConector . voPADTramitacao::$nmAtrObservacao. " = " . $this->getVarComoString($vo->obs);
            $sqlConector = ",";
        }
                
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
   
}
?>