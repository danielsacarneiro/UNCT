<?php
include_once (caminho_funcoes . "pa_penalidade/dominioTipoPenalidade.php");
class voPenalidadePA extends voentidade {
	static $nmAtrCdPA = "pa_cd"; // processo administrativo cd
	static $nmAtrAnoPA = "pa_ex"; // processo administrativo ano
	static $nmAtrSq = "pen_sq";
	static $nmAtrTipo = "pen_tipo";
	static $nmAtrFundamento = "pen_fundamento";
	static $nmAtrObservacao = "pen_observacao";
	static $nmAtrDtAplicacao = "pen_dt_aplicacao";
	
	static $NM_COL_inTemPublicacao = "NM_COL_inTemPublicacao";
	
	var $cdPA = "";
	var $anoPA = "";
	var $sq = "";
	var $tipo = "";
	var $obs = "";
	var $fundamento = "";
	var $dtAplicacao = "";
	
	var $inTemPublicacao = "";
	
	var $dbprocesso = null;
	
	// ...............................................................
	// Funcoes ( Propriedades e metodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = true;
		
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();
		// retira os atributos padrao que nao possui
		// remove tambem os que o banco deve incluir default
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao 
		);
		$this->removeAtributos ( $arrayAtribRemover );
	}
	public static function getTituloJSP() {
		return "PENALIDADE (PAAP)";
	}
	public static function getNmTabela() {
		return "pa_penalidade";
	}
	public static function getNmClassProcesso() {
		return "dbPenalidadePA";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		$query .= " AND " . $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		
		if ($isHistorico) {
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		}
		
		return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrAnoPA . "=" . $this->anoPA;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdPA . "=" . $this->cdPA;
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCdPA,
				self::$nmAtrAnoPA,
				self::$nmAtrSq,
				self::$nmAtrTipo,
				self::$nmAtrObservacao,
				self::$nmAtrFundamento,
				self::$nmAtrDtAplicacao,
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrAnoPA,
				self::$nmAtrCdPA,
				self::$nmAtrSq,
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPA = $registrobanco [self::$nmAtrCdPA];
		$this->anoPA = $registrobanco [self::$nmAtrAnoPA];
		$this->sq = $registrobanco [self::$nmAtrSq];
		
		$this->tipo = $registrobanco [self::$nmAtrTipo];
		$this->obs = $registrobanco [self::$nmAtrObservacao];
		$this->fundamento = $registrobanco [self::$nmAtrFundamento];
		$this->dtAplicacao = $registrobanco [self::$nmAtrDtAplicacao];
		
		$this->inTemPublicacao = $registrobanco [self::$NM_COL_inTemPublicacao];
	}
	function getDadosFormulario() {

		$this->cdPA = @$_POST [self::$nmAtrCdPA];
		$this->anoPA = @$_POST [self::$nmAtrAnoPA];
		$this->sq = @$_POST [self::$nmAtrSq];
		
		$this->tipo = @$_POST [self::$nmAtrTipo];
		$this->obs = @$_POST [self::$nmAtrObservacao];
		$this->fundamento = @$_POST [self::$nmAtrFundamento];
		$this->dtAplicacao = @$_POST [self::$nmAtrDtAplicacao];
		
		// completa com os dados da entidade
		$this->getDadosFormularioEntidade ();
	}
	function toString() {
		$retorno .= $this->anoPA . ",";
		$retorno .= $this->cdPA . ",";
		$retorno .= $this->sq . ",";
		return $retorno;
	}
	function getValorChavePrimaria() {
		$chave = $this->anoPA;
		$chave .= CAMPO_SEPARADOR . $this->cdPA;
		$chave .= CAMPO_SEPARADOR . $this->sq;
		$chave .= CAMPO_SEPARADOR . $this->sqHist;
		
		return $chave;
	}
	function getChavePrimariaVOExplode($array) {
		$this->anoPA = $array [0];
		$this->cdPA = $array [1];
		$this->sq = $array [2];
		$this->sqHist = $array [3];
	}
	
	static function getCampoFundamentoModelo(){
		return constantes::$CD_MODELO_TEXTO . " Cláusula Décima do instrumento contratual, inciso XXX, alínea 'AAA' e art. 7º da Lei nº 10.520/02 c/c art. 87 da Lei nº 8.666/93.";
	}
	
	function getMensagemComplementarTelaSucesso() {
		$sq = complementarCharAEsquerda($this->sq, "0", TAMANHO_CODIGOS);
		$retorno = "Penalidade: $sq-PAAP " . formatarCodigoAno ( $this->cdPA, $this->anoPA );
		if ($this->sqHist != null) {
			$retorno .= "<br>Núm. Histórico: " . complementarCharAEsquerda ( $this->sqHist, "0", TAMANHO_CODIGOS );
		}
		return $retorno;
	}
}
?>