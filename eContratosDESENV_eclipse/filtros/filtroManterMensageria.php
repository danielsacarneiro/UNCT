<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");

class filtroManterMensageria extends filtroManter {
	//public static $nmFiltro = "filtroManterContratoLicon";
	public $nmFiltro = "filtroManterMensageria";
	
	static $NM_TAB_MSGREGISTRO_MAX_SQ = "NM_TAB_MSGREGISTRO_MAX_SQ";
	static $NM_TAB_GESTOR = "NM_TAB_PESSOAGESTOR";
	static $ID_REQ_NumMsgsEnviadas = "ID_REQ_NumMsgsEnviadas";
	
	var $cdContrato = "";
	var $anoContrato = "";
	var $tipoContrato = "";

	var $nmContratada = "";
	var $docContratada = "";
	
	var $inHabilitado = "";
	var $dtInicio = "";
	var $dtFim = "";
	var $inVerificarPeriodoVigente = null;	
	var $inVerificarFrequencia = null;
	var $inSeraProrrogado = "";
	var $numMsgsEnviadas = "";
	var $sq = "";
	
	function getFiltroFormulario() {
		
		$this->cdContrato = @$_POST [voMensageria::$nmAtrCdContrato];
		$this->anoContrato = @$_POST [voMensageria::$nmAtrAnoContrato];
		$this->tipoContrato = @$_POST [voMensageria::$nmAtrTipoContrato];
		$this->sq = @$_POST [voMensageria::$nmAtrSq];
		
		$this->nmContratada = @$_POST [vopessoa::$nmAtrNome];
		$this->docContratada = @$_POST [vopessoa::$nmAtrDoc];
		$this->inHabilitado = @$_POST [voMensageria::$nmAtrInHabilitado];
		$this->dtInicio = @$_POST [voMensageria::$nmAtrDtInicio];
		$this->dtFim = @$_POST [voMensageria::$nmAtrDtFim];
		$this->tpVigencia = @$_POST [static::$nmAtrTpVigencia];
		$this->inSeraProrrogado = @$_POST [voContratoInfo::$nmAtrInSeraProrrogado];
		$this->numMsgsEnviadas = @$_POST [static::$ID_REQ_NumMsgsEnviadas];
		
		if ($this->cdOrdenacao == null) {
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}		
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
		$filtro = "";
		$conector = "";
		
		$nmTabela = voMensageria::getNmTabelaStatic ( $this->isHistorico () );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaMsgRegistroMax = static::$NM_TAB_MSGREGISTRO_MAX_SQ;
		
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			// echo "setou o ano defaul";
			;
		}
				
		if ($this->sq != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voMensageria::$nmAtrSq . " = " . $this->sq;
		
			$conector = "\n AND ";
		}
		
		if ($this->anoContrato != null) {
				
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . " = " . $this->anoContrato;
				
			$conector = "\n AND ";
		}
		
		if ($this->cdContrato != null) {
				
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . " = " . $this->cdContrato;
				
			$conector = "\n AND ";
		}
		
		if ($this->tipoContrato != null) {
				
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . " = " . getVarComoString ( $this->tipoContrato );
				
			$conector = "\n AND ";
		}
		
		if ($this->inHabilitado != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voMensageria::$nmAtrInHabilitado . " = " . getVarComoString($this->inHabilitado);
		
			$conector = "\n AND ";
		}
		
		if ($this->dtInicio != null) {		
			$filtro = $filtro . $conector . "DATE($nmTabela." . voMensageria::$nmAtrDhInclusao . ") >= " . getVarComoData($this->dtInicio);		
			$conector = "\n AND ";
		}
		
		if ($this->dtFim != null) {
			$filtro = $filtro . $conector . "DATE($nmTabela." . voMensageria::$nmAtrDhInclusao . ") <= " . getVarComoData($this->dtFim);
			$conector = "\n AND ";
		}
		
		if ($this->dtVigencia != null) {		
			$filtro = $filtro . $conector . getSQLDataVigenteSimplesPorData($nmTabela, 
					$this->dtVigencia, 
					voMensageria::$nmAtrDtInicio, 
					voMensageria::$nmAtrDtFim);
				
			$conector = "\n AND ";
		}
		
		if (getAtributoComoBooleano($this->inVerificarPeriodoVigente)) {
			$dataHoje = getDataHoje();
			$filtro = $filtro . $conector . $nmTabela . "." . voMensageria::$nmAtrDtInicio . " <= " . getVarComoData($dataHoje);
			$conector = "\n AND ";
			$atributoComparar = "$nmTabela." . voMensageria::$nmAtrDtFim;
			$filtro = $filtro . $conector . "($atributoComparar IS NULL OR $atributoComparar >= " . getVarComoData($dataHoje) . ") ";
			$conector = "\n AND ";
		}
		
