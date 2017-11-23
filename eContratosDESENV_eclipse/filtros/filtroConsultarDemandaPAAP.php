<?php
include_once(caminho_util."bibliotecaSQL.php");

/**
 * 
 * @author daniel.ribeiro
 * 
 * deve ser uma copia do filtroManterDemanda porque inicialmente usara os mesmos metodos de dbDemanda.php
 *
 */

class filtroConsultarDemandaPAAP extends filtroManterDemanda{

	public $nmFiltro = "filtroConsultarDemandaPAAP";
	
	var $voPA;
	var $vodemanda;
	var $qtdDiasPrazo;
	
	// ...............................................................
	// construtor	
	function __construct1($pegarFiltrosDaTela) {
		$this->voPA = new voPA();		
		$this->vodemanda = new voDemanda();
		parent::__construct1($pegarFiltrosDaTela);
		
		//default
		$this->qtdDiasPrazo = 15;
	}	
			
	function getFiltroFormulario(){
		parent::getFiltroFormulario();
		
		$voPA = new voPA();		
		$vodemanda->cd  = @$_POST[voDemanda::$nmAtrCd];		
	}
	 	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";

		$nmTabela = voDemanda::getNmTabelaStatic($this->isHistorico());
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabelaStatic(false);
		$nmTabelaTramitacaoDoc = voDemandaTramDoc::getNmTabelaStatic(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
		$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic(false);
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic(false);
		$nmTabelaPA = voPA::getNmTabelaStatic(false);
					
		//seta os filtros obrigatorios
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
			//echo "setou o ano defaul";
			;
		}
		
		if ($this->vodemanda->situacao != null && !$this->isAtributoArrayVazio($this->vodemanda->situacao)) {
			$comparar = " = '" . $this->vodemanda->situacao . "'";
			if(is_array($this->vodemanda->situacao)){
				$comparar = " IN (" . getSQLStringFormatadaColecaoIN($this->vodemanda->situacao, true) . ")";
			}
		
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrSituacao . $comparar;
		
			$conector = "\n AND ";
		}	
		
		if ($this->voPA->situacao != null && !$this->isAtributoArrayVazio($this->voPA->situacao)) {
			$comparar = " = '" . $this->voPA->situacao . "'";
			if(is_array($this->voPA->situacao)){
				$comparar = " IN (" . getSQLStringFormatadaColecaoIN($this->voPA->situacao, true) . ")";
			}
		
			$filtro = $filtro . $conector . $nmTabelaPA . "." . voPA::$nmAtrSituacao . $comparar;
		
			$conector = "\n AND ";
		}
		
		if($this->vodemanda->tipo != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrTipo;
				
			$tipoDem = $this->vodemanda->tipo;
				
			if($tipoDem == dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO){
				$tipoDem = array_keys(dominioTipoDemanda::getColecaoTipoDemandaContrato());
			}
				
			if(is_array($tipoDem)){
				$filtro .= 	" IN (" . getSQLStringFormatadaColecaoIN($tipoDem, false) . ") ";
		
			}else{
				$filtro .= 	" = " . $tipoDem;
			}
				
			$conector  = "\n AND ";
		}
		
		if($this->vodemanda->cdSetorDestino != null){
			$filtro = $filtro . $conector
			. " (("
					. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrCdSetorDestino
					. " IS NULL AND "
							.$nmTabela. "." .voDemanda::$nmAtrCdSetor
							. " = "
									. $this->vodemanda->cdSetorDestino
									. " ) OR ("
											. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrCdSetorDestino
											. " IS NOT NULL AND "
													. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrCdSetorDestino
													. " = "
															. $this->vodemanda->cdSetorDestino
															. "))";
		
															$conector  = "\n AND ";
		}
		
		
		if($this->qtdDiasPrazo != null){
			$nmAtributoDataNotificacao = $nmTabelaPA . "." .voPA::$nmAtrDtNotificacao;			
			$dtNotificacaoPAram = getVarComoDataSQL(somarOuSubtrairDiasUteisNaData(getDataHoje(), $this->qtdDiasPrazo, "-"));						
			
			//se a data consultada + qtddiasprazo for menor que a data de hoje, significa que o prazo ja passou, entao a demanda deve ser exibida
			$filtro = $filtro . $conector
			. " ($nmAtributoDataNotificacao IS NOT NULL AND $nmAtributoDataNotificacao <= $dtNotificacaoPAram ) ";
			
			$conector  = "\n AND ";
		}
		
		$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);

		//echo "Filtro:$filtro<br>";

		return $filtro;
	}	

}

?>