<?php
class voPA extends voentidade {
	static $nmAtrCdPA = "pa_cd"; // processo administrativo cd
	static $nmAtrAnoPA = "pa_ex"; // processo administrativo ano
	static $nmAtrAnoDemanda = "dem_ex";
	static $nmAtrCdDemanda = "dem_cd";
	static $nmAtrCdResponsavel = "pa_cd_responsavel";
	static $nmAtrObservacao = "pa_observacao";
	static $nmAtrDtAbertura = "pa_dt_abertura";
	static $nmAtrSituacao = "pa_si";
	var $cdPA = "";
	var $anoPA = "";
	var $cdDemanda = "";
	var $anoDemanda = "";
	var $obs = "";
	var $dtAbertura = "";
	var $situacao = "";
	var $cdResponsavel = "";
	
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
		$this->varAtributosARemover = $arrayAtribRemover;
	}
	public static function getTituloJSP() {
		return "PROCESSO ADMINISTRATIVO DE APLICAวรO DE PENALIDADE (PAAP)";
	}
	public static function getNmTabela() {
		return "pa";
	}
	public static function getNmClassProcesso() {
		return "dbPA";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrAnoPA . "=" . $this->anoPA;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdPA . "=" . $this->cdPA;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCdPA,
				self::$nmAtrAnoPA,
				self::$nmAtrAnoDemanda,
				self::$nmAtrCdDemanda,
				self::$nmAtrCdResponsavel,
				self::$nmAtrObservacao,
				self::$nmAtrDtAbertura,
				self::$nmAtrSituacao 
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCdPA,
				self::$nmAtrAnoPA 
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPA = $registrobanco [self::$nmAtrCdPA];
		$this->anoPA = $registrobanco [self::$nmAtrAnoPA];
		$this->cdDemanda = $registrobanco [self::$nmAtrCdDemanda];
		$this->anoDemanda = $registrobanco [self::$nmAtrAnoDemanda];
		$this->cdResponsavel = $registrobanco [self::$nmAtrCdResponsavel];
		
		$this->obs = $registrobanco [self::$nmAtrObservacao];
		$this->dtAbertura = $registrobanco [self::$nmAtrDtAbertura];
		$this->situacao = $registrobanco [self::$nmAtrSituacao];

	}
	function getDadosFormulario() {
		$this->cdPA = @$_POST [self::$nmAtrCdPA];
		$this->anoPA = @$_POST [self::$nmAtrAnoPA];
		$this->cdDemanda = @$_POST [self::$nmAtrCdDemanda];
		$this->anoDemanda = @$_POST [self::$nmAtrAnoDemanda];
		$this->cdResponsavel = @$_POST [self::$nmAtrCdResponsavel];
		
		$this->obs = @$_POST [self::$nmAtrObservacao];
		$this->dtAbertura = @$_POST [self::$nmAtrDtAbertura];
		$this->situacao = @$_POST [self::$nmAtrSituacao];
		
		$this->dhUltAlteracao = @$_POST [self::$nmAtrDhUltAlteracao];
		$this->sqHist = @$_POST [self::$nmAtrSqHist];
		// usuario de ultima manutencao sempre sera o id_user
		$this->cdUsuarioUltAlteracao = id_user;
	}
	function toString() {
		$retorno .= $this->anoPA . ",";
		$retorno .= $this->cdPA . ",";
		return $retorno;
	}
	function getValorChavePrimaria() {
		$chave = $this->anoPA;
		$chave .= CAMPO_SEPARADOR . $this->cdPA;
		$chave .= CAMPO_SEPARADOR . $this->sqHist;
		
		return $chave;
	}
	function getChavePrimariaVOExplode($array) {
		$this->anoPA = $array [0];
		$this->cdPA = $array [1];
		$this->sqHist = $array [2];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = "PAAP: " . formatarCodigoAno($this->cdPA, $this->anoPA);
		return $retorno;
	}
}
?>