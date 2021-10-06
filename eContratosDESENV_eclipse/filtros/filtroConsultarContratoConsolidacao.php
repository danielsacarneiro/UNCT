<?php
include_once (caminho_util . "bibliotecaSQL.php");
class filtroConsultarContratoConsolidacao extends filtroManterContratoInfo {
	public $nmFiltro = "filtroConsultarContratoConsolidacao";

	static $NM_VIEW_CONTRATO_CONSOLIDACAO = "VIEW_CONTRATO_CONSOLIDACAO";
	static $ID_REQ_Caracteristicas = "ID_REQ_Caracteristicas";
	static $CD_ATENCAO = "CD_ATENCAO";

	static $DS_CREDENCIAMENTO = "Credenc.";
	static $DS_ESCOPO= "Escopo";
	static $DS_DEMANDA = "Tem Demanda Prorrog.";
	static $DS_PRORROGADO = "Será Prorrog.";

	static $ID_REQ_InTemDemandaProrrogacao = "ID_REQ_InTemDemandaProrrogacao";
	static $NmColQtdDiasSomadosTermoAtual = "NmColQtdDiasSomadosTermoAtual";
	static $NmColDtFimVigenciaOP = "NmColDtFimVigenciaOrdemParalisacao";
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
	static $NmTABDadosOrdemParalisacao = "NmTABDadosOrdemParalisacao";
	static $NmTABDadosContratoOrdemParalisacao = "NmTABDadosContratoOrdemParalisacao";
	static $NmTabDemandaContratoATUAL = "TAB_DEMANDA_CONTRATO_ATUAL";

	static $nmAtrQtdDiasParaVencimento = "nmAtrQtdDiasParaVencimento";
	static $nmAtrQtdDiasParaVencimentoProposta = "nmAtrQtdDiasParaVencimentoProposta";
	static $nmAtrInProrrogacao = "nmAtrInProrrogacao";

	static $ID_REQ_DtFimVigenciaInicial = "ID_REQ_DtFimVigenciaInicial";
	static $ID_REQ_DtFimVigenciaFinal = "ID_REQ_DtFimVigenciaFinal";

	static $ID_REQ_DtAssinaturaInicial = "ID_REQ_DtAssinaturaInicial";
	static $ID_REQ_DtAssinaturaFinal = "ID_REQ_DtAssinaturaFinal";
	
	static $ID_REQ_ValorInicial = "ID_REQ_ValorInicial";
	static $ID_REQ_ValorFinal = "ID_REQ_ValorFinal";

	static $ID_REQ_NumPeriodoEmAnosInicial = "ID_REQ_NumPeriodoEmAnosInicial";
	static $ID_REQ_NumPeriodoEmAnosFinal = "ID_REQ_NumPeriodoEmAnosFinal";

	static $ID_REQ_MesIntervaloFimVigencia = "ID_REQ_MesIntervaloFimVigencia";
	static $ID_REQ_AnoIntervaloFimVigencia = "ID_REQ_AnoIntervaloFimVigencia";
	static $ID_REQ_AnoAssinatura = "ID_REQ_AnoAssinatura";
	static $ID_REQ_InProduzindoEfeitos = "ID_REQ_InProduzindoEfeitos";

	var $cdEspecie = "";
	var $qtdDiasParaVencimento = "";
	var $qtdDiasParaVencimentoProposta = "";

	var $inProrrogacao = "";
	var $dtAssinaturaInicial = "";
	var $dtAssinaturaFinal = "";

	var $dtFimVigenciaInicial = "";
	var $dtFimVigenciaFinal = "";
	
	var $valorInicial = "";
	var $valorFinal = "";

	var $numPeriodoEmAnosInicial = "";
	var $numPeriodoEmAnosFinal = "";

	var $mesIntervaloFimVigencia= "";
	var $anoIntervaloFimVigencia= "";
	var $anoAssinatura = "";
	var $inProduzindoEfeitos = "";
	var $inCaracteristicas = "";
	var $inTemDemandaProrrogacao = "";

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
		$this->dtAssinaturaFinal = @$_POST [static::$ID_REQ_DtAssinaturaFinal];
		$this->dtAssinaturaInicial = @$_POST [static::$ID_REQ_DtAssinaturaInicial];

