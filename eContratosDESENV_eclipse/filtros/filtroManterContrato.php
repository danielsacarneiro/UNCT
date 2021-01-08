<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "voentidade.php");
include_once (caminho_lib . "filtroManter.php");
include_once (caminho_util . "bibliotecaHTML.php");
class filtroManterContrato extends filtroManter {
	public $nmFiltro = "filtroManterContrato";
	
	public static $NM_TAB_MAXSQCONTRATO = "NM_TAB_MAXSQCONTRATO";
	public static $ID_REQ_InGestor= "ID_REQ_InGestor";	
	public static $ID_REQ_InPublicado = "ID_REQ_InPublicado";
	public static $nmAtrInTrazerConsolidadoPorVigencia = "nmAtrInTrazerConsolidadoPorVigencia";	
	public static $nmAtrAnoArquivo = "nmAtrAnoArquivo";
	public static $nmAtrTpDemanda = "nmAtrTpDemanda";
	public static $NmAtrInOR_AND = "NmAtrInOR_AND";
	
	public static $ID_REQ_DtAssinaturaInicial = "ID_REQ_DtAssinaturaInicial";
	public static $ID_REQ_DtAssinaturaFinal = "ID_REQ_DtAssinaturaFinal";
	
	static $NmAtrVlGlobalInicial = "NmAtrVlGlobalInicial";
	static $NmAtrVlGlobalFinal = "NmAtrVlGlobalFinal";
	
	var $InOR_AND;
	var $cdAutorizacao = "";	
		
	var $cdContrato;
	var $anoContrato;
	var $anoArquivo;
	var $tipo;
	var $especie;
	var $cdEspecie;
	var $sqEspecie;
	var $colecaoCdEspecie;
	var $modalidade;
	var $cdModalidade;
	
	var $contratada;
	var $docContratada;
	var $gestor;
	
	var $objeto;
	var $dtVigenciaInicial;
	var $dtVigenciaFinal;
	
	var $dtAssinaturaInicial;
	var $dtAssinaturaFinal;
	
	var $dtInicio1;
	var $dtInicio2;
	var $dtFim1;
	var $dtFim2;
	var $dtInclusao;
	
	var $cdConsultarArquivo;
	var $inTrazerConsolidadoVigencia;
	var $inPublicado;
	var $inGestor;
	
	var $tpDemanda;
	var $licon;
	var $empenho;
	
	var $voproclic;
	
	var $vlGlobalInicial;
	var $vlGlobalFinal;
	
	// ...............................................................
	// construtor
	
	/*function __construct1($pegarFiltrosDaTela) {
		parent::__construct1($pegarFiltrosDaTela);
		
		$querySelect = "SELECT * ";
		$queryJoin = " FROM " . vocontrato::getNmTabelaStatic($this->isHistorico);
			
		//ACRESCENTA A CONSULTA
		$this->setQueryFromJoin($queryJoin);
		$this->setQuerySelect($querySelect);
				
		if($pegarFiltrosDaTela){
			$this->getFiltroFormulario();		
		}	
	}*/
	
