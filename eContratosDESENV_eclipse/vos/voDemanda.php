<?php
include_once (caminho_lib . "voentidade.php");
include_once ("dbDemanda.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
include_once (caminho_util . "dominioSetor.php");
include_once (caminho_funcoes . "demanda/dominioSituacaoDemanda.php");
include_once (caminho_funcoes . "demanda/dominioTipoDemanda.php");
include_once (caminho_funcoes . "demanda/dominioPrioridadeDemanda.php");
class voDemanda extends voentidade {
	static $nmAtrCd = "dem_cd";
	static $nmAtrAno = "dem_ex";
	static $nmAtrCdSetor = "dem_cd_setor";
	static $nmAtrCdSetorAtual = "NM_COL_SETOR_ATUAL";
	static $nmAtrTipo = "dem_tipo";
	static $nmAtrSituacao = "dem_situacao";
	static $nmAtrTexto = "dem_texto";
	static $nmAtrPrioridade = "dem_prioridade";
	static $nmAtrDtReferencia = "dem_dtreferencia";
	var $cd = "";
	var $ano = "";
	var $cdSetor = "";
	var $cdSetorAtual = "";
	var $tipo = "";
	var $situacao = "";
	var $texto = "";
	var $prioridade = "";
	var $dtReferencia = "";
	var $dbprocesso = null;
	var $colecaoContrato = null;
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )
	function __construct() {
		parent::__construct ();
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
		$retorno = $this->colecaoContrato != null && (count ( $this->colecaoContrato ) > 0);
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
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco [self::$nmAtrCd];
		$this->ano = $registrobanco [self::$nmAtrAno];
		$this->cdSetor = $registrobanco [self::$nmAtrCdSetor];
		$this->cdSetorAtual = $registrobanco [self::$nmAtrCdSetorAtual];
		$this->tipo = $registrobanco [self::$nmAtrTipo];
		$this->situacao = $registrobanco [self::$nmAtrSituacao];
		$this->texto = $registrobanco [self::$nmAtrTexto];
		$this->prioridade = $registrobanco [self::$nmAtrPrioridade];
		$this->dtReferencia = $registrobanco [self::$nmAtrDtReferencia];
		
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
		
		// completa com os dados da entidade
		$this->getDadosFormularioEntidade ();
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
		$retorno = "Demanda (Número - Ano): " . formatarCodigoAnoComplementoArgs ( $this->cd, $this->ano, TAMANHO_CODIGOS, null );
		if ($this->sqHist != null) {
			$retorno .= "<br>Núm. Histórico: " . complementarCharAEsquerda ( $this->sqHist, "0", TAMANHO_CODIGOS );
		}
		return $retorno;
	}
}
?>