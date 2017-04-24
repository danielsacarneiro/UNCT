<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_vos. "vopessoa.php");
include_once (caminho_vos. "vousuario.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_vos. "dbgestor.php");
include_once (caminho_vos. "dbpessoavinculo.php");
include_once (caminho_vos. "dbpessoagestor.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbpessoa extends dbprocesso{
    
	function consultarPorChave($vo, $isHistorico){
        $nmTabela = $vo->getNmTabelaEntidade($isHistorico);
        
		$query = "SELECT ".$nmTabela;
		$query.= ".*, " . vopessoavinculo::getNmTabela() . "." .vopessoavinculo::$nmAtrCd;
		$query.= ", " . vogestor::getNmTabela() . "." .vogestor::$nmAtrCd;
		$query.= ", " . vogestor::getNmTabela() . "." .vogestor::$nmAtrDescricao;
        $query.= ", TAB1." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioInclusao;
        $query.= ", TAB2." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
        
        $query.= " FROM ". $nmTabela;
        $query.= "\n INNER JOIN ". vopessoavinculo::getNmTabela();
        $query.= "\n ON ";
        $query.= vopessoavinculo::getNmTabela(). ".".vopessoavinculo::$nmAtrCdPessoa. "=".vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCd;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB1 ON ";
        $query.= "TAB1.".vousuario::$nmAtrID. "=".vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCdUsuarioInclusao;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB2 ON ";
        $query.= "TAB2.".vousuario::$nmAtrID. "=".vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCdUsuarioUltAlteracao;
        
        $query.= "\n LEFT JOIN ". vopessoagestor::getNmTabela();
        $query.= "\n ON ";
        $query.= vopessoagestor::getNmTabela().".".vopessoagestor::$nmAtrCdPessoa. "=".vopessoa::getNmTabela().".".vopessoa::$nmAtrCd;
        $query.= "\n LEFT JOIN ". vogestor::getNmTabela();
        $query.= "\n ON ";
        $query.= vogestor::getNmTabela().".".vogestor::$nmAtrCd. "=".vopessoagestor::getNmTabela().".".vopessoagestor::$nmAtrCdGestor;
        $query.= " WHERE ";
        $query.= $vo->getValoresWhereSQLChave($isHistorico);
        
		//echo $query;
        $recordset = $this->consultarEntidade($query, false);        
        $colecaoColunasATransformar = array(vopessoavinculo::$nmAtrCd, vogestor::$nmAtrDescricao);
        
        $retorno = $this->getEntidadePorChavePrimariaComValoresDiversosEmColunas($recordset, $colecaoColunasATransformar);
                
        return $retorno;
	}
    
	/**
	 * 
	 * @param unknown $voentidade
	 * @param unknown $filtro
	 * @return string
	 * @deprecated
	 */	
	function consultarPessoa($voentidade, $filtro){
		return $this->consultarPessoaManter($filtro, true);
	}
	
	function consultarPessoaFiltro($filtro){
		return $this->consultarPessoaManter($filtro, false);
	}
	
    function consultarPessoaManter($filtro, $validarConsulta){
    	$atributosConsulta = vopessoa::getNmTabela() . "." .   vopessoa::$nmAtrCd;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrNome;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrDoc;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrEmail;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrTel;
    	$atributosConsulta .= "," . vopessoavinculo::getNmTabela() . "." . vopessoavinculo::$nmAtrCd;
    	
    	//$atributoVinculo = "(SELECT )"    	
    	
    	$nmTabelaContrato = vocontrato::getNmTabela();
    	$nmTabela = vopessoa::getNmTabela();
    	
        $querySelect = "SELECT ". $atributosConsulta;
        
        $queryFrom = "\n FROM ". vopessoa::getNmTabela();
        $queryFrom .= "\n INNER JOIN ". vopessoavinculo::getNmTabela();
        $queryFrom .= "\n ON ". vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCd . "=" . vopessoavinculo::getNmTabela() . "." . vopessoavinculo::$nmAtrCdPessoa;        
        
        $queryFrom .= "\n LEFT JOIN ". $nmTabelaContrato;
        $queryFrom .= "\n ON ". $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
        //echo $querySelect."<br>";
        //echo $queryFrom;
        
        return $this->consultarFiltro($filtro, $querySelect, $queryFrom, $validarConsulta);
        //return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
    }
    
    function consultarPessoaPorContrato($filtro){
    	$atributosConsulta = vopessoa::getNmTabela() . "." .   vopessoa::$nmAtrCd;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrNome;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrDoc;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrEmail;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrTel;
    	$atributosConsulta .= "," . vopessoavinculo::getNmTabela() . "." . vopessoavinculo::$nmAtrCd;
    	$atributosConsulta .= "," . vocontrato::getNmTabela() . "." . vocontrato::$nmAtrSqContrato;
    	 
    	//$atributoVinculo = "(SELECT )"
    	 
    	$querySelect = "SELECT DISTINCT ". $atributosConsulta;
    
    	$queryFrom = "\n FROM ". vopessoa::getNmTabela();
    	$queryFrom .= "\n INNER JOIN ". vopessoavinculo::getNmTabela();
    	$queryFrom .= "\n ON ". vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCd . "=" . vopessoavinculo::getNmTabela() . "." . vopessoavinculo::$nmAtrCdPessoa;
    	$queryFrom .= "\n INNER JOIN ". vocontrato::getNmTabela();
    	$queryFrom .= "\n ON ";
    	$queryFrom .= vopessoa::getNmTabela(). ".".vopessoa::$nmAtrCd. "=".vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdPessoaContratada;   	
    
    	return $this->consultarFiltro($filtro, $querySelect, $queryFrom, false);
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
	
	//o incluir eh implementado para nao usar da voentidade
	//por ser mais complexo
	function incluir($vopessoa){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
			$vopessoa = $this->incluirPessoa($vopessoa);
			//echo "<br>incluiu pessoa:" . var_dump($vopessoa);			
			$this->incluirPessoaVinculo($vopessoa);
			$this->incluirPessoaGestor($vopessoa);
			
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}
	
		return $vopessoa;
	}
		
	function incluirPessoaVinculo($vopessoa){
		$vopvinculo = new vopessoavinculo();
		$vopvinculo->cd = $vopessoa->cdVinculo;
		$vopvinculo->cdPessoa = $vopessoa->cd;
		$dbpvinculo = new dbpessoavinculo();
		$dbpvinculo->cDb = $this->cDb;
		$dbpvinculo->incluir($vopvinculo);
		//echo "<br>incluiu pessoa vinculo:" . var_dump($vopvinculo);
	}    
	
	function excluirPessoaVinculo($vopessoa){
		$vo = new vopessoavinculo();
		$nmTabela = $vo->getNmTabelaEntidade(false);
		$query = "DELETE FROM ".$nmTabela;		
		$query.= "\n WHERE ". vopessoavinculo::$nmAtrCdPessoa. " = ". $vopessoa->cd;
		
		//echo $query;
		return $this->atualizarEntidade($query);
	}
	
	function incluirPessoaGestor($vopessoa){
		$cdGestor = $vopessoa->cdGestor;
		if($cdGestor != null){
			$vopgestor = new vopessoagestor();
			$vopgestor->cdGestor = $cdGestor;
			$vopgestor->cdPessoa = $vopessoa->cd;
			$dbpgestor = new dbpessoagestor();
			$dbpgestor->cDb = $this->cDb;
			$dbpgestor->incluir($vopgestor);
		}
	}
	
	function excluirPessoaGestor($vopessoa){
		$vo = new vopessoagestor();
		$nmTabela = $vo->getNmTabelaEntidade(false);
		$query = "DELETE FROM ".$nmTabela;
		$query.= "\n WHERE ". vopessoagestor::$nmAtrCdPessoa. " = ". $vopessoa->cd;		
		//echo $query;
		return $this->atualizarEntidade($query);
	}
	
	function incluirPessoa($vopessoa){
		$vopessoa->cd = $this->getProximoSequencial(vopessoa::$nmAtrCd, $vopessoa);
	
		$arrayAtribRemover = array(
				vopessoa::$nmAtrDhInclusao,
				vopessoa::$nmAtrDhUltAlteracao
		);
	
		$query = $this->incluirQuery($vopessoa, $arrayAtribRemover);
		$retorno = $this->cDb->atualizar($query);
	
		return $vopessoa;
	}
	
	//o alterar eh implementado para nao usar da voentidade
	//por ser mais complexo
	function alterar($vopessoa){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
			$this->excluirPessoaVinculo($vopessoa);
			$this->incluirPessoaVinculo($vopessoa);
			
			$this->excluirPessoaGestor($vopessoa);
			$this->incluirPessoaGestor($vopessoa);
				
			$vopessoa = parent::alterar($vopessoa);
	
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}
	
		return $vopessoa;
	}
	
	//o alterar eh implementado para nao usar da voentidade
	//por ser mais complexo
	function excluir($vopessoa){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{									
			$this->excluirPessoaVinculo($vopessoa);
			$this->excluirPessoaGestor($vopessoa);			
			$vopessoa = parent::excluir($vopessoa);				
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}
	
		return $vopessoa;
	}
	
	function getSQLValuesInsert($vopessoa){
		$retorno = "";        
        //$retorno.= $this-> getProximoSequencial(vopessoa::$nmAtrCd, $vopessoa) . ",";
        $retorno.= $this-> getVarComoNumero($vopessoa->cd) . ",";
        $retorno.= $this-> getVarComoNumero($vopessoa->id) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->nome) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->doc) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->tel) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->email) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->endereco) . ",";
        $retorno.= $this-> getVarComoString($vopessoa->obs);
        
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
        
        if($vo->obs != null){
        	$retorno.= $sqlConector . vopessoa::$nmAtrObservacao . " = " . $this->getVarComoString($vo->obs);
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