		if ($this->tpVigencia != null && $this->tpVigencia != constantes::$CD_OPCAO_TODOS) {
			if ($this->tpVigencia == dominioTpVigencia::$CD_OPCAO_VIGENTES) {
				/*$dataHoje = getDataHoje();
				$filtro = $filtro . $conector . $nmTabela . "." . voMensageria::$nmAtrDtInicio . " <= " . getVarComoData($dataHoje);
				$conector = "\n AND ";
				$atributoComparar = "$nmTabela." . voMensageria::$nmAtrDtFim;
				$filtro = $filtro . $conector . "($atributoComparar IS NULL OR $atributoComparar >= " . getVarComoData($dataHoje) . ") ";*/
				
				$filtro = $filtro . $conector . getSQLDataVigenteSqSimples($nmTabela, voMensageria::$nmAtrDtInicio, voMensageria::$nmAtrDtFim);				
			}else{
				$filtro = $filtro . $conector . getSQLDataNaoVigenteSqSimples($nmTabela, voMensageria::$nmAtrDtInicio, voMensageria::$nmAtrDtFim);
			}			
			$conector = "\n AND ";
		}
		
		if (getAtributoComoBooleano($this->inVerificarFrequencia)) {
			$filtro = $this->getDataComparacaoFrequenciaComDtUltimoEnvio($filtro, $conector);
			$conector = "\n AND ";
		}
		
		if ($this->nmContratada != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome . " LIKE '%" .  $this->nmContratada . "%'";
			$conector = "\n AND ";
		}
		
		if ($this->docContratada != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc . " = '" . documentoPessoa::getNumeroDocSemMascara ( $this->docContratada ) . "'";
			$conector = "\n AND ";
		}
		
		if (isAtributoValido($this->inSeraProrrogado)) {		
			$filtro = $filtro . $conector . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrInSeraProrrogado . " = " . getVarComoString ( $this->inSeraProrrogado );		
			$conector = "\n AND ";
		}
						
		if (isAtributoValido($this->numMsgsEnviadas)) {		
			$filtro = $filtro . $conector . $nmTabelaMsgRegistroMax . "." . voMensageriaRegistro::$nmAtrSq . " >= " . $this->numMsgsEnviadas;		
			$conector = "\n AND ";
		}
		
		$this->formataCampoOrdenacao ( new voMensageria());
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	
	static function getColunaDtUltimoEnvio(){
		$nmTabelaMsgRegistro = voMensageriaRegistro::getNmTabelaStatic ( false );
		return "$nmTabelaMsgRegistro." . voMensageriaRegistro::$nmAtrDhUltAlteracao;
	}
	
	function getDataComparacaoFrequenciaComDtUltimoEnvio($filtro, $conector){		
		$nmTabela = voMensageria::getNmTabelaStatic ( $this->isHistorico () );
		$nmTabelaMsgRegistro = voMensageriaRegistro::getNmTabelaStatic ( false );
	
		//$nmColunaDataAComparar = "$nmTabela." . voMensageria::$nmAtrDtInicio;
		$nmColunaDataAComparar = static::getColunaDtUltimoEnvio();		
		$nmColunaFrequencia = "$nmTabela." . voMensageria::$nmAtrNumDiasFrequencia;
		$dtParam = " NOW() ";
		$diferencaEntreDatas = getDataSQLDiferencaDias($nmColunaDataAComparar, $dtParam);
		//$fatorFrequencia = $restoDaDivisaoSQL = "(($diferencaEntreDatas) % $nmColunaFrequencia)=0" ;
		$fatorFrequencia = "($diferencaEntreDatas >= $nmColunaFrequencia)" ;
		
		//so trara as mensagerias cuja frequencia seja satisfeita a partir da diferenca entre a data inicio e a data atual			
		$filtro = $filtro . $conector . "($nmColunaDataAComparar IS NULL OR (DATE($nmColunaDataAComparar) <> DATE(NOW()) AND $fatorFrequencia))";	
		return $filtro;
	}
	
	function getAtributoOrdenacaoAnteriorDefault() {
		$nmTabela = voMensageria::getNmTabelaStatic ( $this->isHistorico );
		$retorno = $nmTabela . "." . voMensageria::$nmAtrSq . " " . $this->cdOrdenacao;
		return $retorno;
	}
	function getAtributoOrdenacaoDefault() {
		return voMensageria::getNmTabelaStatic ( $this->isHistorico ) . "." . voMensageria::$nmAtrSq . " " . constantes::$CD_ORDEM_DECRESCENTE;
	}
	function getAtributosOrdenacao() {
		$varAtributos = array (
				voMensageria::$nmAtrSq => "Sequencial",
				voMensageria::$nmAtrAnoContrato => "Ano.Contrato",
				voMensageria::$nmAtrCdContrato => "Cd.Contrato",
				voMensageria::$nmAtrDtInicio=> "Dt.Inicio"
		);
		return $varAtributos;
	}
}

?>