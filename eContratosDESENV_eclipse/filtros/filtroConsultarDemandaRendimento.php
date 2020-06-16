<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");

class filtroConsultarDemandaRendimento extends filtroConsultarDemandaGestao {
	
	public $nmFiltro = "filtroConsultarDemandaRendimento";
	static $NmTabelaRendimento= "NmTabelaRendimento";
	static $NmTabelaTramitacaoMininaPorSetor = "NmTabelaTramitacaoMininaPorSetor";
	static $CD_CAMPO_SUBSTITUICAO_PRINCIPAL = "CD_CAMPO_SUBSTITUICAO_PRINCIPAL";
	
	//colunas
	static $NmColNuEntradas = "NmColNuEntradas";
	static $NmColNuSaidas = "NmColNuSaidas";
	
	function getFiltroFormulario(){
		parent::getFiltroFormulario();
		//tudo isso porque o filtromanter usa um default para os campos abaixo diferente do que deve ser usado para essa funcao
		$this->inOR_AND = @$_POST[self::$NmAtrInOR_AND];
	}
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";

		$nmTabela = voDemandaTramitacao::getNmTabelaStatic(false);
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);	
		$nmTabelaDemandaPL = voDemandaPL::getNmTabelaStatic(false);
		$nmTabelaPL = voProcLicitatorio::getNmTabelaStatic(false);
		$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
					
		//seta os filtros obrigatorios
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
			//echo "setou o ano defaul";
			;
		}

		if($this->vodemanda->ano != null){
				
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemandaTramitacao::$nmAtrAno
			. " = "
					. $this->vodemanda->ano
					;
		
					$conector  = "\n AND ";
		
		}
					
		if ($this->vodemanda->tipo != null 
				&& $this->vodemanda->tipo != "" 
				&& !$this->isAtributoArrayVazio($this->vodemanda->tipo)) {
					
			$filtro = $filtro . $conector
			. $nmTabelaDemanda. "." .voDemanda::$nmAtrTipo;
			
			$tipoDem = $this->vodemanda->tipo;
						
			if(is_array($tipoDem)){
				$filtro .= 	" IN (" . getSQLStringFormatadaColecaoIN($tipoDem, false) . ") ";				
			}else{
				$filtro .= 	" = " . $tipoDem;				
			}				
			
			$conector  = "\n AND ";
		}
		
		$tpDemandaContrato = $this->vodemanda->tpDemandaContrato;
		if ($tpDemandaContrato != null
				&& $tpDemandaContrato != ""
				&& !$this->isAtributoArrayVazio($tpDemandaContrato)) {
				
				$strFiltroTpDemanda = getSQLBuscarStringCampoSeparador($tpDemandaContrato, voDemanda::$nmAtrTpDemandaContrato, constantes::$CD_OPCAO_OR);
				//echo $strFiltroTpDemanda;
				$filtro = $filtro . $conector . $strFiltroTpDemanda;
				$conector  = "\n AND ";
		}
		
		if($this->vodemanda->inTpDemandaReajusteComMontanteA != null){
			$reajuste = $this->vodemanda->inTpDemandaReajusteComMontanteA;
			$clausulaReajuste = " $nmTabelaDemanda." .voDemanda::$nmAtrInTpDemandaReajusteComMontanteA . " = " . getVarComoString($this->vodemanda->inTpDemandaReajusteComMontanteA);
			
			if($reajuste == dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_A
					|| $reajuste == dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_B){
				
						$clausulaReajuste .= " OR $nmTabelaDemanda." .voDemanda::$nmAtrInTpDemandaReajusteComMontanteA
									. " = "
									. getVarComoString(dominioTipoReajuste::$CD_REAJUSTE_AMBOS)
						;				
			}
			$filtro = $filtro . $conector . "($clausulaReajuste)";
					
			$conector  = "\n AND ";
		}	
		
		if ($this->tipoExcludente != null && $this->tipoExcludente != "" && !$this->isAtributoArrayVazio($this->tipoExcludente)) {
			$comparar = " <> '" . $this->tipoExcludente. "'";
			if(is_array($this->tipoExcludente)){		
				$comparar = " NOT IN (" . getSQLStringFormatadaColecaoIN($this->tipoExcludente, true) . ")";
			}
		
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrTipo . $comparar;
		
			$conector = "\n AND ";
		}
				
		$isTpContratoSelecionado = $this->vocontrato->tipo != null && $this->vocontrato->tipo != "";
		$iscdCPLSelecionado = $this->voproclic->cdCPL != null && $this->voproclic->cdCPL != "";
		$isInORAND_AND = $this->inOR_AND == constantes::$CD_OPCAO_AND;		
		if($isInORAND_AND){			
			if($isTpContratoSelecionado){
				$filtro = $filtro . $conector
				. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrTipoContrato
				. " = "
						. getVarComoString($this->vocontrato->tipo)
						;
				$conector  = "\n AND ";
			}
			
			if($iscdCPLSelecionado){
				$filtro = $filtro . $conector
				. $nmTabelaContrato . "." .vocontrato::$nmAtrProcessoLicContrato
				. " LIKE "
				. getVarComoString("%".dominioComissaoProcLicitatorio::getDescricao($this->voproclic->cdCPL)."%");
				
				$conector  = "\n AND ";
			}	
		}else if($isTpContratoSelecionado||$iscdCPLSelecionado){
				$conectorInterno  = $isInOR_ANDSelecionado?" ". $this->inOR_AND . " ":"\n OR ";
				$filtro = $filtro . $conector . "(";
				if($isTpContratoSelecionado){
					$filtro .= $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrTipoContrato
						. " = "
						. getVarComoString($this->vocontrato->tipo)
						;				
				}	
						
				if($iscdCPLSelecionado){
					$filtro = $filtro . $conectorInterno
					. $nmTabelaPL . "." .voProcLicitatorio::$nmAtrCdCPL
					. " = "
					. getVarComoNumero($this->voproclic->cdCPL);
				}
						
				$filtro .= ") ";
				$conector  = "\n AND ";
		}
		
		if($this->inDesativado != null){
			//if($this->vodemanda->cdSetorDestino != null){
			$filtro = $filtro . $conector
			. "$nmTabelaDemanda." . voentidade::$nmAtrInDesativado
			. " = "
					. getVarComoString($this->inDesativado)
					;
		
					$conector  = "\n AND ";

			//se $isInORAND_AND = true, a consulta se darah toda pelo contrato, nao precisando trazer os voproclics vigentes somente
			if(!$isInORAND_AND && $iscdCPLSelecionado){
				//como eh feito um join com proclicitatorio, havera demandas que nao tem pl
				//assim deve ser checado se a demanda tem pl para so assim verificar o indesativado
				$filtro = $filtro . $conector
				. "("
				. "($nmTabelaPL." . voentidade::$nmAtrInDesativado
				. " = " . getVarComoString($this->inDesativado)
				. " AND $nmTabelaPL." . voentidade::$nmAtrInDesativado . " IS NOT NULL)"
				. " OR $nmTabelaPL." . voentidade::$nmAtrInDesativado . " IS NULL"
				. ")";
				
				$conector  = "\n AND ";				
			}
		}		
		
		$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);

		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributoOrdenacaoAnteriorDefault(){
		//$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		//$retorno = $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " " . $this->cdOrdenacao;
		return $retorno;
	}
	
	function getAtributoOrdenacaoDefault(){
		//$retorno = filtroConsultarDemandaGestao::$NmColNumTotalDemandas . " " . constantes::$CD_ORDEM_DECRESCENTE;
		return $retorno;
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				filtroConsultarDemandaRendimento::$NmColNuSaidas => "Saídas",
				filtroConsultarDemandaRendimento::$NmColNuEntradas => "Entradas",				
		);
		
		return $varAtributos;
	}	

}

?>