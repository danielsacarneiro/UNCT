<?php
include_once (caminho_lib . "voentidade.php");
include_once ("dbDemanda.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
include_once (caminho_util . "dominioSetor.php");
include_once (caminho_funcoes . "demanda/dominioSituacaoDemanda.php");
include_once (caminho_funcoes . "demanda/dominioTipoDemanda.php");
include_once (caminho_funcoes . "demanda/dominioFaseDemanda.php");
include_once (caminho_funcoes . "demanda/dominioCaracteristicasDemanda.php");
include_once (caminho_funcoes . "demanda/dominioTipoDemandaContrato.php");
include_once (caminho_funcoes . "demanda/dominioPrioridadeDemanda.php");
include_once (caminho_funcoes . "demanda/dominioTipoReajuste.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");
include_once ("voContratoModificacao.php");

class voDemanda extends voentidade {
	static $ID_PAGINA_ENCAMINHAR_NOVO = "ID_PAGINA_ENCAMINHAR_NOVO";
	
	static $NUM_PRAZO_MONITORAMENTO = 5;
	static $MSG_IN_MONITORAR = "Permite aviso por email.";
	static $CD_MONITORAR_POR_DATA = "CD_MONITORAR_POR_DATA";
	
	static $ID_REQ_DIV_REAJUSTE_MONTANTE_A = "ID_REQ_DIV_REAJUSTE_MONTANTE_A";
	static $ID_REQ_InTemContrato  = "InTemContrato";	
	static $NM_DIV_SEI_EXISTENTE = "NM_DIV_SEI_EXISTENTE";
		
	static $nmAtrCd = "dem_cd";
	static $nmAtrAno = "dem_ex";
	static $nmAtrCdSetor = "dem_cd_setor";
	static $nmAtrCdSetorAtual = "NM_COL_SETOR_ATUAL";
	static $nmAtrTipo = "dem_tipo";	
	static $nmAtrTpDemandaContrato =  "dem_tp_contrato";
	static $nmAtrInTpDemandaReajusteComMontanteA = "dem_tp_temreajustemontanteA";
	static $nmAtrSituacao = "dem_situacao";
	static $nmAtrTexto = "dem_texto";
	static $nmAtrPrioridade = "dem_prioridade";
	static $nmAtrDtReferencia = "dem_dtreferencia";
	static $nmAtrInLegado = "dem_inlegado";
	static $nmAtrCdPessoaRespATJA = "dem_cdpessoaresp_atja";
	static $nmAtrCdPessoaRespUNCT = "dem_cdpessoaresp_unct";
	static $nmAtrFase =  "dem_fase";
	static $nmAtrInCaracteristicas =  "dem_incaracteristicas";
	static $nmAtrInMonitorar =  "dem_inmonitorar";

	static $nmAtrDtMonitoramento =  "dem_dtmonitoramento";
	//lembrar que o PRT foi migrado do demandatramitacao para ca
	static $nmAtrProtocolo = "dtm_prt";
	
	var $cd = "";
	var $ano = "";
	var $cdSetor = "";
	var $cdSetorAtual = "";
	var $tipo = "";
	
	var $tpDemandaContrato = "";
	var $inTpDemandaReajusteComMontanteA = "";
	var $situacao = "";
	var $texto = "";
	var $prioridade = "";
	var $dtReferencia = "";
	var $inLegado = "";
	
	var $cdPessoaRespATJA = "";
	var $cdPessoaRespUNCT = "";
	var $fase = "";
	var $inCaracteristicas = "";
	var $inMonitorar = "";
	var $dtMonitoramento = "";
	
	var $prt = "";
	var $dbprocesso = null;
	
	var $colecaoContrato = null;
	var $voProcLicitatorio = null;
	var $voSolicCompra = null;
	
	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct($arrayChave = null) {
		parent::__construct ($arrayChave);
		$this->temTabHistorico = true;
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();
		$this->colecaoContrato = array ();
		
		// retira os atributos padrao que nao possui
		// remove tambem os que o banco deve incluir default
		/*$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao 
		);
		$this->removeAtributos ( $arrayAtribRemover );
		$this->varAtributosARemover = $arrayAtribRemover;*/
				
		$arrayAtribInclusaoDBDefault = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao,		
		);
		$this->setaAtributosRemocaoEInclusaoDBDefault(null, $arrayAtribInclusaoDBDefault);
		
	}
	public static function getTituloDemandaGestaoPorSetor() {
		return "DEMANDA GEST�O POR SETOR";
	}
	public static function getTituloDemandaRendimentoJSP() {
		return "DEMANDA RENDIMENTO";
	}
	public static function getTituloDemandaUsuarioJSP() {
		return "DEMANDA USU�RIO";
	}
	public static function getTituloDemandaGestaoJSP() {
		return "DEMANDA GEST�O";
	}
	public static function getTituloJSP() {
		return "DEMANDA";
	}
	public static function getNmTabela() {
		return "demanda";
	}
	public static function getNmClassProcesso() {
		return "dbDemanda";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCd . "=" . $this->cd;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrAno . "=" . $this->ano;
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrAno,
				self::$nmAtrCd,
				self::$nmAtrTipo,
				self::$nmAtrTpDemandaContrato,
				self::$nmAtrInTpDemandaReajusteComMontanteA,
				self::$nmAtrCdSetor,
				self::$nmAtrSituacao,
				self::$nmAtrTexto,
				self::$nmAtrPrioridade,
				self::$nmAtrDtReferencia, 
				self::$nmAtrCdPessoaRespATJA,
				self::$nmAtrCdPessoaRespUNCT,
				self::$nmAtrFase,
				self::$nmAtrInCaracteristicas,
				self::$nmAtrInMonitorar,
				self::$nmAtrDtMonitoramento,
				self::$nmAtrProtocolo,
				self::$nmAtrInLegado
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrAno,
				self::$nmAtrCd 
		);
		
		return $retorno;
	}
	
	/*
	 * function temContratoParaIncluir(){
	 * $retorno = $this->voContrato->tipo != null && $this->voContrato->anoContrato != null && $this->voContrato->cdContrato != null;
	 * return $retorno;
	 * }
	 */
	function temContratoParaIncluir() {
		$retorno = $this->colecaoContrato != null && $this->colecaoContrato != "" && (count ( $this->colecaoContrato ) > 0);
		//var_dump($this->colecaoContrato);
		return $retorno;
	}
	
	function temProcLicitatorioParaIncluir() {
		$retorno = $this->voProcLicitatorio != null && $this->voProcLicitatorio->cd != null;
		return $retorno;
	}
	function temSolicCompraParaIncluir() {
		$retorno = $this->voSolicCompra != null && $this->voSolicCompra->cd != null;
		return $retorno;
	}
	/*
	 * function getVODemandaContrato(){
	 * $voDemanda = new voDemandaContrato();
	 * $voDemanda->anoDemanda = $this->ano;
	 * $voDemanda->cdDemanda = $this->cd;
	 * $voDemanda->voContrato = $this->voContrato;
	 *
	 * return $voDemanda;
	 * }
	 */
	function getVODemandaContrato($voContrato) {
		$voDemanda = new voDemandaContrato ();
		$voDemanda->anoDemanda = $this->ano;
		$voDemanda->cdDemanda = $this->cd;
		
		$voDemanda->voContrato = $voContrato;
		return $voDemanda;
	}
	function getVODemandaProcLicitatorio($voProcLic) {
		//$voProcLic = new voProcLicitatorio();
		$voDemanda = new voDemandaPL();
		$voDemanda->anoDemanda = $this->ano;
		$voDemanda->cdDemanda = $this->cd;
		$voDemanda->anoProcLic = $voProcLic->ano;
		$voDemanda->cdProcLic = $voProcLic->cd;
		$voDemanda->cdModProcLic = $voProcLic->cdModalidade;
		return $voDemanda;
	}
	function getVODemandaSolicCompra($voSolicCompra) {		
		$voDemandaSolic = new voDemandaSolicCompra();
		$voDemandaSolic->anoDemanda = $this->ano;
		$voDemandaSolic->cdDemanda = $this->cd;
		$voDemandaSolic->anoSolicCompra = $voSolicCompra->ano;
		$voDemandaSolic->cdSolicCompra = $voSolicCompra->cd;
		$voDemandaSolic->ug = $voSolicCompra->ug;
		return $voDemandaSolic;
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco [self::$nmAtrCd];
		$this->ano = $registrobanco [self::$nmAtrAno];
		$this->cdSetor = $registrobanco [self::$nmAtrCdSetor];
		$this->cdSetorAtual = $registrobanco [self::$nmAtrCdSetorAtual];
		$this->tipo = $registrobanco [self::$nmAtrTipo];
		
		$this->tpDemandaContrato = $registrobanco [self::$nmAtrTpDemandaContrato];
		$this->inTpDemandaReajusteComMontanteA = $registrobanco [self::$nmAtrInTpDemandaReajusteComMontanteA];
		$this->situacao = $registrobanco [self::$nmAtrSituacao];
		$this->texto = $registrobanco [self::$nmAtrTexto];
		$this->prioridade = $registrobanco [self::$nmAtrPrioridade];
		$this->dtReferencia = $registrobanco [self::$nmAtrDtReferencia];
		$this->cdPessoaRespATJA = $registrobanco [self::$nmAtrCdPessoaRespATJA];
		$this->cdPessoaRespUNCT = $registrobanco [self::$nmAtrCdPessoaRespUNCT];
		$this->fase = $registrobanco [self::$nmAtrFase];
		$this->inCaracteristicas = $registrobanco [self::$nmAtrInCaracteristicas];
		$this->inMonitorar = $registrobanco [self::$nmAtrInMonitorar];
		$this->dtMonitoramento = $registrobanco [self::$nmAtrDtMonitoramento];
		
		$this->prt = $registrobanco[self::$nmAtrProtocolo];
		$this->prt = voDemandaTramitacao::getNumeroPRTComMascara($this->prt, false);
		
		$this->inLegado = $registrobanco [self::$nmAtrInLegado];
		
		$this->getProcLicitatorioRegistroBanco($registrobanco);
		$this->getSolicCompraRegistroBanco($registrobanco);
		
		/*
		 * $chaveContrato = $registrobanco[vocontrato::$nmAtrCdContrato];
		 * if($chaveContrato != null){
		 * $voContrato = new vocontrato();
		 * $voContrato->cdContrato = $registrobanco[voDemandaContrato::$nmAtrCdContrato];
		 * $voContrato->anoContrato = $registrobanco[voDemandaContrato::$nmAtrAnoContrato];
		 * $voContrato->tipo = $registrobanco[voDemandaContrato::$nmAtrTipoContrato];
		 * $voContrato->sqEspecie = $registrobanco[voDemandaContrato::$nmAtrSqEspecieContrato];
		 * $voContrato->cdEspecie = $registrobanco[voDemandaContrato::$nmAtrCdEspecieContrato];
		 *
		 * $this->voContrato = $voContrato;
		 * }
		 */
	}
	function getDadosFormulario() {
		// constante definida em bibliotecahtml
		$this->cd = @$_POST [self::$nmAtrCd];
		$this->ano = @$_POST [self::$nmAtrAno];
		$this->cdSetor = @$_POST [self::$nmAtrCdSetor];
		$this->tipo = @$_POST [self::$nmAtrTipo];
		
		$this->tpDemandaContrato = @$_POST [voDemanda::$nmAtrTpDemandaContrato];
		$this->inTpDemandaReajusteComMontanteA = @$_POST [self::$nmAtrInTpDemandaReajusteComMontanteA];
		$this->situacao = @$_POST [self::$nmAtrSituacao];
		$this->texto = @$_POST [self::$nmAtrTexto];
		$this->prioridade = @$_POST [self::$nmAtrPrioridade];
		$this->dtReferencia = @$_POST [self::$nmAtrDtReferencia];
		$this->cdPessoaRespATJA = @$_POST [self::$nmAtrCdPessoaRespATJA];
		$this->cdPessoaRespUNCT = @$_POST [self::$nmAtrCdPessoaRespUNCT];
		$this->fase = @$_POST [voDemanda::$nmAtrFase];
		$this->inCaracteristicas = @$_POST [voDemanda::$nmAtrInCaracteristicas];
		/*if(is_array($this->inCaracteristicas)){
			$this->inCaracteristicas = static::getArrayComoStringCampoSeparador($this->inCaracteristicas);
		}*/
		
		$this->inMonitorar = @$_POST [voDemanda::$nmAtrInMonitorar];
		
		if($this->inMonitorar == constantes::$CD_SIM){
			$this->dtMonitoramento = @$_POST [self::$nmAtrDtMonitoramento];
		}
		
		$this->prt = @$_POST[self::$nmAtrProtocolo];
		$this->inLegado = @$_POST [self::$nmAtrInLegado];
		// quando existir
		// recupera quando da consulta da contratada, ao inserir o contrato na tela
		$chaveContrato = @$_POST [vopessoa::$ID_CONTRATO];
		// echo "chave contrato:" . $chaveContrato;		
		
		//$isEncaminharNovo = $this->tpDemandaContrato != null;
		$isEncaminharNovo = static::isPaginaEncaminharNovo();
		if (!$isEncaminharNovo  && $chaveContrato != null) {			
			//quando vem do encaminhar.php
			$this->setColecaoContratoFormulario ( $chaveContrato );
			//echo "enaminharvelho";
		}else if ($isEncaminharNovo){			
			//quando vem do encaminhar.novo.php
			$voContratoAvulso = new vocontrato();
			$voContratoAvulso->getDadosFormulario();
			//garante que o contrato so sera recuperado se pelo menos a chave logica (tipo, numero e exercicio) esteja preenchida
			//echo "funciona!";
			if($voContratoAvulso->isChaveLogicaValida()){
				$voContratoAvulso->getDadosFormulario();
				$this->colecaoContrato = array($voContratoAvulso);
			}
		}
		
		$this->getProcLicitatorioFormulario();
		$this->getSolicCompraFormulario();
		
		// completa com os dados da entidade
		$this->getDadosFormularioEntidade ();
	}
	function getSolicCompraFormulario() {
		$voSolicCompra = new voSolicCompra();
		$voSolicCompra->getDadosFormulario();
	
		if($voSolicCompra->isChavePrimariaPreenchida()){
			$this->voSolicCompra = $voSolicCompra;
		}
	}
	function getSolicCompraRegistroBanco($registrobanco) {
		$voSolicCompra = new voSolicCompra();
		$voSolicCompra->getDadosBanco($registrobanco);
		$this->voSolicCompra = $voSolicCompra;
	}
	function getProcLicitatorioFormulario() {
		$voProcLic = new voProcLicitatorio();
		$voProcLic->getDadosFormulario();
		
		if($voProcLic->isChavePrimariaPreenchida()){
			$this->voProcLicitatorio = $voProcLic; 
		}		
	}
	function getProcLicitatorioRegistroBanco($registrobanco) {
		$voProcLic = new voProcLicitatorio();
		$voProcLic->getDadosBanco($registrobanco);
		$this->voProcLicitatorio = $voProcLic;
	}
	function getContratoColecaoFormulario($itemColecao) {
		$voContrato = new vocontrato ();
		$voContrato->getChavePrimariaVOExplodeParam ($itemColecao);
		return $voContrato;		
	}
	function setColecaoContratoFormulario($colecao) {
		$retorno = null;
		if ($colecao != null) {
			//var_dump($colecao);
			$retorno = array ();
			//inclui o primeiro contrato
			$voContrato = $this->getContratoColecaoFormulario($colecao[0]);
			$retorno [] = $voContrato;
				
			foreach ( $colecao as $chaveContrato ) {				
				$voAtual = $this->getContratoColecaoFormulario($chaveContrato);
				if(!$voAtual->isIgualChavePrimaria($voContrato)){
					$retorno [] = $voAtual;
					$voContrato = $voAtual;
				}
			}
		}
		$this->colecaoContrato = $retorno;
	}
	function setColecaoContratoRegistroBanco($colecao) {
		$retorno = null;
		if ($colecao != null) {
			$retorno = array ();
			foreach ( $colecao as $registrobanco ) {
				$voContrato = new vocontrato ();
				$voContrato->getDadosBanco ( $registrobanco );
				$retorno [] = $voContrato;
			}
		}
		
		$this->colecaoContrato = $retorno;
	}
	function getContrato() {
		$retorno = null;
		if($this->colecaoContrato != null){
			$retorno = clone $this->colecaoContrato[0]; 
		}
		return  $retorno;
	}	
	function toString() {
		$retorno .= $this->ano;
		$retorno .= "," . $this->cd;
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->ano . CAMPO_SEPARADOR . $this->cd . CAMPO_SEPARADOR . $this->sqHist;
	}
	
	//se a chaveHTML for igual a getValorChavePrimaria nao precisa desse metodo
	//pq ja tem no voentidade
	function getValorChaveHTML(){
		return $this->ano . CAMPO_SEPARADOR . $this->cd . CAMPO_SEPARADOR . $this->sqHist . CAMPO_SEPARADOR . $this->situacao;
	}
	
	function getChavePrimariaVOExplode($array) {
		$this->ano = $array [0];
		$this->cd = $array [1];
		$this->sqHist = $array [2];
	}
	
	static function formataPRTParaApenasNumero($numPRT) {
		$formatado = str_replace(".", "", $numPRT);
		$formatado = str_replace("-", "", $formatado);
		$formatado = str_replace("/", "", $formatado);
		return $formatado;
	}
	
	static function isPRTValido($numPRT, $levantarExcecao=true) {
		$isValido = true;
		if($numPRT != null){
			$formatado = static::formataPRTParaApenasNumero($numPRT);
			$tamanho = strlen($formatado);
			//$isValido = ($tamanho == 18 || $tamanho == 22) && isNumero($formatado);
			$isValido = $tamanho == 18 || static::isPRTSEI($numPRT);
			if($levantarExcecao && !$isValido){
				throw new excecaoGenerica("PRT Inv�lido. Tamanho PRT: $tamanho");
			}
		}
		return $isValido;
	}
	
	/**
	 * verifica se o prt inserido eh do SEI
	 * @param unknown $numPRT
	 * @return boolean
	 */
	static function isPRTSEI($numPRT) {
		$retorno = false;
		if($numPRT != null){
			$formatado = static::formataPRTParaApenasNumero($numPRT);
			$retorno = strlen($formatado) == 22;
			
			if(!$retorno){				
				//verifica se tem formato SEI diferente, como o termo de adesao SEI 003.2020.011.SEFAZ.001
				$retorno = existeStr1NaStr2("SEFAZ", $formatado);
			}
		}
		return $retorno;
	}
	
	/**
	 * verifica se eh numero SEI padrao ou outro formado SEI, como os SEIs de termo de adesao da SAD no formato 003.2020.011.SEFAZ.001
	 * @param unknown $numPRT
	 * @return boolean
	 */
	static function isSEIPadrao($numPRT) {
		$retorno = false;
		if($numPRT != null){
			$formatado = static::formataPRTParaApenasNumero($numPRT);
			$tamanho = strlen($formatado);
			$retorno = static::isPRTSEI($numPRT) && $tamanho == 22;
		}
		return $retorno;
	}
	
	static function getNumeroPRTComMascara($numPRT, $levantarExcecao=true){
		$formatadoRetorno = $numPRT;
		if($numPRT != null){
			$formatado = static::formataPRTParaApenasNumero($numPRT);
			$tamanho = strlen($formatado);
			
			$isSEI = static::isPRTSEI($numPRT);
			if(static::isPRTValido($numPRT, $levantarExcecao)){
				//echo "valido";

				if($isSEI){
					//echo "eh sei";
					if(static::isSEIPadrao($numPRT)){
						$formatado  = substr( $numPRT, 0, 10 ) . '.';
						$formatado .= substr( $numPRT, 10, 6 ) . '/';
						$formatado .= substr( $numPRT, 16, 4 ) . '-';
						$formatado .= substr( $numPRT, 20, 2 );
					}else if($tamanho >= 18){
						$formatado  = substr( $numPRT, 0, 3 ) . '.';
						$formatado .= substr( $numPRT, 3, 4 ) . '.';
						$formatado .= substr( $numPRT, 7, 3 ) . '.';
						$formatado .= substr( $numPRT, 10, 5 ) . '.';
						$formatado .= substr( $numPRT, 15, 3 );
					}else{
						//SEI MENOR - GERALMENTE VEM DA SAD
						$formatado  = substr( $numPRT, 0, 3 ) . '.';
						$formatado .= substr( $numPRT, 3, 4 ) . '.';
						$formatado .= substr( $numPRT, 7, 5 ) . '.';
						$formatado .= substr( $numPRT, 12, 3 );
					}
				}else{
					//echo "nao eh sei";
					$formatado  = substr( $numPRT, 0, 4 ) . '.';
					$formatado .= substr( $numPRT, 4, 5 ) . '.';
					$formatado .= substr( $numPRT, 9, 4 ) . '.';
					$formatado .= substr( $numPRT, 13, 3 ) .'-';
					$formatado .= substr( $numPRT, 16, 2 );
				}
					
				$formatadoRetorno = $formatado;
			}
			/*else{	echo "nao eh PRT valido";
			
			}*/
		}
	
		return $formatadoRetorno;
	}
	
	static function getNumeroPRTSemMascara($numPRT, $levantarExcecao=true){
		//static::isPRTValido($numPRT, $levantarExcecao);
		$retorno = static::formataPRTParaApenasNumero($numPRT);
		return $retorno;
	}
	
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Demanda (N�mero - Ano): " . formatarCodigoAnoComplementoArgs ( $this->cd, $this->ano, TAMANHO_CODIGOS, null );
		if ($this->sqHist != null) {
			$retorno .= "<br>N�m. Hist�rico: " . complementarCharAEsquerda ( $this->sqHist, "0", TAMANHO_CODIGOS );
		}
		return $retorno;
	}
	
	static function isPaginaEncaminharNovo(){
		$teste = @$_POST [self::$ID_PAGINA_ENCAMINHAR_NOVO];
		return isAtributoValido($teste);
	}
	

}
?>