<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes."pa/dominioSituacaoPA.php");
include_once (caminho_filtros."filtroManterPA.php");

// .................................................................................................................
  Class dbPenalidadePA extends dbprocesso{
    
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
  		$nmTabelaDemandaDocumento = voDemandaTramDoc::getNmTabelaStatic ( false );
  		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );  		
  		$nmTabelaPA = voPA::getNmTabelaStatic ( false );
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato,
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrCd,
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrAno,
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrTexto,
  				//define se a penalidade tem anexo de publicacao ou nao
  				"if(".voDemandaTramDoc::$nmAtrTpDoc." IS NULL,".getVarComoString(constantes::$CD_NAO).",".getVarComoString(constantes::$CD_SIM).")"
  				. " AS " . voPenalidadePA::$NM_COL_inTemPublicacao,
  		);
  	
  		$queryFrom .= "\n INNER JOIN ". $nmTabelaPA;
  		$queryFrom .= "\n ON ". $nmTabelaPA . "." . voPA::$nmAtrCdPA. "=" . $nmTabela . "." . voPenalidadePA::$nmAtrCdPA;
  		$queryFrom .= "\n AND ". $nmTabelaPA . "." . voPA::$nmAtrAnoPA . "=" . $nmTabela . "." . voPenalidadePA::$nmAtrAnoPA;
  		
  		$queryFrom .= "\n INNER JOIN ". $nmTabelaDemanda;
  		$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabelaPA . "." . voPA::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaPA . "." . voPA::$nmAtrAnoDemanda;
  		
  		$queryFrom .= "\n INNER JOIN ". $nmTabelaDemandaContrato;
  		$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda; 
		
  		$NM_TAB_TEMP_DEM_DOC = "TAB_DEMANDA_DOC";
  		$listaAtributos = voDemandaTramDoc::$nmAtrAnoDemanda . "," . voDemandaTramDoc::$nmAtrCdDemanda;
  		$queryFrom .= "\n LEFT JOIN (";
  		$queryFrom .= "SELECT $listaAtributos,". voDemandaTramDoc::$nmAtrTpDoc." FROM $nmTabelaDemandaDocumento ";
  		$queryFrom .= "\n WHERE ". voDemandaTramDoc::$nmAtrTpDoc . " = " . getVarComoString(dominioTpDocumento::$CD_TP_DOC_PUBLICACAO_PAAP);
  		$queryFrom .= "\n GROUP BY ". $listaAtributos;
  		$queryFrom .= ") " . $NM_TAB_TEMP_DEM_DOC;
  		$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $NM_TAB_TEMP_DEM_DOC . "." . voDemandaContrato::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $NM_TAB_TEMP_DEM_DOC . "." . voDemandaContrato::$nmAtrAnoDemanda;
  		
  		$queryWhere = " WHERE ";  		
  		$queryWhere .= $vo->getValoresWhereSQLChave ( $isHistorico );
  		//$queryWhere .= " AND " . voDemandaTramDoc::$nmAtrTpDoc . " = " . getVarComoString(dominioTpDocumento::$CD_TP_DOC_PUBLICACAO_PAAP);
  		
  		return $this->consultarMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $queryWhere, $isHistorico, true );  		
  		//return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $isHistorico );
  	}
    
    function consultarPenalidadeTelaConsulta($vo, $filtro){    	
    	$isHistorico = $filtro->isHistorico();
    	$nmTabelaPenalidade = $vo->getNmTabelaEntidade($isHistorico);
    	$nmTabelaPA = voPA::getNmTabelaStatic(false);
    	$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
    	$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
    	$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
    	$nmTabelaPessoaContrato = $filtro->nmTabelaPessoaContrato;
    	
    	$colunaUsuHistorico = "";
    	
    	if ($isHistorico) {
    		$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
    	}
    	$arrayColunasRetornadas = array (
    			$nmTabelaPenalidade . ".*",
    			$nmTabelaContrato. "." . vocontrato::$nmAtrTipoContrato,
    			$nmTabelaContrato. "." . vocontrato::$nmAtrAnoContrato,
    			$nmTabelaContrato. "." . vocontrato::$nmAtrCdContrato,
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd,
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome . " AS " . $filtro->nmColNomePessoaContrato,    			 
    			$colunaUsuHistorico
    	);

    	$queryFrom .= "\n INNER JOIN ". $nmTabelaPA;
    	$queryFrom .= "\n ON ". $nmTabelaPenalidade . "." . voPenalidadePA::$nmAtrCdPA. "=" . $nmTabelaPA . "." . voPA::$nmAtrCdPA;
    	$queryFrom .= "\n AND ". $nmTabelaPenalidade . "." . voPenalidadePA::$nmAtrAnoPA . "=" . $nmTabelaPA . "." . voPA::$nmAtrAnoPA;
    	 
    	$queryFrom .= "\n INNER JOIN ". $nmTabelaDemanda;
    	$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabelaPA . "." . voPA::$nmAtrCdDemanda;
    	$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaPA . "." . voPA::$nmAtrAnoDemanda;
    	 
    	$queryFrom .= "\n INNER JOIN ". $nmTabelaDemandaContrato;
    	$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
    	$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
    	
    	// o proximo join eh p pegar o registro de contrato mais atual na planilha
    	//faz o join apenas com os contratos de maximo sequencial (mais atual)
    	$nmTabelaMAXContrato = "TABELA_MAX_CONTRATO";
    	$atributosGroupContrato = vocontrato::$nmAtrAnoContrato . "," . vocontrato::$nmAtrTipoContrato . "," . vocontrato::$nmAtrCdContrato;
    	$queryFrom .= "\n LEFT JOIN (";
    	$queryFrom .= " SELECT MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato
    	. "," . $atributosGroupContrato . " FROM " . $nmTabelaContrato
    	. " GROUP BY " . $atributosGroupContrato;
    	$queryFrom .= ") $nmTabelaMAXContrato";
    	$queryFrom .= "\n ON ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrAnoContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrTipoContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrCdContrato;
    	
    	$queryFrom .= "\n LEFT JOIN ";
    	$queryFrom .= $nmTabelaContrato;
    	$queryFrom .= "\n ON " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . " = $nmTabelaMAXContrato." . vocontrato::$nmAtrSqContrato;    	 
        
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= " ". $nmTabelaPessoaContrato . " \n ON ". $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada. "=" . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd;
                
        return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryFrom );        
    }
    
    function consultarDemandaPAAP($filtro) {
    	$isHistorico = $filtro->isHistorico;
    	$vo = new voDemanda();
    	$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
    	$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela ();
    	$nmTabelaDemandaContrato = voDemandaContrato::getNmTabela ();
    	$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
    	$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
    	$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
    	$nmTabelaPA = voPA::getNmTabelaStatic ( false );
    
    	$colunaUsuHistorico = "";
    
    	if ($isHistorico) {
    		$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
    	}
    	$arrayColunasRetornadas = array (
    			$nmTabela . ".*",
    			"COUNT(*)  AS " . filtroManterDemanda::$NmColQtdContratos,
    			static::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioInclusao,
    			$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
    			$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
    			$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,    
    			$nmTabelaPA . "." . voPA::$nmAtrDtNotificacao,
    			// $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . " AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
    			"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "," . $nmTabela . "." . voDemanda::$nmAtrCdSetor . ") AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
    			"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrDhInclusao . "," . $nmTabela . "." . voDemanda::$nmAtrDhUltAlteracao . ") AS " . filtroManterDemanda::$NmColDhUltimaMovimentacao,
    			$colunaUsuHistorico
    	);
    
    	$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno;
    
    	// o proximo join eh p pegar a ultima tramitacao apenas, se houver
    	$nmTabelaMAXTramitacao = "TABELA_MAX";
    	$queryJoin = "";
    	$queryJoin .= "\n LEFT JOIN (";
    	$queryJoin .= " SELECT MAX(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq
    	. "," . $atributosGroup . " FROM " . $nmTabelaTramitacao
    	. " GROUP BY " . $atributosGroup;
    	$queryJoin .= ") $nmTabelaMAXTramitacao";
    	$queryJoin .= "\n ON " . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrAno;
    	$queryJoin .= "\n AND " . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrCd;
    
    	// agora pega dos dados da ultima tramitacao, se houver
    	$queryJoin .= "\n LEFT JOIN ";
    	$queryJoin .= $nmTabelaTramitacao;
    	$queryJoin .= "\n ON " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrAno;
    	$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrCd;
    	$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrSq . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrSq;
    
    	$queryJoin .= "\n LEFT JOIN ";
    	$queryJoin .= $nmTabelaDemandaContrato;
    	$queryJoin .= "\n ON " . $nmTabela . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
    	$queryJoin .= "\n AND " . $nmTabela . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
    
    	// o proximo join eh p pegar o registro de contrato mais atual na planilha
    	//faz o join apenas com os contratos de maximo sequencial (mais atual)
    	$nmTabelaMAXContrato = "TABELA_MAX_CONTRATO";
    	$atributosGroupContrato = vocontrato::$nmAtrAnoContrato . "," . vocontrato::$nmAtrTipoContrato . "," . vocontrato::$nmAtrCdContrato;
    	$queryJoin .= "\n LEFT JOIN (";
    	$queryJoin .= " SELECT MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato
    	. "," . $atributosGroupContrato . " FROM " . $nmTabelaContrato
    	. " GROUP BY " . $atributosGroupContrato;
    	$queryJoin .= ") $nmTabelaMAXContrato";
    	$queryJoin .= "\n ON ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrAnoContrato;
    	$queryJoin .= "\n AND ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrTipoContrato;
    	$queryJoin .= "\n AND ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrCdContrato;
    
    	// agora pega dos dados da ultima tramitacao, se houver
    	$queryJoin .= "\n LEFT JOIN ";
    	$queryJoin .= $nmTabelaContrato;
    	$queryJoin .= "\n ON " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . " = $nmTabelaMAXContrato." . vocontrato::$nmAtrSqContrato;
        
    	$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
    	$queryJoin .= "\n ON ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato;
    	$queryJoin .= "\n AND ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato;
    	$queryJoin .= "\n AND ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato;
    
    	$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
    	$queryJoin .= "\n ON ";
    	$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
    	
    	$queryJoin .= "\n LEFT JOIN ";
    	$queryJoin .= $nmTabelaPA;
    	$queryJoin .= "\n ON " . $nmTabela . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaPA . "." . voPA::$nmAtrAnoDemanda;
    	$queryJoin .= "\n AND " . $nmTabela . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaPA . "." . voPA::$nmAtrCdDemanda;    	 
    
    	$arrayGroupby = array (
    			$nmTabela . "." . voDemanda::$nmAtrAno,
    			$nmTabela . "." . voDemanda::$nmAtrCd
    	);
    
    	if ($isHistorico) {
    		$arrayGroupby [] = voentidade::$nmAtrSqHist;
    	}
    
    	$filtro->groupby = $arrayGroupby;
    
    	return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
    }
    
    function validar($vo) {
    	$validar = $vo->validarFundamento();
    	if (!$validar) {  
    		throw new excecaoGenerica("Verifique as palavras chave para o fundamento da penalidade: " . voPenalidadePA::getPalavraChaveFundamento());    		
    	}
    }
    
    function alterar($vo) {
    	$this->validar($vo);
    	 
    	parent::alterar ( $vo );
    }
    
    function incluir($vo) {
    	$this->validar($vo);
    	
    	parent::incluir ( $vo );
    }    
    
    function getSQLValuesInsert($vo){
    	if($vo->sq == null){
    		$vo->sq = $this->getProximoSequencialChaveComposta (voPenalidadePA::$nmAtrSq, $vo );
    	}
    	
		$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vo->cdPA) . ",";
		$retorno.= $this-> getVarComoNumero($vo->anoPA) . ",";
		$retorno.= $this-> getVarComoNumero($vo->sq) . ",";
		
		$retorno.= $this-> getVarComoNumero($vo->tipo). ",";
		$retorno.= $this-> getVarComoString($vo->obs). ",";
		$retorno.= $this-> getVarComoString($vo->fundamento). ",";
		$retorno.= $this-> getVarComoData($vo->dtAplicacao);
	
		$retorno.= $vo->getSQLValuesInsertEntidade();
	
		return $retorno;
	}	
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->obs != null){
            $retorno.= $sqlConector . voPenalidadePA::$nmAtrObservacao . " = " . $this->getVarComoString($vo->obs);
            $sqlConector = ",";
        }
        
        if($vo->fundamento != null){
        	$retorno.= $sqlConector . voPenalidadePA::$nmAtrFundamento . " = " . $this->getVarComoString($vo->fundamento);
        	$sqlConector = ",";
        }
        
        if($vo->dtAplicacao != null){
        	$retorno.= $sqlConector . voPenalidadePA::$nmAtrDtAplicacao . " = " . $this->getVarComoData($vo->dtAplicacao);
        	$sqlConector = ",";
        }
                
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>