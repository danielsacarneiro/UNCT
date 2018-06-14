<?php
include_once (caminho_util . "bibliotecaSQL.php");
class filtroConsultarContratoConsolidacao extends filtroManterContratoInfo {
	public $nmFiltro = "filtroConsultarContratoConsolidacao";
	
	static $NmColDtInicioVigencia = "NmColDtInicioVigencia";
	static $NmColQtdDiasParaVencimento = "NmColQtdDiasParaVencimento";
	static $NmColDtFimVigencia = "NmColDtFimVigencia";
	static $NmColSqContratoMater = "NmColSqContratoMater";
	static $NmColSqContratoAtual = "NmColSqContratoAtual";
	static $NmTabContratoMater = "TAB_CONTRATO_MATER";
	static $NmTabContratoATUAL = "TAB_CONTRATO_ATUAL";
	
	static $nmAtrQtdDiasParaVencimento = "nmAtrQtdDiasParaVencimento";
	static $nmAtrInIMProrrogavel = "nmAtrInIMProrrogavel";
	
	static $ID_REQ_DtFimVigenciaInicial = "ID_REQ_DtFimVigenciaInicial";
	static $ID_REQ_DtFimVigenciaFinal = "ID_REQ_DtFimVigenciaFinal";
	
	var $cdEspecie = "";
	var $qtdDiasParaVencimento = "";
	var $inIMProrrogavel = "";
	var $dtFimVigenciaInicial = "";
	var $dtFimVigenciaFinal = "";
		
	function __construct1($pegarFiltrosDaTela) {
		parent::__construct1($pegarFiltrosDaTela);
		$this->cdEspecie = array(dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER, dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_TERMOADITIVO);		
		//echo "chamou construtor ok";
	}	
	
	function getFiltroFormulario() {
		parent::getFiltroFormulario ();
		$this->cdEspecie = @$_POST [vocontrato::$nmAtrCdEspecieContrato];
		$this->qtdDiasParaVencimento = @$_POST [static::$nmAtrQtdDiasParaVencimento];
		$this->inIMProrrogavel = @$_POST [static::$nmAtrInIMProrrogavel];
		$this->dtFimVigenciaFinal = @$_POST [static::$ID_REQ_DtFimVigenciaFinal];
		$this->dtFimVigenciaInicial = @$_POST [static::$ID_REQ_DtFimVigenciaInicial];
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
		
		$nmTabela = voContratoInfo::getNmTabelaStatic ( $this->isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
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
		
		if ($this->inIMProrrogavel != null && $this->inIMProrrogavel != "") {
			$nmAtributoDataNotificacao = getDataSQLDiferencaAnos(static::$NmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato, static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato);
			
			if(constantes::$CD_NAO == $this->inIMProrrogavel){
				$filtro = $filtro . $conector . "$nmAtributoDataNotificacao >=0 AND $nmAtributoDataNotificacao < 4";
			}else{			
				$filtro = $filtro . $conector . "$nmAtributoDataNotificacao >=0 AND $nmAtributoDataNotificacao >= 4";
			}
				
			$conector = "\n AND ";
		}
		
		if ($this->cdEspecie != null && ! $this->isAtributoArrayVazio ( $this->cdEspecie )) {
			$filtro = $filtro . $conector . $this->getSQFiltroCdEspecie ( $nmTabelaContrato );
			$conector = "\n AND ";
		}
		
		if ($this->tpVigencia != null && $this->tpVigencia != constantes::$CD_OPCAO_TODOS) {
			$nmcoldtinicio = static::$NmTabContratoMater . "." . vocontrato::$nmAtrDtVigenciaInicialContrato;
			$nmcoldtfim = static::$NmTabContratoATUAL . "." . vocontrato::$nmAtrDtVigenciaFinalContrato;
							
			if ($this->tpVigencia == dominioTpVigencia::$CD_OPCAO_VIGENTES) {
				$filtro = $filtro . $conector . getSQLDataVigenteSqSimples ( null, $nmcoldtinicio, $nmcoldtfim );
			} else {
				$filtro = $filtro . $conector . getSQLDataNaoVigenteSqSimples ( null, $nmcoldtinicio, $nmcoldtfim );
			}
			
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
			$filtro = $filtro . $conector . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome . " LIKE '%" . $this->nmContratada . "%'";
			$conector = "\n AND ";
		}
		
		if ($this->docContratada != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc . " = '" . documentoPessoa::getNumeroDocSemMascara ( $this->docContratada ) . "'";
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
		
		
		$this->formataCampoOrdenacao ( new voContratoInfo () );
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	
	static function getComparacaoWhereDataVigencia($nmAtributoTabela){
		return getSQLCASE($nmAtributoTabela
						, '0000-00-00'						
						, 'NULL'
						, $nmAtributoTabela);
	}
	
	function getAtributoOrdenacaoDefault() {
		$nmTabela = voContrato::getNmTabelaStatic ( $this->isHistorico );
		$retorno = $nmTabela . "." . voContrato::$nmAtrTipoContrato . " " . $this->cdOrdenacao 
		. "," . $nmTabela . "." . voContrato::$nmAtrAnoContrato . " " . $this->cdOrdenacao
		. "," . $nmTabela . "." . voContrato::$nmAtrCdContrato . " " . $this->cdOrdenacao;
		return $retorno;
	}
	function getAtributosOrdenacao() {
		$varAtributos = array (
				voContrato::$nmAtrAnoContrato => "Ano",
				voContrato::$nmAtrCdContrato => "Número",
				voContrato::$nmAtrTipoContrato => "Tipo", 
		);
		return $varAtributos;
	}
}