		$this->dtFimVigenciaFinal = @$_POST [static::$ID_REQ_DtFimVigenciaFinal];
		$this->dtFimVigenciaInicial = @$_POST [static::$ID_REQ_DtFimVigenciaInicial];
		
		$this->valorInicial = @$_POST [static::$ID_REQ_ValorInicial];
		$this->valorFinal = @$_POST [static::$ID_REQ_ValorFinal];

		$this->numPeriodoEmAnosInicial = @$_POST [static::$ID_REQ_NumPeriodoEmAnosInicial];
		$this->numPeriodoEmAnosFinal = @$_POST [static::$ID_REQ_NumPeriodoEmAnosFinal];

		$this->mesIntervaloFimVigencia = @$_POST [static::$ID_REQ_MesIntervaloFimVigencia];
		$this->anoIntervaloFimVigencia = @$_POST [static::$ID_REQ_AnoIntervaloFimVigencia];
		$this->anoAssinatura = @$_POST [static::$ID_REQ_AnoAssinatura];

		$this->inCaracteristicas = @$_POST [static::$ID_REQ_Caracteristicas];

		$this->inProduzindoEfeitos = @$_POST [static::$ID_REQ_InProduzindoEfeitos];
		if($this->inProduzindoEfeitos == null || $this->inProduzindoEfeitos == ""){
			$this->inProduzindoEfeitos = dominioContratoProducaoEfeitos::$CD_VISTO_COM_EFEITOS;
		}

		$this->inGestor = @$_POST [voContratoInfo::$nmAtrCdPessoaGestor];
		$this->inTemDemandaProrrogacao = @$_POST [static::$ID_REQ_InTemDemandaProrrogacao];
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

