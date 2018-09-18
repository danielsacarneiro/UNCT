<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbContratoInfo extends dbprocesso {
	static $FLAG_PRINTAR_SQL = false;
	
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPessoaGestor = "NM_TAB_PESSOA_GESTOR";
		
		$colecaoAtributoCoalesceNmPessoa = array(
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
		);
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				$nmTabelaContrato . "." . vocontrato::$nmAtrCdAutorizacaoContrato,
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
				//$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),
				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrCd . " AS " . voContratoInfo::$nmAtrCdPessoaGestor,
				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrNome . " AS " . voContratoInfo::$IDREQNmPessoaGestor,
		);
		
		$groupbyinterno = $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
		
		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
		$nmTabContratoSqMAX = "TAB_MAXCONTRATO";
		$queryJoin .= "\n left JOIN ";
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
		
		$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrSqContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		$queryJoin .= "\n LEFT JOIN $nmTabelaPessoaContrato $nmTabelaPessoaGestor";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaGestor . "." . vopessoa::$nmAtrCd . "=" . $nmTabela . "." . voContratoInfo::$nmAtrCdPessoaGestor;		
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
				
		$colecaoAtributoCoalesceNmPessoa = array(
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
		);
		
		$arrayColunasRetornadas = array (
				"$nmTabela.". voContratoInfo::$nmAtrAnoContrato,
				"$nmTabela.". voContratoInfo::$nmAtrTipoContrato,
				"$nmTabela.". voContratoInfo::$nmAtrCdContrato,
				"$nmTabela.". voContratoInfo::$nmAtrDtProposta,
				$filtro->getSqlAtributoCoalesceAutorizacao() . " AS " . filtroManterContratoInfo::$NmColAutorizacao,
				getSQLNmContratada(),
				//getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
		);		
		
		if ($isHistorico) {			
			$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
			$arrayColunasHistorico = array (
					"$nmTabela.". voContratoInfo::$nmAtrSqHist,
					$colunaUsuHistorico,
					);
			
			$arrayColunasRetornadas = array_merge($arrayColunasRetornadas, $arrayColunasHistorico);
		}		
		
		//$groupbyinterno = $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
		$groupbyinterno = $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		
		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
		$nmTabContratoSqMAX = "TAB_MAXCONTRATO";
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= " (SELECT " . $groupbyinterno . ", MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato . " FROM " . $nmTabContratoInterna;
		/*$queryJoin .= " INNER JOIN " . $nmTabela;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabContratoInterna . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabContratoInterna . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabContratoInterna . "." . vocontrato::$nmAtrCdContrato;*/
		$queryJoin .= " GROUP BY " . $groupbyinterno;
		$queryJoin .= "\n) " . $nmTabContratoSqMAX;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabContratoSqMAX . "." . vocontrato::$nmAtrTipoContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContrato;
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
	function consultarTelaConsultaConsolidacao($filtro) {		
		
		$vo = new vocontrato();		
		//$isHistorico = $filtro->isHistorico;
		$isHistorico = false;
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
	
		$nmTabContratoMater = filtroConsultarContratoConsolidacao::$NmTabContratoMater;
		$nmTabContratoATUAL = filtroConsultarContratoConsolidacao::$NmTabContratoATUAL;
		
		$arrayColunasRetornadas = array (
				$nmTabela . "." . vocontrato::$nmAtrAnoContrato,
				$nmTabela . "." . vocontrato::$nmAtrCdContrato,
				$nmTabela . "." . vocontrato::$nmAtrTipoContrato,
				$nmTabContratoMater . "." . vocontrato::$nmAtrSqContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqContratoMater,
				$nmTabContratoATUAL . "." . vocontrato::$nmAtrSqContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqContratoAtual,
				
				filtroConsultarContratoConsolidacao::getComparacaoWhereDataVigencia($nmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato)
				. " AS " . filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia,
				
				filtroConsultarContratoConsolidacao::getComparacaoWhereDataVigencia($nmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato)
				. " AS " . filtroConsultarContratoConsolidacao::$NmColDtFimVigencia,
				
				//repete as colunas para trazer com outro nome - usado na consulta por chave de contratoinfo quando precisar consolidar
				filtroConsultarContratoConsolidacao::getComparacaoWhereDataVigencia($nmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato)
				. " AS " . vocontrato::$nmAtrDtVigenciaInicialContrato,				
				filtroConsultarContratoConsolidacao::getComparacaoWhereDataVigencia($nmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato)
				. " AS " . vocontrato::$nmAtrDtVigenciaFinalContrato,				
								
				getDataSQLDiferencaDias(getVarComoDataSQL(getDataHoje()), $nmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato) . " AS " . filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento,
				
				$nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrDtProposta,
				$filtro->getSqlAtributoCoalesceAutorizacao() . " AS " . filtroManterContratoInfo::$NmColAutorizacao,
				getSQLNmContratada(),
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
		);
	
		$groupbyinterno = $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;	
		
		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
		$nmTabContratoMINSq = "TAB_CONTRATO_MIN_SQ";
		$nmTabContratoMAXSq = "TAB_CONTRATO_MAX_SQ";
		
		//TABELA $nmTabContratoMater
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= " (SELECT " . $groupbyinterno . ", MIN(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato 
		. " FROM " . $nmTabContratoInterna;
		$queryJoin .= " GROUP BY " . $groupbyinterno;
		$queryJoin .= "\n) " . $nmTabContratoMINSq;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoMINSq . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND $nmTabela." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoMINSq . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND $nmTabela." .vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoMINSq . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n LEFT JOIN $nmTabela $nmTabContratoMater";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabContratoMINSq . "." . vocontrato::$nmAtrSqContrato . "=" . $nmTabContratoMater . "." . vocontrato::$nmAtrSqContrato;
		
		//TABELA $nmTabContratoATUAL
		//pega o registro com maior sequencial, desde que a data final de vigencia nao seja nula ou '0000-00-00'
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= " (SELECT " . $groupbyinterno . ", MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato
		. " FROM " . $nmTabContratoInterna;
		$queryJoin .= constantes::$CD_CAMPO_SUBSTITUICAO . " GROUP BY " . $groupbyinterno;
		$queryJoin .= "\n) " . $nmTabContratoMAXSq;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoMAXSq . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND $nmTabela." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoMAXSq . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND $nmTabela." .vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoMAXSq . "." . vocontrato::$nmAtrTipoContrato;		
		$queryJoin .= "\n LEFT JOIN $nmTabela $nmTabContratoATUAL";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabContratoMAXSq . "." . vocontrato::$nmAtrSqContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrSqContrato;
				
		//pega as informacos em contrato_info do contrato atual
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrTipoContrato;
			
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdPessoaContratada;
	
		$cdCampoSubstituir = "";
		$nmColunaComparacao = vocontrato::$nmAtrDtVigenciaFinalContrato;
		$cdCampoSubstituir = " WHERE $nmColunaComparacao IS NOT NULL AND $nmColunaComparacao <> '0000-00-00'";
		if($filtro->cdEspecie != null){			
			$cdCampoSubstituir .= " AND " . $filtro->getSQFiltroCdEspecie($nmTabContratoInterna);
		}
		
		$queryJoin = str_replace(constantes::$CD_CAMPO_SUBSTITUICAO, $cdCampoSubstituir, $queryJoin);
		
		//ECHO $queryJoin; 
		/*
		 * $arrayGroupby = array($nmTabela . "." . voContratoInfo::$nmAtrAnoContrato,
		 * $nmTabela . "." . voContratoInfo::$nmAtrCdContrato,
		 * $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato
		 * );
		 *
		 * $filtro->groupby = $arrayGroupby;
		 */
		
		$filtro->groupby = $groupbyinterno;
	
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
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaDemandaContrato;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
		$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
				
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
		$retorno .= $this->getVarComoString( $vo->inMaoDeObra ) . ",";
		$retorno .= $this->getVarComoNumero($vo->cdPessoaGestor);		
		
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
		
		if ($vo->cdPessoaGestor != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrCdPessoaGestor . " = " . $this->getVarComoNumero( $vo->cdPessoaGestor);
			$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrCdPessoaGestor . " = null ";
			$sqlConector = ",";				
		}
		
		$retorno = $retorno . $vo->getSQLValuesEntidadeUpdate ();
		
		return $retorno;
	}
}
?>