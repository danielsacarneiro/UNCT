<?php
include_once(caminho_funcoes."contrato_consolidacao/dominioContratoProducaoEfeitos.php");
include_once (caminho_util."dominioSetor.php");
include_once ("vocontrato.php");

Class voContratoInfo extends voentidade{
	
	static $NM_TABELA_RERRA = "NM_TABELA_RERRA";
	static $NmColNumRerra = "NmColNumRerra";
	static $TEXTO_TAG_CONTRATO_SUBST = "SEI da demanda do contrato MATER substituto da presente contratação.";
	static $IDREQNmPessoaGestor = "IDREQNmPessoaGestor";
	static $NM_DIV_CONTRATO_SUBS = "NM_DIV_CONTRATO_SUBS";
	
	static $nmAtrCdContrato  = "ct_numero";
	static $nmAtrAnoContrato  = "ct_exercicio";
	static $nmAtrTipoContrato =  "ct_tipo";
	
	static $nmAtrCdAutorizacaoContrato =  	"ctinf_cd_autorizacao";
	static $nmAtrObs = "ctinf_obs";	
	static $nmAtrDtProposta = "ctinf_dt_proposta";
	static $nmAtrDtBaseReajuste = "ctinf_dt_basereajuste";
		
	static $nmAtrInTemGarantia = "ctinf_in_garantia";
	static $nmAtrTpGarantia = "ctinf_tp_garantia";
	 
	static $nmAtrCdClassificacao = "ctinf_cd_classificacao";
	static $nmAtrInCredenciamento = "ctinf_in_credenciamento";
	static $nmAtrInSeraProrrogado = "ctinf_in_seraprorrogado";	
	
	static $nmAtrInMaoDeObra = "ctinf_in_mao_obra";
	static $nmAtrCdPessoaGestor = "ctinf_cd_pegestor";
	
	static $nmAtrNumPrazo = "ctinf_numprazo";
	static $nmAtrInEscopo =  	"ctinf_in_escopo";
	static $nmAtrInPrazoProrrogacao =  	"ctinf_in_prazoprorrogacao";
	static $nmAtrInEstudoTecnicoSAD =  	"ctinf_in_sad_estudotec";
	
	static $nmAtrNumPrazoMater = "ctinf_numprazoMATER";
	
	static $nmAtrInPendencias = "ctinf_pendencias";
	static $nmAtrInPendenciasBANCO = "ctinf_pendenciasBANCO";
	static $nmAtrSEIContratoSubstituto = "ctinf_SEIcontratosubstituto";
		
	var $cdContrato = "";
	var $anoContrato  = "";
	var $tipo = "";	
	var $cdAutorizacao = "";
	var $obs = "";
	var $dtProposta = "";
	var $dtBaseReajuste = "";
	
	var $inTemGarantia = "";
	var $tpGarantia = "";
	 
	var $inMaoDeObra = "";
	var $cdClassificacao = "";
	var $inCredenciamento = "";
	var $inSeraProrrogado = "";
	var $cdPessoaGestor = "";
	var $inEscopo = "";
	var $inPrazoProrrogacao = "";
	var $inEstudoTecnicoSAD = "";
	var $inPendencias = "";
	var $SEIContratoSubstituto = "";
	
	var $dbprocesso = null;
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )

	function __construct() {
		parent::__construct();
		$this->temTabHistorico = true;
		//por enquanto nao tem tabela relacionada que impeca a exclusao do registro principal
		//diferente do voDemanda, por ex
		$this->temTabsRelacionamentoQueImpedemExclusaoDireta = false;
		
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

	public static function getTituloJSPConsolidacao(){
		return  "CONTRATO-CONSOLIDAÇÃO";
	}
	
	public static function getTituloJSPContratosAVencerAno(){
		return  "CONTRATOS-CONSOLIDAÇÃO-A VENCER";
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
				self::$nmAtrDtProposta,
				self::$nmAtrDtBaseReajuste,
				
				self::$nmAtrInTemGarantia,
				self::$nmAtrTpGarantia,
				self::$nmAtrCdClassificacao,
				self::$nmAtrInCredenciamento,
				self::$nmAtrInSeraProrrogado,
				self::$nmAtrInMaoDeObra,
				self::$nmAtrCdPessoaGestor,
				self::$nmAtrInEscopo,
				self::$nmAtrInPrazoProrrogacao,
				self::$nmAtrInEstudoTecnicoSAD,
				self::$nmAtrInPendencias,
				self::$nmAtrSEIContratoSubstituto,
		);

		return $retorno;
	}

	function getAtributosChavePrimaria(){
		$retorno = array(
				self::$nmAtrAnoContrato,				
				self::$nmAtrCdContrato,
				self::$nmAtrTipoContrato,
		);

		return $retorno;
	}
	
	/**
	 * utilizada para validar a inclusao dos campos obrigatorios
	 * @return string[]
	 */
	function getValoresAtributosObrigatorios(){
		$retorno = array(
				$this->inEstudoTecnicoSAD => "Estudo.Técnico.SAD",
				$this->cdClassificacao => "Classificação",
				$this->inPrazoProrrogacao => "Prorrogação",
				$this->inEscopo => "Escopo",
				$this->inCredenciamento => "Credenciamento",
				$this->inTemGarantia => "Tem.Garantia",
				$this->dtProposta => "Dt.Proposta",
		);
		
		$retorno = $this->getValoresAtributosObrigatoriosPorEntidade($retorno);
		
		return $retorno;
	}
	
	static function getVOContratoInfoDeUmVoContrato($vocontrato){
		$retorno = new voContratoInfo();
		$retorno->cdContrato = $vocontrato->cdContrato;
		$retorno->anoContrato = $vocontrato->anoContrato;
		$retorno->tipo = $vocontrato->tipo;
		$retorno->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$retorno->sqEspecie = 1;
	
		return $retorno;
	}
	
	/**
	 * registrobanco serve somente pra completar as informacoes, quando existir
	 * @param unknown $registrobanco
	 * @return vocontrato
	 */
	function getVOContrato($registrobanco = null){
		$retorno = new vocontrato();
		$retorno->cdContrato = $this->cdContrato;
		$retorno->anoContrato = $this->anoContrato;
		$retorno->tipo = $this->tipo;
		$retorno->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$retorno->sqEspecie = 1;
		if($registrobanco != null){
			//echo "nao eh nulo " . $registrobanco[vocontrato::$nmAtrVlMensalContrato];
			$retorno->vlMensal = $registrobanco[vocontrato::$nmAtrVlMensalContrato];
		}
		
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
		$this->dtBaseReajuste = $registrobanco[self::$nmAtrDtBaseReajuste];
		
		$this->inTemGarantia = $registrobanco[self::$nmAtrInTemGarantia];
		$this->tpGarantia = $registrobanco[self::$nmAtrTpGarantia];
		
		$this->cdClassificacao = $registrobanco[self::$nmAtrCdClassificacao];
		$this->inCredenciamento = $registrobanco[self::$nmAtrInCredenciamento];
		$this->inSeraProrrogado = $registrobanco[self::$nmAtrInSeraProrrogado];
		$this->inMaoDeObra = $registrobanco[self::$nmAtrInMaoDeObra];
		$this->cdPessoaGestor = $registrobanco[self::$nmAtrCdPessoaGestor];
		$this->inEscopo = $registrobanco[self::$nmAtrInEscopo];
		$this->inPrazoProrrogacao = $registrobanco[self::$nmAtrInPrazoProrrogacao];
		$this->inEstudoTecnicoSAD = $registrobanco[self::$nmAtrInEstudoTecnicoSAD];
		$this->inPendencias = $registrobanco[self::$nmAtrInPendencias];
		$this->SEIContratoSubstituto = $registrobanco[self::$nmAtrSEIContratoSubstituto];
	}

	function getDadosFormulario(){
		$this->cdContrato = @$_POST[self::$nmAtrCdContrato];
		$this->anoContrato  = @$_POST[self::$nmAtrAnoContrato];
		$this->tipo  = @$_POST[self::$nmAtrTipoContrato];
		$this->cdAutorizacao  = @$_POST[self::$nmAtrCdAutorizacaoContrato];
		$this->obs = @$_POST[self::$nmAtrObs];
		$this->dtProposta = @$_POST[self::$nmAtrDtProposta];
		$this->dtBaseReajuste = @$_POST[self::$nmAtrDtBaseReajuste];
		
		$this->inTemGarantia = $_POST[self::$nmAtrInTemGarantia];
		$this->tpGarantia = $_POST[self::$nmAtrTpGarantia];
		
		$this->cdClassificacao = $_POST[self::$nmAtrCdClassificacao];
		$this->inCredenciamento = $_POST[self::$nmAtrInCredenciamento];
		$this->inSeraProrrogado = $_POST[self::$nmAtrInSeraProrrogado];
		$this->inMaoDeObra = $_POST[self::$nmAtrInMaoDeObra];		
		$this->cdPessoaGestor = $_POST[self::$nmAtrCdPessoaGestor];
		$this->inEscopo = $_POST[self::$nmAtrInEscopo];
		$this->inPrazoProrrogacao = $_POST[self::$nmAtrInPrazoProrrogacao];
		$this->inEstudoTecnicoSAD = $_POST[self::$nmAtrInEstudoTecnicoSAD];
		$this->inPendencias = $_POST[self::$nmAtrInPendencias];
		if(is_array($this->inPendencias)){
			$this->inPendencias = static::getArrayComoStringCampoSeparador($this->inPendencias);
		}
		
		$this->SEIContratoSubstituto = $_POST[self::$nmAtrSEIContratoSubstituto];
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}
	
	/*function setCarimboObsParaIncluirNoBanco(){
		$novaObs = $this->obs;
		if($novaObs != null && $novaObs != ""){
			$nmUsuario = name_user;
			$dhAlteracao = getDataHoraAtual();				
			$novaObs = "\n[$nmUsuario, em $dhAlteracao]: " . $novaObs;
			
			$this->obs = $novaObs;
		}		 
	}*/
	 
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
	
	static function getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutorizacao, $InOR_AND = null){
		//valor default
		if($InOR_AND == null){
			$InOR_AND = constantes::$CD_OPCAO_OR;
		}
		
		$parametroMetodoEspecifico = dominioAutorizacao::getColecaoCdAutorizacaoInterface($colecaoAutorizacao, $InOR_AND);
		$operador = " IN ";
		$parametroComparacao = " (" . getSQLStringFormatadaColecaoIN($parametroMetodoEspecifico, false) . ")";
		if($InOR_AND == constantes::$CD_OPCAO_AND){
			$operador = " = ";
			$parametroComparacao = $parametroMetodoEspecifico;
		}
		//var_dump($parametroMetodoEspecifico);
		
		$retorno = " $operador $parametroComparacao";
		;
		
		return $retorno;
		
	}
	
	static function getTextoAlertaContratoLocação(){
		$retorno .=	"ATENÇÃO: Contratos de locação de imóvel NÃO se submetem ao art. 57, II, lei 8666/93.";
		$retorno .= " Tais contratos vão para a SAD em qualquer situação de RENOVAÇÃO/PRORROGAÇÃO,";
		$retorno .=	" inclusive exige-se laudo de avaliação a cada 5 anos (60meses, art6, par 2, da portaria),";
		$retorno .=	" de acordo com a portaria 1.116/2016. Em se tratando de REAJUSTE, é dispensável o envio à SAD (art 7, da mesma portaria).";
		
		return $retorno;		
	}
	
	static function getTextoAlertaContratoMaodeObra(){
		$retorno .=	"ATENÇÃO: O art. 3º da Lei Estadual nº 12.525/2003 impõe a fixação de garantia de execução em 5% (cinco por cento) do valor global do contrato.";	
		return $retorno;
	}

	static function getTextoAlertaContratoCredenciamento(){
		$retorno .=	"ATENÇÃO: Contratos de credenciamento NÃO EXIGEM aprovação da PGE (art. 1, par 5, do Decreto Estadual n 37.271/11)"; 
		$retorno .=	" e SAD (art. 5, VI, Decreto Estadual n 42.048/15), salvo a minuta, na fase do edital.";

		
		return $retorno;
	}
	
}
?>