		$nmTabelaContratoInfo = $nmTabela = voContratoInfo::getNmTabelaStatic ( $this->isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabContratoATUAL = static::$NmTabContratoATUAL;
		$nmTabDadosOrdemParalisacao = static::$NmTABDadosOrdemParalisacao;
		$nmTabDemandaContratoATUAL = static::$NmTabDemandaContratoATUAL;

		$colunaACompararDtFim = filtroConsultarContratoConsolidacao::getAtributoDtFimVigenciaConsolidacao();

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
			//$nmColunaDtFim = static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato;
			$colunaAComparar = static::getAtributoDtFimVigenciaConsolidacao();
			$nmAtributoDataNotificacao = getDataSQLDiferencaDias(getVarComoDataSQL(getDataHoje()), $colunaAComparar);
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

		$inTemDemandaProrrogacao = $this->inTemDemandaProrrogacao;
		if (isAtributoValido($inTemDemandaProrrogacao)) {
			$nmTabelaDemandaContratoTA = voDemandaContrato::getNmTabela();
				
			// busca as demandas de prorrogacao pra esse contrato
			$queryExists .= "\n SELECT 'X' FROM  $nmTabelaDemanda";
			$queryExists .= "\n INNER JOIN $nmTabelaDemandaContratoTA ";
			$queryExists .= "\n ON ";
			$queryExists .= $nmTabelaDemandaContratoTA . "." . voDemandaContrato::$nmAtrAnoDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
			$queryExists .= "\n AND ";
			$queryExists .= $nmTabelaDemandaContratoTA . "." . voDemandaContrato::$nmAtrCdDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;

			$queryExists .= " WHERE ";
			//demanda de prorrogacao
			$queryExists .= getSQLBuscarStringCampoSeparador ( dominioTipoDemandaContrato::$CD_TIPO_PRORROGACAO, "$nmTabelaDemanda." . voDemanda::$nmAtrTpDemandaContrato, constantes::$CD_OPCAO_AND );
			//demandas que surgiram apos a assinatura do contrato atual
			$queryExists .= "\n AND ";
			$queryExists .= $nmTabelaDemanda . "." . voDemanda::$nmAtrDtReferencia . ">" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrDtAssinaturaContrato;
			$queryExists .= "\n AND ";
			$queryExists .= $nmTabelaDemandaContratoTA . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrAnoContrato;
			$queryExists .= "\n AND ";
			$queryExists .= $nmTabelaDemandaContratoTA . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrTipoContrato;
			$queryExists .= "\n AND ";
			$queryExists .= $nmTabelaDemandaContratoTA . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabContratoATUAL . "." . vocontrato::$nmAtrCdContrato;
			$queryExists .= "\n AND ";
			$queryExists .= $nmTabelaDemandaContratoTA . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "='" . dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_TERMOADITIVO . "' ";
			//e que sejam diferentes da propria demanda do contrato atual
			$queryExists .= "\n AND ";
			$queryExists .= "$nmTabDemandaContratoATUAL." . voDemandaContrato::$nmAtrAnoDemanda . "<> $nmTabelaDemandaContratoTA." . voDemandaContrato::$nmAtrAnoDemanda;
			$queryExists .= "\n AND ";
			$queryExists .= "$nmTabDemandaContratoATUAL." . voDemandaContrato::$nmAtrCdDemanda . "<> $nmTabelaDemandaContratoTA." . voDemandaContrato::$nmAtrCdDemanda;

			$operadorTemp = "EXISTS";
			if($inTemDemandaProrrogacao == 'N'){
				$operadorTemp = "NOT EXISTS";
			}
			$filtro = $filtro . $conector . " $operadorTemp ($queryExists)\n";
				
			//echoo ($filtro);

			$conector  = "\n AND ";
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
				$filtro = $filtro . $conector . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrInPrazoProrrogacao . " IS NULL ";
			}else{
				$filtro = $filtro . $conector . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrInPrazoProrrogacao . " = " . getVarComoNumero($this->inPrazoProrrogacao);
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

		if ($this->gestor != null) {
			$arrayAtributos = array(
					filtroManterContrato::$NM_TAB_PESSOA_GESTOR . "." . vopessoa::$nmAtrNome,
					filtroConsultarContratoConsolidacao::$NmTabContratoATUAL . "." . vocontrato::$nmAtrGestorContrato,
			);
			$nmAtributo = getSQLCOALESCE($arrayAtributos);
			$filtro = $filtro . $conector . " $nmAtributo LIKE '%" . utf8_encode ( $this->gestor ) . "%'";

			$conector = "\n AND ";
		}

		if ($this->orgaoGestor != null) {
			$nmTabelaOrgaoGestor = vogestor::getNmTabela();
			$nmAtributo = "$nmTabelaOrgaoGestor." . vogestor::$nmAtrDescricao;
			$filtro = $filtro . $conector . " $nmAtributo LIKE '%" . utf8_encode ( $this->orgaoGestor ) . "%'";
		
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

		if ($this->anoAssinatura != null) {
			$anoTemp = $this->anoAssinatura;
		
			$this->dtAssinaturaFinal = "31/12/$anoTemp";
			$this->dtAssinaturaInicial = "01/01/$anoTemp";
		}
		
		if ($this->dtAssinaturaInicial != null) {
			$colunaAComparar = "$nmTabContratoATUAL." . vocontrato::$nmAtrDtAssinaturaContrato;		
			$sqlFiltroTemp = getSQLConsultaIntervaloData($colunaAComparar, $this->dtAssinaturaInicial, ">=");
			$filtro = $filtro . $conector . $sqlFiltroTemp;
				
			$conector = "\n AND ";
		}
		
		if ($this->dtAssinaturaFinal != null) {
			$colunaAComparar = "$nmTabContratoATUAL." . vocontrato::$nmAtrDtAssinaturaContrato;
			$sqlFiltroTemp = getSQLConsultaIntervaloData($colunaAComparar, $this->dtAssinaturaFinal, "<=");
			$filtro = $filtro . $conector . $sqlFiltroTemp;
		
			$conector = "\n AND ";
		}
		
		
		$sqlSubstituicaoJoinContratoATUAL = "";
		if ($this->dtFimVigenciaInicial != null) {
			//$colunaAComparar = static::getComparacaoWhereDataVigencia(static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato);
			$colunaAComparar = $colunaACompararDtFim;
				
			$sqlFiltroTemp = getSQLConsultaIntervaloData($colunaAComparar, $this->dtFimVigenciaInicial, ">=");
			//$sqlFiltroTemp = "($colunaAComparar IS NOT NULL AND $colunaAComparar >= " . getVarComoData($this->dtFimVigenciaInicial) . ") ";
			$filtro = $filtro . $conector . $sqlFiltroTemp;
				
			//vai sem o nome da tabela porque o filtro abaixo vai ser utilizado na tabela interna do maior SQ no join
			//[ATENCAO: COMENTOU O TRECHO ABAIXO APOS A MUDANCA COM DATA FIM VIGENCIA CONSIDERANDO ORDEM DE PARALISACAO]: checar se isso tratá algum impacto
			//$sqlSubstituicaoJoinContratoATUAL .= " AND " . getSQLConsultaIntervaloData(vocontrato::$nmAtrDtVigenciaFinalContrato, $this->dtFimVigenciaInicial, ">=");

			$conector = "\n AND ";
		}

		if ($this->dtFimVigenciaFinal != null) {
			//$colunaAComparar = static::getComparacaoWhereDataVigencia(static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato);
			$colunaAComparar = $colunaACompararDtFim;
			$sqlFiltroTemp = getSQLConsultaIntervaloData($colunaAComparar, $this->dtFimVigenciaFinal, "<=");
			//$sqlFiltroTemp = "($colunaAComparar IS NOT NULL AND $colunaAComparar <= " . getVarComoData($this->dtFimVigenciaFinal) . ") ";
			$filtro = $filtro . $conector . $sqlFiltroTemp;
				
			//$sqlSubstituicaoJoinContratoATUAL .= " AND " . getSQLConsultaIntervaloData(vocontrato::$nmAtrDtVigenciaFinalContrato, $this->dtFimVigenciaFinal, "<=");

			$conector = "\n AND ";
		}

		if ($this->dtVigencia != null) {
			$pChaveTuplaComparacaoSemSequencial = array(
					vocontrato::$nmAtrCdContrato
					,vocontrato::$nmAtrAnoContrato
					, vocontrato::$nmAtrTipoContrato);

			$pArrayParam = array(
					vocontrato::getNmTabela(),
					vocontrato::$nmAtrSqContrato,
					$pChaveTuplaComparacaoSemSequencial,
					$pChaveTuplaComparacaoSemSequencial,
					$this->dtVigencia,
					static::$NmTabContratoMater . "." .vocontrato::$nmAtrDtVigenciaInicialContrato,
					$colunaACompararDtFim,
					true,
					$filtro,
					false,

			);

			$filtro = $filtro . $conector . getSQLDataVigenteArrayParam($pArrayParam);

			//echo $filtro;
			$conector = "\n AND ";
				
			//so permite busca pelo tipo de vigencia se a data de vigencia for nula
			$this->tpVigencia = "";
		}else{
			//so permite busca pelo tipo de vigencia se a data de vigencia for nula
			if (isAtributoValido($this->tpVigencia)) {
				$nmcoldtinicioTemp = vocontrato::$nmAtrDtVigenciaInicialContrato;
				$nmcoldtfimTemp = vocontrato::$nmAtrDtVigenciaFinalContrato;
					
				$nmcoldtinicio = static::$NmTabContratoMater . "." . $nmcoldtinicioTemp;
				//$nmcoldtfim = static::$NmTabContratoATUAL . "." . $nmcoldtfimTemp;
				$nmcoldtfim = $colunaACompararDtFim;
					
				if ($this->tpVigencia == dominioTpVigencia::$CD_OPCAO_VIGENTES) {
					$filtro = $filtro . $conector . getSQLDataVigenteSqSimples ( null, $nmcoldtinicio, $nmcoldtfim );
					//$sqlSubstituicaoJoinContratoATUAL .= " AND ". getSQLDataVigenteSqSimples ( null, $nmcoldtinicioTemp, $nmcoldtfimTemp );
				} else {
					$filtro = $filtro . $conector . getSQLDataNaoVigenteSqSimples ( null, $nmcoldtinicio, $nmcoldtfim );
					//$sqlSubstituicaoJoinContratoATUAL .= " AND ". getSQLDataNaoVigenteSqSimples ( null, $nmcoldtinicioTemp, $nmcoldtfimTemp );
				}

				//o filtro de vigencia deve ter o mesmo filtro utilizado no join em dbcontratoinfo, para nao dar conflito com zero resultado
				$filtro = $filtro . $conector . $this->getSQLTermoMaiorSqVigencia(static::$NmTabContratoATUAL, false);
					
				$conector = "\n AND ";
			}
		}
			
		//substitui $CD_CAMPO_SUBSTITUICAO feito desde a confeccao do join da consulta em dbcontratoinfo
		$this->setSQLFiltrosASubstituir($sqlSubstituicaoJoinContratoATUAL);

		//como a consulta nao vê historico, os contratos so servem se ativados
		$filtro = $filtro . $conector . "$nmTabelaContrato." .vocontrato::$nmAtrInDesativado . "='N' ";

		//retira os contratos CANCELADOS
		//$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrEspecieContrato . " NOT LIKE '%CANCELADO%'";

		$this->formataCampoOrdenacao ( new voContratoInfo());
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );

		// echo "Filtro:$filtro<br>";

		return $filtro;
	}

	function getSQLTermoMaiorSqVigencia($nmTabContrato, $isTabInterna = true){
		$comWhere = $isTabInterna;

		$retorno = "";
		//pega o contrato atual (termo atual), maior sequencial, desde que a data final de vigencia nao seja nula ou '0000-00-00'
		if($isTabInterna){
			//para o caso de ser filtro de tabela interna
			$nmColunaDtFimComparacao = "$nmTabContrato." . vocontrato::$nmAtrDtVigenciaFinalContrato;
		}else{
			//para o caso da consulta se referir ao filtro da tela de chamada, sempre deverá ser o campo abaixo
			$nmColunaDtFimComparacao = static::getAtributoDtFimVigenciaConsolidacao();
		}

		$where = "WHERE";
		if(!$comWhere){
			$where = "";
		}
		
		//$retorno = " $where ($nmColunaDtFimComparacao IS NOT NULL AND $nmColunaDtFimComparacao <> '0000-00-00')";
		
		//deve ser permitida data fim nula na tabela interna, posto que ela eh quem traz os contratos que serao comparados
		//ja na tabela externa, eh onde se restringe a exibicao, permitindo apenas data fim nula para contrato COVID
		if(!$isTabInterna){
			//por enquanto so permite data fim nula para contratos CLASSIFICADOS como COVID
			$nmAtributoInProrrogacao = voContratoInfo::$nmAtrInPrazoProrrogacao;
			$valorAtributoInProrrogacao = dominioProrrogacaoContrato::$CD_COVID;
				
			$sqlPermiteDataFimNula = "AND $nmAtributoInProrrogacao = $valorAtributoInProrrogacao";
		}
		$retorno = " $where (($nmColunaDtFimComparacao IS NOT NULL AND $nmColunaDtFimComparacao <> '0000-00-00')"
				." OR ($nmColunaDtFimComparacao IS NULL $sqlPermiteDataFimNula))";
		

		$isProduzindoEfeitosSelecionado = $this->inProduzindoEfeitos != null;
		$inProduzindoEfeitos = $this->inProduzindoEfeitos;
		if ($isProduzindoEfeitosSelecionado) {
			$nmColunaComparacao = "$nmTabContrato." . vocontrato::$nmAtrDtPublicacaoContrato;
			//pega o contrato atual (termo atual), de maior sequencial, desde que tenha sido publicado, provocando efeitos
			//so vai consultar se for SIM, caso contrario, traz o ultimo registro, independente de ter sido publicado ou nao.
			if($inProduzindoEfeitos == dominioContratoProducaoEfeitos::$CD_VISTO_COM_EFEITOS){
				$retorno .= " AND ($nmColunaComparacao IS NOT NULL AND $nmColunaComparacao <> '0000-00-00')";
			}else if($inProduzindoEfeitos == dominioContratoProducaoEfeitos::$CD_VISTO_SEM_EFEITOS){
				$retorno .= " AND ($nmColunaComparacao IS NULL OR $nmColunaComparacao = '0000-00-00')";
			}
				
		}

		if($this->cdEspecie != null){
			$retorno .= " AND " . $this->getSQFiltroCdEspecie($nmTabContrato);
		}

		return $retorno;
	}

	/**
	 * Traz  o sql que permite calcular o periodo de vigencia total de um contrato
	 * @return string
	 */
	static function getSQLQtdAnosVigenciaContrato(){
		//$colunaAComparar = static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato;
		$colunaAComparar = static::getAtributoDtFimVigenciaConsolidacao();
		return getDataSQLDiferencaAnos(static::$NmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato, $colunaAComparar);
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

	static function getAtributoDtFimVigenciaConsolidacao(){
		$nmTabContratoATUAL = static::$NmTabContratoATUAL;
		//$nmTabDadosOrdemParalisacao = static::$NmTABDadosOrdemParalisacao;

		$nmTempAtributoDtVigenciaFim = $nmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato;
		//passa a considerar a mudanca de vigencia devido a ordens de paralisacao
		/*$arrayAtributosCoalesceOP[] = "$nmTabDadosOrdemParalisacao." . filtroConsultarContratoConsolidacao::$NmColDtFimVigenciaOP;
		 $arrayAtributosCoalesceOP[] = $nmTempAtributoDtVigenciaFim;
		 $nmTempAtributoDtVigenciaFim = getSQLCOALESCE($arrayAtributosCoalesceOP);*/

		$nmTempAtributoDtVigenciaFim = filtroConsultarContratoConsolidacao::getComparacaoWhereDataVigencia($nmTempAtributoDtVigenciaFim);

		return $nmTempAtributoDtVigenciaFim;
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
				//static::$NmColDtFimVigencia => "Fim.Vigencia",
				static::getAtributoDtFimVigenciaConsolidacao() => "Fim.Vigencia",
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
				//voDemanda::$nmAtrAno => static::$DS_DEMANDA,
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
				//voDemanda::$nmAtrAno => filtroConsultarContratoConsolidacao::getAtributoConsultaTemDemanda("$nmTabelaDemanda." . voDemanda::$nmAtrAno),
		);

		return $retorno;
	}

	/**
	 * retorna os atributos que serao totalizados
	 */
	static function getAtributosValoresTotalizados(){
		$nmTABContratoAtual = filtroConsultarContratoConsolidacao::$NmTabContratoATUAL;
		//return array("SUM($nmTABContratoAtual.".vocontrato::$nmAtrVlMensalContrato. ")", "SUM($nmTABContratoAtual.".vocontrato::$nmAtrVlGlobalContrato . ")");
		return array("$nmTABContratoAtual.".vocontrato::$nmAtrVlMensalContrato, "$nmTABContratoAtual.".vocontrato::$nmAtrVlGlobalContrato);
	}

	static function getArrayColunasExportarPlanilha(){
		$colecaoAtributos[] = new colunaPlanilha("Tipo", vocontrato::$nmAtrTipoContrato, colunaPlanilha::$TP_DADO_DOMINIO, "dominioTipoContrato");
		$colecaoAtributos[] = new colunaPlanilha("Contrato", vocontrato::$nmAtrCdContrato);
		$colecaoAtributos[] = new colunaPlanilha("Ano", vocontrato::$nmAtrAnoContrato);
		$colecaoAtributos[] = new colunaPlanilha("Especie", static::$NmColCdEspecieContratoAtual, colunaPlanilha::$TP_DADO_DOMINIO, "dominioEspeciesContrato");
		$colecaoAtributos[] = new colunaPlanilha("Num.", static::$NmColSqEspecieContratoAtual); 
		$colecaoAtributos[] = new colunaPlanilha("Objeto", vocontrato::$nmAtrObjetoContrato);
		$colecaoAtributos[] = new colunaPlanilha("Gestor", vocontrato::$nmAtrGestorContrato);
		$colecaoAtributos[] = new colunaPlanilha("Inicio", filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia);
		$colecaoAtributos[] = new colunaPlanilha("Fim", filtroConsultarContratoConsolidacao::$NmColDtFimVigencia);
		$colecaoAtributos[] = new colunaPlanilha("Vl.Mensal", vocontrato::$nmAtrVlMensalContrato, colunaPlanilha::$TP_DADO_MOEDA);
		$colecaoAtributos[] = new colunaPlanilha("Vl.Global", vocontrato::$nmAtrVlGlobalContrato, colunaPlanilha::$TP_DADO_MOEDA);		
		//$colecaoAtributos[] = new colunaPlanilha("SEI", voDemanda::$nmAtrProtocolo, colunaPlanilha::$TP_DADO_STRING);
		$colunaPlanilhaTemp = new colunaPlanilha("SEI", voDemanda::$nmAtrProtocolo);
		$colunaPlanilhaTemp->funcaoAExecutar = "getNumeroProtocoloFormatado"; 
		$colecaoAtributos[] = $colunaPlanilhaTemp;

		return $colecaoAtributos;
	}

}
