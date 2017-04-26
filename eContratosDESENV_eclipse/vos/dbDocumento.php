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
  		}
  	
  		$query = $this->incluirQueryVO($vo);
  		//echo $query;
  		$retorno = $this->cDb->atualizar($query);
  	
  		return $vo;
  	}   

    function getSQLValuesInsert($voDocumento){
		$retorno = "";        
        $retorno.= $this-> getVarComoNumero($voDocumento->sq) . ",";
        $retorno.= $this-> getVarComoNumero($voDocumento->cdSetor) . ",";
        $retorno.= $this-> getVarComoNumero($voDocumento->ano). ",";
        $retorno.= $this-> getVarComoString($voDocumento->tp). ",";
        $retorno.= $this-> getVarComoString($voDocumento->link);
        
        $retorno.= $voDocumento->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->link != null){
            $retorno.= $sqlConector . voDocumento::$nmAtrLink. " = " . $this->getVarComoString($vo->link);
            $sqlConector = ",";
        }
                
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
   
}
?>