	function getFiltroFormulario(){
		$this->voproclic = new voProcLicitatorio();
		$this->voproclic->getDadosFormulario();
		$this->cdContrato = @$_POST [vocontrato::$nmAtrCdContrato];
		$this->anoContrato = @$_POST [vocontrato::$nmAtrAnoContrato];
		$this->anoArquivo = @$_POST [self::$nmAtrAnoArquivo];
		$this->tipo = @$_POST [vocontrato::$nmAtrTipoContrato];
		$this->especie = @$_POST [vocontrato::$nmAtrEspecieContrato];
		$this->cdEspecie = @$_POST [vocontrato::$nmAtrCdEspecieContrato];
		
		//var_dump($this->cdEspecie);
		if($this->cdEspecie == null){
			$this->cdEspecie = @$_POST [vocontrato::$nmAtrCdEspecieContrato. "[]"];
		}
		$this->sqEspecie = @$_POST [vocontrato::$nmAtrSqEspecieContrato];
		
		$this->modalidade = @$_POST [vocontrato::$nmAtrModalidadeContrato];
		$this->cdModalidade = @$_POST [vocontrato::$nmAtrCdModalidadeProcessoLicContrato];
		
		$this->contratada = @$_POST [vocontrato::$nmAtrContratadaContrato];
		$this->docContratada = @$_POST [vocontrato::$nmAtrDocContratadaContrato];
		$this->gestor = @$_POST [vocontrato::$nmAtrGestorContrato];
		
		$this->objeto = @$_POST [vocontrato::$nmAtrObjetoContrato];
		$this->dtVigenciaInicial = @$_POST [vocontrato::$nmAtrDtVigenciaInicialContrato];
		$this->dtVigenciaFinal = @$_POST [vocontrato::$nmAtrDtVigenciaFinalContrato];
		$this->dtAssinaturaInicial = @$_POST [static::$ID_REQ_DtAssinaturaInicial];
		$this->dtAssinaturaFinal = @$_POST [static::$ID_REQ_DtAssinaturaFinal];
		
		$this->dtInicio1 = @$_POST ["dtInicio1"];
		$this->dtInicio2 = @$_POST ["dtInicio2"];
		$this->dtFim1 = @$_POST ["dtFim1"];
		$this->dtFim2 = @$_POST ["dtFim2"];
		$this->dtInclusao = @$_POST [voentidade::$nmAtrDhInclusao];
		
		$this->cdConsultarArquivo = @$_POST ["cdConsultarArquivo"];
		$this->inTrazerConsolidadoVigencia = @$_POST [self::$nmAtrInTrazerConsolidadoPorVigencia];		
		$this->tpDemanda = @$_POST [self::$nmAtrTpDemanda];
		
		$this->cdAutorizacao = @$_POST [vocontrato::$nmAtrCdAutorizacaoContrato];
		$this->licon = @$_POST [vocontrato::$nmAtrInLicomContrato];
		$this->empenho = @$_POST [vocontrato::$nmAtrNumEmpenhoContrato];
		$this->inPublicado = @$_POST [static::$ID_REQ_InPublicado];
		$this->inGestor = @$_POST [static::$ID_REQ_InGestor];
		
		$this->vlGlobalInicial = @$_POST[self::$NmAtrVlGlobalInicial];
		$this->vlGlobalFinal = @$_POST[self::$NmAtrVlGlobalFinal];
				
		$this->InOR_AND = @$_POST[self::$NmAtrInOR_AND];
		if($this->InOR_AND == null){
			$this->InOR_AND = constantes::$CD_OPCAO_OR;
		}
		
		$this->isTpVigenciaMAxSq = dominioSimNao::getBooleanFormulario(self::$nmAtrIsTpVigenciaMAxSq);
		
	}
		
	function isSetaValorDefault() {
		$retorno = false;
		// verifica os filtros obrigatorios
		if ($this->isValidarConsulta 
				&& !isUsuarioAdmin() && $this->contratada == null && $this->docContratada == null && $this->anoContrato == null 
				&& $this->dtVigenciaInicial == null && $this->dtVigenciaFinal == null && $this->objeto == null && $this->dtVigencia == null) {
			$retorno = true;
			$this->temValorDefaultSetado = true;			
		}		
		return $retorno;		
	}
	
	function getFiltroConsultaSQL() {
		$filtro = "";
		$conector = "";
		$voContrato = new vocontrato ();
		
		/*
		 * $nmTabela = vocontrato::getNmTabela();
		 * if($isHistorico)
		 * $nmTabela = vocontrato::getNmTabelaHistorico();
		 */
		$isHistorico = $this->isHistorico;
		$nmTabela = $voContrato->getNmTabelaEntidade ( $this->isHistorico );
		$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic(false);
		
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			$this->anoContrato = anoDefault;
		}
		
		if ($this->cdContrato != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $this->cdContrato;
			
			$conector = "\n AND ";
		}
		
		if ($this->anoContrato != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $this->anoContrato;
			
			$conector = "\n AND ";
		}
		
		if ($this->tipo != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "='" . $this->tipo . "'";
			
			$conector = "\n AND ";
		}
				
		if ($this->cdModalidade != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrCdModalidadeProcessoLicContrato . "='" . $this->cdModalidade . "'";
				
			$conector = "\n AND ";
		}
		
		if ($this->modalidade != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrProcessoLicContrato . " LIKE '%" . utf8_encode ( $this->modalidade ) . "%'";
				
			$conector = "\n AND ";
		}
		
