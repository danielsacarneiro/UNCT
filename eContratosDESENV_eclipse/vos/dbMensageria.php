<?php
include_once(caminho_lib. "dbprocesso.obj.php");

  Class dbMensageria extends dbprocesso{
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaContrato = voContratoInfo::getNmTabelaStatic ( false );
  		$nmTabelaPessoaGestor = vopessoa::getNmTabelaStatic ( false );
  		
  		$colecaoAtributoCoalesceNmPessoa = array(
  				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrNome,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
  		);
  		 
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrCd. " AS " . voContratoInfo::$nmAtrCdPessoaGestor,
  				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrNome. " AS " . voContratoInfo::$IDREQNmPessoaGestor,
  				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrEmail,
  				/*getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),*/
  		);
  		  		
  		$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaContrato . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrAnoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . voContratoInfo::$nmAtrCdContrato. "=" . $nmTabela . "." . voMensageria::$nmAtrCdContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrTipoContrato;
  		
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaGestor;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoaGestor . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . voContratoInfo::$nmAtrCdPessoaGestor;
  	
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
  	}
  	
  	function consultarTelaConsulta($vo, $filtro) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );  		
  		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
  		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
  		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
  		$nmTabelaPessoaGestor = "NM_TAB_PESSOAGESTOR";
  	
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
  				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrCd . " AS " . voContratoInfo::$nmAtrCdPessoaGestor,
  				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrNome . " AS " . voContratoInfo::$IDREQNmPessoaGestor,
  				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrEmail,
  		);
  	  	  		
  		$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrAnoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . voMensageria::$nmAtrCdContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrTipoContrato;
  	
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
  		
  		$queryJoin .= "\n left JOIN " . $nmTabelaContratoInfo;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrAnoContrato;
  		$queryJoin .= " AND " . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato. "=" . $nmTabela . "." . voMensageria::$nmAtrCdContrato;
  		$queryJoin .= " AND " . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabela . "." . voMensageria::$nmAtrTipoContrato;
  		 
  		$queryJoin .= "\n LEFT JOIN $nmTabelaPessoaContrato $nmTabelaPessoaGestor";
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoaGestor . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdPessoaGestor;
  		
  		$arrayGroupby = array("$nmTabela.".voMensageria::$nmAtrAnoContrato, "$nmTabela.".voMensageria::$nmAtrCdContrato, "$nmTabela.".voMensageria::$nmAtrTipoContrato);
  		$filtro->groupby = $arrayGroupby; 
  	  	
  		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
  	}
  	
  	function validar($vo){
  		//$vomensa = new voMensageria();  		
  		$vocontratoinfo = $vo->vocontratoinfo;
  		$vocontratoInformacoesGerais = new voContratoInfo();
  		$registro = $vocontratoInformacoesGerais->dbprocesso->consultarPorChaveTela($vocontratoinfo, false);
  		$vocontratoInformacoesGerais->getDadosBanco($registro);
  		
  		if($vocontratoInformacoesGerais->cdPessoaGestor == null){
  			throw new excecaoGenerica("O contrato não possui gestor cadastrado.");
  		}  			
  		
  		$vopessoa = new vopessoa();
  		$vopessoa->cd =$vocontratoInformacoesGerais->cdPessoaGestor;
  		$registro = $vopessoa->dbprocesso->consultarPorChaveTela($vopessoa, false);
  		$vopessoa->getDadosBanco($registro);
  		
  		$email = $vopessoa->email; 
  		if($email == null || $email == ""){  		
  			throw new excecaoGenerica("O e-mail do gestor do contrato não é válido.");
  		}
  	}
  	 
  	function incluir($vo){
  		$this->validar($vo);  			
  		return parent::incluir($vo);
  	}
  	
  	function alterar($vo){
  		$this->validar($vo);  			
  		return parent::alterar($vo);
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