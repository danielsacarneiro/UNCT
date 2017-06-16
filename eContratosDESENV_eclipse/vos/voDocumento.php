<?php
include_once (caminho_lib . "voentidade.php");
include_once ("dbDocumento.php");
include_once (caminho_funcoes . "documento/dominioTpDocumento.php");
include_once (caminho_funcoes . "documento/biblioteca_htmlDocumento.php");
include_once (caminho_util . "dominioSetor.php");
class voDocumento extends voentidade {
	static $nmAtrCdSetor = "doc_cd_setor";
	static $nmAtrAno = "doc_ex";
	static $nmAtrSq = "sq";
	static $nmAtrTp = "doc_tp";
	static $nmAtrLink = "doc_link";
	var $sq = "";
	var $cdSetor = "";
	var $ano = "";
	var $tp = "";
	var $link = "";
	var $dbprocesso = "";
	
	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = false;
		$this->dbprocesso = new dbDocumento ();
		
		// retira os atributos padrao que nao possui
		// remove tambem os que o banco deve incluir default
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao,
				self::$nmAtrCdUsuarioInclusao 
		);
		$this->removeAtributos ( $arrayAtribRemover );
		$this->varAtributosARemover = $arrayAtribRemover;
	}
	public static function getTituloJSP() {
		return "DOCUMENTOS";
	}
	public static function getNmTabela() {
		return "documento";
	}
	public static function getNmClassProcesso() {
		return "dbDocumento";
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
		$query = $nmTabela . "." . self::$nmAtrCdSetor . "=" . $this->cdSetor;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrAno . "=" . $this->ano;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrTp . "='" . $this->tp . "'";
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrSq,
				self::$nmAtrCdSetor,
				self::$nmAtrAno,
				self::$nmAtrTp,
				self::$nmAtrLink 
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrSq,
				self::$nmAtrCdSetor,
				self::$nmAtrAno,
				self::$nmAtrTp 
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->sq = $registrobanco [self::$nmAtrSq];
		$this->cdSetor = $registrobanco [self::$nmAtrCdSetor];
		$this->ano = $registrobanco [self::$nmAtrAno];
		$this->tp = $registrobanco [self::$nmAtrTp];
		$this->link = $registrobanco [self::$nmAtrLink];
	}
	function getDadosFormulario() {
		$this->sq = @$_POST [self::$nmAtrSq];
		$this->cdSetor = @$_POST [self::$nmAtrCdSetor];
		$this->ano = @$_POST [self::$nmAtrAno];
		$this->tp = @$_POST [self::$nmAtrTp];
		$this->link = $_POST [self::$nmAtrLink];
		
		$this->dhUltAlteracao = @$_POST [self::$nmAtrDhUltAlteracao];
		$this->sqHist = @$_POST [self::$nmAtrSqHist];
		// usuario de ultima manutencao sempre sera o id_user
		$this->cdUsuarioUltAlteracao = id_user;
	}
	function getEnderecoTpDocumento() {
		$retorno = "";
		
		// $domDoc = new dominioTpDocumento();
		$domSetor = new dominioSetor ();
		$retorno = dominioTpDocumento::getEnderecoPastaBase ();
		if ($this->cdSetor == dominioSetor::$CD_SETOR_UNCT) {
			$retorno = dominioTpDocumento::getEnderecoPastaBaseUNCT ();
		}
		
		if ($this->tp != null) {
			$enderecoTemp = "\\" . dominioTpDocumento::getEnderecoPastaBasePorTpDocumento ( $this->tp );
			
			// excecao
			if ($this->tp == dominioTpDocumento::$CD_TP_DOC_APOSTILAMENTO) {
				$retorno .= "\\ANO $this->ano" . $enderecoTemp;
				$retorno .= "\\";
			} else {
				// regra geral
				$retorno .= dominioTpDocumento::$ENDERECO_PASTA_DOCUMENTOS;
				$retorno .= $enderecoTemp;
				$retorno .= $enderecoTemp . " $this->ano\\";
			}
			
			$retorno .= $this->link;
		}
		
		return $retorno;
	}
	function formatarCodigo($comDescricaoPorExtenso = false) {
		$retorno = self::formatarCodigoDocumento ( $this->sq, $this->cdSetor, $this->ano, $this->tp );
		
		if($comDescricaoPorExtenso)
			$retorno = $this->link;		
		
		return $retorno;
	}
	static function formatarCodigoDocumento($sq, $cdSetor, $ano, $tpDoc) {
		// biblioteca_htmlDocumento
		$retorno = formatarCodigoDocumento ( $sq, $cdSetor, $ano, $tpDoc );		
		return $retorno;
	}
	function toString() {
		$retorno .= "Ano:" . $this->ano . ",";
		$retorno .= "Setor:" . $this->cdSetor . ",";
		$retorno .= "TpDoc:" . $this->tp . ",";
		$retorno .= "Sq:" . $this->sq . ",";
		$retorno .= "Link:" . $this->link . ",";
		return $retorno;
	}
	function getValorChavePrimaria() {
		$separador = CAMPO_SEPARADOR;
		// $separador = "b";
		return $this->ano . $separador . $this->cdSetor . $separador . $this->tp . $separador . $this->sq;
	}
	function getChavePrimariaVOExplode($array) {
		$this->ano = $array [0];
		$this->cdSetor = $array [1];
		$this->tp = $array [2];
		$this->sq = $array [3];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Documento: " . $this->formatarCodigo ();
		return $retorno;
	}
}
?>