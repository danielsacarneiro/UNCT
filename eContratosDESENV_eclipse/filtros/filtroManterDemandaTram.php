<?php
include_once("filtroManterDemanda.php");

class filtroManterDemandaTram extends filtroManterDemanda{

	public $nmFiltro = "filtroManterDemandaTram";
		
	function getFiltroFormulario(){
		parent::getFiltroFormulario();
	
		$sqDemandaTram = @$_POST[voDemandaTramitacao::$nmAtrSq];
		$this->vodemanda->sq = $sqDemandaTram; 
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";
	
		$nmTabela = voDemanda::getNmTabelaStatic($this->isHistorico);
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabelaStatic(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
		$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
		$nmTabelaPessaContrato = vopessoa::getNmTabelaStatic(false);
			
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
	
		if($this->vodemanda->sq != null){
			$filtro = $filtro . $conector
			. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrSq
			. " = "
					. $this->vodemanda->sq
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vodemanda->texto != null){
			//echo "tem texto";
			$filtro = $filtro . $conector
			. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrTexto
			/*. " LIKE '"
			 . substituirCaracterSQLLike($this->vodemanda->texto)*/
			. " LIKE '%"
			 		. $this->vodemanda->texto
			 		. "%'";
		
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
	
		if($this->vodemanda->prt != null){
	
				
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrAno
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCd
					. " AND " . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrProtocolo . "="
							. getVarComoString($this->vodemanda->prt)
							. ")\n";
	
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
	
		if($this->vocontrato->cdAutorizacao != null){
			$filtro = $filtro . $conector
			. $nmTabelaContrato. "." .vocontrato::$nmAtrCdAutorizacaoContrato
			. " = "
					. $this->vocontrato->cdAutorizacao
					;
	
					$conector  = "\n AND ";
		}
	
		if($this->nmContratada != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessaContrato. "." .vopessoa::$nmAtrNome
			. " LIKE '"
					. substituirCaracterSQLLike($this->nmContratada)
					. "'"
							;
							$conector  = "\n AND ";
	
		}
	
		if($this->docContratada != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessaContrato. "." .vopessoa::$nmAtrDoc
			. " = '"
					. documentoPessoa::getNumeroDocSemMascara($this->docContratada)
					. "'"
							;
							$conector  = "\n AND ";
	
		}
	
		$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);
	
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
		$varAtributos = array(
				voDemandaTramitacao::$nmAtrDtReferencia => "Data.Referência",
				filtroManterDemanda::$NmColDhUltimaMovimentacao => "Data.Movimentação",
				voDemandaTramitacao::$nmAtrAno => "Ano",
				voDemandaTramitacao::$nmAtrCd => "Número",
				voDemandaTramitacao::$nmAtrSq => "Tramitação"
		);
		return $varAtributos;
	}
	

}

?>