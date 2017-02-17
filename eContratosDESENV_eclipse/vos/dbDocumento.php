<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once 'voDocumento.php';

  Class dbDocumento extends dbprocesso{
            
  	function consultarDocumento($voentidade, $filtro){
  		$isHistorico = ("S" == $filtro->cdHistorico);
  		$nmTabela = $voentidade->getNmTabelaEntidade($isHistorico);
  		 
  		$atributosConsulta = "*";  		   			 
  		$querySelect = "SELECT ". $atributosConsulta;  	
		$queryFrom = "\n FROM ". $nmTabela;  	
  		
  		return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
  	}
  	 
  	function incluir($vo){
  		  	
  		if($vo->sq == null || $vo->sq == ""){
  			$vo->sq = $this->getProximoSequencialChaveComposta(voDocumento::$nmAtrSq, $vo);
  			//echo "EH NULO";
  		}
  	
  		$query = $this->incluirQueryVO($vo);
  		$retorno = $this->cDb->atualizar($query);
  	
  		return $vo;
  	}   

    function getSQLValuesInsert($voDocumento){
		$retorno = "";        
        $retorno.= $this-> getVarComoNumero($voDocumento->sq) . ",";
        $retorno.= $this-> getVarComoNumero($voDocumento->cdSetor) . ",";
        $retorno.= $this-> getVarComoNumero($voDocumento->ano). ",";
        $retorno.= $this-> getVarComoNumero($voDocumento->tpDoc). ",";
        $retorno.= $this-> getVarComoString($voDocumento->linkDoc);
        
        $retorno.= $voDocumento->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    /*function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->obs != null){
            $retorno.= $sqlConector . voDocumento::$nmAtrObservacao. " = " . $this->getVarComoString($vo->obs);
            $sqlConector = ",";
        }
                
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }*/
   
}
?>