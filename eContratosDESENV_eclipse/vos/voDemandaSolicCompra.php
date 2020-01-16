<?php
include_once ("voDemanda.php");
include_once ("voSolicCompra.php");
class voDemandaSolicCompra extends voentidade {
		
	static $nmAtrAnoDemanda = "dem_ex";
	static $nmAtrCdDemanda = "dem_cd";
	static $nmAtrAnoSolicCompra = "solic_ex";
	static $nmAtrCdSolicCompra = "solic_cd";
	static $nmAtrUG = "solic_ug";
	
	var $anoDemanda = "";
	var $cdDemanda = "";
	
	var $anoSolicCompra = "";
	var $cdSolicCompra = "";
	var $ug = "";
	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = false;
		
		$arrayAtribRemover = array (
		 self::$nmAtrDhUltAlteracao,
		 self::$nmAtrCdUsuarioUltAlteracao,
		 );
		$arrayAtribInclusaoDBDefault = array (
				self::$nmAtrDhInclusao,
		);
		$this->setaAtributosRemocaoEInclusaoDBDefault($arrayAtribRemover, $arrayAtribInclusaoDBDefault);
	}
	public static function getTituloJSP() {
		return "DEMANDA Solic.Compra";
	}
	public static function getNmTabela() {
		return "demanda_solic_compra";
	}
	public static function getNmClassProcesso() {
		return "dbDemandaSolicCompra";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = self::getNmTabelaStatic ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrAnoDemanda . "=" . $this->anoDemanda;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdDemanda . "=" . $this->cdDemanda;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrAnoSolicCompra . "=" . $this->anoSolicCompra;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdSolicCompra . "=" . $this->cdSolicCompra;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrUG . "=" . $this->ug;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrAnoDemanda,
				self::$nmAtrCdDemanda,
				self::$nmAtrAnoSolicCompra,
				self::$nmAtrCdSolicCompra, 
				self::$nmAtrUG,
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
		
		$this->anoSolicCompra = $registrobanco [self::$nmAtrAnoSolicCompra];
		$this->cdSolicCompra = $registrobanco [self::$nmAtrCdSolicCompra];
		$this->ug = $registrobanco [self::$nmAtrUG];
	}
	function getDadosFormulario() {
		$this->cdDemanda = @$_POST [self::$nmAtrCdDemanda];
		$this->anoDemanda = @$_POST [self::$nmAtrAnoDemanda];
		
		$this->anoSolicCompra = @$_POST [self::$nmAtrAnoSolicCompra];
		$this->cdSolicCompra = @$_POST [self::$nmAtrCdSolicCompra];
		$this->ug = @$_POST [self::$nmAtrUG];
	}
	function toString() {
		$retorno .= $this->anoDemanda;
		$retorno .= "," . $this->cdDemanda;
		$retorno .= $this->anoSolicCompra;
		$retorno .= "," . $this->cdSolicCompra;
		$retorno .= "," . $this->ug;
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->anoDemanda . CAMPO_SEPARADOR . $this->cdDemanda . CAMPO_SEPARADOR . $this->anoSolicCompra . CAMPO_SEPARADOR . $this->cdSolicCompra . CAMPO_SEPARADOR . $this->ug;
	}
	function getChavePrimariaVOExplode($array) {
		$this->anoDemanda = $array [0];
		$this->cdDemanda = $array [1];
		
		$this->anoSolicCompra = $array [2];
		$this->cdSolicCompra = $array [3];
		$this->ug = $array [4];
	}
}
?>