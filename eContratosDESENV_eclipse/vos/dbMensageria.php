<?php
include_once(caminho_lib. "dbprocesso.obj.php");

  Class dbMensageria extends dbprocesso{
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
  		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
  		
  		$colecaoAtributoCoalesceNmPessoa = array(
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
  		);
  		 
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				/*$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
  				//$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),*/
  		);
  		  		
  		/*$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrAnoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . voMensageria::$nmAtrCdContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrTipoContrato;
  		
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;*/
  	
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
  	}
  	
  	function consultarTelaConsulta($vo, $filtro) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );  		
  		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
  		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
  	
  		$colecaoAtributoCoalesceNmPessoa = array(
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
  		);
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtPublicacaoContrato,
  				//$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),
  		);
  	  	  		
  		$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrAnoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . voMensageria::$nmAtrCdContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrTipoContrato;
  	
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
  		
  		$arrayGroupby = array("$nmTabela.".voMensageria::$nmAtrAnoContrato, "$nmTabela.".voMensageria::$nmAtrCdContrato, "$nmTabela.".voMensageria::$nmAtrTipoContrato);
  		$filtro->groupby = $arrayGroupby; 
  	  	
  		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
  	}
  	
  	 
  	function getSQLValuesInsert($vo){
  		  		
		$retorno = "";
		
		$retorno.= $this-> getVarComoNumero($vo->vocontratoinfo->anoContrato). ",";
		$retorno.= $this-> getVarComoNumero($vo->vocontratoinfo->cdContrato). ",";
		$retorno.= $this-> getVarComoString($vo->vocontratoinfo->tipo). ",";
		
		$retorno.= $this-> getVarComoData($vo->dtReferencia). ",";
        $retorno.= $this-> getVarComoString("S"). ",";
        $retorno.= $this-> getVarComoNumero($vo->numDiasFrequencia). ",";
        $retorno.= $this-> getVarComoString($vo->obs);
        
        $retorno.= $vo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->dtReferencia != null){
        	$retorno.= $sqlConector . voMensageria::$nmAtrDtReferencia . " = " . $this->getVarComoData($vo->dtReferencia);
        	$sqlConector = ",";
        }
        
        if($vo->inHabilitado != null){
            $retorno.= $sqlConector . voMensageria::$nmAtrInHabilitado . " = " . $this->getVarComoString($vo->inHabilitado);
            $sqlConector = ",";
        }
                
        if($vo->numDiasFrequencia != null){
        	$retorno.= $sqlConector . voMensageria::$nmAtrNumDiasFrequencia . " = " . $this->getVarComoNumero($vo->numDiasFrequencia);
        	$sqlConector = ",";
        }
        
        if($vo->obs != null){
        	$retorno.= $sqlConector . voMensageria::$nmAtrObs . " = " . $this->getVarComoString($vo->obs);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
   
}
?>