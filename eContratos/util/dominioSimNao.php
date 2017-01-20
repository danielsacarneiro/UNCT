<?php
include_once("dominio.class.php");

  Class dominioSimNao extends dominio{

// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = array(				   
				   constantes::$CD_SIM => constantes::$DS_SIM,
                   constantes::$CD_NAO => constantes::$DS_NAO
				   );        
	}

// ...............................................................
// Funções ( Propriedades e métodos da classe )
	
}
?>