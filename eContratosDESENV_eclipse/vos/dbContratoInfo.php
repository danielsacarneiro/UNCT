<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbContratoInfo extends dbprocesso {
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				$nmTabelaContrato . "." . vocontrato::$nmAtrCdAutorizacaoContrato,
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome 
		);
		
		$groupbyinterno = $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
		
		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
		$nmTabContratoSqMAX = "TAB_MAXCONTRATO";
		$queryJoin .= "\n INNER JOIN ";
		$queryJoin .= " (SELECT " . $groupbyinterno . ", MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato . " FROM " . $nmTabContratoInterna;
		$queryJoin .= " INNER JOIN " . $nmTabela;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabContratoInterna . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabContratoInterna . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabContratoInterna . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= " GROUP BY " . $groupbyinterno;
		$queryJoin .= "\n) " . $nmTabContratoSqMAX;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrTipoContrato;
		
		$queryJoin .= "\n INNER JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrSqContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		/*
		 * $queryWhere = "\n WHERE ";
		 * $queryWhere .= $vo->getValoresWhereSQLChave ( $isHistorico );
		 * $queryWhere.= "\n AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $this->sqEspecie;
		 */
		
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
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
				$filtro->getSqlAtributoCoalesceAutorizacao() . " AS " . filtroManterContratoInfo::$NmColAutorizacao,
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
				$colunaUsuHistorico 
		);
		
		$groupbyinterno = $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
		
		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
		$nmTabContratoSqMAX = "TAB_MAXCONTRATO";
		$queryJoin .= "\n INNER JOIN ";
		$queryJoin .= " (SELECT " . $groupbyinterno . ", MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato . " FROM " . $nmTabContratoInterna;
		$queryJoin .= " INNER JOIN " . $nmTabela;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabContratoInterna . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabContratoInterna . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabContratoInterna . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= " GROUP BY " . $groupbyinterno;
		$queryJoin .= "\n) " . $nmTabContratoSqMAX;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrTipoContrato;
		
		$queryJoin .= "\n INNER JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrSqContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		/*
		 * $arrayGroupby = array($nmTabela . "." . voContratoInfo::$nmAtrAnoContrato,
		 * $nmTabela . "." . voContratoInfo::$nmAtrCdContrato,
		 * $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato
		 * );
		 *
		 * $filtro->groupby = $arrayGroupby;
		 */
		
		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
	}
	function consultarDemandaTramitacaoContrato($filtro) {
		$nmTabela = voDemandaTramitacao::getNmTabelaStatic ( false );
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabelaDemandaTramDoc = voDemandaTramDoc::getNmTabelaStatic ( false );
		$nmTabelaDocumento = voDocumento::getNmTabelaStatic ( false );		
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		
		$nmTabelaUsuario = vousuario::getNmTabela ();
		
		$querySelect = "SELECT ";
		$querySelect .= $nmTabela . ".*,";
		$querySelect .= $nmTabelaDemanda . "." . voDemanda::$nmAtrTexto . ",";
		$querySelect .= $nmTabelaDocumento . ".*";
		$querySelect .= "," . $nmTabelaUsuario . "." . vousuario::$nmAtrName;
		$querySelect .= "  AS " . voDemanda::$nmAtrNmUsuarioInclusao;
		$queryFrom = " FROM " . $nmTabela;
		
		$queryFrom .= "\n INNER JOIN " . $nmTabelaDemanda;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		
		$queryFrom .= "\n INNER JOIN " . $nmTabelaDemandaContrato;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
		$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;		
		
		$queryFrom .= "\n INNER JOIN " . $nmTabelaUsuario;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaUsuario . "." . vousuario::$nmAtrID . "=" . $nmTabela . "." . voDemanda::$nmAtrCdUsuarioInclusao;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaDemandaTramDoc;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrSqDemandaTram . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrSq;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaDocumento;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrAnoDoc . "=" . $nmTabelaDocumento . "." . voDocumento::$nmAtrAno;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrCdSetorDoc . "=" . $nmTabelaDocumento . "." . voDocumento::$nmAtrCdSetor;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrTpDoc . "=" . $nmTabelaDocumento . "." . voDocumento::$nmAtrTp;
		$queryFrom .= "\n AND " . $nmTabelaDemandaTramDoc . "." . voDemandaTramDoc::$nmAtrSqDoc . "=" . $nmTabelaDocumento . "." . voDocumento::$nmAtrSq;
				
		return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	function incluirSQL($vo) {
		return $this->incluirQueryVO ( $vo );
	}
	function getSQLValuesInsert($vo) {
		// $vo = new voContratoInfo();
		$retorno = "";
		$retorno .= $this->getVarComoNumero ( $vo->anoContrato ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cdContrato ) . ",";
		$retorno .= $this->getVarComoString ( $vo->tipo ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cdAutorizacao ) . ",";
		// $retorno.= $this-> getVarComoNumero($vo->situacao);
		// $retorno .= $this->getVarComoNumero ( dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA ) . ",";
		
		$retorno .= $this->getVarComoString ( $vo->obs ) . ",";
		$retorno .= $this->getVarComoData ( $vo->dtProposta ) . ",";
		
		$retorno .= $this->getVarComoString ( $vo->inTemGarantia ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->tpGarantia ) . ",";
		
		$retorno .= $this->getVarComoNumero($vo->cdClassificacao) . ",";
		$retorno .= $this->getVarComoString( $vo->inMaoDeObra );
		
		$retorno .= $vo->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		// $vo = new voContratoInfo();
		$retorno = "";
		$sqlConector = "";
		
		if ($vo->cdAutorizacao != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrCdAutorizacaoContrato . " = " . $this->getVarComoString ( $vo->cdAutorizacao );
			$sqlConector = ",";
		}
		
		if ($vo->obs != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrObs . " = " . $this->getVarComoString ( $vo->obs );
			$sqlConector = ",";
		}
		
		if ($vo->dtProposta != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrDtProposta . " = " . $this->getVarComoData ( $vo->dtProposta );
			$sqlConector = ",";
		}
		
		if ($vo->inTemGarantia != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInTemGarantia . " = " . $this->getVarComoString ( $vo->inTemGarantia );
			$sqlConector = ",";
			
			if ($vo->inTemGarantia == constantes::$CD_NAO) {
				$vo->inPrestacaoGarantia = constantes::$CD_CAMPO_NULO;
				$vo->tpGarantia = constantes::$CD_CAMPO_NULO;
			}
		}
				
		if ($vo->tpGarantia != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrTpGarantia . " = " . $this->getVarComoNumero ( $vo->tpGarantia );
			$sqlConector = ",";
		}
		
		if ($vo->cdClassificacao != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrCdClassificacao . " = " . $this->getVarComoNumero ( $vo->cdClassificacao );
			$sqlConector = ",";
		}
		
		if ($vo->inMaoDeObra != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInMaoDeObra . " = " . $this->getVarComoString( $vo->inMaoDeObra );
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $vo->getSQLValuesEntidadeUpdate ();
		
		return $retorno;
	}
}
?>