<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voDemandaTramitacao.php");
require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");

class filtroManterDemanda extends filtroManter{

	public $nmFiltro = "filtroManterDemanda";
	static $NmColDhUltimaMovimentacao = "NmColDhUltimaMovimentacao";
	static $NmColQtdContratos = "NmColQtdContratos";

	static $NmAtrCdDemandaInicial = "NmAtrCdDemandaInicial";
	static $NmAtrCdDemandaFinal = "NmAtrCdDemandaFinal";
	static $NmAtrCdUsuarioTramitacao = "NmAtrCdUsuarioTramitacao";
	
	static $NmAtrVlGlobalInicial = "NmAtrVlGlobalInicial";
	static $NmAtrVlGlobalFinal = "NmAtrVlGlobalFinal";
	static $NmAtrInOR_AND = "NmAtrInOR_AND";
	
	var $vodemanda;
	var $vocontrato;
	var $nmContratada;
	var $docContratada;
	var $temDocumentoAnexo;
	var $cdSetorDocumento;
	var $tpDocumento;
	var $sqDocumento;
	
	var $dtUltMovimentacao;
	var $cdDemandaInicial;
	var $cdDemandaFinal;	
	var $cdUsuarioTramitacao;

	var $vlGlobalInicial;
	var $vlGlobalFinal;
	
	var $InOR_AND;
	
	// ...............................................................
	// construtor
	function __construct0() {
		$this->__construct1(true);
	}
	
	function __construct1($pegarFiltrosDaTela) {
		$this->vodemanda = new voDemandaTramitacao();
		$this->vocontrato = new vocontrato();
		
		parent::__construct1($pegarFiltrosDaTela);				
		
	}	
			
	function getFiltroFormulario(){
		$vodemanda = new voDemandaTramitacao();
		$vocontrato = new vocontrato();
		
		$vodemanda->cd  = @$_POST[voDemanda::$nmAtrCd];
		$vodemanda->ano  = @$_POST[voDemanda::$nmAtrAno];
		$vodemanda->texto = @$_POST[voDemanda::$nmAtrTexto];
		$vodemanda->cdSetor = @$_POST[voDemanda::$nmAtrCdSetor];
		$vodemanda->cdSetorDestino = @$_POST[voDemandaTramitacao::$nmAtrCdSetorDestino];
		$vodemanda->tipo = @$_POST[voDemanda::$nmAtrTipo];
		$vodemanda->situacao  = @$_POST[voDemanda::$nmAtrSituacao];
		$vodemanda->prioridade  = @$_POST[voDemanda::$nmAtrPrioridade];
		$vodemanda->prt = @$_POST[voDemandaTramitacao::$nmAtrProtocolo];
		
		$vocontrato->anoContrato = @$_POST[vocontrato::$nmAtrAnoContrato];
		$vocontrato->cdContrato = @$_POST[vocontrato::$nmAtrCdContrato];
		$vocontrato->tipo = @$_POST[vocontrato::$nmAtrTipoContrato];
		$vocontrato->cdAutorizacao = @$_POST[vocontrato::$nmAtrCdAutorizacaoContrato];
		
		$this->vodemanda = $vodemanda;
		$this->vocontrato = $vocontrato;

		$this->nmContratada = @$_POST[vopessoa::$nmAtrNome];
		$this->docContratada = @$_POST[vopessoa::$nmAtrDoc];
		$this->dtUltMovimentacao = @$_POST[voDemanda::$nmAtrDtReferencia];
		$this->cdSetorDocumento = @$_POST[voDocumento::$nmAtrCdSetor];
		$this->tpDocumento = @$_POST[voDocumento::$nmAtrTp];
		$this->sqDocumento = @$_POST[voDocumento::$nmAtrSq];
		$this->cdDemandaInicial = @$_POST[self::$NmAtrCdDemandaInicial];
		$this->cdDemandaFinal = @$_POST[self::$NmAtrCdDemandaFinal];
		
		$this->vlGlobalInicial = @$_POST[self::$NmAtrVlGlobalInicial];
		$this->vlGlobalFinal = @$_POST[self::$NmAtrVlGlobalFinal];
		
		$this->cdUsuarioTramitacao = @$_POST[self::$NmAtrCdUsuarioTramitacao];
		$this->InOR_AND = @$_POST[self::$NmAtrInOR_AND];
		if($this->InOR_AND == null){
			$this->InOR_AND = constantes::$CD_OPCAO_OR;
		}
		
		if($this->cdOrdenacao == null){
			$this->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
		}		
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
					
		//seta os filtros obrigatorios
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
			//echo "setou o ano defaul";
			;
		}
		 
