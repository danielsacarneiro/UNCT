<?php
include_once (caminho_util . "bibliotecaSQL.php");
class filtroConsultarContratoConsolidacao extends filtroManterContratoInfo {
	public $nmFiltro = "filtroConsultarContratoConsolidacao";
	
	static $ID_REQ_Caracteristicas = "ID_REQ_Caracteristicas";
	static $CD_ATENCAO = "CD_ATENCAO";

	static $DS_CREDENCIAMENTO = "Credenc.";
	static $DS_ESCOPO= "Escopo";
	static $DS_DEMANDA = "Tem Demanda Prorrog.";
	static $DS_PRORROGADO = "Será Prorrog.";	
	
	static $NmColInSeraProrrogadoConsolidado = "NmColInSeraProrrogadoConsolidado";
	static $NmColInTemDemanda = "InTemDemanda";
	static $NmColInProrrogavel = "NmColInProrrogavel";
	static $NmColInProrrogacaoExcepcional = "InProrrogacaoExcepcional";
	
	static $NmColDtInicioVigencia = "NmColDtInicioVigencia";
	static $NmColQtdDiasParaVencimento = "NmColQtdDiasParaVencimento";
	static $NmColPeriodoEmAnos = "NmColPeriodoEmAnos";
	
	static $NmColDtFimVigencia = "NmColDtFimVigencia";
	static $NmColSqContratoMater = "NmColSqContratoMater";
	static $NmColSqContratoAtual = "NmColSqContratoAtual";
	static $NmColCdEspecieContratoAtual = "NmColCdEspecieContratoAtual";
	static $NmColSqEspecieContratoAtual = "NmColSqEspecieContratoAtual";
	static $NmTabContratoMater = "TAB_CONTRATO_MATER";
	static $NmTabContratoATUAL = "TAB_CONTRATO_ATUAL";
	
	static $nmAtrQtdDiasParaVencimento = "nmAtrQtdDiasParaVencimento";
	static $nmAtrQtdDiasParaVencimentoProposta = "nmAtrQtdDiasParaVencimentoProposta";
	static $nmAtrInProrrogacao = "nmAtrInProrrogacao";
	
	static $ID_REQ_DtFimVigenciaInicial = "ID_REQ_DtFimVigenciaInicial";
	static $ID_REQ_DtFimVigenciaFinal = "ID_REQ_DtFimVigenciaFinal";
	
	static $ID_REQ_ValorInicial = "ID_REQ_ValorInicial";
	static $ID_REQ_ValorFinal = "ID_REQ_ValorFinal";
	
	static $ID_REQ_NumPeriodoEmAnosInicial = "ID_REQ_NumPeriodoEmAnosInicial";
	static $ID_REQ_NumPeriodoEmAnosFinal = "ID_REQ_NumPeriodoEmAnosFinal";
	
	static $ID_REQ_MesIntervaloFimVigencia = "ID_REQ_MesIntervaloFimVigencia";
	static $ID_REQ_AnoIntervaloFimVigencia = "ID_REQ_AnoIntervaloFimVigencia";
	static $ID_REQ_InProduzindoEfeitos = "ID_REQ_InProduzindoEfeitos";
	
	var $cdEspecie = "";
	var $qtdDiasParaVencimento = "";
	var $qtdDiasParaVencimentoProposta = "";
	
	var $inProrrogacao = "";
	var $dtFimVigenciaInicial = "";
	var $dtFimVigenciaFinal = "";
	
	var $valorInicial = "";
	var $valorFinal = "";
		
	var $numPeriodoEmAnosInicial = "";
	var $numPeriodoEmAnosFinal = "";
	
	var $mesIntervaloFimVigencia= "";
	var $anoIntervaloFimVigencia= "";
	var $inProduzindoEfeitos = "";
	var $inCaracteristicas = "";
	
	function __construct1($pegarFiltrosDaTela) {
		parent::__construct1($pegarFiltrosDaTela);
		$this->cdEspecie = dominioEspeciesContrato::getColecaoFiltroContratoConsolidacao();
		//echo "chamou construtor ok";
	}	
	
