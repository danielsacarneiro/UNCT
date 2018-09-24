<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once 'dbContratoModificacao.php';

  Class dbContratoModificacao extends dbprocesso{
  	
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
  		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
  		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
  		
  		$colecaoAtributoCoalesceNmPessoa = array(
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
  		);
  		 
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrTipo,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtAssinaturaContrato,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtPublicacaoContrato,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaInicialContrato,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaFinalContrato,
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
  				//$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),
  		);
  		
  		$queryJoin .= "\n left JOIN " . $nmTabelaDemanda;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voContratoLicon::$nmAtrAnoDemanda;
  		$queryJoin .= " AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdDemanda;
  		
  		$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrAnoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrTipoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdEspecieContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrSqEspecieContrato;
  		 
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
  	
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
  	}
  	
  	function consultarTelaConsulta($vo, $filtro) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );  		
  		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
  		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
  		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
  	
  		$colecaoAtributoCoalesceNmPessoa = array(
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
  		);
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrTipo,
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtPublicacaoContrato,
  				//$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),
  		);
  	  	
  		$queryJoin .= "\n left JOIN " . $nmTabelaDemanda;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voContratoLicon::$nmAtrAnoDemanda;
  		$queryJoin .= " AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdDemanda;
  		
  		$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrAnoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrTipoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdEspecieContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrSqEspecieContrato;
  	
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
  	  	
  		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
  	}
  	 
  	/*function consultarTelaConsulta($vo, $filtro){
  		$isHistorico = $filtro->isHistorico;
  		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
  			
  		$atributosConsulta = "*";
  		$querySelect = "SELECT ". $atributosConsulta;
  		$queryFrom = "\n FROM ". $nmTabela;
  	
  		return $this->consultarComPaginacaoQuery($vo, $filtro, $querySelect, $queryFrom);
  	}*/
  	
  	/*function validarInclusao($vo){  		
  		$vodemanda = $vo->vodemandacontrato->getVODemandaChave();
  		$dbdemanda = new dbDemanda();
  		$vodemanda = $dbdemanda->consultarPorChaveVO($vodemanda, false);
  		if($vodemanda->tipo != dominioTipoDemanda::existeItem($vodemanda->tipo, dominioTipoDemanda::getColecaoTipoDemandaSistemasExternos())){
  			throw new excecaoGenerica("Demanda deve ser do tipo 'Sistemas Externos'");
  		}  		
  	}
  	 
  	 function incluir($vo){
  	 	$this->validarInclusao($vo);
  	 	
  		return parent::incluir($vo);
  	}
  	 
  	function alterar($vo){
  		$this->validarInclusao($vo);
  			
  		return parent::alterar($vo);
  	}*/
  	 
  	function getSQLValuesInsert($vo){  		
  		if ($vo->sq == null || $vo->sq == "") {
  			$vo->sq = $this->getProximoSequencialChaveComposta ( voContratoModificacao::$nmAtrSq, $vo );
  		}
  		
		$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vo->sq). ",";
		$retorno.= $this-> getVarComoNumero($vo->vocontrato->anoContrato). ",";
		$retorno.= $this-> getVarComoNumero($vo->vocontrato->cdContrato). ",";
		$retorno.= $this-> getVarComoString($vo->vocontrato->tipo). ",";
		$retorno.= $this-> getVarComoString($vo->vocontrato->cdEspecie). ",";
		$retorno.= $this-> getVarComoNumero($vo->vocontrato->sqEspecie). ",";
		
        $retorno.= $this-> getVarComoNumero($vo->tpModificacao). ",";
        $retorno.= $this-> getVarComoData($vo->dtModificacao). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlModificacaoReferencial). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlModificacaoReal). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlModificacaoAoContrato). ",";
        $retorno.= $this-> getVarComoNumero($vo->numMesesParaOFimdoPeriodo). ",";
        $retorno.= $this-> getVarComoDecimal($vo->numPercentual). ",";
        
        $retorno.= $vo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->situacao != null){
            $retorno.= $sqlConector . voContratoLicon::$nmAtrSituacao . " = " . $this->getVarComoNumero($vo->situacao);
            $sqlConector = ",";
        }
                
        if($vo->obs != null){
        	$retorno.= $sqlConector . voContratoLicon::$nmAtrObs . " = " . $this->getVarComoString($vo->obs);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
   
}
?>