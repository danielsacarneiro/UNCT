<?php
include_once (caminho_lib . "voentidade.php");
include_once ("dbContratoModificacao.php");
include_once (caminho_funcoes . "contrato_mod/dominioTpContratoModificacao.php");

class voContratoModificacao extends voentidade {
	
	static $ColecaoReajustesAplicados = array();
	static $ColecaoProrrogacoesRegistradas = array();	
	
	static $ID_REQ_DIV_DADOS_CONTRATO_MODIFICACAO = "ID_REQ_DIV_DADOS_CONTRATO_MODIFICACAO"; 
	static $ID_REQ_VL_BASE_PERCENTUAL = "ID_REQ_VL_BASE_PERCENTUAL";
	static $ID_REQ_NUM_PERCENTUAL_REAJUSTE = "ID_REQ_NUM_PERCENTUAL_REAJUSTE";
	static $ID_REQ_VL_BASE_REAJUSTE = "ID_REQ_VL_BASE_REAJUSTE";
	static $ID_REQ_InRetroativo = "ID_REQ_InRetroativo";
	static $ID_REQ_NumPrazoUltimaProrrogacao = "NumPrazoUltimaProrrogacao";
	
	static $InReajusteAplicado = "InReajusteAplicado";
	
	static $ID_REQ_VlMensalContratoInseridoTela = "VlMensalContratoInseridoTela";
	static $ID_REQ_VlGlobalContratoInseridoTela = "VlGlobalContratoInseridoTela";
	
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

	static $nmAtrVlMensalAnterior = "ctmod_vlmensalanterior";
	static $nmAtrVlGlobalAnterior = "ctmod_vlglobalanterior";
	
	static $nmAtrVlMensalModAtual = "ctmod_vlmensalmodatual";
	static $nmAtrVlGlobalModAtual = "ctmod_vlglobalmodatual";
	
	static $nmAtrNumPercentual = "ctmod_numpercentual";
	static $nmAtrDtModificacao = "ctmod_dtreferencia";
	static $nmAtrDtModificacaoFim = "ctmod_dtreferenciaFim";

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

	var $vlMensalAnterior;
	var $vlGlobalAnterior;
	
	var $vlMensalModAtual;
	var $vlGlobalModAtual;
	
	var $numPercentual;
	var $dtModificacao;
	var $dtModificacaoFim;
	var $tpModificacao;
	var $inRetroativo;

