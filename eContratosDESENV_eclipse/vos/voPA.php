<?php
include_once (caminho_funcoes . "pa/dominioSituacaoPA.php");
include_once (caminho_funcoes . "pa_penalidade/dominioTipoPenalidade.php");

class voPA extends voentidade {
	static $ID_REQ_DIV_PRAZO = "ID_REQ_DIV_PRAZO";
	
	static $nmAtrCdPA = "pa_cd"; // processo administrativo cd
	static $nmAtrAnoPA = "pa_ex"; // processo administrativo ano
	static $nmAtrAnoDemanda = "dem_ex";
	static $nmAtrCdDemanda = "dem_cd";
	static $nmAtrCdResponsavel = "pa_cd_responsavel";
	static $nmAtrObservacao = "pa_observacao";
	static $nmAtrPublicacao = "pa_publicacao";
	static $nmAtrDtAbertura = "pa_dt_abertura";
	static $nmAtrDtNotificacao = "pa_dt_notificacao";
	static $nmAtrDtUltNotificacaoParaManifestacao = "pa_dt_ult_notmanifestacao";
	static $nmAtrNumDiasPrazoUltNotificacao = "pa_prazodias_ult_notificacao";
	static $nmAtrInDiasUteisPrazoUltNotificacao = "pa_in_diasuteisprazo_ult_notificacao";
	static $nmAtrDtUltNotificacaoPrazoEncerrado = "pa_dt_ultnotprazoencerrado";
	static $nmAtrSituacao = "pa_si";
	var $cdPA = "";
	var $anoPA = "";
	var $cdDemanda = "";
	var $anoDemanda = "";
	var $obs = "";
	var $publicacao = "";
	var $dtPublicacao = "";
	var $dtAbertura = "";
	var $dtNotificacao = "";
	var $dtUlNotificacaoParaManifestacao = "";
	var $dtUlNotificacaoPrazoEncerrado = "";
	var $numDiasPrazoUltNotificacao = "";
	var $situacao = "";
	var $cdResponsavel = "";
	
	var $dbprocesso = null;
	
