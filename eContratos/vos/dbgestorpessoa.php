<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_vos. "vogestorpessoa.php");
include_once (caminho_vos. "vousuario.php");
include_once (caminho_vos. "vogestor.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbgestorpessoa extends dbprocesso{
    
	function consultarPorChave($vo, $isHistorico){
        $nmTabela = $vo->getNmTabelaEntidade($isHistorico);
        
		$query = "SELECT ".$nmTabela;
        $query.= ".*, " . vogestor::getNmTabela() . "." .vogestor::$nmAtrDescricao;
        $query.= ", TAB1." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioInclusao;
        $query.= ", TAB2." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
        
        $query.= " FROM ". $nmTabela;
        $query.= "\n LEFT JOIN ". vogestor::getNmTabela();
        $query.= "\n ON ";
        $query.= vogestor::getNmTabela().".".vogestor::$nmAtrCd. "=".vogestorpessoa::getNmTabela().".".vogestorpessoa::$nmAtrCdGestor;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB1 ON ";
        $query.= "TAB1.".vousuario::$nmAtrID. "=".vogestorpessoa::getNmTabela() . "." . vogestorpessoa::$nmAtrCdUsuarioInclusao;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB2 ON ";
        $query.= "TAB2.".vousuario::$nmAtrID. "=".vogestorpessoa::getNmTabela() . "." . vogestorpessoa::$nmAtrCdUsuarioUltAlteracao;
        $query.= " WHERE ";
        $query.= $vo->getValoresWhereSQLChave($isHistorico);        
        		
		//echo $query;
        return $this->consultarEntidade($query, true);
	}
    
    function consultarGestorPessoa($voentidade, $filtro){
        $querySelect = "SELECT ". vogestorpessoa::getNmTabela() . ".*," . vogestor::$nmAtrDescricao;
        
        $queryFrom = "\n FROM ". vogestorpessoa::getNmTabela();
        $queryFrom .= "\n LEFT JOIN ". vogestor::getNmTabela();
        $queryFrom .= "\n ON ". vogestor::getNmTabela() . "." . vogestor::$nmAtrCd;
        $queryFrom .= "\n = ". vogestorpessoa::getNmTabela() . "." . vogestorpessoa::$nmAtrCdGestor;        
        
        return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
    }
    
	public function consultarSelect($cdGestor){
        $vo = new vogestorpessoa();            
        $nmTabela = $vo->getNmTabelaEntidade(false);        
		$query = "SELECT * FROM ".$nmTabela;
        
        if($cdGestor != null)
            $query.= " WHERE ". vogestorpessoa::$nmAtrCdGestor . "=" . $cdGestor;
        		
		//echo $query;
        return $this->consultarEntidade($query, false);
	}        
    
    function incluirSQL($voGestorPessoa){
        $arrayAtribRemover = array(
            vogestorpessoa::$nmAtrCd,
            vogestorpessoa::$nmAtrDhInclusao,
            vogestorpessoa::$nmAtrDhUltAlteracao
            );
        return $this->incluirQuery($voGestorPessoa, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($voGestorPessoa){
		$retorno = "";        
        //$retorno.= $this-> getProximoSequencial(vogestorpessoa::$nmAtrCd, $voGestorPessoa) . ",";
        $retorno.= $this-> getVarComoNumero($voGestorPessoa->cdGestor) . ",";
        $retorno.= $this-> getVarComoString($voGestorPessoa->nome) . ",";
        $retorno.= $this-> getVarComoString($voGestorPessoa->doc) . ",";
        $retorno.= $this-> getVarComoString($voGestorPessoa->tel) . ",";
        $retorno.= $this-> getVarComoString($voGestorPessoa->email) . ",";
        
        $retorno.= $voGestorPessoa->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->cdGestor != null){
            $retorno.= $sqlConector . vogestorpessoa::$nmAtrCdGestor . " = " . $this->getVarComoNumero($vo->cdGestor);
            $sqlConector = ",";
        }

        if($vo->nome != null){
            $retorno.= $sqlConector . vogestorpessoa::$nmAtrNome . " = " . $this->getVarComoString($vo->nome);
            $sqlConector = ",";
        }
        
        if($vo->doc != null){
            $retorno.= $sqlConector . vogestorpessoa::$nmAtrDoc . " = " . $this->getVarComoString($vo->doc);
            $sqlConector = ",";
        }

        if($vo->email != null){
            $retorno.= $sqlConector . vogestorpessoa::$nmAtrEmail . " = " . $this->getVarComoString($vo->email);
            $sqlConector = ",";
        }
        
        if($vo->tel != null){
            $retorno.= $sqlConector . vogestorpessoa::$nmAtrTel . " = " . $this->getVarComoString($vo->tel);
            $sqlConector = ",";
        }

        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
    
    /**
     *FUNCOES DE IMPORTACAO EXCLUSIVA
     */
    
	function importar($linha){
        $vo = new vogestorpessoa();
        
        $atributosInsert = $vo->getTodosAtributos();        
        $arrayAtribRemover = array(
            vogestorpessoa::$nmAtrDhInclusao,
            vogestorpessoa::$nmAtrDhUltAlteracao,
            vogestorpessoa::$nmAtrCdUsuarioInclusao,
            vogestorpessoa::$nmAtrCdUsuarioUltAlteracao
            );                    
        //var_dump($arrayAtribRemover);
        $atributosInsert = removeColecaoAtributos($atributosInsert, $arrayAtribRemover);        
        $atributosInsert = getColecaoEntreSeparador($atributosInsert, ",");
        
		$query = " INSERT INTO " . $vo->getNmTabela() . " \n";
		$query .= " (";
		$query .= $atributosInsert;
		$query .=") ";
		
		$query .= " \nVALUES(";
		$query .= $this->getAtributosInsertImportacaoPlanilha($linha);
		$query .=")";
		
		//echo $query;		
		$retorno = $this->cDb->atualizarImportacao($query);					
	    return $retorno;		
	}	
    
	function getAtributosInsertImportacaoPlanilha($linha){			
		$cd = $linha["A"];	
		$nome = $linha["B"];
        $tel  = $linha["D"];
        $email  = $linha["E"];
        $doc = null;
		        
        //CUIDADO COM A ORDEM
        //DEVE ESTAR IGUAL A getAtributosFilho()
		$retorno = "";				
		//$retorno.= $this-> getVarComoNumero($cd) . ",";		
		$retorno.= $this-> getVarComoString($nome) . ",";
        $retorno.= $this-> getVarComoString($doc) . ",";
        $retorno.= $this-> getVarComoString($tel) . ",";
        $retorno.= $this-> getVarComoString($email);		
		return $retorno;				
	}    

}
?>