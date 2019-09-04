<?php
include_once ("voDemanda.php");
include_once ("vocontrato.php");
class voDemandaPL extends voentidade {
	static $nmAtrAnoDemanda = "dem_ex";
	static $nmAtrCdDemanda = "dem_cd";
	static $nmAtrAnoProcLic = "pl_ex";
	static $nmAtrCdProcLic = "pl_cd";
	static $nmAtrCdModalidadeProcLic = "pl_mod_cd";
	
	var $anoDemanda = "";
	var $cdDemanda = "";
	var $anoProcLic = "";
	var $cdProcLic = "";
	var $cdModProcLic = "";
	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = false;
		
		// retira os atributos padrao que nao possui
		// remove tambem os que o banco deve incluir default
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao,
				self::$nmAtrCdUsuarioUltAlteracao 
		);
		$this->removeAtributos ( $arrayAtribRemover );
	}
	public static function getTituloJSP() {
		return "DEMANDA P.L.";
	}
	public static function getNmTabela() {
		return "demanda_pl";
	}
	public static function getNmClassProcesso() {
		return "dbDemandaPL";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = self::getNmTabelaStatic ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrAnoDemanda . "=" . $this->anoDemanda;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdDemanda . "=" . $this->cdDemanda;
		$query .= " AND " . $nmTabela . "." . voProcLicitatorio::$nmAtrAno . "=" . $this->anoProcLic;
		$query .= " AND " . $nmTabela . "." . voProcLicitatorio::$nmAtrCd . "=" . $this->cdProcLic;
		$query .= " AND " . $nmTabela . "." . static::$nmAtrCdModalidadeProcLic . "=" . $this->cdModProcLic;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrAnoDemanda,
				self::$nmAtrCdDemanda,
				voProcLicitatorio::$nmAtrAno,
				voProcLicitatorio::$nmAtrCd, 
				self::$nmAtrCdModalidadeProcLic,
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		return $this->getAtributosFilho ();
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdDemanda = $registrobanco [self::$nmAtrCdDemanda];
		$this->anoDemanda = $registrobanco [self::$nmAtrAnoDemanda];
		
		$this->cdProcLic = $registrobanco [self::$nmAtrCdProcLic];
		$this->anoProcLic = $registrobanco [self::$nmAtrAnoProcLic];
		$this->cdModProcLic = $registrobanco [self::$nmAtrCdModalidadeProcLic];
	}
	function getDadosFormulario() {
		$this->cdDemanda = @$_POST [self::$nmAtrCdDemanda];
		$this->anoDemanda = @$_POST [self::$nmAtrAnoDemanda];
		
		$this->cdProcLic = @$_POST [voProcLicitatorio::$nmAtrCd];
		$this->anoProcLic = @$_POST [voProcLicitatorio::$nmAtrAno];
		$this->cdModProcLic = @$_POST [self::$nmAtrCdModalidadeProcLic];
	}
	function toString() {
		$retorno .= $this->anoDemanda;
		$retorno .= "," . $this->cdDemanda;
		$retorno .= $this->anoProcLic;
		$retorno .= "," . $this->cdProcLic;
		$retorno .= "," . $this->cdModProcLic;
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->anoDemanda . CAMPO_SEPARADOR . $this->cdDemanda . CAMPO_SEPARADOR . $this->anoProcLic . CAMPO_SEPARADOR . $this->cdProcLic . CAMPO_SEPARADOR . $this->cdModProcLic . CAMPO_SEPARADOR;
	}
	function getChavePrimariaVOExplode($array) {
		$this->anoDemanda = $array [0];
		$this->cdDemanda = $array [1];
		
		$this->anoProcLic = $array [2];
		$this->cdProcLic = $array [3];
		$this->cdModProcLic = $array [4];
	}
}
?>