<?php
include_once (caminho_lib . "dbprocesso.obj.php");
include_once ("vocontrato.php");
class dbDemanda extends dbprocesso {
	static $FLAG_PRINTAR_SQL = false;
	
	function consultarPorChaveTelaColecaoContrato($vo, $isHistorico) {
		try {
			// para o caso de so haver um contrato, o consultarPorChaveTelaJoinContrato traz apenas um registro
			// como realmente deve ser
			$colecao = $this->consultarPorChaveTelaJoinContrato ( $vo, $isHistorico, true );
			$voContrato = new vocontrato ();
			$voContrato->getDadosBanco ( $colecao );
			//echo $voContrato->docContratada;
			
			$voSolicCompra = new voSolicCompra();
			$voSolicCompra->getDadosBanco ( $colecao );
			$vo->voSolicCompra = $voSolicCompra;
				
			$voProcLic = new voProcLicitatorio ();
			$voProcLic->getDadosBanco ( $colecao );
			$vo->voProcLicitatorio = $voProcLic;
			
			$voPA = new voPA ();
			$voPA->getDadosBanco ( $colecao );
			$vo->voPA = $voPA;
			
			$vo->getDadosBanco ( $colecao );
			$temContrato = $voContrato->cdContrato != null;
			if ($temContrato) {
				$vo->colecaoContrato = array (
						$voContrato 
				);
			}
		} catch ( excecaoMaisDeUmRegistroRetornado $ex ) {
			// faz consulta a parte
			// nao valida a consulta por chave
			$colecao = $this->consultarPorChaveTelaJoinContrato ( $vo, $isHistorico, false );
			// pega os dados do vo que vao ser iguais para qualquer registro.
			$vo->getDadosBanco ( $colecao [0] );
			$colecaoContrato = $this->consultarDemandaContrato ( $vo );
			$vo->setColecaoContratoRegistroBanco ( $colecaoContrato );
		}
		
		return $vo;
	}
	function consultarPorChaveTelaJoinContrato($vo, $isHistorico, $isConsultaPorChave, $isTrazerMaisDadosContrato=false) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		$nmTabelaDemandaProcLic = voDemandaPL::getNmTabelaStatic ( false );
		$nmTabelaDemandaSolicCompra = voDemandaSolicCompra::getNmTabelaStatic ( false );
		$nmTabelaSolicCompra = voSolicCompra::getNmTabelaStatic ( false );
		$nmTabelaProcLic = voProcLicitatorio::getNmTabelaStatic ( false );
		$nmTabelaPA = voPA::getNmTabelaStatic ( false );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela ();
		// $nmTabelaPAAP = voPA::getNmTabelaStatic ( false );
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtAssinaturaContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrVlMensalContrato,
				//$nmTabelaContrato . "." . vocontrato::$nmAtrVlGlobalContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaInicialContrato,
				$nmTabelaPessoa . "." . vopessoa::$nmAtrDoc,
				
				$nmTabelaSolicCompra . "." . voSolicCompra::$nmAtrCd,
				$nmTabelaSolicCompra . "." . voSolicCompra::$nmAtrAno,
				$nmTabelaSolicCompra . "." . voSolicCompra::$nmAtrUG,
				$nmTabelaSolicCompra . "." . voSolicCompra::$nmAtrObjeto,				

				$nmTabelaDemandaProcLic . "." . voProcLicitatorio::$nmAtrCd,
				$nmTabelaDemandaProcLic . "." . voProcLicitatorio::$nmAtrAno,
				$nmTabelaDemandaProcLic . "." . voProcLicitatorio::$nmAtrCdModalidade,
				$nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrNumModalidade,
				$nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrObjeto,
				$nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCdPregoeiro,
				$nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCdCPL,
				// $nmTabelaPessoa . "." . vopessoa::$nmAtrNome,
				getSQLNmContratada (),
				"COALESCE ($nmTabelaTramitacao." . voDemandaTramitacao::$nmAtrCdSetorDestino . ",$nmTabela." . voDemanda::$nmAtrCdSetor . ") AS " . voDemanda::$nmAtrCdSetorAtual 
		);
		
		if($isTrazerMaisDadosContrato){
			$arrayColunasRetornadas[] = $nmTabelaContrato . "." . vocontrato::$nmAtrObjetoContrato;
		}
		
		$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno;
		// o proximo join eh p pegar a ultima tramitacao apenas, se houver
		$queryJoin = "";
		$queryJoin .= "\n LEFT JOIN (";
		$queryJoin .= " SELECT MAX(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq . "," . $atributosGroup . " FROM " . $nmTabelaTramitacao . " GROUP BY " . $atributosGroup;
		$queryJoin .= ") TABELA_MAX";
		$queryJoin .= "\n ON " . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaTramitacao;
		$queryJoin .= "\n ON " . $nmTabela . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabela . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd;
		$queryJoin .= "\n AND " . "TABELA_MAX." . voDemandaTramitacao::$nmAtrSq . " = " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrSq;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaProcLic;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrCd;
				
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaProcLic;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCd;
		$queryJoin .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdModalidadeProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCdModalidade;
		
		//SOLIC COMPRA
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaSolicCompra;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaSolicCompra . "." . voDemandaSolicCompra::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaSolicCompra . "." . voDemandaSolicCompra::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaSolicCompra;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaSolicCompra . "." . voDemandaSolicCompra::$nmAtrAnoSolicCompra . "=" . $nmTabelaSolicCompra . "." . voSolicCompra::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaSolicCompra . "." . voDemandaSolicCompra::$nmAtrCdSolicCompra . "=" . $nmTabelaSolicCompra . "." . voSolicCompra::$nmAtrCd;
		$queryJoin .= "\n AND " . $nmTabelaDemandaSolicCompra . "." . voDemandaSolicCompra::$nmAtrUG . "=" . $nmTabelaSolicCompra . "." . voSolicCompra::$nmAtrUG;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPA;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPA . "." . voPA::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaPA . "." . voPA::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoa;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoa . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		/*
		 * $queryJoin .= "\n LEFT JOIN " . $nmTabelaPAAP;
		 * $queryJoin .= "\n ON ";
		 * $queryJoin .= $nmTabelaPAAP . "." . voPA::$nmAtrCdDemanda. "=" . $nmTabela . "." . voDemanda::$nmAtrCd;
		 * $queryJoin .= "\n AND ";
		 * $queryJoin .= $nmTabelaPAAP . "." . voPA::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrAno;
		 */
		
		$colecao = $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, $isConsultaPorChave );
		return $colecao;
	}
	
