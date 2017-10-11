<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes."contrato/dominioEspeciesContrato.php");
include_once (caminho_funcoes."pa/dominioSituacaoPA.php");
include_once (caminho_filtros."filtroManterPA.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbPA extends dbprocesso{
    
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
  		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
  		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato,
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrTexto
//  				$nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato,  
  		);
  	
  		$queryFrom .= "\n INNER JOIN ". $nmTabelaDemanda;
  		$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabela . "." . voPA::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voPA::$nmAtrAnoDemanda;
  		
  		$queryFrom .= "\n INNER JOIN ". $nmTabelaDemandaContrato;
  		$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda; 
  		
  		/*$queryFrom .= "\n LEFT JOIN " . $nmTabelaContrato;
  		$queryFrom .= "\n ON ";
  		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
  		$queryFrom .= "\n AND ";
  		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
  		$queryFrom .= "\n AND ";
  		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
  		$queryFrom .= "\n AND ";
  		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
  		$queryFrom .= "\n AND ";
  		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;*/
  		
  		
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $isHistorico );
  	}
    
    function consultarPAAP($vo, $filtro){    	
    	$isHistorico = ("S" == $filtro->cdHistorico);
    	$nmTabela = $vo->getNmTabelaEntidade($isHistorico);    	
    	$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
    	$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
    	$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
    	$nmTabelaPessoaContrato = $filtro->nmTabelaPessoaContrato;
    	$nmTabelaPessoaResponsavel = $filtro->nmTabelaPessoaResponsavel;
    	
    	$colunaUsuHistorico = "";
    	
    	if ($isHistorico) {
    		$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
    	}
    	$arrayColunasRetornadas = array (
    			$nmTabela . ".*",
    			$nmTabelaPessoaResponsavel . "." . vopessoa::$nmAtrCd,
    			$nmTabelaPessoaResponsavel . "." . vopessoa::$nmAtrNome . " AS " . $filtro->nmColNomePessoaResponsavel,    			 
    			$nmTabelaContrato. "." . vocontrato::$nmAtrTipoContrato,
    			$nmTabelaContrato. "." . vocontrato::$nmAtrAnoContrato,
    			$nmTabelaContrato. "." . vocontrato::$nmAtrCdContrato,
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd,
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome . " AS " . $filtro->nmColNomePessoaContrato,    			 
    			$colunaUsuHistorico
    	);

    	$queryFrom .= "\n INNER JOIN ". $nmTabelaDemanda;
    	$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabela . "." . voPA::$nmAtrCdDemanda;
    	$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voPA::$nmAtrAnoDemanda;
    	 
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
    	
    	// agora pega dos dados da ultima tramitacao, se houver
    	$queryFrom .= "\n LEFT JOIN ";
    	$queryFrom .= $nmTabelaContrato;
    	$queryFrom .= "\n ON " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . " = $nmTabelaMAXContrato." . vocontrato::$nmAtrSqContrato;    	 
    	 
        /*$queryFrom .= "\n INNER JOIN ". $nmTabelaContrato;
        $queryFrom .= "\n ON ". $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato;
        $queryFrom .= "\n AND ". $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato;
        $queryFrom .= "\n AND ". $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato;*/
        
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= " ". $nmTabelaPessoaContrato . " \n ON ". $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada. "=" . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd;
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= " ". $nmTabelaPessoaResponsavel . " \n ON ". $nmTabela . "." . voPA::$nmAtrCdResponsavel . "=" . $nmTabelaPessoaResponsavel . "." . vopessoa::$nmAtrCd;
                
        //$filtro->cdEspecieContrato = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
        
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
    
    function getSQLValuesInsert($vo){
    	if($vo->cdPA == null){
    		$vo->cdPA = $this->getProximoSequencialChaveComposta (voPA::$nmAtrCdPA, $vo );
    	}
    	
		$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vo->cdPA) . ",";
		$retorno.= $this-> getVarComoNumero($vo->anoPA) . ",";
		
		$retorno.= $this-> getVarComoNumero($vo->anoDemanda) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdDemanda) . ",";

		$retorno.= $this-> getVarComoNumero($vo->cdResponsavel). ",";
		$retorno.= $this-> getVarComoString($vo->obs). ",";
		$retorno.= $this-> getVarComoData($vo->dtAbertura). ",";
		$retorno.= $this-> getVarComoData($vo->dtNotificacao). ",";
		$retorno.= $this-> getVarComoNumero($vo->situacao);		
	
		$retorno.= $vo->getSQLValuesInsertEntidade();
	
		return $retorno;
	}	
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->obs != null){
            $retorno.= $sqlConector . voPA::$nmAtrObservacao . " = " . $this->getVarComoString($vo->obs);
            $sqlConector = ",";
        }
        
        if($vo->dtAbertura != null){
        	$retorno.= $sqlConector . voPA::$nmAtrDtAbertura . " = " . $this->getVarComoData($vo->dtAbertura);
        	$sqlConector = ",";
        }
        
        if($vo->dtNotificacao != null){
        	$retorno.= $sqlConector . voPA::$nmAtrDtNotificacao . " = " . $this->getVarComoData($vo->dtNotificacao);
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