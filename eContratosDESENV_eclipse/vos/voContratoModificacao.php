<?php
include_once (caminho_lib . "voentidade.php");
include_once ("dbContratoModificacao.php");
include_once (caminho_funcoes . "contrato_mod/dominioTpModificacaoContrato.php");

class voContratoModificacao extends voentidade {
	static $ID_REQ_DIV_DADOS_CONTRATO_MODIFICACAO = "ID_REQ_DIV_DADOS_CONTRATO_MODIFICACAO"; 
	static $ID_REQ_VL_BASE_PERCENTUAL = "ID_REQ_VL_BASE_PERCENTUAL";
	
	static $nmColVlMensalParaFinsDeModAtual= "NmColVlMensalParaFinsDeModAtual";
	static $nmColVlGlobalParaFinsDeModAtual= "NmColVlGlobalParaFinsDeModAtual";	
	
	static $nmAtrSq = "ctmod_sq";
	static $nmAtrCdContrato  = "ct_numero";
	static $nmAtrAnoContrato  = "ct_exercicio";
	static $nmAtrTipoContrato =  "ct_tipo";
	static $nmAtrCdEspecieContrato =  "ct_cd_especie";
	static $nmAtrSqEspecieContrato =  "ct_sq_especie";

	static $nmAtrTpModificacao = "ctmod_tipo";
	static $nmAtrVlModificacaoReferencial = "ctmod_vlreferencial";
	static $nmAtrVlModificacaoAoContrato = "ctmod_vlaocontrato";
	static $nmAtrVlModificacaoReal = "ctmod_vlreal";

	static $nmAtrVlMensalAtualizado = "ctmod_vlmensalatual";
	static $nmAtrVlGlobalAtualizado = "ctmod_vlglobalatual";
	static $nmAtrVlGlobalReal = "ctmod_vlglobalreal";
	
	static $nmAtrNumPercentual = "ctmod_numpercentual";
	static $nmAtrDtModificacao = "ctmod_dtreferencia";

	static $nmAtrNumMesesParaOFimPeriodo = "ctmod_nummesesfimperiodo";
	static $nmAtrObs = "ctmod_obs";

	var $sq;
	var $vocontrato;
	var $vlModificacaoReferencial;
	var $vlModificacaoAoContrato;
	var $vlModificacaoReal;

	var $vlMensalAtual;
	var $vlGlobalAtual;
	var $vlGlobalReal;
	
	var $numPercentual;
	var $dtModificacao;
	var $tpModificacao;

	var $numMesesParaOFimdoPeriodo;
	var $obs;

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
		return "CONTRATO-MODIFICAÇÃO (Acréscimos e Supressões)";
	}
	public static function getNmTabela() {
		return "contrato_mod";
	}
	public static function getNmClassProcesso() {
		return "dbContratoModificacao";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		$query .= " AND " . $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
	
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
	
			return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrAnoContrato . "=" . $this->vocontrato->anoContrato;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdContrato . "=" . $this->vocontrato->cdContrato;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrTipoContrato . "=" . getVarComoString($this->vocontrato->tipo);
	
		return $query;
	}
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
				
				self::$nmAtrVlMensalAtualizado,
				self::$nmAtrVlGlobalAtualizado,
				self::$nmAtrVlGlobalReal,
				
				self::$nmAtrNumMesesParaOFimPeriodo,
				self::$nmAtrNumPercentual,
				self::$nmAtrObs,
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
		
		$this->vlMensalAtual = $registrobanco[self::$nmAtrVlMensalAtualizado];
		$this->vlGlobalAtual = $registrobanco[self::$nmAtrVlGlobalAtualizado];
		$this->vlGlobalReal = $registrobanco[self::$nmAtrVlGlobalReal];

		$this->numMesesParaOFimdoPeriodo = $registrobanco[self::$nmAtrNumMesesParaOFimPeriodo];
		$this->numPercentual = $registrobanco[self::$nmAtrNumPercentual];
		$this->obs = $registrobanco[self::$nmAtrObs];

	}
	function getDadosFormulario() {
		$this->vocontrato->getDadosFormulario();

		$this->sq = @$_POST[self::$nmAtrSq];
		$this->tpModificacao = @$_POST[self::$nmAtrTpModificacao];
		$this->dtModificacao = @$_POST[self::$nmAtrDtModificacao];
		$this->vlModificacaoReferencial = @$_POST[self::$nmAtrVlModificacaoReferencial];
		$this->vlModificacaoAoContrato = @$_POST[self::$nmAtrVlModificacaoAoContrato];
		$this->vlModificacaoReal = @$_POST[self::$nmAtrVlModificacaoReal];
		
		$this->vlMensalAtual = @$_POST[self::$nmAtrVlMensalAtualizado];
		$this->vlGlobalAtual = @$_POST[self::$nmAtrVlGlobalAtualizado];
		$this->vlGlobalReal = @$_POST[self::$nmAtrVlGlobalReal];

		$this->numMesesParaOFimdoPeriodo = @$_POST[self::$nmAtrNumMesesParaOFimPeriodo];
		$this->numPercentual = @$_POST[self::$nmAtrNumPercentual];
		$this->obs = @$_POST[self::$nmAtrObs];

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