		if ($this->especie != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrEspecieContrato . " LIKE '%" . utf8_encode ( $this->especie ) . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->cdEspecie != null && !$this->isAtributoArrayVazio($this->cdEspecie)) {
			$comparar = " = '" . $this->cdEspecie . "'";
			if(is_array($this->cdEspecie)){			
				$comparar = " IN (" . getSQLStringFormatadaColecaoIN($this->cdEspecie, true) . ")";
			}
			
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrCdEspecieContrato . $comparar;
			
			$conector = "\n AND ";
		}
				
		if ($this->sqEspecie != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrSqEspecieContrato . "= " . getVarComoNumero($this->sqEspecie);
			$conector = "\n AND ";
		}
		
		if ($this->contratada != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrContratadaContrato . " LIKE '%" . utf8_encode ( $this->contratada ) . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->docContratada != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrDocContratadaContrato . "='" . documentoPessoa::getNumeroDocSemMascara($this->docContratada) . "'";
			
			$conector = "\n AND ";
		}
		
		if ($this->gestor != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrGestorContrato . " LIKE '%" . utf8_encode ( $this->gestor ) . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->objeto != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrObjetoContrato . " LIKE '%" . utf8_encode ( $this->objeto ) . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->dtVigenciaInicial != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrDtVigenciaInicialContrato . ">='" . getDataSQL ( $this->dtVigenciaInicial ) . "'";
			
			$conector = "\n AND ";
		}
		
		if ($this->dtVigenciaFinal != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrDtVigenciaFinalContrato . "<='" . getDataSQL ( $this->dtVigenciaFinal ) . "'";
			
			/*
			 * $filtro = $filtro . $conector
			 * . "("
			 * . $nmTabela. "." .vocontrato::$nmAtrDtVigenciaFinalContrato
			 * . "<='"
			 * . getDataSQL($this->voContrato->dtVigenciaFinal)
			 * . "' OR ("
			 * . $nmTabela. "." .vocontrato::$nmAtrDtVigenciaInicialContrato
			 * . "<='"
			 * . getDataSQL($this->voContrato->dtVigenciaFinal)
			 * . "' AND "
			 * . $nmTabela
			 * . "."
			 * .vocontrato::$nmAtrDtVigenciaFinalContrato
			 * . " IS NULL)) ";
			 */
			
			$conector = "\n AND ";
		}
		
		if ($this->dtAssinaturaInicial != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrDtAssinaturaContrato . ">='" . getDataSQL ( $this->dtAssinaturaInicial ) . "'";
				
			$conector = "\n AND ";
		}
		
		if ($this->dtAssinaturaFinal != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrDtAssinaturaContrato . "<='" . getDataSQL ( $this->dtAssinaturaFinal ) . "'";
			$conector = "\n AND ";
		}
		
		$pChaveTuplaComparacaoSemSequencial = array(
				vocontrato::$nmAtrCdContrato
				,vocontrato::$nmAtrAnoContrato
				, vocontrato::$nmAtrTipoContrato);
		
		if ($this->dtVigencia != null) {
			//$pChaveTuplaComparacaoSemSequencial = $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrAnoContrato. "," . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
			
			$pArrayParam = array(
					$nmTabela,
					vocontrato::$nmAtrSqContrato,
					$pChaveTuplaComparacaoSemSequencial,
					$pChaveTuplaComparacaoSemSequencial,
					$this->dtVigencia,
					vocontrato::$nmAtrDtVigenciaInicialContrato,
					vocontrato::$nmAtrDtVigenciaFinalContrato,
					$this->isTpVigenciaMAxSq,
					$filtro,
					
			);
				
			$filtro = $filtro . $conector . getSQLDataVigenteArrayParam($pArrayParam);
			
			//echo $filtro;
			
			$conector = "\n AND ";
		}
		
