<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");

class filtroManterContratoLicon extends filtroManter {
	//public static $nmFiltro = "filtroManterContratoLicon";
	public $nmFiltro = "filtroManterContratoLicon";
	
	var $cdDemanda = "";
	var $anoDemanda = "";
	
	var $cdContrato = "";
	var $anoContrato = "";
	var $tipoContrato = "";
	var $nmContratada = "";
	var $docContratada = "";
	
	var $situacao = "";
	
	
	function getFiltroFormulario() {
		$this->anoDemanda = @$_POST [voContratoLicon::$nmAtrAnoDemanda];
		$this->cdDemanda = @$_POST [voContratoLicon::$nmAtrCdDemanda];
		
		$this->cdContrato = @$_POST [voContratoLicon::$nmAtrCdContrato];
		$this->anoContrato = @$_POST [voContratoLicon::$nmAtrAnoContrato];
		$this->tipoContrato = @$_POST [voContratoLicon::$nmAtrTipoContrato];
		
		$this->nmContratada = @$_POST [vopessoa::$nmAtrNome];
		$this->docContratada = @$_POST [vopessoa::$nmAtrDoc];
		$this->situacao = @$_POST [voContratoLicon::$nmAtrSituacao];
		
		if ($this->cdOrdenacao == null) {
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}		
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
		$filtro = "";
		$conector = "";
		
		$nmTabela = voContratoLicon::getNmTabelaStatic ( $this->isHistorico () );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			// echo "setou o ano defaul";
			;
		}
		
		if ($this->anoDemanda != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoLicon::$nmAtrAnoDemanda . " = " . $this->anoDemanda;
		
			$conector = "\n AND ";
		}
		
		if ($this->cdDemanda != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoLicon::$nmAtrCdDemanda . " = " . $this->cdDemanda;
		
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
		
		if ($this->situacao != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoLicon::$nmAtrSituacao . " = " . getVarComoNumero($this->situacao);
		
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
						
		$this->formataCampoOrdenacao ( new voContratoLicon () );
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	function getAtributoOrdenacaoAnteriorDefault() {
		$nmTabela = voContratoLicon::getNmTabelaStatic ( $this->isHistorico );
		$retorno = $nmTabela . "." . voContratoLicon::$nmAtrAnoDemanda . " " . $this->cdOrdenacao;
		return $retorno;
	}
	function getAtributoOrdenacaoDefault() {
		return voContratoLicon::getNmTabelaStatic ( $this->isHistorico ) . "." . voContratoLicon::$nmAtrDhUltAlteracao . " " . constantes::$CD_ORDEM_DECRESCENTE;
	}
	function getAtributosOrdenacao() {
		$varAtributos = array (
				voDemandaContrato::$nmAtrAnoDemanda => "Ano.Demanda",
				voDemandaContrato::$nmAtrCdDemanda => "Cd.Demanda",
				voDemandaContrato::$nmAtrDhUltAlteracao => "Data"
		);
		return $varAtributos;
	}
}

?>