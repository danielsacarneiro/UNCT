<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbContratoInfo extends dbprocesso {
	static $FLAG_PRINTAR_SQL = false;
	
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPessoaGestor = "NM_TAB_PESSOA_GESTOR";
		$nmTabContratoSqMAX = "TAB_MAXCONTRATO";
		
		$colecaoAtributoCoalesceNmPessoa = array(
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
		);
		
		$colecaoAtributoAutorizacaoCoalesce = array(				
				$nmTabela . "." . voContratoInfo::$nmAtrCdAutorizacaoContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrCdAutorizacaoContrato,
		);
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				getSQLCOALESCE($colecaoAtributoAutorizacaoCoalesce,voContratoInfo::$nmAtrCdAutorizacaoContrato),
				$nmTabelaContrato . "." . vocontrato::$nmAtrCdAutorizacaoContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrVlMensalContrato,
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
				//$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,				
				getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),
				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrCd . " AS " . voContratoInfo::$nmAtrCdPessoaGestor,
				$nmTabelaPessoaGestor . "." . vopessoa::$nmAtrNome . " AS " . voContratoInfo::$IDREQNmPessoaGestor,
		);
		
		$groupbyinterno = $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
		
		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
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
		$queryJoin .= " WHERE " . vocontrato::$nmAtrContratadaContrato . " IS NOT NULL ";
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
	function consultarTelaConsultaConsolidacao($filtro, $trazerComDadosDemanda=false) {		
		
		$vo = new vocontrato();		
		$isHistorico = $filtro->isHistorico;
		//$isHistorico = false;
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );			
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( $isHistorico );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
	
		//echo "tabela vo: $nmTabela | tabela contrato_info: $nmTabelaContratoInfo";
		
		$nmTabContratoMater = filtroConsultarContratoConsolidacao::$NmTabContratoMater;
		$nmTabContratoATUAL = filtroConsultarContratoConsolidacao::$NmTabContratoATUAL;
		$nmAtrTempContratoAtualCDEspecie = $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdEspecieContrato;
		$nmAtrTempContratoAtualSqEspecie = $nmTabContratoATUAL . "." . vocontrato::$nmAtrSqEspecieContrato;
		
		$atributoProrrogavel = filtroConsultarContratoConsolidacao::getSQLComparacaoPrazoProrrogacao(dominioProrrogacaoFiltroConsolidacao::$CD_PRORROGAVEL);
		$atributoProrrogavelExcepcional = filtroConsultarContratoConsolidacao::getSQLComparacaoPrazoProrrogacao(dominioProrrogacaoFiltroConsolidacao::$CD_PERMITE_EXCEPCIONAL);
		$inAtributoSeraProrrogado = "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrInSeraProrrogado;
		$inPrazoProrrogacao = "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrInPrazoProrrogacao;
		
		$arrayColunasRetornadas = array (
				$nmTabela . "." . vocontrato::$nmAtrAnoContrato,
				$nmTabela . "." . vocontrato::$nmAtrCdContrato,
				$nmTabela . "." . vocontrato::$nmAtrTipoContrato,
				$nmTabContratoMater . "." . vocontrato::$nmAtrSqContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqContratoMater,
				$nmTabContratoATUAL . "." . vocontrato::$nmAtrSqContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqContratoAtual,
				//$nmTabContratoATUAL . "." . vocontrato::$nmAtrCdEspecieContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColCdEspecieContratoAtual,
				"$nmAtrTempContratoAtualCDEspecie AS " . filtroConsultarContratoConsolidacao::$NmColCdEspecieContratoAtual,				
				//$nmTabContratoATUAL . "." . vocontrato::$nmAtrSqEspecieContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqEspecieContratoAtual,
				"$nmAtrTempContratoAtualSqEspecie AS " . filtroConsultarContratoConsolidacao::$NmColSqEspecieContratoAtual,
				
				$nmTabContratoATUAL . "." . vocontrato::$nmAtrGestorContrato,
				
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
				filtroConsultarContratoConsolidacao::getSQLQtdAnosVigenciaContrato() . " AS " . filtroConsultarContratoConsolidacao::$NmColPeriodoEmAnos,
				
				/*getSQLCASE("$nmTabelaContratoInfo." . voContratoInfo::$nmAtrInPrazoProrrogacao
						, " NOT NULL "
						, filtroConsultarContratoConsolidacao::getSQLComparacaoPrazoProrrogacao(dominioProrrogacaoFiltroConsolidacao::$CD_PRORROGAVEL) 
						, "NULL") . " AS " . filtroConsultarContratoConsolidacao::$NmColInProrrogavel,*/
				$atributoProrrogavel . " AS " . filtroConsultarContratoConsolidacao::$NmColInProrrogavel, 
				$atributoProrrogavelExcepcional . " AS " . filtroConsultarContratoConsolidacao::$NmColInProrrogacaoExcepcional,
				
				filtroConsultarContratoConsolidacao::getDataBaseReajuste($nmTabelaContratoInfo, $nmTabela) . "AS " . voContratoInfo::$nmAtrDtProposta,
				$inPrazoProrrogacao,
				$inAtributoSeraProrrogado,
				//o retorno abaixo se refere ao atributo inseraprorrogado, quando o contrato for improrrogavel: deve mandar um sinal de atencao
				//considerando que o inseraprorrogado no contratoinfo pode nao ter sido informado
				//SE $inPrazoProrrogacao for nulo, presume-se prorrogavel
				getSQLCASEBooleano(
						"!$atributoProrrogavel"
						. " AND "
						. "!$atributoProrrogavelExcepcional"
						. " AND "
						. "$inAtributoSeraProrrogado = 'S' ",
						getVarComoString(filtroConsultarContratoConsolidacao::$CD_ATENCAO),
						$inAtributoSeraProrrogado,
						filtroConsultarContratoConsolidacao::$NmColInSeraProrrogadoConsolidado),
				$filtro->getSqlAtributoCoalesceAutorizacao() . " AS " . filtroManterContratoInfo::$NmColAutorizacao,
				getSQLNmContratada(),
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
		);
		
		if ($trazerComDadosDemanda) {
			$arrayTemp = array (
					"$nmTabelaDemanda.". voDemanda::$nmAtrAno,
					"$nmTabelaDemanda.". voDemanda::$nmAtrCd,
					"$nmTabelaDemanda.". voDemanda::$nmAtrProtocolo,
					"$nmTabelaDemanda.". voDemanda::$nmAtrTexto,
					filtroConsultarContratoConsolidacao::getAtributoConsultaTemDemanda("$nmTabelaDemanda.". voDemanda::$nmAtrAno,
							filtroConsultarContratoConsolidacao::$NmColInTemDemanda),
			);
			$arrayColunasRetornadas = array_merge($arrayColunasRetornadas, $arrayTemp);
		}
		
		if ($isHistorico) {		
			$arrayColunasHistorico = array (
					"$nmTabelaContratoInfo.". voContratoInfo::$nmAtrSqHist,
			);				
			$arrayColunasRetornadas = array_merge($arrayColunasRetornadas, $arrayColunasHistorico);
		}
			
		$groupbyinterno = $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;	
		
		//$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
		$nmTabContratoInterna = $nmTabela;		
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
		//RECENTEMENTE o trecho abaixo foi alterado para INNER (estava LEFT JOIN)
		//isto porque, quando consultado o termo atual "com efeitos", e nao houver publicacao (ou seja, sem efeitos), o contrato NAO DEVE SER RETORNADO
		$queryJoin .= "\n INNER JOIN ";
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
		
		if($trazerComDadosDemanda){
			/*$nmCampoAditivoCdEspecie = vocontrato::$nmAtrCdEspecieContrato;
			$nmCampoAditivoSqEspecie = vocontrato::$nmAtrSqEspecieContrato;
			$sqlWhereTemp = " WHERE $nmCampoAditivoCdEspecie = '" .dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_TERMOADITIVO.  "' ";
			$sqlWhereTemp .= " AND $nmCampoAditivoSqEspecie = ($nmAtrTempContratoAtualSqEspecie+1) ";
				
			$selectTemp = " SELECT * FROM $nmTabelaDemandaContrato ";*/
			$strSQLCaseTemp = getSQLCASE($nmAtrTempContratoAtualCDEspecie
					, getVarComoString(dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER)
					, 1
					, "($nmAtrTempContratoAtualSqEspecie + 1)");
			$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaContrato;
			$queryJoin .= "\n ON ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrAnoContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrTipoContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "='" . dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_TERMOADITIVO . "' ";
			$queryJoin .= "\n AND ";
			//pega o proximo aditivo
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=$strSQLCaseTemp";				
			
			$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemanda;
			$queryJoin .= "\n ON ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
			$queryJoin .= "\n AND " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;				
		}
	
		$cdCampoSubstituir = "";
		//pega o contrato atual (termo atual), maior sequencial, desde que a data final de vigencia nao seja nula ou '0000-00-00'
		$nmColunaComparacao = vocontrato::$nmAtrDtVigenciaFinalContrato;
		$cdCampoSubstituir = " WHERE ($nmColunaComparacao IS NOT NULL AND $nmColunaComparacao <> '0000-00-00')";
		
		//analisar as informacoes que estao no filtroConsultarContratoConsolidacao
		$inProduzindoEfeitos= $filtro->inProduzindoEfeitos;
		if ($inProduzindoEfeitos != null) {
			$nmColunaComparacao = vocontrato::$nmAtrDtPublicacaoContrato;
			//pega o contrato atual (termo atual), de maior sequencial, desde que tenha sido publicado, provocando efeitos
			//so vai consultar se for SIM, caso contrario, traz o ultimo registro, independente de ter sido publicado ou nao.
			if($inProduzindoEfeitos == constantes::$CD_SIM){				
				$cdCampoSubstituir .= " AND ($nmColunaComparacao IS NOT NULL AND $nmColunaComparacao <> '0000-00-00')";
			}/*else{
				$cdCampoSubstituir .= " AND ($nmColunaComparacao IS NULL)";
			}*/
		}		
		
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
		$querySelect .= $nmTabelaDemanda . "." . voDemanda::$nmAtrTipo . ",";
		$querySelect .= $nmTabelaDemanda . "." . voDemanda::$nmAtrTpDemandaContrato . ",";
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
		$retorno .= $this->getVarComoData ( $vo->dtBaseReajuste) . ",";
		
		$retorno .= $this->getVarComoString ( $vo->inTemGarantia ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->tpGarantia ) . ",";
		
		$retorno .= $this->getVarComoNumero($vo->cdClassificacao) . ",";
		$retorno .= $this->getVarComoString($vo->inCredenciamento) . ",";
		$retorno .= $this->getVarComoString($vo->inSeraProrrogado) . ",";
		$retorno .= $this->getVarComoString( $vo->inMaoDeObra ) . ",";
		$retorno .= $this->getVarComoNumero($vo->cdPessoaGestor) . ",";
		$retorno .= $this->getVarComoString($vo->inEscopo) . ",";
		$retorno .= $this->getVarComoNumero($vo->inPrazoProrrogacao). ",";
		$retorno .= $this->getVarComoNumero($vo->inEstudoTecnicoSAD). ",";
		$retorno .= $this->getVarComoNumero($vo->inPendencias). ",";
		$retorno .= $this->getVarComoString(voDemanda::getNumeroPRTSemMascara($vo->SEIContratoSubstituto));
		
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
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrObs . " = null ";
			$sqlConector = ",";
		}				
		
		if ($vo->dtProposta != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrDtProposta . " = " . $this->getVarComoData ( $vo->dtProposta );
			$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrDtProposta . " = null ";
			$sqlConector = ",";
		}				
		
		if ($vo->dtBaseReajuste != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrDtBaseReajuste . " = " . $this->getVarComoData ( $vo->dtBaseReajuste );
			$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrDtBaseReajuste . " = null ";
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
		
		if ($vo->inCredenciamento != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInCredenciamento . " = " . $this->getVarComoString($vo->inCredenciamento );
			$sqlConector = ",";
		}
		
		if ($vo->inSeraProrrogado != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInSeraProrrogado . " = " . $this->getVarComoString($vo->inSeraProrrogado );
			$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInSeraProrrogado . " = null ";
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
		
		if ($vo->inEscopo != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInEscopo . " = " . $this->getVarComoString($vo->inEscopo);
			$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInEscopo . " = null ";
			$sqlConector = ",";
		}
		
		if ($vo->inPrazoProrrogacao != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInPrazoProrrogacao . " = " . $this->getVarComoNumero($vo->inPrazoProrrogacao);
			$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInPrazoProrrogacao . " = null ";
			$sqlConector = ",";
		}		
		
		if ($vo->inEstudoTecnicoSAD != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInEstudoTecnicoSAD . " = " . $this->getVarComoNumero($vo->inEstudoTecnicoSAD);
			$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInEstudoTecnicoSAD . " = null ";
			$sqlConector = ",";
		}
		
		if ($vo->inPendencias != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInPendencias . " = "
					. $this->getVarComoString($vo->inPendencias);
					$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrInPendencias . " = null ";
			$sqlConector = ",";
		}
		
		if ($vo->SEIContratoSubstituto != null) {
			$retorno .= $sqlConector . voContratoInfo::$nmAtrSEIContratoSubstituto . " = " 
					. $this->getVarComoString(voDemanda::getNumeroPRTSemMascara($vo->SEIContratoSubstituto));
			$sqlConector = ",";
		}else{
			$retorno .= $sqlConector . voContratoInfo::$nmAtrSEIContratoSubstituto . " = null ";
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $vo->getSQLValuesEntidadeUpdate ();
		
		return $retorno;
	}
}
?>