<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once("voPA.php");
include_once("voPATramitacao.php");
include_once("vocontrato.php");
include_once("vopessoa.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes."contrato/dominioEspeciesContrato.php");
include_once (caminho_funcoes."pa/dominioSituacaoPA.php");
include_once (caminho_filtros."filtroManterPA.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbPA extends dbprocesso{
    
	function consultarPorChave($vo, $isHistorico){
        $nmTabela = $vo->getNmTabelaEntidade($isHistorico);
        
		$query = "SELECT ". $nmTabela;
        $query.= ".*,\n TAB1." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioInclusao;
        $query.= ",\n TAB2." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
        $query.= ",\n ". vopessoa::getNmTabela().".".vopessoa::$nmAtrNome;
        $query.= ",\n ". vopessoa::getNmTabela().".".vopessoa::$nmAtrDoc;
        
        $query.= " FROM ". $nmTabela;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB1 ON ";
        $query.= "TAB1.".vousuario::$nmAtrID. "=".$nmTabela . "." . voPA::$nmAtrCdUsuarioInclusao;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB2 ON ";
        $query.= "TAB2.".vousuario::$nmAtrID. "=".$nmTabela . "." . voPA::$nmAtrCdUsuarioUltAlteracao;
        $query.= "\n INNER JOIN ". vocontrato::getNmTabela();
        $query.= "\n ON ";
        $query.= $nmTabela. ".".voPA::$nmAtrCdContrato. "=".vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdContrato;
        $query.= "\n AND ";
        $query.= $nmTabela. ".".voPA::$nmAtrAnoContrato. "=".vocontrato::getNmTabela() . "." . vocontrato::$nmAtrAnoContrato;
        $query.= "\n AND ";
        $query.= $nmTabela. ".".voPA::$nmAtrTipoContrato. "=".vocontrato::getNmTabela() . "." . vocontrato::$nmAtrTipoContrato;
        $query.= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $query.= "\n ON ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdPessoaContratada. "=" . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCd;
        
        
        /*$query.= "\n LEFT JOIN ". vopessoagestor::getNmTabela();
        $query.= "\n ON ";
        $query.= vopessoagestor::getNmTabela().".".vopessoagestor::$nmAtrCdPessoa. "=".vopessoa::getNmTabela().".".vopessoa::$nmAtrCd;
        $query.= "\n LEFT JOIN ". vogestor::getNmTabela();
        $query.= "\n ON ";
        $query.= vogestor::getNmTabela().".".vogestor::$nmAtrCd. "=".vopessoagestor::getNmTabela().".".vopessoagestor::$nmAtrCdGestor;*/
        $query.= "\n WHERE ";
        $query.= "\n" . vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdEspecieContrato . " = '" . dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER . "'";
        $query.= "\n AND " . $vo->getValoresWhereSQLChave($isHistorico);
        
		//echo $query;
        $recordset = $this->consultarEntidade($query, false);
        $retorno = "";
        if($recordset != "")
        	$retorno = $recordset[0];
        	
        return $retorno ;
	}
    
    function consultarPenalidade($voentidade, $filtro){    	
    	$isHistorico = ("S" == $filtro->cdHistorico);
    	$nmTabela = $voentidade->getNmTabelaEntidade($isHistorico);
    	
    	$nmTabelaPessoaContrato = $filtro->nmTabelaPessoaContrato;
    	$nmTabelaPessoaResponsavel = $filtro->nmTabelaPessoaResponsavel;
    	
    	$atributosConsulta = "";
    	
    	$atributosConsulta .= $nmTabela . "." .   voPA::$nmAtrCdPA;    	
    	$atributosConsulta .= "," . $nmTabela . "." . voPA::$nmAtrAnoPA;
    	$atributosConsulta .= "," . $nmTabela . "." . voPA::$nmAtrCdContrato;
    	$atributosConsulta .= "," . $nmTabela . "." . voPA::$nmAtrAnoContrato;
    	$atributosConsulta .= "," . $nmTabela . "." . voPA::$nmAtrTipoContrato;
    	$atributosConsulta .= "," . $nmTabela . "." . voPA::$nmAtrSituacao;
    	$atributosConsulta .= "," . $nmTabelaPessoaResponsavel . "." . vopessoa::$nmAtrCd;
    	$atributosConsulta .= "," . $nmTabelaPessoaResponsavel . "." . vopessoa::$nmAtrNome . " AS " . $filtro->nmColNomePessoaResponsavel;
    	$atributosConsulta .= "," . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd;
    	$atributosConsulta .= "," . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc;
    	$atributosConsulta .= "," . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome . " AS " . $filtro->nmColNomePessoaContrato;
    	//traz o sqhistorico tambem
    	if($isHistorico)
    		$atributosConsulta .= "," . $nmTabela . "." .   voPA::$nmAtrSqHist;
    		 
    	/*$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrDoc;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrNome;*/
    	
        $querySelect = "SELECT ". $atributosConsulta;
                
        $queryFrom = "\n FROM ". $nmTabela;
        $queryFrom .= "\n INNER JOIN ". vocontrato::getNmTabela();
        $queryFrom .= "\n ON ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabela . "." . voPA::$nmAtrCdContrato;
        $queryFrom .= "\n AND ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voPA::$nmAtrAnoContrato;
        $queryFrom .= "\n AND ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voPA::$nmAtrTipoContrato;
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= " ". $nmTabelaPessoaContrato . " \n ON ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdPessoaContratada. "=" . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd;
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= " ". $nmTabelaPessoaResponsavel . " \n ON ". $nmTabela . "." . voPA::$nmAtrCdResponsavel . "=" . $nmTabelaPessoaResponsavel . "." . vopessoa::$nmAtrCd;
        
        /*$queryFrom = "\n FROM ". vocontrato::getNmTabela();
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= "\n ON ". vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCd . "=" . vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdPessoa;*/
        
        $filtro->cdEspecieContrato = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
        return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
    }
        
    function consultarTramitacao($voentidade){
    	$filtro = new filtroManterPA(false);
    	$filtro->TemPaginacao = false;
    	
    	$voPrincipal = new voPATramitacao();
    	$filtro->nmEntidadePrincipal = $voPrincipal->getNmClassVO();    	
    	    	
    	$filtro->cdPA = $voentidade->cdPA;
    	$filtro->anoPA = $voentidade->anoPA;
    	$querySelect = "SELECT * ";
    	$queryFrom = "\n FROM ". voPATramitacao::getNmTabela();
    
    	$retorno = $this->consultarFiltro($filtro, $querySelect, $queryFrom, false);
    	    	
    	return $retorno;
    }
    
    //o incluir eh implementado para nao usar da voentidade
    //por ser mais complexo
    function incluir($vo){
    	//Start transaction
    	$this->cDb->retiraAutoCommit();
    	try{
    		$vo = $this->incluirPA($vo);
    		$vo = $this->incluirPATramitacao($vo);
    		
    		//End transaction
    		$this->cDb->commit();
    	}catch(Exception $e){
    		$this->cDb->rollback();
    		throw new Exception($e->getMessage());
    	}
    
    	return $vo;
    }
    
    function incluirPA($vo){
    	$arrayAtribRemover = array(
    			voPA::$nmAtrDhInclusao,
    			voPA::$nmAtrDhUltAlteracao
    	);
    		
    	if($vo->cdPA == null || $vo->cdPA == ""){
    		$vo->cdPA = $this->getProximoSequencialChaveComposta(voPA::$nmAtrCdPA, $vo);
    		//echo "EH NULO";
    	}
    	$vo->situacao = dominioSituacaoPA::$CD_SITUACAO_PA_INSTAURADO;
    	if (is_array($vo->colecaoTramitacao)){    		
    		$vo->situacao = dominioSituacaoPA::$CD_SITUACAO_PA_EM_ANDAMENTO;
    	}
    	 
    	$query = $this->incluirQuery($vo, $arrayAtribRemover);
    	$retorno = $this->cDb->atualizar($query);
    
    	return $vo;
    }
    
    function incluirPATramitacao($vo){

    	if($vo->colecaoTramitacao != null){
    		//echo "tem tramitacao";
    		 
    		if (is_array($vo->colecaoTramitacao)){
    			$tamanho = sizeof($vo->colecaoTramitacao);
    			$vo->situacao = dominioSituacaoPA::$CD_SITUACAO_PA_EM_ANDAMENTO;
    		}
    		else{
    			$tamanho = 0;
    		}    		
   		
			for ($i=0;$i<$tamanho;$i++) {
				$voTramitacao = new voPATramitacao();
				$voTramitacao = $vo->colecaoTramitacao[$i];
				$voTramitacao->cdPA = $vo->cdPA;
				$voTramitacao->anoPA = $vo->anoPA;
				$voTramitacao->sq = $this->getProximoSequencialChaveComposta(voPATramitacao::$nmAtrSq, $voTramitacao); 
				$voTramitacao->cdUsuarioUltAlteracao = $vo->cdUsuarioUltAlteracao;
				
				$voTramitacao->dbprocesso->cDb = $this->cDb;
				
				//var_dump($voTramitacao);
				$voTramitacao->dbprocesso->incluir($voTramitacao);
				
				$vo->colecaoTramitacao[$i] = $voTramitacao;
    		}	    	
    	}
    	//else echo "NAO tem tramitacao";
    	
    	return $vo;
    }
	
    function excluirPATramitacao($voPA){
    	$vo = new voPATramitacao();
    	$nmTabela = $vo->getNmTabelaEntidade(false);
    	$query = "DELETE FROM ".$nmTabela;
    	$query.= "\n WHERE ". voPATramitacao::$nmAtrCdPA. " = ". $voPA->cdPA;
    	$query.= "\n AND ". voPATramitacao::$nmAtrAnoPA. " = ". $voPA->anoPA;
    	//echo $query;
    	return $this->atualizarEntidade($query);
    }
    
    //o excluir eh implementado para nao usar da voentidade
    //por ser mais complexo
    function excluir($vo){
    	//Start transaction
    	$this->cDb->retiraAutoCommit();
    	try{    		
    		$this->excluirPATramitacao($vo);
    		$vo = parent::excluir($vo);
    		//End transaction
    		$this->cDb->commit();
    	}catch(Exception $e){
    		$this->cDb->rollback();
    		throw new Exception($e->getMessage());
    	}
    
    	return $vo;
    }
    
    function alterar($vo){
    	//Start transaction
    	$this->cDb->retiraAutoCommit();
    	try{
    		$this->excluirPATramitacao($vo);
    		$vo = $this->incluirPATramitacao($vo);
    		
    		$vo = parent::alterar($vo);
    		//End transaction
    		$this->cDb->commit();
    	}catch(Exception $e){
    		$this->cDb->rollback();
    		throw new Exception($e->getMessage());
    	}
    
    	return $vo;
    }
    
    function getSQLValuesInsert($vo){
		$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vo->cdPA) . ",";
		$retorno.= $this-> getVarComoNumero($vo->anoPA) . ",";
		
		$retorno.= $this-> getVarComoNumero($vo->cdContrato) . ",";
		$retorno.= $this-> getVarComoNumero($vo->anoContrato) . ",";
		$retorno.= $this-> getVarComoString($vo->tpContrato) . ",";
		$retorno.= $this-> getVarComoString($vo->processoLic) . ",";
		$retorno.= $this-> getVarComoString($vo->obs). ",";
		$retorno.= $this-> getDataSQL($vo->dtAbertura). ",";
		$retorno.= $this-> getVarComoNumero($vo->situacao). ",";
		$retorno.= $this-> getVarComoNumero($vo->cdResponsavel);
	
		$retorno.= $vo->getSQLValuesInsertEntidade();
	
		return $retorno;
	}	
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->processoLic != null){
            $retorno.= $sqlConector . voPA::$nmAtrProcessoLicitatorio . " = " . $this->getVarComoString($vo->processoLic);
            $sqlConector = ",";
        }

        if($vo->obs != null){
            $retorno.= $sqlConector . voPA::$nmAtrObservacao . " = " . $this->getVarComoString($vo->obs);
            $sqlConector = ",";
        }
        
        if($vo->dtAbertura != null){
        	$retorno.= $sqlConector . voPA::$nmAtrDtAbertura . " = " . $this->getDataSQL($vo->dtAbertura);
        	$sqlConector = ",";
        }
        
        if($vo->cdResponsavel != null){
        	$retorno.= $sqlConector . voPA::$nmAtrCdResponsavel. " = " . $this->getVarComoNumero($vo->cdResponsavel);
        	$sqlConector = ",";
        }
        
        if($vo->situacao != null){
        	$retorno.= $sqlConector . voPA::$nmAtrSituacao. " = " . $this->getVarComoNumero($vo->situacao);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>