<?php
include_once("dominio.class.php");

  Class dominioSimNao extends dominio{
  	static $DS_SIM  = "SIM";
  	static $DS_NAO  = "N�O";  
  	
  	static $CD_SIM  = "S";
  	static $CD_NAO  = "N";  	 

// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = array(				   
				   self::$CD_SIM => self::$DS_SIM,
				   self::$CD_NAO => self::$DS_NAO
				   );        
	}

// ...............................................................
// Fun��es( Propriedades e metodos da classe )
	
}
?>