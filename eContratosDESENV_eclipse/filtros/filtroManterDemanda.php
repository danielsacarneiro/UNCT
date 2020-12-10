<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voDemandaTramitacao.php");
require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");

class filtroManterDemanda extends filtroManter{		
	public $nmFiltro = "filtroManterDemanda";
	
	static $NM_TABELA_DADOS_CONTRATO_DEMANDA = "NM_TABELA_DADOS_CONTRATO_DEMANDA";
	static $NM_TABELA_USUARIO_UNCT = "NM_TABELA_USUARIO_UNCT";
	static $NM_COL_NOME_RESP_UNCT = "NM_COL_NOME_RESP_UNCT";
	static $NmAtrFasePlanilha = "NmAtrFasePlanilha";
	static $NmAtrCdSetorPassagem = "NmAtrCdSetorPassagem";
	static $NmColQtdDiasDataDtReferencia = "NmColQtdDiasDataDtReferencia";
	static $NmColDhUltimaMovimentacao = "NmColDhUltimaMovimentacao";
	static $NmColQtdContratos = "NmColQtdContratos";
	static $NmColDtReferenciaSetorAtual = "NmColDtReferenciaSetorAtual";

	static $NmAtrCdDemandaInicial = "NmAtrCdDemandaInicial";
	static $NmAtrCdDemandaFinal = "NmAtrCdDemandaFinal";
	static $NmAtrCdUsuarioTramitacao = "NmAtrCdUsuarioTramitacao";
	static $NmAtrCdSetorImplementacaoEConti = "NmAtrCdSetorImplementacaoEConti";
	
	static $NmAtrVlGlobalInicial = "NmAtrVlGlobalInicial";
	static $NmAtrVlGlobalFinal = "NmAtrVlGlobalFinal";
	static $NmAtrInOR_AND = "NmAtrInOR_AND";
	static $NmAtrTipoExcludente = "NmAtrTipoExcludente";
	static $NmAtrPrioridadeExcludente = "NmAtrPrioridadeExcludente";
	static $NmAtrInComPAAPInstaurado = "NmAtrInComPAAPInstaurado";
	static $NmAtrInSEI = "NmAtrInSEI";
	static $NmAtrDtUltimaMovimentacaoInicial = "NmAtrDtUltimaMovimentacaoInicial";
	static $NmAtrDtUltimaMovimentacaoFinal = "NmAtrDtUltimaMovimentacaoFinal";
	
	static $NmAtrDtReferenciaInicial = "NmAtrDtReferenciaInicial";
	static $NmAtrDtReferenciaFinal = "NmAtrDtReferenciaFinal";
	
	static $ID_REQ_NuTempoVidaMinimo = "ID_REQ_NuTempoVidaMinimo";
	static $ID_REQ_NuTempoVidaMinimoUltimaTram = "ID_REQ_NuTempoVidaMinimoUltimaTram";
	static $ID_REQ_InMonitorar = "ID_REQ_InMonitorar";
	
	static $NmColNuTempoVida = "NmColNuTempoVida";
	static $NmColNuTempoUltimaTram= "NmColNuTempoUltimaTram";
	
	static $NmAtrInOR_AND_Fase = "InOR_AND_Fase";
	
	
	var $vodemanda;
	var $vocontrato;
	var $voproclic;
	var $voPA;
	var $nmContratada;
	var $docContratada;
	var $temDocumentoAnexo;
	var $cdSetorDocumento;
	var $cdSetorPassagem;
	var $tpDocumento;
	var $sqDocumento;
	var $anoDocumento;
	
	var $dtUltMovimentacaoInicial;
	var $dtUltMovimentacaoFinal;

	var $dtReferenciaInicial;
	var $dtReferenciaFinal;
	
	var $cdDemandaInicial;
	var $cdDemandaFinal;	
	var $cdUsuarioTramitacao;

	var $vlGlobalInicial;
	var $vlGlobalFinal;
	
	var $inOR_AND;
	var $inOR_AND_Fase;
	var $fasePlanilha;
	var $tipoExcludente;
	var $prioridadeExcludente;
	var $cdClassificacaoContrato;
	var $inMaoDeObra = "";
	var $inContratoComDtPropostaVencida;
	var $inComPAAPInstaurado;
	var $cdSetorImplementacaoEconti;
	var $inSEI;
	private $sqlComplementoContratoComDtPropostaVencida;
	var $inRetornarReajusteSeLocacaoImovel;
	
