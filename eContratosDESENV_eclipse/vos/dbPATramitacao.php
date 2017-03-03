<?php
include_once(caminho_lib. "dbprocesso.obj.php");

  Class dbPATramitacao extends dbprocesso{
    
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
        $retorno.= $this-> getVarComoString($voPADTramitacao->obs) . ",";
        
        /*$retorno.= $this-> getVarComoNumero($voPADTramitacao->cdSetorDoc) . ",";
        $retorno.= $this-> getVarComoNumero($voPADTramitacao->anoDoc) . ",";
        $retorno.= $this-> getVarComoString($voPADTramitacao->tpDoc) . ",";
        $retorno.= $this-> getVarComoNumero($voPADTramitacao->sqDoc);*/
        
        $retorno.= $this-> getVarComoNumero($voPADTramitacao->voDoc->cdSetor) . ",";
        $retorno.= $this-> getVarComoNumero($voPADTramitacao->voDoc->ano) . ",";
        $retorno.= $this-> getVarComoString($voPADTramitacao->voDoc->tpDoc) . ",";
        $retorno.= $this-> getVarComoNumero($voPADTramitacao->voDoc->sq);
        
        $retorno.= $voPADTramitacao->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    /*function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->obs != null){
            $retorno.= $sqlConector . voPATramitacao::$nmAtrObservacao. " = " . $this->getVarComoString($vo->obs);
            $sqlConector = ",";
        }
                        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }*/
   
}
?>