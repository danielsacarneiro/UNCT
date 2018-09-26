<?php
include_once (caminho_lib . "voentidade.php");
include_once ("dbContratoModificacao.php");

class voContratoModificacao extends voentidade {
	
	static $nmAtrSq = "asctfasdfas_numero";
	static $nmAtrCdContrato  = "ct_numero";
	static $nmAtrAnoContrato  = "ct_exercicio";
	static $nmAtrTipoContrato =  "ct_tipo";
	static $nmAtrCdEspecieContrato =  "ct_cd_especie";
	static $nmAtrSqEspecieContrato =  "ct_sq_especie";	
	
	static $nmAtrTpModificacao = "ctl_obs";
	static $nmAtrVlModificacaoReferencial = "ctl_sitasdauacao";
	static $nmAtrVlModificacaoAoContrato = "ctlasd_situacao";
	static $nmAtrVlModificacaoReal = "ctasddl_situacao";
	
	static $nmAtrNumPercentual = "ctladssa_obs";	
	static $nmAtrDtModificacao = "ctl_obs";
		
	static $nmAtrNumMesesParaOFimPeriodo = "ctwdql_obs";

	var $sq;
	var $vocontrato;	
	var $vlModificacaoReferencial;
	var $vlModificacaoAoContrato;
	var $vlModificacaoReal;
		
	var $numPercentual;
	var $dtModificacao;
	var $tpModificacao;
	
	var $numMesesParaOFimdoPeriodo;
	
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )
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
				
		$this->vocontrato = new vocontrato();
	}
	public static function getTituloJSP() {
		return "CONTRATO-MODIFICAÇÃO";
	}
	public static function getNmTabela() {
		//return "contrato_mod";
		return "mensageria";		
	}
	public static function getNmClassProcesso() {
		return "dbContratoModificacao";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->vodemandacontrato->getValoresWhereSQLChaveComOutraTabela($isHistorico, $nmTabela);
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
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
				self::$nmAtrCdEspecieContrato,
				self::$nmAtrSqEspecieContrato,
				self::$nmAtrTpModificacao,
				self::$nmAtrDtModificacao,
				self::$nmAtrVlModificacaoReferencial,
				self::$nmAtrVlModificacaoReal,
				self::$nmAtrVlModificacaoAoContrato,		
				self::$nmAtrNumMesesParaOFimPeriodo,
				self::$nmAtrNumPercentual,
		);		
		$retorno = array_merge($array1, $array2);		
		
		return $retorno;
	}
	static function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrSq,
				self::$nmAtrAnoContrato,
				self::$nmAtrCdContrato,
				self::$nmAtrTipoContrato,
		);
				
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		$this->vocontrato->getDadosRegistroBanco($registrobanco);
		
		$this->sq = $registrobanco[self::$nmAtrSq];
		$this->tpModificacao = $registrobanco[self::$nmAtrTpModificacao];
		$this->dtModificacao = $registrobanco[self::$nmAtrDtModificacao];		
		$this->vlModificacaoReferencial = $registrobanco[self::$nmAtrVlModificacaoReferencial];
		$this->vlModificacaoAoContrato = $registrobanco[self::$nmAtrVlModificacaoAoContrato];
		$this->vlModificacaoReal = $registrobanco[self::$nmAtrVlModificacaoReal];
		
		$this->numMesesParaOFimdoPeriodo = $registrobanco[self::$nmAtrNumMesesParaOFimPeriodo];
		$this->numPercentual = $registrobanco[self::$nmAtrNumPercentual];
		
	}
	function getDadosFormulario() {
		$this->vocontrato->getDadosFormulario();	
	
		$this->sq = @$_POST[self::$nmAtrSq];
		$this->tpModificacao = @$_POST[self::$nmAtrTpModificacao];
		$this->dtModificacao = @$_POST[self::$nmAtrDtModificacao];
		$this->vlModificacaoReferencial = @$_POST[self::$nmAtrVlModificacaoReferencial];
		$this->vlModificacaoAoContrato = @$_POST[self::$nmAtrVlModificacaoAoContrato];
		$this->vlModificacaoReal = @$_POST[self::$nmAtrVlModificacaoReal];
		
		$this->numMesesParaOFimdoPeriodo = @$_POST[self::$nmAtrNumMesesParaOFimPeriodo];
		$this->numPercentual = @$_POST[self::$nmAtrNumPercentual];
		
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}
	
	function toString() {
		$retorno .= "Sq:" . $this->sq;
		$retorno .= "|Contrato:" . formatarCodigoContrato($this->vocontrato->cdContrato, $this->vocontrato->anoContrato, $this->vocontrato->tipo);		
		
		return $retorno;
	}
	
	function getValorChavePrimaria(){
		return $this->vocontrato->anoContrato
		. CAMPO_SEPARADOR
		. $this->vocontrato->cdContrato
		. CAMPO_SEPARADOR
		. $this->vocontrato->tipo
		. CAMPO_SEPARADOR
		. $this->sq;		
	}
	
	function getChavePrimariaVOExplode($array){
		$this->vocontrato->anoContrato = $array[0];
		$this->vocontrato->cdContrato = $array[1];
		$this->vocontrato->tipo = $array[2];
		$this->sq = $array[3];
	}
	
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Contrato-Modificação: " . $this->toString();
		return $retorno;
	}
}
