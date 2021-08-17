<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbContratoInfo extends dbprocesso {
	static $FLAG_PRINTAR_SQL = FALSE;
	
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPessoaGestor = "NM_TAB_PESSOA_GESTOR";
		$nmTabContratoSqMAX = "TAB_MAXCONTRATO";
		$nmTabelaContratoInfoRerra = voContratoInfo::$NM_TABELA_RERRA;
		
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
				"$nmTabelaContratoInfoRerra." . voContratoInfo::$NmColNumRerra,
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
		
		//indica se o contrato tem RERRA, para que seja chamada a atencao para qq alteracao contratual
		$dadosRerra = $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato 
		. "," . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato 
		. "," . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato
		/*. ",$nmTabelaContrato." . vocontrato::$nmAtrCdEspecieContrato
		. ",$nmTabelaContrato." . vocontrato::$nmAtrSqEspecieContrato*/
		;		
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= " (SELECT $dadosRerra, MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato
		. ", COUNT(*) AS " . voContratoInfo::$NmColNumRerra
		. " FROM " . $nmTabContratoInterna;
		$queryJoin .= " WHERE " . vocontrato::$nmAtrCdEspecieContrato . " = " . getVarComoString(dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RERRATIFICACAO);
		$queryJoin .= " AND " . voContratoInfo::$nmAtrInDesativado . "='N'";		
		$queryJoin .= " group by $dadosRerra) $nmTabelaContratoInfoRerra \n ON ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabelaContratoInfoRerra . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabelaContratoInfoRerra . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabelaContratoInfoRerra . "." . vocontrato::$nmAtrCdContrato;
		
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
				"$nmTabela.". voContratoInfo::$nmAtrInSeraProrrogado,
				"$nmTabela.". voContratoInfo::$nmAtrInPrazoProrrogacao,
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
	
	static function getQueryContratoOrdemParalisacao($nmTabelaPrincipal, $nmTabDadosOrdemParalisacao, $joinTabs, $queryAdicionalWhereInterno=null){
		$query = "select
		ct_dt_vigencia_fim,
		qtd,
		ADDDATE(ct_dt_vigencia_fim, INTERVAL qtd day)
		from contrato
		inner join (
				select MAX(sq) as sq from contrato
				where contrato.ct_exercicio = 2018
				and contrato.ct_numero = 23
				and contrato.ct_tipo = 'C'
				and contrato.ct_cd_especie in ('TA')
				) tab_atual
				on tab_atual.sq = contrato.sq
		
				inner join
		
				(select ct_dt_assinatura, ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie,
						SUM(DATEDIFF(ct_dt_vigencia_fim, ct_dt_vigencia_inicio)) as qtd from contrato
						where contrato.ct_exercicio = 2018
						and contrato.ct_numero = 23
						and contrato.ct_tipo = 'C'
						and contrato.ct_cd_especie in ('OP')) TAB_OP
						ON contrato.ct_exercicio = TAB_OP.ct_exercicio
						and contrato.ct_numero = TAB_OP.ct_numero
						and contrato.ct_tipo = TAB_OP.ct_tipo
						and contrato.ct_dt_assinatura <= TAB_OP.ct_dt_assinatura";
		
		$nmTabelaContrato = vocontrato::getNmTabela();
		//$nmTabContratoATUAL = "NM_TAB_CONTRATO_ATUAL_ORDEM_PARALISACAO";
		$nmTabContratoATUAL = filtroConsultarContratoConsolidacao::$NmTABDadosContratoOrdemParalisacao;
		
		$nmTabINTERNA_OP = "NM_TAB_INTERNA_OP";
		$nmDataFinal = vocontrato::$nmAtrDtVigenciaFinalContrato;
		$nmDataInicial = vocontrato::$nmAtrDtVigenciaInicialContrato;
		$nmDataAssinatura = vocontrato::$nmAtrDtAssinaturaContrato;
		$qtd = filtroConsultarContratoConsolidacao::$NmColQtdDiasSomadosTermoAtual;
		
		$nmAnoContrato = vocontrato::$nmAtrAnoContrato;
		$nmTipoContrato = vocontrato::$nmAtrTipoContrato;
		$nmNumeroContrato = vocontrato::$nmAtrCdContrato;
		$nmCdEspecie = vocontrato::$nmAtrCdEspecieContrato;
		$nmSqEspecie = vocontrato::$nmAtrSqEspecieContrato;
		$atributosinterno = "$nmAnoContrato,$nmTipoContrato,$nmNumeroContrato,$nmCdEspecie,$nmSqEspecie";
		
		$whereInterno = vocontrato::$nmAtrCdEspecieContrato . " <> " . getVarComoString(dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_ORDEM_PARALISACAO)
		. " AND " . vocontrato::$nmAtrCdEspecieContrato . " IN (" . getSQLStringFormatadaColecaoIN(dominioEspeciesContrato::getColecaoTermosQuePodemAlterarVigencia(), true) . ")";
		
		$query = " SELECT
		$nmTabINTERNA_OP.$nmAnoContrato,
		$nmTabINTERNA_OP.$nmTipoContrato,
		$nmTabINTERNA_OP.$nmNumeroContrato,
		$nmTabINTERNA_OP.$nmCdEspecie,
		$nmTabINTERNA_OP.$nmSqEspecie,
		$nmTabContratoATUAL.$nmDataFinal,
		$qtd,
		
		ADDDATE($nmTabContratoATUAL.$nmDataFinal, INTERVAL $qtd day) AS " . filtroConsultarContratoConsolidacao::$NmColDtFimVigenciaOP
		. "
		FROM $nmTabelaContrato"
		//o join nao pode ver vigencia porque ordem de paralisacao adia o contrato pra alem de sua vigencia
		.static::getQueryContratoTermoAtual($nmTabelaContrato, $nmTabContratoATUAL, " INNER JOIN ", $whereInterno, false)
		." INNER JOIN				
				(SELECT $nmDataAssinatura, $atributosinterno,
						SUM(DATEDIFF($nmDataFinal, $nmDataInicial)) as $qtd from $nmTabelaContrato
						WHERE "	
						.vocontrato::$nmAtrCdEspecieContrato
						." = (".getVarComoString(dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_ORDEM_PARALISACAO)
						.")) $nmTabINTERNA_OP
						ON $nmTabelaContrato.$nmAnoContrato = $nmTabINTERNA_OP.$nmAnoContrato
						and $nmTabelaContrato.$nmNumeroContrato = $nmTabINTERNA_OP.$nmNumeroContrato
						and $nmTabelaContrato.$nmTipoContrato = $nmTabINTERNA_OP.$nmTipoContrato
						and $nmTabelaContrato.$nmDataAssinatura <= $nmTabINTERNA_OP.$nmDataAssinatura ";
		
		$query = " $joinTabs (SELECT * FROM ($query) tab_temp_interno ) $nmTabDadosOrdemParalisacao ";
		$query .= " ON $nmTabelaPrincipal.$nmAnoContrato = $nmTabDadosOrdemParalisacao.$nmAnoContrato
						and $nmTabelaPrincipal.$nmNumeroContrato = $nmTabDadosOrdemParalisacao.$nmNumeroContrato
						and $nmTabelaPrincipal.$nmTipoContrato = $nmTabDadosOrdemParalisacao.$nmTipoContrato 
						and $nmTabelaPrincipal.$nmCdEspecie = $nmTabDadosOrdemParalisacao.$nmCdEspecie
						and $nmTabelaPrincipal.$nmSqEspecie = $nmTabDadosOrdemParalisacao.$nmSqEspecie ";
		//ECHO $query;
		return	$query;
		
	}
	
	static function getQueryContratoTermoAtual($nmTabelaPrincipal, $nmTabContratoATUAL, $joinTabs, $queryAdicionalWhereInterno=null, $mantendoFiltroGeral = true){
		$nmTabContratoAtual = $nmTabContratoMAXSq = "TAB_CONTRATO_MAX_SQ";

		$campo_subst = constantes::$CD_CAMPO_SUBSTITUICAO;
				
		if($queryAdicionalWhereInterno != null){
			if(!$mantendoFiltroGeral ){
				$campo_subst = " WHERE ";
			}else{
				//acrescenta o query adicional e mantem o campo substituicao pra substituir o filtro interno, quando necessario
				//o query externo ja terah o WHERE
				$queryAdicionalWhereInterno = " AND $queryAdicionalWhereInterno ";				
			}
		}
		
		$queryAdicionalWhereInterno = $campo_subst . " " . $queryAdicionalWhereInterno;
				
		$nmTabContratoInterna = vocontrato::getNmTabela();
		$groupbyinterno = $nmTabContratoInterna . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabContratoInterna . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabContratoInterna . "." . vocontrato::$nmAtrTipoContrato;
		
		$queryJoin .= " $joinTabs (SELECT " . $groupbyinterno . ", MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato
		. " FROM $nmTabContratoInterna $queryAdicionalWhereInterno GROUP BY " . $groupbyinterno;
		
		//echo $queryAdicionalWhereInterno;
		$queryJoin .= "\n) " . $nmTabContratoMAXSq;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPrincipal . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoMAXSq . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND $nmTabelaPrincipal." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoMAXSq . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND $nmTabelaPrincipal." .vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoMAXSq . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n LEFT JOIN $nmTabelaPrincipal $nmTabContratoATUAL";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabContratoMAXSq . "." . vocontrato::$nmAtrSqContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrSqContrato;
				
		return $queryJoin;		
	} 
		
	function consultarTelaConsultaConsolidacao($filtro) {		
		
		$vo = new vocontrato();		
		$isHistorico = $filtro->isHistorico;
		//$isHistorico = false;
		$nmTabela = $vo->getNmTabelaEntidade ( false );			
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( $isHistorico );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPessoaGestorContratoInfo = filtroManterContrato::$NM_TAB_PESSOA_GESTOR;
		$nmTabelaOrgaoGestor = vogestor::getNmTabela();
		//$nmTabDadosOrdemParalisacao = filtroConsultarContratoConsolidacao::$NmTABDadosOrdemParalisacao;
		//echo "tabela vo: $nmTabela | tabela contrato_info: $nmTabelaContratoInfo";
		
		$nmTabContratoMater = filtroConsultarContratoConsolidacao::$NmTabContratoMater;
		$nmTabContratoATUAL = filtroConsultarContratoConsolidacao::$NmTabContratoATUAL;
		$nmTabDemandaContratoATUAL = filtroConsultarContratoConsolidacao::$NmTabDemandaContratoATUAL;
		$nmTabDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		
		$nmAtrTempContratoAtualCDEspecie = $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdEspecieContrato;
		$nmAtrTempContratoAtualSqEspecie = $nmTabContratoATUAL . "." . vocontrato::$nmAtrSqEspecieContrato;
		
		$atributoProrrogavel = filtroConsultarContratoConsolidacao::getSQLComparacaoPrazoProrrogacao(dominioProrrogacaoFiltroConsolidacao::$CD_PRORROGAVEL);
		$atributoProrrogavelExcepcional = filtroConsultarContratoConsolidacao::getSQLComparacaoPrazoProrrogacao(dominioProrrogacaoFiltroConsolidacao::$CD_PERMITE_EXCEPCIONAL);
		$inAtributoSeraProrrogado = "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrInSeraProrrogado;
		$inPrazoProrrogacao = voContratoInfo::$nmAtrInPrazoProrrogacao;
		
		$arrayCoalesceGestor = array(
				"$nmTabelaPessoaGestorContratoInfo." . vopessoa::$nmAtrNome,
				"$nmTabContratoATUAL." . vocontrato::$nmAtrGestorContrato);
		
		//faz as operacoes nas datas de vigencia
		$nmTempAtributoDtVigenciaInicio = $nmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato;	
		//corrige para o caso da data ser inserida em formato errado antigo da planilha
		$nmTempAtributoDtVigenciaInicio = filtroConsultarContratoConsolidacao::getComparacaoWhereDataVigencia($nmTempAtributoDtVigenciaInicio);
		//somente a data de vigencia pode ser alterada pelas ordens de paralisacao
		$nmTempAtributoDtVigenciaFim = filtroConsultarContratoConsolidacao::getAtributoDtFimVigenciaConsolidacao();
				
		$arrayColunasRetornadas = array (
				$nmTabela . "." . vocontrato::$nmAtrAnoContrato,
				$nmTabela . "." . vocontrato::$nmAtrCdContrato,
				$nmTabela . "." . vocontrato::$nmAtrTipoContrato,
				$nmTabContratoMater . "." . vocontrato::$nmAtrSqContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqContratoMater,
				$nmTabContratoMater . "." . vocontrato::$nmAtrObjetoContrato,
				$nmTabContratoATUAL . "." . vocontrato::$nmAtrVlMensalContrato,
				$nmTabContratoATUAL . "." . vocontrato::$nmAtrVlGlobalContrato,
				$nmTabContratoATUAL . "." . vocontrato::$nmAtrSqContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqContratoAtual,
				//$nmTabContratoATUAL . "." . vocontrato::$nmAtrCdEspecieContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColCdEspecieContratoAtual,
				"$nmAtrTempContratoAtualCDEspecie AS " . filtroConsultarContratoConsolidacao::$NmColCdEspecieContratoAtual,				
				//$nmTabContratoATUAL . "." . vocontrato::$nmAtrSqEspecieContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqEspecieContratoAtual,
				"$nmAtrTempContratoAtualSqEspecie AS " . filtroConsultarContratoConsolidacao::$NmColSqEspecieContratoAtual,
				
				getSQLCOALESCE($arrayCoalesceGestor, vocontrato::$nmAtrGestorContrato),
				
				" $nmTempAtributoDtVigenciaInicio AS " . filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia,				
				" $nmTempAtributoDtVigenciaFim AS " . filtroConsultarContratoConsolidacao::$NmColDtFimVigencia,
				//repete as colunas para trazer com outro nome - usado na consulta por chave de contratoinfo quando precisar consolidar
				" $nmTempAtributoDtVigenciaInicio AS " . vocontrato::$nmAtrDtVigenciaInicialContrato,
				" $nmTempAtributoDtVigenciaFim AS " . vocontrato::$nmAtrDtVigenciaFinalContrato,

								
				getDataSQLDiferencaDias(getVarComoDataSQL(getDataHoje()), $nmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato) . " AS " . filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento,
				filtroConsultarContratoConsolidacao::getSQLQtdAnosVigenciaContrato() . " AS " . filtroConsultarContratoConsolidacao::$NmColPeriodoEmAnos,
				
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
				
		if ($isHistorico) {		
			$arrayColunasHistorico = array (
					"$nmTabelaContratoInfo.". voContratoInfo::$nmAtrSqHist,
			);				
			$arrayColunasRetornadas = array_merge($arrayColunasRetornadas, $arrayColunasHistorico);
		}
			
		$groupbyinterno = $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;	
		
		$nmTabContratoInterna = $nmTabela;		
		$nmTabContratoMINSq = "TAB_CONTRATO_MIN_SQ";
		//$nmTabContratoMAXSq = "TAB_CONTRATO_MAX_SQ";
		
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
		//$filtro = new filtroConsultarContratoConsolidacao();
		$jointemp = "LEFT";
		$tipoJoinINNER = isAtributoValido($filtro->tpVigencia) || $filtro->inProduzindoEfeitos == dominioContratoProducaoEfeitos::$CD_VISTO_COM_EFEITOS;
		if($tipoJoinINNER){
			$jointemp = "INNER";
		}
		$joinTabs = "\n $jointemp JOIN ";
		
		//$queryJoin .= "\n $jointemp JOIN ";
		$queryJoin .= static::getQueryContratoTermoAtual($nmTabela, $nmTabContratoATUAL, $joinTabs);
			
		//pega as informacos em contrato_info do contrato atual
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrTipoContrato;
		/*$queryJoin .= "\n AND ";
		$queryJoin .= "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrInDesativado . "='N'";*/
		
		$queryJoin .= "\n LEFT JOIN $nmTabelaPessoaContrato $nmTabelaPessoaGestorContratoInfo ";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaGestorContratoInfo . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdPessoaGestor;
		$queryJoin .= " AND " . $nmTabelaPessoaGestorContratoInfo . "." . vopessoa::$nmAtrInDesativado . "= 'N'";
		
		$nmTabelaPessoaGestor = vopessoagestor::getNmTabela();
		$queryJoin .= "\n LEFT JOIN $nmTabelaPessoaGestor ";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaGestorContratoInfo . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaGestor . "." . vopessoa::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN $nmTabelaOrgaoGestor ";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaGestor . "." . vopessoagestor::$nmAtrCdGestor . "=" . $nmTabelaOrgaoGestor . "." . vogestor::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		//PEGA A QTD DE DIAS SOMADAS DE ORDEM DE SUSPENSAO PRA ACRESCENTAR NO TERMO ATUAL
		//a ordem de suspensao so tera efeito na vigencia quando forem o termo atual
		//posto que, havendo TA posterior, este ja estara considerando o periodo alterado pela ordem de paralisacao
		//$queryJoin .= static::getQueryContratoOrdemParalisacao($nmTabContratoATUAL, $nmTabDadosOrdemParalisacao, " LEFT JOIN ");	
			
			$queryJoin .= "\n LEFT JOIN $nmTabDemandaContrato $nmTabDemandaContratoATUAL";
			$queryJoin .= "\n ON ";
			$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrAnoContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrTipoContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdEspecieContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrSqEspecieContrato;
	
		$cdCampoSubstituir = $filtro->getSQLTermoMaiorSqVigencia($nmTabContratoInterna);
		//substitui o sql no join do MAXCONTRATO
		$queryJoin = str_replace(constantes::$CD_CAMPO_SUBSTITUICAO, $cdCampoSubstituir . $filtro::$CD_CAMPO_SUBSTITUICAO, $queryJoin);
		//$queryJoin = str_replace(constantes::$CD_CAMPO_SUBSTITUICAO, $cdCampoSubstituir, $queryJoin);
		
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
		$retorno .= $this->getVarComoString($vo->inPendencias). ",";
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
	
	
	function createViewContratoConsolidacao() {
		$filtro = new filtroConsultarContratoConsolidacao(false);
		$vo = new vocontrato();
		$isHistorico = $filtro->isHistorico;
		//$isHistorico = false;
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( $isHistorico );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPessoaGestorContratoInfo = filtroManterContrato::$NM_TAB_PESSOA_GESTOR;
		//$nmTabDadosOrdemParalisacao = filtroConsultarContratoConsolidacao::$NmTABDadosOrdemParalisacao;
		//echo "tabela vo: $nmTabela | tabela contrato_info: $nmTabelaContratoInfo";
	
		$nmTabContratoMater = filtroConsultarContratoConsolidacao::$NmTabContratoMater;
		$nmTabContratoATUAL = filtroConsultarContratoConsolidacao::$NmTabContratoATUAL;
		$nmTabDemandaContratoATUAL = filtroConsultarContratoConsolidacao::$NmTabDemandaContratoATUAL;
		$nmTabDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
	
		$nmAtrTempContratoAtualCDEspecie = $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdEspecieContrato;
		$nmAtrTempContratoAtualSqEspecie = $nmTabContratoATUAL . "." . vocontrato::$nmAtrSqEspecieContrato;
	
		$atributoProrrogavel = filtroConsultarContratoConsolidacao::getSQLComparacaoPrazoProrrogacao(dominioProrrogacaoFiltroConsolidacao::$CD_PRORROGAVEL);
		$atributoProrrogavelExcepcional = filtroConsultarContratoConsolidacao::getSQLComparacaoPrazoProrrogacao(dominioProrrogacaoFiltroConsolidacao::$CD_PERMITE_EXCEPCIONAL);
		$inAtributoSeraProrrogado = "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrInSeraProrrogado;
		$inPrazoProrrogacao = voContratoInfo::$nmAtrInPrazoProrrogacao;
	
		$arrayCoalesceGestor = array(
				"$nmTabelaPessoaGestorContratoInfo." . vopessoa::$nmAtrNome,
				"$nmTabContratoATUAL." . vocontrato::$nmAtrGestorContrato);
	
		//faz as operacoes nas datas de vigencia
		$nmTempAtributoDtVigenciaInicio = $nmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato;
		//corrige para o caso da data ser inserida em formato errado antigo da planilha
		$nmTempAtributoDtVigenciaInicio = filtroConsultarContratoConsolidacao::getComparacaoWhereDataVigencia($nmTempAtributoDtVigenciaInicio);
		//somente a data de vigencia pode ser alterada pelas ordens de paralisacao
		$nmTempAtributoDtVigenciaFim = filtroConsultarContratoConsolidacao::getAtributoDtFimVigenciaConsolidacao();
	
		$arrayColunasRetornadas = array (
				$nmTabela . "." . vocontrato::$nmAtrAnoContrato,
				$nmTabela . "." . vocontrato::$nmAtrCdContrato,
				$nmTabela . "." . vocontrato::$nmAtrTipoContrato,
				$nmTabContratoMater . "." . vocontrato::$nmAtrSqContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqContratoMater,
				$nmTabContratoMater . "." . vocontrato::$nmAtrObjetoContrato,
				$nmTabContratoATUAL . "." . vocontrato::$nmAtrVlMensalContrato,
				$nmTabContratoATUAL . "." . vocontrato::$nmAtrVlGlobalContrato,
				$nmTabContratoATUAL . "." . vocontrato::$nmAtrSqContrato . " AS " . filtroConsultarContratoConsolidacao::$NmColSqContratoAtual,

				"$nmAtrTempContratoAtualCDEspecie AS " . filtroConsultarContratoConsolidacao::$NmColCdEspecieContratoAtual,
				"$nmAtrTempContratoAtualSqEspecie AS " . filtroConsultarContratoConsolidacao::$NmColSqEspecieContratoAtual,
	
				getSQLCOALESCE($arrayCoalesceGestor, vocontrato::$nmAtrGestorContrato),
	
				" $nmTempAtributoDtVigenciaInicio AS " . filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia,
				" $nmTempAtributoDtVigenciaFim AS " . filtroConsultarContratoConsolidacao::$NmColDtFimVigencia,
				//repete as colunas para trazer com outro nome - usado na consulta por chave de contratoinfo quando precisar consolidar
				" $nmTempAtributoDtVigenciaInicio AS " . vocontrato::$nmAtrDtVigenciaInicialContrato,
				" $nmTempAtributoDtVigenciaFim AS " . vocontrato::$nmAtrDtVigenciaFinalContrato,
		
				getDataSQLDiferencaDias(getVarComoDataSQL(getDataHoje()), $nmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato) . " AS " . filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento,
				filtroConsultarContratoConsolidacao::getSQLQtdAnosVigenciaContrato() . " AS " . filtroConsultarContratoConsolidacao::$NmColPeriodoEmAnos,
	
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
	
		if ($isHistorico) {
			$arrayColunasHistorico = array (
					"$nmTabelaContratoInfo.". voContratoInfo::$nmAtrSqHist,
			);
			$arrayColunasRetornadas = array_merge($arrayColunasRetornadas, $arrayColunasHistorico);
		}
			
		$groupbyinterno = $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
	
		$nmTabContratoInterna = $nmTabela;
		$nmTabContratoMINSq = "TAB_CONTRATO_MIN_SQ";
		//$nmTabContratoMAXSq = "TAB_CONTRATO_MAX_SQ";
	
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
	
		$jointemp = "LEFT";
		$joinTabs = "\n $jointemp JOIN ";	

		$queryJoin .= static::getQueryContratoTermoAtual($nmTabela, $nmTabContratoATUAL, $joinTabs);
			
		//pega as informacos em contrato_info do contrato atual
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrTipoContrato;
			
		$queryJoin .= "\n LEFT JOIN $nmTabelaPessoaContrato $nmTabelaPessoaGestorContratoInfo ";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaGestorContratoInfo . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdPessoaGestor;
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdPessoaContratada;
	
		//PEGA A QTD DE DIAS SOMADAS DE ORDEM DE SUSPENSAO PRA ACRESCENTAR NO TERMO ATUAL
		//a ordem de suspensao so tera efeito na vigencia quando forem o termo atual
		//posto que, havendo TA posterior, este ja estara considerando o periodo alterado pela ordem de paralisacao
		//$queryJoin .= static::getQueryContratoOrdemParalisacao($nmTabContratoATUAL, $nmTabDadosOrdemParalisacao, " LEFT JOIN ");
			
		$queryJoin .= "\n LEFT JOIN $nmTabDemandaContrato $nmTabDemandaContratoATUAL";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdEspecieContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabDemandaContratoATUAL . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrSqEspecieContrato;
		
		$filtro->groupby = $groupbyinterno;
		$filtro->isRetornarQueryCompleta = true;
	
		$colecao = parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
		
		$sqlView = $filtro->getSQL_QUERY_COMPLETA();
		$nmView = filtroConsultarContratoConsolidacao::$NM_VIEW_CONTRATO_CONSOLIDACAO;
		$sqlView = "CREATE VIEW $nmView AS $sqlView;";
		$this->atualizarEntidade($sqlView);				
	}
}
?>