		if($this->cdUsuarioTramitacao != null){
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrAno
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCd
					. " AND " . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCdUsuarioInclusao . "="
					. getVarComoNumero($this->cdUsuarioTramitacao)
					. ")\n";
			
			$conector  = "\n AND ";
		}
		
		$consultaDocumento = $this->temDocumentoAnexo == constantes::$CD_SIM || $this->tpDocumento != null || $this->cdSetorDocumento != null || $this->sqDocumento != null;
		if($consultaDocumento){
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacaoDoc
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrAnoDemanda
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrCdDemanda;
					
			if($this->tpDocumento != null){
				$filtro .= " AND " . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrTpDoc. "="
							. getVarComoString($this->tpDocumento);
			}

			if($this->cdSetorDocumento != null){
				$filtro .= " AND " . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrCdSetorDoc . "="
						. getVarComoNumero($this->cdSetorDocumento);
			}
				
			if($this->sqDocumento != null){
				$filtro .= " AND " . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrSqDoc . "="
						. getVarComoNumero($this->sqDocumento);						
			}
			
			$filtro .= ")\n";
				
			$conector  = "\n AND ";
		}
		
		if($this->isHistorico() && $this->vodemanda->sqHist != null){			
			$filtro = $filtro . $conector
				. $nmTabela. "." .voDemanda::$nmAtrSqHist
				. " = "
				. $this->vodemanda->sqHist
				;						
			$conector  = "\n AND ";
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
		
		if($this->vodemanda->tipo != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrTipo;
			
			if($this->vodemanda->tipo != dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO){			
				$filtro .= 	" = "
					. $this->vodemanda->tipo
					;			
			}else{
				$filtro .= 	" IN ("
						. getSQLStringFormatadaColecaoIN(array_keys(dominioTipoDemanda::getColecaoTipoDemandaContrato()), false)
						. ") "
						;				
			}			
			
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
		
		if($this->vodemanda->texto != null){
			//echo "tem texto";
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrTexto
			/*. " LIKE '"
			. substituirCaracterSQLLike($this->vodemanda->texto)*/
			. " LIKE '%"
			. $this->vodemanda->texto
			. "%'";
		
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
		
		if($this->dtUltMovimentacao != null){
			$colDemandaTram = $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrDhInclusao;
			$colDemanda = $nmTabela. "." .voDemanda::$nmAtrDhUltAlteracao;
			
			$filtro = $filtro . $conector
			. " ((". $colDemandaTram 
			. " IS NOT NULL AND DATE(". $colDemandaTram
			. ") = "
			. getVarComoDataSQL($this->dtUltMovimentacao)
			. ") OR "
			. "(". $colDemanda
			. " IS NOT NULL AND DATE(". $colDemanda
			. ") = "
			. getVarComoDataSQL($this->dtUltMovimentacao)					
			. ")) "
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
				$filtro = $filtro . $conector . $strComparacao . voContratoInfo::getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutorizacao, $this->InOR_AND);
				
			}			
			
			$conector  = "\n AND ";
		}
		
		if($this->nmContratada != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrNome
			. " LIKE '%"
			. $this->nmContratada
			. "%'"
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
		
		$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);

		//echo "Filtro:$filtro<br>";

		return $filtro;
	}	
	
	function getAtributoOrdenacaoDefault(){
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		$retorno = $nmTabelaDemanda . "." . voDemanda::$nmAtrPrioridade . " " . constantes::$CD_ORDEM_CRESCENTE
				. "," . $nmTabelaDemanda . "." . voDemanda::$nmAtrDhInclusao . " " . constantes::$CD_ORDEM_CRESCENTE; 
		return $retorno; 		
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				voDemanda::$nmAtrCd => "Número",
				voDemanda::$nmAtrDtReferencia => "Data.Referência",
				filtroManterDemanda::$NmColDhUltimaMovimentacao => "Data.Movimentação",
				voDemanda::$nmAtrPrioridade => "Prioridade",				
				voDemanda::$nmAtrTipo => "Tipo"				
		);
		return $varAtributos;
	}	

}

?>