<?php
include_once (caminho_lib . "voentidade.php");
//include_once ("dbProcLicitatorio.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes . "solic_compra/dominioTipoSolicCompra.php");
include_once (caminho_funcoes . "solic_compra/dominioSituacaoSolicCompra.php");
include_once (caminho_funcoes . "solic_compra/dominioUGSolicCompra.php");
include_once (caminho_funcoes . "solic_compra/biblioteca_htmlSolicCompra.php");

class voSolicCompra extends voentidade {
	
	static $nmAtrCd = "solic_cd";
	static $nmAtrAno = "solic_ex";	
	static $nmAtrUG = "solic_ug";

	static $nmAtrTipo = "solic_tp";	
	static $nmAtrObjeto = "solic_objeto";	
	static $nmAtrSituacao = "solic_si";
	static $nmAtrValor = "solic_valor";
	static $nmAtrObservacao = "solic_observacao";	
	
	var $cd = "";
	var $ano = "";
	var $ug = "";
	
	var $tipo = "";
	var $objeto = "";
	var $situacao = "";	
	var $valor = "";
	var $obs = "";
	
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = true;		
		
		/*$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrCdUsuarioInclusao,
		);*/		
		$arrayAtribInclusaoDBDefault = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao
		);
		$this->setaAtributosRemocaoEInclusaoDBDefault($arrayAtribRemover, $arrayAtribInclusaoDBDefault);
	}
	
	public static function getNomeObjetoJSP() {
		return "Solic.Compra";
	}
	public static function getTituloJSP() {
		return "SOLICITAÇÃO DE COMPRA";
	}
	public static function getNmTabela() {
		return "solic_compra";
	}
	public static function getNmClassProcesso() {
		return "dbSolicCompra";
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
		$query .= " AND " . $nmTabela . "." . self::$nmAtrUG . "=" . getVarComoString($this->ug);
		
		return $query;
	}
	
	/**
	 * Define os atributos do VO
	 */
	static function getAtributosFilho() {
		$array1 = static::getAtributosChavePrimaria();
		
		$array2 = array (
				self::$nmAtrTipo,
				self::$nmAtrObjeto,
				self::$nmAtrSituacao,
				self::$nmAtrValor,
				self::$nmAtrObservacao
		);
		$retorno = array_merge($array1, $array2);
	
		return $retorno;
	}
	
	/**
	 *  Chave primaria
	 */
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrAno,
				self::$nmAtrUG,
				self::$nmAtrCd
		);
		
		return $retorno;
	}
	
	function getDadosRegistroBanco($registrobanco) {		
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->ano = $registrobanco [self::$nmAtrAno];
		$this->ug = $registrobanco [self::$nmAtrUG];		
		$this->cd = $registrobanco [self::$nmAtrCd];
		
		$this->tipo = $registrobanco [self::$nmAtrTipo];
		$this->objeto = $registrobanco [self::$nmAtrObjeto];
		$this->situacao = $registrobanco [self::$nmAtrSituacao];
		$this->valor = $registrobanco [self::$nmAtrValor];
		$this->obs = $registrobanco [self::$nmAtrObservacao];	
	}
	
	function getDadosFormulario() {
		$this->ano = @$_POST [self::$nmAtrAno];
		$this->ug = @$_POST [self::$nmAtrUG];
		$this->cd = @$_POST [self::$nmAtrCd];
		
		$this->tipo = @$_POST [self::$nmAtrTipo];
		$this->objeto = @$_POST [self::$nmAtrObjeto];
		$this->situacao = @$_POST [self::$nmAtrSituacao];
		$this->valor = @$_POST [self::$nmAtrValor];
		$this->obs = @$_POST [self::$nmAtrObservacao];
		
		// completa com os dados da entidade
		$this->getDadosFormularioEntidade ();
	}

	function toString() {
		$retorno .= $this->ano;
		$retorno .= "," . $this->ug;
		$retorno .= "," . $this->cd;
		
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->ano . CAMPO_SEPARADOR . $this->ug . CAMPO_SEPARADOR . $this->cd . CAMPO_SEPARADOR . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {
		$this->ano = $array [0];
		$this->ug = $array [1];
		$this->cd = $array [2];
		$this->sqHist = $array [3];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = static::getTituloJSP() . " (UG.CD/ANO): " . formatarCodigoAnoComplementoArgs ( $this->cd, $this->ano, TAMANHO_CODIGOS, $this->ug.".");

		if ($this->sqHist != null) {
			$retorno .= "<br>Núm. Histórico: " . complementarCharAEsquerda ( $this->sqHist, "0", TAMANHO_CODIGOS );
		}
		return $retorno;
	}
}
?>