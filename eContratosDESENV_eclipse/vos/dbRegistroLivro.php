<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes."contrato/dominioEspeciesContrato.php");

  Class dbRegistroLivro extends dbprocesso{
  	static $FLAG_PRINTAR_SQL = false;
  	  	    
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		//$nmTabelaDemandaPL = voDemandaPL::getNmTabela();
  		$nmTabelaDemanda = voDemanda::getNmTabela();
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",  
  		);
  		  		
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $isHistorico );
  	}
    
    function consultarTelaConsulta($arrayParamConsulta){
    	$filtro = $arrayParamConsulta[0];
    	$vo = $filtro->voPrincipal;
    	$isHistorico = ("S" == $filtro->cdHistorico);
    	$nmTabela = $vo->getNmTabelaEntidade($isHistorico);    	
    	$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
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
    	        
    	$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
    	$queryJoin .= "\n ON ";
    	$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
    	$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . vocontrato::$nmAtrCdContrato;
    	$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
    	$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrCdEspecieContrato;
    	$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrSqEspecieContrato;
    	 
        return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );        
    }   
    
    function consultarContratosNaoRegistrados(){
    	$retorno = "";
    
    	$vo = new voDemandaContrato();
    	$nmTabela = $vo->getNmTabelaEntidade(false);
    	$nmTabelaRegistro = voRegistroLivro::getNmTabela();
    	//$nmTabelaDemandaSolicCompra = voDemandaPL::getNmTabelaStatic(false);
    	$groupby = "$nmTabela." . voDemandaContrato::$nmAtrAnoContrato
    	. ",$nmTabela." . voDemandaContrato::$nmAtrTipoContrato
    	. ",$nmTabela." . voDemandaContrato::$nmAtrCdContrato
    	. ",$nmTabela." . voDemandaContrato::$nmAtrCdEspecieContrato
    	. ",$nmTabela." . voDemandaContrato::$nmAtrSqEspecieContrato;
    		    		
    	$arrayTermos = array_keys(dominioEspeciesContrato::getColecaoImportacaoPlanilha());
    
    	$query .= " SELECT * ";
    	$query .= " FROM " . $nmTabela;
    	$query .= " WHERE "
    			. "$nmTabela." .  voDemandaContrato::$nmAtrCdEspecieContrato . "  IN (". getSQLStringFormatadaColecaoIN($arrayTermos, true).") AND "
    					. " NOT EXISTS (SELECT 'X' FROM " . $nmTabelaRegistro
    					. " WHERE $nmTabela." . voDemandaContrato::$nmAtrAnoContrato . "= $nmTabelaRegistro.". vocontrato::$nmAtrAnoContrato
    					. " AND $nmTabela." . voDemandaContrato::$nmAtrTipoContrato . "= $nmTabelaRegistro.". vocontrato::$nmAtrTipoContrato
    					. " AND $nmTabela." . voDemandaContrato::$nmAtrCdContrato . "= $nmTabelaRegistro.". vocontrato::$nmAtrCdContrato
    					. " AND $nmTabela." . voDemandaContrato::$nmAtrCdEspecieContrato . "= $nmTabelaRegistro.". vocontrato::$nmAtrCdEspecieContrato
    					. " AND $nmTabela." . voDemandaContrato::$nmAtrSqEspecieContrato . "= $nmTabelaRegistro.". vocontrato::$nmAtrSqEspecieContrato
    					. ")\n";

    	$query .= " AND DATE($nmTabela." .  voDemandaContrato::$nmAtrDhInclusao . ") >= " . getVarComoDataSQL("01/09/2020");
    	$query .= " group by $groupby";
    	
    	$arrayGroupby = array(voDemandaContrato::$nmAtrAnoContrato . " DESC ",
    							voDemandaContrato::$nmAtrTipoContrato,
    							voDemandaContrato::$nmAtrCdEspecieContrato,
    					);
    	
    	$stringOrderBy = "$nmTabela." .  voDemandaContrato::$nmAtrDhInclusao . " DESC," . getSQLStringFormatadaColecaoIN($arrayGroupby);
    	$query .= " order by $stringOrderBy";
    
    	$retorno = parent::consultarEntidade ( $query, false);
    
    	return $retorno;
    }
    
    function getSQLValuesInsert($vo){    			
		$colecaoAtributos = array(
				$this-> getVarComoNumero($vo->voContrato->anoContrato),
				$this-> getVarComoNumero($vo->voContrato->cdContrato),
				$this-> getVarComoString($vo->voContrato->tipo),				
				$this-> getVarComoString($vo->voContrato->cdEspecie),
				$this-> getVarComoNumero($vo->voContrato->sqEspecie),
								
				$this-> getVarComoNumero($vo->numLivro),
				$this-> getVarComoNumero($vo->numFolha),
				$this-> getVarComoData($vo->dtRegistro),
				$this-> getVarComoString($vo->obs),
		);
		
		$retorno = getSQLStringFormatadaColecaoIN($colecaoAtributos);
		$retorno.= $vo->getSQLValuesInsertEntidade();
	
		return $retorno;
	}	
        
    function getSQLValuesUpdate($vo){ 
    	throw new excecaoMetodoNaoImplementado("getSQLValuesUpdate", $vo);
    	
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