<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_vos. "vopessoagestor.php");
include_once (caminho_vos. "vousuario.php");
include_once (caminho_vos. "vogestor.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbpessoagestor extends dbprocesso{
    
	function consultarPorChave($vo, $isHistorico){
        $nmTabela = $vo->getNmTabelaEntidade($isHistorico);
        
		$query = "SELECT ".$nmTabela;
        $query.= ".*, " . vogestor::getNmTabela() . "." .vogestor::$nmAtrDescricao;
        $query.= ", TAB2." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
        
        $query.= " FROM ". $nmTabela;
        $query.= "\n LEFT JOIN ". vogestor::getNmTabela();
        $query.= "\n ON ";
        $query.= vogestor::getNmTabela().".".vogestor::$nmAtrCd. "=".vopessoagestor::getNmTabela().".".vopessoagestor::$nmAtrCdGestor;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB2 ON ";
        $query.= "TAB2.".vousuario::$nmAtrID. "=".vopessoagestor::getNmTabela() . "." . vopessoagestor::$nmAtrCdUsuarioUltAlteracao;
        $query.= " WHERE ";
        $query.= $vo->getValoresWhereSQLChave($isHistorico);        		
        
		//echo $query;
        return $this->consultarEntidade($query, true);
	}
    
    function consultarPessoaGestor($voentidade, $filtro){
        $querySelect = "SELECT ". vopessoagestor::getNmTabela() . ".*," . vogestor::$nmAtrDescricao;
        
        $queryFrom = "\n FROM ". vopessoagestor::getNmTabela();
        $queryFrom .= "\n LEFT JOIN ". vogestor::getNmTabela();
        $queryFrom .= "\n ON ". vogestor::getNmTabela() . "." . vogestor::$nmAtrCd;
        $queryFrom .= "\n = ". vopessoagestor::getNmTabela() . "." . vopessoagestor::$nmAtrCdGestor;        
        
        return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
    }
    
	public function consultarSelect($cdGestor){
        $vo = new vopessoagestor();            
        $nmTabela = $vo->getNmTabelaEntidade(false);        
		$query = "SELECT * FROM ".$nmTabela;
        
        if($cdGestor != null)
            $query.= " WHERE ". vopessoagestor::$nmAtrCdGestor . "=" . $cdGestor;
        		
		//echo $query;
        return $this->consultarEntidade($query, false);
	}        
    
    function incluirSQL($vopessoagestor){
        $arrayAtribRemover = array(
            vopessoagestor::$nmAtrCd,
            vopessoagestor::$nmAtrDhInclusao,
            vopessoagestor::$nmAtrDhUltAlteracao
            );
        return $this->incluirQuery($vopessoagestor, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($vopessoagestor){
		$retorno = "";        
        $retorno.= $this-> getVarComoNumero($vopessoagestor->cdPessoa) . ",";
        $retorno.= $this-> getVarComoString($vopessoagestor->cdGestor) . ",";
        
        $retorno.= $vopessoagestor->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    
}
?>