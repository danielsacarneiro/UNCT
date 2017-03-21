<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voDemanda.php");

class filtroManterDemanda extends filtroManter{

	public $nmFiltro = "filtroManterDemanda";
	var $vodemanda;
	
	// ...............................................................
	// construtor
	function __construct() {
		parent::__construct(true);
		
		$vodemanda = new voDemanda();
		$vodemanda->cd  = @$_POST[voDemanda::$nmAtrCd];
		$vodemanda->ano  = @$_POST[voDemanda::$nmAtrAno];
		$vodemanda->cdSetor = @$_POST[voDemanda::$nmAtrCdSetor];
		$vodemanda->tipo = @$_POST[voDemanda::$nmAtrTipo];
		$vodemanda->situacao  = @$_POST[voDemanda::$nmAtrSituacao];
		
		$this->vodemanda = $vodemanda;
		
		if($this->cdOrdenacao == null){
			$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
		}
	}
	 
	function getFiltroConsultaSQL(){
		$filtro = "";
		$conector  = "";

		$nmTabela = voDemanda::getNmTabelaStatic($this->isHistorico);
					
		//seta os filtros obrigatorios
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
			//echo "setou o ano defaul";
			;
		}
		
		if($this->vodemanda->ano != null){
			
			$filtro = $filtro . $conector
				. $nmTabela. "." .voDemanda::$nmAtrAno
				. " = "
				. $this->vodemanda->ano
				;
						
			$conector  = "\n AND ";

		}

		if($this->vodemanda->cd != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrCd
			. " = "
					. $this->vodemanda->cd
					;
		
					$conector  = "\n AND ";		
		}
		
		if($this->vodemanda->situacao != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrSituacao
			. " = "
					. $this->vodemanda->situacao
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
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		$varAtributos = array(
				voDemanda::$nmAtrDhInclusao => "Data",
				$nmTabelaDemanda . "." . voDemanda::$nmAtrCd => "N�mero"
		);
		return $varAtributos;
	}
	

}

?>