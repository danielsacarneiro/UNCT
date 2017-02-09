<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once("vopenalidade.php");
include_once("vocontrato.php");
include_once("vopessoa.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbpenalidade extends dbprocesso{
    
	function consultarPorChave($vo, $isHistorico){
        $nmTabela = $vo->getNmTabelaEntidade($isHistorico);
        
		$query = "SELECT ". $nmTabela;
        $query.= ".*, TAB1." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioInclusao;
        $query.= ", TAB2." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
        
        $query.= " FROM ". $nmTabela;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB1 ON ";
        $query.= "TAB1.".vousuario::$nmAtrID. "=".vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrCdUsuarioInclusao;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB2 ON ";
        $query.= "TAB2.".vousuario::$nmAtrID. "=".vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrCdUsuarioUltAlteracao;
        /*$query.= "\n INNER JOIN ". vopessoavinculo::getNmTabela();
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
        $query.= vogestor::getNmTabela().".".vogestor::$nmAtrCd. "=".vopessoagestor::getNmTabela().".".vopessoagestor::$nmAtrCdGestor;*/
        $query.= " WHERE ";
        $query.= $vo->getValoresWhereSQLChave($isHistorico);
        
		//echo $query;
        $recordset = $this->consultarEntidade($query, false);
        $retorno = "";
        if($recordset != "")
        	$retorno = $recordset[0];
        	
        return $retorno ;
	}
    
    function consultarPenalidade($voentidade, $filtro){
    	
    	$atributosConsulta = "";
    	$atributosConsulta .= vopenalidade::getNmTabela() . "." .   vopenalidade::$nmAtrCd;
    	$atributosConsulta .= "," . vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrCdPA;
    	$atributosConsulta .= "," . vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrAnoPA;
    	$atributosConsulta .= "," . vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrCdContrato;
    	$atributosConsulta .= "," . vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrAnoContrato;
    	$atributosConsulta .= "," . vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrTipoContrato;
    	
    	/*$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrDoc;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrNome;*/
    	
        $querySelect = "SELECT ". $atributosConsulta;
        
        static $nmAtrCdContrato  = "ct_numero";
        static $nmAtrAnoContrato  = "ct_exercicio";
        static $nmAtrTipoContrato =  "ct_tipo";
        
        
        $queryFrom = "\n FROM ". vopenalidade::getNmTabela();
        $queryFrom .= "\n INNER JOIN ". vocontrato::getNmTabela();
        $queryFrom .= "\n ON ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdContrato . "=" . vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrCdContrato;
        $queryFrom .= "\n AND ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrAnoContrato . "=" . vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrAnoContrato;
        $queryFrom .= "\n AND ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrTipoContrato . "=" . vopenalidade::getNmTabela() . "." . vopenalidade::$nmAtrTipoContrato;
                
        /*$queryFrom = "\n FROM ". vocontrato::getNmTabela();
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= "\n ON ". vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCd . "=" . vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdPessoa;*/
        
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
	
	function incluirSQL($vopenalidade){
		$arrayAtribRemover = array(				
				vopenalidade::$nmAtrDhInclusao,
				vopenalidade::$nmAtrDhUltAlteracao
		);
	
		$vopenalidade->cd = $this->getProximoSequencialChaveComposta(vopenalidade::$nmAtrCd, $vopenalidade);
	
		return $this->incluirQuery($vopenalidade, $arrayAtribRemover);
	}
	
	function getSQLValuesInsert($vopenalidade){
		$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vopenalidade->cd) . ",";
		$retorno.= $this-> getVarComoNumero($vopenalidade->cdPA) . ",";
		$retorno.= $this-> getVarComoNumero($vopenalidade->anoPA) . ",";
		
		$retorno.= $this-> getVarComoNumero($vopenalidade->cdContrato) . ",";
		$retorno.= $this-> getVarComoNumero($vopenalidade->anoContrato) . ",";
		$retorno.= $this-> getVarComoString($vopenalidade->tpContrato) . ",";
		$retorno.= $this-> getVarComoString($vopenalidade->processoLic) . ",";
		$retorno.= $this-> getVarComoString($vopenalidade->obs);
	
		$retorno.= $vopenalidade->getSQLValuesInsertEntidade();
	
		return $retorno;
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