	function getFiltroFormulario() {
		parent::getFiltroFormulario ();
		$this->cdEspecie = @$_POST [vocontrato::$nmAtrCdEspecieContrato];
		$this->qtdDiasParaVencimento = @$_POST [static::$nmAtrQtdDiasParaVencimento];
		$this->qtdDiasParaVencimentoProposta = @$_POST [static::$nmAtrQtdDiasParaVencimentoProposta];
		
		$this->inProrrogacao = @$_POST [static::$nmAtrInProrrogacao];
		$this->dtFimVigenciaFinal = @$_POST [static::$ID_REQ_DtFimVigenciaFinal];
		$this->dtFimVigenciaInicial = @$_POST [static::$ID_REQ_DtFimVigenciaInicial];
		
		$this->valorInicial = @$_POST [static::$ID_REQ_ValorInicial];
		$this->valorFinal = @$_POST [static::$ID_REQ_ValorFinal];
		
		$this->numPeriodoEmAnosInicial = @$_POST [static::$ID_REQ_NumPeriodoEmAnosInicial];
		$this->numPeriodoEmAnosFinal = @$_POST [static::$ID_REQ_NumPeriodoEmAnosFinal];
		
		$this->mesIntervaloFimVigencia = @$_POST [static::$ID_REQ_MesIntervaloFimVigencia];
		$this->anoIntervaloFimVigencia = @$_POST [static::$ID_REQ_AnoIntervaloFimVigencia];
		
		$this->inCaracteristicas = @$_POST [static::$ID_REQ_Caracteristicas];
		
		$this->inProduzindoEfeitos = @$_POST [static::$ID_REQ_InProduzindoEfeitos];
		if($this->inProduzindoEfeitos == null || $this->inProduzindoEfeitos == ""){
			$this->inProduzindoEfeitos = constantes::$CD_SIM;
		}
		
		$this->inGestor = @$_POST [voContratoInfo::$nmAtrCdPessoaGestor];		
	}
	
	static function getDataBaseReajuste($nmTabelaContratoInfo, $nmTabelaContrato){
		$atributoDataReajuste = "COALESCE($nmTabelaContratoInfo" . "." .voContratoInfo::$nmAtrDtBaseReajuste
		. ",$nmTabelaContratoInfo." . voContratoInfo::$nmAtrDtProposta
		. ",$nmTabelaContrato ." . vocontrato::$nmAtrDtAssinaturaContrato
		.")";
		
		return $atributoDataReajuste;
		
	}
	function getSQFiltroCdEspecie($nmTabelaContrato) {
		if ($this->cdEspecie != null && ! $this->isAtributoArrayVazio ( $this->cdEspecie )) {
			$comparar = " = '" . $this->cdEspecie . "'";
			if (is_array ( $this->cdEspecie )) {
				$comparar = " IN (" . getSQLStringFormatadaColecaoIN ( $this->cdEspecie, true ) . ")";
			}
			
			$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . $comparar;
		}
		
		return $filtro;
	}
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
		$filtro = "";
		$conector = "";
		$isHistorico = $this->isHistorico;
		
		$nmTabela = voContratoInfo::getNmTabelaStatic ( $this->isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( $isHistorico );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );

		if($this->qtdDiasParaVencimentoProposta != null){	
			$dtReferencia = getVarComoDataSQL(getDataHoje());
			$atributoDataReajuste = static::getDataBaseReajuste($nmTabelaContratoInfo, $nmTabelaContrato);
			
			$ano = "YEAR($dtReferencia)";
			$mes = "MONTH($atributoDataReajuste)";
			$dia = "DAY($atributoDataReajuste)";
			
			//$dia = "15";
			$dtPropostaPAram = getDataSQLFormatada($ano,$mes, $dia);
			//echo $dtPropostaPAram;
			
			$nmAtributoDataNotificacao = getDataSQLDiferencaDias($dtReferencia, $dtPropostaPAram);			
			$filtro = $filtro . $conector . "$nmAtributoDataNotificacao >=0 AND $nmAtributoDataNotificacao <= $this->qtdDiasParaVencimentoProposta";
		
			$conector  = "\n AND ";
		}
		
