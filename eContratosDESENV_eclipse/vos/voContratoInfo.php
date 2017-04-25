<?php
include_once(caminho_lib."voentidade.php");
include_once("dbDemanda.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_util."dominioSetor.php");
include_once(caminho_funcoes. "contrato/dominioAutorizacao.php");
include_once(caminho_funcoes. "contrato/dominioEspeciesContrato.php");
include_once (caminho_util."documentoPessoa.php");

Class voContratoInfo extends voentidade{
	
	static $nmAtrCdContrato  = "ct_numero";
	static $nmAtrAnoContrato  = "ct_exercicio";
	static $nmAtrTipoContrato =  "ct_tipo";
	
	static $nmAtrCdAutorizacaoContrato =  	"ctinf_cd_autorizacao";
	static $nmAtrObs = "ctinf_obs";
	static $nmAtrDtProposta = "ctinf_dt_proposta";
	 
	var $cdContrato = "";
	var $anoContrato  = "";
	var $tipo = "";	
	var $cdAutorizacao = "";
	var $obs = "";
	var $dtProposta = "";
	 
	var $dbprocesso = null;
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )

	function __construct() {
		parent::__construct();
		$this->temTabHistorico = true;
		$class = self::getNmClassProcesso();
		$this->dbprocesso= new $class();
		//retira os atributos padrao que nao possui
		//remove tambem os que o banco deve incluir default
		$arrayAtribRemover = array(
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao
		);
		$this->removeAtributos($arrayAtribRemover);
		$this->varAtributosARemover = $arrayAtribRemover;
	}
	 
	public static function getTituloJSP(){
		return  "CONTRATO-INFORMAÇÕES ADICIONAIS";
	}

	public static function getNmTabela(){
		return  "contrato_info";
	}

	public static function getNmClassProcesso(){
		return  "dbContratoInfo";
	}

	function getValoresWhereSQLChave($isHistorico){
		$nmTabela = $this->getNmTabelaEntidade($isHistorico);
		$query = $nmTabela . "." . self::$nmAtrAnoContrato . "=" . $this->anoContrato;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdContrato . "=" . $this->cdContrato;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrTipoContrato . "=" . getVarComoString($this->tipo);

		if($isHistorico)
			$query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;

			return $query;
	}

	function getAtributosFilho(){
		$retorno = array(
				self::$nmAtrAnoContrato,
				self::$nmAtrCdContrato,
				self::$nmAtrTipoContrato,
				self::$nmAtrCdAutorizacaoContrato,
				self::$nmAtrObs,
				self::$nmAtrDtProposta
		);

		return $retorno;
	}

	function getAtributosChavePrimaria(){
		$retorno = array(
				self::$nmAtrAnoContrato,
				self::$nmAtrTipoContrato,
				self::$nmAtrCdContrato
		);

		return $retorno;
	}

	function getVOContrato(){
		$retorno = new vocontrato();
		$retorno->cdContrato = $this->cdContrato;
		$retorno->anoContrato = $this->anoContrato;
		$retorno->tipo = $this->tipo;
			
		return $retorno;
	}
	
	function getDadosRegistroBanco($registrobanco){
		//as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdContrato = $registrobanco[self::$nmAtrCdContrato];
		$this->anoContrato  = $registrobanco[self::$nmAtrAnoContrato];
		$this->tipo  = $registrobanco[self::$nmAtrTipoContrato];
		$this->cdAutorizacao  = $registrobanco[self::$nmAtrCdAutorizacaoContrato];
		$this->obs = $registrobanco[self::$nmAtrObs];
		$this->dtProposta = $registrobanco[self::$nmAtrDtProposta];		 
	}

	function getDadosFormulario(){
		$this->cdContrato = @$_POST[self::$nmAtrCdContrato];
		$this->anoContrato  = @$_POST[self::$nmAtrAnoContrato];
		$this->tipo  = @$_POST[self::$nmAtrTipoContrato];
		$this->cdAutorizacao  = @$_POST[self::$nmAtrCdAutorizacaoContrato];
		$this->obs = @$_POST[self::$nmAtrObs];
		$this->dtProposta = @$_POST[self::$nmAtrDtProposta];
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}
	 
	function toString(){
		/*$retorno.= $this->ano;
		$retorno.= "," . $this->tipo;
		$retorno.= "," . $this->cd;*/
		return formatarCodigoContrato($this->cdContrato, $this->anoContrato, $this->tipo);
	}

	function getValorChavePrimaria(){
		return $this->anoContrato
		. CAMPO_SEPARADOR
		. $this->cdContrato
		. CAMPO_SEPARADOR
		. $this->tipo
		. CAMPO_SEPARADOR
		. $this->sqHist;
		}

	function getChavePrimariaVOExplode($array){
		$this->anoContrato = $array[0];
		$this->cdContrato = $array[1];
		$this->tipo = $array[2];
		$this->sqHist = $array[3];
	}
	
	function getMensagemComplementarTelaSucesso(){
		$retorno = "Contrato : " . formatarCodigoContrato($this->cdContrato, $this->anoContrato, $this->tipo);
		return $retorno; 
	}	

}
?>