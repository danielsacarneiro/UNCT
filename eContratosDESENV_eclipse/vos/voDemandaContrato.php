<?php
include_once("voDemanda.php");
include_once("vocontrato.php");

Class voDemandaContrato extends voentidade{
	 
	static $nmAtrAnoDemanda = "dem_ex";
	static $nmAtrCdDemanda = "dem_cd";
	static $nmAtrCdContrato  = "ct_numero";
	static $nmAtrAnoContrato  = "ct_exercicio";
	static $nmAtrTipoContrato =  "ct_tipo";
	static $nmAtrSqEspecieContrato =  "ct_sq_especie"; //sequencial da especie (primeiro, segundo TA, por ex)
	static $nmAtrCdEspecieContrato =  "ct_cd_especie"; //especie propriamente dita(TA, apostilamento)	
	
	var $voContrato = "";
	var $anoDemanda = "";
	var $cdDemanda = "";
	var $dbprocesso = "";

	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )

	function __construct() {
		parent::__construct();
		$this->temTabHistorico = false;
		$class = self::getNmClassProcesso();
		$this->dbprocesso= new $class();
		$this->voContrato = new vocontrato();

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
		return  "DEMANDA CONTRATO";
	}

	public static function getNmTabela(){
		return  "demanda_contrato";
	}

	public static function getNmClassProcesso(){
		return  "dbDemandaContrato";
	}

	function getValoresWhereSQLChave($isHistorico){
		$nmTabela = self::getNmTabelaStatic($isHistorico);
		$query =  $nmTabela . "." . self::$nmAtrAnoDemanda . "=" . $this->anoDemanda;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdDemanda . "=" . $this->cdDemanda;
		$query .= " AND " . $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $this->voContrato->anoContrato;
		$query .= " AND " . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $this->voContrato->cdContrato;
		$query .= " AND " . $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . getVarComoString($this->voContrato->tipo);
		$query .= " AND " . $nmTabela . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . getVarComoString($this->voContrato->cdEspecie);
		$query .= " AND " . $nmTabela . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $this->voContrato->sqEspecie;

		if($isHistorico)
			$query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;

			return $query;
	}

	function getAtributosFilho(){
		$retorno = array(
				self::$nmAtrAnoDemanda,
				self::$nmAtrCdDemanda,
				vocontrato::$nmAtrAnoContrato,
				vocontrato::$nmAtrTipoContrato,
				vocontrato::$nmAtrCdEspecieContrato,
				vocontrato::$nmAtrCdContrato,
				vocontrato::$nmAtrSqEspecieContrato
		);

		return $retorno;
	}

	function getAtributosChavePrimaria(){
		return $this->getAtributosFilho();
	}

	function getDadosRegistroBanco($registrobanco){
		//as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade

		$this->cdDemanda = $registrobanco[self::$nmAtrCdDemanda];
		$this->anoDemanda  = $registrobanco[self::$nmAtrAnoDemanda];
		
		$this->voContrato = new vocontrato();
		$this->voContrato->cdContrato = $registrobanco[self::$nmAtrCdContrato];
		$this->voContrato->anoContrato = $registrobanco[self::$nmAtrAnoContrato];
		$this->voContrato->tipo = $registrobanco[self::$nmAtrTipoContrato];		
		$this->voContrato->sqEspecie	 = $registrobanco[self::$nmAtrSqEspecieContrato];
		$this->voContrato->cdEspecie	 = $registrobanco[self::$nmAtrCdEspecieContrato];
		$this->voContrato->sq = $registrobanco[vocontrato::$nmAtrSqContrato];
		
		//echo "cd contrato = " . $this->voContrato->cdContrato; 
	}

	function getDadosFormulario(){		
		$this->cdDemanda = @$_POST[self::$nmAtrCdDemanda];
		$this->anoDemanda  = @$_POST[self::$nmAtrAnoDemanda];
		
		if(@$_POST[vocontrato::getNmTabela()] != null){
			$chave = @$_POST[vocontrato::getNmTabela()];
			$voContrato = new vocontrato();
			$voContrato->getChavePrimariaVOExplodeParam($chave);
			$this->voContrato = $voContrato;
		}
		
	}
	 
	function toString(){
		$retorno.= $this->anoDemanda;
		$retorno.= "," . $this->cdDemanda;
		$retorno.= "," . $this->voContrato->getValorChavePrimaria();
		return $retorno;
	}

	function getValorChavePrimaria(){
		return $this->anoDemanda
		. CAMPO_SEPARADOR
		. $this->cdDemanda
		. CAMPO_SEPARADOR
		. $this->voContrato->getValorChaveHTML();		
	}

	function getChavePrimariaVOExplode($array){
		$this->anoDemanda = $array[0];
		$this->cdDemanda = $array[1];		
		
		$this->voContrato->anoContrato = $array[3];
		$this->voContrato->tipo = $array[4];
		$this->voContrato->cdContrato = $array[5];
		$this->voContrato->cdEspecie = $array[6];		
		$this->voContrato->cdEspecie = $array[7];
	}

}
?>