<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");
class filtroManterContratoInfo extends filtroManter {
	public $nmFiltro = "filtroManterContratoInfo";
	var $cdContrato = "";
	var $anoContrato = "";
	var $tipoContrato = "";
	var $nmContratada = "";
	var $docContratada = "";
	
	// ...............................................................
	function getFiltroFormulario() {
		$this->cdContrato = @$_POST [voContratoInfo::$nmAtrCdContrato];
		$this->anoContrato = @$_POST [voContratoInfo::$nmAtrAnoContrato];
		$this->tipoContrato = @$_POST [voContratoInfo::$nmAtrTipoContrato];
		
		$this->nmContratada = @$_POST [vopessoa::$nmAtrNome];
		$this->docContratada = @$_POST [vopessoa::$nmAtrDoc];
		
		if ($this->cdOrdenacao == null) {
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}
	}
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
		$filtro = "";
		$conector = "";
		
		$nmTabela = voContratoInfo::getNmTabelaStatic ( $this->isHistorico );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		/*$filtro = $filtro . $conector . $nmTabelaContrato . "." . voContrato::$nmAtrAnoContrato . " = " . $this->anoContrato;		
		$conector = "\n AND ";*/
		
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
		
		if ($this->nmContratada != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome . " LIKE '%" .  $this->nmContratada . "%'";
			$conector = "\n AND ";
		}
		
		if ($this->docContratada != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc . " = '" . documentoPessoa::getNumeroDocSemMascara ( $this->docContratada ) . "'";
			$conector = "\n AND ";
		}
		
		$this->formataCampoOrdenacao ( new voContratoInfo () );
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	
	/*
	 * function getAtributoOrdenacaoDefault(){
	 * $nmTabela = voContratoInfo::getNmTabelaStatic($this->isHistorico);
	 * $retorno = $nmTabela . "." . voContratoInfo::$nmAtrPrioridade . " " . constantes::$CD_ORDEM_CRESCENTE
	 * . "," . $nmTabelaDemanda . "." . voDemanda::$nmAtrDhInclusao . " " . constantes::$CD_ORDEM_DECRESCENTE;
	 * return $retorno;
	 * }
	 */
	function getAtributosOrdenacao() {
		$varAtributos = array (
				voContratoInfo::$nmAtrAnoContrato => "Ano",
				voContratoInfo::$nmAtrCdContrato => "Número",
				voContratoInfo::$nmAtrTipoContrato => "Tipo" 
		);
		return $varAtributos;
	}
}

?>