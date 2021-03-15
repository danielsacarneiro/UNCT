<?php
include_once(caminho_util."dominio.class.php");

  Class dominioUsuarioCaracteristicas extends dominio{
  	
  	static $CD_CHEFE = "C";
  	static $CD_ATJA = "A";
  	 
  	static $DS_CHEFE = "Chefe";
  	static $DS_ATJA = "ATJA";
  	
// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = self::getColecao();
	}	
// ...............................................................
// Funções ( Propriedades e métodos da classe )

	static function getColecao(){
		return array(
				static::$CD_CHEFE => static::$DS_CHEFE,
				static::$CD_ATJA => static::$DS_ATJA,
		);
	}
			
}
?>