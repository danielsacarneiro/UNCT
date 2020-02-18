<?php
include_once(caminho_lib. "dbprocesso.obj.php");

  Class dbMensageria extends dbprocesso{
  	static $FLAG_PRINTAR_SQL = FALSE;
  	
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
  		$nmTabelaMsgRegistro = voMensageriaRegistro::getNmTabelaStatic ( false );
  	
  		$colecaoAtributoCoalesceNmPessoa = array(
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,  				
  		);
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				filtroManterMensageria::getColunaDtUltimoEnvio(). " AS " . voMensageria::$nmCOLDhUltimoEnvio,
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
  		
  		$nmTabMsgRegistroMAXSq = "TAB_MSGREGISTRO_MAX_SQ";
  		$groupbyinterno = "$nmTabelaMsgRegistro." . voMensageriaRegistro::$nmAtrSqMensageria;
  		
  		//TABELA $nmTabContratoMater
  		$queryJoin .= "\n LEFT JOIN ";
  		$queryJoin .= " (SELECT " . $groupbyinterno . ", MAX(" . voMensageriaRegistro::$nmAtrSq. ") AS " . voMensageriaRegistro::$nmAtrSq
  		. " FROM " . $nmTabelaMsgRegistro;
  		$queryJoin .= " GROUP BY " . $groupbyinterno;
  		$queryJoin .= "\n) " . $nmTabMsgRegistroMAXSq;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabela . "." . voMensageria::$nmAtrSq . "=" . $nmTabMsgRegistroMAXSq . "." . voMensageriaRegistro::$nmAtrSqMensageria;

  		$queryJoin .= "\n LEFT JOIN $nmTabelaMsgRegistro";
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabMsgRegistroMAXSq . "." . voMensageriaRegistro::$nmAtrSq . "=" . $nmTabelaMsgRegistro . "." . voMensageriaRegistro::$nmAtrSq;
  		$queryJoin .= " AND " . $nmTabela . "." . voMensageria::$nmAtrSq . "=" . $nmTabelaMsgRegistro . "." . voMensageriaRegistro::$nmAtrSqMensageria;  		
  		
  		$arrayGroupby = array("$nmTabela.".voMensageria::$nmAtrSq, "$nmTabela.".voMensageria::$nmAtrAnoContrato, "$nmTabela.".voMensageria::$nmAtrCdContrato, "$nmTabela.".voMensageria::$nmAtrTipoContrato);
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
  	 
  	function excluir($vo) {
  		// Start transaction
  		$this->cDb->retiraAutoCommit ();
  		try {
			$this->excluirMensageriaRegistro ( $vo );
  			$vo = parent::excluir ( $vo );
  			// End transaction
  			$this->cDb->commit ();
  		} catch ( Exception $e ) {
  			$this->cDb->rollback ();
  			throw new Exception ( $e->getMessage () );
  		}
  	
  		return $vo;
  	}
  	function excluirMensageriaRegistro($voMensageria) {
  		
  		$voMesgRegistroTemp = new voMensageriaRegistro();
  		$nmTabela = $voMesgRegistroTemp->getNmTabelaEntidade ( false );  		
  		$query = "DELETE FROM " . $nmTabela;
  		$query .= "\n WHERE " . voMensageriaRegistro::$nmAtrSqMensageria . " = " . $voMensageria->sq;

  		// echo $query;
  		return $this->atualizarEntidade ( $query );
  	}
  	 
  	function getSQLValuesInsert($vo){
  		  		
		$retorno = "";
		
		$retorno.= $this-> getVarComoNumero($vo->vocontratoinfo->anoContrato). ",";
		$retorno.= $this-> getVarComoNumero($vo->vocontratoinfo->cdContrato). ",";
		$retorno.= $this-> getVarComoString($vo->vocontratoinfo->tipo). ",";
		
		$retorno.= $this-> getVarComoData($vo->dtInicio). ",";
		$retorno.= $this-> getVarComoData($vo->dtFim). ",";
        $retorno.= $this-> getVarComoString("S"). ",";
        $retorno.= $this-> getVarComoNumero($vo->numDiasFrequencia). ",";
        $retorno.= $this-> getVarComoString($vo->obs);
        
        $retorno.= $vo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->dtInicio != null){
        	$retorno.= $sqlConector . voMensageria::$nmAtrDtInicio . " = " . $this->getVarComoData($vo->dtInicio);
        	$sqlConector = ",";
        }        
        
        if($vo->dtFim != null){
        	$retorno.= $sqlConector . voMensageria::$nmAtrDtFim. " = " . $this->getVarComoData($vo->dtFim);
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