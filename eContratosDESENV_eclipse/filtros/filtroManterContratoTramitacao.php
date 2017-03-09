<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voContratoTramitacao.php");

class filtroManterContratoTramitacao extends filtroManter{

	public static $nmFiltro = "filtroManterContratoTramitacao";

	var $sq = "";
	var $cdContrato;
	var $anoContrato;
	var $tipoContrato;
	
	// ...............................................................
	// construtor
	function __construct() {
		parent::__construct(true);
		
		$this->sq = @$_POST[voContratoTramitacao::$nmAtrSq];
		$this->cdContrato = @$_POST[voContratoTramitacao::$nmAtrCdContrato];
		$this->anoContrato = @$_POST[voContratoTramitacao::$nmAtrAnoContrato];
		$this->tipoContrato= @$_POST[voContratoTramitacao::$nmAtrTipoContrato];

		$this->nmEntidadePrincipal = (new voContratoTramitacao())->getNmClassVO();

		if($this->cdOrdenacao == null){
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}
	}
	 
	function getFiltroConsultaSQL($isHistorico){
		$voContratoTramitacao= new voContratoTramitacao();
		$filtro = "";
		$conector  = "";

		$nmTabela = $voContratoTramitacao->getNmTabelaEntidade($isHistorico);

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

		//finaliza o filtro
		$filtro = parent::getFiltroConsultaSQL($filtro);

		//echo "Filtro:$filtro<br>";

		return $filtro;
	}

}

?>