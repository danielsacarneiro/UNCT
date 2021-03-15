<?php
include_once(caminho_lib."voentidade.php");
include_once("dbUsuarioInfo.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_util."dominioSetor.php");
include_once (caminho_util."documentoPessoa.php");
include_once (caminho_funcoes . "usuario_info/dominioUsuarioCaracteristicas.php");
 
Class voUsuarioInfo extends vousuario{	
	 
	static $nmAtrSetor = "user_setor";
	static $nmAtrInCaracteristicas = "user_in_caracteristicas";	
	
	var $colecaoSetor = null;
	 
	var $dbprocesso = null;
	var $setor = null;
	var $inCaracteristicas = null;
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )

	//os outros atributos estao na classe PAI vousuario que representa o wp_users wordpress com adaptacoes
	//lembrando que o vousuarioinfo eh um hibrido entre os dados do econti e os dados do wordpress, 
	// dai seu funcionamento distinto
	function __construct($arrayChave = null) {
		parent::__construct ($arrayChave);
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();
		$this->colecaoSetor = array ();
		//para saber se tem historico, consultar a classe pai
	}	
	
	public static function getTituloJSP(){
		return  "USUÁRIO-INFORMAÇÕES ADICIONAIS";
	}

	public static function getNmTabela(){
		return  "usuario_info";
	}

	public static function getNmClassProcesso(){
		return  "dbUsuarioInfo";
	}

	function getValoresWhereSQLChave($isHistorico){
		$nmTabela = $this->getNmTabelaEntidade($isHistorico);
		$query = $nmTabela . "." . self::$nmAtrID . "=" . $this->id;

		if($isHistorico)
			$query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;

			return $query;
	}

	function getAtributosChavePrimaria(){
		$retorno = array(
				self::$nmAtrID
		);
	
		return $retorno;
	}
	
	function getAtributosFilho(){
		$array1 = static::getAtributosChavePrimaria();
		
		$array2 = array (
				self::$nmAtrInCaracteristicas,
		);
		$retorno = array_merge($array1, $array2);
		
		return $retorno;
	}
	
	function getDadosRegistroBanco($registrobanco){
		//as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->id = $registrobanco[self::$nmAtrID];
		$this->login = $registrobanco[self::$nmAtrLogin];
		$this->name  = $registrobanco[self::$nmAtrName];
		$this->setor = $registrobanco[self::$nmAtrSetor];
		$this->inCaracteristicas = $registrobanco[self::$nmAtrInCaracteristicas];
	}

	function getDadosFormulario(){
		$this->id = $_POST[self::$nmAtrID];
		$this->login = $_POST[self::$nmAtrLogin];
		$this->name  = $_POST[self::$nmAtrName];
		
		$this->setor = $this->getColecaoSetorFormulario();		
		$this->inCaracteristicas = static::getAtributoFormularioMultiplosValores($this->inCaracteristicas, self::$nmAtrInCaracteristicas);		
		
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}
	
	/**
	 * Pega os setores da tela e formata para incluir na base
	 * @param unknown $colecao
	 */
	function getColecaoSetorFormulario() {
		$retorno = null;
		$colecao = @$_POST [voUsuarioInfo::$nmAtrSetor];
		
		if ($colecao != null) {
			$colecao = getArrayComItemTamanhoFormatado($colecao);
			$retorno = self::getArrayComoStringCampoSeparador($colecao);			
		}
	
		return $retorno;
	}
	
	
	function setColecaoSetorRegistroBanco() {
		$this->colecaoSetor = static::getStringCampoSeparadorComoArray($this->setor);
	}	
	 
	function toString(){
		$retorno.= $this->id;
		$retorno.= "," . $this->login;
		return $retorno;
	}

	function getValorChavePrimaria(){
		return $this->id;
		}

	function getChavePrimariaVOExplode($array){
		$this->id = $array[0];
		$this->sqHist = $array[1];
	}
	
	function getMensagemComplementarTelaSucesso(){
		$retorno = "Usuário informações adicionais: " . $this->name . " (".complementarCharAEsquerda($this->id, "0", TAMANHO_CODIGOS).")";
		return $retorno; 
	}	

}
?>