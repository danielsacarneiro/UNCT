<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once("voPAD.php");
include_once("voPADTramitacao.php");
include_once("vocontrato.php");
include_once("vopessoa.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes."contrato/dominioEspeciesContrato.php");
include_once (caminho_funcoes."pad/dominioSituacaoPAD.php");
include_once (caminho_filtros."filtroManterPAD.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbPAD extends dbprocesso{
    
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
        $query.= "TAB1.".vousuario::$nmAtrID. "=".$nmTabela . "." . voPAD::$nmAtrCdUsuarioInclusao;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB2 ON ";
        $query.= "TAB2.".vousuario::$nmAtrID. "=".$nmTabela . "." . voPAD::$nmAtrCdUsuarioUltAlteracao;
        $query.= "\n INNER JOIN ". vocontrato::getNmTabela();
        $query.= "\n ON ";
        $query.= $nmTabela. ".".voPAD::$nmAtrCdContrato. "=".vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdContrato;
        $query.= "\n AND ";
        $query.= $nmTabela. ".".voPAD::$nmAtrAnoContrato. "=".vocontrato::getNmTabela() . "." . vocontrato::$nmAtrAnoContrato;
        $query.= "\n AND ";
        $query.= $nmTabela. ".".voPAD::$nmAtrTipoContrato. "=".vocontrato::getNmTabela() . "." . vocontrato::$nmAtrTipoContrato;
        $query.= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $query.= "\n ON ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdPessoaContratada. "=" . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCd;
        
        
        /*$query.= "\n LEFT JOIN ". vopessoagestor::getNmTabela();
        $query.= "\n ON ";
        $query.= vopessoagestor::getNmTabela().".".vopessoagestor::$nmAtrCdPessoa. "=".vopessoa::getNmTabela().".".vopessoa::$nmAtrCd;
        $query.= "\n LEFT JOIN ". vogestor::getNmTabela();
        $query.= "\n ON ";
        $query.= vogestor::getNmTabela().".".vogestor::$nmAtrCd. "=".vopessoagestor::getNmTabela().".".vopessoagestor::$nmAtrCdGestor;*/
        $query.= "\n WHERE ";
        $query.= "\n" . vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdEspecieContrato . " = " . dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
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
    	
    	$atributosConsulta = "";
    	
    	$atributosConsulta .= $nmTabela . "." .   voPAD::$nmAtrCdPA;    	
    	$atributosConsulta .= "," . $nmTabela . "." . voPAD::$nmAtrAnoPA;
    	$atributosConsulta .= "," . $nmTabela . "." . voPAD::$nmAtrCdContrato;
    	$atributosConsulta .= "," . $nmTabela . "." . voPAD::$nmAtrAnoContrato;
    	$atributosConsulta .= "," . $nmTabela . "." . voPAD::$nmAtrTipoContrato;
    	$atributosConsulta .= "," . $nmTabela . "." . voPAD::$nmAtrSituacao;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrDoc;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrNome;
    	//traz o sqhistorico tambem
    	if($isHistorico)
    		$atributosConsulta .= "," . $nmTabela . "." .   voPAD::$nmAtrSqHist;
    		 
    	/*$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrDoc;
    	$atributosConsulta .= "," . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrNome;*/
    	
        $querySelect = "SELECT ". $atributosConsulta;
                
        $queryFrom = "\n FROM ". $nmTabela;
        $queryFrom .= "\n INNER JOIN ". vocontrato::getNmTabela();
        $queryFrom .= "\n ON ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabela . "." . voPAD::$nmAtrCdContrato;
        $queryFrom .= "\n AND ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voPAD::$nmAtrAnoContrato;
        $queryFrom .= "\n AND ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voPAD::$nmAtrTipoContrato;
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= "\n ON ". vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdPessoaContratada. "=" . vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCd;
        
        /*$queryFrom = "\n FROM ". vocontrato::getNmTabela();
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= "\n ON ". vopessoa::getNmTabela() . "." . vopessoa::$nmAtrCd . "=" . vocontrato::getNmTabela() . "." . vocontrato::$nmAtrCdPessoa;*/
        
        $filtro->cdEspecieContrato = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
        return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
    }
        
    function consultarTramitacao($voentidade){
    	$filtro = new filtroManterPAD(false);
    	$filtro->TemPaginacao = false;
    	
    	$voPrincipal = new voPADTramitacao();
    	$filtro->nmEntidadePrincipal = $voPrincipal->getNmClassVO();
    	
    	    	
    	$filtro->cdPA = $voentidade->cdPA;
    	$filtro->anoPA = $voentidade->anoPA;
    	$querySelect = "SELECT * ";
    	$queryFrom = "\n FROM ". voPADTramitacao::getNmTabela();
    
    	$retorno = $this->consultarFiltro($filtro, $querySelect, $queryFrom, false);
    	    	
    	return $retorno;
    }
    
    //o incluir eh implementado para nao usar da voentidade
    //por ser mais complexo
    function incluir($vo){
    	//Start transaction
    	$this->cDb->retiraAutoCommit();
    	try{
    		$vo = $this->incluirPAD($vo);
    		$this->incluirPADTramitacao($vo);
    		//End transaction
    		$this->cDb->commit();
    	}catch(Exception $e){
    		$this->cDb->rollback();
    		throw new Exception($e->getMessage());
    	}
    
    	return $vo;
    }
    
    function incluirPAD($vo){
    	$arrayAtribRemover = array(
    			voPAD::$nmAtrDhInclusao,
    			voPAD::$nmAtrDhUltAlteracao
    	);
    		
    	if($vo->cdPA == null || $vo->cdPA == ""){
    		$vo->cdPA = $this->getProximoSequencialChaveComposta(voPAD::$nmAtrCdPA, $vo);
    		//echo "EH NULO";
    	}
    	$vo->situacao = dominioSituacaoPAD::$CD_SITUACAO_PAD_INSTAURADO;
    	 
    	$query = $this->incluirQuery($vo, $arrayAtribRemover);
    	$retorno = $this->cDb->atualizar($query);
    
    	return $vo;
    }
    
    function incluirPADTramitacao($vo){

    	if($vo->colecaoTramitacao != null){
    		//echo "tem tramitacao";
    		 
    		if (is_array($vo->colecaoTramitacao)){
    			$tamanho = sizeof($vo->colecaoTramitacao);
    			$vo->situacao = $vo->situacao = dominioSituacaoPAD::$CD_SITUACAO_PAD_EM_ANDAMENTO;    			
    		}
    		else{
    			$tamanho = 0;
    		}    		
   		
			for ($i=0;$i<$tamanho;$i++) {
				$voTramitacao = new voPADTramitacao();
				$voTramitacao = $vo->colecaoTramitacao[$i];
				$voTramitacao->cdPA = $vo->cdPA;
				$voTramitacao->anoPA = $vo->anoPA;
				$voTramitacao->sq = $this->getProximoSequencialChaveComposta(voPADTramitacao::$nmAtrSq, $voTramitacao); 
				
				$voTramitacao->dbPADTramitacao->cDb = $this->cDb;
				
				//var_dump($voTramitacao);
				$voTramitacao->dbPADTramitacao->incluir($voTramitacao);
				
				$vo->colecaoTramitacao[$i] = $voTramitacao;
    		}	    	
    	}
    	//else echo "NAO tem tramitacao";
    	
    	return $vo;
    }
	
    function excluirPADTramitacao($voPAD){
    	$vo = new voPADTramitacao();
    	$nmTabela = $vo->getNmTabelaEntidade(false);
    	$query = "DELETE FROM ".$nmTabela;
    	$query.= "\n WHERE ". voPADTramitacao::$nmAtrCdPA. " = ". $voPAD->cdPA;
    	$query.= "\n AND ". voPADTramitacao::$nmAtrAnoPA. " = ". $voPAD->anoPA;
    	//echo $query;
    	return $this->atualizarEntidade($query);
    }
    
    //o incluir eh implementado para nao usar da voentidade
    //por ser mais complexo
    function excluir($vo){
    	//Start transaction
    	$this->cDb->retiraAutoCommit();
    	try{    		
    		$this->excluirPADTramitacao($vo);
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
    		$this->excluirPADTramitacao($vo);
    		$this->incluirPADTramitacao($vo);
    		
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
		$retorno.= $this-> getVarComoNumero($vo->situacao);
	
		$retorno.= $vo->getSQLValuesInsertEntidade();
	
		return $retorno;
	}	
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->processoLic != null){
            $retorno.= $sqlConector . voPAD::$nmAtrProcessoLicitatorio . " = " . $this->getVarComoString($vo->processoLic);
            $sqlConector = ",";
        }

        if($vo->obs != null){
            $retorno.= $sqlConector . voPAD::$nmAtrObservacao . " = " . $this->getVarComoString($vo->obs);
            $sqlConector = ",";
        }
        
        if($vo->dtAbertura != null){
        	$retorno.= $sqlConector . voPAD::$nmAtrDtAbertura . " = " . $this->getDataSQL($vo->dtAbertura);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>