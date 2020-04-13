<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbDemanda extends dbprocesso {
	static $FLAG_PRINTAR_SQL = FALSE;
	
	function consultarPorChaveTelaColecaoContrato($vo, $isHistorico) {
		try {
			// para o caso de so haver um contrato, o consultarPorChaveTelaJoinContrato traz apenas um registro
			// como realmente deve ser
			$colecao = $this->consultarPorChaveTelaJoinContrato ( $vo, $isHistorico, true );
			$voContrato = new vocontrato ();
			$voContrato->getDadosBanco ( $colecao );
			
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
	function consultarPorChaveTelaJoinContrato($vo, $isHistorico, $isConsultaPorChave) {
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
				// $nmTabelaPessoa . "." . vopessoa::$nmAtrNome,
				getSQLNmContratada (),
				"COALESCE (" . " . $nmTabelaTramitacao." . voDemandaTramitacao::$nmAtrCdSetorDestino . "," . $nmTabela . "." . voDemanda::$nmAtrCdSetor . ") AS " . voDemanda::$nmAtrCdSetorAtual 
		);
		
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
	function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico;
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela ();
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabela ();
		$nmTabelaDemandaProcLic = voDemandaPL::getNmTabelaStatic ( false );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPA = voPA::getNmTabelaStatic ( false );
		
		$cdSetorAtual = $filtro->vodemanda->cdSetorDestino;
		$isSetorAtualSelecionado = $filtro->isSetorAtualSelecionado ();
		$nmTabelaMINDestinoTramitacao = "TABELA_MIN_TRAMDESTINO";
		$nmTabelaDestinoTramitacao = "TABELA_DESTINO_TRAM";
		
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
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				"COUNT(*)  AS " . filtroManterDemanda::$NmColQtdContratos,
				static::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioInclusao,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato,
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato,
				$nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdProcLic,
				$nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoProcLic,
				$nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdModalidadeProcLic,
				getSQLNmContratada (),
				// $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . " AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
				"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "," . $nmTabela . "." . voDemanda::$nmAtrCdSetor . ") AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
				"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrDhInclusao . "," . $nmTabela . "." . voDemanda::$nmAtrDhUltAlteracao . ") AS " . filtroManterDemanda::$NmColDhUltimaMovimentacao,
				$colunaUsuHistorico,
				$colunaDtReferenciaSetorAtual 
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
		
		// o proximo join eh p pegar o registro de contrato mais atual na planilha
		// faz o join apenas com os contratos de maximo sequencial (mais atual)
		// e com pessoa contratada diferente de nulo
		$nmTabelaMAXContrato = "TABELA_MAX_CONTRATO";
		$atributosGroupContrato = vocontrato::$nmAtrAnoContrato . "," . vocontrato::$nmAtrTipoContrato . "," . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n LEFT JOIN (";
		$queryJoin .= " \n\nSELECT MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato . "," . $atributosGroupContrato 
				. " FROM " . $nmTabelaContrato 
				. " WHERE " . vocontrato::$nmAtrContratadaContrato . " IS NOT NULL "
				. " GROUP BY " . $atributosGroupContrato;
		$queryJoin .= ") $nmTabelaMAXContrato";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrCdContrato;
		
		// agora pega dos dados da ultima tramitacao, se houver
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= $nmTabelaContrato;
		$queryJoin .= "\n ON " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . " = $nmTabelaMAXContrato." . vocontrato::$nmAtrSqContrato;
		
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
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPA;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPA . "." . voPA::$nmAtrAnoDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrAno;
		$queryJoin .= "\n AND " . $nmTabelaPA . "." . voPA::$nmAtrCdDemanda . "=" . $nmTabela . "." . voDemanda::$nmAtrCd;
		
		// Para o caso de se desejar ordenar pela primeira vez que foi encaminhada ao setor atual selecionado pelo usuario
		
		if ($isSetorAtualSelecionado) {
			// echo "tem";
			$queryJoin .= "\n LEFT JOIN (";
			$queryJoin .= " SELECT MIN(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq . "," . $atributosGroup . " FROM " . $nmTabelaTramitacao . " WHERE " . voDemandaTramitacao::$nmAtrCdSetorDestino . " = " . $cdSetorAtual . " GROUP BY " . $atributosGroup;
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
	function isSetorDestinoIgualDemandante($vo, $textoFuncao, $naoValidar = false) {
		// verifica se o setor atual eh igual ao setor de origem
		$filtro = new filtroManterDemanda ( false );
		$filtro->vodemanda = new voDemanda ();
		$filtro->vodemanda->cd = $vo->cd;
		$filtro->vodemanda->ano = $vo->ano;
		// echo "SITUACAO FECHADA";
		$colecao = $this->consultarTelaConsulta ( $vo, $filtro );
		$retorno = true;
		if ($colecao != "") {
			$setorAtual = $colecao [0] [voDemandaTramitacao::$nmAtrCdSetorDestino];
			// echo "setor atual:" . $setorAtual;
			if ($setorAtual != null && $vo->cdSetor != $setorAtual && !$naoValidar) {
				$retorno = false;
				$msg = "A demanda deve estar encaminhada ao setor responsável para $textoFuncao.";
				throw new Exception ( $msg );
			}
		} // else echo "COLECAO VAZIA";
	
		return $retorno;
	}	
	
	static function validarGenerico(&$vo) {
		$tipo = $vo->tipo;
		$tpDemandaContrato = $vo->tpDemandaContrato;
		$tpReajuste = $vo->inTpDemandaReajusteComMontanteA;
		
		if ($tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO){
			if($tpDemandaContrato == null || $tpDemandaContrato == "" || isColecaoVazia($tpDemandaContrato)){
				throw new excecaoGenerica ( "Os campos informações complementares da demanda/contrato são obrigatórios." );
			}
			
			/*if(is_array($tpDemandaContrato)){
				$tpDemandaContratoStr = voDemanda::getArrayComoStringCampoSeparador($tpDemandaContrato);
			}				
			if(dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE, $tpDemandaContratoStr)
					&& ($tpReajuste == null || $tpReajuste == "")){
				throw new excecaoGenerica ( "O campo tipo de reajuste é obrigatório." );
			}*/
		}		
		
		if (dominioTipoDemanda::isContratoObrigatorio($tipo) && ! $vo->temContratoParaIncluir ()) {
			$msg = "Selecione ao menos um contrato.";
			throw new excecaoGenerica ( $msg );
		}
	}
	
	function validarAlteracao($vo) {
		
		$this->validarGenerico($vo);
		
		if ($vo->situacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA) {
			
			$this->isSetorDestinoIgualDemandante ( $vo, "fechamento" );
			
			// verifica se tem PAAP para encerrar
			  // $vo = new voDemanda();
			if ($vo->tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_PROCADM) {
				$voPA = $this->consultarPAAPDemanda ( $vo );
				if ($voPA != null) {
					$situacaoPAAP = $voPA->situacao;
					$isSituacaoPAAPAtivo = dominioSituacaoPA::existeItem ( $situacaoPAAP, dominioSituacaoPA::getColecaoSituacaoAtivos () );
					if ($isSituacaoPAAPAtivo) {
						throw new excecaoGenerica ( "Fechamento não permitido para demanda cujo PAAP esteja ativo." );
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
					throw new excecaoGenerica ( "$textoFuncao não permitido(a) para demanda cujo PAAP esteja ativo." );
				}
			}
		}
		return true;
	}
	function validarExclusao($vo) {
		$textoFuncao = "Exclusão";
		return $this->isSetorDestinoIgualDemandante ( $vo, $textoFuncao ) && $this->isDemandaPAAPInativo ( $vo, $textoFuncao );
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
	function incluirColecaoDemandaContrato($voDemanda) {
		$colecao = $voDemanda->colecaoContrato;
		foreach ( $colecao as $voContrato ) {
			$voDemContrato = new voDemandaContrato ();
			$voDemContrato = $voDemanda->getVODemandaContrato ( $voContrato );
			$this->incluirDemandaContrato ( $voDemContrato );
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
		$tipo = $vo->tipo;
		//quando vem da tela eh um array
		//quando vem do banco, deve ser uma string
		//dai eh importante sempre converter pra string
		if(is_array($tpDemandaContrato)){
			$tpDemandaContrato = voDemanda::getArrayComoStringCampoSeparador($tpDemandaContrato);
		}
					
		$vo->tpDemandaContrato = $tpDemandaContrato;
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
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
}
?>