		if($this->qtdDiasParaVencimento != null){
			/*$nmAtributoDataNotificacao = static::$NmTabContratoATUAL . "." .vocontrato::$nmAtrDtVigenciaFinalContrato;
			$dtNotificacaoPAram = getVarComoDataSQL(somarOuSubtrairDiasNaData(getDataHoje(), $this->qtdDiasParaVencimento));
				
			//se a data consultada + qtddiasprazo for menor que a data de hoje, significa que o prazo ja passou, entao a demanda deve ser exibida
			$filtro = $filtro . $conector
			. " (!($nmAtributoDataNotificacao IS NULL OR $nmAtributoDataNotificacao = '0000-00-00') AND $nmAtributoDataNotificacao <= $dtNotificacaoPAram ) ";*/
						
			$nmAtributoDataNotificacao = getDataSQLDiferencaDias(getVarComoDataSQL(getDataHoje()), static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato);
			$filtro = $filtro . $conector . "$nmAtributoDataNotificacao >=0 AND $nmAtributoDataNotificacao <= $this->qtdDiasParaVencimento";
				
			$conector  = "\n AND ";
		}
				
		if ($this->inProrrogacao != null && $this->inProrrogacao != "") {
			//echo "TESTE".$this->inProrrogacao ."teste";
			$filtro = $filtro . $conector . static::getSQLComparacaoPrazoProrrogacao($this->inProrrogacao);
			$conector = "\n AND ";
		}
		
		if ($this->cdEspecie != null && ! $this->isAtributoArrayVazio ( $this->cdEspecie )) {
			$filtro = $filtro . $conector . $this->getSQFiltroCdEspecie ( $nmTabelaContrato );
			$conector = "\n AND ";
		}
		
		if ($this->tpVigencia != null && $this->tpVigencia != "") {//constantes::$CD_OPCAO_TODOS) {
			$nmcoldtinicio = static::$NmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato;
			$nmcoldtfim = static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato;
							
			if ($this->tpVigencia == dominioTpVigencia::$CD_OPCAO_VIGENTES) {
				$filtro = $filtro . $conector . getSQLDataVigenteSqSimples ( null, $nmcoldtinicio, $nmcoldtfim );
			} else {
				$filtro = $filtro . $conector . getSQLDataNaoVigenteSqSimples ( null, $nmcoldtinicio, $nmcoldtfim );
			}
			
			$conector = "\n AND ";
		}
	
		/*if ($this->dtVigencia != null && $this->dtVigencia != "") {
			$nmcoldtinicio = vocontrato::$nmAtrDtVigenciaInicialContrato;
			$nmcoldtfim = vocontrato::$nmAtrDtVigenciaFinalContrato;
				
			$filtro = $filtro . $conector . getSQLDataVigenteSimplesPorData(
					$nmTabelaContrato, 
					$this->dtVigencia, 
					$nmcoldtinicio, 
					$nmcoldtfim);
				
			$conector = "\n AND ";
		}*/
		
		//esse filtro deve ser conjulgado com o JOIN no dbContratoInfo
		//isto porque, quando a producao dos efeitos for NAO, retornará os ultimos registros que ainda nao foram publicados
		//ou seja, o JOIN, em dbContratoInfo, ja foi feito retornando o ultimo registro (mais atual)
		//dai eh so verificar se a data publicacao EH NULA
		//caso contrario, quando se desejar aqueles que produzem efeitos, basta, no JOIN do dbContratoInfo
		//SOMENTE retornar o ultimo registro QUE TENHA A DATA DE PUBLICACAO DIFERENTE DE NULO, o que nao eh feito aqui
		//ja que, para chegar aqui, o JOIN ja foi feito
		/*$inProduzindoEfeitos= $this->inProduzindoEfeitos;
		if ($inProduzindoEfeitos != null) {
			$nmColunaComparacao = vocontrato::$nmAtrDtPublicacaoContrato;
			//pega o contrato atual (termo atual), de maior sequencial, independente de efeitos
			if($inProduzindoEfeitos == constantes::$CD_NAO){
				$filtro = $filtro . $conector 
				. "(". static::$NmTabContratoATUAL . ".$nmColunaComparacao IS NULL "
				. " OR ". static::$NmTabContratoATUAL. ".$nmColunaComparacao = '0000-00-00')";
				//$cdCampoSubstituir .= " AND ($nmColunaComparacao IS NOT NULL AND $nmColunaComparacao <> '0000-00-00')";
			}
			
			$conector = "\n AND ";
		}*/		
		
		// atributos do filtromanter
		if ($this->anoContrato != null) {
			
			$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . " = " . $this->anoContrato;
			
			$conector = "\n AND ";
		}
		
