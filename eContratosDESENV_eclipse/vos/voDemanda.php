<?php
include_once (caminho_lib . "voentidade.php");
include_once ("dbDemanda.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
include_once (caminho_util . "dominioSetor.php");
include_once (caminho_funcoes . "demanda/dominioSituacaoDemanda.php");
include_once (caminho_funcoes . "demanda/dominioTipoDemanda.php");
include_once (caminho_funcoes . "demanda/dominioPrioridadeDemanda.php");
class voDemanda extends voentidade {
	static $ID_REQ_DIV_REAJUSTE_MONTANTE_A = "ID_REQ_DIV_REAJUSTE_MONTANTE_A";
	
	static $nmAtrCd = "dem_cd";
	static $nmAtrAno = "dem_ex";
	static $nmAtrCdSetor = "dem_cd_setor";
	static $nmAtrCdSetorAtual = "NM_COL_SETOR_ATUAL";
	static $nmAtrTipo = "dem_tipo";
	static $nmAtrInTpDemandaReajusteComMontanteA = "dem_tp_temreajustemontanteA";
	static $nmAtrSituacao = "dem_situacao";
	static $nmAtrTexto = "dem_texto";
	static $nmAtrPrioridade = "dem_prioridade";
	static $nmAtrDtReferencia = "dem_dtreferencia";
	var $cd = "";
	var $ano = "";
	var $cdSetor = "";
	var $cdSetorAtual = "";
	var $tipo = "";
	var $inTpDemandaReajusteComMontanteA = "";
	var $situacao = "";
	var $texto = "";
	var $prioridade = "";
	var $dtReferencia = "";
	var $dbprocesso = null;
	var $colecaoContrato = null;
	var $voProcLicitatorio = null;
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
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao 
		);
		$this->removeAtributos ( $arrayAtribRemover );
		$this->varAtributosARemover = $arrayAtribRemover;
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
				self::$nmAtrInTpDemandaReajusteComMontanteA,
				self::$nmAtrCdSetor,
				self::$nmAtrSituacao,
				self::$nmAtrTexto,
				self::$nmAtrPrioridade,
				self::$nmAtrDtReferencia 
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
		return $retorno;
	}
	
	function temProcLicitatorioParaIncluir() {
		$retorno = $this->voProcLicitatorio != null && $this->voProcLicitatorio->cd != null;
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
		$voDemanda = new voDemandaPL();
		$voDemanda->anoDemanda = $this->ano;
		$voDemanda->cdDemanda = $this->cd;
		$voDemanda->anoProcLic = $voProcLic->ano;
		$voDemanda->cdProcLic = $voProcLic->cd;
		return $voDemanda;
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco [self::$nmAtrCd];
		$this->ano = $registrobanco [self::$nmAtrAno];
		$this->cdSetor = $registrobanco [self::$nmAtrCdSetor];
		$this->cdSetorAtual = $registrobanco [self::$nmAtrCdSetorAtual];
		$this->tipo = $registrobanco [self::$nmAtrTipo];
		$this->inTpDemandaReajusteComMontanteA = $registrobanco [self::$nmAtrInTpDemandaReajusteComMontanteA];
		$this->situacao = $registrobanco [self::$nmAtrSituacao];
		$this->texto = $registrobanco [self::$nmAtrTexto];
		$this->prioridade = $registrobanco [self::$nmAtrPrioridade];
		$this->dtReferencia = $registrobanco [self::$nmAtrDtReferencia];
		
		$this->getProcLicitatorioRegistroBanco($registrobanco);
		
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
		$this->inTpDemandaReajusteComMontanteA = @$_POST [self::$nmAtrInTpDemandaReajusteComMontanteA];
		$this->situacao = @$_POST [self::$nmAtrSituacao];
		$this->texto = @$_POST [self::$nmAtrTexto];
		$this->prioridade = @$_POST [self::$nmAtrPrioridade];
		$this->dtReferencia = @$_POST [self::$nmAtrDtReferencia];
		// quando existir
		// recupera quando da consulta da contratada, ao inserir o contrato na tela
		$chaveContrato = @$_POST [vopessoa::$ID_CONTRATO];
		// echo "chave contrato:" . $chaveContrato;
		if ($chaveContrato != null) {
			$this->setColecaoContratoFormulario ( $chaveContrato );
		}
		
		$this->getProcLicitatorioFormulario();
		
		// completa com os dados da entidade
		$this->getDadosFormularioEntidade ();
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
	function toString() {
		$retorno .= $this->ano;
		$retorno .= "," . $this->cd;
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->ano . CAMPO_SEPARADOR . $this->cd . CAMPO_SEPARADOR . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {
		$this->ano = $array [0];
		$this->cd = $array [1];
		$this->sqHist = $array [2];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Demanda (N�mero - Ano): " . formatarCodigoAnoComplementoArgs ( $this->cd, $this->ano, TAMANHO_CODIGOS, null );
		if ($this->sqHist != null) {
			$retorno .= "<br>N�m. Hist�rico: " . complementarCharAEsquerda ( $this->sqHist, "0", TAMANHO_CODIGOS );
		}
		return $retorno;
	}
}
?>