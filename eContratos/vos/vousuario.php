<?php
include_once(caminho_lib."voentidade.php");

Class vousuario extends voentidade{
        static $nmEntidade = "wp_users";	
        static $nmAtrID  = "ID";
        static $nmAtrLogin  = "user_login";
        static $nmAtrName  = "user_nicename";
		
		var $id;
		var $login;
		var $name;

// ...............................................................
// Construtor
	function __construct() {
		//exemplo de chamada de construtor da classe pai em caso de override
		//parent::__construct($altura,$grossura,$largura,$cor); 
		$this->varAtributos = array(
            $this->nmAtrID,
            $this->nmAtrLogin,
            $this->nmAtrName
        );
		
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

// ...............................................................
// Funções ( Propriedades e métodos da classe )
		
	function getUsuarioBanco($registrobanco){
		
		$this->id = $registrobanco[vocontrato::$nmAtrID];
		$this->login = $registrobanco[vocontrato::$nmAtrLogin];
		$this->name = $registrobanco[vocontrato::$nmAtrName];
		
	}

}
?>