		if ($this->cdContrato != null) {
			
			$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato . " = " . $this->cdContrato;
			
			$conector = "\n AND ";
		}
		
		if ($this->tipoContrato != null) {
			
			$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . " = " . getVarComoString ( $this->tipoContrato );
			
			$conector = "\n AND ";
		}
		
		if ($this->nmContratada != null) {
			$filtro = $filtro . $conector 
			. "($nmTabelaPessoaContrato." . vopessoa::$nmAtrNome . " LIKE '%" . $this->nmContratada . "%'" 
			. " OR $nmTabelaContrato ." . vocontrato::$nmAtrContratadaContrato . " LIKE '%" . $this->nmContratada . "%')"
			;
			$conector = "\n AND ";
		}
		
		if ($this->docContratada != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc . " = '" . documentoPessoa::getNumeroDocSemMascara ( $this->docContratada ) . "'";
			$conector = "\n AND ";
		}
		
		//eh independente do inCaracteristicas (que eh usado na tela de consulta)
		//este aqui eh usado para consultas especificas
		if (isAtributoValido($this->inSeraProrrogado)) {
			$filtroTemp = $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrInSeraProrrogado . " = " . getVarComoString ( $this->inSeraProrrogado);
			//SE FOR NULO na base, presume-se prorrogavel
			if($this->inSeraProrrogado == constantes::$CD_SIM){
				$filtroTemp .= " OR $nmTabelaContratoInfo." . voContratoInfo::$nmAtrInSeraProrrogado . " IS NULL ";
				$filtroTemp = "($filtroTemp)";
			}
							
			$filtro = $filtro . $conector . $filtroTemp;
			//$filtroTemp = getSQLFiltroAtributoArrayComparacao($this->inSeraProrrogado, voContratoInfo::$nmAtrInSeraProrrogado, $nmTabelaContratoInfo);		
			$conector = "\n AND ";
		}
		
		if ($this->inTemGarantia != null) {
			
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrInTemGarantia . " = " . getVarComoString ( $this->inTemGarantia );
			
			$conector = "\n AND ";
		}
		
		if ($this->tpGarantia != null) {
			
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrTpGarantia . " = " . getVarComoNumero ( $this->tpGarantia );
			
			$conector = "\n AND ";
		}
		
		if ($this->inMaoDeObra != null) {
			
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrInMaoDeObra . " = " . getVarComoString ( $this->inMaoDeObra );
			
			$conector = "\n AND ";
		}
		
		if ($this->cdClassificacao != null) {
			
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrCdClassificacao . " = " . getVarComoNumero ( $this->cdClassificacao );
			
			$conector = "\n AND ";
		}
		
		if ($this->cdAutorizacao != null) {
			$strComparacao = $this->getSqlAtributoCoalesceAutorizacao ();
			
			if (! is_array ( $this->cdAutorizacao )) {
				$filtro = $filtro . $conector . 
				// . $nmTabelaContrato. "." .vocontrato::$nmAtrCdAutorizacaoContrato
				$strComparacao . " = " . $this->cdAutorizacao;
			} else {
				
				$colecaoAutorizacao = $this->cdAutorizacao;
				$filtro = $filtro . $conector . $strComparacao . voContratoInfo::getOperacaoFiltroCdAutorizacaoOR_AND ( $colecaoAutorizacao, $this->InOR_AND );
				// echo $strComparacao . voContratoInfo::getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutorizacao, $this->InOR_AND);
			}
			
			$conector = "\n AND ";
		}
		
		if ($this->objeto != null) {
			$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrObjetoContrato . " LIKE '%" . utf8_encode ( $this->objeto ) . "%'";
				
			$conector = "\n AND ";
		}
		
		if ($this->mesIntervaloFimVigencia != null) {
			if($this->anoIntervaloFimVigencia == null){
				$this->anoIntervaloFimVigencia = getAnoHoje();
			}
			$anoTemp = $this->anoIntervaloFimVigencia;
			
			$this->dtFimVigenciaFinal = getDataUltimoDiaMesHtml($this->mesIntervaloFimVigencia, $anoTemp);
			$this->dtFimVigenciaInicial = getDataMesHtml("01", $this->mesIntervaloFimVigencia, $anoTemp);
		}		
		
