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
	static $NM_TAB_USUARIO_RESP_PAAP = "NM_TAB_USUARIO_RESP_PAAP"; 
	static $NmColRESP_PAAP = "NM_COL_NM_RESP_PAAP";
	
	var $voPA;
	var $vodemanda;
	var $qtdDiasPrazo;
	var $InVerificarPrazo;
	
	// ...............................................................
	// construtor	
	function __construct1($pegarFiltrosDaTela) {
		$this->voPA = new voPA();		
		$this->vodemanda = new voDemanda();
		parent::__construct1($pegarFiltrosDaTela);
		
		//default
		$this->qtdDiasPrazo = 15;
		$this->InVerificarPrazo = constantes::$CD_SIM;
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
		
		if($this->inComPAAPInstaurado != null){
			$comparacao = " IS NOT NULL ";
			if(!getAtributoComoBooleano($this->inComPAAPInstaurado)){
				$comparacao = " IS NULL ";
			}
				
			$filtro = $filtro . $conector
			. $nmTabelaPA . "." .voPA::$nmAtrCdPA
			. " $comparacao "
			;
		
			$conector  = "\n AND ";
		}
			
		//verifica prazo encerrado da notificacao
		if(getAtributoComoBooleano($this->InVerificarPrazo)){
			$filtro = $this->getDataComparacaoPrazoEncerrado($filtro, $nmTabelaPA, $conector);
		}
		$conector  = "\n AND ";		
		
		$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);

		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getDataComparacaoPrazoEncerrado($filtro, $nmTabelaPA, $conector){
		
		$nmAtributoDataPrazoEncerrado = $nmTabelaPA . "." .voPA::$nmAtrDtUltNotificacaoPrazoEncerrado;
		$dtParam = " NOW() ";
			
		//se a data consultada $nmAtributoDataPrazoEncerrado for menor que a data de hoje, significa que o prazo ja passou, entao a demanda deve ser exibida
		//se a situacao for AGUARDANDO ACAO, traga o PAAP de todo jeito
		$filtro = $filtro . $conector
		. " ($nmTabelaPA." .voPA::$nmAtrSituacao . "=" . dominioSituacaoPA::$CD_SITUACAO_PA_AGUARDANDO_ACAO . " OR "
		. " ($nmTabelaPA." .voPA::$nmAtrSituacao . "=" . dominioSituacaoPA::$CD_SITUACAO_PA_AGUARDANDO_NOTIFICACAO_ENVIADA . " AND "
		. " $nmAtributoDataPrazoEncerrado IS NOT NULL AND $nmAtributoDataPrazoEncerrado <= $dtParam)) "
		;
		
		return $filtro; 
	}
	
	function getAtributoOrdenacaoDefault(){
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		$nmTabelaPA = voPA::getNmTabelaStatic($this->isHistorico);
		$retorno = 
		$nmTabelaPA . "." . voPA::$nmAtrDtUltNotificacaoParaManifestacao . " " . constantes::$CD_ORDEM_CRESCENTE
		. "," .$nmTabelaPA . "." . voPA::$nmAtrAnoPA . " " . constantes::$CD_ORDEM_CRESCENTE
		. "," . $nmTabelaPA . "." . voPA::$nmAtrCdPA . " " . constantes::$CD_ORDEM_CRESCENTE
		;
		
		return $retorno;
	}
	

}

?>