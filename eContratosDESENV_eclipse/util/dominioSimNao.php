<?php
include_once(caminho_util. "dominio.class.php");

Class dominioSimNao extends dominio{
// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = self::getColecao();        
	}

	static function getColecao(){
		return array(
				constantes::$CD_SIM => constantes::$DS_SIM,
				constantes::$CD_NAO => constantes::$DS_NAO
		);
	}
	
// ...............................................................
// Funções( Propriedades e metodos da classe )
	
}
?>