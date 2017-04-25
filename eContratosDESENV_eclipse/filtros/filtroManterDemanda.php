<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voDemandaTramitacao.php");

class filtroManterDemanda extends filtroManter{

	public $nmFiltro = "filtroManterDemanda";
	static $NmColDhUltimaMovimentacao = "NmColDhUltimaMovimentacao";
	
	var $vodemanda;
	var $vocontrato;
	var $nmContratada;
	var $docContratada;
	var $tpDocumento;
	
	var $dtUltMovimentacao;
	
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
		$this->tpDocumento = @$_POST[voDocumento::$nmAtrTp];
		
		if($this->cdOrdenacao == null){
			$this->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
		}		
	}
	 
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";

		$nmTabela = voDemanda::getNmTabelaStatic($this->isHistorico);
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabelaStatic(false);
		$nmTabelaTramitacaoDoc = voDemandaTramDoc::getNmTabelaStatic(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
		$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic(false);
					
		//seta os filtros obrigatorios
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
			//echo "setou o ano defaul";
			;
		}
		
		if($this->tpDocumento != null){
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacaoDoc
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrAnoDemanda
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrCdDemanda
					. " AND " . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrTpDoc. "="
							. getVarComoString($this->tpDocumento)
							. ")\n";
		
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
			. $nmTabela. "." .voDemanda::$nmAtrTipo
			. " = "
					. $this->vodemanda->tipo
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
		
		if($this->vodemanda->texto != null){
			//echo "tem texto";
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrTexto
			. " LIKE '"
					. substituirCaracterSQLLike($this->vodemanda->texto)
					. "'";
		
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