<?php
include_once("vocontrato.php");
include_once("dbDemanda.php");
include_once (caminho_util."dominioSetor.php");

Class voContratoInfo extends voentidade{
	
	static $nmAtrCdContrato  = "ct_numero";
	static $nmAtrAnoContrato  = "ct_exercicio";
	static $nmAtrTipoContrato =  "ct_tipo";
	
	static $nmAtrCdAutorizacaoContrato =  	"ctinf_cd_autorizacao";
	static $nmAtrObs = "ctinf_obs";
	static $nmAtrDtProposta = "ctinf_dt_proposta";
		
	static $nmAtrInTemGarantia = "ctinf_in_garantia";
	static $nmAtrTpGarantia = "ctinf_tp_garantia";
	 
	static $nmAtrCdClassificacao = "ctinf_cd_classificacao";
	static $nmAtrInMaoDeObra = "ctinf_in_mao_obra";
	
	var $cdContrato = "";
	var $anoContrato  = "";
	var $tipo = "";	
	var $cdAutorizacao = "";
	var $obs = "";
	var $dtProposta = "";
	
	var $inTemGarantia = "";
	var $tpGarantia = "";
	 
	var $inMaoDeObra = "";
	var $cdClassificacao = "";
	
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
				
				self::$nmAtrInTemGarantia,
				self::$nmAtrTpGarantia,
				self::$nmAtrCdClassificacao,
				self::$nmAtrInMaoDeObra
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
		$retorno->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$retorno->sqEspecie = 1;
		
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
		
		$this->inTemGarantia = $registrobanco[self::$nmAtrInTemGarantia];
		$this->tpGarantia = $registrobanco[self::$nmAtrTpGarantia];
		
		$this->cdClassificacao = $registrobanco[self::$nmAtrCdClassificacao];
		$this->inMaoDeObra = $registrobanco[self::$nmAtrInMaoDeObra];
	}

	function getDadosFormulario(){
		$this->cdContrato = @$_POST[self::$nmAtrCdContrato];
		$this->anoContrato  = @$_POST[self::$nmAtrAnoContrato];
		$this->tipo  = @$_POST[self::$nmAtrTipoContrato];
		$this->cdAutorizacao  = @$_POST[self::$nmAtrCdAutorizacaoContrato];
		$this->obs = @$_POST[self::$nmAtrObs];
		$this->dtProposta = @$_POST[self::$nmAtrDtProposta];
		
		$this->inTemGarantia = $_POST[self::$nmAtrInTemGarantia];
		$this->tpGarantia = $_POST[self::$nmAtrTpGarantia];
		
		$this->cdClassificacao = $_POST[self::$nmAtrCdClassificacao];
		$this->inMaoDeObra = $_POST[self::$nmAtrInMaoDeObra];		
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
	
	static function getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutorizacao, $InOR_AND){
		
		$parametroMetodoEspecifico = dominioAutorizacao::getColecaoCdAutorizacaoIntercace($colecaoAutorizacao, $InOR_AND);
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

}
?>