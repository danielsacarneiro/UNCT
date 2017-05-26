<?php
include_once (caminho_lib . "voentidade.php");
include_once (caminho_util . "dominioSetor.php");

Class voUsuarioSetor extends voentidade{
	 
	static $nmAtrID  = "ID";
	static $nmAtrCdSetor  = "usu_cd_setor";
	
	var $cdSetor = "";
	var $id = "";
	var $dbprocesso = "";

	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )

	function __construct() {
		parent::__construct();
		$this->temTabHistorico = false;
		$class = self::getNmClassProcesso();
		$this->dbprocesso= new $class();

		//retira os atributos padrao que nao possui
		//remove tambem os que o banco deve incluir default
		$arrayAtribRemover = array(
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao,
				self::$nmAtrCdUsuarioUltAlteracao,
		);
		$this->removeAtributos($arrayAtribRemover);
		$this->varAtributosARemover = $arrayAtribRemover;
	}
	 
	public static function getTituloJSP(){
		return  "USUÁRIO SETOR";
	}

	public static function getNmTabela(){
		return  "usuario_setor";
	}

	public static function getNmClassProcesso(){
		return  "dbUsuarioSetor";
	}

	function getValoresWhereSQLChave($isHistorico){
		$nmTabela = self::getNmTabelaStatic($isHistorico);
		$query =  $nmTabela . "." . self::$nmAtrID . "=" . $this->ID;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdSetor . "=" . $this->cdSetor;

		if($isHistorico)
			$query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;

			return $query;
	}

	function getAtributosFilho(){
		$retorno = array(
				self::$nmAtrID,
				self::$nmAtrCdSetor
		);

		return $retorno;
	}

	function getAtributosChavePrimaria(){
		return $this->getAtributosFilho();
	}

	function getDadosRegistroBanco($registrobanco){
		//as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->id = $registrobanco[self::$nmAtrID];
		$this->cdSetor = $registrobanco[self::$nmAtrCdSetor];	
		// completa com os dados da entidade
		$this->getDadosBancoEntidade();		
	}

	function getDadosFormulario(){		
		$this->id = @$_POST[self::$nmAtrID];
		$this->cdSetor = @$_POST[self::$nmAtrCdSetor];
	}
	 
	function toString(){
		$retorno.= $this->id;
		$retorno.= "," . $this->cdSetor;
		return $retorno;
	}

	function getValorChavePrimaria(){
		return $this->id
		. CAMPO_SEPARADOR
		. $this->cdSetor;		
	}

	function getChavePrimariaVOExplode($array){
		$this->id = $array[0];
		$this->cdSetor = $array[1];		
	}

}
?>