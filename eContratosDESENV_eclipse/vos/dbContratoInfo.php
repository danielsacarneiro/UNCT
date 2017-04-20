<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbContratoInfo extends dbprocesso {
		
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				$nmTabelaPessoa . "." . vopessoa::$nmAtrDoc,
				 $nmTabelaPessoa . "." . vopessoa::$nmAtrNome
		);
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoa;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoa . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		$queryWhere = "\n WHERE ";
		$queryWhere .= $vo->getValoresWhereSQLChave ( $isHistorico );
		$queryWhere.= "\n AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . getVarComoString(dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER);
		//$queryWhere.= "\n AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $this->sqEspecie;		
		
		return $this->consultarMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico );
	}
	function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico;
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		$colunaUsuHistorico = "";
		
		if ($isHistorico) {
			$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
		}
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",				
				"MAX(". $nmTabelaContrato . "."  . vocontrato::$nmAtrSqContrato. ")",
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
				$colunaUsuHistorico 
		);			
						
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		
		 $arrayGroupby = array($nmTabela . "." . voContratoInfo::$nmAtrAnoContrato,
		 		$nmTabela . "." . voContratoInfo::$nmAtrCdContrato,
		 		$nmTabela . "." . voContratoInfo::$nmAtrTipoContrato
		 );
		
		 $filtro->groupby = $arrayGroupby;		
		
		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
	}
		
	function incluirSQL($vo) {
		return $this->incluirQueryVO ( $vo );
	}
	
	function getSQLValuesInsert($vo) {		
		$retorno = "";
		$retorno .= $this->getVarComoNumero ( $vo->ano ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cd ) . ",";
		$retorno .= $this->getVarComoString ( $vo->tipo ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cdAutorizacao) . ",";
		// $retorno.= $this-> getVarComoNumero($vo->situacao);
		//$retorno .= $this->getVarComoNumero ( dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA ) . ",";
		
		$retorno .= $this->getVarComoString ( $vo->obs ) . ",";
		$retorno .= $this->getVarComoData ( $vo->dtProposta);
		
		$retorno .= $vo->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		$retorno = "";
		$sqlConector = "";
		
		if ($vo->cdAutorizacao != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrCdAutorizacaoContrato . " = " . $this->getVarComoString( $vo->cdAutorizacao);
			$sqlConector = ",";
		}
				
		if ($vo->obs != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrObs. " = " . $this->getVarComoString($vo->obs);
			$sqlConector = ",";
		}
		
		if ($vo->dtProposta != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrDtProposta . " = " . $this->getVarComoData($vo->dtProposta);
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
}
?>