		$isTrazerMaiorSqVigente = $this->isTpVigenciaMAxSq;
		if($isTrazerMaiorSqVigente){
			$pNmColSequencial = vocontrato::$nmAtrSqContrato;
			
				if($filtro != null){
					$sqlFiltroInternoMaiorSq = " WHERE $filtro ";
				}
				
				$striAtributosChaveSemSq = formataArrayChaveTuplaComparacaoSequencial($pChaveTuplaComparacaoSemSequencial,$nmTabela);
				$filtro = $filtro . $conector .
				" (("
						. $striAtributosChaveSemSq
						. ", $nmTabela.$pNmColSequencial" 
						. ")\n IN \n( SELECT "
						. $striAtributosChaveSemSq
						. ", MAX($nmTabela.$pNmColSequencial)"
						. "\n FROM "
						. $nmTabela
						. $sqlFiltroInternoMaiorSq
						. "\n GROUP BY "
						. $striAtributosChaveSemSq
						. "))";		
				
						$conector = "\n AND ";
		}
				
		
		if ($this->dtInicio1 != null || $this->dtInicio2 != null) {
			$filtro = $filtro . $conector . getSQLIntervaloDatas ( $nmTabela, vocontrato::$nmAtrDtVigenciaInicialContrato, $this->dtInicio1, $this->dtInicio2 );
			
			$conector = "\n AND ";
		}
		
		if ($this->dtFim1 != null || $this->dtFim2 != null) {
			$filtro = $filtro . $conector . getSQLIntervaloDatas ( $nmTabela, vocontrato::$nmAtrDtVigenciaFinalContrato, $this->dtFim1, $this->dtFim2 );
			
			$conector = "\n AND ";
		}
		
		if ($this->dtInclusao != null) {
			$filtro = $filtro . $conector . "DATE($nmTabela" . "." . vocontrato::$nmAtrDhInclusao . ")='" . getDataSQL ( $this->dtInclusao ) . "'";
			
			$conector = "\n AND ";
		}
		
		if ($this->tpVigencia != null && $this->tpVigencia != constantes::$CD_OPCAO_TODOS) {
			if ($this->tpVigencia == dominioTpVigencia::$CD_OPCAO_VIGENTES) {
				$filtro = $filtro . $conector . getSQLDataVigenteSqSimples ( $nmTabela, vocontrato::$nmAtrDtVigenciaInicialContrato, vocontrato::$nmAtrDtVigenciaFinalContrato );
			} else {
				$filtro = $filtro . $conector . getSQLDataNaoVigenteSqSimples ( $nmTabela, vocontrato::$nmAtrDtVigenciaInicialContrato, vocontrato::$nmAtrDtVigenciaFinalContrato );
			}
			
			$conector = "\n AND ";
		}
				
		if ($this->tpDemanda != null && !$this->isAtributoArrayVazio($this->tpDemanda)) {
			$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
			$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
			$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela ();
				
			$queryJoin .= $this->getQueryFromJoin() . "\n INNER JOIN " . $nmTabelaDemandaContrato;
			$queryJoin .= "\n ON ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrTipoContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrCdContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrCdEspecieContrato;
			$queryJoin .= "\n AND ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabela . "." . vocontrato::$nmAtrSqEspecieContrato;
				
			$queryJoin .= "\n INNER JOIN " . $nmTabelaDemanda;
			$queryJoin .= "\n ON ";
			$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
			$queryJoin .= "\n AND " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
			
			// o proximo join eh p pegar a ultima tramitacao apenas, se houver
			$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno;
			$queryJoin .= "\n LEFT JOIN (";
			$queryJoin .= " SELECT MAX(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq . "," . $atributosGroup . " FROM " . $nmTabelaTramitacao . " GROUP BY " . $atributosGroup;
			$queryJoin .= ") TABELA_MAX";
			$queryJoin .= "\n ON " . $nmTabelaDemanda . "." . voDemandaTramitacao::$nmAtrAno . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrAno;
			$queryJoin .= "\n AND " . $nmTabelaDemanda . "." . voDemandaTramitacao::$nmAtrCd . " = TABELA_MAX." . voDemandaTramitacao::$nmAtrCd;
			
			$queryJoin .= "\n LEFT JOIN " . $nmTabelaTramitacao;
			$queryJoin .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno;
			$queryJoin .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd;
			$queryJoin .= "\n AND " . "TABELA_MAX." . voDemandaTramitacao::$nmAtrSq . " = " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrSq;				
			
			$this->setQueryFromJoin($queryJoin);
						
			if(is_array($this->tpDemanda) && count($this->tpDemanda) > 1){
				$comparar = " IN (" . getSQLStringFormatadaColecaoIN($this->tpDemanda, false) . ")";
			}else{
				$comparar = " = " . $this->tpDemanda[0];
			}
				
			//traz apenas das demandas abertas e que estao na ATJA
			$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrTipo . $comparar;
			$filtro = $filtro . " AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrSituacao . " = "  . dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA;
			$filtro = $filtro . " AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . " = "  . dominioSetor::$CD_SETOR_ATJA;
				
			$conector = "\n AND ";		
		}
		