		if ($this->dtFimVigenciaInicial != null) {
			$colunaAComparar = static::getComparacaoWhereDataVigencia(static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato);				
			$filtro = $filtro . $conector . "($colunaAComparar IS NOT NULL AND $colunaAComparar >= " . getVarComoData($this->dtFimVigenciaInicial) . ") ";
				
			$conector = "\n AND ";
		}

		if ($this->dtFimVigenciaFinal != null) {
			$colunaAComparar = static::getComparacaoWhereDataVigencia(static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato);		
			$filtro = $filtro . $conector . "($colunaAComparar IS NOT NULL AND $colunaAComparar <= " . getVarComoData($this->dtFimVigenciaFinal) . ") ";
		
			$conector = "\n AND ";
		}		
		
		$fatorMensal = "*12";
		if ($this->valorInicial != null || $this->valorFinal != null) {
			$filtro = $filtro . $conector . static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrVlMensalContrato . " > '0' ";
			
			$conector = "\n AND ";
				
		}
		if ($this->valorInicial != null) {			
			$filtro = $filtro . $conector . static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrVlMensalContrato . "$fatorMensal >= " . getVarComoDecimal($this->valorInicial);
		
			$conector = "\n AND ";
		}
		
		if ($this->valorFinal != null) {
			$filtro = $filtro . $conector . static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrVlMensalContrato . "$fatorMensal <= " . getVarComoDecimal($this->valorFinal);
		
			$conector = "\n AND ";
		}

		if ($this->numPeriodoEmAnosInicial != null) {
			$filtro = $filtro . $conector . filtroConsultarContratoConsolidacao::getSQLQtdAnosVigenciaContrato() . " >= " . getVarComoNumero($this->numPeriodoEmAnosInicial);
		
			$conector = "\n AND ";
		}
		
		if ($this->numPeriodoEmAnosFinal != null) {
			$filtro = $filtro . $conector . filtroConsultarContratoConsolidacao::getSQLQtdAnosVigenciaContrato() . " <= " . getVarComoNumero($this->numPeriodoEmAnosFinal);
		
			$conector = "\n AND ";
		}
		
		if ($this->inPrazoProrrogacao != null) {
			if($this->inPrazoProrrogacao == constantes::$CD_OPCAO_NENHUM){
				$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrInPrazoProrrogacao . " IS NULL ";
			}else{
				$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrInPrazoProrrogacao . " = " . getVarComoNumero($this->inPrazoProrrogacao);
			}
			$conector = "\n AND ";
		}
		
		$inCaracteristicas = $this->inCaracteristicas;
		//var_dump($inCaracteristicas);
		if (isAtributoValido($inCaracteristicas) && ! $this->isAtributoArrayVazio ( $inCaracteristicas )) {
			//echo "entrou2";
			//$inOrAndFase = $this->inOR_AND_Fase;
			$strFiltroCaracteristica = getSQLBuscarAtributoSimOuNaoOuSeNulo (
					$inCaracteristicas,
					static::getDadosContratoColecaoCheckBox($this->isHistorico()),
					constantes::$CD_OPCAO_AND,
					false);

			$filtro = $filtro . $conector . $strFiltroCaracteristica;
			$conector = "\n AND ";
		}
		
		if (isAtributoValido($this->inGestor)) {
			$temp = $this->inGestor == constantes::$CD_SIM? " IS NOT NULL ": " IS NULL ";
		
			$filtro = $filtro . $conector . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdPessoaGestor . $temp;
		
			$conector = "\n AND ";
		}		
		
		//retira os contratos CANCELADOS
		//$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrEspecieContrato . " NOT LIKE '%CANCELADO%'";
		
		$this->formataCampoOrdenacao ( new voContratoInfo());
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	
	/**
	 * Traz  o sql que permite calcular o periodo de vigencia total de um contrato
	 * @return string
	 */
	static function getSQLQtdAnosVigenciaContrato(){
		return getDataSQLDiferencaAnos(static::$NmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato, static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato);
	}
	
