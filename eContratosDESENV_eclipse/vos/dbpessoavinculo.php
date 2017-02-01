<?php
include_once(caminho_lib. "dbprocesso.obj.php");

  Class dbpessoavinculo extends dbprocesso{
    
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
        $queryFrom = "\n FROM ". vopessoavinculo::getNmTabela();        
        
        return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
    }
        
    function incluirSQL($vopessoavinculo){
        $arrayAtribRemover = null;
        return $this->incluirQuery($vopessoavinculo, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($vopessoavinculo){
		$retorno = "";        
        $retorno.= $this-> getVarComoNumero($vopessoavinculo->cd) . ",";
        $retorno.= $this-> getVarComoNumero($vopessoavinculo->cdPessoa);
        
        $retorno.= $vopessoavinculo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->cd != null){
            $retorno.= $sqlConector . vopessoavinculo::$nmAtrCd . " = " . $this->getVarComoNumero($vo->cd);
            $sqlConector = ",";
        }

        if($vo->cdPessoa != null){
            $retorno.= $sqlConector . vopessoavinculo::$nmAtrCdPessoa . " = " . $this->getVarComoNumero($vo->cdPessoa);
            $sqlConector = ",";
        }
                
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
   
}
?>