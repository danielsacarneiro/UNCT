<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");

class filtroManterContratoModificacao extends filtroManter {

	public $nmFiltro = "filtroManterContratoModificacao";
	
	static $NmColNumPercentualDisponivel = "NmColNumPercentualDisponivel";
	static $NmColVlGlobalMater = "NmColVlGlobalMater";
	static $NmColVlMensalMater = "NmColVlMensalMater";
	
	static $ID_REQ_SituacaoExceto = "ID_REQ_SituacaoExceto"; 
		
	var $vocontrato;

	var $nmContratada = "";
	var $docContratada = "";
	
	var $tipo = "";	
	var $dtModificacao = "";	
	
	function getFiltroFormulario() {
		$this->vocontrato = new vocontrato();		
		$this->vocontrato->cdContrato = @$_POST [voContratoModificacao::$nmAtrCdContrato];
		$this->vocontrato->anoContrato = @$_POST [voContratoModificacao::$nmAtrAnoContrato];
		$this->vocontrato->tipoContrato = @$_POST [voContratoModificacao::$nmAtrTipoContrato];
		$this->vocontrato->cdEspecie = @$_POST [voContratoModificacao::$nmAtrCdEspecieContrato];
		$this->vocontrato->sqEspecie = @$_POST [voContratoModificacao::$nmAtrSqEspecieContrato];
		
		$this->nmContratada = @$_POST [vopessoa::$nmAtrNome];
		$this->docContratada = @$_POST [vopessoa::$nmAtrDoc];
		$this->tipo = @$_POST [voContratoModificacao::$nmAtrTpModificacao];
		$this->dtModificacao = @$_POST [vocontrato::$nmAtrDtPublicacaoContrato];
		
		if ($this->cdOrdenacao == null) {
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}		
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
		$filtro = "";
		$conector = "";
		
		$nmTabela = voContratoModificacao::getNmTabelaStatic ( $this->isHistorico () );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			// echo "setou o ano defaul";
			;
		}
				
		if ($this->vocontrato->anoContrato != null) {
				
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoModificacao::$nmAtrAnoContrato . " = " . $this->vocontrato->anoContrato;
				
			$conector = "\n AND ";
		}
		
		if ($this->vocontrato->cdContrato != null) {
				
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoModificacao::$nmAtrCdContrato . " = " . $this->vocontrato->cdContrato;
				
			$conector = "\n AND ";
		}
		
		if ($this->vocontrato->tipoContrato != null) {
				
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoModificacao::$nmAtrTipoContrato . " = " . getVarComoString ( $this->vocontrato->tipoContrato );
				
			$conector = "\n AND ";
		}
		
		if ($this->vocontrato->cdEspecie != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoModificacao::$nmAtrCdEspecieContrato . " = " . getVarComoString ( $this->vocontrato->cdEspecie );
		
			$conector = "\n AND ";
		}
		
		if ($this->vocontrato->sqEspecie != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoModificacao::$nmAtrSqEspecieContrato . " = " . getVarComoNumero($this->vocontrato->sqEspecie);
		
			$conector = "\n AND ";
		}
						
		if ($this->tipo != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoModificacao::$nmAtrTpModificacao . " = " . getVarComoNumero($this->tipo);
		
			$conector = "\n AND ";
		}
		
		if ($this->dtModificacao != null) {
		
			$filtro = $filtro . $conector . $nmTabelaContrato . "." . voContratoModificacao::$nmAtrDtModificacao . " = " . getVarComoData($this->dtModificacao);
		
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
						
		$this->formataCampoOrdenacao ( new voContratoModificacao());
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	function getAtributoOrdenacaoAnteriorDefault() {
		$nmTabela = voContratoModificacao::getNmTabelaStatic ( $this->isHistorico );
		$retorno = $nmTabela . "." . voContratoModificacao::$nmAtrDhUltAlteracao . " " . $this->cdOrdenacao;
		return $retorno;
	}
	function getAtributoOrdenacaoDefault() {
		return voContratoModificacao::getNmTabelaStatic ( $this->isHistorico ) . "." . voContratoModificacao::$nmAtrDhUltAlteracao . " " . constantes::$CD_ORDEM_DECRESCENTE;
	}
	function getAtributosOrdenacao() {
		$varAtributos = array (
				voContratoModificacao::$nmAtrAnoContrato => "Ano",
				voContratoModificacao::$nmAtrDhUltAlteracao => "Data"
		);
		return $varAtributos;
	}
}

?>