		//echo $this->vocontrato->cdAutorizacao;
		if($this->cdAutorizacao != null){
			$strComparacao = $nmTabela . "." . voContrato::$nmAtrCdAutorizacaoContrato;
						
					if(!is_array($this->cdAutorizacao)){
						$filtro = $filtro . $conector
						//. $nmTabelaContrato. "." .vocontrato::$nmAtrCdAutorizacaoContrato
						. $strComparacao
						. " = "
								. $this->cdAutorizacao
								;
					}else{
		
						$colecaoAutorizacao = $this->cdAutorizacao;
						$filtro = $filtro . $conector . $strComparacao . voContratoInfo::getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutorizacao, $this->InOR_AND);
		
					}
						
					$conector  = "\n AND ";
		}
		
		if ($this->licon != null && $this->licon != constantes::$CD_OPCAO_TODOS) {
			$filtro = $filtro . $conector . "$nmTabela." . vocontrato::$nmAtrInLicomContrato. "=" . getVarComoString($this->licon);				
			$conector = "\n AND ";
		}
		
		if ($this->empenho != null && $this->empenho != "") {
			$filtro = $filtro . $conector . "$nmTabela." . vocontrato::$nmAtrNumEmpenhoContrato 
			. getSQLLike($this->empenho);
			$conector = "\n AND ";
		}
		
		if ($this->inPublicado != null && $this->inPublicado != "") {
			if($this->inPublicado == constantes::$CD_SIM){
				$temp = " IS NOT NULL ";
			}else{
				$temp = " IS NULL ";
			}
			$filtro = $filtro . $conector . "$nmTabela." . vocontrato::$nmAtrDtPublicacaoContrato . $temp;
			$conector = "\n AND ";
		}
		
		if ($this->inGestor != null && $this->inGestor != "") {
			if($this->inGestor == constantes::$CD_SIM){
				$temp = " IS NOT NULL ";
			}else{
				$temp = " IS NULL ";
			}
			$filtro = $filtro . $conector . "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrCdPessoaGestor . $temp;
			$conector = "\n AND ";
		}
		
		if($this->voproclic->cd != null){
			$filtro = $filtro . $conector
			. $nmTabela . "." .vocontrato::$nmAtrCdProcessoLicContrato
			. " = "
					. $this->voproclic->cd
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voproclic->ano != null){
			$filtro = $filtro . $conector
			. $nmTabela . "." .vocontrato::$nmAtrAnoProcessoLicContrato
			. " = "
					. $this->voproclic->ano
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vlGlobalInicial != null){
			$filtro = $filtro . $conector
			. $nmTabela . "." .vocontrato::$nmAtrVlGlobalContrato
			. " >= "
					. getVarComoDecimal($this->vlGlobalInicial);
					$conector  = "\n AND ";
		
		}
		
		//echo "global $this->vlGlobalFinal";
		if($this->vlGlobalFinal != null){
			$filtro = $filtro . $conector
			. $nmTabela . "." .vocontrato::$nmAtrVlGlobalContrato
			. " <= "
					. getVarComoDecimal($this->vlGlobalFinal);
					$conector  = "\n AND ";
		
		}		
		
		//serve para retirar a ambiguidade, quando existir, do atributo da ordenacao
		//nem sempre sera usado pelos filtros
		$this->formataCampoOrdenacao(new vocontrato());
		// finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	function getAtributoOrdenacaoDefault() {
		return vocontrato::getNmTabelaStatic ( $this->isHistorico ) . "." . vocontrato::$nmAtrSqContrato . " " . constantes::$CD_ORDEM_DECRESCENTE;
	}
	function getAtributosOrdenacao() {
		$varAtributos = array (
				"ct_exercicio" => "Ano",
				"ct_numero" => "Numero",
				"ct_tipo" => "Tipo",
				vocontrato::$nmAtrCdEspecieContrato => "Especie",
				"ct_contratada" => "Contratada",
				vocontrato::$nmAtrDtAssinaturaContrato => "Dt.Assinatura",
				"ct_valor_global" => "Vl.Global",
				vocontrato::$nmAtrSqContrato => "Sq.",
		);
		return $varAtributos;
	}
}

?>