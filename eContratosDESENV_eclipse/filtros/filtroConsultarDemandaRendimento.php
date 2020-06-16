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
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";

		$nmTabela = voDemandaTramitacao::getNmTabelaStatic(false);
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);	
		$nmTabelaDemandaPL = voDemandaPL::getNmTabelaStatic(false);
		$nmTabelaPL = voProcLicitatorio::getNmTabelaStatic(false);
					
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
		
		/*if($this->vodemanda->cd != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemandaTramitacao::$nmAtrCd
			. " = "
					. $this->vodemanda->cd
					;
		
					$conector  = "\n AND ";		
		}*/
				
		if ($this->vodemanda->tipo != null 
				&& $this->vodemanda->tipo != "" 
				&& !$this->isAtributoArrayVazio($this->vodemanda->tipo)) {
					
			$filtro = $filtro . $conector
			. $nmTabelaDemanda. "." .voDemanda::$nmAtrTipo;
			
			$tipoDem = $this->vodemanda->tipo;
			
			/*if($tipoDem == dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO){			
				$tipoDem = array_keys(dominioTipoDemanda::getColecaoTipoDemandaContrato());
			}*/
			
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
		
		/*if($this->vodemanda->cdSetor != null){
			$filtro = $filtro . $conector
			. voDemanda::$nmAtrCdSetor
			. " = "
					. $this->vodemanda->cdSetor
					;
		
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
		
		if($this->cdSetorPassagem != null){
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrAno
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCd
					. " AND (" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCdSetorOrigem. "=" . $this->cdSetorPassagem
					. " OR "
					. $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "=" . $this->cdSetorPassagem							
					. "))\n ";
		
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
		
		if ($this->vodemanda->situacao != null 
				&& (!is_array($this->vodemanda->situacao) || (is_array($this->vodemanda->situacao) && !$this->isAtributoArrayVazio($this->vodemanda->situacao)))) {
						
			$comparar = " = '" . $this->vodemanda->situacao . "'";
			if(is_array($this->vodemanda->situacao)){
							
				if(count($this->vodemanda->situacao) == 1 && $this->vodemanda->situacao[0] == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_FAZER){
					$comparar = " IN (" . getSQLStringFormatadaColecaoIN(array_keys(dominioSituacaoDemanda::getColecaoAFazer()), true) . ")";
				}else{
					$comparar = " IN (" . getSQLStringFormatadaColecaoIN($this->vodemanda->situacao, true) . ")";
				}
			}
				
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrSituacao . $comparar;
				
			$conector = "\n AND ";
		}*/		
		
		if ($this->tipoExcludente != null && $this->tipoExcludente != "" && !$this->isAtributoArrayVazio($this->tipoExcludente)) {
			$comparar = " <> '" . $this->tipoExcludente. "'";
			if(is_array($this->tipoExcludente)){		
				$comparar = " NOT IN (" . getSQLStringFormatadaColecaoIN($this->tipoExcludente, true) . ")";
			}
		
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrTipo . $comparar;
		
			$conector = "\n AND ";
		}
		
		/*if($this->inComPAAPInstaurado != null){
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
		
		if($this->inSEI != null){			
			$numCaracteres = constantes::$TAMANHO_CARACTERES_PRT;
			if(getAtributoComoBooleano($this->inSEI)){
				$numCaracteres = constantes::$TAMANHO_CARACTERES_SEI;
			}
				
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrAno
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCd
					. " AND LENGTH($nmTabelaTramitacao." . voDemandaTramitacao::$nmAtrProtocolo
					. ") =  $numCaracteres)\n";
						
			$conector  = "\n AND ";
		}
		
		if ($this->prioridadeExcludente != null && !$this->isAtributoArrayVazio($this->prioridadeExcludente)) {
			$comparar = " <> '" . $this->prioridadeExcludente. "'";
			if(is_array($this->prioridadeExcludente)){
				$comparar = " NOT IN (" . getSQLStringFormatadaColecaoIN($this->prioridadeExcludente, true) . ")";
			}
		
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrPrioridade . $comparar;
		
			$conector = "\n AND ";
		}
		
		if($this->dtUltMovimentacaoInicial != null){			
			$filtro = $filtro . $conector . static::getSQLDataDemandaMovimentacao($this->dtUltMovimentacaoInicial, ">=");
		
			$conector  = "\n AND ";
		}
		
		if($this->dtUltMovimentacaoFinal != null){
			$filtro = $filtro . $conector . static::getSQLDataDemandaMovimentacao($this->dtUltMovimentacaoFinal, "<=");		
			$conector  = "\n AND ";
		}
		
		if($this->cdSetorImplementacaoEconti != null){
			if($this->cdSetorImplementacaoEconti == dominioSetor::$CD_SETOR_UNCT){
				$filtro = $filtro . $conector . static::getSQLDataDemandaMovimentacao("01/02/2019", ">=");
			}
			
			$conector  = "\n AND ";
		}
		
		if($this->vodemanda->prt != null){
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao
			. " WHERE " 
			. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrAno
			. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCd
			. " AND " . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrProtocolo 
			. " LIKE '%"			
			. voDemandaTramitacao::getNumeroPRTSemMascara($this->vodemanda->prt,false)
			. "%')\n";
		
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
		}*/
				
		$isTpContratoSelecionado = $this->vocontrato->tipo != null && $this->vocontrato->tipo != "";
		$iscdCPLSelecionado = $this->voproclic->cdCPL != null && $this->voproclic->cdCPL != "";
		$isInOR_ANDSelecionado = $this->inOR_AND != null && $this->inOR_AND != "";
		if($isTpContratoSelecionado ||  $iscdCPLSelecionado){
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
		
		/*if($this->vocontrato->cdEspecie != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrCdEspecieContrato
			. " = "
					. getVarComoString($this->vocontrato->cdEspecie)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vocontrato->sqEspecie != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrSqEspecieContrato
			. " = "
					. getVarComoNumero($this->vocontrato->sqEspecie)
					;
		
					$conector  = "\n AND ";
		}
		
		//echo $this->vocontrato->cdAutorizacao; 
		if($this->vocontrato->cdAutorizacao != null){
			$strComparacao = "COALESCE (" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdAutorizacaoContrato 
							. "," 
							. $nmTabelaContrato . "." . voContrato::$nmAtrCdAutorizacaoContrato . ")";
			
			if(!is_array($this->vocontrato->cdAutorizacao)){
				$filtro = $filtro . $conector
				//. $nmTabelaContrato. "." .vocontrato::$nmAtrCdAutorizacaoContrato
				. $strComparacao
				. " = "
						. $this->vocontrato->cdAutorizacao
						;				
			}else{
				
				$colecaoAutorizacao = $this->vocontrato->cdAutorizacao;				
				$filtro = $filtro . $conector . $strComparacao . voContratoInfo::getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutorizacao, $this->inOR_AND);
				
			}			
			
			$conector  = "\n AND ";
		}
		
		if($this->nmContratada != null){
			$filtro = $filtro . $conector
			//. "($nmTabelaPessoaContrato." .vopessoa::$nmAtrNome
			. "(". getSQLNmContratada(false)
			. " LIKE '%"
			. $this->nmContratada
			. "%'"
			. " OR $nmTabela." .voDemanda::$nmAtrTexto
			. " LIKE '%"
			. $this->nmContratada
			. "%')"
			;		
			$conector  = "\n AND ";
		
		}
		
		if($this->docContratada != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrDoc
			. " = '"
					. documentoPessoa::getNumeroDocSemMascara($this->docContratada)
					. "'"
							;
							$conector  = "\n AND ";
		
		}
		
		if($this->cdDemandaInicial != null){
			$filtro = $filtro . $conector
			. $nmTabela . "." .voDemanda::$nmAtrCd
			. " >= "
					. $this->cdDemandaInicial;
					$conector  = "\n AND ";
		
		}
		
		if($this->cdDemandaFinal != null){
			$filtro = $filtro . $conector
			. $nmTabela . "." .voDemanda::$nmAtrCd
			. " <= "
					. $this->cdDemandaFinal;
					$conector  = "\n AND ";
		
		}
		
		if($this->vlGlobalInicial != null){
			$filtro = $filtro . $conector
			. $nmTabelaContrato . "." .vocontrato::$nmAtrVlGlobalContrato
			. " >= "
					. getVarComoDecimal($this->vlGlobalInicial);
					$conector  = "\n AND ";
		
		}
		
		if($this->vlGlobalFinal != null){
			$filtro = $filtro . $conector
			. $nmTabelaContrato . "." .vocontrato::$nmAtrVlGlobalContrato
			. " >= "
					. getVarComoDecimal($this->vlGlobalFinal);
					$conector  = "\n AND ";
		
		}
		
		if($this->cdClassificacaoContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaContratoInfo . "." .voContratoInfo::$nmAtrCdClassificacao
			. " = "
			. getVarComoNumero($this->cdClassificacaoContrato);
			$conector  = "\n AND ";
		
		}
		
		if ($this->inMaoDeObra != null) {
		
			$filtro = $filtro . $conector . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrInMaoDeObra . " = " . getVarComoString ( $this->inMaoDeObra);
		
			$conector = "\n AND ";
		}
										
		if($this->voproclic->cdModalidade != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaPL . "." .voDemandaPL::$nmAtrCdModalidadeProcLic
			. " = "
					. getVarComoString($this->voproclic->cdModalidade)
					;
		
					$conector  = "\n AND ";
		}*/
				
		
		if($this->inDesativado != null){
			//if($this->vodemanda->cdSetorDestino != null){
			$filtro = $filtro . $conector
			. "$nmTabelaDemanda." . voentidade::$nmAtrInDesativado
			. " = "
					. getVarComoString($this->inDesativado)
					;
		
					$conector  = "\n AND ";
					
			if($iscdCPLSelecionado){
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