	var $numMesesParaOFimdoPeriodo;
	var $obs;

	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct($arrayChave = null) {
		parent::__construct1 ($arrayChave);
		//apenas tera historico quando reajuste retroativo for incluido
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
		return "CONTRATO-EXECU��O (Acr�scimos e Supress�es)";
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
				self::$nmAtrDtModificacaoFim,
				self::$nmAtrVlModificacaoReferencial,
				self::$nmAtrVlModificacaoReal,
				self::$nmAtrVlModificacaoAoContrato,
				
				self::$nmAtrVlMensalAtualizado,
				self::$nmAtrVlGlobalAtualizado,
				self::$nmAtrVlGlobalReal,
				
				self::$nmAtrVlMensalAnterior,
				self::$nmAtrVlGlobalAnterior,
				
				self::$nmAtrVlMensalModAtual,
				self::$nmAtrVlGlobalModAtual,
				
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
		$this->dtModificacaoFim = $registrobanco[self::$nmAtrDtModificacaoFim];
		$this->vlModificacaoReferencial = $registrobanco[self::$nmAtrVlModificacaoReferencial];
		$this->vlModificacaoAoContrato = $registrobanco[self::$nmAtrVlModificacaoAoContrato];
		$this->vlModificacaoReal = $registrobanco[self::$nmAtrVlModificacaoReal];
		
		$this->vlMensalAtual = $registrobanco[self::$nmAtrVlMensalAtualizado];
		$this->vlGlobalAtual = $registrobanco[self::$nmAtrVlGlobalAtualizado];
		$this->vlGlobalReal = $registrobanco[self::$nmAtrVlGlobalReal];

		$this->vlMensalAnterior = $registrobanco[self::$nmAtrVlMensalAnterior];
		$this->vlGlobalAnterior = $registrobanco[self::$nmAtrVlGlobalAnterior];
		
		$this->vlMensalModAtual = $registrobanco[self::$nmAtrVlMensalModAtual];
		$this->vlGlobalModAtual = $registrobanco[self::$nmAtrVlGlobalModAtual];
		
		$this->numMesesParaOFimdoPeriodo = $registrobanco[self::$nmAtrNumMesesParaOFimPeriodo];
		$this->numPercentual = $registrobanco[self::$nmAtrNumPercentual];
		$this->obs = $registrobanco[self::$nmAtrObs];

	}
	function getDadosFormulario() {
		$this->vocontrato->getDadosFormulario();

		$this->sq = @$_POST[self::$nmAtrSq];
		$this->tpModificacao = @$_POST[self::$nmAtrTpModificacao];
		$this->dtModificacao = @$_POST[self::$nmAtrDtModificacao];
		$this->dtModificacaoFim = @$_POST[self::$nmAtrDtModificacaoFim];
		$this->vlModificacaoReferencial = @$_POST[self::$nmAtrVlModificacaoReferencial];
		$this->vlModificacaoAoContrato = @$_POST[self::$nmAtrVlModificacaoAoContrato];
		$this->vlModificacaoReal = @$_POST[self::$nmAtrVlModificacaoReal];
		
		$this->vlMensalAtual = @$_POST[self::$nmAtrVlMensalAtualizado];
		$this->vlGlobalAtual = @$_POST[self::$nmAtrVlGlobalAtualizado];
		$this->vlGlobalReal = @$_POST[self::$nmAtrVlGlobalReal];
		
		$this->vlMensalAnterior = @$_POST[vocontrato::$nmAtrVlMensalContrato];
		$this->vlGlobalAnterior = @$_POST[vocontrato::$nmAtrVlGlobalContrato];
		
		$this->numPercentual = @$_POST[self::$nmAtrNumPercentual];
		
		$this->vlMensalModAtual = @$_POST[self::$nmAtrVlMensalModAtual];
		$this->vlGlobalModAtual = @$_POST[self::$nmAtrVlGlobalModAtual];
		
		$isReajuste = $this->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_REAJUSTE
				|| $this->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_REPACTUACAO;
		if($isReajuste){
			$this->numPercentual = @$_POST[self::$ID_REQ_NUM_PERCENTUAL_REAJUSTE];
			
			$fator = 1 + (getDecimalSQL($this->numPercentual)/100);
						
			$this->vlMensalModAtual = getDecimalSQL($this->vlMensalModAtual)*$fator;
			$this->vlGlobalModAtual = getDecimalSQL($this->vlGlobalModAtual)*$fator;				
		}

		$this->numMesesParaOFimdoPeriodo = @$_POST[self::$nmAtrNumMesesParaOFimPeriodo];
		$this->obs = @$_POST[self::$nmAtrObs];

		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}

