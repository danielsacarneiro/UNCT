<?php
include_once (caminho_lib . "voentidade.php");
//include_once ("dbProcLicitatorio.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
include_once (caminho_util . "dominioSetor.php");
include_once (caminho_funcoes . "proc_licitatorio/dominioModalidadeProcLicitatorio.php");
include_once (caminho_funcoes . "proc_licitatorio/dominioTipoProcLicitatorio.php");
include_once (caminho_funcoes . "proc_licitatorio/dominioSituacaoPL.php");
include_once (caminho_funcoes . "proc_licitatorio/dominioComissaoProcLicitatorio.php");
include_once (caminho_funcoes . "proc_licitatorio/biblioteca_htmlProcLicitatorio.php");

class voProcLicitatorio extends voentidade {
	static $NmColNomePregoeiro = "NmColNomePregoeiro";
	
	static $nmAtrCd = "pl_cd";
	static $nmAtrAno = "pl_ex";	

	static $nmAtrCdOrgaoResponsavel = "pl_orgao_responsavel";
	static $nmAtrCdModalidade = "pl_mod_cd";
	static $nmAtrNumModalidade = "pl_mod_num";
	static $nmAtrTipo = "pl_tp";
	static $nmAtrCdPregoeiro = "pl_cd_pregoeiro";
	static $nmAtrCdCPL = "pl_cd_cpl";	
	
	static $nmAtrDtAbertura = "pl_dt_abertura";
	static $nmAtrDtPublicacao = "pl_dt_publicacao";
	static $nmAtrObjeto = "pl_objeto";
	static $nmAtrObservacao = "pl_observacao";
	static $nmAtrSituacao = "pl_si";
	static $nmAtrValor = "pl_valor";
	
	var $cd = "";
	var $ano = "";
	var $cdDemanda = "";
	var $anoDemanda = "";
	
	var $cdOrgaoResponsavel = "";
	var $cdModalidade = "";
	var $numModalidade = "";
	var $tipo = "";
	var $cdPregoeiro = "";
	var $cdCPL = "";
	
	var $dtAbertura = "";
	var $dtPublicacao = "";
	var $objeto = "";
	var $obs = "";
	var $situacao = "";	
	var $valor = "";
	
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
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdModalidade . "=" . getVarComoString($this->cdModalidade);
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrAno,
				self::$nmAtrCd,
				self::$nmAtrCdOrgaoResponsavel,
				self::$nmAtrCdModalidade,
				self::$nmAtrNumModalidade,
				self::$nmAtrTipo,
				self::$nmAtrCdPregoeiro,
				self::$nmAtrCdCPL,
				self::$nmAtrDtAbertura,
				self::$nmAtrDtPublicacao,				
				self::$nmAtrObjeto,
				self::$nmAtrObservacao,
				self::$nmAtrSituacao,				
				self::$nmAtrValor,
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrAno,
				self::$nmAtrCd,
				self::$nmAtrCdModalidade
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
		$this->cdModalidade = $registrobanco [self::$nmAtrCdModalidade];
		$this->numModalidade = $registrobanco [self::$nmAtrNumModalidade];
		$this->tipo = $registrobanco [self::$nmAtrTipo];
		$this->cdPregoeiro = $registrobanco [self::$nmAtrCdPregoeiro];
		$this->cdCPL = $registrobanco [self::$nmAtrCdCPL];
		$this->dtAbertura = $registrobanco [self::$nmAtrDtAbertura];
		$this->dtPublicacao = $registrobanco [self::$nmAtrDtPublicacao];
		$this->objeto = $registrobanco [self::$nmAtrObjeto];
		$this->obs = $registrobanco [self::$nmAtrObservacao];
		$this->situacao = $registrobanco [self::$nmAtrSituacao];
		$this->valor = $registrobanco [self::$nmAtrValor];
	}
	function getDadosFormulario() {

		$this->cd = @$_POST [self::$nmAtrCd];
		$this->ano = @$_POST [self::$nmAtrAno];
		
		$this->cdOrgaoResponsavel = dominioSetor::$CD_SETOR_SEFAZ;
		//$this->cdOrgaoResponsavel = @$_POST [self::$nmAtrCdOrgaoResponsavel];
		$this->cdModalidade = @$_POST [self::$nmAtrCdModalidade];
		$this->numModalidade = @$_POST [self::$nmAtrNumModalidade];
		$this->tipo = @$_POST [self::$nmAtrTipo];
		$this->cdPregoeiro = @$_POST [self::$nmAtrCdPregoeiro];
		$this->cdCPL = @$_POST [self::$nmAtrCdCPL];
		$this->dtAbertura = @$_POST [self::$nmAtrDtAbertura];
		$this->dtPublicacao = @$_POST [self::$nmAtrDtPublicacao];
		$this->objeto = @$_POST [self::$nmAtrObjeto];
		$this->obs = @$_POST [self::$nmAtrObservacao];
		$this->situacao = @$_POST [self::$nmAtrSituacao];
		$this->valor = @$_POST[self::$nmAtrValor];
				
		// completa com os dados da entidade
		$this->getDadosFormularioEntidade ();
	}

	function toString() {
		$retorno .= $this->ano;
		$retorno .= "," . $this->cd;
		$retorno .= "," . $this->cdModalidade;
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->ano . CAMPO_SEPARADOR . $this->cd . CAMPO_SEPARADOR . $this->cdModalidade . CAMPO_SEPARADOR . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {
		$this->ano = $array [0];
		$this->cd = $array [1];
		$this->cdModalidade = $array [2];
		$this->sqHist = $array [3];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = static::getTituloJSP() . " (Número - Ano): " . formatarCodigoAnoComplementoArgs ( $this->cd, $this->ano, TAMANHO_CODIGOS, null );
		$retorno .= " - ".dominioModalidadeProcLicitatorio::getDescricaoStatic($this->cdModalidade);
		if ($this->sqHist != null) {
			$retorno .= "<br>Núm. Histórico: " . complementarCharAEsquerda ( $this->sqHist, "0", TAMANHO_CODIGOS );
		}
		return $retorno;
	}
}
?>