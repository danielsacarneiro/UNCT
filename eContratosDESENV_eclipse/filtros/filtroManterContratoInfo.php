<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");
class filtroManterContratoInfo extends filtroManter {
	public $nmFiltro = "filtroManterContratoInfo";
	static $NmAtrInOR_AND = "NmAtrInOR_AND";
	static $ID_REQ_InGestor= "ID_REQ_InGestor";
	static $NmColAutorizacao = "NmColAutorizacao";
	
	var $cdContrato = "";
	var $anoContrato = "";
	var $tipoContrato = "";
	var $nmContratada = "";
	var $docContratada = "";
	

	var $InOR_AND;	
	var $cdAutorizacao = "";
	var $inTemGarantia = "";
	var $tpGarantia = "";	
	
	var $cdClassificacao = "";
	var $inMaoDeObra = "";
	var $inGestor = "";
	var $objeto = "";
	var $obs = "";
	
	var $inPrazoProrrogacao = "";
		
	// ...............................................................
	function getFiltroFormulario() {
		$this->cdContrato = @$_POST [voContratoInfo::$nmAtrCdContrato];
		$this->anoContrato = @$_POST [voContratoInfo::$nmAtrAnoContrato];
		$this->tipoContrato = @$_POST [voContratoInfo::$nmAtrTipoContrato];
		
		$this->nmContratada = @$_POST [vopessoa::$nmAtrNome];
		$this->docContratada = @$_POST [vopessoa::$nmAtrDoc];
		$this->cdAutorizacao = @$_POST [voContratoInfo::$nmAtrCdAutorizacaoContrato];
		
		$this->inTemGarantia = @$_POST [voContratoInfo::$nmAtrInTemGarantia];
		$this->tpGarantia = @$_POST [voContratoInfo::$nmAtrTpGarantia];
		
		$this->cdClassificacao = @$_POST [voContratoInfo::$nmAtrCdClassificacao];
		$this->inMaoDeObra = @$_POST [voContratoInfo::$nmAtrInMaoDeObra];
		$this->inGestor = @$_POST [STATIC::$ID_REQ_InGestor];
		$this->objeto = @$_POST [vocontrato::$nmAtrObjetoContrato];
		$this->obs = @$_POST [voContratoInfo::$nmAtrObs];
		$this->inPrazoProrrogacao = @$_POST [voContratoInfo::$nmAtrInPrazoProrrogacao];
		
		$this->InOR_AND = @$_POST[self::$NmAtrInOR_AND];
		if($this->InOR_AND == null){
			$this->InOR_AND = constantes::$CD_OPCAO_OR;
		}		
		
		if ($this->cdOrdenacao == null) {
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}
	}
	
	function getSqlAtributoCoalesceAutorizacao(){
		return 	"COALESCE (" . voContratoInfo::getNmTabelaStatic ( $this->isHistorico ) . "." . voContratoInfo::$nmAtrCdAutorizacaoContrato
		. "," . vocontrato::getNmTabelaStatic ( false ) . "." . vocontrato::$nmAtrCdAutorizacaoContrato . ")";	
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
				
		if (isAtributoValido($this->inTemGarantia)) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrInTemGarantia . " = " . getVarComoString ( $this->inTemGarantia );
		
			$conector = "\n AND ";
		}
		
		if ($this->tpGarantia != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrTpGarantia . " = " . getVarComoNumero($this->tpGarantia);
		
			$conector = "\n AND ";
		}
		
		if ($this->inMaoDeObra != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrInMaoDeObra . " = " . getVarComoString ( $this->inMaoDeObra );
		
			$conector = "\n AND ";
		}
		
		if (isAtributoValido($this->inGestor)) {
			$temp = $this->inGestor == constantes::$CD_SIM? " IS NOT NULL ": " IS NULL "; 
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrCdPessoaGestor . $temp;
		
			$conector = "\n AND ";
		}
		
		if ($this->cdClassificacao != null) {
		
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrCdClassificacao . " = " . getVarComoNumero($this->cdClassificacao);
		
			$conector = "\n AND ";
		}
				
		if($this->cdAutorizacao != null){
			$strComparacao = $this->getSqlAtributoCoalesceAutorizacao();
						
					if(!is_array($this->cdAutorizacao)){
						$filtro = $filtro . $conector
						//. $nmTabelaContrato. "." .vocontrato::$nmAtrCdAutorizacaoContrato
						. $strComparacao
						. " = "
								. $this->cdAutorizacao
								;
					}else{
		
						$colecaoAutorizacao = $this->cdAutorizacao;
						$filtro = $filtro . $conector . $strComparacao . voContratoInfo::getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutorizacao, $this->InOR_AND);
						//echo $strComparacao . voContratoInfo::getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutorizacao, $this->InOR_AND);
		
					}
						
					$conector  = "\n AND ";
		}
		
		if ($this->objeto != null) {
			$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrObjetoContrato . " LIKE '%" . utf8_encode ( $this->objeto ) . "%'";
		
			$conector = "\n AND ";
		}
				
		if ($this->obs != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrObs . " LIKE '%" . utf8_encode ( $this->obs ) . "%'";
		
			$conector = "\n AND ";
		}
		
		if ($this->inPrazoProrrogacao != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . voContratoInfo::$nmAtrInPrazoProrrogacao . " = " . getVarComoNumero($this->inPrazoProrrogacao);		
			$conector = "\n AND ";
		}
		
		$this->formataCampoOrdenacao ( new voContratoInfo () );
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		//echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	
	
	function getAtributoOrdenacaoDefault(){
	  $nmTabela = voContratoInfo::getNmTabelaStatic($this->isHistorico);
	  $retorno = $nmTabela . "." . voContratoInfo::$nmAtrAnoContrato . " " . $this->cdOrdenacao
	  . "," . $nmTabela . "." . voContratoInfo::$nmAtrTipoContrato . " " . $this->cdOrdenacao
	  . "," . $nmTabela . "." . voContratoInfo::$nmAtrCdContrato . " " . $this->cdOrdenacao;
	  
	  return $retorno;
	}
	 
	function getAtributosOrdenacao() {
		$nmTabela = voContratoInfo::getNmTabelaStatic($this->isHistorico);
		
		$varAtributos = array (
				voContratoInfo::$nmAtrAnoContrato => "Ano",
				voContratoInfo::$nmAtrCdContrato => "Número",
				voContratoInfo::$nmAtrTipoContrato => "Tipo",
				"$nmTabela." . voContratoInfo::$nmAtrDhUltAlteracao => "Data Alteração"
		);
		return $varAtributos;
	}
}

?>