<?php
include_once (caminho_lib . "voentidade.php");
//include_once ("dbProcLicitatorio.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
include_once (caminho_util . "dominioSetor.php");
include_once (caminho_funcoes . "proc_licitatorio/dominioModalidadeProcLicitatorio.php");
include_once (caminho_funcoes . "proc_licitatorio/dominioTipoProcLicitatorio.php");
include_once (caminho_funcoes . "proc_licitatorio/dominioComissaoProcLicitatorio.php");

class voProcLicitatorio extends voentidade {
	static $nmAtrCd = "pl_cd";
	static $nmAtrAno = "pl_ex";	

	static $nmAtrCdOrgaoResponsavel = "pl_orgao_responsavel";
	static $nmAtrCdComissao = "pl_comissao_cd";	
	static $nmAtrCdModalidade = "pl_mod_cd";
	static $nmAtrNumModalidade = "pl_mod_num";
	static $nmAtrTipo = "pl_tp";
	static $nmAtrCdPregoeiro = "pl_cd_pregoeiro";
	
	static $nmAtrDtAbertura = "pl_dt_abertura";
	static $nmAtrDtPublicacao = "pl_dt_publicacao";
	static $nmAtrObjeto = "pl_objeto";
	static $nmAtrObservacao = "pl_observacao";
	static $nmAtrSituacao = "pl_si";
	
	var $cd = "";
	var $ano = "";
	var $cdDemanda = "";
	var $anoDemanda = "";
	
	var $cdOrgaoResponsavel = "";
	var $cdComissao = "";
	var $cdModalidade = "";
	var $numModalidade = "";
	var $tipo = "";
	var $cdPregoeiro = "";
	
	var $dtAbertura = "";
	var $dtPublicacao = "";
	var $objeto = "";
	var $obs = "";
	var $situacao = "";	
	
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = true;		
		// retira os atributos padrao que nao possui
		// remove tambem os que o banco deve incluir default
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao 
		);
		$this->removeAtributos ( $arrayAtribRemover );
	}
	public static function getTituloJSP() {
		return "PROCESSO LICITATÓRIO";
	}
	public static function getNmTabela() {
		return "proc_licitatorio";
	}
	public static function getNmClassProcesso() {
		return "dbProcLicitatorio";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCd . "=" . $this->cd;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrAno . "=" . $this->ano;
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrAno,
				self::$nmAtrCd,
				self::$nmAtrCdOrgaoResponsavel,
				self::$nmAtrCdComissao,
				self::$nmAtrCdModalidade,
				self::$nmAtrNumModalidade,
				self::$nmAtrTipo,
				self::$nmAtrCdPregoeiro,
				self::$nmAtrDtAbertura,
				self::$nmAtrDtPublicacao,				
				self::$nmAtrObjeto,
				self::$nmAtrObservacao,
				self::$nmAtrSituacao,				
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrAno,
				self::$nmAtrCd 
		);
		
		return $retorno;
	}
	
	function getDadosRegistroBanco($registrobanco) {		
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco [self::$nmAtrCd];
		$this->ano = $registrobanco [self::$nmAtrAno];
		$this->cdDemanda = $registrobanco [voDemanda::$nmAtrCd];
		$this->anoDemanda = $registrobanco [voDemanda::$nmAtrAno];
		
		$this->cdOrgaoResponsavel = $registrobanco [self::$nmAtrCdOrgaoResponsavel];
		$this->cdComissao = $registrobanco [self::$nmAtrCdComissao];		
		$this->cdModalidade = $registrobanco [self::$nmAtrCdModalidade];
		$this->numModalidade = $registrobanco [self::$nmAtrNumModalidade];
		$this->tipo = $registrobanco [self::$nmAtrTipo];
		$this->cdPregoeiro = $registrobanco [self::$nmAtrCdPregoeiro];
		$this->dtAbertura = $registrobanco [self::$nmAtrDtAbertura];
		$this->dtPublicacao = $registrobanco [self::$nmAtrDtPublicacao];
		$this->objeto = $registrobanco [self::$nmAtrObjeto];
		$this->obs = $registrobanco [self::$nmAtrObservacao];
		$this->situacao = $registrobanco [self::$nmAtrSituacao];
	}
	function getDadosFormulario() {

		$this->cd = @$_POST [self::$nmAtrCd];
		$this->ano = @$_POST [self::$nmAtrAno];
		
		$this->cdOrgaoResponsavel = dominioSetor::$CD_SETOR_SEFAZ;
		//$this->cdOrgaoResponsavel = @$_POST [self::$nmAtrCdOrgaoResponsavel];
		$this->cdComissao = @$_POST [self::$nmAtrCdComissao];
		$this->cdModalidade = @$_POST [self::$nmAtrCdModalidade];
		$this->numModalidade = @$_POST [self::$nmAtrNumModalidade];
		$this->tipo = @$_POST [self::$nmAtrTipo];
		$this->cdPregoeiro = @$_POST [self::$nmAtrCdPregoeiro];
		$this->dtAbertura = @$_POST [self::$nmAtrDtAbertura];
		$this->dtPublicacao = @$_POST [self::$nmAtrDtPublicacao];
		$this->objeto = @$_POST [self::$nmAtrObjeto];
		$this->obs = @$_POST [self::$nmAtrObservacao];
		$this->situacao = @$_POST [self::$nmAtrSituacao];
				
		// completa com os dados da entidade
		$this->getDadosFormularioEntidade ();
	}

	function toString() {
		$retorno .= $this->ano;
		$retorno .= "," . $this->cd;
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->ano . CAMPO_SEPARADOR . $this->cd . CAMPO_SEPARADOR . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {
		$this->ano = $array [0];
		$this->cd = $array [1];
		$this->sqHist = $array [2];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = static::getTituloJSP() . " (Número - Ano): " . formatarCodigoAnoComplementoArgs ( $this->cd, $this->ano, TAMANHO_CODIGOS, null );
		if ($this->sqHist != null) {
			$retorno .= "<br>Núm. Histórico: " . complementarCharAEsquerda ( $this->sqHist, "0", TAMANHO_CODIGOS );
		}
		return $retorno;
	}
}
?>