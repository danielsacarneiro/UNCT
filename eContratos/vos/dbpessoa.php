<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_vos. "vopessoa.php");
include_once (caminho_vos. "vousuario.php");
include_once (caminho_vos. "vogestor.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbpessoa extends dbprocesso{
    
	function consultarPorChave($vo, $isHistorico){
        $nmTabela = $vo->getNmTabelaEntidade($isHistorico);
        
		$query = "SELECT ".$nmTabela;
        $query.= ".*, " . vogestor::getNmTabela() . "." .vogestor::$nmAtrDescricao;
        $query.= ", TAB1." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioInclusao;
        $query.= ", TAB2." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
        
        $query.= " FROM ". $nmTabela;
        $query.= "\n LEFT JOIN ". vogestor::getNmTabela();
        $query.= "\n ON ";
        $query.= vogestor::getNmTabela().".".vogestor::$nmAtrCd. "=".vopessoa::getNmTabela().".".vogestor::$nmAtrCd;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB1 ON ";
        $query.= "TAB1.".vousuario::$nmAtrID. "=".vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCdUsuarioInclusao;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB2 ON ";
        $query.= "TAB2.".vousuario::$nmAtrID. "=".vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCdUsuarioUltAlteracao;
        $query.= " WHERE ";
        $query.= $vo->getValoresWhereSQLChave($isHistorico);        		
        
		//echo $query;
        return $this->consultarEntidade($query, true);
	}
    
    function consultarPessoa($voentidade, $filtro){
    	$atributosConsulta = vopessoa::getNmTabela() . "." .   vopessoa::$nmAtrCd;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrNome;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrEmail;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrTel;
    	
    	
    	//$atributoVinculo = "(SELECT )"
    	
    	
        $querySelect = "SELECT ". $atributosConsulta;
        
        $queryFrom = "\n FROM ". vopessoa::getNmTabela();
        
        return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
    }
    
	public function consultarPessoaPorGestor($cdGestor){
        $vo = new vopessoa();            
        $nmTabela = $vo->getNmTabelaEntidade(false);        
		$query = "SELECT * FROM ".$nmTabela;
        
        if($cdGestor != null)
            $query.= " WHERE ". vogestor::$nmAtrCd . "=" . $cdGestor;
        		
		//echo $query;
        return $this->consultarEntidade($query, false);
	}        
    
	public function consultarGestorPorParam($cdGestor){
		$vo = new vogestor();
		$nmTabela = $vo->getNmTabelaEntidade(false);
		$query = "SELECT * FROM ".$nmTabela;
	
		if($cdGestor != null)
			$query.= " WHERE ". vogestor::$nmAtrDescricao . " LIKE '%" . $cdGestor . "%'";
	
		//echo $query;
		return $this->consultarEntidade($query, false);
	}
	
	function incluirSQL($vopessoa){
        $arrayAtribRemover = array(
            vopessoa::$nmAtrCd,
            vopessoa::$nmAtrDhInclusao,
            vopessoa::$nmAtrDhUltAlteracao
            );
        //var_dump($arrayAtribRemover);
        return $this->incluirQuery($vopessoa, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($vopessoa){
		$retorno = "";        
        //$retorno.= $this-> getProximoSequencial(vopessoa::$nmAtrCd, $vopessoa) . ",";
        //$retorno.= $this-> getVarComoNumero($vopessoa->cd) . ",";
        $retorno.= $this-> getVarComoNumero($vopessoa->id) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->nome) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->doc) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->tel) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->email) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->endereco);
        
        $retorno.= $vopessoa->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->id != null){
            $retorno.= $sqlConector . vopessoa::$nmAtrIdUser . " = " . $this->getVarComoNumero($vo->id);
            $sqlConector = ",";
        }

        if($vo->nome != null){
            $retorno.= $sqlConector . vopessoa::$nmAtrNome . " = " . $this->getVarComoString($vo->nome);
            $sqlConector = ",";
        }
        
        if($vo->doc != null){
            $retorno.= $sqlConector . vopessoa::$nmAtrDoc . " = " . $this->getVarComoString($vo->doc);
            $sqlConector = ",";
        }

        if($vo->email != null){
            $retorno.= $sqlConector . vopessoa::$nmAtrEmail . " = " . $this->getVarComoString($vo->email);
            $sqlConector = ",";
        }
        
        if($vo->tel != null){
            $retorno.= $sqlConector . vopessoa::$nmAtrTel . " = " . $this->getVarComoString($vo->tel);
            $sqlConector = ",";
        }

        if($vo->endereco != null){
        	$retorno.= $sqlConector . vopessoa::$nmAtrEndereco . " = " . $this->getVarComoString($vo->endereco);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
    
    /**
     *FUNCOES DE IMPORTACAO EXCLUSIVA
     */
    
	function importar($linha){
        $vo = new vopessoa();
        
        $atributosInsert = $vo->getTodosAtributos();        
        $arrayAtribRemover = array(
        	vopessoa::$nmAtrCd,
        	vopessoa::$nmAtrDhInclusao,
            vopessoa::$nmAtrDhUltAlteracao,
            vopessoa::$nmAtrCdUsuarioInclusao,
            vopessoa::$nmAtrCdUsuarioUltAlteracao
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
			
		$nome = $linha["B"];
        $tel  = $linha["D"];
        $email  = $linha["E"];
        $doc = null;
        $id = null;
        $endereco = null;
		        
        //CUIDADO COM A ORDEM
        //DEVE ESTAR IGUAL A getAtributosFilho()
		$retorno = "";				
		//$retorno.= $this-> getVarComoNumero($cd) . ",";		
		$retorno.= $this-> getVarComoNumero($id) . ",";
		$retorno.= $this-> getVarComoString($nome) . ",";
        $retorno.= $this-> getVarComoString($doc) . ",";
        $retorno.= $this-> getVarComoString($tel) . ",";
        $retorno.= $this-> getVarComoString($email) . ",";
        $retorno.= $this-> getVarComoString($endereco);
		return $retorno;				
	}    

}
?>