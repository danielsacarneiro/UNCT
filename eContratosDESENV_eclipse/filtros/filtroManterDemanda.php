<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voDemandaTramitacao.php");

class filtroManterDemanda extends filtroManter{

	public $nmFiltro = "filtroManterDemanda";
	var $vodemanda;
	var $vocontrato;
	
	// ...............................................................
	// construtor
	function __construct() {
		parent::__construct(true);
		
		$vodemanda = new voDemandaTramitacao();
		$vocontrato = new vocontrato();
		
		$vodemanda->cd  = @$_POST[voDemanda::$nmAtrCd];
		$vodemanda->ano  = @$_POST[voDemanda::$nmAtrAno];
		$vodemanda->cdSetor = @$_POST[voDemanda::$nmAtrCdSetor];
		$vodemanda->cdSetorDestino = @$_POST[voDemandaTramitacao::$nmAtrCdSetorDestino];
		$vodemanda->tipo = @$_POST[voDemanda::$nmAtrTipo];
		$vodemanda->situacao  = @$_POST[voDemanda::$nmAtrSituacao];
		$vodemanda->prioridade  = @$_POST[voDemanda::$nmAtrPrioridade];
		
		$vocontrato->anoContrato = @$_POST[vocontrato::$nmAtrAnoContrato];
		$vocontrato->cdContrato = @$_POST[vocontrato::$nmAtrCdContrato];
		$vocontrato->tipo = @$_POST[vocontrato::$nmAtrTipoContrato];
		
		$this->vodemanda = $vodemanda;
		$this->vocontrato = $vocontrato;
		
		if($this->cdOrdenacao == null){
			$this->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
		}
	}
	 
	function getFiltroConsultaSQL(){
		$filtro = "";
		$conector  = "";

		$nmTabela = voDemanda::getNmTabelaStatic($this->isHistorico);
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabelaStatic(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
					
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
		
		if($this->vodemanda->cdSetor != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrCdSetor
			. " = "
					. $this->vodemanda->cdSetor
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vodemanda->cdSetorDestino != null){
			$filtro = $filtro . $conector
			. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrCdSetorDestino
			. " = "
					. $this->vodemanda->cdSetorDestino
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vodemanda->prioridade != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrPrioridade
			. " = "
					. $this->vodemanda->prioridade
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
		
		if($this->vocontrato->anoContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrAnoContrato
			. " = "
					. $this->vocontrato->anoContrato 
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vocontrato->cdContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrCdContrato
			. " = "
					. $this->vocontrato->cdContrato
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vocontrato->tipo != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrTipoContrato
			. " = "
					. getVarComoString($this->vocontrato->tipo)
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
	
	
	function getAtributoOrdenacaoDefault(){
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		$retorno = $nmTabelaDemanda . "." . voDemanda::$nmAtrPrioridade . " " . constantes::$CD_ORDEM_CRESCENTE
				. "," . $nmTabelaDemanda . "." . voDemanda::$nmAtrDhInclusao . " " . constantes::$CD_ORDEM_DECRESCENTE; 
		return $retorno; 		
	}
	
	function getAtributosOrdenacao(){
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		$varAtributos = array(
				voDemanda::$nmAtrPrioridade => "Prioridade",
				voDemanda::$nmAtrDhInclusao => "Data",
				$nmTabelaDemanda . "." . voDemanda::$nmAtrCd => "N�mero"
		);
		return $varAtributos;
	}
	

}

?>