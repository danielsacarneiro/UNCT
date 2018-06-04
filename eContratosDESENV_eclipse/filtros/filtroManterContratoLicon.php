<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");

class filtroManterContratoLicon extends filtroManter {
	//public static $nmFiltro = "filtroManterContratoLicon";
	public $nmFiltro = "filtroManterContratoLicon";
	
	var $sq = "";	
	
	function getFiltroFormulario() {
		$this->sq = @$_POST [voDemanda::$nmAtrCd];
		
		if ($this->cdOrdenacao == null) {
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}		
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
		$filtro = "";
		$conector = "";
		
		$nmTabela = voContratoLicon::getNmTabelaStatic ( $this->isHistorico () );
		
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			// echo "setou o ano defaul";
			;
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
				voDemandaContrato::$nmAtrCdDemanda => "Cd.Demanda" 
		);
		return $varAtributos;
	}
}

?>