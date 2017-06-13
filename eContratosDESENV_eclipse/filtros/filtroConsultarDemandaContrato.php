<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");
include_once (caminho_vos . "voDemandaTramitacao.php");
require_once (caminho_funcoes . vocontrato::getNmTabela () . "/dominioAutorizacao.php");
class filtroConsultarDemandaContrato extends filtroManterDemanda {
	public $nmFiltro = "filtroConsultarDemandaContrato";
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
		$filtro = "";
		$conector = "";
		
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( $this->isHistorico () );
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabelaStatic ( false );
		$nmTabelaTramitacaoDoc = voDemandaTramDoc::getNmTabelaStatic ( false );
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
		
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			// echo "setou o ano defaul";
			;
		}
		
		if ($this->cdUsuarioTramitacao != null) {
			$filtro = $filtro . $conector . " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao . " WHERE " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . " AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd . " AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdUsuarioInclusao . "=" . getVarComoNumero ( $this->cdUsuarioTramitacao ) . ")\n";
			
			$conector = "\n AND ";
		}
		
		$consultaDocumento = $this->temDocumentoAnexo == constantes::$CD_SIM || $this->tpDocumento != null || $this->sqDocumento != null;
		if ($consultaDocumento) {
			$filtro = $filtro . $conector . " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacaoDoc 
			. " WHERE " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . "=" . $nmTabelaTramitacaoDoc . "." . voDemandaTramDoc::$nmAtrAnoDemanda 
			. " AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd . "=" . $nmTabelaTramitacaoDoc . "." . voDemandaTramDoc::$nmAtrCdDemanda
			. " AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrSq . "=" . $nmTabelaTramitacaoDoc . "." . voDemandaTramDoc::$nmAtrSqDemandaTram;
						
			if ($this->tpDocumento != null) {
				$filtro .= " AND " . $nmTabelaTramitacaoDoc . "." . voDemandaTramDoc::$nmAtrTpDoc . "=" . getVarComoString ( $this->tpDocumento );
			}
			
			if ($this->sqDocumento != null) {
				$filtro .= " AND " . $nmTabelaTramitacaoDoc . "." . voDemandaTramDoc::$nmAtrSqDoc . "=" . getVarComoNumero ( $this->sqDocumento );
			}
			
			$filtro .= ")\n";
			
			$conector = "\n AND ";
		}
		
		if ($this->isHistorico () && $this->vodemanda->sqHist != null) {
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrSqHist . " = " . $this->vodemanda->sqHist;
			$conector = "\n AND ";
		}
		
		if ($this->vodemanda->ano != null) {
			
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $this->vodemanda->ano;
			
			$conector = "\n AND ";
		}
		
		if ($this->vodemanda->cd != null) {
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $this->vodemanda->cd;
			
			$conector = "\n AND ";
		}
		
		if ($this->vodemanda->tipo != null) {
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrTipo;
			
			if ($this->vodemanda->tipo != dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO) {
				$filtro .= " = " . $this->vodemanda->tipo;
			} else {
				$filtro .= " IN (" . getSQLStringFormatadaColecaoIN ( array_keys ( dominioTipoDemanda::getColecaoTipoDemandaContrato () ), false ) . ") ";
			}
			
			$conector = "\n AND ";
		}
		
		if ($this->vodemanda->cdSetor != null) {
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrCdSetor . " = " . $this->vodemanda->cdSetor;
			
			$conector = "\n AND ";
		}
		
		if ($this->vodemanda->texto != null) {
			// echo "tem texto";
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrTexto
			/*. " LIKE '"
			. substituirCaracterSQLLike($this->vodemanda->texto)*/
			. " LIKE '%" . $this->vodemanda->texto . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->vodemanda->cdSetorDestino != null) {
			$filtro = $filtro . $conector . " ((" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . " IS NULL AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCdSetor . " = " . $this->vodemanda->cdSetorDestino . " ) OR (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . " IS NOT NULL AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . " = " . $this->vodemanda->cdSetorDestino . "))";
			
			$conector = "\n AND ";
		}
		
		if ($this->vodemanda->prioridade != null) {
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrPrioridade . " = " . $this->vodemanda->prioridade;
			
			$conector = "\n AND ";
		}
		
		if ($this->vodemanda->situacao != null) {
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrSituacao . " = " . $this->vodemanda->situacao;
			
			$conector = "\n AND ";
		}
		
		if ($this->dtUltMovimentacao != null) {
			$colDemandaTram = $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrDhInclusao;
			$colDemanda = $nmTabelaDemanda . "." . voDemanda::$nmAtrDhUltAlteracao;
			
			$filtro = $filtro . $conector . " ((" . $colDemandaTram . " IS NOT NULL AND DATE(" . $colDemandaTram . ") = " . getVarComoDataSQL ( $this->dtUltMovimentacao ) . ") OR " . "(" . $colDemanda . " IS NOT NULL AND DATE(" . $colDemanda . ") = " . getVarComoDataSQL ( $this->dtUltMovimentacao ) . ")) ";
			
			$conector = "\n AND ";
		}
		
		if ($this->vodemanda->prt != null) {
			$filtro = $filtro . $conector . " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao . " WHERE " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . " AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd . " AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrProtocolo . "=" . getVarComoString ( $this->vodemanda->prt ) . ")\n";
			
			$conector = "\n AND ";
		}
		
		if ($this->vocontrato->anoContrato != null) {
			$filtro = $filtro . $conector . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . " = " . $this->vocontrato->anoContrato;
			
			$conector = "\n AND ";
		}
		
		if ($this->vocontrato->cdContrato != null) {
			$filtro = $filtro . $conector . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . " = " . $this->vocontrato->cdContrato;
			
			$conector = "\n AND ";
		}
		
		if ($this->vocontrato->tipo != null) {
			$filtro = $filtro . $conector . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . " = " . getVarComoString ( $this->vocontrato->tipo );
			
			$conector = "\n AND ";
		}
		
		// echo $this->vocontrato->cdAutorizacao;
		if ($this->vocontrato->cdAutorizacao != null) {
			
			if (! is_array ( $this->vocontrato->cdAutorizacao )) {
				$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrCdAutorizacaoContrato . " = " . $this->vocontrato->cdAutorizacao;
			} else {
				
				$colecaoAutorizacao = $this->vocontrato->cdAutorizacao;
				$parametroMetodoEspecifico = dominioAutorizacao::getColecaoCdAutorizacaoIntercace ( $colecaoAutorizacao );
				
				// var_dump($parametroMetodoEspecifico);
				
				$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrCdAutorizacaoContrato . " IN (" . getSQLStringFormatadaColecaoIN ( $parametroMetodoEspecifico, false ) . ")";
				;
			}
			
			$conector = "\n AND ";
		}
		
		if ($this->nmContratada != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome . " LIKE '%" . $this->nmContratada . "%'";
			$conector = "\n AND ";
		}
		
		if ($this->docContratada != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc . " = '" . documentoPessoa::getNumeroDocSemMascara ( $this->docContratada ) . "'";
			$conector = "\n AND ";
		}
		
		if ($this->cdDemandaInicial != null) {
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " >= " . $this->cdDemandaInicial;
			$conector = "\n AND ";
		}
		
		if ($this->cdDemandaFinal != null) {
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " <= " . $this->cdDemandaFinal;
			$conector = "\n AND ";
		}
		
		if ($this->vlGlobalInicial != null) {
			$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrVlGlobalContrato . " >= " . getVarComoDecimal ( $this->vlGlobalInicial );
			$conector = "\n AND ";
		}
		
		if ($this->vlGlobalFinal != null) {
			$filtro = $filtro . $conector . $nmTabelaContrato . "." . vocontrato::$nmAtrVlGlobalContrato . " >= " . getVarComoDecimal ( $this->vlGlobalFinal );
			$conector = "\n AND ";
		}
		
		$this->formataCampoOrdenacao ( new voDemanda () );
		// finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	function getAtributoOrdenacaoDefault() {
		$nmTabelaDemanda = voDemandaTramitacao::getNmTabelaStatic ( false );
		$retorno = $nmTabelaDemanda . "." . voDemandaTramitacao::$nmAtrDhInclusao . " " . constantes::$CD_ORDEM_DECRESCENTE;
		return $retorno;
	}
}

?>