	var $nuTempoVidaMinimo;
	var $nuTempoVidaMinimoUltimaTram;
	var $inCdResponsavelUNCT;
	var $inMonitorar;
	var $numPrazoMonitorar;
	
	// ...............................................................
	// construtor
	function __construct0() {
		$this->__construct1(true);
	}
	
	function __construct1($pegarFiltrosDaTela) {
		$this->vodemanda = new voDemandaTramitacao();
		//echo "ano vodemanda " . $this->vodemanda->ano;
		$this->vocontrato = new vocontrato();
		$this->vocontrato->sqEspecie = null;
		//$this->voPA = new voPA();
		
		parent::__construct1($pegarFiltrosDaTela);
		/*if($this->inDesativado == null){
			$this->inDesativado = false;
		}*/		
	}	
			
	function getFiltroFormulario(){
		$vodemanda = new voDemandaTramitacao();
		$vocontrato = new vocontrato();
		$this->voproclic = new voProcLicitatorio();
		$this->voproclic->getDadosFormulario();
		
		$this->voPA = new voPA();
		$this->voPA->getDadosFormulario();
		
		$vodemanda->cd  = @$_POST[voDemanda::$nmAtrCd];
		$vodemanda->ano  = @$_POST[voDemanda::$nmAtrAno];
		if(!isset($_POST[voDemanda::$nmAtrAno])){
			$vodemanda->ano = getAnoHoje();
		}		
		$vodemanda->texto = @$_POST[voDemanda::$nmAtrTexto];
		$vodemanda->cdSetor = @$_POST[voDemanda::$nmAtrCdSetor];
		$vodemanda->cdSetorDestino = @$_POST[voDemandaTramitacao::$nmAtrCdSetorDestino];
		$this->cdSetorPassagem = @$_POST[static::$NmAtrCdSetorPassagem];
		
		$vodemanda->tipo = @$_POST[voDemanda::$nmAtrTipo];
		//echoo("recuperando atributo filtro TIPO." . $this->nmFiltro . " " . $vodemanda->tipo);
		
		$vodemanda->tpDemandaContrato = @$_POST[voDemanda::$nmAtrTpDemandaContrato];
		$vodemanda->fase = @$_POST[voDemanda::$nmAtrFase];
		$this->fasePlanilha = @$_POST[static::$NmAtrFasePlanilha];
		$vodemanda->inTpDemandaReajusteComMontanteA = @$_POST[voDemanda::$nmAtrInTpDemandaReajusteComMontanteA];
		//var_dump($vodemanda->tpDemandaContrato);
		$vodemanda->situacao  = @$_POST[voDemanda::$nmAtrSituacao];		
		$vodemanda->prioridade  = @$_POST[voDemanda::$nmAtrPrioridade];
		$this->prioridadeExcludente = @$_POST[static::$NmAtrPrioridadeExcludente];
		$vodemanda->prt = trim(@$_POST[voDemandaTramitacao::$nmAtrProtocolo]);
		$vodemanda->cdPessoaRespATJA  = @$_POST[voDemanda::$nmAtrCdPessoaRespATJA];
		$vodemanda->cdPessoaRespUNCT = @$_POST[voDemanda::$nmAtrCdPessoaRespUNCT];
		
		$vocontrato->anoContrato = @$_POST[vocontrato::$nmAtrAnoContrato];
		$vocontrato->cdContrato = @$_POST[vocontrato::$nmAtrCdContrato];
		$vocontrato->tipo = @$_POST[vocontrato::$nmAtrTipoContrato];
		$vocontrato->cdEspecie = @$_POST[vocontrato::$nmAtrCdEspecieContrato];
		$vocontrato->sqEspecie = @$_POST[vocontrato::$nmAtrSqEspecieContrato];
		
		$this->tipoExcludente = @$_POST[static::$NmAtrTipoExcludente];
		$vocontrato->cdAutorizacao = @$_POST[vocontrato::$nmAtrCdAutorizacaoContrato];
		
		$this->vodemanda = $vodemanda;
		$this->vocontrato = $vocontrato;

		$this->nmContratada = @$_POST[vopessoa::$nmAtrNome];
		$this->docContratada = @$_POST[vopessoa::$nmAtrDoc];
		$this->dtUltMovimentacaoInicial = @$_POST[static::$NmAtrDtUltimaMovimentacaoInicial];
		$this->dtUltMovimentacaoFinal= @$_POST[static::$NmAtrDtUltimaMovimentacaoFinal];

		$this->dtReferenciaInicial = @$_POST[static::$NmAtrDtReferenciaInicial];
		$this->dtReferenciaFinal= @$_POST[static::$NmAtrDtReferenciaFinal];
		
		$this->cdSetorDocumento = @$_POST[voDocumento::$nmAtrCdSetor];
		$this->tpDocumento = @$_POST[voDocumento::$nmAtrTp];
		$this->sqDocumento = @$_POST[voDocumento::$nmAtrSq];
		$this->anoDocumento = @$_POST[voDocumento::$nmAtrAno];
		$this->cdDemandaInicial = @$_POST[self::$NmAtrCdDemandaInicial];
		$this->cdDemandaFinal = @$_POST[self::$NmAtrCdDemandaFinal];
		
		$this->vlGlobalInicial = @$_POST[self::$NmAtrVlGlobalInicial];
		$this->vlGlobalFinal = @$_POST[self::$NmAtrVlGlobalFinal];
		
		$this->cdUsuarioTramitacao = @$_POST[self::$NmAtrCdUsuarioTramitacao];
		$this->cdClassificacaoContrato = @$_POST [voContratoInfo::$nmAtrCdClassificacao];
		$this->inMaoDeObra = @$_POST [voContratoInfo::$nmAtrInMaoDeObra];
		$this->inOR_AND = @$_POST[self::$NmAtrInOR_AND];
		$this->inOR_AND_Fase = @$_POST[self::$NmAtrInOR_AND_Fase];
		$this->inComPAAPInstaurado = @$_POST[self::$NmAtrInComPAAPInstaurado];
		$this->inSEI = @$_POST[self::$NmAtrInSEI];

		$this->cdSetorImplementacaoEconti = @$_POST[self::$NmAtrCdSetorImplementacaoEConti];
		if($this->inOR_AND == null){
			$this->inOR_AND = constantes::$CD_OPCAO_OR;
		}
				
		if($this->cdOrdenacao == null){
			$this->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
		}
		
		$this->nuTempoVidaMinimo = @$_POST[static::$ID_REQ_NuTempoVidaMinimo];
		$this->nuTempoVidaMinimoUltimaTram = @$_POST[static::$ID_REQ_NuTempoVidaMinimoUltimaTram];
		$this->inMonitorar = @$_POST[static::$ID_REQ_InMonitorar];
	}
	 	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";

