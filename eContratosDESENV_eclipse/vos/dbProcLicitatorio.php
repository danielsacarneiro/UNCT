<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes."contrato/dominioEspeciesContrato.php");
include_once (caminho_funcoes."pa/dominioSituacaoPA.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbProcLicitatorio extends dbprocesso{
  	static $FLAG_PRINTAR_SQL = false;
  	
  	function consultarPorChaveTela($vo, $isHistorico) {  		
  		$retorno = "";
  		// para o caso de haver mais de uma demanda por proclic
  		$retornoGeral = $this->consultarPorChaveTelaColecao ( $vo, $isHistorico, false);
  		if(!isColecaoVazia($retornoGeral)){
	  		if(sizeof($retornoGeral)==1){
	  			$retorno = $retornoGeral[0];
	  		}else{
	  			//$temDemandaEdital = false;
	  			foreach ($retornoGeral as $registrobanco){
	  				$voDemanda = new voDemanda();
	  				$voDemanda->getDadosBanco($registrobanco);
	  				
	  				if($voDemanda->tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL){
	  					//$temDemandaEdital = true;
	  					break;
	  				}
	  			}
	  			  			
	  			$retorno = $registrobanco;
	  			
	  		}
  		}else {
  			throw new excecaoChaveRegistroInexistente();
  		}
  	
  		return $retorno;
  	}
    
  	function consultarPorChaveTelaColecao($vo, $isHistorico, $isConsultarPorChave) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaDemandaPL = voDemandaPL::getNmTabela();
  		$nmTabelaDemanda = voDemanda::getNmTabela();
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",  
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrCd,
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrAno,
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrTipo,
  		);
  		
  		//$nmTabelaDemandaEdital = "NM_TAB_DEMANDA_EDITAL";
  		/*$queryFrom .= "\n LEFT JOIN ". $nmTabelaDemandaPL;
  		$queryFrom .= "\n ON $nmTabela." . voProcLicitatorio::$nmAtrCd . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdProcLic;
  		$queryFrom .= "\n AND $nmTabela." . voProcLicitatorio::$nmAtrAno . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrAnoProcLic;
  		  		
  		$queryFrom .= "\n LEFT JOIN (";
  		$queryFrom .= " SELECT * FROM $nmTabelaDemanda WHERE " . voDemanda::$nmAtrTipo . "=" . dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL;
  		$queryFrom .= ") $nmTabelaDemanda";
  		$queryFrom .= "\n ON $nmTabelaDemanda." . voDemanda::$nmAtrCd . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND $nmTabelaDemanda." . voDemanda::$nmAtrAno . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrAnoDemanda;*/

  		$queryFrom .= "\n LEFT JOIN ". $nmTabelaDemandaPL;
  		$queryFrom .= "\n ON $nmTabela." . voProcLicitatorio::$nmAtrCd . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdProcLic;
  		$queryFrom .= "\n AND $nmTabela." . voProcLicitatorio::$nmAtrAno . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrAnoProcLic;
  		$queryFrom .= "\n AND $nmTabela." . voProcLicitatorio::$nmAtrCdModalidade . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdModalidadeProcLic;
  		
  		$queryFrom .= "\n LEFT JOIN (SELECT * FROM $nmTabelaDemanda WHERE ". voDemanda::$nmAtrTipo . "=" . dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL . ") $nmTabelaDemanda " ;
  		$queryFrom .= "\n ON $nmTabelaDemanda." . voDemanda::$nmAtrCd . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND $nmTabelaDemanda." . voDemanda::$nmAtrAno . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrAnoDemanda;  		
  		
  		/*$queryFrom .= "\n INNER JOIN ". $nmTabelaDemanda;
  		$queryFrom .= "\n ON $nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdDemanda . "=$nmTabelaDemanda." . voDemanda::$nmAtrCd;
  		$queryFrom .= "\n AND $nmTabelaDemandaPL." . voDemandaPL::$nmAtrAnoDemanda . "=$nmTabelaDemanda." . voDemanda::$nmAtrAno;*/
  		
  		$queryWhere = " WHERE ";
  		$queryWhere .= "$nmTabelaDemanda." . voDemanda::$nmAtrInDesativado . "='N' AND ";
  		$queryWhere .= $vo->getValoresWhereSQLChave ( $isHistorico );
  		//traz apenas a demanda do tipo EDITAL
  		//$queryWhere .= " AND $nmTabelaDemanda." . voDemanda::$nmAtrTipo . " = " . dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL;
  		/*if($isFiltrarPorDemandaEdital){
  			$queryWhere .= "\n AND $nmTabelaDemanda.". voDemanda::$nmAtrTipo . "=" . dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL;
  		}*/
  		return $this->consultarMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $queryWhere, $isHistorico, $isConsultarPorChave );
  		
  		//return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $isHistorico );
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
        $queryJoin .= "\n AND " . $nmTabela . "." . voProcLicitatorio::$nmAtrCdModalidade . "=" . $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrCdModalidadeProcLic;
                
        $queryJoin .= "\n LEFT JOIN " . $nmTabelaDemanda;
        $queryJoin .= "\n ON ";
        $queryJoin .= $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrAnoDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
        $queryJoin .= "\n AND " . $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrCdDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
        
        //$filtro->tpDemanda = dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL;
        $groupby = array("$nmTabela.". voProcLicitatorio::$nmAtrAno,
        		"$nmTabela.". voProcLicitatorio::$nmAtrCd,
        		"$nmTabela.". voProcLicitatorio::$nmAtrCdModalidade);
        $filtro->groupby = $groupby; 
        
        //$filtro->cdEspecieContrato = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
        
        return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );        
    }
    
    function getSQLValuesInsert($vo){
    	if($vo->cd == null){
    		$vo->cd = $this->getProximoSequencialChaveComposta (voProcLicitatorio::$nmAtrCd, $vo );
    	}
    	
		$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vo->ano) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cd) . ",";
		
		$retorno.= $this-> getVarComoNumero($vo->cdOrgaoResponsavel) . ",";

		$retorno.= $this-> getVarComoString($vo->cdModalidade). ",";
		$retorno.= $this-> getVarComoNumero($vo->numModalidade). ",";
		$retorno.= $this-> getVarComoString($vo->tipo). ",";
		$retorno.= $this-> getVarComoNumero($vo->cdPregoeiro). ",";
		$retorno.= $this-> getVarComoNumero($vo->cdCPL). ",";
		$retorno.= $this-> getVarComoData($vo->dtAbertura). ",";
		$retorno.= $this-> getVarComoData($vo->dtPublicacao). ",";
		$retorno.= $this-> getVarComoString($vo->objeto). ",";
		$retorno.= $this-> getVarComoString($vo->obs). ",";
		$retorno.= $this-> getVarComoNumero($vo->situacao). ",";
		$retorno.= $this-> getVarComoDecimal($vo->valor);
	
		$retorno.= $vo->getSQLValuesInsertEntidade();
	
		return $retorno;
	}	
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                        
        if($vo->tipo != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrTipo . " = " . $this->getVarComoString($vo->tipo);
        	$sqlConector = ",";
        }
        
        if($vo->cdModalidade != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrCdModalidade . " = " . $this->getVarComoString($vo->cdModalidade);
        	$sqlConector = ",";
        }
                
        if($vo->numModalidade != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrNumModalidade . " = " . $this->getVarComoNumero($vo->numModalidade);
        	$sqlConector = ",";
        }
        
        if($vo->objeto != null){
            $retorno.= $sqlConector . voProcLicitatorio::$nmAtrObjeto . " = " . $this->getVarComoString($vo->objeto);
            $sqlConector = ",";
        }
        
        if($vo->obs != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrObservacao . " = " . $this->getVarComoString($vo->obs);
        	$sqlConector = ",";
        }
                
        if($vo->dtAbertura != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrDtAbertura . " = " . $this->getVarComoData($vo->dtAbertura);
        	$sqlConector = ",";
        }
        
        if($vo->dtPublicacao != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrDtPublicacao . " = " . $this->getVarComoData($vo->dtPublicacao);
        	$sqlConector = ",";
        }
        
        if($vo->situacao != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrSituacao. " = " . $this->getVarComoNumero($vo->situacao);
        	$sqlConector = ",";
        }
        
        if($vo->valor != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrValor . " = " . $this->getVarComoDecimal($vo->valor);
        	$sqlConector = ",";
        }
        
        if($vo->cdPregoeiro != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrCdPregoeiro . " = " . $this->getVarComoNumero($vo->cdPregoeiro);
        	$sqlConector = ",";
        }
        
        if($vo->cdCPL != null){
        	$retorno.= $sqlConector . voProcLicitatorio::$nmAtrCdCPL . " = " . $this->getVarComoNumero($vo->cdCPL);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>