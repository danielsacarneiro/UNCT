<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once 'voOficio.php';

  Class dbOficio extends dbprocesso{
            
  	function consultarOficio($voentidade, $filtro){
  		$isHistorico = ("S" == $filtro->cdHistorico);
  		$nmTabela = $voentidade->getNmTabelaEntidade($isHistorico);
  		 
  		$atributosConsulta = "*";  		   			 
  		$querySelect = "SELECT ". $atributosConsulta;  	
		$queryFrom = "\n FROM ". $nmTabela;  	
  		
  		return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
  	}
  	 
  	function incluir($vo){
  		  	
  		if($vo->sq == null || $vo->sq == ""){
  			$vo->sq = $this->getProximoSequencialChaveComposta(voOficio::$nmAtrSq, $vo);
  			//echo "EH NULO";
  		}
  	
  		$query = $this->incluirQueryVO($vo);
  		$retorno = $this->cDb->atualizar($query);
  	
  		return $vo;
  	}   

    function getSQLValuesInsert($voOficio){
		$retorno = "";        
        $retorno.= $this-> getVarComoNumero($voOficio->sq) . ",";
        $retorno.= $this-> getVarComoNumero($voOficio->cdSetor) . ",";
        $retorno.= $this-> getVarComoNumero($voOficio->ano);
        
        $retorno.= $voOficio->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    /*function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->obs != null){
            $retorno.= $sqlConector . voOficio::$nmAtrObservacao. " = " . $this->getVarComoString($vo->obs);
            $sqlConector = ",";
        }
                
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }*/
   
}
?>