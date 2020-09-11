<?php
include_once (caminho_lib . "voentidade.php");
//include_once ("dbProcLicitatorio.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");

class voRegistroLivro extends voentidade {
	
	static $nmAtrNumLivro = "regliv_numlivro";
	static $nmAtrNumFolha = "regliv_numfolha";
	static $nmAtrDtRegistro = "regliv_dtregistro";	
	static $nmAtrObservacao = "regliv_obs";
	
	var $voContrato = null;	
	var $numLivro = "";
	var $numFolha = "";
	var $dtRegistro = "";
	var $obs = "";
	
	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = true;	
		$this->voContrato = new vocontrato();
		
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
		return "Registro Livro";
	}
	public static function getTituloJSP() {
		return "REGISTRO LIVRO";
	}
	public static function getNmTabela() {
		return "registro_livro";
	}
	public static function getNmClassProcesso() {
		return "dbRegistroLivro";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		//$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		$query .= " $nmTabela." .vocontrato::$nmAtrAnoContrato . "=" . $this->voContrato->anoContrato;
		$query .= " AND $nmTabela." .vocontrato::$nmAtrCdContrato . "=" . $this->voContrato->cdContrato;
		$query .= " AND $nmTabela." .vocontrato::$nmAtrTipoContrato . "=" . getVarComoString($this->voContrato->tipo);
		$query .= " AND $nmTabela." .vocontrato::$nmAtrCdEspecieContrato . "=" . getVarComoString($this->voContrato->cdEspecie);
		$query .= " AND $nmTabela." .vocontrato::$nmAtrSqEspecieContrato . "=" . $this->voContrato->sqEspecie;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	/*function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrAno . "=" . $this->ano;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrUG . "=" . getVarComoString($this->ug);
		
		return $query;
	}*/
	
	/**
	 * Define os atributos do VO
	 */
	static function getAtributosFilho() {
		$array1 = static::getAtributosChavePrimaria();
		
		$array2 = array (
				self::$nmAtrNumLivro,
				self::$nmAtrNumFolha,
				self::$nmAtrDtRegistro,
				self::$nmAtrObservacao,
		);
		$retorno = array_merge($array1, $array2);
	
		return $retorno;
	}
	
	/**
	 *  Chave primaria
	 */
	function getAtributosChavePrimaria() {
		$retorno = array (
				vocontrato::$nmAtrAnoContrato,
				vocontrato::$nmAtrCdContrato,
				vocontrato::$nmAtrTipoContrato,
				vocontrato::$nmAtrCdEspecieContrato,
				vocontrato::$nmAtrSqEspecieContrato,
		);
		
		return $retorno;
	}
	
	function getDadosRegistroBanco($registrobanco) {		
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->voContrato->getDadosRegistroBanco($registrobanco);
		$this->numFolha = $registrobanco [self::$nmAtrNumFolha];		
		$this->numLivro = $registrobanco [self::$nmAtrNumLivro];		
		$this->dtRegistro = $registrobanco [self::$nmAtrDtRegistro];
		$this->obs = $registrobanco [self::$nmAtrObservacao];	
	}
	
	function getDadosFormulario() {
		$this->voContrato->getDadosFormulario();
		$this->numFolha = @$_POST [self::$nmAtrNumFolha];
		$this->numLivro = @$_POST [self::$nmAtrNumLivro];
		$this->dtRegistro = @$_POST [self::$nmAtrDtRegistro];
		$this->obs = @$_POST [self::$nmAtrObservacao];
		
		// completa com os dados da entidade
		$this->getDadosFormularioEntidade ();
	}

	function toString() {
		$retorno .= $this->voContrato->getCodigoContratoFormatado(true);
		/*$retorno .= "," . $this->ug;
		$retorno .= "," . $this->cd;*/
		
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->voContrato->getValorChaveLogica() . CAMPO_SEPARADOR . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {
		$this->voContrato->anoContrato = $array [0];
		$this->voContrato->cdContrato = $array [1];
		$this->voContrato->tipo = $array [2];
		$this->voContrato->cdEspecie = $array [3];
		$this->voContrato->sqEspecie = $array [4];
		$this->sqHist = $array [5];
	}
	
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Registro: " . $this->toString() . "|Data Registro: " . $this->dtRegistro;
		return $retorno;
	}
	
}
?>