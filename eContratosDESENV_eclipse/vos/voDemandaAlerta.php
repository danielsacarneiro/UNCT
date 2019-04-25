<?php
include_once (caminho_lib . "voentidade.php");

class voDemandaAlerta extends voentidade {	
	
	static $nmAtrDtInicioVigencia = "ctmod_dtreferencia";
	static $nmAtrSq = "sq";
	static $nmAtrSqMensageria = "msg_sq";
			
	var $sq;
	var $sqMensageria;
	
	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct($arrayChave = null) {
		parent::__construct1 ($arrayChave);
		$this->temTabHistorico = false;
		
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrCdUsuarioInclusao,
		);
		
		$arrayAtribInclusaoDBDefault = array (
				self::$nmAtrDhUltAlteracao
		);
		$this->setaAtributosRemocaoEInclusaoDBDefault($arrayAtribRemover, $arrayAtribInclusaoDBDefault);
	}
	
	public static function getTituloJSP() {
		return "DEMANDA ALERTA";
	}
	public static function getNmTabela() {
		return "demanda_alerta";
	}
	public static function getNmClassProcesso() {
		return "dbDemandaAlerta";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ($isHistorico);
		$query .= " AND " . $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrSqMensageria . "=" . $this->sqMensageria;
		
		return $query;
	}
	static function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrSq,
				self::$nmAtrSqMensageria,
		);
		
		return $retorno;
	}
	static function getAtributosFilho() {
		$retorno = static::getAtributosChavePrimaria();		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		$this->sq = $registrobanco[self::$nmAtrSq];
		$this->sqMensageria = $registrobanco[self::$nmAtrSqMensageria];
	}
	function getDadosFormulario() {
		$this->sq = @$_POST[self::$nmAtrSq];
		$this->sqMensageria = @$_POST[self::$nmAtrSqMensageria];
		
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}

	function toString() {
		$retorno .= "Registro" . complementarCharAEsquerda($this->sq, "0", constantes::$TAMANHO_CODIGOS) 
		. ". Alerta:" . complementarCharAEsquerda($this->sqMensageria, "0", constantes::$TAMANHO_CODIGOS);
		
		return $retorno;
	}
	function getValorChavePrimaria() {
		$separador = CAMPO_SEPARADOR;
		$chave = $this->sq
		. $separador
		. $this->sqMensageria;
		// $separador = "b";
		return $chave;
	}
	function getChavePrimariaVOExplode($array) {		
		$this->sq = $array[0];
		$this->sqMensageria = $array[1];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Registro Mensageria: " . $this->toString();
		return $retorno;
	}
}
?>