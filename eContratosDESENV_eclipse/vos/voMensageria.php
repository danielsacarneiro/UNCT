<?php
include_once (caminho_lib . "voentidade.php");
include_once(caminho_funcoes."alertas/alertas.php");
include_once(caminho_funcoes."alertas/Biblioteca_alertas.php");
include_once(caminho_funcoes."mensageria/dominioTipoMensageria.php");

class voMensageria extends voentidade {	
	
	static $DS_RESPONSABILIDADE_CAMPO_OBR = "*Assumo a responsabilidade de n�o incluir os campos obrigat�rios.";	
	static $MSG_IN_VERIFICAR_SERA_PRORROGADO = "Verifique o indicador de prorroga��o do contrato.";
	
	//ativa o envio da mensageria para a UNCT
	static $ATIVADO = true;
	//ativa o envio do email do relatorio diario
	static $ENVIAR_EMAIL_RELATORIO_DIARIO = true;
	//ativa o envio do email da mensageria ao gestor apenas para a UNCT
	static $ENVIAR_EMAIL_GESTOR_UNCT = true;
	//ativa o envio do email da mensageria ao gestor
	static $ENVIAR_EMAIL_GESTOR_CONTRATO = true;
	//ativa o envio do email para teste permitindo o envio de mais de um email por dia
	static $IN_VERIFICAR_FREQUENCIA = "S";
	static $IMPRIMIR_MENSAGEM_SE_CONSULTA_VAZIA = true;
	
	static $NUM_DIAS_FREQUENCIA_MAIL_PADRAO = 7;
	static $NUM_DIAS_CONTRATOS_A_VENCER = 45;
	static $NUM_DIAS_CONTRATOS_A_VENCER_IMPRORROGAVEIS = 180;
	
	static $nmCOLDhUltimoEnvio = "nmCOLDhUltimoEnvio";
	
	static $nmAtrSq = "msg_sq";
	static $nmAtrAnoContrato  = "ct_exercicio";
	static $nmAtrCdContrato  = "ct_numero";
	static $nmAtrTipoContrato =  "ct_tipo";
	
	static $nmAtrTipo = "msg_tipo";
	static $nmAtrDtInicio = "msg_dt_inicio";
	static $nmAtrDtFim = "msg_dt_fim";
	static $nmAtrInHabilitado = "msg_in_habilitado";
	static $nmAtrNumDiasFrequencia = "msg_num_dias_frequencia";
	
	static $nmAtrObs = "msg_obs";	
			
	var $sq;
	var $vocontratoinfo;
	var $dtInicio;
	var $dtFim;
	var $inHabilitado;
	var $tipo;
	var $numDiasFrequencia;
	var $obs;	
	
	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct($arrayChave = null) {
		parent::__construct1 ($arrayChave);
		$this->temTabHistorico = false;
		
		// retira os atributos padrao que nao possui
		// remove tambem os que o banco deve incluir default
		/*$arrayAtribRemover = array (
				self::$nmAtrSq,
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao,
		);
		$this->removeAtributos ( $arrayAtribRemover );
		$this->varAtributosARemover = $arrayAtribRemover;*/
		
		
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao,
		);
		
		$arrayAtribInclusaoDBDefault = array (
				self::$nmAtrSq,
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao,
				
		);
		$this->setaAtributosRemocaoEInclusaoDBDefault($arrayAtribRemover, $arrayAtribInclusaoDBDefault);
		
		
		$this->vocontratoinfo = new voContratoInfo();
	}
	public static function getTituloJSP() {
		return "MENSAGERIA";
	}
	public static function getNmTabela() {
		return "mensageria";
	}
	public static function getNmClassProcesso() {
		return "dbMensageria";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ($isHistorico);
		$query .= $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		/*$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;*/
		
		return $query;
	}
	static function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrSq,
		);
		
		return $retorno;
	}
	static function getAtributosFilho() {
		$array1 = static::getAtributosChavePrimaria();
				
		$array2 = array (
				self::$nmAtrAnoContrato,
				self::$nmAtrCdContrato,				
				self::$nmAtrTipoContrato,
				
				self::$nmAtrTipo,
				self::$nmAtrDtInicio,
				self::$nmAtrDtFim,
				self::$nmAtrInHabilitado,
				self::$nmAtrNumDiasFrequencia,
				self::$nmAtrObs,
		);		
		$retorno = array_merge($array1, $array2);		
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		$this->sq = $registrobanco[self::$nmAtrSq];
		$this->vocontratoinfo->getDadosRegistroBanco($registrobanco);
		
		$this->dtInicio = $registrobanco[self::$nmAtrDtInicio];		
		$this->dtFim = $registrobanco[self::$nmAtrDtFim];
		$this->inHabilitado = $registrobanco[self::$nmAtrInHabilitado];
		$this->tipo = $registrobanco[self::$nmAtrTipo];
		$this->numDiasFrequencia = $registrobanco[self::$nmAtrNumDiasFrequencia];
		$this->obs = $registrobanco[self::$nmAtrObs];
	}
	function getDadosFormulario() {
		$this->sq = @$_POST[self::$nmAtrSq];
		$this->vocontratoinfo->getDadosFormulario();
		
		$this->dtInicio = @$_POST[self::$nmAtrDtInicio];
		$this->dtFim = getCampoRequest(self::$nmAtrDtFim, true);
		
		$this->inHabilitado = @$_POST[self::$nmAtrInHabilitado];
		$this->tipo = @$_POST[self::$nmAtrTipo];
		$this->numDiasFrequencia = @$_POST[self::$nmAtrNumDiasFrequencia];
		$this->obs = @$_POST[self::$nmAtrObs];
				
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}

	function toString() {
		$retorno .= "Alerta " . complementarCharAEsquerda($this->sq, "0", constantes::$TAMANHO_CODIGOS) . ".Contrato:" . formatarCodigoContrato($this->vocontratoinfo->cdContrato, $this->vocontratoinfo->anoContrato, $this->vocontratoinfo->tipo);
		
		return $retorno;
	}
	function getValorChavePrimaria() {
		$separador = CAMPO_SEPARADOR;
		// $separador = "b";
		return $this->sq;
	}
	function getChavePrimariaVOExplode($array) {		
		$this->sq = $array[0];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Mensageria: " . $this->toString();
		return $retorno;
	}
}
?>