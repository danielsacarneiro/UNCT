<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes."contrato/dominioEspeciesContrato.php");
include_once (caminho_funcoes."pa/dominioSituacaoPA.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbProcLicitatorio extends dbprocesso{
  	static $nmTabelaPublicacao = "nmTabelaPublicacao";
    
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",  
  		);
  		  		  		 		
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $isHistorico );
  	}
    
    function consultarTelaConsulta($vo, $filtro){    	
    	$isHistorico = ("S" == $filtro->cdHistorico);
    	$nmTabela = $vo->getNmTabelaEntidade($isHistorico);    	
    	$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
    	$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
    	$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
    	$nmTabelaDemandaPL = voDemandaPL::getNmTabelaStatic(false);
    	//$nmTabelaPessoaContrato = $filtro->nmTabelaPessoaContrato;
    	$nmTabelaPessoaPregoeiro = filtroManterProcLicitatorio::$nmTabelaPregoeiro;
    	
    	$colunaUsuHistorico = "";
    	
    	if ($isHistorico) {
    		$sqHist = $nmTabela . "." . voPA::$nmAtrSqHist;
    		$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voProcLicitatorio::$nmAtrNmUsuarioOperacao;
    	}
    	$arrayColunasRetornadas = array (
    			$nmTabela . ".*",
    			$nmTabelaDemanda . "." . voDemanda::$nmAtrSituacao,
    			$nmTabelaPessoaPregoeiro . "." . vopessoa::$nmAtrCd,
    			$nmTabelaPessoaPregoeiro . "." . vopessoa::$nmAtrNome . " AS " . filtroManterProcLicitatorio::$nmColNomePregoeiro,    			 
    			$colunaUsuHistorico,
    			$sqHist
    	);
    	        
    	$queryJoin .= "\n LEFT JOIN ". vopessoa::getNmTabela();
    	$queryJoin .= " ". $nmTabelaPessoaPregoeiro . " \n ON ". $nmTabela . "." . voProcLicitatorio::$nmAtrCdPregoeiro . "=" . $nmTabelaPessoaPregoeiro . "." . vopessoa::$nmAtrCd;
        
        $queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaPL;
        $queryJoin .= "\n ON ";
        $queryJoin .= $nmTabela . "." . voProcLicitatorio::$nmAtrAno . "=" . $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrAnoProcLic;
        $queryJoin .= "\n AND " . $nmTabela . "." . voProcLicitatorio::$nmAtrCd . "=" . $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrCdProcLic;
                
        $queryJoin .= "\n LEFT JOIN " . $nmTabelaDemanda;
        $queryJoin .= "\n ON ";
        $queryJoin .= $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrAnoDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
        $queryJoin .= "\n AND " . $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrCdDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
        
        //$filtro->cdEspecieContrato = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
        
        return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );        
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
    			self::$nmTabelaPublicacao . "." . voDemandaTramitacao::$nmAtrDtReferencia,
    			$colunaUsuHistorico,
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
    	
    	//pega a data da publicacao para o caso de calcular quando deve emitir o alerta
    	$queryJoin .= $this->getSQLJoinDataPublicacaoPAAP($nmTabela);
    
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
    
    function consultarContratoPAAP($vo) {
    	$isHistorico = $vo->isHistorico ();
    	
    	$nmTabelaPAAP = voPA::getNmTabelaStatic ( $isHistorico );
    	$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( $isHistorico );
    	$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
    	$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
    	$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
    
    	$querySelect = "SELECT ";
    	$querySelect .= $nmTabelaPAAP . ".*,";
    	$querySelect .= $nmTabelaDemandaContrato . ".*";
    	$queryFrom = " FROM " . $nmTabelaPAAP;
    
    	$queryFrom .= "\n INNER JOIN ";
    	$queryFrom .= $nmTabelaDemanda;
    	$queryFrom .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaPAAP . "." . voPA::$nmAtrAnoDemanda;
    	$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaPAAP . "." . voPA::$nmAtrCdDemanda;
    
    	$queryFrom .= "\n INNER JOIN ";
    	$queryFrom .= $nmTabelaDemandaContrato;
    	$queryFrom .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
    	$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
    	 
    	$queryFrom .= "\n LEFT JOIN " . $nmTabelaContrato;
    	$queryFrom .= "\n ON ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
    
    	$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
    	$queryFrom .= "\n ON ";
    	$queryFrom .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
    
    	$filtro = new filtroManterPA(false );
    	// var_dump($vo);
    	$filtro->anoPA = $vo->anoPA;
    	$filtro->cdPA = $vo->cdPA;
    	$filtro->sqHistPA = $vo->sqHist;
    	
    	$filtro->TemPaginacao = false;
    	$filtro->isHistorico = $isHistorico;
    
    	return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
    }
    
    function consultarPenalidade($vo) {
    	//$vo = new voPA();
    	$dbpenalidade = new dbPenalidadePA();    	
    	$filtro = new filtroManterPenalidade(false, false);
    	$vopenalidade = new voPenalidadePA();
    	$filtro->voPrincipal = $vopenalidade;
    	$filtro->inDesativado = "N";
    	//$filtro->TemPaginacao = false;
    	
    	$filtro->anoPA = $vo->anoPA;
    	$filtro->cdPA = $vo->cdPA;
    	$colecao = $dbpenalidade->consultarPenalidadeTelaConsulta($vopenalidade, $filtro);
    	return $colecao;
    }
    
    function consultarVODemanda($vo) {    	
    	//$vo = new voPA();
    	$vodemanda = new voDemanda(array($vo->anoDemanda, $vo->cdDemanda));
    	$vodemanda = $vodemanda->dbprocesso->consultarPorChaveVO($vodemanda, false);
    	return $vodemanda;
    }
    
    function temPenalidade($vo) {
    	$colecao = $this->consultarPenalidade($vo);
    	return !isColecaoVazia($colecao);
    }
    
    function validarAlteracao($vo) {
    	//$vo = new voPA();    	
    	//$retorno = true;    	
    	$vodemanda = $this->consultarVODemanda($vo);
    	//$vodemanda = new voDemanda();
    	$isDemandaFechada = $vodemanda->situacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA; 

    	if(dominioSituacaoPA::existeItem($vo->situacao, dominioSituacaoPA::getColecaoSituacaoTerminados())){    		
    		if(!$isDemandaFechada){
    			throw new excecaoGenerica("Só é permitido terminar o processo cuja demanda esteja concluída.");
    		}
    	}
    	
    	if($vo->situacao == dominioSituacaoPA::$CD_SITUACAO_PA_ENCERRADO){
    		$temPenalidade = $this->temPenalidade($vo);
    		if(!$temPenalidade){
    			throw new excecaoGenerica("Só é permitido encerrar o processo que possua penalidades cadastradas.");
    		}
    		    		
    		$registro = $this->consultarPorChaveTela($vo, false);
    		
    		$voBanco = new voPA();
    		$voBanco->getDadosBanco($registro);
    		if($voBanco->dtPublicacao == null){
    			throw new excecaoGenerica("Só é permitido encerrar o processo que tenha sido publicado.");
    		}
    	}     	
    	//return $retorno;
    }
    
    function alterar($vo) {
    	$this->validarAlteracao($vo);
    	return parent::alterar($vo);
    }    
    
    function getSQLValuesInsert($vo){
    	if($vo->cd == null){
    		$vo->cd = $this->getProximoSequencialChaveComposta (voProcLicitatorio::$nmAtrCd, $vo );
    	}
    	
		$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vo->ano) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cd) . ",";
		
		$retorno.= $this-> getVarComoNumero($vo->cdOrgaoResponsavel) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdComissao) . ",";

		$retorno.= $this-> getVarComoString($vo->cdModalidade). ",";
		$retorno.= $this-> getVarComoNumero($vo->numModalidade). ",";
		$retorno.= $this-> getVarComoString($vo->tipo). ",";
		$retorno.= $this-> getVarComoNumero($vo->cdPregoeiro). ",";
		$retorno.= $this-> getVarComoData($vo->dtAbertura). ",";
		$retorno.= $this-> getVarComoData($vo->dtPublicacao). ",";
		$retorno.= $this-> getVarComoString($vo->objeto). ",";
		$retorno.= $this-> getVarComoString($vo->obs). ",";
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
        
        if($vo->publicacao != null){
        	$retorno.= $sqlConector . voPA::$nmAtrPublicacao . " = " . $this->getVarComoString($vo->publicacao);
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