<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");

class filtroManterMensageria extends filtroManter {
	//public static $nmFiltro = "filtroManterContratoLicon";
	public $nmFiltro = "filtroManterMensageria";
	
	var $cdContrato = "";
	var $anoContrato = "";
	var $tipoContrato = "";

	var $nmContratada = "";
	var $docContratada = "";
	
	var $inHabilitado = "";
	
	
	function getFiltroFormulario() {
		
		$this->cdContrato = @$_POST [voMensageria::$nmAtrCdContrato];
		$this->anoContrato = @$_POST [voMensageria::$nmAtrAnoContrato];
		$this->tipoContrato = @$_POST [voMensageria::$nmAtrTipoContrato];
		
		$this->nmContratada = @$_POST [vopessoa::$nmAtrNome];
		$this->docContratada = @$_POST [vopessoa::$nmAtrDoc];
		$this->inHabilitado = @$_POST [voMensageria::$nmAtrInHabilitado];
		
		if ($this->cdOrdenacao == null) {
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}		
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
		$filtro = "";
		$conector = "";
		
		$nmTabela = voMensageria::getNmTabelaStatic ( $this->isHistorico () );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			// echo "setou o ano defaul";
			;
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
				voMensageria::$nmAtrDtReferencia => "Data"
		);
		return $varAtributos;
	}
}

?>