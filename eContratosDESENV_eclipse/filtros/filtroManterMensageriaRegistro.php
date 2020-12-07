<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");

class filtroManterMensageriaRegistro extends filtroManter {
	//public static $nmFiltro = "filtroManterContratoLicon";
	public $nmFiltro = "filtroManterMensageriaRegistro";
	
	static $ID_REQ_DtReferenciaInicial = "ID_REQ_DtReferenciaInicial";
	static $ID_REQ_DtReferenciaFinal = "ID_REQ_DtReferenciaFinal";
	static $ID_REQ_NumEmailsEnviados = "ID_REQ_NumEmailsEnviados";
	
	var $cdContrato = "";
	var $anoContrato = "";
	var $tipoContrato = "";

	var $nmContratada = "";
	var $docContratada = "";
	
	var $inHabilitado = "";
	var $dtInicio = "";
	var $dtFim = "";
	var $sq = "";
	var $numEmailsEnviados = "";
	
	function getFiltroFormulario() {
		
		$this->cdContrato = @$_POST [voMensageria::$nmAtrCdContrato];
		$this->anoContrato = @$_POST [voMensageria::$nmAtrAnoContrato];
		$this->tipoContrato = @$_POST [voMensageria::$nmAtrTipoContrato];
		$this->sq = @$_POST [voMensageria::$nmAtrSq];
		
		$this->nmContratada = @$_POST [vopessoa::$nmAtrNome];
		$this->docContratada = @$_POST [vopessoa::$nmAtrDoc];
		$this->inHabilitado = @$_POST [voMensageria::$nmAtrInHabilitado];
		$this->dtInicio = @$_POST [static::$ID_REQ_DtReferenciaInicial];
		$this->dtFim = @$_POST [static::$ID_REQ_DtReferenciaFinal];
		$this->numEmailsEnviados = @$_POST [static::$ID_REQ_NumEmailsEnviados];
		
		if ($this->cdOrdenacao == null) {
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}		
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
		$filtro = "";
		$conector = "";
		
		$nmTabela = voMensageriaRegistro::getNmTabelaStatic ( false);
		$nmTabelaMensageria = voMensageria::getNmTabelaStatic ( $this->isHistorico () );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			// echo "setou o ano defaul";
			;
		}
				
		if ($this->sq != null) {
		
			$filtro = $filtro . $conector . $nmTabelaMensageria . "." . voMensageria::$nmAtrSq . " = " . $this->sq;
		
			$conector = "\n AND ";
		}
		
		if ($this->anoContrato != null) {
				
			$filtro = $filtro . $conector . $nmTabelaMensageria . "." . voMensageria::$nmAtrAnoContrato . " = " . $this->anoContrato;
				
			$conector = "\n AND ";
		}
		
		if ($this->cdContrato != null) {
				
			$filtro = $filtro . $conector . $nmTabelaMensageria . "." . voMensageria::$nmAtrCdContrato . " = " . $this->cdContrato;
				
			$conector = "\n AND ";
		}
		
		if ($this->tipoContrato != null) {
				
			$filtro = $filtro . $conector . $nmTabelaMensageria . "." . voMensageria::$nmAtrTipoContrato . " = " . getVarComoString ( $this->tipoContrato );
				
			$conector = "\n AND ";
		}
		
		if ($this->inHabilitado != null) {
		
			$filtro = $filtro . $conector . $nmTabelaMensageria . "." . voMensageria::$nmAtrInHabilitado . " = " . getVarComoString($this->inHabilitado);
		
			$conector = "\n AND ";
		}
		
		if ($this->dtInicio != null) {		
			$filtro = $filtro . $conector . "DATE($nmTabela." . voMensageriaRegistro::$nmAtrDhUltAlteracao . ") >= " . getVarComoData($this->dtInicio);		
			$conector = "\n AND ";
		}
		
		if ($this->dtFim != null) {
			$filtro = $filtro . $conector . "DATE($nmTabela." . voMensageriaRegistro::$nmAtrDhUltAlteracao . ") <= " . getVarComoData($this->dtFim);
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
						
		$this->formataCampoOrdenacao ( new voMensageria());
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	
	//serve para criar uma ordenacao independente de qualquer selecao de ordenacao feita pelo usuario
	function getAtributoOrdenacaoAnteriorDefault() {
		$nmTabela = voMensageriaRegistro::getNmTabelaStatic ( $this->isHistorico );
		return  "$nmTabela." . voMensageria::$nmAtrDhUltAlteracao . " " . $this->cdOrdenacao;
		return $retorno;
	}
	//serve para criar uma ordenacao default
	function getAtributoOrdenacaoDefault() {
		$nmTabela = voMensageriaRegistro::getNmTabelaStatic ( $this->isHistorico ); 
		return  "$nmTabela." . voMensageria::$nmAtrDhUltAlteracao . " " . constantes::$CD_ORDEM_DECRESCENTE;
	}
	function getAtributosOrdenacao() {
		$varAtributos = array (
				voMensageriaRegistro::$nmAtrDhUltAlteracao => "Dt.Envio",
				voMensageria::$nmAtrSq => "Sequencial",
				voMensageria::$nmAtrAnoContrato => "Ano.Contrato",
				voMensageria::$nmAtrCdContrato => "Cd.Contrato",
		);
		return $varAtributos;
	}
}

?>