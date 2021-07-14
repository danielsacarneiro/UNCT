<?php
include_once (caminho_lib . "voentidade.php");
include_once ("dbContratoLicon.php");
include_once (caminho_funcoes . "contrato_licon/dominioSituacaoContratoLicon.php");

class voContratoLicon extends voentidade {
	
	static $ID_REQ_DIV_DADOS_CONTRATO_LICON = "ID_REQ_DIV_DADOS_CONTRATO_LICON";
	
	static $nmAtrAnoDemanda = "dem_ex";
	static $nmAtrCdDemanda = "dem_cd";
	static $nmAtrCdContrato  = "ct_numero";
	static $nmAtrAnoContrato  = "ct_exercicio";
	static $nmAtrTipoContrato =  "ct_tipo";
	static $nmAtrCdEspecieContrato =  "ct_cd_especie"; //especie propriamente dita(TA, apostilamento)
	static $nmAtrSqEspecieContrato =  "ct_sq_especie"; //sequencial da especie (primeiro, segundo TA, por ex)	
	
	static $nmAtrSituacao = "ctl_situacao";
	static $nmAtrObs = "ctl_obs";	
			
	var $vodemandacontrato;
	var $situacao;
	var $obs;
	var $dbprocesso = "";
	
	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct($arrayChave = null) {
		parent::__construct1 ($arrayChave);
		$this->temTabHistorico = true;
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();
				
		$arrayAtribInclusaoDBDefault = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao
		);
		$this->setaAtributosRemocaoEInclusaoDBDefault($arrayAtribRemover, $arrayAtribInclusaoDBDefault);		
		
		$this->vodemandacontrato = new voDemandaContrato();
	}
	public static function getTituloJSP() {
		return "LICON-SISTEMA EXTERNO-CONTRATO";
	}
	public static function getNmTabela() {
		return "contrato_licon";
	}
	public static function getNmClassProcesso() {
		return "dbContratoLicon";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( false );		
		
		/*if ($isHistorico){
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		}*/
		
		if ($isHistorico){
			$this->vodemandacontrato->sqHist = $this->sqHist;
		}
					
		$query = $this->vodemandacontrato->getValoresWhereSQLChaveComOutraTabela($isHistorico, $nmTabela);
		
		return $query;
	}
	/*function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrCdSetor . "=" . $this->cdSetor;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrAno . "=" . $this->ano;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrTp . "='" . $this->tp . "'";
		
		return $query;
	}*/
	static function getAtributosFilho() {
		$array1 = static::getAtributosChavePrimaria();		
		$array2 = array (
				self::$nmAtrSituacao,
				self::$nmAtrObs,
		);		
		$retorno = array_merge($array1, $array2);		
		
		return $retorno;
	}
	static function getAtributosChavePrimaria() {
		$retorno = voDemandaContrato::getAtributosChavePrimaria();		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		$this->vodemandacontrato->getDadosRegistroBanco($registrobanco);

		$this->vodemandacontrato->voContrato->anoContrato = $registrobanco[self::$nmAtrAnoContrato];
		$this->vodemandacontrato->voContrato->cdContrato = $registrobanco[self::$nmAtrCdContrato];
		$this->vodemandacontrato->voContrato->tipo = $registrobanco[self::$nmAtrTipoContrato];
		$this->vodemandacontrato->voContrato->cdEspecie = $registrobanco[self::$nmAtrCdEspecieContrato];
		$this->vodemandacontrato->voContrato->sqEspecie = $registrobanco[self::$nmAtrSqEspecieContrato];
		
		$this->situacao = $registrobanco[self::$nmAtrSituacao];		
		$this->obs = $registrobanco[self::$nmAtrObs];
	}
	function getDadosFormulario() {
		$this->vodemandacontrato->getDadosFormulario();
		
		$this->vodemandacontrato->voContrato->anoContrato = @$_POST[self::$nmAtrAnoContrato];
		$this->vodemandacontrato->voContrato->cdContrato = @$_POST[self::$nmAtrCdContrato];
		$this->vodemandacontrato->voContrato->tipo = @$_POST[self::$nmAtrTipoContrato];
		$this->vodemandacontrato->voContrato->cdEspecie = @$_POST[self::$nmAtrCdEspecieContrato];
		$this->vodemandacontrato->voContrato->sqEspecie = @$_POST[self::$nmAtrSqEspecieContrato];
		
		$this->situacao = @$_POST [self::$nmAtrSituacao];
		$this->obs = @$_POST[self::$nmAtrObs];
		
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}

	function toString() {
		$retorno .= "Ano.Demanda:" . $this->vodemandacontrato->anoDemanda;
		$retorno .= "|Cd.Demanda:" . $this->vodemandacontrato->cdDemanda;		
		//$retorno .= "|Contrato:" . formatarCodigoContrato($this->vodemandacontrato->voContrato->cdContrato, $this->vodemandacontrato->voContrato->anoContrato, $this->vodemandacontrato->voContrato->tipo);		
		$retorno .= "|Contrato:" . $this->vodemandacontrato->voContrato->getCodigoContratoFormatado(true);
		
		if($this->situacao != null){
			$retorno .= "|Situacao:" . dominioSituacaoContratoLicon::getDescricaoStatic($this->situacao);
		}

		return $retorno;
	}
	function getValorChavePrimaria() {
		$separador = CAMPO_SEPARADOR;
		// $separador = "b";
		return $this->vodemandacontrato->getValorChavePrimaria() . $separador . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {		
		$this->vodemandacontrato->getChavePrimariaVOExplode($array);
		$this->sqHist = $array [9];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Contrato-Sistema Externo: " . $this->toString();
		return $retorno;
	}
}
?>