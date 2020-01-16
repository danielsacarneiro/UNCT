<?php
include_once (caminho_util . "dominio.class.php");
class dominioUGSolicCompra extends dominio {
		
	static $CD_UG_150101 = 150101;
	static $CD_UG_150110 = 150110;
	static $CD_UG_290301 = 290301;	
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_UG_150101 => self::$CD_UG_150101,
				self::$CD_UG_150110 => self::$CD_UG_150110,
				self::$CD_UG_290301 => self::$CD_UG_290301,
		);
		
		return $retorno;
	}
			
}
?>