		$nmTabela = voDemanda::getNmTabelaStatic($this->isHistorico());
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabelaStatic(false);
		$nmTabelaTramitacaoDoc = voDemandaTramDoc::getNmTabelaStatic(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
		$nmTabelaDemandaPL = voDemandaPL::getNmTabelaStatic(false);
		$nmTabelaProcLic = voProcLicitatorio::getNmTabelaStatic(false);
		$nmTabelaPA = voPA::getNmTabelaStatic(false);
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
		
		$consultaDocumento = $this->temDocumentoAnexo == constantes::$CD_SIM || $this->tpDocumento != null || $this->cdSetorDocumento != null || $this->sqDocumento != null || $this->anoDocumento != null;
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
			
			if($this->anoDocumento != null){
				$filtro .= " AND " . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrAnoDoc . "="
						. getVarComoNumero($this->anoDocumento);
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
				
		if ($this->vodemanda->tipo != null 
				&& $this->vodemanda->tipo != "" 
				&& !$this->isAtributoArrayVazio($this->vodemanda->tipo)) {
					
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrTipo;
			
			$tipoDem = $this->vodemanda->tipo;
			
			/*if($tipoDem == dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO){			
				$tipoDem = array_keys(dominioTipoDemanda::getColecaoTipoDemandaContrato(false));
			}*/
			
			if(is_array($tipoDem)){
				$filtro .= 	" IN (" . getSQLStringFormatadaColecaoIN($tipoDem, false) . ") ";
				
			}else{
				$filtro .= 	" = " . $tipoDem;				
			}				
			
			$conector  = "\n AND ";
		}
		
		$fasePlanilha = $this->fasePlanilha;
		if (isAtributoValido($fasePlanilha) && ! $this->isAtributoArrayVazio ( $fasePlanilha )) {
			//echo "entrou2";
			//$inOrAndFase = $this->inOR_AND_Fase;
			$strFiltroFase = getSQLBuscarAtributoSimOuNaoOuSeNulo (
					$fasePlanilha,
					static::getDadosContratoColecaoCheckBox($this->isHistorico()),
					constantes::$CD_OPCAO_AND);
			// echo $strFiltroTpDemanda;
			$filtro = $filtro . $conector . $strFiltroFase;
			$conector = "\n AND ";
		}
		
		$fase = $this->vodemanda->fase;
		if ($fase != null
				&& $fase != ""
				&& !$this->isAtributoArrayVazio($fase)) {
					//echo "entrou1";
					$inOrAndFase = $this->inOR_AND_Fase;					
					$strFiltroFase = getSQLBuscarStringCampoSeparador($fase, voDemanda::$nmAtrFase, $inOrAndFase);
					//echo $strFiltroTpDemanda;
					$filtro = $filtro . $conector . $strFiltroFase;
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
			$clausulaReajuste = " $nmTabela." .voDemanda::$nmAtrInTpDemandaReajusteComMontanteA . " = " . getVarComoString($this->vodemanda->inTpDemandaReajusteComMontanteA);
			
			if($reajuste == dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_A
					|| $reajuste == dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_B){
				
						$clausulaReajuste .= " OR $nmTabela." .voDemanda::$nmAtrInTpDemandaReajusteComMontanteA
									. " = "
									. getVarComoString(dominioTipoReajuste::$CD_REAJUSTE_AMBOS)
						;				
			}
			$filtro = $filtro . $conector . "($clausulaReajuste)";
					
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
		
		if($this->cdSetorPassagem != null){
			$setorPassagem = $this->cdSetorPassagem;
			if(!is_array($setorPassagem)){
				$filtro = $filtro . $conector
				. static::getSQLSetorPassagem($nmTabela, $nmTabelaTramitacao, $this->cdSetorPassagem);
			}else{
				$conectorTemp  = " AND ";
				$filtroTemp =  "(";
				
				$temPeloMenosUm = false;
				foreach ($setorPassagem as $setor){
					if($setor != ""){
					//	echoo($setor);
						$filtroTemp .= static::getSQLSetorPassagem($nmTabela, $nmTabelaTramitacao, $setor) . $conectorTemp;
						$temPeloMenosUm = true;
					}
				}
				$filtroTemp = removerUltimaString($conectorTemp, $filtroTemp);
				$filtroTemp .= ") ";
				
				if($temPeloMenosUm){
					$filtro = $filtro . $conector . $filtroTemp;
					$conector  = "\n AND ";
				}
				
			}
			
		}
		
		
		if($this->vodemanda->prioridade != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrPrioridade
			. " = "
					. $this->vodemanda->prioridade
					;
		
					$conector  = "\n AND ";
		}
		
		/*if($this->vodemanda->situacao != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrSituacao
			. " = "
					. $this->vodemanda->situacao
					;
		
					$conector  = "\n AND ";
		}*/
		
		if ($this->vodemanda->situacao != null 
				&& (!is_array($this->vodemanda->situacao) || (is_array($this->vodemanda->situacao) && !$this->isAtributoArrayVazio($this->vodemanda->situacao)))) {
						
			$comparar = " = '" . $this->vodemanda->situacao . "'";
			$colecaoAComparar = $this->vodemanda->situacao;
			
			if(is_array($colecaoAComparar)){
				//acrescenta os itens que compoem o A FAZER
				if(in_array(dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_FAZER, $colecaoAComparar)){					
					$colecaoAComparar = array_merge($colecaoAComparar, array_keys(dominioSituacaoDemanda::getColecaoAFazer()));					
					$colecaoAComparar = removeElementoArray($colecaoAComparar, dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_FAZER);					
				}									
				$comparar = " IN (" . getSQLStringFormatadaColecaoIN($colecaoAComparar, true) . ")";
			}
				
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrSituacao . $comparar;
				
			$conector = "\n AND ";
		}		
		
		if ($this->tipoExcludente != null && $this->tipoExcludente != "" && !$this->isAtributoArrayVazio($this->tipoExcludente)) {
			$comparar = " <> '" . $this->tipoExcludente. "'";
			if(is_array($this->tipoExcludente)){		
				$comparar = " NOT IN (" . getSQLStringFormatadaColecaoIN($this->tipoExcludente, true) . ")";
			}
		
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrTipo . $comparar;
		
			$conector = "\n AND ";
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
		
		if(isAtributoValido($this->inCdResponsavelUNCT)){
			$comparacao = " IS NOT NULL ";
			if(constantes::$CD_OPCAO_NENHUM == $this->inCdResponsavelUNCT){
				$comparacao = " IS NULL ";
			}
			
			$filtro = $filtro . $conector
			. $nmTabela . "." .voDemanda::$nmAtrCdPessoaRespUNCT 
			. " $comparacao "
					;
		
					$conector  = "\n AND ";
		}		
		
		if(isAtributoValido($this->inMonitorar)){				
			$filtro = $filtro . $conector
			. $nmTabela . "." .voDemanda::$nmAtrInMonitorar . " = " . getVarComoString($this->inMonitorar);
			;
		
			$conector  = "\n AND ";
			if($this->numPrazoMonitorar != null){
				/*$filtro = $filtro . $conector
				. " (("
						. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrCdSetorDestino
						. " IS NULL AND " 
						. getDataSQLDiferencaDias("$nmTabela." .voDemanda::$nmAtrDtReferencia, getVarComoDataSQL(getDataHoje()))
						. " >= " . vodemanda::$NUM_PRAZO_MONITORAMENTO
						. " ) OR ("
						. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrCdSetorDestino
						. " IS NOT NULL AND "
						. getDataSQLDiferencaDias("$nmTabelaTramitacao." .voDemandaTramitacao::$nmAtrDtReferencia, getVarComoDataSQL(getDataHoje()))
						. " >= " . vodemanda::$NUM_PRAZO_MONITORAMENTO
						. "))";	*/

						$filtro = $filtro . $conector
						. filtroConsultarDemandaGestao::getSQLNuTempoUltimaTram($nmTabelaTramitacao, $nmTabela) . " >= " . vodemanda::$NUM_PRAZO_MONITORAMENTO;						
						
			}
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
		
		if($this->dtReferenciaInicial != null){
			$filtro = $filtro . $conector 
			. "$nmTabela." . voDemanda::$nmAtrDtReferencia . " >= " . getVarComoDataSQL($this->dtReferenciaInicial);
		
			$conector  = "\n AND ";
		}
		
		if($this->dtReferenciaFinal != null){
			$filtro = $filtro . $conector
			. "$nmTabela." . voDemanda::$nmAtrDtReferencia . " <= " . getVarComoDataSQL($this->dtReferenciaFinal);
		
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
		
		if($this->vodemanda->cdPessoaRespATJA != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrCdPessoaRespATJA;
			
			$temp = " = " . $this->vodemanda->cdPessoaRespATJA;			
			$temp = $this->vodemanda->cdPessoaRespATJA == constantes::$CD_OPCAO_NENHUM?" IS NULL ":$temp;
			$filtro .= $temp;
		
			$conector  = "\n AND ";
		}		
		
		if($this->vodemanda->cdPessoaRespUNCT != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrCdPessoaRespUNCT;
				
			$temp = " = " . $this->vodemanda->cdPessoaRespUNCT;
			$temp = $this->vodemanda->cdPessoaRespUNCT == constantes::$CD_OPCAO_NENHUM?" IS NULL ":$temp;
			$filtro .= $temp;
		
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
		
		if($this->vocontrato->cdEspecie != null){
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
			. " <= "
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
		
		if($this->inContratoComDtPropostaVencida != null){
			/*if($this->vocontrato->dtProposta == null){
				throw new excecaoGenerica("Consulta data proposta futura: campo obrigatório: vocontrato->dtproposta.");
			}*/

			//a data da comparacao eh a data de hoje
			$dtReferencia = getVarComoDataSQL(getDataHoje());
			//$dtReferencia = getVarComoDataSQL($this->vocontrato->dtProposta);
			$atributoDataReajuste = "COALESCE($nmTabelaContratoInfo" . "." .voContratoInfo::$nmAtrDtBaseReajuste 
						. ",$nmTabelaContratoInfo." . voContratoInfo::$nmAtrDtProposta 
						. ",$nmTabelaContrato ." . vocontrato::$nmAtrDtAssinaturaContrato
						.")";
			//$nmAtributoDataProposta = $nmTabelaContratoInfo . "." .voContratoInfo::$nmAtrDtProposta;
			$dtPropostaPAram = $atributoDataReajuste;
			//CONSIDERA 1 ANO ANTES DO ATUAL: isto porque quando houver diferenca de um ano, o DATEDIFF usado no metodo getDataSQLDiferencaAnos abaixo, trara
			//mais que 365 dias...dai tem-se que a operacao datediff/365 será maior que 1!!! e o valor da 'operacao' serah maior que zero, na validacao abaixo
			$ano = "YEAR($dtReferencia)-1";
			$mes = "MONTH($dtPropostaPAram)";
			//considera o dia 15 do mes como dia limite para obtencao do indice de reajuste exigido por lei
			//ver data da liberacao dos indices em https://www.indiceseindicadores.com.br/inpc/
			//dai que foi usado o dia 15 como media
			$dia = "15";			
			//$dia = "DAY($dtPropostaPAram)";
			$dtPropostaPAram = getDataSQLFormatada($ano,$mes, $dia);
			
			//echo "$dtReferencia";						
			//se a diferenca de anos for zero, quer dizer que nao ha diferenca de 1 ano
			//nesse caso, o vencimento da data da proposta nao ocorreu, nao podendo ser a demanda analisada para fins de reajuste
			if(getAtributoComoBooleano($this->inContratoComDtPropostaVencida)){
				//desejam-se as demandas com propostas vencidas
				$operacao = " > 0 ";
			}else{
				//desejam-se as demandas com propostas a vencer
				$operacao = " = 0 ";
			}			
			
			//se a data da proposta for nula, exibe o alerta de todo o jeito, ate que ela seja preenchida
			//ainda verifica se tem ou nao montanteA, caso tenha, traz a demanda pois ela sera analisada de imediato
			//se nao tiver montanteA, trarah apenas em caso positivo de aniversario da data da proposta
			$conjuntoSQLMontanteA = "'".dominioTipoReajuste::$CD_REAJUSTE_AMBOS."','" . dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_A . "'"; 
			$conjuntoSQLMontanteB = "'".dominioTipoReajuste::$CD_REAJUSTE_OUTROS 
									."','" . dominioTipoReajuste::$CD_REAJUSTE_AMBOS
									."','" . dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_B 
									. "'";
			
			$nmAtributoInTpDemandaReajusteComMontanteA = voDemanda::$nmAtrInTpDemandaReajusteComMontanteA; 
			$sqlTrazerTipoReajusteComMontanteA =  " $nmAtributoInTpDemandaReajusteComMontanteA IN ($conjuntoSQLMontanteA) ";
			$sqlTrazerTipoReajusteComMontanteB = " $nmAtributoInTpDemandaReajusteComMontanteA IN ($conjuntoSQLMontanteB) ";
			
			/*$sqlIsDemandaSAD =
					"($nmTabela." . voDemanda::$nmAtrTpDemandaContrato . "=".dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE
					. " AND "
					. "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrCdClassificacao . "<>".dominioClassificacaoContrato::$CD_LOCACAO_IMOVEL
					. " AND "
					. $this->getSQLInternoIsDemandaSAD($nmTabelaContratoInfo, $nmTabelaContrato)
					. ")";*/
			
			//para demandas SAD nao ha preocupacao de listar aqui
			//pois elas aparecerao no lugar especifico de DEMANDAS SAD PRIORIZADAS
			$filtro = $filtro . $conector
			. " (
				$atributoDataReajuste IS NULL 
				OR 
				$nmAtributoInTpDemandaReajusteComMontanteA IS NULL 
				OR 
				$sqlTrazerTipoReajusteComMontanteA 
				OR 
				($sqlTrazerTipoReajusteComMontanteB AND "
				//basta comparar se o mes da data de referencia (hoje) eh maior ou igual ao mes da data de comparacao
				//se for, significa que o tempo necessario para se ter o calculo do indice, que eh de 1 ano, ja passou
				//. " MONTH($dtReferencia) >= MONTH($dtPropostaPAram) "
				// verifica se transcorreu 1 ano da data base de reajuste
				. getDataSQLDiferencaAnos($dtPropostaPAram, $dtReferencia)
				// . $operacao
				. ")) ";
			
			$conector  = "\n AND ";
		}
						
		if($this->inRetornarReajusteSeLocacaoImovel != null && !getAtributoComoBooleano($this->inRetornarReajusteSeLocacaoImovel)){
			$filtro = $filtro . $conector
			. " NOT ($nmTabela." .voDemanda::$nmAtrTipo 
			. " = "
			. dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO_REAJUSTE
			. " AND $nmTabelaContratoInfo." .voContratoInfo::$nmAtrCdClassificacao
			. " IS NOT NULL "
			. " AND $nmTabelaContratoInfo." .voContratoInfo::$nmAtrCdClassificacao
			. " = "
			. dominioClassificacaoContrato::$CD_LOCACAO_IMOVEL
			. ")";
					$conector  = "\n AND ";
		
		}
		
		if($this->voproclic->cd != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaPL . "." .voDemandaPL::$nmAtrCdProcLic
			. " = "
					. $this->voproclic->cd
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voproclic->ano != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaPL . "." .voDemandaPL::$nmAtrAnoProcLic
			. " = "
					. $this->voproclic->ano
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voproclic->cdModalidade != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaPL . "." .voDemandaPL::$nmAtrCdModalidadeProcLic
			. " = "
					. getVarComoString($this->voproclic->cdModalidade)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voproclic->cdCPL != null){
			$filtro = $filtro . $conector
			. $nmTabelaProcLic . "." .voProcLicitatorio::$nmAtrCdCPL
			. " = "
					. getVarComoNumero($this->voproclic->cdCPL)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voPA->cdPA != null){
			$filtro = $filtro . $conector
			. $nmTabelaPA . "." .voPA::$nmAtrCdPA
			. " = "
					. $this->voPA->cdPA
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voPA->anoPA != null){
			$filtro = $filtro . $conector
			. $nmTabelaPA . "." .voPA::$nmAtrAnoPA
			. " = "
					. $this->voPA->anoPA
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->nuTempoVidaMinimo != null){
			$filtro = $filtro . $conector
			. filtroConsultarDemandaGestao::getSQLNuTempoVida($nmTabela)
			. " >= "
					. $this->nuTempoVidaMinimo
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->nuTempoVidaMinimoUltimaTram != null){
			//if($this->vodemanda->cdSetorDestino != null){
			$filtro = $filtro . $conector
			. filtroConsultarDemandaGestao::getSQLNuTempoUltimaTram($nmTabelaTramitacao, $nmTabela)
			. " >= "
					. $this->nuTempoVidaMinimoUltimaTram
					;
		
					$conector  = "\n AND ";
		}
		
		$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);

		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	/**
	 * Metodo que relaciona o codigo do dominio ao atributo no banco com cujo valor sera comparado
	 * eh usado quando a consulta tiver dados check box de uma entidade, cujo filtro eh do tipo sim ou nao via checkbox
	 * @return string[]
	 */
	static function getDadosContratoColecaoCheckBox($isTabHistorico) {
		$retorno = array (
				dominioFaseDemanda::$CD_ASSINADO => static::$NM_TABELA_DADOS_CONTRATO_DEMANDA . "." . vocontrato::$nmAtrDtAssinaturaContrato,
				dominioFaseDemanda::$CD_PUBLICADO => static::$NM_TABELA_DADOS_CONTRATO_DEMANDA . "." . vocontrato::$nmAtrDtPublicacaoContrato,
		);
	
		return $retorno;
	}
	
		
	/**
	 * retorna o SQL que permite verificar se uma demanda passou por um setor
	 * @param unknown $nmTabela
	 * @param unknown $nmTabelaTramitacao
	 * @param unknown $cdSetorPassagem
	 * @return string
	 */
	static function getSQLSetorPassagem($nmTabela, $nmTabelaTramitacao, $cdSetorPassagem){
		if($cdSetorPassagem != null){
			$retorno =
			" EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrAno
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCd
					. " AND (" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCdSetorOrigem. "=" . $cdSetorPassagem
					. " OR "
							. $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "=" . $cdSetorPassagem
							. "))\n ";
		}
		return $retorno;
		
	}
	
	static function getSQLDataDemandaMovimentacao($dataComparacao, $tipoOperacao){		
			$colDemandaTram = voDemandaTramitacao::getNmTabela() . "." .voDemandaTramitacao::$nmAtrDhInclusao;
			$colDemanda = voDemanda::getNmTabela(). "." .voDemanda::$nmAtrDhUltAlteracao;
			$dtUltMovimentacao = getVarComoDataSQL($dataComparacao);
			
			$retorno = 
			" ((". $colDemandaTram
			. " IS NOT NULL AND DATE(". $colDemandaTram
			. ") $tipoOperacao $dtUltMovimentacao "
			. ") OR "
				. "( $colDemandaTram IS NULL AND $colDemanda "
				. " IS NOT NULL AND DATE(". $colDemanda
				. ") $tipoOperacao "
				. $dtUltMovimentacao
			. ")) ";
									
		return $retorno;
	}
	
	function getSQLInternoIsDemandaSAD($nmTabelaContratoInfo, $nmTabelaContrato){
		$arrayAtributosCOALESCE = array($nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdAutorizacaoContrato, $nmTabelaContrato . "." . voContrato::$nmAtrCdAutorizacaoContrato); 
		$strComparacao = getSQLCOALESCE($arrayAtributosCOALESCE);			
		$colecaoAutoSAD = array(dominioAutorizacao::$CD_AUTORIZ_SAD);			
		$retorno = $strComparacao . voContratoInfo::getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutoSAD, constantes::$CD_OPCAO_OR);
			
		return $retorno;
	}
	
	function isSetorAtualSelecionado(){
		$cdSetorAtual = $this->vodemanda->cdSetorDestino;
		return $cdSetorAtual != null && $cdSetorAtual != "";		
	}
	
	function getAtributoOrdenacaoAnteriorDefault(){
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		//$retorno = $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " " . $this->cdOrdenacao; 
		return $retorno; 		
	}
	
	function getAtributoOrdenacaoDefault(){
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		$retorno = $nmTabelaDemanda . "." . voDemanda::$nmAtrPrioridade . " " . constantes::$CD_ORDEM_CRESCENTE
		//. "," . $nmTabelaDemanda . "." . voDemanda::$nmAtrDhInclusao . " " . constantes::$CD_ORDEM_CRESCENTE;
		. "," . filtroConsultarDemandaGestao::$NmColNuTempoUltimaTram . " " . constantes::$CD_ORDEM_DECRESCENTE;
		/*$retorno .= ", $nmTabelaDemanda." . voDemanda::$nmAtrAno . " " . constantes::$CD_ORDEM_CRESCENTE;
		$retorno .= ", $nmTabelaDemanda." . voDemanda::$nmAtrCd . " " . constantes::$CD_ORDEM_CRESCENTE;*/
		return $retorno;
	}
	
	function getAtributosOrdenacao(){
		if($this->isSetorAtualSelecionado()){
			$atributoDtReferenciaSetorAtual = array(static::$NmColDtReferenciaSetorAtual => "Dt.Chegada.SetorAtual");
		}
		
		$varAtributos = array(
				filtroManterDemanda::$NmColDhUltimaMovimentacao => "Data.Movimentação",
				voDemanda::$nmAtrAno => "Ano",
				voDemanda::$nmAtrCd => "Número",
				voDemanda::$nmAtrDtReferencia => "Data.Abertura",				
				voDemanda::$nmAtrPrioridade => "Prioridade",				
				voDemanda::$nmAtrTipo => "Tipo",
				filtroConsultarDemandaGestao::$NmColNuTempoUltimaTram => "Prazo Atual",
		);
		
		if($atributoDtReferenciaSetorAtual != null)
			$varAtributos = putElementoArray2NoArray1ComChaves ($atributoDtReferenciaSetorAtual, $varAtributos);
				
		return $varAtributos;
	}	

}

?>