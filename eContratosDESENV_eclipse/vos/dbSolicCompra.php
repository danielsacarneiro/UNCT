<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes."contrato/dominioEspeciesContrato.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbSolicCompra extends dbprocesso{
  	static $FLAG_PRINTAR_SQL = false;
  	
  	/*function consultarPorChaveTela($vo, $isHistorico) {  		
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
  		}
  	
  		return $retorno;
  	}*/
    
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		//$nmTabelaDemandaPL = voDemandaPL::getNmTabela();
  		$nmTabelaDemanda = voDemanda::getNmTabela();
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",  
  				/*$nmTabelaDemanda . "." . voDemanda::$nmAtrCd,
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrAno,
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrTipo,*/
  		);
  		
  		/*$queryFrom .= "\n LEFT JOIN ". $nmTabelaDemandaPL;
  		$queryFrom .= "\n ON $nmTabela." . voSolicCompra::$nmAtrCd . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdProcLic;
  		$queryFrom .= "\n AND $nmTabela." . voSolicCompra::$nmAtrAno . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrAnoProcLic;
  		$queryFrom .= "\n AND $nmTabela." . voSolicCompra::$nmAtrCdModalidade . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdModalidadeProcLic;
  		
  		$queryFrom .= "\n LEFT JOIN (SELECT * FROM $nmTabelaDemanda WHERE ". voDemanda::$nmAtrTipo . "=" . dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL . ") $nmTabelaDemanda " ;
  		$queryFrom .= "\n ON $nmTabelaDemanda." . voDemanda::$nmAtrCd . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND $nmTabelaDemanda." . voDemanda::$nmAtrAno . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrAnoDemanda;  	*/  		
  		
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $isHistorico );
  	}
    
    function consultarTelaConsulta($arrayParamConsulta){
    	$filtro = $arrayParamConsulta[0];
    	$vo = $filtro->voPrincipal;
    	$isHistorico = ("S" == $filtro->cdHistorico);
    	$nmTabela = $vo->getNmTabelaEntidade($isHistorico);    	
    	$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
    	//$nmTabelaDemandaSolicCompra = voDemandaPL::getNmTabelaStatic(false);
    	
    	$colunaUsuHistorico = "";
    	
    	if ($isHistorico) {
    		$sqHist = $nmTabela . "." . voSolicCompra::$nmAtrSqHist;
    		$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voSolicCompra::$nmAtrNmUsuarioOperacao;
    	}
    	$arrayColunasRetornadas = array (
    			$nmTabela . ".*",
    			//$nmTabelaDemanda . "." . voDemanda::$nmAtrSituacao,
    			$colunaUsuHistorico,
    			$sqHist
    	);
    	        
        /*$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaPL;
        $queryJoin .= "\n ON ";
        $queryJoin .= $nmTabela . "." . voSolicCompra::$nmAtrAno . "=" . $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrAnoProcLic;
        $queryJoin .= "\n AND " . $nmTabela . "." . voSolicCompra::$nmAtrCd . "=" . $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrCdProcLic;
                
        $queryJoin .= "\n LEFT JOIN " . $nmTabelaDemanda;
        $queryJoin .= "\n ON ";
        $queryJoin .= $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrAnoDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
        $queryJoin .= "\n AND " . $nmTabelaDemandaPL . "." . voDemandaPL::$nmAtrCdDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
        
        //$filtro->tpDemanda = dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL;
        $groupby = array("$nmTabela.". voSolicCompra::$nmAtrAno,
        		"$nmTabela.". voSolicCompra::$nmAtrCd,
        		
        );
        $filtro->groupby = $groupby; */
        
        //$filtro->cdEspecieContrato = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
        
        return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );        
    }    
    
    function getSQLValuesInsert($vo){
    	if($vo->cd == null){
    		$vo->cd = $this->getProximoSequencialChaveComposta (voSolicCompra::$nmAtrCd, $vo );
    	}
    	
		/*$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vo->cd) . ",";
		$retorno.= $this-> getVarComoNumero($vo->ano) . ",";
		$retorno.= $this-> getVarComoNumero($vo->ug) . ",";
		
		$retorno.= $this-> getVarComoString($vo->tipo). ",";
		$retorno.= $this-> getVarComoString($vo->objeto). ",";
		$retorno.= $this-> getVarComoNumero($vo->situacao). ",";
		$retorno.= $this-> getVarComoDecimal($vo->valor). ",";
		$retorno.= $this-> getVarComoString($vo->obs);*/
		
		$colecaoAtributos = array(
				$this-> getVarComoNumero($vo->ano),
				$this-> getVarComoNumero($vo->ug),
				$this-> getVarComoNumero($vo->cd),				
				
				$this-> getVarComoNumero($vo->tipo),
				$this-> getVarComoString($vo->objeto),
				$this-> getVarComoNumero($vo->situacao),
				$this-> getVarComoDecimal($vo->valor),
				$this-> getVarComoString($vo->obs),
		);
		
		$retorno = getSQLStringFormatadaColecaoIN($colecaoAtributos);
		$retorno.= $vo->getSQLValuesInsertEntidade();
	
		return $retorno;
	}	
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";                                
               
        if($vo->tipo != null){
        	$retorno.= $sqlConector . voSolicCompra::$nmAtrTipo . " = " . $this->getVarComoNumero($vo->tipo);
        	$sqlConector = ",";
        }
        
        if($vo->objeto != null){
            $retorno.= $sqlConector . voSolicCompra::$nmAtrObjeto . " = " . $this->getVarComoString($vo->objeto);
            $sqlConector = ",";
        }
        
        if($vo->situacao != null){
        	$retorno.= $sqlConector . voSolicCompra::$nmAtrSituacao. " = " . $this->getVarComoNumero($vo->situacao);
        	$sqlConector = ",";
        }        
        
        if($vo->valor != null){
        	$retorno.= $sqlConector . voSolicCompra::$nmAtrValor . " = " . $this->getVarComoDecimal($vo->valor);
        	$sqlConector = ",";
        }
        
        if($vo->obs != null){
        	$retorno.= $sqlConector . voSolicCompra::$nmAtrObservacao . " = " . $this->getVarComoString($vo->obs);
        	$sqlConector = ",";
        }                
                
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>