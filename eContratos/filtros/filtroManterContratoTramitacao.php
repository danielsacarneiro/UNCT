<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voContratoTramitacao.php");

class filtroManterContratoTramitacao extends filtroManter{

	public $nmFiltro = "filtroManterContratoTramitacao";
	static $nmAtrNmContratada = "nmContratada"; 
	static $nmAtrDocContratada = "docContratada";
	
	var $sq = "";
	var $cdContrato;
	var $anoContrato;
	var $tipoContrato;
	var $cdEspecieContrato;
	
	var $nmContratada;
	var $docContratada;
	// ...............................................................
	// construtor
	function __construct() {
		parent::__construct(true);
		
		$this->sq = @$_POST[voContratoTramitacao::$nmAtrSq];
		$this->cdContrato = @$_POST[voContratoTramitacao::$nmAtrCdContrato];
		$this->anoContrato = @$_POST[voContratoTramitacao::$nmAtrAnoContrato];
		$this->tipoContrato= @$_POST[voContratoTramitacao::$nmAtrTipoContrato];
		$this->cdEspecieContrato = @$_POST[vocontrato::$nmAtrCdEspecieContrato];

		$this->nmContratada = @$_POST[self::$nmAtrNmContratada];
		$this->docContratada = @$_POST[self::$nmAtrDocContratada];
				
		if($this->cdOrdenacao == null){
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}
	}
	 
	function getFiltroConsultaSQL($isHistorico){
		$voContratoTramitacao= new voContratoTramitacao();
		$filtro = "";
		$conector  = "";

		$nmTabela = $voContratoTramitacao->getNmTabelaEntidade($isHistorico);
		$nmTabelaContrato = vocontrato::getNmTabela();
		$nmTabelaPessoa = vopessoa::getNmTabela();

		//seta os filtros obrigatorios
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
			//echo "setou o ano defaul";
			;
		}

		if($this->sq != null){
			$filtro = $filtro . $conector
				. $nmTabela. "." .voContratoTramitacao::$nmAtrSq
				. " = "
				. $this->sq
				;
						
			$conector  = "\n AND ";

		}

		if($this->cdContrato != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voContratoTramitacao::$nmAtrCdContrato
			. " = "
					. $this->cdContrato
					;
		
					$conector  = "\n AND ";
		
		}
		
		if($this->anoContrato != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voContratoTramitacao::$nmAtrAnoContrato
			. " = "
					. $this->anoContrato
					;

					$conector  = "\n AND ";

		}

		if($this->tipoContrato != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voContratoTramitacao::$nmAtrTipoContrato
			. " = '"
					. $this->tipoContrato
					. "'"
					;

			$conector  = "\n AND ";

		}

		if($this->cdEspecieContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaContrato. "." .vocontrato::$nmAtrCdEspecieContrato
			. " = '"
					. $this->cdEspecieContrato
					. "'"
					;
		
			$conector  = "\n AND ";
		
		}
		
		if($this->nmContratada != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoa. "." .vopessoa::$nmAtrNome
			. " LIKE '"
			. substituirCaracterSQLLike($this->nmContratada)
			. "'"
			;		
			$conector  = "\n AND ";
		
		}
		
		if($this->docContratada != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoa. "." .vopessoa::$nmAtrDoc
			. " = '"
					. substituirCaracterSQLLike($this->docContratada)
					. "'"
							;
							$conector  = "\n AND ";
		
		}
		
		//finaliza o filtro
		$filtro = parent::getFiltroConsultaSQL($filtro);

		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				voContratoTramitacao::$nmAtrDtReferencia=> "Data",
				voContratoTramitacao::getNmTabelaStatic($this->isHistorico) . "." . voContratoTramitacao::$nmAtrCdContrato => "Contrato",
				voContratoTramitacao::getNmTabelaStatic($this->isHistorico) . ".". voContratoTramitacao::$nmAtrAnoContrato => "Ano",
				voContratoTramitacao::getNmTabelaStatic($this->isHistorico) . ".". voContratoTramitacao::$nmAtrTipoContrato => "Tipo Contrato",
				voContratoTramitacao::$nmAtrSqIndice => "Tramitação"
		);
		return $varAtributos;
	}
	

}

?>