	static function getSQLComparacaoPrazoProrrogacao($filtroPorrogacao){
		//prorrogacao excepcional eh cabivel somente na modalidade do art. 57, II
		//um formato para o filtro que nao tenha a ver com prorrogacao excepcional, cabivel apenas para o art 57,II, conforme art 57, par 4, lei 8666
		//se nada tiver a ver com excepcional, o formato do filtro deve ser no modo loop abaixo porque deve levar em consideracao todos os tipos de prorrogacao
		//caso contrario, basta verificar para o art 57, II, que eh o unico que admite prorrogacao excepcional
		if(!array_key_exists($filtroPorrogacao, dominioProrrogacaoFiltroConsolidacao::getColecaoExcepcional())){
		//if(!in_array($filtroPorrogacao, array_keys(dominioProrrogacaoFiltroConsolidacao::getColecaoExcepcional()))){
			//echo "normal";
			if($filtroPorrogacao == dominioProrrogacaoFiltroConsolidacao::$CD_PRORROGAVEL){
				$sinal = "<";
			}else{
				$sinal = ">=";
			}							
			
			$STR_SUBSTITUIR_IND_PROR = constantes::$CD_CAMPO_SUBSTITUICAO . "INDICADOR_PROR";
			$STR_SUBSTITUIR_VALOR_PRAZO = constantes::$CD_CAMPO_SUBSTITUICAO . "VALOR_PRAZO";
			//$nmAtributoAcomparar = getDataSQLDiferencaAnos(static::$NmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato, static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato);
			$nmAtributoAcomparar = static::getSQLQtdAnosVigenciaContrato();		
			$filtroTemp = voContratoInfo::$nmAtrInPrazoProrrogacao . " = $STR_SUBSTITUIR_IND_PROR AND $nmAtributoAcomparar >=0 AND $nmAtributoAcomparar $sinal " . $STR_SUBSTITUIR_VALOR_PRAZO;		
			$operadorSQL = " OR ";
			
			foreach (array_keys(dominioProrrogacaoContrato::getColecaoValidacaoSQL()) as $chave){
				$temp = str_replace($STR_SUBSTITUIR_IND_PROR, $chave, $filtroTemp);
				$temp = str_replace($STR_SUBSTITUIR_VALOR_PRAZO, dominioProrrogacaoContrato::getPrazoProrrogacao($chave), $temp);
									
				$retorno .= "($temp)$operadorSQL";
			}			
			
			 // tamanho da string retirada do fim do retorno			
			$retorno = removerUltimaString($operadorSQL, $retorno);
					
		}else{
			//echo "excepcional";
			//apenas para os filtros de prorrogacao excepcional
			if($filtroPorrogacao == dominioProrrogacaoFiltroConsolidacao::$CD_PERMITE_EXCEPCIONAL){
				$sinal = "=";
			}else{
				$sinal = ">=1+";
			}
				
			$chave = $STR_SUBSTITUIR_IND_PROR = dominioProrrogacaoContrato::$CD_ART57_II;
			$STR_SUBSTITUIR_VALOR_PRAZO = dominioProrrogacaoContrato::getPrazoProrrogacao($chave);
			//$nmAtributoAcomparar = getDataSQLDiferencaAnos(static::$NmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato, static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato);
			$nmAtributoAcomparar = static::getSQLQtdAnosVigenciaContrato();
			$temp = voContratoInfo::$nmAtrInPrazoProrrogacao . " = $STR_SUBSTITUIR_IND_PROR AND $nmAtributoAcomparar >=0 AND $nmAtributoAcomparar $sinal " . $STR_SUBSTITUIR_VALOR_PRAZO;
			$operadorSQL = " OR ";
			//$retorno .= "($temp)$operadorSQL";			
			$retorno .= "$temp";
		}		
		
		//para o caso de permitir sempre prorrogacao e a busca for por prorrogaveis
		if($filtroPorrogacao == dominioProrrogacaoFiltroConsolidacao::$CD_PRORROGAVEL){
			$retorno = "($retorno) OR ";
			$retorno .= "(" . voContratoInfo::$nmAtrInPrazoProrrogacao . " IS NULL OR "
					. voContratoInfo::$nmAtrInPrazoProrrogacao ." IN (". getSQLStringFormatadaColecaoIN(array_keys(dominioProrrogacaoContrato::getColecaoPermiteProrrogacaoSQL())) . "))";
		}else if($filtroPorrogacao == dominioProrrogacaoFiltroConsolidacao::$CD_NAOPRORROGAVEL){
			//mesma logica acima
			$retorno = "($retorno) OR ";
			//$retorno .= "(". voContratoInfo::$nmAtrInPrazoProrrogacao ." IN (". getSQLStringFormatadaColecaoIN(array_keys(dominioProrrogacaoContrato::getColecaoPermiteProrrogacaoSQL())) . "))";
			$retorno .= "(". voContratoInfo::$nmAtrInPrazoProrrogacao ." = ". dominioProrrogacaoContrato::$CD_IMPRORROGAVEL . ")";
		}
		
		$retorno = "($retorno)";
		
		return $retorno;
	}
		
