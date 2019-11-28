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
	
	function consultarTelaConsulta($vo, $filtro) {
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
				// $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
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
				
		// echo "aqui";
		
		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
	}
	
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
	/*function consultaTermosPosterioresAoReajuste($vo) {
		$voContratoFiltro = clone $vo->vocontrato;
		$voContratoFiltro->cdEspecie = null;
		$voContratoFiltro->sqEspecie = null;
		$dtProducaoEfeito = $vo->dtModificacao;
		if ($dtProducaoEfeito == null) {
			throw new excecaoGenerica ( "A data do termo em questão não pode ser nula. Verifique o campo na tela de inclusão." );
		}
		$retorno = null;
		$filtro = new filtroManterContratoModificacao ( false );
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vocontrato = $voContratoFiltro;
		$filtro->dtProducaoEfeitoTermoPosterior = $dtProducaoEfeito;
		$recordSet = $this->consultarTelaConsulta ( $vo, $filtro );
		
		return $recordSet;
	}
	
	/**
	 * recalcula os termos de contrato anteriores ao reajuste
	 * 
	 * @param unknown $vo        	
	 * @throws excecaoChaveRegistroInexistente
	 * @throws excecaoGenerica
	 */
	/*function recalcularReajuste($vo) {
		$colecaoTermos = $this->consultaTermosPosterioresAoReajuste ( $vo );
		// $vo = new voContratoModificacao();
		if(!isColecaoVazia($colecaoTermos)){
			foreach ( $colecaoTermos as $registro ) {
				$termo = new voContratoModificacao ();
				$termo->getDadosBanco($registro);			
				$termo->setPercentualReajuste ( $percentual );
				
				//echoo( $termo->toString()); 
				parent::alterarPorCima ( $termo );
			}
		}
	}*/
	
	/*
	 * function getRegistroDataTermoComDataPosterior($vo){
	 * $voContratoFiltro = clone $vo->vocontrato;
	 * $voContratoFiltro->cdEspecie = null;
	 * $voContratoFiltro->sqEspecie = null;
	 * $dtProducaoEfeito = $vo->dtModificacao;
	 * if($dtProducaoEfeito == null){
	 * throw new excecaoGenerica("A data do termo em questão não pode ser nula. Verifique o campo na tela de inclusão.");
	 * }
	 * $retorno = null;
	 * $filtro = new filtroManterContratoModificacao(false);
	 * $filtro->setaFiltroConsultaSemLimiteRegistro();
	 * $filtro->vocontrato = $voContratoFiltro;
	 * $filtro->dtProducaoEfeitoTermoPosterior = $dtProducaoEfeito;
	 * $recordSet = $this->consultarTelaConsulta($vo, $filtro);
	 *
	 * if(!isColecaoVazia($recordSet)){
	 * $retorno = $recordSet[0];
	 * }
	 *
	 * return $retorno;
	 * }
	 */
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
	/**
	 * pega o contratomod passado como parametro de uma colecao de valores execucao
	 * retorna um vogenerico/registro
	 * @param unknown $voContratoMod
	 * @param unknown $recordSet
	 * @return voContratoModificacao
	 */
	/*function getRegistroTermoEspecificoColecaoExecucao($voContrato, $recordSet) {
		if(!isColecaoVazia($recordSet)){
			//a busca eh decrescente porque o recordset esta na ordem crescente da execucao do contrato
			//dai a funcao pega o mais recente
			$existe = false;
			$indiceinicial = sizeof($recordSet)-1; 
			for($i=$indiceinicial; $i>=0;$i--){
				$registro = $recordSet[$i];
				$voTemp = new vocontrato();
				$voTemp->getDadosBanco($registro);
				
				$voContratoMod = $registro[filtroManterContratoModificacao::$NmColVOContratoModReajustado];
							
				if($voContrato->isIgualChavePrimaria($voTemp)){					
					//echoo ($voTemp->vlMensal);
					$existe = true;
					break;
				}
			}
			//se nao encontrou nenhum eh pq o valor atualizado eh o primeiro registro (mais recente)
			if(!$existe){
				$registro = $recordSet[$indiceinicial];
			}
			
			$retorno = $registro;
		}
		return $retorno;
	}*/
	
	function getRegistroAtualColecaoExecucao($recordSet) {
		if(!isColecaoVazia($recordSet)){
			$indiceinicial = sizeof($recordSet)-1;
			$retorno = $recordSet[$indiceinicial];
		}
		return $retorno;
	}
	
	
	function consultarExecucaoTermoEspecifico($voContratoComChaveCompleta, $dataVigencia=null) {
		$voContratoMater = clone $voContratoComChaveCompleta;
		$voContratoMater->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$voContratoMater->sqEspecie = 1;
		//echo ($dataVigencia);
		$recordSet = $this->consultarExecucao(clone $voContratoMater, $dataVigencia);
		return $this->getRegistroAtualColecaoExecucao($recordSet);		
		//return $this->getRegistroTermoEspecificoColecaoExecucao(clone $voContratoComChaveCompleta, $recordSet);		
	}
	
	/**
	 * identifica os registros que sofrerao o reajuste atraves da analise da data
	 * @param unknown $recordSet
	 * @param unknown $voContratoModReajuste
	 */
	function getColecaoIndicesRegistrosAAplicarReajuste($recordSet, $voContratoModReajuste) {
		$retorno = array();
		$i = 0;
		foreach ( $recordSet as $registro) {
			$voTemp = new voContratoModificacao ();
			$voTemp->getDadosBanco ( $registro );
				
			if ($voTemp->isReajusteAAplicar ( $voContratoModReajuste )) {			
				$retorno[] = $i;
			}			
			$i++;
		}
		
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
	 * @param unknown $recordSet
	 * @param unknown $voContratoModReajuste
	 * @return number[]
	 */
	function atualizaColecaoRegistros(&$recordSet) {
		$i = 0;
		$voAtualizadoAnterior = $recordSet [filtroManterContratoModificacao::$NmColVOContratoModReajustado][0];
		foreach ( $recordSet as $registro) {
			$voTemp = new voContratoModificacao ();
			$voTemp->getDadosBanco ( $registro );
			//echoo("teste1");
			$voAConsiderar = $registro [filtroManterContratoModificacao::$NmColVOContratoModReajustado];
			//var_dump($voAConsiderar);
			if ($voAConsiderar == null){
				
				if($voAtualizadoAnterior == null){
					$voAtualizadoAnterior = clone $voTemp;
				}
				
				if($voTemp->tpModificacao != dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO){
					//para todos os outros nada deve ser feito, pois os calculos ja foram realizados antes
					$voAConsiderar = clone $voTemp;
					//echoo("teste2");
				}else{
					//a prorrogacao eh a copia do vomod anterior
					$voAConsiderar = clone $voAtualizadoAnterior;
					//echoo("teste3");
				}
				$registro [filtroManterContratoModificacao::$NmColVOContratoModReajustado] = $voAConsiderar;
				$recordSet [$i] = $registro;
			}
			/*echoo($voTemp->toString());
			echoo($voAConsiderar->vlMensal);*/
			
			$i++;			
			$voAtualizadoAnterior = clone $voAConsiderar;
		}		
	}
	
	function consultarExecucaoValorGlobalReferencial($voContratoMater) {
		$recordset = $this->consultarExecucao($voContratoMater);		
		$this->atualizaValorGlobalPorPeriodo($recordset, $voContratoMater);
		return  $recordset;
	}
	
	function consultarExecucao($voContratoMater, $dataVigencia=null) {
		// consulta os regitros modificacao do contrato SEM REAJUSTE
		$voContratoFiltro = clone $voContratoMater;
		$voContratoFiltro->cdEspecie = null;
		$voContratoFiltro->sqEspecie = null;
		$retorno = null;
		$filtro = new filtroManterContratoModificacao ( false );
		// $filtro->tipoExceto = array(dominioTpContratoModificacao::$CD_TIPO_REAJUSTE);
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$atributoOrdenacao = voContratoModificacao::$nmAtrDtModificacao . " " . constantes::$CD_ORDEM_CRESCENTE;
		//$atributoOrdenacao .= "," . voContratoModificacao::$nmAtrTpModificacao . " " . constantes::$CD_ORDEM_CRESCENTE;
		$filtro->cdAtrOrdenacao = $atributoOrdenacao;
		/*
		 * $filtro->cdAtrOrdenacao = voContratoModificacao::$nmAtrDtModificacao;
		 * $filtro->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
		 */
		$filtro->vocontrato = $voContratoFiltro;
		$filtro->dtVigencia = $dataVigencia;
		$recordSet = $this->consultarTelaConsulta ( new voContratoModificacao (), $filtro );
		
		// agora consulta os reajustes para aplica-los aos registros necessarios
		$filtro->tipoExceto = null;
		
		//so serao consultados os reajustes porque, em caso de repactuacao, o valor nao se altera
		//isto quer dizer que, a partir da repactuacao, o valor em contratoMOD permanece inalterado para os reajustes retroativos
		//tendo em vista que, com a repactuacao, a empresa renuncia a outros valores 
		$filtro->tipo = dominioTpContratoModificacao::$CD_TIPO_REAJUSTE;		
		//$filtro->tipo = array(dominioTpContratoModificacao::$CD_TIPO_REAJUSTE, dominioTpContratoModificacao::$CD_TIPO_REPACTUACAO);
		
		$colecaoReajuste = $this->consultarTelaConsulta ( new voContratoModificacao (), $filtro );
		
		//para todos os registros incluidos em contrato Mod
		//reajustar os valores de acordo com os percentuais dos reajustes retroativos
		if (! isColecaoVazia ( $recordSet )) {								
			if (! isColecaoVazia ( $colecaoReajuste )) {
				$tamColecaoRecordSet = sizeof ( $recordSet );

				$registro = $recordSet [0];
				$voContratoReajustadoAtual = new voContratoModificacao ();
				$voContratoReajustadoAtual->getDadosBanco ( $registro );
				//MARRETA??? verificar o contrato csafi040-18 como exemplo
				if($voContratoReajustadoAtual->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_REAJUSTE){
					//so pra quando o primeiro registro contratomod eh de reajuste, pq o percentual de reajuste serah aplicado no contrato mater
					$voContratoReajustadoAtual->vlMensalAtual = getVarComoDecimal($voContratoMater->vlMensal);
					$voContratoReajustadoAtual->vlGlobalAtual = getVarComoDecimal($voContratoMater->vlGlobal);
				}
				// o primeiro contratomod ajustado eh ele mesmo
				$registro [filtroManterContratoModificacao::$NmColVOContratoModReajustado] = $voContratoReajustadoAtual;
				$recordSet [0] = $registro;
				//echoo("valor inicial atualizado: " . $voContratoReajustadoAtual->vlMensalAtual);
				
				$j=0;
				//para cada registro em contratoMod, identificar quais reajustes retroativos serao aplicados
				foreach ( $colecaoReajuste as $registroReajuste ) {
					$voTempReajuste = new voContratoModificacao ();
					$voTempReajuste->getDadosBanco ( $registroReajuste );
					//echoo ( "<br>PErcentual reajuste:" . $voTempReajuste->numPercentual );
					
					$colecaoIndicesRegistrosAReajustar = $this->getColecaoIndicesRegistrosAAplicarReajuste ( $recordSet, $voTempReajuste );
					//var_dump($colecaoIndicesRegistrosAReajustar);
					$tamColecaoRegistrosAReajustar = sizeof ( $colecaoIndicesRegistrosAReajustar );
					
					for($i = 0; $i < $tamColecaoRegistrosAReajustar; $i++) {
						//echoo("valor a reajustar: " . $voContratoReajustadoAtual->vlMensalAtual);
						//echoo($i);
						$indice = $colecaoIndicesRegistrosAReajustar [$i];
						$registro = $recordSet [$indice];
						
						$voTemp = new voContratoModificacao ();
						$voTemp->getDadosBanco ( $registro );
						//echoo($voTemp->toString(true)); 
						//echoo("indice" . $indice . " " . dominioTpContratoModificacao::getDescricaoStatic($voTemp->tpModificacao));
											
						if(!$registro[voContratoModificacao::$InReajusteAplicado] &&
								($voTemp->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_ACRESCIMO
								|| $voTemp->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_SUPRESSAO)){
							;
						}else{
							$voTemp->getValoresReajustadosAtuais($voContratoReajustadoAtual);
						}						
						
						//var_dump($registro);
						//echoo ("ANTIGO" . $voTemp->vlMensalAtual );
						$reajusteJaAplicado = $registro[voContratoModificacao::$InReajusteAplicado];
						//echoo (" reajuste aplicado $reajusteJaAplicado");
						if(!$reajusteJaAplicado){
							if($voTemp->tpModificacao != dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO){
								$voTemp->setPercentualReajuste ( $voTempReajuste );
							}
						}
						//echoo ("NOVO" . $voTemp->vlMensalAtual );
						// se for prorrogacao, so repete o valor contrato mod anterior, sem reajustar nada
						// se nao for, passou pelo if acima e a atualizacao do valor contrato mod deve ser registrada
						$registro [filtroManterContratoModificacao::$NmColVOContratoModReajustado] = CLONE $voTemp;
						$voContratoReajustadoAtual = clone $voTemp;
						//echoo ("VALOR MENSAL ATUALIZADO" . $voContratoReajustadoAtual->vlMensalAtual );
						$registro[voContratoModificacao::$InReajusteAplicado] = true;
						
						//$voContratoReajustadoAtual = clone $voTemp;
						// inclui novamente com as alteracoes realizadas
						$recordSet [$indice] = $registro;
					}
					$j ++;
				}
			}
			//pega o ultimo
			/*$indiceUltimo = $tamColecaoRecordSet-1;
			$registro = $recordSet[$indiceUltimo];
			$voTemp = new voContratoModificacao ();
			$voTemp->getDadosBanco ( $registro );
			//verifica se o ultimo registro eh do tipo prorrogacao para apenas repetir o valor
			if($voTemp->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO){
				$registro [filtroManterContratoModificacao::$NmColVOContratoModReajustado] = CLONE $voContratoReajustadoAtual;
				$recordSet [$indiceUltimo] = $registro;
			}*/
			
			$this->atualizaColecaoRegistros($recordSet);
			
			$retorno = $recordSet;
			
		} else {
			// se nao tiver registro alem de reajuste, mostra apenas os reajustes
			$retorno = $colecaoReajuste;
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