	function toString($completo = false) {
		$retorno .= "Sq:" . $this->sq;
		$retorno .= "|Contrato:" . formatarCodigoContrato($this->vocontrato->cdContrato, $this->vocontrato->anoContrato, $this->vocontrato->tipo);
		if($completo){
			//$retorno .= " => " . $this->vocontrato->toString();
			$retorno .= " - " . $this->vocontrato->cdEspecie . "," . $this->vocontrato->sqEspecie; 
		}

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

	function getValorChavePrimariaTermo(){
		return $this->vocontrato->anoContrato
		. CAMPO_SEPARADOR
		. $this->vocontrato->cdContrato
		. CAMPO_SEPARADOR
		. $this->vocontrato->tipo
		. CAMPO_SEPARADOR
		. $this->vocontrato->cdEspecie
		. CAMPO_SEPARADOR
		. $this->vocontrato->sqEspecie;
	}
	
	/**
	 * Serve para identificar a chave do contrato modificacao
	 * que envolve o termo contratual + o sequencial do contrato modificacao
	 * @return string
	 */
	function getValorChavePrimariaContratoModCompleto(){
		return $this->vocontrato->anoContrato
		. CAMPO_SEPARADOR
		. $this->vocontrato->cdContrato
		. CAMPO_SEPARADOR
		. $this->vocontrato->tipo
		. CAMPO_SEPARADOR
		. $this->vocontrato->cdEspecie
		. CAMPO_SEPARADOR
		. $this->vocontrato->sqEspecie
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
		$retorno = "Contrato-Modifica��o: " . $this->toString();
		
		try{
			$vocontrato = $this->vocontrato;		
			$dbcontrato = new dbcontrato();
			$vocontrato = $dbcontrato->consultarPorChaveVO($vocontrato);
			//$vocontrato = new vocontrato();
			$vlGlobalMod = getValorMoedaComoDecimal($this->vlGlobalAtual);
			$vlGlobalContrato = getValorMoedaComoDecimal($vocontrato->vlGlobal);
			
			if($vlGlobalMod != $vlGlobalContrato){
				$retorno .= "<br><br>ATEN��O: verifique se o valor do contrato deve ser alterado na funcao 'Contratos."; 
			}
		}catch (Exception $ex){
			$retorno .= "<br><br>" . $ex->getMessage();
		}
		return $retorno;
	}
	
	function getPercentualAcrescimoAtual(){
		$percAcrescimo = 0;
		if($this->vlGlobalModAtual == null){
			throw new excecaoGenerica("Valor Global Modifica��o Atual n�o pode ser nulo.");
		}
		
		$vlGlobalAtual = floatval($this->vlGlobalAtual);
		$vlGlobalModAtual = floatval($this->vlGlobalModAtual);
		
		$isPercentualAConsiderar = $this->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_ACRESCIMO
				|| $this->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_SUPRESSAO; 
		
		if($isPercentualAConsiderar){
				$percAcrescimo = 100*(($vlGlobalAtual - $vlGlobalModAtual)/$vlGlobalModAtual);										
		}		
		return $percAcrescimo;		
	}
	
	function setPercentualReajuste($voContratoModReajuste){
		//$voContratoModReajuste = new voContratoModificacao();
		
		$percentual = $voContratoModReajuste->numPercentual;
		//echoo($voContratoModReajuste->getValorChaveHTML() . ":" . $voContratoModReajuste->vlMensalAnterior);
	
		//se for acrescimo ou supressao, o percentual a aplicar para corrigir o valor reajustado eh somente o aritmetico	
		if(in_array($voContratoModReajuste->tpModificacao, dominioTpContratoModificacao::getColecaoChavesPercentuaisMatematicos())){
			$percentual = $voContratoModReajuste->vlModificacaoReferencial/$voContratoModReajuste->vlMensalAnterior*100;
		}
		
		if($percentual != null){
			/*$this->vlModificacaoReferencial = atualizarValorPercentual($this->vlModificacaoReferencial, $percentual);
			$this->vlModificacaoAoContrato = atualizarValorPercentual($this->vlModificacaoAoContrato, $percentual);
			$this->vlModificacaoReal = atualizarValorPercentual($this->vlModificacaoReal, $percentual);*/
			
			$this->vlMensalAtual = atualizarValorPercentual($this->vlMensalAtual, $percentual);
			/*$this->vlGlobalAtual = atualizarValorPercentual($this->vlGlobalAtual, $percentual);
			$this->vlGlobalReal = atualizarValorPercentual($this->vlGlobalReal, $percentual);
			
			$this->vlMensalAnterior = atualizarValorPercentual($this->vlMensalAnterior, $percentual);
			$this->vlGlobalAnterior = atualizarValorPercentual($this->vlGlobalAnterior, $percentual);
			
			$this->vlMensalModAtual = atualizarValorPercentual($this->vlMensalModAtual, $percentual);
			$this->vlGlobalModAtual = atualizarValorPercentual($this->vlGlobalModAtual, $percentual);*/			
		}
	}
	
	/**
	 * por enquanto atualiza somente o valor mensal pois eh o unico usado como referencia para os valores corrigidos
	 * @param unknown $voContratoModReajuste
	 */
	function getValorMensalReajustadoAtual($voContratoModReajuste){
		
		/*$this->vlModificacaoReferencial = $voContratoModReajuste->vlModificacaoReferencial;		
		$this->vlModificacaoAoContrato = $voContratoModReajuste->vlModificacaoAoContrato;
		$this->vlModificacaoReal = $voContratoModReajuste->vlModificacaoReal;*/
			
		$this->vlMensalAtual = $voContratoModReajuste->vlMensalAtual;
		/*$this->vlGlobalAtual = $voContratoModReajuste->vlGlobalAtual;
		$this->vlGlobalReal = $voContratoModReajuste->vlGlobalReal;
		
		if($voContratoModReajuste->vlMensalAnterior != null){
			$this->vlMensalAnterior = $voContratoModReajuste->vlMensalAnterior;
		}
		if($voContratoModReajuste->vlGlobalAnterior != null){
			$this->vlGlobalAnterior = $voContratoModReajuste->vlGlobalAnterior;
		}
			
		$this->vlMensalModAtual = $voContratoModReajuste->vlMensalModAtual;
		$this->vlGlobalModAtual = $voContratoModReajuste->vlGlobalModAtual;*/
	}
			
}