	static function getComparacaoWhereDataVigencia($nmAtributoTabela){
		return getSQLCASE($nmAtributoTabela
						, '0000-00-00'						
						, 'NULL'
						, $nmAtributoTabela);
	}
	
	function getAtributoOrdenacaoDefault() {
		$nmTabela = (new vocontrato())->getNmTabelaEntidade($this->isHistorico );
		$retorno = $nmTabela . "." . vocontrato::$nmAtrTipoContrato . " " . $this->cdOrdenacao 
		. "," . $nmTabela . "." . vocontrato::$nmAtrAnoContrato . " " . $this->cdOrdenacao
		. "," . $nmTabela . "." . vocontrato::$nmAtrCdContrato . " " . $this->cdOrdenacao;
		//return $retorno;
		return "";
	}
	function getAtributosOrdenacao() {
		$nmTabela = (new vocontrato())->getNmTabelaEntidade($this->isHistorico );
		$varAtributos = array (
				"$nmTabela.".vocontrato::$nmAtrAnoContrato => "Ano",
				"$nmTabela.".vocontrato::$nmAtrCdContrato => "Número",
				"$nmTabela.".vocontrato::$nmAtrTipoContrato => "Tipo",
				static::$NmColDtFimVigencia => "Fim.Vigencia",
		);
		return $varAtributos;
	}
	/**
	 * usado para fases retiradas da planilha
	 */
	static function getColecaoCaracteristicas() {
		$retorno = array (
				voContratoInfo::$nmAtrInEscopo => static::$DS_ESCOPO,
				voContratoInfo::$nmAtrInCredenciamento => static::$DS_CREDENCIAMENTO,
				//voContratoInfo::$nmAtrInSeraProrrogado => static::$DS_PRORROGADO,
				voDemanda::$nmAtrAno => static::$DS_DEMANDA,
		);
	
		return $retorno;
	}
	
	/**
	 * Metodo que relaciona o codigo do dominio ao atributo no banco com cujo valor sera comparado
	 * eh usado quando a consulta tiver dados check box de uma entidade, cujo filtro eh do tipo sim ou nao via checkbox
	 * @return string[]
	 */
	static function getDadosContratoColecaoCheckBox($isTabHistorico) {
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$retorno = array (
				voContratoInfo::$nmAtrInEscopo => voContratoInfo::$nmAtrInEscopo,
				voContratoInfo::$nmAtrInCredenciamento => voContratoInfo::$nmAtrInCredenciamento,
				//voContratoInfo::$nmAtrInSeraProrrogado => voContratoInfo::$nmAtrInSeraProrrogado,
				voDemanda::$nmAtrAno => filtroConsultarContratoConsolidacao::getAtributoConsultaTemDemanda("$nmTabelaDemanda." . voDemanda::$nmAtrAno),
	
		);
	
		return $retorno;
	}
	
	static function getAtributoConsultaTemDemanda($nmAtributoAComparar, $nmAtributoSQLAlias=null){
		return 	getSQLCASEIsNULL($nmAtributoAComparar
							, getVarComoString(constantes::$CD_NAO)
							, getVarComoString(constantes::$CD_SIM),
							$nmAtributoSQLAlias);	
	}
	
	/**
	 * retorna os atributos que serao totalizados
	 */
	static function getAtributosValoresTotalizados(){
		$nmTABContratoAtual = filtroConsultarContratoConsolidacao::$NmTabContratoATUAL;
		//return array("SUM($nmTABContratoAtual.".vocontrato::$nmAtrVlMensalContrato. ")", "SUM($nmTABContratoAtual.".vocontrato::$nmAtrVlGlobalContrato . ")");		
		return array("$nmTABContratoAtual.".vocontrato::$nmAtrVlMensalContrato, "$nmTABContratoAtual.".vocontrato::$nmAtrVlGlobalContrato);
	}
	
}
