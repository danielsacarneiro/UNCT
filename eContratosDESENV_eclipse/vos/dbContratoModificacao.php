<?php
include_once (caminho_lib . "dbprocesso.obj.php");
// include_once(caminho_util."bibliotecaDataHora.php");
include_once 'dbContratoModificacao.php';
class dbContratoModificacao extends dbprocesso {
	static $FLAG_PRINTAR_SQL = false;
	
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabContratoMATER = filtroManterContratoModificacao::$NmTabContratoMATER;
	
		$colecaoAtributoCoalesceNmPessoa = array (
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato
		);
	
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtAssinaturaContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtPublicacaoContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaInicialContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaFinalContrato,
				$nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrInEscopo,
				$nmTabContratoMATER . "." . vocontrato::$nmAtrDtVigenciaInicialContrato,
				$nmTabContratoMATER . "." . vocontrato::$nmAtrDtVigenciaFinalContrato,
				getSQLCOALESCE ( $colecaoAtributoCoalesceNmPessoa, vopessoa::$nmAtrNome )
		);
	
		$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrAnoContrato;
		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdContrato;
		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrTipoContrato;
		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdEspecieContrato;
		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrSqEspecieContrato;
		 
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= "\n\n (SELECT * ";
		$queryJoin .= " FROM " . $nmTabelaContratoInfo;
		$queryJoin .= " WHERE ";
		$queryJoin .= voContratoInfo::$nmAtrInDesativado . "='N'";
		$queryJoin .= "\n) " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . voContratoModificacao::$nmAtrTipoContrato;
		
		
		/*$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;*/		
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
	
		// SERVE PARA PEGAR O VALOR INICIAL DO CONTRATO
		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
		$groupbyinterno = vocontrato::$nmAtrAnoContrato . "," . vocontrato::$nmAtrCdContrato . "," . vocontrato::$nmAtrTipoContrato . "," . vocontrato::$nmAtrCdEspecieContrato . "," . vocontrato::$nmAtrSqEspecieContrato . "," . vocontrato::$nmAtrDtVigenciaInicialContrato . "," . vocontrato::$nmAtrDtVigenciaFinalContrato;
	
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= "\n\n (SELECT $groupbyinterno ";
		$queryJoin .= " FROM " . $nmTabContratoInterna;
		$queryJoin .= " WHERE ";
		$queryJoin .= vocontrato::$nmAtrCdEspecieContrato . "=" . getVarComoString ( dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER );
		$queryJoin .= "\n) " . $nmTabContratoMATER;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrTipoContrato;
	
	
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
	}
	
	/**
	 * metodo tem formato distinto porque exige um UNION com a tabela de contrato para trazer, no minimo, o contrato mater
	 * ainda que nao tenha nenhuma modificacao, o valor do contrato mater serah o valor atual da execucao
	 * {@inheritDoc}
	 * @see dbprocesso::consultarTelaConsulta()
	 */
	function consultarTelaConsulta($vo, $filtro) {
		//mudando o vo para vocontrato porque esta passarah a ser a tabela base da consulta
		$vo = new vocontrato();
		$isHistorico = $filtro->isHistorico;
		$isConsultaPorChave = false;
		$nmTabelaACompararCdUsuario = $vo->getNmTabelaEntidade ($isHistorico);
		
		//define as tabelas
		$nmTabelaMod = voContratoModificacao::getNmTabelaStatic ( false );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabContratoMATER = filtroManterContratoModificacao::$NmTabContratoMATER;
		$nmTabReajustes = filtroManterContratoModificacao::$NmTabReajustes;
		
		//define os atributos
		$colecaoAtributoCoalesceNmPessoa = array (
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato
		);
		
		$arrayColunasRetornadasComum = array (		
				$nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtAssinaturaContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaFinalContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaInicialContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrVlMensalContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrVlGlobalContrato,

				/*$nmTabelaMod . "." . voContratoModificacao::$nmAtrAnoContrato,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrTipoContrato,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrCdContrato,*/
				
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrSq,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrTpModificacao,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrNumPercentual,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrVlModificacaoReferencial,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrVlMensalModAtual,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrVlMensalAtualizado,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrVlMensalAnterior,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrVlGlobalModAtual,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrVlGlobalAtualizado,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrDhUltAlteracao,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrCdUsuarioUltAlteracao,
				
				getSQLCOALESCE ( $colecaoAtributoCoalesceNmPessoa, vopessoa::$nmAtrNome ),
		);		

		$querySelect .= " SELECT * FROM ";
		
		if($filtro->inTrazerMater){			
			$sqlParentesesFinal = ")";
			
			$arrayColunasRetornadasMater = array (
					$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaInicialContrato . " AS " . voContratoModificacao::$nmAtrDtModificacao,
					$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaFinalContrato . " AS " . voContratoModificacao::$nmAtrDtModificacaoFim,					
						
					$nmTabelaContrato . "." . vocontrato::$nmAtrVlMensalContrato . " AS " . filtroManterContratoModificacao::$NmColVlMensalMater,
					$nmTabelaContrato . "." . vocontrato::$nmAtrVlGlobalContrato . " AS " . filtroManterContratoModificacao::$NmColVlGlobalMater,
			);
			
			$arrayColunasRetornadasMater = array_merge($arrayColunasRetornadasComum,$arrayColunasRetornadasMater);
			
			//pega somente os dados do contrato mater
			$atributos = getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadasMater, false );

			$querySelect .= "((SELECT " . $atributos . " FROM $nmTabelaContrato ";
			$querySelect .= "\n LEFT JOIN " . $nmTabelaMod;
			$querySelect .= "\n ON ";
			$querySelect .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrAnoContrato;
			$querySelect .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrCdContrato;
			$querySelect .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrTipoContrato;
			$querySelect .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrCdEspecieContrato;
			$querySelect .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrSqEspecieContrato;
			
			$querySelect .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
			$querySelect .= "\n ON ";
			$querySelect .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;		
			
			$querySelect .= "\n WHERE ";
			$querySelect .= "$nmTabelaContrato." . vocontrato::$nmAtrCdEspecieContrato . " = " . getVarComoString(dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER);
			$querySelect .= "\n AND $nmTabelaContrato." . vocontrato::$nmAtrSqEspecieContrato . " = 1) ";
			//$querySelect .= "\n AND " . CONSTANTES::$CD_CAMPO_SEPARADOR_FILTRO . ")";
			
			
			$querySelect .= "\n\n UNION \n\n";		
		}
		
		//CONSULTA DE FATO DAS MODIFICACOES
		$arrayColunasRetornadasMod = array (
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrDtModificacao,
				$nmTabelaMod . "." . voContratoModificacao::$nmAtrDtModificacaoFim,
				
				$nmTabContratoMATER . "." . vocontrato::$nmAtrVlMensalContrato . " AS " . filtroManterContratoModificacao::$NmColVlMensalMater,
				$nmTabContratoMATER . "." . vocontrato::$nmAtrVlGlobalContrato . " AS " . filtroManterContratoModificacao::$NmColVlGlobalMater,
		);
		$arrayColunasRetornadasMod = array_merge($arrayColunasRetornadasComum,$arrayColunasRetornadasMod);
				
		$atributos = getSQLStringFormatadaColecaoIN ( $arrayColunasRetornadasMod, false );	
		$querySelect .= "(SELECT " . $atributos . " FROM $nmTabelaMod ";
		
		$querySelect .= "\n LEFT JOIN " . $nmTabelaContrato;
		$querySelect .= "\n ON ";
		$querySelect .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrAnoContrato;
		$querySelect .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrCdContrato;
		$querySelect .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrTipoContrato;
		$querySelect .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrCdEspecieContrato;
		$querySelect .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaMod . "." . voContratoModificacao::$nmAtrSqEspecieContrato;
		
		$querySelect .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$querySelect .= "\n ON ";
		$querySelect .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		// SERVE PARA PEGAR O VALOR INICIAL DO CONTRATO
		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
		$groupbyinterno = vocontrato::$nmAtrAnoContrato . "," . vocontrato::$nmAtrCdContrato . "," . vocontrato::$nmAtrTipoContrato . "," . vocontrato::$nmAtrCdEspecieContrato . "," . vocontrato::$nmAtrSqEspecieContrato . "," . vocontrato::$nmAtrVlMensalContrato . "," . vocontrato::$nmAtrVlGlobalContrato;
		
		$querySelect .= "\n LEFT JOIN ";
		$querySelect .= "\n\n (SELECT $groupbyinterno ";
		$querySelect .= " FROM " . $nmTabContratoInterna;
		$querySelect .= " WHERE ";
		$querySelect .= vocontrato::$nmAtrCdEspecieContrato . "=" . getVarComoString ( dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER );
		$querySelect .= "\n) " . $nmTabContratoMATER;
		$querySelect .= "\n ON ";
		$querySelect .= $nmTabelaMod . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrAnoContrato;
		$querySelect .= "\n AND ";
		$querySelect .= $nmTabelaMod . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrCdContrato;
		$querySelect .= "\n AND ";
		$querySelect .= $nmTabelaMod . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrTipoContrato;
		$querySelect .= ")$sqlParentesesFinal " . filtroManterContratoModificacao::$NmTABELAGERAL;
		//$querySelect .= " " . CONSTANTES::$CD_CAMPO_SEPARADOR_FILTRO . ")";
		
		$voContratoModUsu = new voContratoModificacao();
		$nmTabelaACompararCdUsuario = filtroManterContratoModificacao::$NmTABELAGERAL;
		$querySelect.= $this->getQueryJoinUsuarioTabelaAComparar($voContratoModUsu, $nmTabelaACompararCdUsuario, $isHistorico);
		
		$retorno = $this->consultarFiltroSemPaginacao( $filtro, $querySelect);
		return $retorno;
	}
	
	/*function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico;
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabContratoMATER = filtroManterContratoModificacao::$NmTabContratoMATER;
		$nmTabReajustes = filtroManterContratoModificacao::$NmTabReajustes;
	
		$colecaoAtributoCoalesceNmPessoa = array (
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato
		);
	
		$arrayColunasRetornadas = array (
				$nmTabela . ".*",
				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtPublicacaoContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtAssinaturaContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaFinalContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaInicialContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrVlMensalContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrVlGlobalContrato,
				$nmTabContratoMATER . "." . vocontrato::$nmAtrVlMensalContrato . " AS " . filtroManterContratoModificacao::$NmColVlMensalMater,
				$nmTabContratoMATER . "." . vocontrato::$nmAtrVlGlobalContrato . " AS " . filtroManterContratoModificacao::$NmColVlGlobalMater,
				getSQLCOALESCE ( $colecaoAtributoCoalesceNmPessoa, vopessoa::$nmAtrNome ),
				vousuario::$nmAtrName
		);
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabela . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voContratoModificacao::$nmAtrTipoContrato;
		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabela . "." . voContratoModificacao::$nmAtrCdEspecieContrato;
		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabela . "." . voContratoModificacao::$nmAtrSqEspecieContrato;
	
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
	
		// SERVE PARA PEGAR O VALOR INICIAL DO CONTRATO
		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
		$groupbyinterno = vocontrato::$nmAtrAnoContrato . "," . vocontrato::$nmAtrCdContrato . "," . vocontrato::$nmAtrTipoContrato . "," . vocontrato::$nmAtrCdEspecieContrato . "," . vocontrato::$nmAtrSqEspecieContrato . "," . vocontrato::$nmAtrVlMensalContrato . "," . vocontrato::$nmAtrVlGlobalContrato;
	
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= "\n\n (SELECT $groupbyinterno ";
		$queryJoin .= " FROM " . $nmTabContratoInterna;
		$queryJoin .= " WHERE ";
		$queryJoin .= vocontrato::$nmAtrCdEspecieContrato . "=" . getVarComoString ( dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER );
		$queryJoin .= "\n) " . $nmTabContratoMATER;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrAnoContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrCdContrato;
		$queryJoin .= "\n AND ";
		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrTipoContrato;
	
		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
	}*/
	
	function validarInclusao($vo) {
		$vocontratoTemp = $vo->vocontrato;
		/*
		// verifica se o contrato ja foi adicionado a planilha		
		$dbcontrato = new dbcontrato ();
		try {
			$vocontratoTemp = $dbcontrato->consultarPorChaveVO ( $vocontratoTemp, false );
		} catch ( excecaoChaveRegistroInexistente $ex ) {
			throw new excecaoChaveRegistroInexistente ( "Contrato selecionado: " . $vocontratoTemp->getCodigoContratoFormatado ( true ) . " não existe na planilha.", $ex );
		}*/
				
		$vocontratoinfo = voContratoInfo::getVOContratoInfoDeUmVoContrato($vocontratoTemp);
		$vocontratoinfo = $vocontratoinfo->dbprocesso->consultarPorChaveVO($vocontratoinfo);
		//$vocontratoinfo = new voContratoInfo();
		if($vocontratoinfo->inEscopo == null){
			throw new excecaoGenerica("Informação 'contrato por escopo' inexistente. Requer seja informada no cadastro do contrato em 'Informações Adicionais'.");
		}		
	
		/*
		 * $registro = $this->getRegistroDataTermoComDataPosterior($vo);
		 * $termo = new voContratoModificacao();
		 * $termo->getDadosBanco($registro);
		 * $data = $termo->dtModificacao;
		 *
		 * $isOrdemIncorreta = $data != null;
		 * if($isOrdemIncorreta){
		 * $dtProducaoEfeito = $vo->dtModificacao;
		 * $detContrato = $termo->vocontrato->getCodigoContratoFormatado(true);
		 * throw new excecaoGenerica("A data de produção de efeitos do termo em questão ($dtProducaoEfeito) é anterior a do termo já existente ($detContrato): " . getData($data) . ". Verifique a ordem das modificações contratuais.");
		 * }
		 */
	}
	
	/*function alterarTermoRelacionado($voMod){
		//$voMod = new voContratoModificacao();
		$vocontrato = $voMod->vocontrato;
		
		$dbcontrato = new dbcontrato(); 
		$vocontrato = $dbcontrato->consultarContratoPorChave($vocontrato, false);
		
	}*/

	function incluir($vo) {
		$this->validarInclusao ( $vo );
		$this->cDb->retiraAutoCommit ();		
		$retorno = $vo;
		try {
			//recalcula apenas para reajuste
			/*if (dominioTpContratoModificacao::$CD_TIPO_REAJUSTE == $vo->tpModificacao) {
				$this->recalcularReajuste ( $vo );
			}*/
			$retorno = parent::incluir ( $vo );
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $retorno;
	}
	function alterar($vo) {
		throw new excecaoGenerica ( "Operação não permitida." );
		// $this->validarInclusao($vo);
		// return parent::alterar($vo);
	}
	
	function excluirVarios($vo) {
		$chaveVarios = @$_POST["rdb_consulta"];
		echo $chaveVarios;
		
		// $this->validarInclusao($vo);
		// return parent::alterar($vo);
	}
	
	function getRegistroAtualColecaoExecucao($recordSet) {
		if(!isColecaoVazia($recordSet)){
			$indice = sizeof($recordSet)-1;
			$retorno = $recordSet[$indice];
		}
		return $retorno;
	}
	
	
	function consultarExecucaoTermoEspecifico($voContratoComChaveCompleta, $dataVigencia=null) {
		$voContratoMater = clone $voContratoComChaveCompleta;
		$voContratoMater->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$voContratoMater->sqEspecie = 1;
		
		$recordSet = $this->consultarExecucao(clone $voContratoMater, $dataVigencia);		
		$retorno = $this->getRegistroAtualColecaoExecucao($recordSet);
		
		return $retorno;
	}
	
	/**
	 * Funcao que atualiza os valores globais do contrato ajustado tendo como
	 * referencia o valor mensal calculado, multiplicado pelo prazo de efeitos de cada periodo
	 * @param unknown $recordSet
	 */
	function atualizaValorGlobalPorPeriodo(&$recordSet, $voContratoMater = null) {
		$i = 0;
		$vlMensalAnterior = getVarComoDecimal($voContratoMater->vlMensal);
		//$numMesContrato = 12;		
		$numMesContrato = getQtdMesesEntreDatas($voContratoMater->dtVigenciaInicial, $voContratoMater->dtVigenciaFinal);
		//echo $numMesContrato;
		foreach ( $recordSet as $registro) {
			$voTemp = new voContratoModificacao ();
			$voTemp->getDadosBanco ( $registro );
			
			$tpMod = $voTemp->tpModificacao;			
			if($tpMod == dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO){
				//pega o periodo da ultima prorrogacao;
				$numMesContrato = $voTemp->numMesesParaOFimdoPeriodo; 
			}			

			$voContratoModExecucao = $registro [filtroManterContratoModificacao::$NmColVOContratoModReajustado];

			$numMeses = $voTemp->numMesesParaOFimdoPeriodo;
			//echoo($voContratoModExecucao->vlMensalAtual);
			$vlGlobalAtual = ($voContratoModExecucao->vlMensalAtual)*$numMeses;
			$numMesesSobra = $numMesContrato-$numMeses;
			
			if($numMesesSobra>0){
				//echoo ($vlMensalAnterior);
				$vlGlobalAtual = $vlGlobalAtual + ($numMesesSobra*$vlMensalAnterior);
				//echo $vlGlobalAtual;
			}			
			$vlMensalAnterior = $voContratoModExecucao->vlMensalAtual;			
			$voContratoModExecucao->vlGlobalAtual = $vlGlobalAtual;
			
			//echoo("meses:$numMeses, valor mensal:" . $voContratoModExecucao->vlMensalAtual);
			$registro [filtroManterContratoModificacao::$NmColVOContratoModReajustado] = $voContratoModExecucao;
			$recordSet [$i] = $registro;				
			$i++;
		}
	}
	
	/**
	 * verifica a lista apos os calculos do reajuste para replicar nos itens que nao sofreram reajuste
	 * METODO USADO APENAS PARA AJUSTAR OS VALORES QUANDO APENAS HA PRORROGACOES CADASTRADAS - que nao exigem calculos extras
	 * @param unknown $recordSet
	 * @param unknown $voContratoModReajuste
	 * @return number[]
	 */
	function atualizaColecaoRegistros($recordSet) {
		$i = 0;
		$retorno = null;
		$voAtualizadoAnterior = $recordSet [filtroManterContratoModificacao::$NmColVOContratoModReajustado][0];
		foreach ( $recordSet as $registro) {
			$voTemp = new voContratoModificacao();
			$voTemp->getDadosBanco ( $registro );

			$voContratoTemp = new vocontrato();
			$voContratoTemp->getDadosBanco ( $registro );
			
			//echoo("teste1");
			$voAConsiderar = $registro [filtroManterContratoModificacao::$NmColVOContratoModReajustado];
			//var_dump($voAConsiderar);
			if ($voAConsiderar == null){
				
				if($voAtualizadoAnterior == null){
					//MARRETA
					$voTemp->vlMensalAtual = getDecimalSQL($voContratoTemp->vlMensal);
					$voTemp->vlGlobalAtual = getDecimalSQL($voContratoTemp->vlGlobal);
					$voAtualizadoAnterior = clone $voTemp;
				}
				
				if($voTemp->tpModificacao != null && $voTemp->tpModificacao != dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO){
					//para todos os outros nada deve ser feito, pois os calculos ja foram realizados antes
					$voAConsiderar = clone $voTemp;
					throw new excecaoGenerica("Operacao Invalida para registro diferente de prorrogacao: $voTemp->tpModificacao . ");
					//echoo("teste2");
				}else{
					//a prorrogacao eh a copia do vomod anterior
					$voAConsiderar = clone $voAtualizadoAnterior;
					//echoo("teste3");
				}
				$registro [filtroManterContratoModificacao::$NmColVOContratoModReajustado] = $voAConsiderar;
				$recordSet [$i] = $registro;

				//nao inclui o mater
				if($i != 0){
					$retorno[] = $registro;
				}
			}
			/*echoo($voTemp->toString());
			echoo($voAConsiderar->vlMensal);*/
			
			$i++;			
			$voAtualizadoAnterior = clone $voAConsiderar;
		}	
		
		return $retorno;
	}
	
	/**
	 * atualiza o valor global da execucao do contrato baseado nos valores mensais (que podem sofrer alteracao)
	 * @param unknown $registroGenerico
	 * @return unknown
	 */
	function consultarExecucaoValorGlobalReferencial($registroGenerico) {
		$voContratoMater = new vocontrato();
		$voContratoMater->getDadosBanco($registroGenerico);
		$voContratoMater->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$voContratoMater->sqEspecie = 1;
		$voContratoMater = $voContratoMater->dbprocesso->consultarPorChaveVO($voContratoMater, false);		
		
		$voContratoInfo = new voContratoInfo();
		$voContratoInfo->getDadosBanco($registroGenerico);
		
		$recordset = $this->consultarExecucao($voContratoMater);
		//o contrato por escopo nao tem variacao de preco mensal, pois o preco mensal eh unico para toda execucao
		//ainda que sofra acrescimo, o preco mensal de todos os meses tambem sofrem
		//var_dump($recordset);
		$isEscopo = $voContratoInfo->inEscopo == "S";
		//echo ($voContratoMaterInfo->inEscopo);
		if(!$isEscopo){
			//echoo("nao eh por escopo. atualzia global.");
			$this->atualizaValorGlobalPorPeriodo($recordset, $voContratoMater);
		}
		return  $recordset;
	}
	
	function consultarExecucao($voContratoMater, $dataVigencia=null) {
		// consulta os regitros modificacao do contrato SEM REAJUSTE
		$voContratoFiltro = clone $voContratoMater;
		$voContratoFiltro->cdEspecie = null;
		$voContratoFiltro->sqEspecie = null;
		$retorno = null;
		
		$filtro = new filtroManterContratoModificacao ( false );
		$filtro->inTrazerMater = true;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$atributoOrdenacao = voContratoModificacao::$nmAtrDtModificacao . " " . constantes::$CD_ORDEM_CRESCENTE;
		$filtro->cdAtrOrdenacao = $atributoOrdenacao;
		$filtro->vocontrato = $voContratoFiltro;		
		$filtro->dtVigencia = $dataVigencia;
		
		//consulta todos os contratos mods para exibir
		$recordSet = $this->consultarTelaConsulta ( new voContratoModificacao (), $filtro );		

		//agora consulta apenas os registros que alteram o valor do contrato
		$filtro->inTrazerMater = false;
		$filtro->tipoExceto = dominioTpContratoModificacao::getColecaoChavesQueNaoAlteramValorContrato();
		$colecaoReajuste = $this->consultarTelaConsulta ( new voContratoModificacao (), $filtro );
				
		//para todos os registros incluidos em contrato Mod
		//reajustar os valores de acordo com os percentuais dos reajustes retroativos
		if (! isColecaoVazia ( $recordSet )) {
			//echo "nao vazia";
			//recupera o registromodificacao do contrato mater para que seu valor seja considerado na validacao dos reajustes
			$registroMater = $voContratoMater->getRegistroGenerico();
			$voContratoModAtual = new voContratoModificacao ();
			$voContratoModAtual->getDadosBanco ( $registroMater );
			$voContratoModAtual->vocontrato = clone $voContratoMater;
			$voContratoReajustadoAtual = $registroMater [filtroManterContratoModificacao::$NmColVOContratoModReajustado] =  $voContratoModAtual;
			
			//verifica se so tem o contrato mater
			if(sizeof($recordSet) == 1){
				$retorno[] = $registroMater;
			}else{
				//verifica se eh caso de reajustes
				if (! isColecaoVazia ( $colecaoReajuste )) {								
					//recupera os termos que podem alterar vigencia ao contrato que servirao de base para a aplicacao dos reajustes encontrados
					$colecaoTermosQueAlteramVigencia = getColecaoContratosModVigencia($recordSet);	
					//inclui o contrato mater que servira de valor base para os reajustes que seguirao
					$colecaoTermosQueAlteramVigencia = array_merge(array($registroMater), $colecaoTermosQueAlteramVigencia);
					//var_dump($colecaoTermosQueAlteramVigencia);
					
					for($indice=0; $indice < sizeof($colecaoTermosQueAlteramVigencia); $indice++){
						
						$registro = $colecaoTermosQueAlteramVigencia[$indice];
						
						$voContratoAtual = new vocontrato();
						$voContratoAtual->getDadosBanco($registro);
						//echoo("contrato vigencia". $voContratoAtual->toString());
						
						$voContratoModAtual = new voContratoModificacao();
						$voContratoModAtual->getDadosBanco($registro);	
						
						//serve apenas para registrar a prorrogacao
						if(isProrrogacaoNaoRegistrada($voContratoModAtual)){
							$registro[filtroManterContratoModificacao::$NmColVOContratoModReajustado] = $voContratoReajustadoAtual;
							$retorno[] = $registro;
						}
						
						//recupera os reajustes a serem aplicados pra cada termo base da vigencia do contrato
						$colecaoReajustesAplicar = getColecaoReajustesAAplicarContratoMod($voContratoAtual, $colecaoReajuste);
						
						for($indiceReajuste=0; $indiceReajuste < sizeof($colecaoReajustesAplicar); $indiceReajuste++){
							
							$registroReajuste = $colecaoReajustesAplicar[$indiceReajuste];
							$voTempReajuste = new voContratoModificacao ();
							$voTempReajuste->getDadosBanco ( $registroReajuste );
							
							//echoo($voTempReajuste->getValorChaveHTML());
																									
							//recupera o valor mensal atualizado (pois eh o unico usado como referencia)						
							$voTempReajuste->getValorMensalReajustadoAtual($voContratoReajustadoAtual);
							//aplica o percentual						
							$voTempReajuste->setPercentualReajuste ( $voTempReajuste );
							
							//echoo("valor reajustado: " . getMoeda($voTempReajuste->vlMensalAtual));												 
							$voContratoReajustadoAtual = $registroReajuste [filtroManterContratoModificacao::$NmColVOContratoModReajustado] = CLONE $voTempReajuste;						
							$retorno[] = $registroReajuste;
						}						
						
					}				
	
				}else{
					$retorno = $recordSet;
					$retorno = $this->atualizaColecaoRegistros($retorno);
				}
			}
			
			//$this->atualizaColecaoRegistros($recordSet);		
			
		} 
		
		return $retorno;
	}
	
	function getSQLValuesInsert($vo) {
		if ($vo->sq == null || $vo->sq == "") {
			$vo->sq = $this->getProximoSequencialChaveComposta ( voContratoModificacao::$nmAtrSq, $vo );
		}
		
		$retorno = "";
		$retorno .= $this->getVarComoNumero ( $vo->sq ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->vocontrato->anoContrato ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->vocontrato->cdContrato ) . ",";
		$retorno .= $this->getVarComoString ( $vo->vocontrato->tipo ) . ",";
		$retorno .= $this->getVarComoString ( $vo->vocontrato->cdEspecie ) . ",";
		$retorno .= $this->getVarComoNumero ( $vo->vocontrato->sqEspecie ) . ",";
		
		$retorno .= $this->getVarComoNumero ( $vo->tpModificacao ) . ",";
		$retorno .= $this->getVarComoData ( $vo->dtModificacao ) . ",";
		$retorno .= $this->getVarComoData ( $vo->dtModificacaoFim ) . ",";
		$retorno .= $this->getVarComoDecimal ( $vo->vlModificacaoReferencial ) . ",";
		$retorno .= $this->getVarComoDecimal ( $vo->vlModificacaoReal ) . ",";
		$retorno .= $this->getVarComoDecimal ( $vo->vlModificacaoAoContrato ) . ",";
		
		$retorno .= $this->getVarComoDecimal ( $vo->vlMensalAtual ) . ",";
		$retorno .= $this->getVarComoDecimal ( $vo->vlGlobalAtual ) . ",";
		$retorno .= $this->getVarComoDecimal ( $vo->vlGlobalReal ) . ",";
		
		$retorno .= $this->getVarComoDecimal ( $vo->vlMensalAnterior ) . ",";
		$retorno .= $this->getVarComoDecimal ( $vo->vlGlobalAnterior ) . ",";
		
		$retorno .= $this->getVarComoDecimal ( $vo->vlMensalModAtual ) . ",";
		$retorno .= $this->getVarComoDecimal ( $vo->vlGlobalModAtual ) . ",";
		
		$retorno .= $this->getVarComoDecimal ( $vo->numMesesParaOFimdoPeriodo ) . ",";
		$retorno .= $this->getVarComoDecimal ( $vo->numPercentual ) . ",";
		$retorno .= $this->getVarComoString ( $vo->obs );
		
		$retorno .= $vo->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		$retorno = "";
		$sqlConector = "";        
				           
		//$vo = new voContratoModificacao();
        if ($vo->vlModificacaoReferencial != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlModificacaoReferencial . " = " . $this->getVarComoDecimal($vo->vlModificacaoReferencial );
			$sqlConector = ",";
		}
		
		if ($vo->vlModificacaoAoContrato != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlModificacaoAoContrato . " = " . $this->getVarComoDecimal($vo->vlModificacaoAoContrato );
			$sqlConector = ",";
		}
		
		if ($vo->vlModificacaoReal != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlModificacaoReal . " = " . $this->getVarComoDecimal($vo->vlModificacaoReal );
			$sqlConector = ",";
		}					
		
		if ($vo->vlMensalAtual != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlMensalAtualizado . " = " . $this->getVarComoDecimal($vo->vlMensalAtual );
			$sqlConector = ",";
		}
		
		if ($vo->vlGlobalAtual != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlGlobalAtualizado . " = " . $this->getVarComoDecimal($vo->vlGlobalAtual );
			$sqlConector = ",";
		}
		
		if ($vo->vlGlobalReal != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlGlobalReal . " = " . $this->getVarComoDecimal($vo->vlGlobalReal );
			$sqlConector = ",";
		}	
		
		if ($vo->vlMensalAnterior != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlMensalAnterior . " = " . $this->getVarComoDecimal($vo->vlMensalAnterior );
			$sqlConector = ",";
		}
		
		if ($vo->vlGlobalAnterior != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlGlobalAnterior . " = " . $this->getVarComoDecimal($vo->vlGlobalAnterior );
			$sqlConector = ",";
		}
		
		if ($vo->vlMensalModAtual != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlMensalModAtual . " = " . $this->getVarComoDecimal($vo->vlMensalModAtual );
			$sqlConector = ",";
		}
		
		if ($vo->vlGlobalModAtual != null) {
			$retorno .= $sqlConector . voContratoModificacao::$nmAtrVlGlobalModAtual . " = " . $this->getVarComoDecimal($vo->vlGlobalModAtual );
			$sqlConector = ",";
		}
				
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesEntidadeUpdate ();
		
		return $retorno;
	}
}
?>