<?php
include_once (caminho_util . "multiplosConstrutores.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
class voentidade extends multiplosConstrutores {
	static $nmTabelaSufixoHistorico = "_hist";
	static $nmTabelaSufixoSequencial = "_seq";
	static $nmAtrConfirmarNaoInclusaoDeCamposObrigatorios = "ConfirmarNaoInclusaoDeCamposObrigatorios";
	static $nmAtrTemDesativado = "TemDesativado";
	static $nmAtrSqHist = "hist";
	static $nmAtrDhInclusao = "dh_inclusao";
	static $nmAtrDhUltAlteracao = "dh_ultima_alt";
	static $nmAtrDhOperacao = "dh_operacao";
	
	static $nmAtrCdUsuarioInclusao = "cd_usuario_incl";
	static $nmAtrCdUsuarioUltAlteracao = "cd_usuario_ultalt";
	static $nmAtrCdUsuarioOperacao = "cd_usuario_operacao";
	
	static $nmAtrNmUsuarioInclusao = "nm_usuario_incl";
	static $nmAtrNmUsuarioUltAlteracao = "nm_usuario_ultalt";
	static $nmAtrNmUsuarioOperacao = "nm_usuario_operacao";
	static $nmAtrInDesativado = "in_desativado";
	
	//implementar a constante abaixo quando retirar o temTabHistorico das instancias
	//static $existeTabHistorico = false;
	
	var $NM_METODO_RETORNO_CONFIRMAR;
	var $varChaves;
	var $varAtributos;
	var $varAtributosARemover;
	var $varAtributosDBDefault;
	var $temTabHistorico;
	var $dbprocesso = "";
	var $dhInclusao;
	var $dhUltAlteracao;
	var $dhOperacao;
	var $cdUsuarioInclusao;
	var $cdUsuarioOperacao;
	// id_user eh o usuario logado no sistema
	// constante definida em bibliotecaHTML
	var $cdUsuarioUltAlteracao;
	var $nmUsuarioInclusao;
	var $nmUsuarioUltAlteracao;
	var $nmUsuarioOperacao;
	var $sqHist;
	
	function __construct0() {
		self::__construct1 (null);		
	}
	// var $dbprocesso;
	function __construct1($arrayChave = null) {
		// exemplo de chamada de construtor da classe pai em caso de override
		// parent::__construct($altura,$grossura,$largura,$cor);
		$this->varAtributos = array (
				voentidade::$nmAtrDhInclusao,
				voentidade::$nmAtrDhUltAlteracao,
				voentidade::$nmAtrCdUsuarioInclusao,
				voentidade::$nmAtrCdUsuarioUltAlteracao 
		);
		
		$this->cdUsuarioUltAlteracao = id_user;
		$this->NM_METODO_RETORNO_CONFIRMAR = null;
		//CASO NAO TENHA TAB HISTORICO, O USUARIO DE CONTROLE SERA O DE ULTIMA_ALTERACAO
		//O DE INCLUSAO NAO EXISTIRA
		$this->temTabHistorico = true;
		
		if (method_exists ( $this, "getNmClassProcesso" )) {
			$class = static::getNmClassProcesso ();
			$this->dbprocesso = new $class ();
		}

				
		if ($arrayChave != null && method_exists ( $this, "getChavePrimariaVOExplode" )) {
			$this->getChavePrimariaVOExplode ( $arrayChave );
		}
				
		// cria a classe processo para todo vo
		/*
		 * $class = static::getNmClassProcesso();
		 * $this->dbprocesso= new $class();
		 */
	}
	
	// ...............................................................
	// Funcoes ( Propriedades e metodos da classe )
	function getSQLValuesInsertEntidade() {
		$userManutencao = $this->cdUsuarioUltAlteracao;
		if ($this->cdUsuarioInclusao == null || $this->cdUsuarioInclusao == "" || $this->cdUsuarioInclusao == "null"){
			$this->cdUsuarioInclusao = $userManutencao;
		}
				
		$temAtributosParaChecar = $this->varAtributos != null;
		$temUsuarioInc = false;
		$temUsuarioAlt = false;
		$temDtInc = false;
		$temDtAlt = false;
		
		if ($temAtributosParaChecar) {
			$temUsuarioInc = array_search ( self::$nmAtrCdUsuarioInclusao, $this->varAtributos );
			$temUsuarioAlt = array_search ( self::$nmAtrCdUsuarioUltAlteracao, $this->varAtributos );
			$temDtInc = array_search ( self::$nmAtrDhInclusao, $this->varAtributos );
			$temDtAlt = array_search ( self::$nmAtrDhUltAlteracao, $this->varAtributos );
		}
		
		$retorno = "";
		$conector = ",";
		if ($temUsuarioInc) {
			$retorno .= $conector . $this->cdUsuarioInclusao;
			$conector = ",";
			
			 //ECHO "TEM USU INCLUSAO";
		} // ELSE ECHO "NAO TEM USU INCLUSAO";
		
		if ($temUsuarioAlt) {
			$retorno .= $conector . $this->cdUsuarioUltAlteracao;
			//ECHO "TEM USU ALTERACAO";
			// $conector = ",";
		}
		
		return $retorno;
	}
	
	/**
	 *
	 * @deprecated
	 *
	 */
	function getSQLValuesUpdate() {
		$retorno = "";
		$retorno .= self::$nmAtrDhUltAlteracao . " = now() ";
		$retorno .= ",";
		$retorno .= self::$nmAtrCdUsuarioUltAlteracao . " = " . id_user;
		
		//echoo($retorno);
		
		return $retorno;
	}
	function getSQLValuesEntidadeUpdate() {
		$temUsuarioAlt = array_search ( self::$nmAtrCdUsuarioUltAlteracao, $this->varAtributos );
		$temDtAlt = array_search ( self::$nmAtrDhUltAlteracao, $this->varAtributos );
		
		$retorno = "";
		$conector = ",";
		if ($temUsuarioAlt) {
			$retorno .= $conector . self::$nmAtrCdUsuarioUltAlteracao . " = " . id_user;
			$conector = ",";
		}
		if ($temDtAlt) {
			$retorno .= $conector . self::$nmAtrDhUltAlteracao . " = now() ";
		}
		
		return $retorno;
	}
	function getValoresWhereSQL($voEntidade, $colecaoAtributos) {
		$sqlConector = "";
		$retorno = "";
		$nmTabela = $voEntidade->getNmTabelaEntidade ( false );
		
		$tamanho = sizeof ( $colecaoAtributos );
		$chaves = array_keys ( $colecaoAtributos );
		
		for($i = 0; $i < $tamanho; $i ++) {
			$nmAtributo = $chaves [$i];
			$retorno .= $sqlConector . $this->getAtributoValorSQL ( $nmAtributo, $colecaoAtributos [$nmAtributo] );
			$sqlConector = " AND ";
		}
		return $retorno;
	}
	function getAtributoValorSQL($atributo, $valor) {
		return $atributo . " = " . $valor;
	}
	function getDadosFormularioEntidade() {		
		// chama o getdadosformulario do filho
		//SOMENTE DESCOMENTAR QUANDO TODOS OS VOS NAO CHAMAREM O METODO PAI getDadosFormularioEntidade, SE NAO CAUSA DEADLOCK
		//$this->getDadosFormulario ();
		
		// completa com os dados da entidade se existirem
		$this->dhUltAlteracao = @$_POST [self::$nmAtrDhUltAlteracao];
		$this->sqHist = @$_POST [self::$nmAtrSqHist];
		// usuario de ultima manutencao sempre sera o id_user
		$this->cdUsuarioUltAlteracao = id_user;
	}
	function getDadosBancoEntidade($registrobanco) {
		$this->dhInclusao = $registrobanco [voentidade::$nmAtrDhInclusao];
		$this->dhUltAlteracao = $registrobanco [voentidade::$nmAtrDhUltAlteracao];
		
		$this->cdUsuarioInclusao = $registrobanco [voentidade::$nmAtrCdUsuarioInclusao];
		$this->cdUsuarioUltAlteracao = $registrobanco [voentidade::$nmAtrCdUsuarioUltAlteracao];
		$this->sqHist = $registrobanco [voentidade::$nmAtrSqHist];
		// $this->cdHistorico = $registrobanco[voentidade::$nmAtrcdSqHist];
		
		$this->nmUsuarioInclusao = $registrobanco [voentidade::$nmAtrNmUsuarioInclusao];
		$this->nmUsuarioUltAlteracao = $registrobanco [voentidade::$nmAtrNmUsuarioUltAlteracao];
		
		if ($this->sqHist != null) {
			$this->dhOperacao = $registrobanco [voentidade::$nmAtrDhOperacao];
			$this->nmUsuarioOperacao = $registrobanco [voentidade::$nmAtrNmUsuarioOperacao];
		}
	}
	function getDadosBanco($registrobanco) {
		$this->getDadosRegistroBanco ( $registrobanco );
		$this->getDadosBancoEntidade ( $registrobanco );
	}
	// usado para operacoes mais complexas apenas quando se consulta por chave primaria
	function getDadosBancoPorChave($registrobanco) {
		$this->getDadosBanco ( $registrobanco );
		
		// metodo geralmente usado para exibir o objeto em paginas de detalhamento
		// quando necessita de consultas maiores
		// evitando que as consultas nas paginas de consultas sejam pesadas
		if (method_exists ( $this, "getDadosChaveOperacaoMaixComplexa" )) {
			$this->getDadosChaveOperacaoMaixComplexa ( $registrobanco );
		}
	}
	function setaAtributosRemocaoEInclusaoDBDefault($arrayAtribRemover=null, $arrayAtributosDBDefault=null) {
		$this->varAtributos = removeColecaoAtributos ( $this->varAtributos, $arrayAtribRemover );

		$this->varAtributosDBDefault = $arrayAtributosDBDefault;
		$this->varAtributosARemover = $arrayAtribRemover;
	}
	function removeAtributos($arrayAtribRemover) {
		$this->varAtributos = removeColecaoAtributos ( $this->varAtributos, $arrayAtribRemover );
		$this->varAtributosARemover = $arrayAtribRemover;
	}
	function removeTodosAtributosPai() {
		unset ( $this->varAtributos );
	}
	function getTodosAtributos() {
		// metodo da classe filha
		$novosAtributos = $this->getAtributosFilho ();
		$retorno = $novosAtributos;
		// tamanho + 5
		if ($this->varAtributos != null)
			$retorno = array_merge ( $novosAtributos, $this->varAtributos );
		
		return $retorno;
	}
	function getNmTabelaEntidade($isHistorico) {
		/*
		 * $nmTabela = static::getNmTabela();
		 * if($isHistorico)
		 * $nmTabela = self::getNmTabelaHistorico();
		 * return $nmTabela;
		 */
		 if(!$this->temTabHistorico()){
		 	$isHistorico = false;
		 }
		 
		return self::getNmTabelaStatic ( $isHistorico );
	}
	static function getNmTabelaStatic($isHistorico) {
		$nmTabela = static::getNmTabela ();
		if ($isHistorico) {
			$nmTabela = self::getNmTabelaHistorico ();
		}
		return $nmTabela;
	}
	static function getNmTabelaHistorico() {
		return static::getNmTabela () . voentidade::$nmTabelaSufixoHistorico;
	}
	
	/*
	 * static function getNmTabelaSequencial(){
	 * return static::getNmTabela() . voentidade::$nmTabelaSufixoSequencial;
	 * }
	 */
	function isIgualChavePrimaria($voentidade) {
		$chaveEntidade = "";
		
		if ($voentidade != null) {
			// $chaveEntidade = call_user_func_array(array($voentidade,$nmMetodo),array(""));
			$chaveEntidade = $voentidade->getValorChaveLogica ();
		}
		
		$chaveAComparar = $this->getValorChaveLogica ();
				
		 /*echo "chave a comparar:" . $chaveEntidade . "<br>";
		 echo "chave referencia:" . $chaveAComparar . "<br>";*/		 
		
		return $chaveAComparar == $chaveEntidade;
	}
	function getValorChaveHTML() {
		// pega do filho
		return $this->getValorChavePrimaria ();
	}
	function getValorChaveLogica() {
		// pega do filho
		return $this->getValorChavePrimaria ();
	}
	function getNmClassVO() {
		$classProcesso = static::getNmClassProcesso ();
		return str_replace ( "db", "vo", $classProcesso );
	}
	function getVOExplodeChave() {
		$chave = @$_GET ["chave"];
		$this->getChavePrimariaVOExplodeParam ( $chave );
	}
	function getChavePrimariaVOExplodeParam($chave) {
		$array = explode ( CAMPO_SEPARADOR, $chave );
		$this->getChavePrimariaVOExplode ( $array );
	}
	function getAtributosComIdentificacaoTabela($colecaoAtributos, $isHistorico) {
		$retorno = array ();
		foreach ( $colecaoAtributos as $nmAtributo ) {
			$retorno [] = $this->getNmTabelaEntidade ( $isHistorico ) . "." . $nmAtributo;
		}
		return $retorno;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		// via de regra a chave logica eh igual a chave primaria
		// quando for distinta, o metodo getValoresWhereSQLChaveLogica
		// devera ser implementado no vo especifico
		return $this->getValoresWhereSQLChave ( $isHistorico );
	}
	function getTelaRetornoConfirmar() {
		// se a filha nao tiver sobrescrito esse metodo
		// pega o metodo da classe filha via de REGRA
		return $this->getNmTabela ();
	}
	function getMensagemComplementarTelaSucesso() {
		return "";
	}
	function getMensagemComplementarTelaSucessoPadrao($titulo, $cd, $descricao, $sqHistorico = null) {
		$retorno = "$titulo: " . $descricao . " (Código: " . complementarCharAEsquerda ( $cd, "0", TAMANHO_CODIGOS ) . ")";
		if ($sqHistorico != null) {
			$retorno .= "<br>" . "Histórico: " . complementarCharAEsquerda ( $sqHistorico, "0", TAMANHO_CODIGOS );
		}
		return $retorno;
	}
	function isHistorico() {
		return $this->sqHist != null && $this->sqHist != "";
	}
	function temTabHistorico() {
		return $this->temTabHistorico;
	}
	function getValoresWhereSQLChaveComOutraTabela($isHistorico, $nmOutraTabela) {
		$nmTabelaOriginal = $this->getNmTabelaEntidade ( $isHistorico );
		if($nmOutraTabela == null || $nmOutraTabela == ""){
			$nmTabelaOriginal .= ".";
			$nmOutraTabela = "";
		}
		
		return str_replace ($nmTabelaOriginal , $nmOutraTabela, $this->getValoresWhereSQLChave ( $isHistorico ) );
	}
	function getValoresWhereSQLChaveSemNomeTabela($isHistorico) {
		return $this->getValoresWhereSQLChaveComOutraTabela($isHistorico, "");
		//return str_replace ( $this->getNmTabelaEntidade ( $isHistorico ) . ".", "", $this->getValoresWhereSQLChave ( $isHistorico ) );
	}
	static function getCodigoFormatado($codigo) {
		return complementarCharAEsquerda ( $codigo, "0", TAMANHO_CODIGOS );
	}
	static function getCodigoDEscricaoFormatado($codigo, $ds) {
		return static::getCodigoFormatado ( $codigo ) . " - " . $ds;
	}
	static function excluirArquivo($enderecoArquivo) {
		if (file_exists ( $enderecoArquivo )) {
			unlink ( $enderecoArquivo );
		}
	}
	
	function isChavePrimariaPreenchida() {
		$chave = $this->getValorChavePrimaria();
		$chave = str_replace(constantes::$CD_CAMPO_SEPARADOR, "", $chave);
		$chave = str_replace(" ", "", $chave);
		
		$retorno = false;
		if (strlen($chave)>1) {
			$retorno = true;
		}
		
		return $retorno;
	}
	
	static function getArrayComoStringCampoSeparador($array) {		
		if($array == null){
			throw new excecaoGenerica("getArrayComoStringCampoSeparador não deve receber valor nulo.");
		}
		return implode( CAMPO_SEPARADOR, $array );
	}
	
	static function getStringCampoSeparadorComoArray($chave) {		
		$array = explode ( CAMPO_SEPARADOR, $chave );
		return $array;
	}
	/*
	 * function validaExclusaoRelacionamentoHistorico(){
	 * $retorno = false;
	 * //so exclui os relacionamentos se a exclusao for de registro historico
	 * //e nao existir outro registro vigente que possa utilizar os relacionamentos
	 * if($this->isHistorico() && !$this->dbprocesso->existeRegistroVigente($this)){
	 * $retorno = true;
	 * }
	 * return $retorno;
	 * }
	 */
}
?>