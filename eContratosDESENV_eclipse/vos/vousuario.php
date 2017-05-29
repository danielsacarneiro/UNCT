<?php
include_once(caminho_lib."voentidade.php");

Class vousuario extends voentidade{
        static $nmEntidade = "wp_users";	
        static $nmAtrID  = "ID";
        static $nmAtrLogin  = "user_login";
        static $nmAtrName  = "display_name";
		
		var $id;
		var $login;
		var $name;

// ...............................................................
// Construtor
	/*function __construct() {
		//exemplo de chamada de construtor da classe pai em caso de override
		//parent::__construct($altura,$grossura,$largura,$cor); 
		$this->varAtributos = array(
            $this->nmAtrID,
            $this->nmAtrLogin,
            $this->nmAtrName
        );
		
	}*/
	
	function __construct() {
		parent::__construct0();
		//por enquanto nao vai ter historico
		//quando guardarmos mais informacoes do usuario, colocamos historico
		$this->temTabHistorico = false;
		//$class = self::getNmClassProcesso();
		//$this->dbprocesso= new $class();		
	
		//retira os atributos padrao que nao possui
		//remove tambem os que o banco deve incluir default
		$arrayAtribRemover = array(
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao,
				self::$nmAtrCdUsuarioUltAlteracao
		);
		$this->removeAtributos($arrayAtribRemover);
		$this->varAtributosARemover = $arrayAtribRemover;
	}	
	
	public static function getTituloJSP(){
		return  "USU�RIO";
	}
	
	public static function getNmTabela(){
		return  "wp_users";
	}
	
	public static function getNmClassProcesso(){
		return  "dbusuario";
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
				self::$nmAtrID,
				self::$nmAtrLogin,
				self::$nmAtrName
		);
	
		return $retorno;
	}
	
	function getAtributosChavePrimaria(){
		$retorno = array(
				self::$nmAtrID
		);
	
		return $retorno;
	}	
	
// ...............................................................
// Funções ( Propriedades e métodos da classe )
		
	function getUsuarioBanco($registrobanco){
		
		$this->id = $registrobanco[vocontrato::$nmAtrID];
		$this->login = $registrobanco[vocontrato::$nmAtrLogin];
		$this->name = $registrobanco[vocontrato::$nmAtrName];
		
	}

}
?>