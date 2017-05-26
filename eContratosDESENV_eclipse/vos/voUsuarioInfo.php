<?php
include_once(caminho_lib."voentidade.php");
include_once("dbUsuarioInfo.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_util."dominioSetor.php");
include_once (caminho_util."documentoPessoa.php");
 
Class voUsuarioInfo extends vousuario{	
	 
	var $colecaoSetor = null;
	 
	var $dbprocesso = null;
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )

	function __construct() {
		parent::__construct0();
		//por enquanto nao vai ter historico
		//quando guardarmos mais informacoes do usuario, colocamos historico
		$this->temTabHistorico = false;
		$class = self::getNmClassProcesso();
		$this->dbprocesso= new $class();
		
		$this->colecaoSetor = array ();
		
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

	function getAtributosFilho(){
		$retorno = array(
				self::$nmAtrID
		);

		return $retorno;
	}

	function getAtributosChavePrimaria(){
		$retorno = array(
				self::$nmAtrID
		);

		return $retorno;
	}
	
	function getDadosRegistroBanco($registrobanco){
		//as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->id = $registrobanco[self::$nmAtrID];
		$this->login = $registrobanco[self::$nmAtrLogin];
		$this->name  = $registrobanco[self::$nmAtrName];		
	}

	function getDadosFormulario(){
		$this->id = $_POST[self::$nmAtrID];
		$this->login = $_POST[self::$nmAtrLogin];
		$this->name  = $_POST[self::$nmAtrName];
		
		$this->colecaoSetor = $_POST[voUsuarioSetor::$nmAtrCdSetor];
		
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}
	
	function setColecaoSetorRegistroBanco($colecao) {
		$retorno = null;
		if ($colecao != null) {
			$retorno = array ();
			foreach ( $colecao as $registrobanco ) {
				$retorno [] = $registrobanco[voUsuarioSetor::$nmAtrCdSetor];
			}
		}
	
		$this->colecaoSetor = $retorno;
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