/**
 * consulta da tela de demanda rendimento
 * {@inheritDoc}
 */
	function consultarTelaConsultaRendimentoDemanda($filtro) {
		$voPrincipal = new voDemandaTramitacao();		
		$nmTabela = $voPrincipal->getNmTabela();
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
		$nmTabelaDemandaPL = voDemandaPL::getNmTabelaStatic(false);
		$nmTabelaPL = voProcLicitatorio::getNmTabelaStatic(false);
		$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
				
		if($filtro->groupby == null){
			$filtro->groupby = voDemandaTramitacao::$nmAtrCdSetor;
		}
		
		$mes = "MONTH(".voDemandaTramitacao::$nmAtrDtReferencia.")";
		$mesColuna = $mes . " AS " . voDemanda::$nmAtrDtReferencia;
		$arrayColunasRetornadasEntrada = array (
				voDemanda::$nmAtrTipo,
				voDemandaTramitacao::$nmAtrCdSetorDestino . " AS " . vodemanda::$nmAtrCdSetor,
				$mesColuna,
				"1 AS " . filtroConsultarDemandaRendimento::$NmColNuEntradas,
				"0 AS " . filtroConsultarDemandaRendimento::$NmColNuSaidas,
				"0 AS " . filtroConsultarDemandaRendimento::$NmColNumTotalDemandas,
	    );
		
		$arrayColunasRetornadasSaida = array (
				voDemanda::$nmAtrTipo,
				voDemandaTramitacao::$nmAtrCdSetorOrigem . " AS " . vodemanda::$nmAtrCdSetor,
				$mesColuna,
				"0 AS " . filtroConsultarDemandaRendimento::$NmColNuEntradas,
				"1 AS " . filtroConsultarDemandaRendimento::$NmColNuSaidas,
				"0 AS " . filtroConsultarDemandaRendimento::$NmColNumTotalDemandas,
		);
		
		$arrayColunasRetornadasNuDemandas = array (
				voDemanda::$nmAtrTipo,
				"$nmTabela.".voDemandaTramitacao::$nmAtrCdSetorDestino . " AS " . vodemanda::$nmAtrCdSetor,
				$mesColuna,
				"0 AS " . filtroConsultarDemandaRendimento::$NmColNuEntradas,
				"0 AS " . filtroConsultarDemandaRendimento::$NmColNuSaidas,
				"1 AS " . filtroConsultarDemandaRendimento::$NmColNumTotalDemandas,
		);
		
		$isTpContratoSelecionado = $filtro->vocontrato->tipo != null && $filtro->vocontrato->tipo != "";
		$iscdCPLSelecionado = $filtro->voproclic->cdCPL != null && $filtro->voproclic->cdCPL != "";
		$isInORAND_AND = $filtro->inOR_AND == constantes::$CD_OPCAO_AND;
		//echo "andor $isInORAND_AND" . $filtro->inOR_AND;
		
		//se o AND foi acionado, toda a consulta vai ser dar pela tabela de contrato
		/*if($isInORAND_AND && ($isTpContratoSelecionado||$iscdCPLSelecionado)){
			$joinDemandaContrato .= "\n LEFT JOIN " . $nmTabelaDemandaContrato;
			$joinDemandaContrato .= "\n ON ";
			$joinDemandaContrato .= "$nmTabelaDemanda." . voDemanda::$nmAtrAno . "=$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrAnoDemanda;
			$joinDemandaContrato .= "\n AND $nmTabelaDemanda." . voDemanda::$nmAtrCd . "=$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrCdDemanda;		
			$joinDemandaContrato .= "\n LEFT JOIN " . $nmTabelaContrato;
			$joinDemandaContrato .= "\n ON ";
			$joinDemandaContrato .= "$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrAnoContrato . "=$nmTabelaContrato." . vocontrato::$nmAtrAnoContrato;
			$joinDemandaContrato .= "\n AND ";
			$joinDemandaContrato .= "$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrCdContrato . "=$nmTabelaContrato." . vocontrato::$nmAtrCdContrato;
			$joinDemandaContrato .= "\n AND ";
			$joinDemandaContrato .= "$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrTipoContrato . "=$nmTabelaContrato." . vocontrato::$nmAtrTipoContrato;
			$joinDemandaContrato .= "\n AND ";
			$joinDemandaContrato .= "$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrCdEspecieContrato . "=$nmTabelaContrato." . vocontrato::$nmAtrCdEspecieContrato;
			$joinDemandaContrato .= "\n AND ";
			$joinDemandaContrato .= "$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrSqEspecieContrato . "=$nmTabelaContrato." . vocontrato::$nmAtrSqEspecieContrato;		
		}else{*/
			if($isTpContratoSelecionado){
				$joinDemandaContrato .= "\n LEFT JOIN " . $nmTabelaDemandaContrato;
				$joinDemandaContrato .= "\n ON ";
				$joinDemandaContrato .= "$nmTabelaDemanda." . voDemanda::$nmAtrAno . "=$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrAnoDemanda;
				$joinDemandaContrato .= "\n AND $nmTabelaDemanda." . voDemanda::$nmAtrCd . "=$nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrCdDemanda;
			}
			
			if($iscdCPLSelecionado){
				$joinDemandaPL .= "\n LEFT JOIN " . $nmTabelaDemandaPL;
				$joinDemandaPL .= "\n ON ";
				$joinDemandaPL .= "$nmTabelaDemanda." . voDemanda::$nmAtrAno . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrAnoDemanda;
				$joinDemandaPL .= "\n AND $nmTabelaDemanda." . voDemanda::$nmAtrCd . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdDemanda;
				$joinDemandaPL .= "\n LEFT JOIN " . $nmTabelaPL;
				$joinDemandaPL .= "\n ON ";
				$joinDemandaPL .= "$nmTabelaPL." . voProcLicitatorio::$nmAtrAno . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrAnoProcLic;
				$joinDemandaPL .= "\n AND $nmTabelaPL." . voProcLicitatorio::$nmAtrCd . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdProcLic;
				$joinDemandaPL .= "\n AND $nmTabelaPL." . voProcLicitatorio::$nmAtrCdModalidade . "=$nmTabelaDemandaPL." . voDemandaPL::$nmAtrCdModalidadeProcLic;
			}
				
		//}		
		
		$joinComum .= "\n INNER JOIN " . $nmTabelaDemanda;
		$joinComum .= "\n ON ";
		$joinComum .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$joinComum .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		$joinComum .= $joinDemandaContrato;
		$joinComum .= $joinDemandaPL;
				
		$queryEntrada = "SELECT ";
		$queryEntrada .= getSQLStringFormatadaColecaoIN($arrayColunasRetornadasEntrada);
		$queryEntrada .= " FROM $nmTabela ";
		$queryEntrada .= $joinComum;
		$queryEntrada .= filtroManter::$CD_CAMPO_SUBSTITUICAO;
		
		$querySaida = "SELECT ";
		$querySaida .= getSQLStringFormatadaColecaoIN($arrayColunasRetornadasSaida);
		$querySaida .= " FROM $nmTabela ";
		$querySaida .= $joinComum;
		$querySaida .= filtroManter::$CD_CAMPO_SUBSTITUICAO;
		
		$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno . "," . voDemandaTramitacao::$nmAtrCdSetorDestino;
		$nmatrsqtram = voDemandaTramitacao::$nmAtrSq;
		$nmTabDemandaMinPorSetor = filtroConsultarDemandaRendimento::$NmTabelaTramitacaoMininaPorSetor;
		$queryNumDemandas = "SELECT ";
		$queryNumDemandas .= getSQLStringFormatadaColecaoIN($arrayColunasRetornadasNuDemandas);
		$queryNumDemandas .= " FROM $nmTabelaDemanda ";		
		$queryNumDemandas .= $joinDemandaContrato;
		$queryNumDemandas .= $joinDemandaPL;
		$queryNumDemandas .= "\n INNER JOIN (SELECT MIN($nmatrsqtram) AS $nmatrsqtram,$atributosGroup FROM $nmTabela group by $atributosGroup) $nmTabDemandaMinPorSetor";
		$queryNumDemandas .= "\n ON ";
		$queryNumDemandas .= "$nmTabelaDemanda." . voDemanda::$nmAtrAno . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrAno;
		$queryNumDemandas .= "\n AND $nmTabelaDemanda." . voDemanda::$nmAtrCd . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrCd;		
		$queryNumDemandas .= "\n INNER JOIN " . $nmTabela;
		$queryNumDemandas .= "\n ON ";
		$queryNumDemandas .= "$nmTabela." . voDemandaTramitacao::$nmAtrAno . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrAno;
		$queryNumDemandas .= "\n AND $nmTabela." . voDemandaTramitacao::$nmAtrCd . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrCd;
		$queryNumDemandas .= "\n AND $nmTabela." . voDemandaTramitacao::$nmAtrSq . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrSq;
		
		$queryNumDemandas .= filtroManter::$CD_CAMPO_SUBSTITUICAO;
		
		$numSaidas = "SUM(".filtroConsultarDemandaRendimento::$NmTabelaRendimento . ".". filtroConsultarDemandaRendimento::$NmColNuSaidas .") AS ";
		$numSaidas .= filtroConsultarDemandaRendimento::$NmColNuSaidas;
		$numEntradas = "SUM(".filtroConsultarDemandaRendimento::$NmTabelaRendimento . ".".filtroConsultarDemandaRendimento::$NmColNuEntradas.") AS ";
		$numEntradas .= filtroConsultarDemandaRendimento::$NmColNuEntradas;
		$numDemandas = "SUM(".filtroConsultarDemandaRendimento::$NmTabelaRendimento . ".".filtroConsultarDemandaRendimento::$NmColNumTotalDemandas .") AS ";
		$numDemandas .= filtroConsultarDemandaRendimento::$NmColNumTotalDemandas;
		
		$arrayColunasRetornadas = array (
				vodemanda::$nmAtrCdSetor,
				vodemanda::$nmAtrDtReferencia,
				$numEntradas,
				$numSaidas,
				$numDemandas,
		);
		
		$query = " SELECT ";
		$query .= getSQLStringFormatadaColecaoIN($arrayColunasRetornadas);
		$query .= " FROM ($queryEntrada UNION ALL $querySaida UNION ALL $queryNumDemandas) " . filtroConsultarDemandaRendimento::$NmTabelaRendimento;
		$query .= filtroConsultarDemandaRendimento::$CD_CAMPO_SUBSTITUICAO_PRINCIPAL;
		$query .= " GROUP BY " . $filtro->groupby;
		
		$filtroPrincipal = "";
		if($filtro->vodemanda->cdSetor != null){
			$filtroPrincipal = voDemanda::$nmAtrCdSetor . "=" . $filtro->vodemanda->cdSetor;
		}
		$arraySubstituicao = array(
				filtroManter::$CD_CAMPO_SUBSTITUICAO => $filtro->getSQLFiltroPreenchido(),
				filtroConsultarDemandaRendimento::$CD_CAMPO_SUBSTITUICAO_PRINCIPAL => $filtroPrincipal,
				
		);
				
		$filtro->sqlFiltrosASubstituir = $arraySubstituicao;
				
		if($filtro->cdAtrOrdenacao != null){
			$query .= " ORDER BY " . $filtro->cdAtrOrdenacao . " " . $filtro->cdOrdenacao;
		}
		//$query .= " ORDER BY " . voDemanda::$nmAtrDtReferencia;
				
		$retorno = parent::consultarFiltroPorSubstituicao($filtro, $query);
		
		return $retorno;
	}
	
	/**
 * consulta da tela de demanda gestao
 * {@inheritDoc}
 */
	function consultarTelaConsultaGestaoDemanda($filtro) {
		
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$dtDemandaTramEntrada = "$nmTabelaDemanda." . voDemanda::$nmAtrDtReferencia;
		$numDemandas = "COUNT(*)";
		
		$arrayColunasRetornadas = array (
				"$nmTabelaDemanda." . voDemanda::$nmAtrTipo,
				$dtDemandaTramEntrada,
				"$numDemandas  AS " . filtroConsultarDemandaGestao::$NmColNumTotalDemandas,
				"SUM(".filtroConsultarDemandaGestao::getSQLNuTempoVida($nmTabelaDemanda). ")/$numDemandas AS ". filtroConsultarDemandaGestao::$NmColNumTempoVidaMedio,
	    );
		
		/*$queryJoin .= "\n INNER JOIN " . $nmTabelaDemanda;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaDemTram . "." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaDemTram . "." . voDemandaTramitacao::$nmAtrCd;
		
		$queryJoin .= "\n INNER JOIN " . $nmTabelaUsuario;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaUsuario . "." . vousuario::$nmAtrID . "=" . $nmTabelaDemTram . "." . voDemanda::$nmAtrCdUsuarioInclusao;	*/	
		
		$arrayGroupby = array (
				$nmTabelaDemanda . "." . voDemanda::$nmAtrTipo, 
		);
				
		$filtro->groupby = $arrayGroupby;
		
		$retorno = parent::consultarMontandoQueryTelaConsulta ( new voDemanda(), $filtro, $arrayColunasRetornadas, $queryJoin );
		//return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
		//echo $filtro->getSQLFiltro();
		
		return $retorno;
	}
	
	/**
 * consulta da tela de detalhar demanda gestao
 * {@inheritDoc}
 */
	function consultarTelaGestaoDemandaDetalhePorTipo($filtro) {		
		
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabelaStatic ( false );
		$nmTabelaPrazoPorSetor = filtroConsultarDemandaGestao::$NmTabelaDemandaPrazoPorSetor;
	
		$arrayAtributosSetorDestino = array(
				"$nmTabelaTramitacao." . voDemandaTramitacao::$nmAtrCdSetorDestino,
				"$nmTabelaDemanda." . voDemanda::$nmAtrCdSetor,
		);
		$cdSetorDestino = getSQLCOALESCE($arrayAtributosSetorDestino);
		$somaTotalDemandas = "SUM(".filtroConsultarDemandaGestao::$NmColNumTotalDemandas.")";
		
				
		$queryJoin .= "SELECT " . voDemandaTramitacao::$nmAtrCdSetorDestino;
		$queryJoin .= ",$somaTotalDemandas AS " . filtroConsultarDemandaGestao::$NmColNumTotalDemandas;
		$queryJoin .= ",SUM(".filtroConsultarDemandaGestao::$NmColNuTempoVida.") AS " . filtroConsultarDemandaGestao::$NmColNuTempoVida;
		$queryJoin .= ",SUM(".filtroConsultarDemandaGestao::$NmColNuTempoVida.")/$somaTotalDemandas AS " . filtroConsultarDemandaGestao::$NmColNumTempoVidaMedio;
		$queryJoin .= ",SUM(".filtroConsultarDemandaGestao::$NmColInSetorAtualDemanda .") AS " . filtroConsultarDemandaGestao::$NmColNumTotalDemandasNoSetor;
		$queryJoin .= " FROM ";				
		
		$queryJoin .= "(SELECT ";
		$queryJoin .= "$cdSetorDestino AS " . voDemandaTramitacao::$nmAtrCdSetorDestino;
		$queryJoin .= "," . getSQLCASE($cdSetorDestino, "TAB_TEMP." . voDemandaTramitacao::$nmAtrCdSetorDestino, "1", "0") . " AS " . filtroConsultarDemandaGestao::$NmColInSetorAtualDemanda;
		$queryJoin .= ",1  AS " . filtroConsultarDemandaGestao::$NmColNumTotalDemandas; //cada linha representa uma demanda, dai o numero 1 para contar ao final o total de demandas por setor
		//$queryJoin .= ",SUM($nmTabelaPrazoPorSetor.".filtroConsultarDemandaGestao::$NmColNuTempoVida. ") AS ". filtroConsultarDemandaGestao::$NmColNuTempoVida;
		$queryJoin .= ",$nmTabelaPrazoPorSetor.".filtroConsultarDemandaGestao::$NmColNuTempoVida;
		$queryJoin .= " FROM $nmTabelaDemanda ";
		// agora pega dos dados da ultima tramitacao, se houver
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= $nmTabelaTramitacao;
		$queryJoin .= "\n ON " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . " = $nmTabelaDemanda." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd . " = $nmTabelaDemanda." . voDemandaTramitacao::$nmAtrCd;
		
		$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno;
		// o proximo join eh p pegar a ultima tramitacao apenas, se houver, PARA SABER EM QUE SETOR ESTA A DEMANDA
		$queryJoin .= "\n LEFT JOIN (";
		$queryJoin .= " SELECT MAX(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq . "," . $atributosGroup . " FROM " . $nmTabelaTramitacao . " GROUP BY " . $atributosGroup;
		$queryJoin .= ") TABELA_MAX";
		$queryJoin .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrCd;		
		$queryJoin .= "\n LEFT JOIN  $nmTabelaTramitacao TAB_TEMP";
		$queryJoin .= "\n ON TABELA_MAX." . voDemandaTramitacao::$nmAtrAno . " = TAB_TEMP." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND TABELA_MAX." . voDemandaTramitacao::$nmAtrCd . " = TAB_TEMP." . voDemandaTramitacao::$nmAtrCd;
		$queryJoin .= "\n AND TABELA_MAX." . voDemandaTramitacao::$nmAtrSq . " = TAB_TEMP." . voDemandaTramitacao::$nmAtrSq;		
		
		//para recuperar os dias por setor
		$dtDemandaTramSaida = filtroConsultarDemandaGestao::getSQLDtDemandaTramitacaoSaida($nmTabelaTramitacao, $nmTabelaDemanda);
		$dtDemandaTramEntrada = "$nmTabelaTramitacao." . voDemandaTramitacao::$nmAtrDtReferencia;		
				
		$arrayGroupbyAliasTemp = array (
				voDemandaTramitacao::$nmAtrCdSetorDestino,
				voDemanda::$nmAtrAno,
				voDemanda::$nmAtrCd,
		);
		$nmAtributosGroupByAliasTemp =  getSQLStringFormatadaColecaoIN($arrayGroupbyAliasTemp);
		
		$queryJoin .= " LEFT JOIN (";
		$queryJoin .= " SELECT $nmAtributosGroupByAliasTemp, SUM(".filtroConsultarDemandaGestao::$NmColNuTempoVida.") AS ".filtroConsultarDemandaGestao::$NmColNuTempoVida." FROM ";
		$queryJoin .= "(SELECT ";
		$queryJoin .= $nmTabelaTramitacao . ".*";
		$queryJoin .= ", ". getDataSQLDiferencaDias($dtDemandaTramEntrada, $dtDemandaTramSaida) . " AS " . filtroConsultarDemandaGestao::$NmColNuTempoVida;
		$queryJoin .= " FROM " . $nmTabelaTramitacao;		
		$queryJoin .= "\n INNER JOIN " . $nmTabelaDemanda;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd;
		
		$queryJoin .= filtroManter::$CD_CAMPO_SUBSTITUICAO;
		
		$queryJoin .= ") ALIAS_TEMP GROUP BY " . $nmAtributosGroupByAliasTemp;
		$queryJoin .= ") $nmTabelaPrazoPorSetor " ;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPrazoPorSetor . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "=" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaPrazoPorSetor . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaPrazoPorSetor . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
		
		$queryJoin .= filtroManter::$CD_CAMPO_SUBSTITUICAO;
		
		$queryJoin .= " GROUP BY $cdSetorDestino" . ",$nmTabelaDemanda." . vodemanda::$nmAtrAno . ",$nmTabelaDemanda." . vodemanda::$nmAtrCd; 
		$queryJoin .= ") ALIAS_TEMP_2 ";
		
		$queryJoin .= " GROUP BY " . voDemandaTramitacao::$nmAtrCdSetorDestino;
			
		$queryJoin .= " ORDER BY " . filtroConsultarDemandaGestao::$NmColNumTotalDemandas . " DESC";
		
		$arraySubstituicao = array(
				filtroManter::$CD_CAMPO_SUBSTITUICAO => $filtro->getSQLFiltroPreenchido(),		
		);		
		
		$filtro->inDesativado = constantes::$CD_NAO;
		$filtro->sqlFiltrosASubstituir = $arraySubstituicao;
		
		$retorno = parent::consultarFiltroPorSubstituicao($filtro, $queryJoin);
		
		//$retorno = parent::consultarMontandoQueryTelaConsulta ( new voDemanda(), $filtro, $arrayColunasRetornadas, $queryJoin );
				
		return $retorno;
	}
	
	/**
	 * consulta da tela de demanda
	 * {@inheritDoc}
	 * @see dbprocesso::consultarTelaConsulta()
	 */
	function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico;
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela ();
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabela ();
		$nmTabelaDemandaProcLic = voDemandaPL::getNmTabelaStatic ( false );
		$nmTabelaProcLic = voProcLicitatorio::getNmTabelaStatic ( false );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPA = voPA::getNmTabelaStatic ( false );
		$nmTabelaWPUsers = vousuario::getNmTabelaStatic ( false);
	
		$cdSetorAtual = $filtro->vodemanda->cdSetorDestino;
		$isSetorAtualSelecionado = $filtro->isSetorAtualSelecionado ();
		$nmTabelaMINDestinoTramitacao = "TABELA_MIN_TRAMDESTINO";
		$nmTabelaDestinoTramitacao = "TABELA_DESTINO_TRAM";
		$nmTabelaWPUsersUNCT = filtroManterDemanda::$NM_TABELA_USUARIO_UNCT;
	
		$colunaUsuHistorico = "";
	
		if ($isHistorico) {
			$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
		}
	
		// para nao dar pau caso seja des-selecionado
		if ($isSetorAtualSelecionado) {
			$colunaDtReferenciaSetorAtual = "$nmTabelaDestinoTramitacao." . voDemandaTramitacao::$nmAtrDtReferencia . "  AS " . filtroManterDemanda::$NmColDtReferenciaSetorAtual;
		} else if ($filtro->cdAtrOrdenacao == filtroManterDemanda::$NmColDtReferenciaSetorAtual) {
			$filtro->cdAtrOrdenacao = "";
		}
		$dhUltimaMov = filtroConsultarDemandaGestao::getSQLDataUltimaMovimentacao($nmTabelaTramitacao, $nmTabela);//"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrDhInclusao . "," . $nmTabela . "." . voDemanda::$nmAtrDhUltAlteracao . ")";
		$dtreferencia = filtroConsultarDemandaGestao::getSQLDataBaseTempoVida($nmTabela); //"$nmTabela." . voDemanda::$nmAtrDtReferencia;
		 
		//mostra a data do contrato da demanda que venceu ou vencera
		$atributoDataLimiteDemandaContratoAVencer = filtroManterDemanda::getNmAtributosDataACompararDemandasContratoAVencer();
		$atributoDataLimiteDemandaContratoAVencer = getSQLCOALESCE($atributoDataLimiteDemandaContratoAVencer, filtroManterDemanda::$NmColDtLimiteContratoAVencer);
		
		//echo "atributos a verificar $atributoDataLimiteDemandaContratoAVencer";
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",				
				"COUNT(*)  AS " . filtroManterDemanda::$NmColQtdContratos,
				static::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioInclusao,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato,
				$atributoDataLimiteDemandaContratoAVencer,
				$nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdProcLic,
				$nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoProcLic,
				$nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdModalidadeProcLic,
				$nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCdCPL,
				getSQLNmContratada (),
				// $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . " AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
				"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "," . $nmTabela . "." . voDemanda::$nmAtrCdSetor . ") AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
				"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrDhInclusao . "," . $nmTabela . "." . voDemanda::$nmAtrDhUltAlteracao . ") AS " . filtroManterDemanda::$NmColDhUltimaMovimentacao,
				" $dhUltimaMov AS " . filtroManterDemanda::$NmColDhUltimaMovimentacao,
				$colunaUsuHistorico,
				$colunaDtReferenciaSetorAtual,
				filtroConsultarDemandaGestao::getSQLNuTempoUltimaTram($nmTabelaTramitacao, $nmTabela) . " AS " . filtroManterDemanda::$NmColNuTempoUltimaTram,
				filtroConsultarDemandaGestao::getSQLNuTempoVida($nmTabela) . " AS " . filtroManterDemanda::$NmColNuTempoVida,
				"$nmTabelaWPUsersUNCT." . vousuario::$nmAtrName . " AS " .  filtroManterDemanda::$NM_COL_NOME_RESP_UNCT, 
		);
	
		$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno;
	
		// o proximo join eh p pegar a ultima tramitacao apenas, se houver
		$nmTabelaMAXTramitacao = "TABELA_MAX";
		$queryJoin = "";
		$queryJoin .= "\n LEFT JOIN (";
		$queryJoin .= " SELECT MAX(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq . "," . $atributosGroup . " FROM " . $nmTabelaTramitacao . " GROUP BY " . $atributosGroup;
		$queryJoin .= ") $nmTabelaMAXTramitacao";
		$queryJoin .= "\n ON " . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrCd;
	
		// agora pega dos dados da ultima tramitacao, se houver
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= $nmTabelaTramitacao;
		$queryJoin .= "\n ON " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrCd;
		$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrSq . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrSq;
	
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= $nmTabelaDemandaContrato;
		$queryJoin .= "\n ON " . $nmTabela . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
		$queryJoin .= "\n AND " . $nmTabela . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
	
		// o proximo join eh p pegar os dados do contrato relacionado a demanda
		$nmTabelaDadosContratoDemanda = filtroManterDemanda::$NM_TABELA_DADOS_CONTRATO_DEMANDA;
		//$atributosGroupContrato = getSQLStringFormatadaColecaoIN(vocontrato::getAtributosChaveLogica());
		$queryJoin .= "\n LEFT JOIN $nmTabelaContrato $nmTabelaDadosContratoDemanda ";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaDadosContratoDemanda . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaDadosContratoDemanda . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaDadosContratoDemanda . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaDadosContratoDemanda . "." . vocontrato::$nmAtrCdEspecieContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaDadosContratoDemanda . "." . vocontrato::$nmAtrSqEspecieContrato;
				
		// o proximo join eh p pegar o registro de contrato mais atual na planilha
		// faz o join apenas com os contratos de maximo sequencial (mais atual)
		$nmTabelaMAXContratoTEMP = "NM_TAB_MAX_CONTRATO_TEMP";
		$especiesinternas = getSQLStringFormatadaColecaoIN(dominioEspeciesContrato::getColecaoTermosQuePodemAlterarVigencia(), true);
		$atributosGroupContrato = vocontrato::$nmAtrAnoContrato . "," . vocontrato::$nmAtrTipoContrato . "," . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n LEFT JOIN (";
		$queryJoin .= " \n\nSELECT MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato . "," . $atributosGroupContrato
		. " FROM " . $nmTabelaContrato
		. " WHERE " . vocontrato::$nmAtrCdEspecieContrato . " IN ($especiesinternas) "
				. " GROUP BY " . $atributosGroupContrato;
				$queryJoin .= ") $nmTabelaMAXContratoTEMP";
				$queryJoin .= "\n ON ";
				$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaMAXContratoTEMP . "." . vocontrato::$nmAtrAnoContrato;
				$queryJoin .= "\n AND ";
				$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaMAXContratoTEMP . "." . vocontrato::$nmAtrTipoContrato;
				$queryJoin .= "\n AND ";
				$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaMAXContratoTEMP . "." . vocontrato::$nmAtrCdContrato;
	
		// agora pega dos dados do MAX contrato
		$queryJoin .= "\n LEFT JOIN $nmTabelaContrato ";
		$queryJoin .= "\n ON " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . " = $nmTabelaMAXContratoTEMP." . vocontrato::$nmAtrSqContrato;
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato;
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaDemandaProcLic;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrCd;
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaProcLic;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCd;
		$queryJoin .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdModalidadeProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCdModalidade;
				
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPA;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPA . "." . voPA::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaPA . "." . voPA::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrCd;
		
		//pega o usuario responsavel da UNCT
		$queryJoin .= "\n LEFT JOIN $nmTabelaWPUsers $nmTabelaWPUsersUNCT ";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . voDemanda::$nmAtrCdPessoaRespUNCT . "=" . $nmTabelaWPUsersUNCT . "." . vousuario::$nmAtrID;
		
		/*$querySelect = " SELECT * ";
		$queryFrom = " FROM " . $nmTabelaUsuInfo;
		$queryFrom .= " INNER JOIN " . $nmTabelaWPUsers;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaUsuInfo . "." . voUsuarioInfo::$nmAtrID . "=" . $nmTabelaWPUsers . "." . vousuario::$nmAtrID;		
		//$nmTabelaUsuInfo = voUsuarioInfo::getNmTabelaStatic ( false);*/
		
	
		// Para o caso de se desejar ordenar pela primeira vez que foi encaminhada ao setor atual selecionado pelo usuario
		if ($isSetorAtualSelecionado) {
			$compararSetorAtual = "= $cdSetorAtual";
			if(is_array($cdSetorAtual)){
				$compararSetorAtual = " IN (" . getSQLStringFormatadaColecaoIN($cdSetorAtual). ")";
			}
			// echo "tem";
			$queryJoin .= "\n LEFT JOIN (";
			$queryJoin .= " SELECT MIN(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq . "," . $atributosGroup . " FROM " . $nmTabelaTramitacao 
			. " WHERE " . voDemandaTramitacao::$nmAtrCdSetorDestino . " $compararSetorAtual GROUP BY " . $atributosGroup;
			$queryJoin .= ") $nmTabelaMINDestinoTramitacao ";
			$queryJoin .= "\n ON " . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno . " = $nmTabelaMINDestinoTramitacao." . voDemandaTramitacao::$nmAtrAno;
			$queryJoin .= "\n AND " . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd . " = $nmTabelaMINDestinoTramitacao." . voDemandaTramitacao::$nmAtrCd;
				
			// agora pega dos dados da ultima tramitacao, se houver
			$queryJoin .= "\n LEFT JOIN ";
			$queryJoin .= "$nmTabelaTramitacao $nmTabelaDestinoTramitacao";
			$queryJoin .= "\n ON " . $nmTabelaDestinoTramitacao . "." . voDemandaTramitacao::$nmAtrAno . " = $nmTabelaMINDestinoTramitacao." . voDemandaTramitacao::$nmAtrAno;
			$queryJoin .= "\n AND " . $nmTabelaDestinoTramitacao . "." . voDemandaTramitacao::$nmAtrCd . " = $nmTabelaMINDestinoTramitacao." . voDemandaTramitacao::$nmAtrCd;
			$queryJoin .= "\n AND " . $nmTabelaDestinoTramitacao . "." . voDemandaTramitacao::$nmAtrSq . " = $nmTabelaMINDestinoTramitacao." . voDemandaTramitacao::$nmAtrSq;
		}
	
		$arrayGroupby = array (
				$nmTabela . "." . voDemanda::$nmAtrAno,
				$nmTabela . "." . voDemanda::$nmAtrCd
		);

		if ($isHistorico) {
			$arrayGroupby [] = voentidade::$nmAtrSqHist;
		}
	
		$filtro->groupby = $arrayGroupby;
	
		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
	}
	
	/**
	 * consulta demanda tramitacao simples
	 * @param unknown $vo
	 * @return string
	 */
	function consultarDemandaTramitacao($vo) {
		$nmTabela = voDemandaTramitacao::getNmTabelaStatic ( false );
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabelaDemandaTramDoc = voDemandaTramDoc::getNmTabelaStatic ( false );
		$nmTabelaDocumento = voDocumento::getNmTabelaStatic ( false );
		
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		
		$nmTabelaUsuario = vousuario::getNmTabela ();
		
		$querySelect = "SELECT ";
		$querySelect .= $nmTabela . ".*,";
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
		
		$filtro = new filtroManterDemanda ( false );
		// $vo = new voContratoInfo();
		// vo eh vodemanda
		$filtro->vodemanda->cd = $vo->cd;
		$filtro->vodemanda->ano = $vo->ano;
		
		$filtro->TemPaginacao = false;
		$filtro->cdAtrOrdenacao = voDemandaTramitacao::$nmAtrDhInclusao;
		$filtro->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		// echo $vo->texto;
		
		return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}

	/**
	 * consulta demanda tramitacao gestao
	 * @param unknown $vo
	 * @return string
	 */
	function consultarDemandaGestaoTramitacao($vo) {
		$nmTabela = voDemandaTramitacao::getNmTabelaStatic ( false );
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );	
		$nmTabelaDemandaContadorTram = "NM_TAB_DEMANDA_CONTADOR_TRAM";
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );	
		$nmTabelaUsuario = vousuario::getNmTabela ();
				
		/*$dtDemandaTramSaida = filtroConsultarDemandaGestao::getSQLDtDemandaTramitacaoSaida($nmTabela, $nmTabelaDemanda);
		$dtDemandaTramEntrada = "$nmTabela." . voDemandaTramitacao::$nmAtrDtReferencia;*/
		
		$nmAtributoCountDemanda = "NM_COL_COUNT_DEMANDA";
		$colecaoINSituacaoFechada = getSQLStringFormatadaColecaoIN(array_keys(dominioSituacaoDemanda::getColecaoFechada()));
		$dtDemandaTramSaida = "$nmTabela." . voDemandaTramitacao::$nmAtrDtReferencia;
		$dtDemandaTramSaida = getSQLCASEBooleano("$nmAtributoCountDemanda > 1", $dtDemandaTramSaida 
				, getSQLCASEBooleano("$nmTabelaDemandaContadorTram." . voDemanda::$nmAtrSituacao . " IN ($colecaoINSituacaoFechada)" 
						, $dtDemandaTramSaida , "DATE(NOW())")
				);
		$dtDemandaTramEntrada = filtroConsultarDemandaGestao::getSQLDtDemandaTramitacaoEntrada($nmTabela, $nmTabelaDemanda);
		
		$querySelect = "SELECT ";
		$querySelect .= $nmTabela . ".*";
		$querySelect .= ", $dtDemandaTramEntrada AS " . filtroConsultarDemandaGestao::$NmColDtReferenciaEntrada;
		$querySelect .= ", $dtDemandaTramSaida AS " . filtroConsultarDemandaGestao::$NmColDtReferenciaSaida;
		$querySelect .= ", ". getDataSQLDiferencaDias($dtDemandaTramEntrada, $dtDemandaTramSaida) . " AS " . filtroConsultarDemandaGestao::$NmColNuTempoVida;
		$querySelect .= "," . $nmTabelaUsuario . "." . vousuario::$nmAtrName;
		$querySelect .= "  AS " . voDemanda::$nmAtrNmUsuarioInclusao;
		$queryFrom = " FROM " . $nmTabela;
		
		$queryFromTemp .= "\n INNER JOIN " . $nmTabelaDemanda;
		$queryFromTemp .= "\n ON ";
		$queryFromTemp .= $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryFromTemp .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		
		$queryFrom .= $queryFromTemp;
		
		$groupinterno = "$nmTabela.". voDemanda::$nmAtrAno . "," . "$nmTabela.".voDemanda::$nmAtrCd . "," . "$nmTabelaDemanda.".voDemanda::$nmAtrSituacao; 
		$subSelect = "(SELECT COUNT(*) AS $nmAtributoCountDemanda, $groupinterno FROM $nmTabela $queryFromTemp group by $groupinterno)";		
		$queryFrom .= "\n INNER JOIN $subSelect $nmTabelaDemandaContadorTram ";
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemandaContadorTram . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryFrom .= "\n AND " . $nmTabelaDemandaContadorTram . "." . voDemanda::$nmAtrCd . "=" . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		
		$queryFrom .= "\n INNER JOIN " . $nmTabelaUsuario;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaUsuario . "." . vousuario::$nmAtrID . "=" . $nmTabela . "." . voDemanda::$nmAtrCdUsuarioInclusao;
		
		$filtro = new filtroConsultarDemandaGestao(false );
		// $vo = new voContratoInfo();
		// vo eh vodemanda
		$filtro->vodemanda->cd = $vo->cd;
		$filtro->vodemanda->ano = $vo->ano;
	
		$filtro->TemPaginacao = false;
		$filtro->cdAtrOrdenacao = voDemandaTramitacao::$nmAtrDhInclusao;
		$filtro->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		// echo $vo->texto;
	
		return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	
	function consultarDadosDemanda($vo) {
		$isHistorico = $vo->isHistorico ();
		
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( $isHistorico );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPAAP = voPA::getNmTabelaStatic ( false );
		// $nmTabelaPL = voProcLicitatorio::getNmTabelaStatic ( false );
		
		$querySelect = "SELECT ";
		$querySelect .= $nmTabelaDemanda . ".*,";
		$querySelect .= $nmTabelaDemandaContrato . ".*,";
		$querySelect .= "$nmTabelaPAAP." . voPA::$nmAtrAnoPA . ",$nmTabelaPAAP." . voPA::$nmAtrCdPA;
		$queryFrom = " FROM " . $nmTabelaDemanda;
		
		$queryFrom .= "\n LEFT JOIN ";
		$queryFrom .= $nmTabelaDemandaContrato;
		$queryFrom .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
		$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPAAP;
		$queryFrom .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaPAAP . "." . voPA::$nmAtrCdDemanda;
		$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaPAAP . "." . voPA::$nmAtrAnoDemanda;
		
		$filtro = new filtroManterDemanda ( false );
		// var_dump($vo);
		$filtro->vodemanda->cd = $vo->cd;
		$filtro->vodemanda->ano = $vo->ano;
		$filtro->vodemanda->sqHist = $vo->sqHist;
		$filtro->TemPaginacao = false;
		$filtro->isHistorico = $isHistorico;
		
		return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	function consultarDemandaContrato($vo) {
		$isHistorico = $vo->isHistorico ();
		
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( $isHistorico );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		$querySelect = "SELECT ";
		$querySelect .= $nmTabelaDemanda . ".*,";
		$querySelect .= $nmTabelaDemandaContrato . ".*";
		$queryFrom = " FROM " . $nmTabelaDemanda;
		
		$queryFrom .= "\n INNER JOIN ";
		$queryFrom .= $nmTabelaDemandaContrato;
		$queryFrom .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
		$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
		$queryFrom .= "\n AND ";
		$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
		/*
		 * $queryJoin .= "\n AND ";
		 * $queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
		 * $queryJoin .= "\n AND ";
		 * $queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
		 */
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		$filtro = new filtroManterDemanda ( false );
		// var_dump($vo);
		$filtro->vodemanda->cd = $vo->cd;
		$filtro->vodemanda->ano = $vo->ano;
		$filtro->vodemanda->sqHist = $vo->sqHist;
		$filtro->TemPaginacao = false;
		$filtro->isHistorico = $isHistorico;
		
		return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	
	function consultarTelaConsultaDemandaUsuario($filtro) {
	
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabelaWPUsers = vousuario::getNmTabela();
						
		$numDemandas = "COUNT(*)";
		$arrayColunasRetornadas = array (
				"$nmTabelaWPUsers." . vousuario::$nmAtrName,
				"$nmTabelaWPUsers." . vousuario::$nmAtrID,
				"$numDemandas  AS " . filtroConsultarDemandaGestao::$NmColNumTotalDemandas,
		);
		
		$arrayGroupby = array (
				$nmTabelaDemanda . "." . voDemanda::$nmAtrCdPessoaRespUNCT,
		);
		
		//pega o usuario responsavel da UNCT
		$queryJoin .= "\n LEFT JOIN $nmTabelaWPUsers ";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemanda . "." . voDemanda::$nmAtrCdPessoaRespUNCT . "=" . $nmTabelaWPUsers . "." . vousuario::$nmAtrID;		
	
		$filtro->groupby = $arrayGroupby;
	
		$retorno = parent::consultarMontandoQueryTelaConsulta ( new voDemanda(), $filtro, $arrayColunasRetornadas, $queryJoin );

		//implementar
		/*$arrayColunasRetornadasSetor = array (
				"$nmTabelaWPUsers." . vousuario::$nmAtrName,
				"$nmTabelaWPUsers." . vousuario::$nmAtrID,
				"1 AS " . filtroConsultarDemandaUsuario::$NmColNumDemandasSetor,
				"0 AS " . filtroConsultarDemandaUsuario::$NmColNumTotalDemandas,
				"0 AS " . filtroConsultarDemandaUsuario::$NmColFatorTrabalho,
		);
		
		$arrayColunasRetornadasTotal = array (
				"$nmTabelaWPUsers." . vousuario::$nmAtrName,
				"$nmTabelaWPUsers." . vousuario::$nmAtrID,
				"0 AS " . filtroConsultarDemandaUsuario::$NmColNumDemandasSetor,
				"1 AS " . filtroConsultarDemandaUsuario::$NmColNumTotalDemandas,
				"0 AS " . filtroConsultarDemandaUsuario::$NmColFatorTrabalho,
		);
		
		$querySetor = "SELECT ";
		$querySetor .= getSQLStringFormatadaColecaoIN($arrayColunasRetornadasEntrada);
		$querySetor .= " FROM $nmTabela ";
		$querySetor .= $joinComum;
		$querySetor .= filtroManter::$CD_CAMPO_SUBSTITUICAO;
		
		$queryTotal = "SELECT ";
		$queryTotal .= getSQLStringFormatadaColecaoIN($arrayColunasRetornadasSaida);
		$queryTotal .= " FROM $nmTabela ";
		$queryTotal .= $joinComum;
		$queryTotal .= filtroManter::$CD_CAMPO_SUBSTITUICAO;
		
		$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno . "," . voDemandaTramitacao::$nmAtrCdSetorDestino;
		$nmatrsqtram = voDemandaTramitacao::$nmAtrSq;
		$nmTabDemandaMinPorSetor = filtroConsultarDemandaRendimento::$NmTabelaTramitacaoMininaPorSetor;
		$queryNumDemandas = "SELECT ";
		$queryNumDemandas .= getSQLStringFormatadaColecaoIN($arrayColunasRetornadasNuDemandas);
		$queryNumDemandas .= " FROM $nmTabelaDemanda ";
		$queryNumDemandas .= $joinDemandaContrato;
		$queryNumDemandas .= $joinDemandaPL;
		$queryNumDemandas .= "\n INNER JOIN (SELECT MIN($nmatrsqtram) AS $nmatrsqtram,$atributosGroup FROM $nmTabela group by $atributosGroup) $nmTabDemandaMinPorSetor";
		$queryNumDemandas .= "\n ON ";
		$queryNumDemandas .= "$nmTabelaDemanda." . voDemanda::$nmAtrAno . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrAno;
		$queryNumDemandas .= "\n AND $nmTabelaDemanda." . voDemanda::$nmAtrCd . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrCd;
		$queryNumDemandas .= "\n INNER JOIN " . $nmTabela;
		$queryNumDemandas .= "\n ON ";
		$queryNumDemandas .= "$nmTabela." . voDemandaTramitacao::$nmAtrAno . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrAno;
		$queryNumDemandas .= "\n AND $nmTabela." . voDemandaTramitacao::$nmAtrCd . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrCd;
		$queryNumDemandas .= "\n AND $nmTabela." . voDemandaTramitacao::$nmAtrSq . "=$nmTabDemandaMinPorSetor." . voDemandaTramitacao::$nmAtrSq;
		
		$queryNumDemandas .= filtroManter::$CD_CAMPO_SUBSTITUICAO;
		
		$numSaidas = "SUM(".filtroConsultarDemandaRendimento::$NmTabelaRendimento . ".". filtroConsultarDemandaRendimento::$NmColNuSaidas .") AS ";
		$numSaidas .= filtroConsultarDemandaRendimento::$NmColNuSaidas;
		$numEntradas = "SUM(".filtroConsultarDemandaRendimento::$NmTabelaRendimento . ".".filtroConsultarDemandaRendimento::$NmColNuEntradas.") AS ";
		$numEntradas .= filtroConsultarDemandaRendimento::$NmColNuEntradas;
		$numDemandas = "SUM(".filtroConsultarDemandaRendimento::$NmTabelaRendimento . ".".filtroConsultarDemandaRendimento::$NmColNumTotalDemandas .") AS ";
		$numDemandas .= filtroConsultarDemandaRendimento::$NmColNumTotalDemandas;
		
		$arrayColunasRetornadas = array (
				vodemanda::$nmAtrCdSetor,
				vodemanda::$nmAtrDtReferencia,
				$numEntradas,
				$numSaidas,
				$numDemandas,
		);
		
		$query = " SELECT ";
		$query .= getSQLStringFormatadaColecaoIN($arrayColunasRetornadas);
		$query .= " FROM ($queryEntrada UNION ALL $querySaida UNION ALL $queryNumDemandas) " . filtroConsultarDemandaRendimento::$NmTabelaRendimento;
		$query .= filtroConsultarDemandaRendimento::$CD_CAMPO_SUBSTITUICAO_PRINCIPAL;
		$query .= " GROUP BY " . $filtro->groupby;
		
		$filtroPrincipal = "";
		if($filtro->vodemanda->cdSetor != null){
			$filtroPrincipal = voDemanda::$nmAtrCdSetor . "=" . $filtro->vodemanda->cdSetor;
		}
		$arraySubstituicao = array(
				filtroManter::$CD_CAMPO_SUBSTITUICAO => $filtro->getSQLFiltroPreenchido(),
				filtroConsultarDemandaRendimento::$CD_CAMPO_SUBSTITUICAO_PRINCIPAL => $filtroPrincipal,
		
		);
		
		$filtro->sqlFiltrosASubstituir = $arraySubstituicao;
		$retorno = parent::consultarFiltroPorSubstituicao($filtro, $query);*/
		
		return $retorno;
	}
	
	function consultarPAAPDemanda($vo, $validarUnicoPAAP = true) {
		// $vo = new voDemanda();
		$voPA = null;
		$filtro = new filtroManterPA ( false );
		$filtro->anoDemanda = $vo->ano;
		$filtro->cdDemanda = $vo->cd;
		$dbpa = new dbPA ();
		$colecao = $dbpa->consultarPAAP ( new voPA (), $filtro );
		// var_dump($filtro);
		if (! isColecaoVazia ( $colecao )) {
			if (count ( $colecao ) > 1 && $validarUnicoPAAP) {
				throw new excecaoMaisDeUmRegistroRetornado ( "A consulta de PAAP trouxe mais de um registro para esta demanda." );
			}
			
			$registro = $colecao [0];
			$voPA = new voPA ();
			$voPA->getDadosBancoPorChave ( $registro );
		}
		
		return $voPA;
	}
	function isExclusaoPermitida($vo, $textoFuncao, $naoValidar = false) {
		// verifica se o setor atual eh igual ao setor de origem
		$filtro = new filtroManterDemanda ( false );
		$filtro->vodemanda = new voDemanda ();
		$filtro->vodemanda->cd = $vo->cd;
		$filtro->vodemanda->ano = $vo->ano;
		
		$colecao = $this->consultarTelaConsulta ( $vo, $filtro );
		$retorno = false;
		if ($colecao != "") {
			$setorAtual = $colecao [0] [voDemandaTramitacao::$nmAtrCdSetorDestino];
			$usuInclusaoDemanda = $colecao [0] [voDemanda::$nmAtrCdUsuarioInclusao];
			// echo "setor atual:" . $setorAtual;
			if(!$naoValidar){
				//a ordem das opcoes eh da mais ampla pra mais restrita
				if ($setorAtual == null || $vo->cdSetor == $setorAtual) {
					$retorno = true;
				}else{
					$msg .= "A demanda deve estar encaminhada ao setor responsсvel para '$textoFuncao'";
					$conector = " ou ";
				}	
				
				if(!$retorno && $usuInclusaoDemanda != null && getIdUsuarioLogado() == $usuInclusaoDemanda) {
					//valida tambem se o usuario logado for o mesmo que incluiu a demanda, permite excluir
					$retorno = true;					
				}else{
					$msg .= $conector . "'$textoFuncao' permitido/a para o usuсrio que incluiu a demanda";
					$conector = " ou ";
				}
										
				$msg .= ".";
				
				if(!$retorno){
					throw new Exception ( $msg );
				}
			}
		} // else echo "COLECAO VAZIA";
	
		return $retorno;
	}
	
 /**
 * valida inclusao de demanda licon
 * MARRETA CORRIGIR DEPOIS incluindo no dbdemanda 
 * @param unknown $vo
 * @throws Exception
 */
	static function validarDemandaLicon($vo){
		if ($vo->tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_LICON) {
			$strAbuscar = "publica";			
			//if(existeStr1NaStr2ComSeparador($vo->textoTram, $strAbuscar, false))
			if(!existeStr1NaStr2($strAbuscar, $vo->texto)){
				$msg = "Щ necessсrio informar a data de publicaчуo no TЭTULO, no seguinte formato: 'Data de publicaчуo: DD/MM/AAAA'.";
				
				//var_dump($vo);
				throw new excecaoGenerica( $msg );
			}
		};
	}	
		
	static function validarDadosContrato($voContrato, $vocontratoInfo) {
		//$voContrato = new vocontrato();		
		$array = $voContrato->getValoresAtributosObrigatorios($vocontratoInfo);
		//var_dump($array);
		static::validarDadosEntidadeArray($array);
		
	}
	
	static function validarDadosContratoModificacao($voDemanda) {
		if(isDemandaContratoModificacaoObrigatorio($voDemanda)){
			$vocontratoDemanda = $voDemanda->getContrato();	
			
			$filtro = new filtroManterContratoModificacao(false);
			$filtro->vocontrato = $vocontratoDemanda;
			$filtro->inDesativado = 'N';
			$dbcontratomod = new dbContratoModificacao();
			$vocontratomod = new voContratoModificacao();
			$colecao = $dbcontratomod->consultarTelaConsultaFiltro($filtro);
			
			$qtdtipoContratoMod = dominioTipoDemandaContrato::getNumChavesColecaoNoArrayOuStrSeparador(array_keys(dominioTipoDemandaContrato::getColecaoAlteraValorContrato()), $voDemanda->tpDemandaContrato);
			$tam = sizeof($colecao);
			if(isColecaoVazia($colecao) || $qtdtipoContratoMod > $tam){
				throw new excecaoGenerica("Necessсrio regularizar o presente termo na funчуo '".voContratoModificacao::getTituloJSP()."'. Exigem-se, pelo menos, $qtdtipoContratoMod registros de modificaчуo.");
			}
		}	
	}
	
	static function validarGenerico(&$vo) {
		$tipo = $vo->tipo;
		$tpDemandaContrato = $vo->tpDemandaContrato;
		$tpReajuste = $vo->inTpDemandaReajusteComMontanteA;
		
		if ($tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO){
			if($tpDemandaContrato == null || $tpDemandaContrato == "" || isColecaoVazia($tpDemandaContrato)){
				throw new excecaoGenerica ( "Os campos informaчѕes complementares da demanda/contrato sуo obrigatѓrios." );
			}
			
			/*if(is_array($tpDemandaContrato)){
				$tpDemandaContratoStr = voDemanda::getArrayComoStringCampoSeparador($tpDemandaContrato);
			}				
			if(dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE, $tpDemandaContratoStr)
					&& ($tpReajuste == null || $tpReajuste == "")){
				throw new excecaoGenerica ( "O campo tipo de reajuste щ obrigatѓrio." );
			}*/
		}
		
		//static::validarDemandaLicon($vo);
		
		if (dominioTipoDemanda::isContratoObrigatorio($tipo) && ! $vo->temContratoParaIncluir ()) {
			$msg = "Selecione ao menos um contrato.";
			throw new excecaoGenerica ( $msg );
		}
		
		//apenas usuario avancado pode determinar prioridade alta
		/*if (!isUsuarioChefia()) {
			//$vo = new voDemandaTramitacao();
			if ($vo->tipo != dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL && $vo->prioridade == dominioPrioridadeDemanda::$CD_PRIORI_ALTA) {
				$msg = "Usuсrio nуo autorizado para incluir demandas com prioridade ALTA.";
				throw new Exception ( $msg );
			}
		}*/
		
	}
		
	
	function validarAlteracao($vo) {
		$this->validarGenerico($vo);
		
		if ($vo->situacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA) {			
			//$this->isExclusaoPermitida ( $vo, "fechamento" );
			
			//verifica se o contrato foi incluido em contratoinfo
			$vocontratoInfo = new voContratoInfo();
			//$vo = new voDemanda();
			$vocontratoDemanda = $vo->getContrato();
			if($vocontratoDemanda != null){				
				try{
					$dbContrato = new dbcontrato();
					//var_dump($vocontratoDemanda);
					$vocontratoDemanda =$dbContrato->consultarPorChaveVO($vocontratoDemanda);
				}catch (excecaoChaveRegistroInexistente $ex){
					throw new excecaoGenerica("Verifique se o termo relacionado foi incluэdo corretamente na funчуo 'contratos'.");
				}
				
				//throw new excecaoGenerica("Aguardem....");
				
				$vocontratoInfo->anoContrato = $vocontratoDemanda->anoContrato;
				$vocontratoInfo->cdContrato = $vocontratoDemanda->cdContrato;
				$vocontratoInfo->tipo = $vocontratoDemanda->tipo;
				try{					
					$vocontratoInfo = $vocontratoInfo->dbprocesso->consultarPorChaveVO($vocontratoInfo);
				}catch (excecaoChaveRegistroInexistente $ex){
					throw new excecaoChaveRegistroInexistente("Verifique a inclusуo das informaчѕes adicionais ao contrato relacionado.", null, $vocontratoInfo);
				}
				
				$arrayRetorno = getHTMLDocumentosContrato($vocontratoDemanda);
				$temAmbosDocsAExibir = $arrayRetorno[2];
				$naovalidaDocs = isAtributoValido($vo->inCaracteristicas) && in_array(dominioCaracteristicasDemanda::$CD_NAO_VALIDA_DOCS, $vo->inCaracteristicas);
								
				if(!$temAmbosDocsAExibir && !$naovalidaDocs){
					throw new excecaoGenerica("Fechamento nуo permitido: ambos os documentos 'MINUTA' (em word) e 'CONTRATO' (em pdf) devem ser anexados р demanda. |" 
							. $vocontratoDemanda->getCodigoContratoFormatado(true));
				}
				
				$temGarantia = $vocontratoInfo->inTemGarantia == constantes::$CD_SIM;				
				//$vo = new voDemandaTramitacao();
				$garantiaOk = !$temGarantia  || (isAtributoValido($vo->fase) && in_array(dominioFaseDemanda::$CD_GARANTIA_PRESTADA, $vo->fase));
				if(!$garantiaOk){ 
					throw new excecaoGenerica("Fechamento nуo permitido: verifique a garantia do contrato. |"
							. $vocontratoDemanda->getCodigoContratoFormatado(true));						
				}
				
				$temPendenciaContratoEnvioSAD = isAtributoValido($vocontratoInfo->inPendencias) && in_array(dominioAutorizacao::$CD_AUTORIZ_SAD, $vocontratoInfo->inPendencias);
				$temPendenciaContratoEnvioPGE = isAtributoValido($vocontratoInfo->inPendencias) && in_array(dominioAutorizacao::$CD_AUTORIZ_PGE, $vocontratoInfo->inPendencias);
				
				$isContratoEnvioSAD = !$temPendenciaContratoEnvioSAD && isContratoEnvioSADPGE($vocontratoDemanda, dominioSetor::$CD_SETOR_SAD, $vocontratoInfo);
				$isContratoEnvioPGE = !$temPendenciaContratoEnvioPGE && isContratoEnvioSADPGE($vocontratoDemanda, dominioSetor::$CD_SETOR_PGE, $vocontratoInfo);				
				//$vo = new voDemandaTramitacao();
				$isAnaliseSADOK = !$isContratoEnvioSAD || (isAtributoValido($vo->fase) && in_array(dominioFaseDemanda::$CD_VISTO_SAD, $vo->fase));
				$isAnalisePGEOK = !$isContratoEnvioPGE || (isAtributoValido($vo->fase) && in_array(dominioFaseDemanda::$CD_VISTO_PGE, $vo->fase));
				if(!$isAnaliseSADOK){
					throw new excecaoGenerica("Fechamento nуo permitido: ausente anсlise SAD ao contrato. |"
							. $vocontratoDemanda->getCodigoContratoFormatado(true));
				}
				
				if(!$isAnalisePGEOK){
					throw new excecaoGenerica("Fechamento nуo permitido: ausente anсlise PGE ao contrato. |"
							. $vocontratoDemanda->getCodigoContratoFormatado(true));
				}
				
				//$vo = new voDemandaTramitacao();
				$temRespUNCT = isAtributoValido($vo->cdPessoaRespUNCT);
				if(!$temRespUNCT){
					throw new excecaoGenerica("Fechamento nуo permitido: indique o responsсvel UNCT pela demanda.");
				}
				
				static::validarDadosContrato($vocontratoDemanda, $vocontratoInfo);
				
				static::validarDadosContratoModificacao($vo); 
								
			}
			
			// verifica se tem PAAP para encerrar
			  // $vo = new voDemanda();
			if ($vo->tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_PROCADM) {
				$voPA = $this->consultarPAAPDemanda ( $vo );
				if ($voPA != null) {
					$situacaoPAAP = $voPA->situacao;
					$isSituacaoPAAPAtivo = dominioSituacaoPA::existeItem ( $situacaoPAAP, dominioSituacaoPA::getColecaoSituacaoAtivos () );
					if ($isSituacaoPAAPAtivo) {
						throw new excecaoGenerica ( "Fechamento nуo permitido para demanda cujo PAAP esteja ativo." );
					}
				}
			}
		}
		
		// throw new Exception("REMOVER!!:" . $msg);
		
		return true;
	}
	function alterarMais($vo, $isAlteracaoTelaDemanda = true) {
		$isAlteracaoPermitida = $this->validarAlteracao ( $vo );
		if ($isAlteracaoPermitida) {
			$this->cDb->retiraAutoCommit ();
			try {
				
				// so altera o contrato se vier da tela de alteracao de demanda
				if ($isAlteracaoTelaDemanda) {
					$this->excluirDemandaContrato ( $vo );
					if ($vo->temContratoParaIncluir ()) {
						$this->incluirColecaoDemandaContrato ( $vo );
					}
					
					// $vo=new voDemanda();
					$this->excluirDemandaProcLicitatorio ( $vo );
					if ($vo->temProcLicitatorioParaIncluir ()) {
						$voProcLic = $vo->voProcLicitatorio;
						$this->incluirDemandaProcLicitatorio ( $vo->getVODemandaProcLicitatorio ( $voProcLic ) );
					}
					
					//$vo=new voDemanda();
					$this->excluirDemandaSolicCompra($vo );
					if ($vo->temSolicCompraParaIncluir()) {
						$voSolicCompra = $vo->voSolicCompra;
						$this->incluirDemandaSolicCompra($vo->getVODemandaSolicCompra( $voSolicCompra ) );
					}
						
				}
				
				parent::alterar ( $vo );
				// End transaction
				$this->cDb->commit ();				
			} catch ( Exception $e ) {
				$this->cDb->rollback ();
				throw new Exception ( $e->getMessage () );
			}
		}
	}
	function alterarApenasVODemanda($vo) {
		return $this->alterarMais ( $vo, false );
	}
	function alterar($vo) {
		return $this->alterarMais ( $vo, true );
	}
	function isDemandaPAAPInativo($vo, $textoFuncao) {
		if ($vo->tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_PROCADM) {
			$voPA = $this->consultarPAAPDemanda ( $vo );
			if ($voPA != null) {
				$situacaoPAAP = $voPA->situacao;
				$isSituacaoPAAPAtivo = dominioSituacaoPA::existeItem ( $situacaoPAAP, dominioSituacaoPA::getColecaoSituacaoAtivos () );
				if ($isSituacaoPAAPAtivo) {
					throw new excecaoGenerica ( "$textoFuncao nуo permitido(a) para demanda cujo PAAP esteja ativo." );
				}
			}
		}
		return true;
	}
	function validarExclusao($vo) {
		$textoFuncao = "Exclusуo";
		return $this->isExclusaoPermitida ( $vo, $textoFuncao ) && $this->isDemandaPAAPInativo ( $vo, $textoFuncao );
	}
	
	// o excluir eh implementado para nao usar da voentidade
	// por ser mais complexo
	function excluir($vo) {
		// Start transaction
		$isExclusaoPermitida = $this->validarExclusao ( $vo );
		if ($isExclusaoPermitida) {
			$this->cDb->retiraAutoCommit ();
			try {
				$permiteExcluirPrincipal = $this->permiteExclusaoPrincipal ( $vo );
				// so exclui os relacionamentos se a exclusao for de registro historico
				// e nao existir outro registro vigente que possa utilizar os relacionamentos
				// if($vo->validaExclusaoRelacionamentoHistorico()){
				if ($permiteExcluirPrincipal) {
					$this->excluirDemandaTramitacao ( $vo );
					$this->excluirDemandaContrato ( $vo );
					$this->excluirDemandaProcLicitatorio ( $vo );
					$this->excluirDemandaSolicCompra( $vo );
				}
				$vo = parent::excluir ( $vo );
				// End transaction
				$this->cDb->commit ();
			} catch ( Exception $e ) {
				$this->cDb->rollback ();
				throw new Exception ( $e->getMessage () );
			}
		}
		
		return $vo;
	}
	function excluirDemandaTramitacao($voDemanda) {
		// exclui os docs relacionadas a demanda
		$this->excluirDemandaTramDoc ( $voDemanda );
		
		$vo = new voDemandaTramitacao ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voDemandaTramitacao::$nmAtrAno . " = " . $voDemanda->ano;
		$query .= "\n AND " . voDemandaTramitacao::$nmAtrCd . " = " . $voDemanda->cd;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function excluirDemandaTramDoc($voDemanda) {
		$nmTabela = voDemandaTramDoc::getNmTabelaStatic ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voDemandaTramDoc::$nmAtrAnoDemanda . " = " . $voDemanda->ano;
		$query .= "\n AND " . voDemandaTramDoc::$nmAtrCdDemanda . " = " . $voDemanda->cd;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function excluirDemandaContrato($voDemanda) {
		$vo = new voDemandaContrato ();
		
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voDemandaContrato::$nmAtrAnoDemanda . " = " . $voDemanda->ano;
		$query .= "\n AND " . voDemandaContrato::$nmAtrCdDemanda . " = " . $voDemanda->cd;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function excluirDemandaProcLicitatorio($voDemanda) {
		$vo = new voDemandaPL ();
		
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voDemandaPL::$nmAtrAnoDemanda . " = " . $voDemanda->ano;
		$query .= "\n AND " . voDemandaPL::$nmAtrCdDemanda . " = " . $voDemanda->cd;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function excluirDemandaSolicCompra($voDemanda) {
		$vo = new voDemandaSolicCompra();
	
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voDemandaSolicCompra::$nmAtrAnoDemanda . " = " . $voDemanda->ano;
		$query .= "\n AND " . voDemandaSolicCompra::$nmAtrCdDemanda . " = " . $voDemanda->cd;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}

	/**
	 * verifica se ja existe uma demanda A FAZER de PRORROGACAO para o contrato
	 * caso ja exista, a mesma numeracao nao pode ser utilizada como nova demanda.
	 * Se ainda assim for necessсrio, a mesma demanda deve ser utilizada, nao sendo permitido incluir 
	 * uma nova demanda para o mesmo termo aditivo de prorrogacao, que deve ser unificada com o novo evento que se deseja registrar
	 * RESUMINDO: havendo uma demanda de prorrogacao aberta, para o mesmo contrato, esta deve ser utilizada para qualquer novo evento
	 * @param unknown $voDemanda
	 * @return boolean
	 */
	function existeDemandaAbertaContratoProrrogacao($voDemanda) {
		$retorno = false;
		//$voDemanda = new voDemanda();
		//$isSituacaoFechada = $voDemanda->situacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA;
		$isSituacaoFechada = isSituacaoDemandaFechada($voDemanda->situacao);
			
		$vocontrato = $voDemanda->getContrato();
		//se a situacao eh pra fechar, nao precisa validar
		if(!$isSituacaoFechada && $vocontrato != null){
			//$vocontrato = new vocontrato();
			//$voDemanda = new voDemanda();
			//unico caso em que permite incluir demanda para um contrato que ja possui demanda de prorrogacao aberta: quando eh reajuste, posto que nao se sabe o termo que sera gerado no futuro(pode ser TA ou apostilamento)
			$permiteDemandaMesmoContrato = $vocontrato->cdEspecie == dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER 
				&& dominio::existePeloMenosUmaChaveColecaoNoArrayOuStrSeparador(array_keys(dominioTipoDemandaContrato::getColecaoReajustamento()), $voDemanda->tpDemandaContrato);
			//var_dump($voDemanda->tpDemandaContrato);
			//throw new excecaoGenerica($voDemanda->tpDemandaContrato . " " . $permiteDemandaMesmoContrato);			
			if(!$permiteDemandaMesmoContrato){
				$filtro = new filtroManterDemanda(false);
				$filtro->vocontrato = new vocontrato();
				$filtro->vodemanda = new voDemanda();
				$filtro->vocontrato->anoContrato = $vocontrato->anoContrato;
				$filtro->vocontrato->cdContrato = $vocontrato->cdContrato;
				$filtro->vocontrato->tipo = $vocontrato->tipo;
				$filtro->vocontrato->cdEspecie = $vocontrato->cdEspecie;
				$filtro->vocontrato->sqEspecie = $vocontrato->sqEspecie;
				$filtro->inDesativado = constantes::$CD_NAO;
				$filtro->vodemanda->tpDemandaContrato = array(dominioTipoDemandaContrato::$CD_TIPO_PRORROGACAO);
				
				$filtro->vodemanda->situacao = array(dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_FAZER);
				$colecao = $this->consultarTelaConsulta($voDemanda, $filtro);
				
				$retorno = !isColecaoVazia($colecao);
				//$vocontrato = new vocontrato();			
				if($retorno){
					throw new excecaoGenerica("Jс existe uma demanda ABERTA para o Contrato ".$vocontrato->getCodigoContratoFormatado().". Verifique se o TERMO/ADITIVO indicado estс correto.");
				}
			}
		}
		
		return 	$retorno;
	}
	
	function incluirColecaoDemandaContrato($voDemanda) {		
		$colecao = $voDemanda->colecaoContrato;
		
		foreach ( $colecao as $voContrato ) {
			if(!$this->existeDemandaAbertaContratoProrrogacao($voDemanda)){
				$voDemContrato = new voDemandaContrato ();
				$voDemContrato = $voDemanda->getVODemandaContrato ( $voContrato );
				$this->incluirDemandaContrato ( $voDemContrato );
			}
		}
	}
	function incluirDemandaContrato($voDemContrato) {
		$voDemContrato->dbprocesso->cDb = $this->cDb;
		$voDemContrato->dbprocesso->incluir ( $voDemContrato );
	}
	function incluirDemandaProcLicitatorio($voDemandaProcLic) {
		$voDemandaProcLic->dbprocesso->cDb = $this->cDb;
		$voDemandaProcLic->dbprocesso->incluir ( $voDemandaProcLic );
	}
	function incluirDemandaSolicCompra($voDemandaSolicCompra) {
		$voDemandaSolicCompra->dbprocesso->cDb = $this->cDb;
		$voDemandaSolicCompra->dbprocesso->incluir ( $voDemandaSolicCompra );
	}
	function incluirSQL($vo) {
		if ($vo->cd == null || $vo->cd == "") {
			$vo->cd = $this->getProximoSequencialChaveComposta ( voDemanda::$nmAtrCd, $vo );
		}
		return $this->incluirQueryVO ( $vo );
	}
	
	function tratarDados(&$vo){
		static::validarGenerico($vo);
		
		$tpDemandaContrato = $vo->tpDemandaContrato;		
		$fase = $vo->fase;
		$inCaracteristicas = $vo->inCaracteristicas;		
		$tipo = $vo->tipo;
		//quando vem da tela eh um array
		//quando vem do banco, deve ser uma string
		//dai eh importante sempre converter pra string
		if(is_array($tpDemandaContrato)){
			$tpDemandaContrato = voDemanda::getArrayComoStringCampoSeparador($tpDemandaContrato);
		}
		
		if(is_array($fase)){
			$fase = voDemanda::getArrayComoStringCampoSeparador($fase);
		}
		if(is_array($inCaracteristicas)){
			$inCaracteristicas = voDemanda::getArrayComoStringCampoSeparador($inCaracteristicas);
		}
		
		$vo->tpDemandaContrato = $tpDemandaContrato;
		$vo->fase = $fase;
		$vo->inCaracteristicas = $inCaracteristicas;
		
		if(!dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE, $tpDemandaContrato)){
			$vo->inTpDemandaReajusteComMontanteA = null;
		}		
				
		if($vo->inLegado==null){
			$vo->inLegado = constantes::$CD_SIM;
		}
		
		return $vo; 
	}
	function getSQLValuesInsert($vo) {
		$vo = $this->tratarDados($vo);
		
		$retorno = "";
		$retorno .= $this->getVarComoNumero ( $vo->ano ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cd ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->tipo ) . ",";
	
		$retorno.= $this-> getVarComoString($vo->tpDemandaContrato). ",";		
		$retorno .= $this->getVarComoString ($vo->inTpDemandaReajusteComMontanteA) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->cdSetor ) . ",";
		// $retorno.= $this-> getVarComoNumero($vo->situacao);
		$retorno .= $this->getVarComoNumero ( dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA ) . ",";
		$retorno .= $this->getVarComoString ( strtoupper ( $vo->texto ) ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->prioridade ) . ",";
		$retorno .= $this->getVarComoData ( $vo->dtReferencia ). ",";
		$retorno .= $this->getVarComoNumero ( $vo->cdPessoaRespATJA ). ",";
		$retorno .= $this->getVarComoNumero ( $vo->cdPessoaRespUNCT ). ",";
		$retorno .= $this-> getVarComoString($vo->fase). ",";
		$retorno .= $this-> getVarComoString($vo->inCaracteristicas). ",";
		$retorno .= $this-> getVarComoString($vo->inMonitorar). ",";
		$retorno .= $this->getVarComoData ( $vo->dtMonitoramento). ",";
		$retorno .= $this->getVarComoString ( voDemanda::getNumeroPRTSemMascara($vo->prt) ) . ",";
		
		$retorno .= $this->getVarComoString($vo->inLegado);
		
		$retorno .= $vo->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		$vo = $this->tratarDados($vo);
		$retorno = "";
		$sqlConector = "";
				
		if ($vo->prioridade != null) {
			$retorno .= $sqlConector . voDemanda::$nmAtrPrioridade . " = " . $this->getVarComoNumero ( $vo->prioridade );
			$sqlConector = ",";
		}
		
		if ($vo->situacao != null) {
			$retorno .= $sqlConector . voDemanda::$nmAtrSituacao . " = " . $this->getVarComoNumero ( $vo->situacao );
			$sqlConector = ",";
		}
		
		if ($vo->texto != null) {
			$retorno .= $sqlConector . voDemanda::$nmAtrTexto . " = " . $this->getVarComoString ( strtoupper ( $vo->texto ) );
			$sqlConector = ",";
		}
		
		if ($vo->tipo != null) {
			$retorno .= $sqlConector . voDemanda::$nmAtrTipo . " = " . $this->getVarComoNumero ( $vo->tipo );
			$sqlConector = ",";
		}
		
		$retorno .= $sqlConector . voDemanda::$nmAtrTpDemandaContrato . " = " . $this->getVarComoString ( $vo->tpDemandaContrato );
		$sqlConector = ",";
		
		$retorno .= $sqlConector . voDemanda::$nmAtrInTpDemandaReajusteComMontanteA . " = " . $this->getVarComoString ( $vo->inTpDemandaReajusteComMontanteA );
		$sqlConector = ",";		
		
		$retorno .= $sqlConector . voDemanda::$nmAtrCdPessoaRespATJA . " = " . $this->getVarComoNumero ( $vo->cdPessoaRespATJA );
		$sqlConector = ",";
		
		$retorno .= $sqlConector . voDemanda::$nmAtrCdPessoaRespUNCT . " = " . $this->getVarComoNumero ( $vo->cdPessoaRespUNCT);
		$sqlConector = ",";
		
		$retorno .= $sqlConector . voDemanda::$nmAtrFase . " = " . $this->getVarComoString ( $vo->fase );
		$sqlConector = ",";
		
		$retorno .= $sqlConector . voDemanda::$nmAtrInCaracteristicas . " = " . $this->getVarComoString ( $vo->inCaracteristicas );
		$sqlConector = ",";
		
		$retorno .= $sqlConector . voDemanda::$nmAtrInMonitorar . " = " . $this->getVarComoString ( $vo->inMonitorar );
		$sqlConector = ",";
		
		$retorno .= $sqlConector . voDemanda::$nmAtrDtMonitoramento . " = " . $this->getVarComoData( $vo->dtMonitoramento);
		$sqlConector = ",";
				
		$retorno .= $sqlConector . voDemanda::$nmAtrProtocolo . " = " . $this->getVarComoString ( voDemanda::getNumeroPRTSemMascara($vo->prt));
		$sqlConector = ",";
				
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
}
?>