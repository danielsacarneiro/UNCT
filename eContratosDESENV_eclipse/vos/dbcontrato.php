<?php
include_once (caminho_lib . "dbprocesso.obj.php");
include_once (caminho_funcoes . "/contrato/dominioEspeciesContrato.php");
include_once (caminho_vos . "vocontrato.php");
include_once (caminho_vos . "vousuario.php");
include_once (caminho_filtros . "filtroManterContrato.php");

// .................................................................................................................
// Classe select
// cria um combo select html
class dbcontrato extends dbprocesso {
	static $FLAG_PRINTAR_SQL = false;
	static $FLAG_PRINTAR_LOG_IMPORTACAO = false;
	static $FLAG_PRINTAR_LOG_IMPORTACAO_ERRO = TRUE;
	static $FLAG_PRINTAR_LOG_IMPORTACAO_NUM_LINHA = false;

	static $CD_CONSTANTE_FIM_IMPORTACAO = "FIM";
	static $CD_CONSTANTE_PENDENTE_IMPORTACAO = "PENDENTE";
	static $CD_CONSTANTE_DATA_INVALIDA = "00-00-00";
	
	static $ID_REQ_INICIAR_TAB_CONTRATO= "ID_REQ_INICIAR_TAB_CONTRATO";
	static $ID_REQ_REMOVER_CARACTER_ESPECIAL = "ID_REQ_REMOVER_CARACTER_ESPECIAL";

	static $NM_ARQUIVO_PLANILHA_CONTRATOS= "CONTRATOS C-SAFI  2017- UNCT - ATUAL.xlsx";
	static $NM_PLANILHA_CONTRATOS= "Contratos Vigentes";
	static $NM_PLANILHA_CONVENIOS= "Convênios";

	function consultarFiltroManterContrato($voentidade, $filtro) {
		$isArquivo = ("S" == $filtro->cdConsultarArquivo);

		if ($isArquivo) {
			return "";
		} else {
			$groupby = array (
					vocontrato::$nmAtrSqContrato
			);
			$filtro->groupby = $groupby;
				
			//$retorno = $this->consultarFiltroManter ( $filtro, true );
			$arrayParamConsulta = array($filtro);
			$retorno = $this->consultarTelaConsulta($arrayParamConsulta);
				
			return $retorno;
		}
	}
	
	function consultarTelaConsulta($arrayParamConsulta){
		$filtro = $arrayParamConsulta[0];
		$vo = $filtro->voPrincipal;
		$isHistorico = ("S" == $filtro->cdHistorico);
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic(false);
		//$nmTabelaDemandaSolicCompra = voDemandaPL::getNmTabelaStatic(false);
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
		);		
		 		 