	// ...............................................................
	// Funcoes ( Propriedades e metodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = true;
		
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();
		// retira os atributos padrao que nao possui
		// remove tambem os que o banco deve incluir default
		$arrayAtribInclusaoDBDefault = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao 
		);
		$this->setaAtributosRemocaoEInclusaoDBDefault(null, $arrayAtribInclusaoDBDefault);
	}
	public static function getTituloJSP() {
		return "PROCESSO ADMINISTRATIVO DE APLICAÇÃO DE PENALIDADE (PAAP)";
	}
	public static function getNmTabela() {
		return "pa";
	}
	public static function getNmClassProcesso() {
		return "dbPA";
	}
	
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdPA . "=" . $this->cdPA;
	
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
	
			return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );		
		$query = $nmTabela . "." . self::$nmAtrAnoPA . "=" . $this->anoPA;
			
		return $query;
	}
	
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCdPA,
				self::$nmAtrAnoPA,
				self::$nmAtrAnoDemanda,
				self::$nmAtrCdDemanda,
				self::$nmAtrCdResponsavel,
				self::$nmAtrObservacao,
				self::$nmAtrPublicacao,
				self::$nmAtrDtAbertura,
				self::$nmAtrDtNotificacao,
				self::$nmAtrDtUltNotificacaoParaManifestacao,
				self::$nmAtrDtUltNotificacaoPrazoEncerrado,
				self::$nmAtrNumDiasPrazoUltNotificacao,
				self::$nmAtrSituacao 
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCdPA,
				self::$nmAtrAnoPA 
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPA = $registrobanco [self::$nmAtrCdPA];
		$this->anoPA = $registrobanco [self::$nmAtrAnoPA];
		$this->cdDemanda = $registrobanco [self::$nmAtrCdDemanda];
		$this->anoDemanda = $registrobanco [self::$nmAtrAnoDemanda];
		$this->cdResponsavel = $registrobanco [self::$nmAtrCdResponsavel];
		
		$this->obs = $registrobanco [self::$nmAtrObservacao];
		$this->publicacao = $registrobanco [self::$nmAtrPublicacao];
		$this->dtAbertura = $registrobanco [self::$nmAtrDtAbertura];
		$this->dtNotificacao = $registrobanco [self::$nmAtrDtNotificacao];
		$this->dtUlNotificacaoParaManifestacao = $registrobanco [self::$nmAtrDtUltNotificacaoParaManifestacao];
		$this->dtUlNotificacaoPrazoEncerrado = $registrobanco [self::$nmAtrDtUltNotificacaoPrazoEncerrado];
		$this->numDiasPrazoUltNotificacao = $registrobanco [self::$nmAtrNumDiasPrazoUltNotificacao];
		$this->situacao = $registrobanco [self::$nmAtrSituacao];
		$this->dtPublicacao = $registrobanco [voDemandaTramitacao::$nmAtrDtReferencia];

	}
	function getDadosFormulario() {
		$this->cdPA = @$_POST [self::$nmAtrCdPA];
		$this->anoPA = @$_POST [self::$nmAtrAnoPA];
		$this->cdDemanda = @$_POST [self::$nmAtrCdDemanda];
		$this->anoDemanda = @$_POST [self::$nmAtrAnoDemanda];
		$this->cdResponsavel = @$_POST [self::$nmAtrCdResponsavel];
		
		$this->obs = @$_POST [self::$nmAtrObservacao];
		$this->publicacao = @$_POST [self::$nmAtrPublicacao];
		$this->dtAbertura = @$_POST [self::$nmAtrDtAbertura];
		$this->dtNotificacao = @$_POST [self::$nmAtrDtNotificacao];
		$this->dtUlNotificacaoParaManifestacao = @$_POST [self::$nmAtrDtUltNotificacaoParaManifestacao];
		$this->dtUlNotificacaoPrazoEncerrado = @$_POST [self::$nmAtrDtUltNotificacaoPrazoEncerrado];
		$this->numDiasPrazoUltNotificacao = @$_POST [self::$nmAtrNumDiasPrazoUltNotificacao];
		$this->situacao = @$_POST [self::$nmAtrSituacao];
		
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}
	
	static function getTextoModeloPublicacaoPenalidade($voContrato=null){
		//$voContrato = new vocontrato();
		$codigoContrato = "XXX";
		$nomeEmpresa = "XXX, " .documentoPessoa::$DS_REFERENCIA. " n° XXX";
		
		if($voContrato != null){
			$codigoContrato = $voContrato->getCodigoContratoFormatado();
			
			$colecao = consultarPessoasContrato($voContrato);
			$voPessoa = new vopessoa();
			$voPessoa->getDadosBanco($colecao[0]);
			
			$nomeEmpresa = "$voPessoa->nome, CNPJ/CNPF n° ". documentoPessoa::getNumeroDocFormatado($voPessoa->doc); 				
		}
		
		$diaExtenso = strftime ( '%d de %B de %Y', strtotime ( 'today' ) );
	
		$retorno = constantes::$CD_MODELO_TEXTO . " Contrato: $codigoContrato. Empresa: $nomeEmpresa. Penalidade: XXX. Fundamento: XXX. ";
		$retorno .= "Recurso: desta decisão cabe recurso no prazo 05 dias úteis, contados da intimação do ato, conforme art. 33, do Decreto n° 42.191/2015. ";
		$retorno .= "O Processo encontra-se com vista franqueada na ATJA/SEFAZ, localizada na Avenida Cruz Cabugá, n° 1419, Sala 108, Bairro Santo Amaro, ";
		$retorno .= "Recife/PE, no horário das 09h às 16h. Recife, $diaExtenso. Marcelo José Mendonça de Sá - Superintendente Administrativo e Financeiro da SEFAZ-PE.";
		
		return $retorno;
	}
	
	function toString() {
		$retorno .= $this->anoPA . ",";
		$retorno .= $this->cdPA . ",";
		return $retorno;
	}
	function getValorChavePrimaria() {
		$chave = $this->anoPA;
		$chave .= CAMPO_SEPARADOR . $this->cdPA;
		$chave .= CAMPO_SEPARADOR . $this->sqHist;
		
		return $chave;
	}
	function getChavePrimariaVOExplode($array) {
		$this->anoPA = $array [0];
		$this->cdPA = $array [1];
		$this->sqHist = $array [2];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = "PAAP: " . formatarCodigoAno($this->cdPA, $this->anoPA);
		if ($this->sqHist != null) {
			$retorno .= "<br>Núm. Histórico: " . complementarCharAEsquerda ( $this->sqHist, "0", TAMANHO_CODIGOS );
		}		
		return $retorno;
	}
}
?>