<?php
include_once(caminho_util."dominio.class.php");

  Class dominioSetor extends dominio{
  	static $CD_SETOR_SAFI= 1;
  	static $CD_SETOR_UNCT= 2;
  	static $CD_SETOR_ATJA= 3;

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao(){
		return array(
				self::$CD_SETOR_SAFI => "SAFI",
				self::$CD_SETOR_UNCT => "UNCT",
				self::$CD_SETOR_ATJA => "ATJA"
				);
	}
	
}
?>