		$queryJoin .= "\n left JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= " AND " . $nmTabelaContratoInfo . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= " AND " . $nmTabelaContratoInfo . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
	
		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
	}
	
	function consultarContratoModificacao($vo, $isHistorico) {

		$vocontratoTemp = clone $vo;
		$vocontratoTemp->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$vocontratoTemp->sqEspecie = 1;

		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaContratoMod = voContratoModificacao::getNmTabelaStatic ( false );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaContratoUltValorRepactuado = "NM_TAB_CONTRATO_VL_REPACTUADO";
		$nmTabelaContratoUltValorReajustado = "NM_TAB_CONTRATO_VL_REAJUSTADO";
		$nmTabelaContratoUltValorAtualizado= "NM_TAB_CONTRATO_VL_ATUALIZADO";
		$nmTabelaContratoInseridoTela= "NM_TAB_CONTRATO_INSERIDO_TELA";

		$nmTabContratoModSqMAXReajuste = "TAB_MAX_MOD_CONTRATO_REAJUSTE";
		$nmTabContratoModSqMAXRepactuacao = "TAB_MAX_MOD_CONTRATO_REPACTUACAO";

		$arrayColunasRetornadas = array (
				"$nmTabelaContratoInseridoTela.".vocontrato::$nmAtrVlMensalContrato . " AS " . voContratoModificacao::$ID_REQ_VlMensalContratoInseridoTela,
				"$nmTabelaContratoInseridoTela.".vocontrato::$nmAtrVlGlobalContrato . " AS " . voContratoModificacao::$ID_REQ_VlGlobalContratoInseridoTela,
				getSQLCOALESCE(
						array(
								"$nmTabelaContratoUltValorAtualizado.".voContratoModificacao::$nmAtrVlMensalAtualizado,
								"$nmTabela.".vocontrato::$nmAtrVlMensalContrato),
						vocontrato::$nmAtrVlMensalContrato),
				getSQLCOALESCE(
						array(
								"$nmTabelaContratoUltValorAtualizado.".voContratoModificacao::$nmAtrVlGlobalAtualizado,
								"$nmTabela.".vocontrato::$nmAtrVlGlobalContrato),
						vocontrato::$nmAtrVlGlobalContrato),
				//verifica se tem valor repactuado
				" CASE WHEN $nmTabelaContratoUltValorRepactuado." . voContratoModificacao::$nmAtrSq
				. " > "
				. "$nmTabelaContratoUltValorReajustado ." . voContratoModificacao::$nmAtrSq
				." THEN "
				. "$nmTabelaContratoUltValorRepactuado.".voContratoModificacao::$nmAtrVlMensalAtualizado
				. " ELSE " .
				getSQLCOALESCE(
						array("$nmTabelaContratoUltValorReajustado.".voContratoModificacao::$nmAtrVlMensalModAtual,
								"$nmTabela.".vocontrato::$nmAtrVlMensalContrato))
				. " END AS " . voContratoModificacao::$nmAtrVlMensalModAtual,
				//verifica se tem valor repactuado
				" CASE WHEN $nmTabelaContratoUltValorRepactuado." . voContratoModificacao::$nmAtrSq
				. " > "
				. "$nmTabelaContratoUltValorReajustado ." . voContratoModificacao::$nmAtrSq
				." THEN "
				. "$nmTabelaContratoUltValorRepactuado.".voContratoModificacao::$nmAtrVlGlobalAtualizado
				. " ELSE " .
				getSQLCOALESCE(
						array(
								"$nmTabelaContratoUltValorReajustado.".voContratoModificacao::$nmAtrVlGlobalModAtual,
								"$nmTabela.".vocontrato::$nmAtrVlGlobalContrato))
				. " END AS " . voContratoModificacao::$nmAtrVlGlobalModAtual,

				"$nmTabela." . vocontrato::$nmAtrAnoContrato,
				"$nmTabela." . vocontrato::$nmAtrCdContrato,
				"$nmTabela." . vocontrato::$nmAtrTipoContrato,
				"$nmTabela." . vocontrato::$nmAtrCdEspecieContrato,
				"$nmTabela." . vocontrato::$nmAtrSqEspecieContrato,

				"$nmTabelaContratoInseridoTela." . vocontrato::$nmAtrDtVigenciaInicialContrato,
				"$nmTabelaContratoInseridoTela." . vocontrato::$nmAtrDtVigenciaFinalContrato,
				"$nmTabelaContratoInseridoTela." . vocontrato::$nmAtrDtAssinaturaContrato,
		);

		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;

		$nmTabContratoInterna = voContratoModificacao::getNmTabelaStatic ( false );
		$groupbyinterno = voContratoModificacao::$nmAtrAnoContrato . "," . voContratoModificacao::$nmAtrCdContrato . "," . voContratoModificacao::$nmAtrTipoContrato;
		//relacao da tabela que pega o ultimo valor atualizado para fins de reajuste
		//desconsiderando acrescimos e supressoes
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= "\n\n (SELECT " . $groupbyinterno . ", MAX(" . voContratoModificacao::$nmAtrSq . ") AS " . voContratoModificacao::$nmAtrSq;
		$queryJoin .= " FROM " . $nmTabContratoInterna;
		//repactuacao nao entra aqui porque o percentual da repactuacao nao eh considerado para fins de reajuste
		$queryJoin .= " WHERE " . voContratoModificacao::$nmAtrTpModificacao . " = " . dominioTpContratoModificacao::$CD_TIPO_REAJUSTE;
		//$queryJoin .= " WHERE " . voContratoModificacao::$nmAtrTpModificacao
		//		. " IN (" . getSQLStringFormatadaColecaoIN(array(dominioTpContratoModificacao::$CD_TIPO_REPACTUACAO, dominioTpContratoModificacao::$CD_TIPO_REAJUSTE), false) . ")";
		$queryJoin .= " GROUP BY " . $groupbyinterno;
		$queryJoin .= "\n) " . $nmTabContratoModSqMAXReajuste;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoModSqMAXReajuste . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoModSqMAXReajuste . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoModSqMAXReajuste . "." . voContratoModificacao::$nmAtrTipoContrato;

		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoMod . " $nmTabelaContratoUltValorReajustado";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabContratoModSqMAXReajuste . "." . voContratoModificacao::$nmAtrAnoContrato . "=" . $nmTabelaContratoUltValorReajustado . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabContratoModSqMAXReajuste . "." . voContratoModificacao::$nmAtrCdContrato . "=" . $nmTabelaContratoUltValorReajustado . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabContratoModSqMAXReajuste . "." . voContratoModificacao::$nmAtrTipoContrato . "=" . $nmTabelaContratoUltValorReajustado . "." . voContratoModificacao::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabContratoModSqMAXReajuste . "." . voContratoModificacao::$nmAtrSq . "=" . $nmTabelaContratoUltValorReajustado . "." . voContratoModificacao::$nmAtrSq;

		//AGORA PARA REPACTUACAO
		//se o contratoModMAx for repactuacao, este sera o valor utilizado como valor referencia para acrescimo
		//tendo em vista que a repactuacao tem efeito de nova contratacao
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= "\n\n (SELECT " . $groupbyinterno . ", MAX(" . voContratoModificacao::$nmAtrSq . ") AS " . voContratoModificacao::$nmAtrSq;
		$queryJoin .= " FROM " . $nmTabContratoInterna;
		$queryJoin .= " WHERE " . voContratoModificacao::$nmAtrTpModificacao . " = " . dominioTpContratoModificacao::$CD_TIPO_REPACTUACAO;
		$queryJoin .= " GROUP BY " . $groupbyinterno;
		$queryJoin .= "\n) " . $nmTabContratoModSqMAXRepactuacao;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoModSqMAXRepactuacao . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoModSqMAXRepactuacao . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoModSqMAXRepactuacao . "." . voContratoModificacao::$nmAtrTipoContrato;

		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoMod . " $nmTabelaContratoUltValorRepactuado";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabContratoModSqMAXRepactuacao . "." . voContratoModificacao::$nmAtrAnoContrato . "=" . $nmTabelaContratoUltValorRepactuado . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabContratoModSqMAXRepactuacao . "." . voContratoModificacao::$nmAtrCdContrato . "=" . $nmTabelaContratoUltValorRepactuado . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabContratoModSqMAXRepactuacao . "." . voContratoModificacao::$nmAtrTipoContrato . "=" . $nmTabelaContratoUltValorRepactuado . "." . voContratoModificacao::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabContratoModSqMAXRepactuacao . "." . voContratoModificacao::$nmAtrSq . "=" . $nmTabelaContratoUltValorRepactuado . "." . voContratoModificacao::$nmAtrSq;

		//relacao da tabela que pega o ultimo valor atualizado geral
		$nmTabContratoModSqMAXAtualizado = "TAB_MAX_MOD_CONTRATO_ATUALIZADO";
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= "\n\n (SELECT " . $groupbyinterno . ", MAX(" . voContratoModificacao::$nmAtrSq . ") AS " . voContratoModificacao::$nmAtrSq;
		$queryJoin .= " FROM " . $nmTabContratoInterna;
		$queryJoin .= " GROUP BY " . $groupbyinterno;
		$queryJoin .= "\n) " . $nmTabContratoModSqMAXAtualizado;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoModSqMAXAtualizado . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoModSqMAXAtualizado . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoModSqMAXAtualizado . "." . voContratoModificacao::$nmAtrTipoContrato;

		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoMod . " $nmTabelaContratoUltValorAtualizado";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabContratoModSqMAXAtualizado . "." . voContratoModificacao::$nmAtrAnoContrato . "=" . $nmTabelaContratoUltValorAtualizado . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabContratoModSqMAXAtualizado . "." . voContratoModificacao::$nmAtrCdContrato . "=" . $nmTabelaContratoUltValorAtualizado . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabContratoModSqMAXAtualizado . "." . voContratoModificacao::$nmAtrTipoContrato . "=" . $nmTabelaContratoUltValorAtualizado . "." . voContratoModificacao::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabContratoModSqMAXAtualizado . "." . voContratoModificacao::$nmAtrSq . "=" . $nmTabelaContratoUltValorAtualizado . "." . voContratoModificacao::$nmAtrSq;


		$groupbyinterno = vocontrato::$nmAtrAnoContrato
		. "," . vocontrato::$nmAtrTipoContrato
		. "," . vocontrato::$nmAtrCdContrato
		. "," . vocontrato::$nmAtrDtAssinaturaContrato
		. "," . vocontrato::$nmAtrDtVigenciaInicialContrato
		. "," . vocontrato::$nmAtrDtVigenciaFinalContrato
		. "," . vocontrato::$nmAtrVlMensalContrato
		. "," . vocontrato::$nmAtrVlGlobalContrato;

		//$vocontratoTemp = $vo;
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= "\n\n (SELECT $groupbyinterno  FROM  $nmTabela ";
		$queryJoin .= " WHERE " . $vo->getValoresWhereSQLChave ( $isHistorico );
		$queryJoin .= "\n) " . $nmTabelaContratoInseridoTela;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabelaContratoInseridoTela . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabelaContratoInseridoTela . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabelaContratoInseridoTela . "." . vocontrato::$nmAtrTipoContrato;

		//echo "teste";
		return $this->consultarPorChaveMontandoQuery ( $vocontratoTemp, $arrayColunasRetornadas, $queryJoin, $isHistorico );
	}

	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaProcLic = voProcLicitatorio::getNmTabelaStatic ( false );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPregoeiro = "NM_TAB_PREGOEIRO";

		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				"$nmTabelaProcLic.".voProcLicitatorio::$nmAtrCdCPL,
				"$nmTabelaContratoInfo." . voContratoInfo::$nmAtrDtProposta,
				"$nmTabelaContratoInfo." . voContratoInfo::$nmAtrInEscopo,
				"$nmTabelaPregoeiro." . vopessoa::$nmAtrNome . " AS " . voProcLicitatorio::$NmColNomePregoeiro,
				"TAB2." . vousuario::$nmAtrName . " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao,
		);

		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;

		$queryJoin .= "\n LEFT JOIN " . $nmTabelaProcLic;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoProcessoLicContrato . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrAno;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdProcessoLicContrato . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCd;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdModalidadeProcessoLicContrato . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCdModalidade;
		
		$queryJoin .= "\n LEFT JOIN $nmTabelaPessoa $nmTabelaPregoeiro" ;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPregoeiro . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCdPregoeiro;

		$queryJoin .= "\n LEFT JOIN " . vousuario::$nmEntidade;
		$queryJoin .= "\n TAB1 ON ";
		$queryJoin .= "TAB1." . vousuario::$nmAtrID . "=$nmTabela." . vocontrato::$nmAtrCdUsuarioInclusao;
		$queryJoin .= "\n LEFT JOIN " . vousuario::$nmEntidade;
		$queryJoin .= "\n TAB2 ON ";
		$queryJoin .= "TAB2." . vousuario::$nmAtrID . "=$nmTabela." . vocontrato::$nmAtrCdUsuarioUltAlteracao;
			
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
	}
	function consultarContratoPorChave($voContrato, $isHistorico) {
		//$nmTabela = $voContrato->getNmTabelaEntidade ( $isHistorico );
		$nmTabela = vocontrato::getNmTabelaStatic ( $isHistorico );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaContratoLicon = voContratoLicon::getNmTabelaStatic ( false );
		$nmTabContratoLiconSqMAX = "TAB_MAX_LICON_CONTRATO";

		$queryJoin = "SELECT $nmTabela.*";
		$queryJoin .= ", $nmTabelaContratoInfo." . voContratoInfo::$nmAtrDtProposta;
		$queryJoin .= ", $nmTabelaContratoInfo." . voContratoInfo::$nmAtrInEscopo;
		$queryJoin .= ", $nmTabelaContratoLicon." . voContratoLicon::$nmAtrSituacao;
		$queryJoin .= ", TAB1." . vousuario::$nmAtrName . " AS " . voentidade::$nmAtrNmUsuarioInclusao;
		$queryJoin .= ", TAB2." . vousuario::$nmAtrName . " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
			

		$queryJoin .= " FROM " . $nmTabela;

		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;

		$groupbyinterno = $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrAnoContrato
		. "," . $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrCdContrato
		. "," . $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrTipoContrato
		. "," . $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrCdEspecieContrato
		. "," . $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrSqEspecieContrato
		;


		//a query abaixo eh p pegar a ultima situacao incluida em contratolicon que definirah se o contrato foi incluido no licon
		$nmTabContratoInterna = $nmTabelaContratoLicon;
		$queryJoin .= "\n left JOIN ";

		$nmTabelaTemp = "$nmTabela" . "TEMP";
		$queryJoin .= " (SELECT " . voContratoLicon::$nmAtrAnoDemanda . "," . voContratoLicon::$nmAtrCdDemanda . ", $groupbyinterno, MAX($nmTabContratoInterna." . voContratoLicon::$nmAtrDhInclusao . ") FROM " . $nmTabContratoInterna;
		$queryJoin .= " INNER JOIN $nmTabela ";
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoInterna . "." . voContratoLicon::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoInterna . "." . voContratoLicon::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoInterna . "." . voContratoLicon::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabContratoInterna . "." . voContratoLicon::$nmAtrCdEspecieContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabContratoInterna . "." . voContratoLicon::$nmAtrSqEspecieContrato;
		$queryJoin .= " WHERE " . voContratoLicon::$nmAtrSituacao . " IN (" . getSQLStringFormatadaColecaoIN(array_keys(dominioSituacaoContratoLicon::getColecaoIncluidoSucesso()), false) . ")";
		$queryJoin .= " AND " . $voContrato->getValoresWhereSQLChave ( $isHistorico );
		$queryJoin .= " GROUP BY " . $groupbyinterno;
		$queryJoin .= "\n) " . $nmTabContratoLiconSqMAX;

		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoLiconSqMAX . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoLiconSqMAX . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoLiconSqMAX . "." . vocontrato::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdEspecieContrato. "=" . $nmTabContratoLiconSqMAX . "." . vocontrato::$nmAtrCdEspecieContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabContratoLiconSqMAX . "." . vocontrato::$nmAtrSqEspecieContrato;
			
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoLicon;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrCdContrato . "=" . $nmTabContratoLiconSqMAX . "." . voContratoLicon::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrAnoContrato . "=" . $nmTabContratoLiconSqMAX . "." . voContratoLicon::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrTipoContrato . "=" . $nmTabContratoLiconSqMAX . "." . voContratoLicon::$nmAtrTipoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrCdEspecieContrato . "=" . $nmTabContratoLiconSqMAX . "." . voContratoLicon::$nmAtrCdEspecieContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrSqEspecieContrato . "=" . $nmTabContratoLiconSqMAX . "." . voContratoLicon::$nmAtrSqEspecieContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrAnoDemanda . "=" . $nmTabContratoLiconSqMAX . "." . voContratoLicon::$nmAtrAnoDemanda;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrCdDemanda . "=" . $nmTabContratoLiconSqMAX . "." . voContratoLicon::$nmAtrCdDemanda;

		$queryJoin .= "\n LEFT JOIN " . vousuario::$nmEntidade;
		$queryJoin .= "\n TAB1 ON ";
		$queryJoin .= "TAB1." . vousuario::$nmAtrID . "=$nmTabela." . vocontrato::$nmAtrCdUsuarioInclusao;
		$queryJoin .= "\n LEFT JOIN " . vousuario::$nmEntidade;
		$queryJoin .= "\n TAB2 ON ";
		$queryJoin .= "TAB2." . vousuario::$nmAtrID . "=$nmTabela." . vocontrato::$nmAtrCdUsuarioUltAlteracao;
		$queryJoin .= " WHERE ";
		$queryJoin .= $voContrato->getValoresWhereSQLChave ( $isHistorico );
		
		//echo $voContrato->getValoresWhereSQLChave ( $isHistorico );
		//echo $queryJoin;
		//$queryJoin .= " AND $nmTabelaContratoInfo." . voContratoInfo::$nmAtrInDesativado . "= 'N' ";
		/*
		 * $queryJoin.= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $voContrato->cdContrato;
		 * $queryJoin.= " AND " . $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $voContrato->anoContrato;
		 * $queryJoin.= " AND ". $nmTabela . "." . vocontrato::$nmAtrSqContrato . "=" . $voContrato->sq;
		 */

		// echo $queryJoin;
		return $this->consultarEntidade ( $queryJoin, true );
	}

	function consultarContratoMovimentacoes($voContrato, $isHistorico=false) {
		$nmTabela = $voContrato->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( $isHistorico );

		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				"$nmTabelaContratoInfo." . voContratoInfo::$nmAtrDtProposta,
		);

		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;

		$nmAtributosWhere = array (
				"$nmTabela.".vocontrato::$nmAtrAnoContrato => $voContrato->anoContrato,
				"$nmTabela.".vocontrato::$nmAtrCdContrato => $voContrato->cdContrato,
				"$nmTabela.".vocontrato::$nmAtrTipoContrato => "'$voContrato->tipo'"
		);
		$queryWhere = "\n WHERE " . $voContrato->getValoresWhereSQL ( $voContrato, $nmAtributosWhere );

		//$orderby = "\n ORDER BY " . vocontrato::$nmAtrSqContrato . " " . constantes::$CD_ORDEM_CRESCENTE;
		$orderby = "\n ORDER BY " . vocontrato::$nmAtrDtAssinaturaContrato . " " . constantes::$CD_ORDEM_CRESCENTE;

		$colecao = $this->consultarMontandoQuery($voContrato, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, false, $orderby);
		return $colecao;
	}
	
	/**
	 * usado pra consultar informacoes do contrato incluindo informacoes de contrato_licon
	 * @param unknown $vo
	 * @param unknown $isHistorico
	 * @return unknown|string
	 */
	function consultarContratoComLicon($vo, $isHistorico, $isConsultaPorChave=true) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaContratoLicon = voContratoLicon::getNmTabelaStatic ( false );
	
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				"$nmTabelaContratoInfo." . voContratoInfo::$nmAtrDtProposta,
				"$nmTabelaContratoInfo." . voContratoInfo::$nmAtrInEscopo,
				"$nmTabelaContratoLicon." . voContratoLicon::$nmAtrAnoDemanda, //usado para saber se ha contrato_licon relacionado
				"$nmTabelaContratoLicon." . voContratoLicon::$nmAtrCdDemanda,
		);
		
		$queryJoin .= "\n left JOIN " . $nmTabelaContratoLicon;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrAnoContrato;
		$queryJoin .= " AND " . $nmTabela . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrCdContrato;
		$queryJoin .= " AND " . $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrTipoContrato;
		$queryJoin .= " AND " . $nmTabela . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrCdEspecieContrato;
		$queryJoin .= " AND " . $nmTabela . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContratoLicon . "." . voContratoLicon::$nmAtrSqEspecieContrato;
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
				
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico , $isConsultaPorChave);
	}
	
	function incluirSQL($voContrato) {
		$atributosInsert = $voContrato->getTodosAtributos ();
		// var_dump ($atributosInsert);
		$arrayAtribRemover = array (
				vocontrato::$nmAtrSqContrato,
				vocontrato::$nmAtrDhInclusao,
				vocontrato::$nmAtrDhUltAlteracao
		);

		// var_dump($arrayAtribRemover);

		$atributosInsert = removeColecaoAtributos ( $atributosInsert, $arrayAtribRemover );
		// var_dump ($atributosInsert);

		$atributosInsert = getColecaoEntreSeparador ( $atributosInsert, "," );

		// echo "<br>$atributosInsert";

		$query = " INSERT INTO " . $voContrato->getNmTabela () . " \n";
		$query .= " (";
		$query .= $atributosInsert;
		$query .= ") ";
		$query .= " \nVALUES(";
		$query .= $this->getSQLValuesInsert ( $voContrato );
		$query .= ")";

		//echoo($query);

		return $query;
	}
	function getSQLValuesInsert($voContrato) {
		$retorno = "";
		$retorno .= $voContrato->anoContrato . ",";
		$retorno .= $voContrato->cdContrato . ",";
		$retorno .= $this->getVarComoString ( $voContrato->tipo ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->especie ) . ",";
		$retorno .= $this->getVarComoNumero ( $voContrato->sqEspecie ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->cdEspecie ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->situacao ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->objeto ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->nmGestorPessoa ) . ",";
		$retorno .= $this->getVarComoNumero ( $voContrato->cdPessoaGestor ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->gestor ) . ",";
		$retorno .= $this->getVarComoNumero ( $voContrato->cdGestor ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->procLic ) . ",";
		$retorno .= $this->getVarComoNumero($voContrato->cdProcLic ) . ",";
		$retorno .= $this->getVarComoNumero($voContrato->anoProcLic ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->cdModalidadeLic ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->modalidade ) . ",";

		$retorno .= $this->getVarComoString ( $voContrato->dataPublicacao ) . ",";
		$retorno .= $this->getVarComoData( $voContrato->dtPublicacao ) . ",";
		$retorno .= $this->getVarComoData ( $voContrato->dtAssinatura ) . ",";
		$retorno .= $this->getVarComoData ( $voContrato->dtVigenciaInicial ) . ",";
		$retorno .= $this->getVarComoData ( $voContrato->dtVigenciaFinal ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->contratada ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->docContratada ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->empenho ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->tpAutorizacao ) . ",";
		$retorno .= $this->getVarComoNumero ( $voContrato->cdAutorizacao ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->licom ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->importacao ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->obs ) . ",";
		/*$retorno .= $this->getDecimalSQL ( $voContrato->vlGlobal ) . ",";
		 $retorno .= $this->getDecimalSQL ( $voContrato->vlMensal ) . ",";*/
		//echoo("valor global:".$voContrato->vlGlobal);
		$retorno .= $this->getVarComoDecimal($voContrato->vlGlobal ) . ",";
		$retorno .= $this->getVarComoDecimal($voContrato->vlMensal ) . ",";

		$retorno .= $this->getDataSQL ( $voContrato->dtProposta ) . ",";
		$retorno .= $this->getVarComoNumero ( $voContrato->cdPessoaContratada ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->linkDoc ) . ",";
		$retorno .= $this->getVarComoString ( $voContrato->linkMinutaDoc );

		$retorno .= $voContrato->getSQLValuesInsertEntidade ();

		//echoo($retorno);

		return $retorno;
	}
	function getSQLValuesUpdate($voContrato) {
		$retorno = "";
		$sqlConector = "";

		if ($voContrato->tipo != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrTipoContrato . " = " . $this->getVarComoString ( $voContrato->tipo );
			$sqlConector = ",";
		}

		if ($voContrato->especie != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrEspecieContrato . " = " . $this->getVarComoString ( $voContrato->especie );
			$sqlConector = ",";
		}

		if ($voContrato->sqEspecie != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrSqEspecieContrato . " = " . $this->getVarComoNumero ( $voContrato->sqEspecie );
			$sqlConector = ",";
		}

		if ($voContrato->cdEspecie != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrCdEspecieContrato . " = " . $this->getVarComoString ( $voContrato->cdEspecie );
			$sqlConector = ",";
		}

		if ($voContrato->modalidade != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrModalidadeContrato . " = " . $this->getVarComoString ( $voContrato->modalidade );
			$sqlConector = ",";
		}

		if ($voContrato->cdPessoaContratada != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrCdPessoaContratada . " = " . $this->getVarComoNumero ( $voContrato->cdPessoaContratada );
			$sqlConector = ",";
		}

		if ($voContrato->contratada != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrContratadaContrato . " = " . $this->getVarComoString ( $voContrato->contratada );
			$sqlConector = ",";
		}

		if ($voContrato->docContratada != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrDocContratadaContrato . " = " . $this->getVarComoString ( $voContrato->docContratada );
			$sqlConector = ",";
		}

		if ($voContrato->gestor != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrGestorContrato . " = " . $this->getVarComoString ( $voContrato->gestor );
			$sqlConector = ",";
		}

		if ($voContrato->cdGestor != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrCdGestorContrato . " = " . $this->getVarComoNumero ( $voContrato->cdGestor );
			$sqlConector = ",";
		}

		if ($voContrato->nmGestorPessoa != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrGestorPessoaContrato . " = " . $this->getVarComoString ( $voContrato->nmGestorPessoa );
			$sqlConector = ",";
		}

		if ($voContrato->cdPessoaGestor != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrCdPessoaGestorContrato . " = " . $this->getVarComoNumero ( $voContrato->cdPessoaGestor );
			$sqlConector = ",";
		}

		if ($voContrato->obs != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrObservacaoContrato . " = " . $this->getVarComoString ( $voContrato->obs );
			$sqlConector = ",";
		}

		if ($voContrato->objeto != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrObjetoContrato . " = " . $this->getVarComoString ( $voContrato->objeto );
			$sqlConector = ",";
		}

		if ($voContrato->procLic != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrProcessoLicContrato . " = " . $this->getVarComoString ( $voContrato->procLic );
			$sqlConector = ",";
		}

		if ($voContrato->cdProcLic != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrCdProcessoLicContrato . " = " . $this->getVarComoNumero($voContrato->cdProcLic );
			$sqlConector = ",";
		}

		if ($voContrato->anoProcLic != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrAnoProcessoLicContrato . " = " . $this->getVarComoNumero($voContrato->anoProcLic );
			$sqlConector = ",";
		}

		if ($voContrato->empenho != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrNumEmpenhoContrato . " = " . $this->getVarComoString ( $voContrato->empenho );
			$sqlConector = ",";
		}

		if ($voContrato->tpAutorizacao != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrTipoAutorizacaoContrato . " = " . $this->getVarComoString ( $voContrato->tpAutorizacao );
			$sqlConector = ",";
		}

		if ($voContrato->cdAutorizacao != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrCdAutorizacaoContrato . " = " . $this->getVarComoNumero ( $voContrato->cdAutorizacao );
			$sqlConector = ",";
		}

		if ($voContrato->licom != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrInLicomContrato . " = " . $this->getVarComoString ( $voContrato->licom );
			$sqlConector = ",";
		}

		if ($voContrato->dtVigenciaInicial != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrDtVigenciaInicialContrato . " = " . $this->getDataSQL ( $voContrato->dtVigenciaInicial );
			$sqlConector = ",";
		}
		if ($voContrato->dtVigenciaFinal != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrDtVigenciaFinalContrato . " = " . $this->getDataSQL ( $voContrato->dtVigenciaFinal );
			$sqlConector = ",";
		}
		if ($voContrato->dtAssinatura != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrDtAssinaturaContrato . " = " . $this->getDataSQL ( $voContrato->dtAssinatura );
			$sqlConector = ",";
		}
		if ($voContrato->dtPublicacao != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrDtPublicacaoContrato . " = " . $this->getDataSQL ( $voContrato->dtPublicacao );
			$sqlConector = ",";
		}
		if ($voContrato->vlMensal != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrVlMensalContrato . " = " . $this->getDecimalSQL ( $voContrato->vlMensal );
			$sqlConector = ",";
		}
		if ($voContrato->vlGlobal != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrVlGlobalContrato . " = " . $this->getDecimalSQL ( $voContrato->vlGlobal );
			$sqlConector = ",";
		}
		if ($voContrato->situacao != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrCdSituacaoContrato . " = " . $this->getVarComoString ( $voContrato->situacao );
			$sqlConector = ",";
		}
		if ($voContrato->dtProposta != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrDtProposta . " = " . $this->getDataSQL ( $voContrato->dtProposta );
			$sqlConector = ",";
		}
		if ($voContrato->linkDoc != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrLinkDoc . " = " . $this->getVarComoString ( $voContrato->linkDoc );
			$sqlConector = ",";
		}
		if ($voContrato->linkMinutaDoc != null) {
			$retorno .= $sqlConector . vocontrato::$nmAtrLinkMinutaDoc . " = " . $this->getVarComoString ( $voContrato->linkMinutaDoc );
			$sqlConector = ",";
		}
		$retorno = $retorno . $sqlConector . $voContrato->getSQLValuesUpdate ();

		return $retorno;
	}

	/**
	 * FUNCOES DE IMPORTACAO EXCLUSIVA
	 */
	function incluirContratoImport($tipo, $linha, $numLinhaPlanilha=null) {
		$voContrato = new vocontrato ();

		$atributosInsert = $voContrato->getTodosAtributos ();
		/*
		 * $arrayAtribRemover = array(
		 * vocontrato::$nmAtrSqContrato,
		 * vocontrato::$nmAtrDhInclusao,
		 * vocontrato::$nmAtrDhUltAlteracao,
		 * vocontrato::$nmAtrDataPublicacaoContrato,
		 * vocontrato::$nmAtrInImportacaoContrato
		 * );
		 */

		$arrayAtribRemover = array (
				vocontrato::$nmAtrSqContrato,
				vocontrato::$nmAtrDhInclusao,
				vocontrato::$nmAtrDhUltAlteracao
		);

		$atributosInsert = removeColecaoAtributos ( $atributosInsert, $arrayAtribRemover );
		$atributosInsert = getColecaoEntreSeparador ( $atributosInsert, "," );

		try {
			$voContrato = $this->getVOImportacaoPlanilha ( $tipo, $linha );
				
			$query = " INSERT INTO " . $voContrato->getNmTabela () . " \n";
			$query .= " (";
			$query .= $atributosInsert;
			$query .= ") ";
				
			$query .= " \nVALUES(";
			$query .= $this->getAtributosInsertImportacaoPlanilha ($voContrato );
			$query .= ")";
				
			//echoo("query inclusao contrato: $query");
				
			// tenta incluir
			$retorno = $this->cDb->atualizarImportacao ( $query );
			
			if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO){
				echoo("Contrato incluído com sucesso: " . $voContrato->getCodigoContratoFormatado());
			}
		} catch ( excecaoFimImportacaoContrato $exFim ) {
			echo "<BR> FIM DA IMPORTAÇÃO. <BR>";
			throw $exFim;
		} catch ( Exception $e ) {
			
			if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO_ERRO){
				echo "<BR> ERRO INCLUSAO. LINHA $numLinhaPlanilha <BR>";
				$msgErro = "CONTRATO:: "
						. $voContrato->getCodigoContratoFormatado()
						. "|" .$voContrato->sqEspecie
						. "º " . dominioEspeciesContrato::getDescricaoStatic($voContrato->cdEspecie);
	
						$msgErro .= "<BR>" . $e->getMessage ();
				echo "<BR>" . $msgErro . "<BR>";
			}
					//$query = "";
					// se der pau, vai alterar
					// $retorno = $this->cDb->atualizarImportacao($query);
		}

		return $retorno;
	}
	/*private function getAtributosInsertImportacaoPlanilha($tipo, $linha, $voContrato = null) {
		$voContrato = $this->getVOImportacaoPlanilha ( $tipo, $linha );
		$retorno = $this->getSQLValuesInsert ( $voContrato );

		return $retorno;
		}*/
	private function getAtributosInsertImportacaoPlanilha($voContrato) {
		$retorno = $this->getSQLValuesInsert ( $voContrato );
		return $retorno;
	}
	function getCdAutorizacao($tipoAutorizacao) {
		include_once (caminho_funcoes . "contrato/dominioAutorizacao.php");

		$retorno = dominioAutorizacao::$CD_AUTORIZ_NENHUM;

		$isPGE = mb_stripos ( $tipoAutorizacao, "pge" ) !== false;
		$isSAD = mb_stripos ( $tipoAutorizacao, "sad" ) !== false;
		$isGOV = mb_stripos ( $tipoAutorizacao, "gov" ) !== false;

		if ($isPGE && $isSAD && $isGOV)
			$retorno = dominioAutorizacao::$CD_AUTORIZ_SAD_PGE_GOV;
			else if ($isPGE && $isSAD)
				$retorno = dominioAutorizacao::$CD_AUTORIZ_SAD_PGE;
				else if ($isPGE)
					$retorno = dominioAutorizacao::$CD_AUTORIZ_PGE;
					else if ($isSAD)
						$retorno = dominioAutorizacao::$CD_AUTORIZ_SAD;

						return $retorno;
	}

	/**
	 * pega excepcionalmente a informacao do tipo do contrato na especie
	 * alguns contratos foram inseridos incorretamente ate 2019. CAso daqueles que tem Cessao de Uso na especie, quando deveriam estar no tipo
	 *
	 * @param unknown $paramEspecie
	 * @param unknown $objeto
	 * @return NULL|mixed
	 */
	function getTpContratoNaEspecie($paramEspecie, $objeto=null) {
		return $this->getCdEspecieContrato($paramEspecie, $objeto, true);
	}
	// recebe tambem o objeto porque as vezes a informacao esta nele
	// quando a informacao nao estiver na especie, ele tenta no objeto
	function getCdEspecieContrato($paramEspecie, $objeto=null, $isTpContrato = false) {
		$retorno = null;
		//se for true, a consulta da especie vai ser usada para determinar o tpcontrato
		//pq alguns contratos foram inseridos incorretamente ate 2019. CAso daqueles que tem Cessao de Uso na especie, quando deveriam estar no tipo
		if(!$isTpContrato){
			/*$dominioEspecies = new dominioEspeciesContrato ();
			 $colecao = $dominioEspecies->getDominioImportacaoPlanilha ();*/
			$colecao = dominioEspeciesContrato::getColecaoImportacaoPlanilha();
		}else{
			$colecao = dominioTipoContrato::getColecaoImportacaoPlanilhaExcecao();
		}

		$tamanho = count ( $colecao );
		// echo $tamanho . "<br>";
		// var_dump($colecao) . "<br>";
		$chaves = array_keys ( $colecao );
		// echo "<br>especie:$paramEspecie";

		for($i = 0; $i < $tamanho; $i ++) {
			$chave = $chaves [$i];
			$especie = $colecao [$chave];
				
			$mystring = utf8_encode ( $especie );
			// $mystring = $especie;
			// echo "<br>$mystring X $paramEspecie";
				
			// verifica se eh o tipo da especie em questao
			if (existeStr1NaStr2ComSeparador ( $paramEspecie, $mystring, false )) {
				$retorno = $chave;
				break;
			}
		}

		if ($retorno != null) {
			if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO){
				echo "<br>EXISTE<br>";
			}
		} else {
			// se nao conseguiu na especie, tenta no objeto
			if ($objeto != null) {
				$retorno = $this->getCdEspecieContrato ( $objeto, null,$isTpContrato);
			} else {
				if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO){
					echo "<br>NAO EXISTE $paramEspecie <br>";
				}
			}
		}

		// $mystring = utf8_decode($param);
		return $retorno;
	}
	
	function getDataLinhaImportacao($param) {
		$datahtml = null;
		if (isAtributoValido ( $param )) {
			$param = str_replace ( " ", "", $param );
			//echoo(" valor vindo planilha: $param ");
				
			if ($param != static::$CD_CONSTANTE_PENDENTE_IMPORTACAO && $param != static::$CD_CONSTANTE_DATA_INVALIDA) {
				
				$datahtml = substr ( $param, 3, 2 ) . "/" . substr ( $param, 0, 2 ) . "/" . (substr ( $param, 6, 4 ) + 2000);
				$isdatavalida = checkDataValida($datahtml);
				//echoo(" tentativa1: $datahtml ");
	
				if(!$isdatavalida){
					// trecho que serve para data de publicacao quando vier com o extrato de publicacao nao formatado em data
					//ou ultima tentativa de recuperar a data mal formatada
					$datahtml = getDataHtmlDoFinaldoTexto($param);
					$isdatavalida = checkDataValida($datahtml);
					//echoo(" tentativa2: $datahtml");
					if(!$isdatavalida){
						$datahtml = null;
					}
						
				}
			}
		}
	
		//echoo(" retorno: $datahtml ");
		return $datahtml;
	}	
	
	function getNumeroLinhaImportacao($param) {
		$retorno = "null";
		if ($param != null)
			$retorno = substr ( $param, 0, 3 );

			return $retorno;
	}
	function getAnoLinhaImportacao($param) {
		$retorno = "null";
		if ($param != null)
			$retorno = substr ( $param, 4, 2 ) + 2000;

			return $retorno;
	}

	/*function getDataLinhaImportacao($param) {
		$retorno = "null";
		$isDataExcel = getPosicaoPalavraNaString ( $param, " " ) === false;
		echo "XX$paramXX";

		//if (isatrib$param != null && $param != "") {
		if (isAtributoValido($param) && $param != static::$CD_CONSTANTE_PENDENTE_IMPORTACAO && $param != '0000-00-00') {
			if ($isDataExcel) {
				// $retorno = "'" . substr($param,3,2) . "/" . substr($param,0,2) . "/" . (substr($param,6,4) + 2000). "'";
				$retorno = substr ( $param, 3, 2 ) . "/" . substr ( $param, 0, 2 ) . "/" . (substr ( $param, 6, 4 ) + 2000);
			}else{
				$param = str_replace(" ", "", $param);
				$retorno = $param;
				//eh string normal
			}
		}

		//imprimeLinhaHTML ( "parametro:$param" );
		// imprimeLinhaHTML ( "resultado:$retorno" );

		return $retorno;
	}*/
	
	function getDecimalLinhaImportacao($param) {
		$retorno = "null";

		$valor = str_replace ( ",", "", "$param" );
		$valor = str_replace ( " ", "", "$valor" );
		$valor = str_replace ( "-", "", "$valor" );

		// echo "<br>decimal apos conversao:" . $valor;
		if (isNumero ( $valor )) {
			$retorno = getMoedaMascaraImportacao ( $param );
			// echo "É NÚMERO! <BR>";
		}
		// else
		// echo "NÃO É NÚMERO! <BR>";

		return $retorno;
	}
	function atualizarPessoasContrato() {
		echoo("Atualizando contratadas");

		$query = "SELECT ";
		$query .= vopessoa::getNmTabela () . "." . vopessoa::$nmAtrDoc;
		$query .= "," . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrCd;
		$query .= "\n FROM " . vopessoa::getNmTabela ();
		$query .= "\n WHERE " . vopessoa::$nmAtrDoc . " IS NOT NULL";
		$query .= "\n GROUP BY " . vopessoa::$nmAtrDoc;
		$colecaoDocs = $this->consultarEntidade ( $query, false );
		$tam = count ( $colecaoDocs );
		echoo("Número de documentos registrados:" . $tam);

		$arrayDocs = array ();
		for($i = 0; $i < $tam; $i ++) {
				
			$doc = $colecaoDocs [$i] [vopessoa::$nmAtrDoc];
			$cdPessoa = $colecaoDocs [$i] [vopessoa::$nmAtrCd];
				
			$doc = new documentoPessoa ( $doc );
			$arrayDocs [$doc->getNumDoc ()] = $cdPessoa;
		}

		//var_dump($arrayDocs);

		$query = "SELECT * ";
		/*$query .= vocontrato::getNmTabela () . "." . vocontrato::$nmAtrSqContrato;
		 $query .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrAnoContrato;
		 $query .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrCdContrato;
		 $query .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrTipoContrato;
		 $query .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrCdEspecieContrato;
		 $query .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrSqEspecieContrato;
		 $query .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrDocContratadaContrato;
		 $query .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrCdPessoaContratada;
		 $query .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrDhUltAlteracao;*/
		$query .= "\n FROM " . vocontrato::getNmTabela ();
		$query .= "\n WHERE " . vocontrato::$nmAtrDocContratadaContrato . " IS NOT NULL";
		$query .= "\n AND " . vocontrato::$nmAtrCdPessoaContratada . " IS NULL";
		$query .= "\n ORDER BY " . vocontrato::$nmAtrSqContrato;
		// $query.= " AND " .vocontrato::$nmAtrSqContrato . " = " . "1";

		// echo $query;

		$colecaoContratos = $this->consultarEntidade ( $query, false );

		$tam = count ( $colecaoContratos );

		$qtdRegistros = 0;

		if(!isColecaoVazia($colecaoContratos)){
			for($i = 0; $i < $tam; $i ++) {
				$voContrato = new voContrato ();
				$voContrato->getDadosBanco ( $colecaoContratos [$i] );
				$docContrato = new documentoPessoa ( $voContrato->docContratada );
				$doc = $docContrato->getNumDoc ();

				if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO){
					echoo("<br>__________");
					echo "<br> Documento: " . $doc;
					echo "<br> Cd.Pessoa: " . $arrayDocs [$doc];
				}
				/*
				 * $key = array_search($doc, $arrayDocs);
				 * $key = in_array($doc, $arrayDocs);
				 *
				 * echo $key;
				 */

				$cdPessoa = $arrayDocs [$doc];
				if (($voContrato->cdPessoaContratada == null || $voContrato->cdPessoaContratada == "" || $voContrato->cdPessoaContratada == 0)
						&& $cdPessoa != null && $cdPessoa != "") {
							$voContrato->cdPessoaContratada = $cdPessoa;
							$voContrato->cdUsuarioUltAlteracao = constantes::$CD_USUARIO_BATCH;
							$this->alterarPorCima ( $voContrato );
								
							$qtdRegistros ++;
													
						}
						
				if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO){
					echoo("CONTRATO:: " . $voContrato->getCodigoContratoFormatado() . "|" .$voContrato->sqEspecie . " " . dominioEspeciesContrato::getDescricaoStatic($voContrato->cdEspecie));
				}
			}
		}

		//echoo("Removendo caracteres especiais...");
		//$this->removerCaracterEspecial();

		echo "<br>quantidade registros alterados:" . $qtdRegistros;
	}
	function atualizarNomesCaracteresEspeciais() {
		// a ideia aqui eh colocar a atualizacao do objeto e nome contratada
		// pra retirar os caracterees especiais
		; // $retorno = $this->cDb->atualizar($query);
	}
	/**
	 * monta a matriz de sequencial para o contrato especie passado como parametro
	 * para tanto eh necessario que os registros sejam listados por ordem de contrato
	 * @param unknown $matriz
	 * @param unknown $vocontrato
	 * @return number[]
	 */
	static function manterMatrizTpDocContratoSqEspecie($vocontrato) {
		$vocontratoTempMater = clone $vocontrato;
		/*$vocontratoTempMater->anoContrato = $vocontrato->anoContrato ;
		 $vocontratoTempMater->cdContrato = $vocontrato->cdContrato ;
		 $vocontratoTempMater->tipo = $vocontrato->tipo ;*/
		$vocontratoTempMater->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$vocontratoTempMater->sqEspecie = 1;
		// se o contrato for valido, mas nao tiver sequencial, vamos dar um sequencial
		//echoo("contrato eh nulo? " . $vocontrato->anoContrato . " a especie do contrato eh " . $vocontrato->sqEspecie);

		if ($vocontrato != null && $vocontrato->sqEspecie == null) {
				
			$chaveContratoEspecie = $vocontrato->getValorChaveLogica ();
			$chaveContratoEspecieMater = $vocontrato->getValorChaveMater ();
			$chaveContratoMater = $vocontratoTempMater->getValorChaveMater ();
			$isMesmoContrato = $chaveContratoEspecieMater == $chaveContratoMater;
			//verifica se eh do mesmo contrato, se nao, zera
			if (vocontrato::$matrizImportacao != null && $isMesmoContrato) {
				//$arrayChaves = array_keys ( vocontrato::$matrizImportacao );
				if (array_key_exists ( $chaveContratoEspecie, vocontrato::$matrizImportacao )) {
					$sqEspecie = vocontrato::$matrizImportacao [$chaveContratoEspecie];
					vocontrato::$matrizImportacao [$chaveContratoEspecie] = $sqEspecie + 1;
					//echoo("alterou o sequencial $chaveContratoEspecie para " . vocontrato::$matrizImportacao [$chaveContratoEspecie]);
				}else{
					//para o caso da especie nao existir na matriz
					vocontrato::$matrizImportacao[$chaveContratoEspecie] = 1;
					//echoo("incluiu para $chaveContratoEspecie sq 1");
				}
			} else {
				vocontrato::$matrizImportacao = array($chaveContratoEspecie => 1);
				//echoo("criou nova matriz para $chaveContratoEspecie");
			}
		}

		//return $matriz;
	}

	function getVOImportacaoPlanilha($tipo, $linha) {
		$tpContrato = $linha ["A"];
		
		if ($tpContrato == trim ( static::$CD_CONSTANTE_FIM_IMPORTACAO )) {
			throw new excecaoFimImportacaoContrato ();
		}

		$numero = $linha ["B"];
		$ano = $linha ["B"];
		$especie = $linha ["D"];
		//pega antes para validacoes anteriores
		if ($tipo != "V") {
			// contrato/profisco
			$nomeContratada = $linha ["O"];
		} else {
			// convenio
			$nomeContratada = $linha ["M"];
		}

		$dtAlteracao = $linha ["E"];
		$objeto = $linha ["F"];
		
		$cancelado = "CANCELADO";
		$isCancelado = existeStr1NaStr2($cancelado, $especie) || $nomeContratada == $cancelado || $objeto == $cancelado;		
		if($isCancelado){
			throw new excecaoGenerica("Contrato CANCELADO.");
		}
		
		$gestorPessoa = $linha ["G"];
		$linkDoc = $linha [vocontrato::$nmAtrLinkDoc];
		$linkMinutaDoc = $linha [vocontrato::$nmAtrLinkMinutaDoc];
		
		if ($tipo != "V") {
			// contrato/profisco
			$gestor = $linha ["H"];
			
			$valorGlobal = $linha ["J"];
			$valorMensal = $linha ["I"];
			$processoLic = $linha ["K"];
			$modalidadeLic = $linha ["L"];
			$dtAssinatura = $linha ["M"];
			$dataPublic = $linha ["N"];
			//$nomeContratada = $linha ["O"];
			$docContratada = $linha ["P"];
			
			$dtVigenciaInicio = $linha ["Q"];
			$dtVigenciaFim = $linha ["R"];
			$sqEmpenho = $linha ["T"];
			// $sqEmpenho = $linha ["S"];
			$tpAutorizacao = $linha ["U"];
			// $tpAutorizacao = $linha ["T"];
			// $inLicom = $linha ["V"];
			$obs = $linha ["V"];
		} else {
			// convenio
			$gestor = null;
			
			$valorGlobal = $linha ["H"];
			$valorMensal = null;
			// $processoLic = $linha ["H"];
			$modalidadeLic = $linha ["J"];
			$dtAssinatura = $linha ["K"];
			$dataPublic = $linha ["L"];
			//$nomeContratada = $linha ["M"];
			$docContratada = $linha ["N"];
			
			$dtVigenciaInicio = $linha ["O"];
			$dtVigenciaFim = $linha ["P"];
			
			if ($tipo == "V")
				$sqEmpenho = $linha ["R"];
			else
				$sqEmpenho = $linha ["Q"];
			
			$tpAutorizacao = null;
			// $inLicom = $linha ["S"];
			$obs = $linha ["S"];
		}
		
		// recupera o sequencial da especie (aditivo, apostilamento) quando existir
		$sqEspecie = substr ( $especie, 0, 3 );
		$indiceEspecie = getIndicePosteriorAoUltimoNumeroAPartirDoComeco ( $sqEspecie );
		$sqEspecie = substr ( $sqEspecie, 0, $indiceEspecie );
		// recuperar a especie propriamente dita
		// $cdEspecie = $this->getCdEspecieContrato ( $especie, $objeto );
		$cdEspecie = $this->getCdEspecieContrato ( $especie );
		
		// pega tp contrato que esteja na especie apenas excepcionalmente
		// casos de contratos que foram cadastrados incorretamente. Em 2020 isso nao deve mais ocorrer
		$tpContratoEspecie = $this->getTpContratoNaEspecie ( $especie );
		// se tpContratoEspecie nao for nulo, quer dizer que o tipo esta na especie e ele deve prevalecer
		if ($tpContratoEspecie != null) {
			$tipo = $tpContratoEspecie;
		}
		
		$situacao = "null";
		$dtProposta = "null";
		$cdGestor = "null";
		$cdPessoaGestor = "null";
		$cdPessoaContratada = "null";
		
		$importacao = "S";
		// trata o valor do inlicom
		/*
		 * if ($inLicom == "OK")
		 * $inLicom = "S";
		 * else
		 * $inLicom = "N";
		 */
		
		$retorno = new vocontrato ();
		/*
		 * $retorno->cdContrato = $numero;
		 * $retorno->anoContrato = $ano;
		 */
		// corrige os tipos de dados
		$retorno->anoContrato = $this->getAnoLinhaImportacao ( $ano );
		$retorno->cdContrato = $this->getNumeroLinhaImportacao ( $numero );
		$retorno->tipo = $tipo;
		$retorno->especie = $especie;
		$retorno->cdEspecie = $cdEspecie;
		$retorno->linkDoc = getDocLinkMascaraImportacao ( $linkDoc );
		$retorno->linkMinutaDoc = getDocLinkMascaraImportacao ( $linkMinutaDoc );
		
		if ($sqEspecie != null) {
			$retorno->sqEspecie = $sqEspecie;
			// echoo("Encontrou sqEspecie $sqEspecie");
		} else {
			$retorno->sqEspecie = null;
			static::manterMatrizTpDocContratoSqEspecie ( $retorno );
			$retorno->sqEspecie = vocontrato::$matrizImportacao [$retorno->getValorChaveLogica ()];
			// echoo("Incluindo sequencial " . $retorno->sqEspecie . " para a especie " . dominioEspeciesContrato::getDescricao($cdEspecie));
		}
		
		// $retorno->objeto = $objeto;
		$retorno->objeto = getStringImportacaoCaracterEspecial ( $objeto );
		$retorno->nmGestorPessoa = $gestorPessoa;
		// $retorno->gestor = $gestor;
		$retorno->gestor = getStringImportacaoCaracterEspecial ( $gestor );
		
		$retorno->vlGlobal = $valorGlobal;
		$retorno->vlMensal = $valorMensal;
		if ($processoLic != null) {
			// $retorno->procLic = $processoLic;
			$retorno->procLic = getStringImportacaoCaracterEspecial ( $processoLic );
			
			try {
				$arrayProcLic = getArrayFormatadoLinhaImportacaoPorSeparador ( $processoLic );
				$retorno->cdProcLic = $arrayProcLic [0];
				$retorno->anoProcLic = $arrayProcLic [1];
				
				// pega o cdmodalidade no campo modalidade da planilha
				$retorno->cdModalidadeLic = dominioModalidadeProcLicitatorio::getChaveDeUmaStringPorExtenso ( $modalidadeLic, dominioModalidadeProcLicitatorio::getColecaoImportacaoPlanilha () );
				if ($retorno->cdModalidadeLic == null) {
					// se nao achar, busca no campo proclic
					$retorno->cdModalidadeLic = dominioModalidadeProcLicitatorio::getChaveDeUmaStringPorColecaoSimples ( $processoLic, dominioModalidadeProcLicitatorio::getColecaoImportacaoPlanilhaPorCodigoSimples () );
				}
			} catch ( excecaoNumProcLicImportacaoInvalido $exProcLic ) {
				if (static::$FLAG_PRINTAR_LOG_IMPORTACAO) {
					echoo ( $exProcLic->getMessage () );
				}
			} catch ( Exception $ex ) {
				if (static::$FLAG_PRINTAR_LOG_IMPORTACAO) {
					echoo ( $ex->getMessage () );
				}
			}
		}
		
		$retorno->modalidade = $modalidadeLic;
		$retorno->contratada = getStringImportacaoCaracterEspecial ( $nomeContratada );
		
		$documento = new documentoPessoa ( $docContratada );
		$retorno->docContratada = $documento->getNumDoc ();
		
		$retorno->empenho = $sqEmpenho;
		$retorno->tpAutorizacao = $tpAutorizacao;
		$retorno->licom = $inLicom;
		$retorno->obs = $obs;
		$retorno->importacao = $importacao;
		
		// corrige os tipos de dados
		$retorno->cdAutorizacao = $this->getCdAutorizacao ( $retorno->tpAutorizacao );
		// echoo("valor global:" . $valorGlobal);
		// echo "<br> VALOR GLOBAL: " . $retorno->vlGlobal;
		// echo "<br> VALOR vlMensal: " . $retorno->vlMensal;
		$retorno->vlGlobal = $this->getDecimalLinhaImportacao ( $retorno->vlGlobal );
		$retorno->vlMensal = $this->getDecimalLinhaImportacao ( $retorno->vlMensal );
		// echo "<br> VALOR GLOBAL: " . $retorno->vlGlobal;
		
		$retorno->dtAssinatura = $this->getDataLinhaImportacao ( $dtAssinatura );
		$retorno->dtPublicacao = $this->getDataLinhaImportacao ( $dataPublic );
		$retorno->dtVigenciaInicial = $this->getDataLinhaImportacao ( $dtVigenciaInicio );
		$retorno->dtVigenciaFinal = $this->getDataLinhaImportacao ( $dtVigenciaFim );
		
		/*
		 * $retorno->cdUsuarioInclusao = "null";
		 * $retorno->cdUsuarioUltAlteracao = "null";
		 */
		
		$retorno->cdUsuarioInclusao = constantes::$CD_USUARIO_BATCH;
		$retorno->cdUsuarioUltAlteracao = constantes::$CD_USUARIO_BATCH;
		
		/*
		 * echo "<br>data assinatura: " . $retorno->dtAssinatura;
		 * echo "<br>data dtVigenciaInicial: " . $retorno->dtVigenciaInicial;
		 * echo "<br>data dtVigenciaFinal: " . $retorno->dtVigenciaFinal;
		 */
		
		return $retorno;
	}

	function iniciarTabelaContrato(){
		$this->atualizarEntidade("drop table IF EXISTS contrato;");
		$this->atualizarEntidade(static::getSQLLimparTabelaContrato());
	}

	function removerCaracterEspecial(){
		echoo("Removendo caracteres especiais.");
		$this->atualizarEntidade(static::getSQLRemoveCaracteresEspeciais());
	}

	static function getSQLLimparTabelaContrato(){
		$sql =
		"CREATE TABLE contrato (
		    sq INT NOT NULL AUTO_INCREMENT,
		    ct_exercicio INT NOT NULL,
		    ct_numero INT NOT NULL,
		    ct_tipo char(1) NOT NULL,
			ct_especie VARCHAR(50),
		    ct_sq_especie INT DEFAULT 1 NOT NULL,
		    ct_cd_especie CHAR(2) NOT NULL,
			ct_cd_situacao CHAR(2),
		    ct_objeto LONGTEXT,
		    ct_gestor_pessoa VARCHAR(300) ,
		    pe_cd_resp INT ,
		    ct_gestor VARCHAR(200) ,
		    gt_cd INT ,
			ct_processo_lic VARCHAR(300),
		    ct_cd_processo_lic INT,
		    ct_ano_processo_lic INT,
		    ct_cdmod_processo_lic CHAR(2),
			ct_modalidade_lic VARCHAR(300),
			ct_data_public VARCHAR(300),
		    ct_dt_public DATE,
		    ct_dt_assinatura DATE,
		    ct_dt_vigencia_inicio DATE,
		    ct_dt_vigencia_fim DATE,
			ct_dt_proposta DATE NULL,
		    ct_contratada VARCHAR(300),
		    pe_cd_contratada INT,
		    ct_doc_contratada VARCHAR(30),
		    ct_num_empenho VARCHAR(50),
		    ct_tp_autorizacao VARCHAR(15),
		    ct_cd_autorizacao INT,
		    ct_in_licom CHAR(1),
			ct_in_importacao CHAR(1) DEFAULT 'N',
		    ct_observacao LONGTEXT,
		    ct_valor_global DECIMAL (14,4),
		    ct_valor_mensal DECIMAL (14,4),
		    ct_doc_link TEXT NULL,
			ct_doc_minuta TEXT NULL,
		    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
		    cd_usuario_incl INT,
		    cd_usuario_ultalt INT,

		    CONSTRAINT pk PRIMARY KEY (sq, ct_exercicio, ct_numero, ct_tipo),
		    UNIQUE KEY chave_logica_contrato (ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie),
		    CONSTRAINT fk_ct_gestor FOREIGN KEY ( gt_cd ) REFERENCES gestor (gt_cd)
			ON DELETE RESTRICT
			ON UPDATE RESTRICT,
			CONSTRAINT fk_ct_pessoa_resp FOREIGN KEY ( pe_cd_resp ) REFERENCES pessoa (pe_cd)
			ON DELETE RESTRICT
			ON UPDATE RESTRICT,
			CONSTRAINT fk_ct_pessoa_contratada FOREIGN KEY ( pe_cd_contratada ) REFERENCES pessoa (pe_cd)
			ON DELETE RESTRICT
			ON UPDATE RESTRICT
		);";
		return $sql;
	}

	static function getSQLRemoveCaracteresEspeciais(){
		$sql =
		//"quotestr(
		"UPDATE contrato SET
			ct_contratada = replace(replace(replace(replace(ct_contratada,'“','\"'),'”','\"'),'–','-'), '?','-'),
			ct_objeto = replace(replace(replace(replace(ct_objeto,'“','\"'),'”','\"'),'–','-'), '?','-'),
			ct_gestor = replace(replace(replace(replace(ct_gestor,'“','\"'),'”','\"'),'–','-'), '?','-'),
			ct_processo_lic = replace(replace(replace(replace(ct_processo_lic,'“','\"'),'”','\"'),'–','-'), '?','-')
			"
				;

				return $sql;

	}
	
	/**
	 * consulta funcao estatisticas da tela de consulta de contrato
	 * {@inheritDoc}
	 * @see dbprocesso::consultarTelaConsulta()
	 */
	function consultarEstatisticas($ano){
		$retorno = "";
		if($ano != null){
			$vo = new vocontrato();
			$nmTabela = $vo->getNmTabelaEntidade(false);
			$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic(false);
			//$nmTabelaDemandaSolicCompra = voDemandaPL::getNmTabelaStatic(false);
				
			$query .= " SELECT ";
			$query .= vocontrato::$nmAtrTipoContrato;
			$query .= ",COUNT(" . vocontrato::$nmAtrTipoContrato;
			$query .= ") AS " . vocontrato::$nmAtrNumQuantidade;
			$query .= ") FROM " . $nmTabela;
			/*$query .= "\n left JOIN " . $nmTabelaContratoInfo;
			$query .= "\n ON ";
			$query .= $nmTabelaContratoInfo . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
			$query .= " AND " . $nmTabelaContratoInfo . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . vocontrato::$nmAtrCdContrato;
			$query .= " AND " . $nmTabelaContratoInfo . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
			*/
			$query .= " WHERE " 
					. vocontrato::$nmAtrAnoContrato ." = $ano ";
					//. " AND " . vocontrato::$nmAtrAnoContrato ." = $ano ";
			
			static::printarSQL($query);
		
			$retorno = parent::consultarEntidade ( $query, false);
		}
		
		return $retorno;
	}
	
	
}
?>